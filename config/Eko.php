<?php
class Eko
{
    // const BASE_URL = 'https://staging.eko.in/ekoapi/v1/'; //Test Url
    // private $initiator_id = '9962981729'; // Test Id
    // private $developer_key = 'becbbce45f79c6f5109f848acd540567'; // Test key
    // private $auth_pass = "f46fd59b-bc90-41e2-9a52-efb5c0778d50";
    // private $user_code = 20810200;

    const BASE_URL = 'https://api.eko.in/ekoicici/v1/'; // Live Url
    private $initiator_id = '8433812226';
    private $developer_key = 'b7f4c2a9341c8828ac9afb9d97b27471'; // Live
    private $auth_pass = "cb1013cc-109b-46ea-ae6c-6d4bb2d74918"; // Live auth pass
    private $user_code = '34922001';

    private $secret_key;
    private $secret_key_timestamp;

    const CASH_WITHDRAW = 2;
    const BALANCE_ENQUIRY = 3;
    const MINI_STATEMENT = 4;

    function onboard($first_name, $last_name, $pan_number, $dob, $email, $mobile, $shop_name, $address)
    {
        $url = self::BASE_URL . "user/onboard";
        $data = [];
        $data['initiator_id'] = $this->initiator_id;
        $data['pan_number'] = $pan_number;
        $data['mobile'] = $mobile;
        $data['first_name'] = $first_name;
        $data['last_name'] = $last_name;
        $data['email']  = $email;
        $data['residence_address'] = $address; // in json object
        $data['dob'] = $dob;
        $data['shop_name'] = $shop_name;
        $post = http_build_query($data);

        $this->setKeyTimeStamp();

        $header = [];
        $header[] = "Content-Type: application/x-www-form-urlencoded";
        $header[] = "developer_key: " . $this->developer_key;
        $header[] = "secret-key: " . $this->secret_key;
        $header[] = "secret-key-timestamp: " . $this->secret_key_timestamp;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $resp = curl_exec($ch);
        return $resp;
    }

    private function setKeyTimeStamp()
    {
        // Encode it using base64
        $encodedKey = base64_encode($this->auth_pass);
        $secret_key_timestamp = "" . round(microtime(true) * 1000);
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);

        // Encode it using base64
        $secret_key = base64_encode($signature);
        $this->secret_key = $secret_key;
        $this->secret_key_timestamp = $secret_key_timestamp;
    }

    public static function getKeyTimeStamp()
    {
        $ek = new Eko();
        $ek->setKeyTimeStamp();

        $ob = new stdClass();
        $ob->key = $ek->secret_key;
        $ob->timestamp = $ek->secret_key_timestamp;
        return $ob;
    }

    public static function ekoFormattedAddress($line, $city, $state, $pincode)
    {
        $ob = new stdClass();
        $ob->line = $line;
        $ob->city = $city;
        $ob->state = $state;
        $ob->pincode = $pincode;
        return json_encode($ob);
    }

    function activateService($user_code, $service_code)
    {
        $url = self::BASE_URL . 'user/service/activate';
        $this->setKeyTimeStamp();

        $header = [];
        $header[] = "Content-Type: multipart/form-data";
        $header[] = "developer_key: " . $this->developer_key;
        $header[] = "secret-key: " . $this->secret_key;
        $header[] = "secret-key-timestamp: " . $this->secret_key_timestamp;

        $data = [];
        $data['initiator_id'] = $this->initiator_id;
        $data['service_code'] = $service_code;
        $data['user_code'] = $user_code;
        $post = http_build_query($data);
        $post = array(
            'form-data' => $post,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $result = curl_exec($ch);

        $ob = json_decode($result);
        return $ob;
    }

    function verify_pan($pan_number)
    {
        $url = self::BASE_URL . "pan/verify";
        $data = [];
        $data['initiator_id'] = $this->initiator_id;
        $data['pan_number'] = $pan_number;
        $data['purpose'] = 1;
        $data['purpose_desc'] = "biharsewa signup";
        return $this->run($url, $data);
    }

    function aadharInit($aadhar_number, $name)
    {
        // $url = 'https://staging.eko.in/ekoapi/external/getAdhaarConsent';
        $url = 'https://api.eko.in/ekoicici/v1/external/getAdhaarConsent';
        $data = [];
        $data['source'] = 'NEWCONNECT';
        $data['initiator_id'] = $this->initiator_id;
        $data['is_consent'] = "Y";
        $data['consent_text'] = $aadhar_number;
        $data['name'] = $name;
        $data['user_code'] = $this->user_code;
        $data['realsourceip'] = '223.190.111.58';

        $this->setKeyTimeStamp();
        $header = [];
        $header[] = "accept: application/json";
        $header[] = "developer_key: " . $this->developer_key;
        $header[] = "secret-key: " . $this->secret_key;
        $header[] = "secret-key-timestamp: " . $this->secret_key_timestamp;

        $url .= '?' . http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $result = curl_exec($ch);
        $ob = json_decode($result);
        return $ob;
    }

    function getAadharOTP($aadhar_number, $access_key)
    {
        // $url = 'https://staging.eko.in/ekoapi/external/getAdhaarConsent';
        $url = 'https://api.eko.in/ekoicici/v1/external/getAdhaarOTP';
        $data = [];
        $data['source'] = 'NEWCONNECT';
        $data['initiator_id'] = $this->initiator_id;
        $data['is_consent'] = "Y";
        $data['aadhar'] = $aadhar_number;
        $data['caseId'] = $aadhar_number;
        $data['user_code'] = $this->user_code;
        $data['realsourceip'] = '223.190.111.58';
        $data['access_key'] = $access_key;

        $this->setKeyTimeStamp();
        $header = [];
        $header[] = "accept: application/json";
        $header[] = "developer_key: " . $this->developer_key;
        $header[] = "secret-key: " . $this->secret_key;
        $header[] = "secret-key-timestamp: " . $this->secret_key_timestamp;

        $url .= '?' . http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $result = curl_exec($ch);
        $ob = json_decode($result);
        return $ob;
    }

    function getAadharDetails($aadhar_number, $access_key,  $otp)
    {
        $url = 'https://api.eko.in/ekoicici/v1/external/getAdhaarFile';
        $data = [];
        $data['source'] = 'NEWCONNECT';
        $data['initiator_id'] = $this->initiator_id;
        $data['is_consent'] = "Y";
        $data['aadhar'] = $aadhar_number;
        $data['caseId'] = $aadhar_number;
        $data['user_code'] = $this->user_code;
        $data['realsourceip'] = '223.190.111.58';
        $data['access_key'] = $access_key;
        $data['otp'] = $otp;
        $data['share_code'] = rand(1111, 9999);

        $this->setKeyTimeStamp();
        $header = [];
        $header[] = "accept: application/json";
        $header[] = "developer_key: " . $this->developer_key;
        $header[] = "secret-key: " . $this->secret_key;
        $header[] = "secret-key-timestamp: " . $this->secret_key_timestamp;

        $url .= '?' . http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $result = curl_exec($ch);
        $ob = json_decode($result);
        return $ob;
    }

    private function sendPayment($user_code, $txn_id, $bank_ac_name, $bank_ac_number, $bank_ifsc, $amount)
    {
        $url = self::BASE_URL . "agent/user_code:$user_code/settlement";

        $data = [];
        $data['initiator_id'] = $this->initiator_id;
        $data['client_ref_id'] = $txn_id;
        $data['service_code'] = 45;
        $data['payment_mode'] = 5;
        $data['recipient_name'] = $bank_ac_name;
        $data['account'] = $bank_ac_number;
        $data['ifsc'] = $bank_ifsc;
        $data['amount'] = $amount;
        $data['sender_name'] = "Transmitto Development";
        $data['beneficiary_account_type'] = 1;

        return $this->run($url, $data);
    }

    private function run($url, $data)
    {
        $post = http_build_query($data);
        $this->setKeyTimeStamp();
        $header = [];
        $header[] = "Content-Type: application/x-www-form-urlencoded";
        $header[] = "developer_key: " . $this->developer_key;
        $header[] = "secret-key: " . $this->secret_key;
        $header[] = "secret-key-timestamp: " . $this->secret_key_timestamp;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        $ob = json_decode($result);
        return $ob;
    }
}
