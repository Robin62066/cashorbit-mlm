<?php

class User extends Core
{

    function __construct()
    {
        parent::__construct();
    }

    function login(string $username, string $password)
    {
        $user = $this->db->select("ai_users", ['username' => $username, 'passwd' => $password], 1, false, "AND", "id, username, first_name, last_name, ac_status")->row();
        if (is_object($user)) {
            return $user;
        } else {
            return false;
        }
    }

    public function isRegisteredEmail(string $email_id): bool
    {
        $user = $this->db->select("ai_users", ['email_id' => $email_id], 1, false, "AND", "id")->row();
        if (is_object($user)) {
            return true;
        } else {
            return false;
        }
    }
    public function isRegisteredMobile(string $mobile): bool
    {
        $user = $this->db->select("ai_users", ['mobile' => $mobile], 1, false, "AND", "id")->row();
        if (is_object($user)) {
            return true;
        } else {
            return false;
        }
    }

    public function createSignup(string $sponsor, string $first_name, string $last_name, string $email_id, string $mobile, int $position, $Password): RestApi
    {
        $api = new RestApi();
        $checkuser = $this->getUserFromUname($sponsor);
        if (!$checkuser) {
            $api->setError("user Not Found");
            return $api;
        }

        //   $checkEmail = $this->isRegisteredEmail($email_id);
        //     if ($checkEmail) {
        //         $api->setError("Email Id already registered");
        //         return $api;
        //     }

        //     $checkMobile = $this->isRegisteredMobile($mobile);
        //     if ($checkMobile) {
        //         $api->setError("Mobile no already registered");
        //         return $api;
        //     }  

        $sb                = [];
        $sb['id']          = $id = User::getRandomUserId();
        $sb['username']    = "CB" . $id;
        $sb['first_name']  = $first_name;
        $sb['last_name']   = $last_name;
        $sb['mobile']      = $mobile;
        $sb['email_id']    = $email_id;
        $sb['created']     = date("Y-m-d H:i:s");
        $sb['passwd']      = $Password;
        $vari              = $this->getAutoSponsorExtremeLeftRight($checkuser->id, $position);
        $sb['sponsor_id']  = $checkuser->id;
        $sb['placement_id'] = $vari->placement_id;
        $sb['position']    = $vari->position;
        $sb['status']      = 1;
        $this->db->insert("ai_users", $sb);

        // Add Magic Wallet Balance
        $this->creditWallet($id, 1500, FUND_DEPOSITE, 0, "SELF-ACTIVATION");
        $api->setData($sb);
        $api->setOK("success");
        return $api;
    }

    public function forgotPassword(string $email): bool
    {
        $user = $this->db->select('ai_users', ['email_id' => $email], 1, false, "AND", 'id')->row();
        if (is_object($user)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserinfo(int $id)
    {
        $ab = $this->db->select('ai_users', ['id' => $id], 1)->row();
        $ab->plan_total = '-';
        $topup = $this->db->select("ai_topup_history", ['user_id' => $id], 1)->row();
        if (is_object($topup)) {
            if ($topup->act_info == 1) $ab->plan_total = 1499;
            if ($topup->act_info == 2) $ab->plan_total = 2999;
        }
        return $ab;
    }

    public function getUserFromUname(string $uname)
    {
        $ab = $this->db->select('ai_users', ['username' => $uname], 1)->row();
        return $ab;
    }

    public function getMainBalance(int $user_id)
    {
        $sql = "SELECT sum(amount) as total FROM ai_transaction WHERE user_id ='$user_id' AND cr_dr = 'cr'";
        $sumCr = (float) $this->db->query($sql)->row()->total;
        $sql = "SELECT sum(amount) as total FROM ai_transaction WHERE user_id ='$user_id' AND cr_dr = 'dr'";
        $sumDr = (float) $this->db->query($sql)->row()->total;

        $bal = $sumCr - $sumDr;
        return $bal;
    }

    public function getFundBalance(int $user_id)
    {
        $sql = "SELECT sum(amount) as total FROM ai_fund WHERE user_id ='$user_id' AND cr_dr = 'cr'";
        $sumCr = (float) $this->db->query($sql)->row()->total;
        $sql = "SELECT sum(amount) as total FROM ai_fund WHERE user_id ='$user_id' AND cr_dr = 'dr'";
        $sumDr = (float) $this->db->query($sql)->row()->total;

        $bal = $sumCr - $sumDr;
        return $bal;
    }
    public function getWalletBalance(int $user_id)
    {
        $sql = "SELECT sum(amount) as total FROM ai_wallet WHERE user_id ='$user_id' AND cr_dr = 'cr'";
        $sumCr = (float) $this->db->query($sql)->row()->total;
        $sql = "SELECT sum(amount) as total FROM ai_wallet WHERE user_id ='$user_id' AND cr_dr = 'dr'";
        $sumDr = (float) $this->db->query($sql)->row()->total;

        $bal = $sumCr - $sumDr;
        return $bal;
    }

    public function getActivationPlans(int $plan_id = 0)
    {
        $items = [];
        $items[] = ['label' => ' Rs 1500/-', 'value' => 1500, 'plan_id' => 1, 'status' => true];

        if ($plan_id > 0) {
            return $items[$plan_id - 1] ?? null;
        }
        return $items;
    }

    public function isPlanActive(int $user_id, int $plan_id)
    {
        $item = $this->db->select("ai_topup_history", ['user_id' => $user_id, 'act_info' => $plan_id], 1)->row();
        return is_object($item);
    }

    public function activateAccount(int $user_id, int $plan_id, int $activated_by, string $wallet)
    {
        // update users table'
        $user = $this->getUserinfo($user_id);
        if (!$user->ac_active_date) {
            $sb = [];
            $sb['ac_status'] = 1;
            $sb['ac_active_date'] = date("Y-m-d H:i:s");
            $this->db->update('ai_users', $sb, ['id' => $user_id], 1);
        } else {
            $sb              = [];
            $sb['ac_status'] = $plan_id;
            $sb['plan_id']   = $plan_id;
            $this->db->update('ai_users', $sb, ['id' => $user_id], 1);
        }

        // maintain topup history
        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['act_type'] = $wallet;
        $sb['act_info'] = $plan_id;
        $sb['topup_by'] = $activated_by;
        $sb['created']  = date("Y-m-d H:i:s");
        $this->db->insert("ai_topup_history", $sb);

        // Send Level income
        $this->sendLevelIncome($user_id);
        $this->addUserplans($user_id, $plan_id);
        $this->add2UniversalPool($user_id);
        $this->upgradeRank($user->sponsor_id);

        // Debit Magic Wallet
        $this->debitMagicBalance($user->sponsor_id);
    }

    public function upgradeRank(int $sponsor_id)
    {

        $sql = "SELECT COUNT(*) AS total FROM ai_users WHERE sponsor_id = $sponsor_id AND ac_status = 1";
        $ob = $this->db->query($sql)->row();
        if (is_object($ob) && $ob->total >= 10) {
            // Upgrade Rank
            $this->db->update('ai_users', ['user_rank' => 1], ['id' => $sponsor_id], 1);
        }
    }

    function sendSponsorIncome(int $user_id, float $amount)
    {
        $netPay = $amount * 0.05;
        $sp = $this->db->select("ai_users", ['id' => $user_id], 1, false, "AND", "sponsor_id")->row();
        $sp_id = $sp->sponsor_id;
        $comment = "ACTIVATION/$user_id";
        $this->credit($sp_id, $netPay, INCOME_SPONSOR, $user_id, $comment);
    }

    public function credit(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '', string $txn_id = ''): int
    {
        if ($txn_id != '') {
            $chk = $this->db->query("SELECT id FROM ai_transaction WHERE txn_id = '$txn_id' LIMIT 1")->row();
            if (is_object($chk)) return $chk->id;
        } else {
            $dt     = date("YmdHis");
            $txn_id = "$user_id-$notes-$dt-$ref_id";
        }
        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['amount']   = $amount;
        $sb['notes']    = $notes;
        $sb['cr_dr']    = 'cr';
        $sb['created']  = date("Y-m-d H:i:s");
        $sb['ref_id']   = $ref_id;
        $sb['comments'] = $comment;
        $sb['txn_id']   = $txn_id;
        $this->db->insert("ai_transaction", $sb);

        return $this->db->id();
    }

    public function debit(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '', string $txn_id = ''): int
    {
        if ($txn_id != '') {
            $chk = $this->db->query("SELECT id FROM ai_transaction WHERE txn_id = '$txn_id' LIMIT 1")->row();
            if (is_object($chk)) return $chk->id;
        } else {
            $dt     = date("YmdHis");
            $txn_id = "$user_id-$notes-$dt-$ref_id";
        }

        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['amount']   = $amount;
        $sb['notes']    = $notes;
        $sb['cr_dr']    = 'dr';
        $sb['created']  = date("Y-m-d H:i:s");
        $sb['ref_id']   = $ref_id;
        $sb['comments'] = $comment;
        $sb['txn_id']   = $txn_id;
        $this->db->insert("ai_transaction", $sb);

        return $this->db->id();
    }

    public function creditFund(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '')
    {

        $sb = [];
        $sb['user_id'] = $user_id;
        $sb['amount'] = $amount;
        $sb['notes'] = $notes;
        $sb['cr_dr'] = 'cr';
        $sb['created'] = date("Y-m-d H:i:s");
        $sb['ref_id'] = $ref_id;
        $sb['comments'] = $comment;
        $this->db->insert("ai_fund", $sb);
    }

    public function debitFund(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '')
    {
        $sb = [];
        $sb['user_id'] = $user_id;
        $sb['amount'] = $amount;
        $sb['notes'] = $notes;
        $sb['cr_dr'] = 'dr';
        $sb['created'] = date("Y-m-d H:i:s");
        $sb['ref_id'] = $ref_id;
        $sb['comments'] = $comment;
        $this->db->insert("ai_fund", $sb);
    }



    public function withdrawBalance(int $user_id, int $amount)
    {
        $fee = ($amount * WITHDRAWAL_FEE) / 100;

        // withdraw table me data insert
        $sb = [];
        $sb['user_id'] = $user_id;
        $sb['amount'] = $amount;
        $sb['paid_total'] = $amount - $fee;
        $sb['status'] = 0;
        $sb['created'] = date("Y-m-d H:i:s");
        $this->db->insert("ai_withdraw", $sb);
        $ref_id = $this->db->id();
        // debit entry in transaction table
        $this->debit($user_id, $amount, "withdraw", $ref_id, "self withdrawal");
    }
    public function fundTransfer(int $user_id, float $amount, string $fundtype, int $myid)
    {
        //first check if fundtype is main then we have to dr amount elseif fund type is fund then dr amount from fund table

        if ($fundtype == "main") {
            $this->debit($myid, $amount, FUND_TRANSFER, $user_id, 'P2P TO CB' . $user_id);
            $this->credit($user_id, $amount, FUND_TRANSFER, $myid, 'P2P FROM CB' . $myid);
        } else if ($fundtype == "fund") {
            $this->debitFund(
                $myid,
                $amount,
                FUND_TRANSFER,
                $user_id,
                'P2P TO CB' . $user_id
            );
            $this->creditFund($user_id, $amount, FUND_TRANSFER, $myid, 'P2P FROM CB' . $myid);
        }
    }

    // public function kycUpdate($user_id, $adhar_front, $adhar_back, $pan): bool
    // {
    //     $sb = [];
    //     if (isset($_FILES['adhar_front']['name']) && $_FILES['adhar_front']['name'] != '') {
    //         $sb['adhar_front'] = do_upload($adhar_front);
    //     }
    //     if (isset($_FILES['adhar_back']['name']) && $_FILES['adhar_back']['name'] != '') {
    //         $sb['adhar_back'] = do_upload($adhar_back);
    //     }
    //     if (isset($_FILES['pan']['name']) && $_FILES['pan']['name'] != '') {
    //         $sb['pan'] = do_upload($pan);
    //     }
    //     $sb['kyc_status'] = 1;
    //     $sb['kyc_updated'] = 1;

    //     $this->db->insert("ai_users", $sb, ['id' => $user_id]);
    //     return true;
    // }

    //user update-profle
    public function updateProfile(int $user_id, string $last_name, string $email, string $city, string $district, $address, string $bank_name, string $bank_ac_name, string $bank_ac_number, string $bank_ifsc, string $bank_branch, string $pin_code = '', $state = 0): bool
    {
        $sb                   = [];
        $sb['last_name']      = $last_name;
        $sb['email_id']       = $email;
        $sb['city_name']      = $city;
        $sb['district']       = $district;
        $sb['address']        = $address;
        $sb['bank_name']      = $bank_name;
        $sb['bank_ac_name']   = $bank_ac_name;
        $sb['bank_ac_number'] = $bank_ac_number;
        $sb['bank_ifsc']      = $bank_ifsc;
        $sb['bank_branch']    = $bank_branch;
        $sb['pin_code']       = $pin_code;
        $sb['state']          = $state;
        $this->db->update("ai_users", $sb, ['id' => $user_id], 1);
        return true;
    }

    public  function getAutoSponsorExtremeLeftRight($placement_id, $position = 1)
    {
        $sp = $this->db->select('ai_users', ['placement_id' => $placement_id, 'position' => $position], 1)->row();

        $ob             = new stdClass();
        $ob->position   = $position;
        $ob->placement_id = 0;

        if (is_object($sp)) {
            $ob = $this->getAutoSponsorExtremeLeftRight($sp->id, $position);
        } else {
            $ob->placement_id = $placement_id;
        }
        return $ob;
    }

    public static function  getRandomUserId()
    {
        $db = db_connect();
        $id = rand(100000, 999999);
        $chk = $db->select('ai_users', ['id' => $id], 1)->row();
        if (!is_object($chk)) {
            return $id;
        } else {
            return User::getRandomUserId();
        }
    }
    public static function getRandomPassword()
    {
        $db = db_connect();
        $pass = rand(100000, 999999);
        $chk = $db->select('ai_users', ['passwd' => $pass], 1)->row();
        if (!is_object($chk)) {
            return $pass;
        } else {
            return User::getRandomPassword();
        }
    }

    public function activeRecharge(int $user_id, int $plan_id): void
    {
        $getu = $this->getUserinfo($user_id);
        $user = $this->db->select("ai_topup_history", ["user_id" => $user_id], 1)->row();
        $days = 0;
        if ($plan_id == 1) {
            for ($i = 1; $i <= 5; $i++) {
                $ab = [];
                $ab['user_id'] = $getu->id;
                $ab['amount'] = 300;
                $ab['status'] = 0;
                $ab['next_recharge'] = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($user->created)));
                $this->db->insert('ai_recharges', $ab);
                $days += 60;
            }
        } else if ($plan_id == 2) {
            for ($i = 1; $i <= 12; $i++) {
                $ab = [];
                $ab['user_id'] = $getu->id;
                $ab['amount'] = 300;
                $ab['status'] = 0;
                $ab['next_recharge'] = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($user->created)));
                $this->db->insert('ai_recharges', $ab);
                $days += 30;
            }
        }
    }
    public function rechargeInfo(int $user_id)
    {
        $items = $this->db->select("ai_recharges", ['user_id' => $user_id])->result();
        $arr = [];
        foreach ($items as $item) {
            if (date('Y-m-d') >= date("Y-m-d", strtotime($item->next_recharge))) {
                $item->disable = 1;
                if ($item->status == 1 || $item->status == 2) {
                    $item->disable = 0;
                }
            } else {
                $item->disable = 0;
            }
            $arr[] = $item;
        }
        return $arr;
    }
    public function getRecharge(int $id, int $mobile, string $operator): bool
    {
        $ab                  = [];
        $ab['recharge_date'] = date("Y-m-d H:i:s");
        $ab['mobile']        = $mobile;
        $ab['operator']      = $operator;
        $ab['status']        = 2;
        $ab['disable']       = 0;
        $this->db->update('ai_recharges', $ab, ['id' => $id]);
        return true;
    }

    public function placementTree(int $user_id, $ids = [])
    {
        $ab = $this->db->select("ai_users", ['id' => $user_id], 1, false, "AND", "id, placement_id")->row();
        if ($ab->placement_id == 0) return $ids;
        if (is_object($ab)) {
            $ids[] = $ab->placement_id;
            $ids = $this->placementTree($ab->placement_id, $ids);
        }
        return $ids;
    }

    public function sponsorTree(int $user_id, $ids = [])
    {
        $ab = $this->db->select("ai_users", ['id' => $user_id], 1, false, "AND", "id, sponsor_id")->row();
        if ($ab->sponsor_id == 0) return $ids;
        if (is_object($ab)) {
            $ids[] = $ab->sponsor_id;
            $ids = $this->sponsorTree($ab->sponsor_id, $ids);
        }
        return $ids;
    }

    public function isActiveId(int $user_id): bool
    {
        $sql = "SELECT id, ac_status FROM ai_users WHERE id = '$user_id' LIMIT 1";
        $ob = $this->db->query($sql)->row();
        return is_object($ob) && $ob->ac_status == 1;
    }

    public function sendLevelIncome(int $user_id, int $plan_id = 1)
    {
        $rates = [50, 30, 10, 10, 10, 10, 5, 5];
        if ($plan_id == 2) {
            $rates = [100, 60, 30, 20, 10, 10, 5, 5];
        }
        $ids = $this->sponsorTree($user_id);
        $ids = array_splice($ids, 0, 8);
        foreach ($ids as $index => $id) {
            $level = $index + 1;
            $amt = $rates[$index];
            $comments = "LEVEL $level/$user_id";

            if ($this->isActiveId($id)) {
                $this->credit($id, $amt, INCOME_LEVEL, $user_id, $comments);
            }
        }
    }

    public function addUserplans(int $user_id, int $plan_id): int
    {
        $days = $plan_id == 1 ? 120 : 300; // No of days

        $sb                = [];
        $sb['user_id']     = $user_id;
        $sb['plan_id']     = $plan_id;
        $sb['daily_roi']   = $plan_id == 1 ? 7.50 : 18; // Rs
        $sb['amount']      = $plan_id == 1 ? 1499 : 2999;
        $sb['matching']    = 0;
        $sb['capping']     = 0;
        $sb['start_dt']    = date("Y-m-d H:i:s", strtotime("+1 day"));
        $sb['end_dt']      = date("Y-m-d H:i:s", strtotime("+$days  days"));
        $sb['status']      = 1;
        $sb['created']     = date("Y-m-d H:i:s");
        $this->db->insert('ai_userplans', $sb);
        $id = $this->db->id();
        return $id;
    }

    public function getDirectChilds($user_id)
    {
        $ob       = new stdClass();
        $ob->left = $ob->right = null;
        $rest = $this->db->select('ai_level_manager', ['sponsor_id' => $user_id], false, false, 'AND', 'user_id, position')->result();
        foreach ($rest as $row) {
            if ($row->position == 1) {
                $ob->left = $row->user_id;
            } else {
                $ob->right = $row->user_id;
            }
        }
        return $ob;
    }

    function levelMembers($user_id, $depth = 1, $level_id = 1)
    {
        $data = [];
        for ($i = 0; $i <= 15; $i++) {
            $data[] = [];
        }
        $data[0] = [$user_id];
        for ($lvl = 1; $lvl <= 15; $lvl++) {
            $ar = [];
            foreach ($data[$lvl - 1] as $id) {
                if ($id == 0 || $id == null) continue;
                $ids = $this->getMatrixDirectChilds($id, $level_id);
                $newids = [];
                foreach ($ids as $tid) {
                    if ($tid == null) continue;
                    $newids[] = $tid;
                }
                $ar = array_merge($ar, $newids);
            }
            $data[$lvl] = $ar;
            $ar = [];
        }
        return $data[$depth];
    }

    public function autoSponsorLeftRight(int $level = 1)
    {
        $ob = new stdClass();
        $ob->sponsor_id = 0;
        $ob->position = 1;
        $childs = pow(2, $level);
        $sql = "SELECT * FROM ai_level_manager WHERE level_id = '$level' ORDER BY id ASC";
        $users = $this->db->query($sql)->result();
        foreach ($users as $user) {
            $sql = "SELECT COUNT(*) AS count FROM ai_level_manager WHERE sponsor_id = {$user->user_id}  AND level_id = '$level'";
            $count = (int) $this->db->query($sql)->row()->count;
            if ($count < $childs) {
                $ob->sponsor_id = $user->user_id;
                $ob->position = ($count + 1);
                break;
            }
        }
        return $ob;
    }

    public function add2UniversalPool(int $user_id, int $level = 1)
    {
        $sp               = $this->autoSponsorLeftRight($level);
        $sb               = [];
        $sb['user_id']    = $user_id;
        $sb['sponsor_id'] = $sp->sponsor_id;
        $sb['position']   = $sp->position;
        $sb['created']    = date("Y-m-d H:i:s");
        $sb['level_id']   = $level;
        $this->db->insert("ai_level_manager", $sb);

        // check latest position
        $spon_id = $sp->sponsor_id;
        $sql = "SELECT MAX(level_id) as level_id FROM ai_level_manager WHERE user_id = '$spon_id' LIMIT 1";
        $ab = $this->db->query($sql)->row();
        if ($ab->level_id == 1) {
            // Credit pool income
            $this->credit($sp->sponsor_id, 15, INCOME_AUTOPOOL, $user_id, "AUTOPOOL/$user_id");
            if ($sp->position == 2) {
                $next = $level + 1;
                $this->add2UniversalPool($sp->sponsor_id, $next);
            }
        } else if ($ab->level_id == 2) {
            // Credit pool income
            $this->credit($sp->sponsor_id, 20, INCOME_AUTOPOOL, $user_id, "AUTOPOOL/$user_id");
            if ($sp->position == 4) {
                $next = $level + 1;
                $this->add2UniversalPool($sp->sponsor_id, $next);
            }
        } else if ($ab->level_id == 3) {
            // Credit pool income
            $this->credit($sp->sponsor_id, 75, INCOME_AUTOPOOL, $user_id, "AUTOPOOL/$user_id");
            if ($sp->position == 8) {
                $next = $level + 1;
                $this->add2UniversalPool($sp->sponsor_id, $next);
            }
        } else if ($ab->level_id == 4) {
            // Credit pool income
            $this->credit($sp->sponsor_id, 375, INCOME_AUTOPOOL, $user_id, "AUTOPOOL/$user_id");
            if ($sp->position == 16) {
                $next = $level + 1;
                $this->add2UniversalPool($sp->sponsor_id, $next);
            }
        } else if ($ab->level_id == 5) {
            // Credit pool income
            $this->credit($sp->sponsor_id, 3750, INCOME_AUTOPOOL, $user_id, "AUTOPOOL/$user_id");
            if ($sp->position == 16) {
                $next = $level + 1;
                $this->add2UniversalPool($sp->sponsor_id, $next);
            }
        } else if ($ab->level_id == 6) {
            // Credit pool income
            $this->credit($sp->sponsor_id, 200000, INCOME_AUTOPOOL, $user_id, "AUTOPOOL/$user_id");
        }
    }

    //get the sponser two childs
    function getTwoChild($user_id)
    {
        $user = $this->db->select("ai_users", ['placement_id' => $user_id], false, false, "AND", "id, position")->result();
        $ob = new stdClass();
        $ob->left = $ob->right = null;
        foreach ($user as $obr) {
            if ($obr->position == 1) $ob->left = $obr->id;
            if ($obr->position == 2) $ob->right = $obr->id;
        }
        return $ob;
    }
    function getDownloadLineIds($user_id, $ids = array())
    {
        $user = $this->db->select("ai_users", ['placement_id' => $user_id], false, false, "AND", "id")->result();
        foreach ($user as $obr) {
            $ids[] = $obr->id;
            $ids   = $this->getDownloadLineIds($obr->id, $ids);
        }
        return $ids;
    }

    function getUplineIds($user_id, $ids = array())
    {
        $user = $this->db->select("ai_users", ['id' => $user_id], false, false, "AND", "sponsor_id")->row();
        if (is_object($user)) {
            if ($user->sponsor_id > 0) {
                $ids[] = $user->sponsor_id;
                $ids = $this->getUplineIds($user->sponsor_id, $ids);
            }
        }
        return $ids;
    }

    function getDirectLineIds($user_id, $ids = array()): array
    {
        $user = $this->db->select("ai_users", ['sponsor_id' => $user_id], false, false, "AND", "id")->result();
        foreach ($user as $obr) {
            $ids[] = $obr->id;
            $ids   = $this->getDirectLineIds($obr->id, $ids);
        }
        return $ids;
    }

    public function getTotalMemers(int $user_id)
    {
        $data = $this->getTwoChild($user_id);
        $totalLeft = [];
        $totalRight = [];
        if ($data->left) {
            $totalLeft = $this->getDownloadLineIds($data->left);
            $totalLeft[] =  $data->left;
        }
        if ($data->right) {
            $totalRight = $this->getDownloadLineIds($data->right);
            $totalRight[] =  $data->right;
        }
        // $countTsLeft = Count($totalLeft)+ 1;
        // $countTsRight = Count($totalRight) + 1;
        $grand = array_merge($totalLeft, $totalRight);
        return $grand;
    }

    public function CheckMembers(int $user_id)
    {
        $data = $this->getTwoChild($user_id);
        $totalLeft = [];
        $totalRight = [];
        if ($data->left) {
            $totalLeft = $this->getDownloadLineIds($data->left);
            $totalLeft[] =  $data->left;
        }
        if ($data->right) {
            $totalRight = $this->getDownloadLineIds($data->right);
            $totalRight[] =  $data->right;
        }
        $grand = array_merge($totalLeft, $totalRight);
        $ar = [];
        $count = 0;
        foreach ($grand as $tl) {
            $users = $this->db->select("ai_users", ['id' => $tl], false, false, "AND", "first_name, last_name, username, sponsor_id, mobile, position, created")->row();
            $ar[$count] = $users;
            $count++;
        }
        return $ar;
    }

    function getMatrixDirectChilds($parent_id)
    {
        $childs = $this->db->select('ai_users', ['placement_id' => $parent_id])->result();
        $ids = array();
        $ids[0] = null;
        $ids[1] = null;
        foreach ($childs as $ob) {
            if ($ob->position == 1) {
                $ids[0] = $ob->id;
            } else {
                $ids[1] = $ob->id;
            }
        }
        return $ids;
    }

    function getMatrixDownlineIds($parent_id, $level_id = 1)
    {
        $data = array();
        $ids = $this->getMatrixDirectChilds($parent_id, $level_id);
        for ($i = 0; $i < 15; $i++) {
            $data[] = null;
        }
        $data[0] = $parent_id;
        $data[1] = isset($ids[0]) ? $ids[0] : null;
        $data[2] = isset($ids[1]) ? $ids[1] : null;

        if (isset($data[1])) {
            $ids = $this->getMatrixDirectChilds($data[1], $level_id);
            $data[3] = isset($ids[0]) ? $ids[0] : null;
            $data[4] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[2])) {
            $ids = $this->getMatrixDirectChilds($data[2], $level_id);
            $data[5] = isset($ids[0]) ? $ids[0] : null;
            $data[6] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[3])) {
            $ids = $this->getMatrixDirectChilds($data[3], $level_id);
            $data[7] = isset($ids[0]) ? $ids[0] : null;
            $data[8] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[4])) {
            $ids = $this->getMatrixDirectChilds($data[4], $level_id);
            $data[9] = isset($ids[0]) ? $ids[0] : null;
            $data[10] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[5])) {
            $ids = $this->getMatrixDirectChilds($data[5], $level_id);
            $data[11] = isset($ids[0]) ? $ids[0] : null;
            $data[12] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[6])) {
            $ids = $this->getMatrixDirectChilds($data[6], $level_id);
            $data[13] = isset($ids[0]) ? $ids[0] : null;
            $data[14] = isset($ids[1]) ? $ids[1] : null;
        }
        return $data;
    }

    public function getMemberTree(int $user_id): array
    {

        $ids              = array();
        $ob               = new stdClass();
        $ob->id           = null;
        $ob->name         = '-';
        $ob->image        = base_url('assets/img/user_inactive.png');
        $ob->username     = '-';
        $ob->mobile       = '-';
        $ob->designation  = "-";
        $ob->doj          = "-";
        $ob->dot          = "-";
        $ob->sponsor_id   = '-';
        $ob->sponsor_name = '-';
        $ob->plan         = '-';
        $ob->matching     = '-';
        $ob->placement_id = '-';
        $data = array();

        $ids = $this->getMatrixDownlineIds($user_id);


        foreach ($ids as $id) {
            if ($id == null) {
                $data[] = $ob;
            } else {

                $u = $this->db->select('ai_users', ['id' => $id], 1, false, "AND", 'username, first_name, last_name, mobile, created, ac_active_date, ac_status, plan_total, matching, sponsor_id, placement_id, plan_id')->row();

                $su = $this->db->select('ai_users', ['id' => $id], 1, false, "AND", 'username, first_name, last_name')->row();

                if ($u->sponsor_id == 0) {
                    $sname = null;
                } else {
                    $sname = $su->first_name . ' ' . $su->last_name;
                }

                $p              = new stdClass();
                $p->id          = $id;
                $p->name        = $u->first_name . ' ' . $u->last_name;
                $p->username    = $u->username;
                $p->mobile      = $u->mobile;
                $p->designation = "Distributor";
                $p->doj         = date("d M, Y h:i:s A", strtotime($u->created));
                $p->image       = "user_inactive.png";

                if ($u->ac_status == 1) {
                    $p->image = base_url('assets/img/user_active.png');
                    $p->dot = date("d M, Y h:i:s A", strtotime($u->ac_active_date));
                } else {
                    $p->dot = '-';
                    $p->image = base_url('assets/img/user_inactive.png');
                }
                $p->sponsor_id   = id2userid($u->sponsor_id);
                $p->sponsor_name = $sname;
                $p->plan         = '-';
                if ($u->plan_id == 1)  $p->plan = 1499;
                if ($u->plan_id == 2)  $p->plan = 2999;
                $p->matching     = $u->matching;

                // get placemet id
                $p->placement_id = id2userid($u->placement_id);
                $data[] = $p;
            }
        }
        return $data;
    }

    public function mainTofundTransfer(int $user_id, float $amount, int $fundtype)
    {
        $last_id = $this->debit($user_id, $amount, FUND_TRANSFER, $user_id, M2F);
        if ($fundtype == 1) {
            $this->creditFund($user_id, $amount * 0.95, FUND_TRANSFER, $last_id, M2F);
        } else if ($fundtype == 2) {
            $this->creditRechargeFund($user_id, $amount * 0.95, FUND_TRANSFER, $last_id, "RECHARGE FUND");
        }
    }

    function creditRechargeFund(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = ''): int
    {
        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['amount']   = $amount;
        $sb['notes']    = $notes;
        $sb['cr_dr']    = 'cr';
        $sb['created']  = date("Y-m-d H:i:s");
        $sb['ref_id']   = $ref_id;
        $sb['comments'] = $comment;
        $this->db->insert("ai_recharge_fund", $sb);

        $last_id = $this->db->id();
        return $last_id;
    }

    function debitRechargeFund(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = ''): int
    {
        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['amount']   = $amount;
        $sb['notes']    = $notes;
        $sb['cr_dr']    = 'dr';
        $sb['created']  = date("Y-m-d H:i:s");
        $sb['ref_id']   = $ref_id;
        $sb['comments'] = $comment;
        $this->db->insert("ai_recharge_fund", $sb);

        $last_id = $this->db->id();
        return $last_id;
    }

    public function creditFundToRecharge(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '')
    {
        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['amount']   = $amount * 0.90;
        $sb['notes']    = $notes;
        $sb['cr_dr']    = 'cr';
        $sb['created']  = date("Y-m-d H:i:s");
        $sb['ref_id']   = $ref_id;
        $sb['comments'] = $comment;
        $this->db->insert("ai_recharge_fund", $sb);
    }

    public function creditFundFromFund(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '')
    {

        $sb = [];
        $sb['user_id'] = $user_id;
        $sb['amount'] = $amount * 0.95;
        $sb['notes'] = $notes;
        $sb['cr_dr'] = 'cr';
        $sb['created'] = date("Y-m-d H:i:s");
        $sb['ref_id'] = $ref_id;
        $sb['comments'] = $comment;
        $this->db->insert("ai_fund", $sb);
    }

    public function getIncomeByType(int $user_id, string $notes): float
    {
        $sql = "SELECT SUM(amount) as total FROM ai_transaction WHERE notes = '$notes' AND user_id = '$user_id'";
        $bal = (float) $this->db->query($sql)->row()->total;
        return $bal;
    }

    public function isEligibleForMatching(int $user_id): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM ai_users WHERE sponsor_id = '$user_id'";
        $total = (int) $this->db->query($sql)->row()->total;
        if ($total < 2) {
            return false;
        } else {
            $childs = $this->getTwoChild($user_id);
            if ($childs->left == null || $childs->right == null) {
                return false;
            } else {
                $leftChilds = $this->getDownloadLineIds($childs->left);
                $leftChilds[] = $childs->left;

                $rightChilds = $this->getDownloadLineIds($childs->right);
                $rightChilds[] = $childs->right;

                $leftExists = $rightExists = false;

                $directMembers = $this->getDirectLineIds($user_id);
                foreach ($directMembers as $uid) {
                    if (in_array($uid, $leftChilds)) {
                        $leftExists = true;
                        break;
                    }
                }

                foreach ($directMembers as $uid) {
                    if (in_array($uid, $rightChilds)) {
                        $rightExists = true;
                        break;
                    }
                }

                return $leftExists && $rightExists;
            }
        }
    }

    function getPairMatching($user_id, $date = '')
    {
        $date    = $date     == '' ? date("Y-m-d") : $date;
        $direct  = $this->getTwoChild($user_id);
        $leftIds = $rightIds  = [];
        if ($direct->left) {
            $leftIds   = $this->getDownloadLineIds($direct->left);
            $leftIds[] = $direct->left;
        }
        if ($direct->right) {
            $rightIds   = $this->getDownloadLineIds($direct->right);
            $rightIds[] = $direct->right;
        }

        $laIds = $raIds = 0;
        if (count($leftIds) > 0) {
            $user_ids = implode(',', $leftIds);
            $sql = "SELECT COUNT(*) AS total FROM ai_users WHERE id IN ($user_ids) AND ac_status = 1 AND DATE(ac_active_date) = '$date'";
            $laIds = (int)$this->db->query($sql)->row()->total;
        }

        if (count($rightIds) > 0) {
            $user_ids = implode(',', $rightIds);
            $sql = "SELECT COUNT(*) AS total FROM ai_users WHERE id IN ($user_ids) AND ac_status = 1 AND DATE(ac_active_date) = '$date'";
            $raIds = (int)$this->db->query($sql)->row()->total;
        }

        $ob = new stdClass();
        $ob->left = (int)$laIds;
        $ob->right = (int)$raIds;

        return $ob;
    }

    public function singlePersonMatching(int $user_id, string $calDate): int
    {
        $ob = $this->getPairMatching($user_id, $calDate);
        if ($ob->left == 0 && $ob->right == 0) return 0;

        $sql = "SELECT plan_id FROM ai_users WHERE id = '$user_id' LIMIT 1";
        $me = $this->db->query($sql)->row();
        $plan_id = $me->plan_id;

        // Last day carry report
        $lastMatch = 0;
        $sql = "SELECT * FROM ai_reports WHERE DATE(created) < '$calDate' AND user_id = '$user_id' LIMIT 1";
        $lastDayReport = $this->db->query($sql)->row();

        $lc = $ob->left;
        $rc = $ob->right;

        if (is_object($lastDayReport)) {
            $lc = $ob->left + $lastDayReport->left_carry;
            $rc =  $ob->right + $lastDayReport->right_carry;
            $lastMatch = $lastDayReport->matching;
        }

        $lcr      = $rcr = 0;
        $matching = 0;
        $laps     = 0;

        $save                   = array();
        $save['user_id']        = $user_id;
        $save['left_count']     = $ob->left;
        $save['right_count']    = $ob->right;
        $save['matching']       = 0;
        $save['laps']           = 0;
        $save['plan_total']     = 0;
        $save['report_created'] = date("Y-m-d H:i:s");
        $save['created']        = $calDate;
        $save['slot']           = 1;

        if ($lc >= $rc) {
            $rcr = 0;
            $lcr = $lc - $rc;
            $matching = $rc;
        } else {
            $lcr = 0;
            $rcr = $rc - $lc;
            $matching = $lc;
        }

        $save['left_carry'] = $lcr;
        $save['right_carry'] = $rcr;

        // Check left right matching exists
        $chkLR  = $this->isEligibleForMatching($user_id);
        if ($chkLR == false) {
            $laps     = $matching;
            $matching = 0;
        }

        $save['laps']           = $laps;
        $save['matching']       = $matching;
        $save['plan_total']     = $matching + $lastMatch;
        $save['report_created'] = date("Y-m-d H:i");

        $txn_id = date("Ymd", strtotime($calDate)) . '-' . $user_id;

        // Check duplicate entry
        $sql = "SELECT * FROM ai_reports WHERE txn_id = '$txn_id' LIMIT 1";
        $dpChk = $this->db->query($sql)->row();
        if (!is_object($dpChk)) {
            $save['txn_id'] = $txn_id;
            $this->db->insert('ai_reports', $save);

            if ($plan_id == 1) $rate = 80;
            else $rate = 180;
            $rate = 6;

            $total = $matching * $rate;
            if ($total == 0) return 0;

            $capAmt = $rate * 10; //10 times of rate
            if ($total > $capAmt) {
                $total = $capAmt;
            }
            $this->credit($user_id, $total, INCOME_MATCHING, 0, "MATCHING $matching", $txn_id);

            // Increment Total matching
            $sql = "UPDATE ai_users SET matching = matching + $matching WHERE id = $user_id LIMIT 1";
            $this->db->query($sql);
        }

        return $matching;
    }

    public function activateAccountByLoan(int $user_id, int $plan_id = 1, int $activated_by = 0, string $wallet = "loan")
    {
        // update users table
        $sb = [];
        $sb['ac_status'] = 1;
        $sb['ac_active_date'] = date("Y-m-d H:i:s");
        $sb['plan_id'] = $plan_id;
        $this->db->update('ai_users', $sb, ['id' => $user_id], 1);

        // maintain topup history
        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['act_type'] = $wallet;
        $sb['act_info'] = $plan_id;
        $sb['topup_by'] = $activated_by;
        $sb['created']  = date("Y-m-d H:i:s");
        $this->db->insert("ai_topup_history", $sb);
    }

    public function activateBoosting(int $user_id): int
    {

        $sql = "SELECT id FROM ai_boosting ORDER BY id DESC LIMIT 1";
        $lastItem = $this->db->query($sql)->row();
        $sponsor_id = is_object($lastItem) ? $lastItem->id : 0;

        $sb               = [];
        $sb['user_id']    = $user_id;
        $sb['sponsor_id'] = $sponsor_id;
        $sb['created']    = date("Y-m-d H:i:s");
        $sb['level_id']   = 1;
        $sb['status']     = 1;
        $sb['position']   = 1;
        $this->db->insert("ai_boosting", $sb);

        $sql = "SELECT COUNT(*) as total FROM ai_boosting";
        $total = (int) $this->db->query($sql)->row()->total;
        if ($total % 3 === 0) {
            // Extract top 1st
            $sql = "SELECT * FROM ai_boosting WHERE status = 1 ORDER BY id ASC LIMIT 1";
            $user = $this->db->query($sql)->row();

            // Credit user
            $txn_id = "{$user->id}-boosting";
            $this->credit($user->user_id, 200, INCOME_BOOSTING, $user_id, "BOOSTING BY $user_id", $txn_id);

            // Distable
            $this->db->update('ai_boosting', ['status' => 0], ['id' => $user->id], 1);

            // Check for 13th retopup
            $sql = "SELECT COUNT(*) as total FROM ai_boosting WHERE user_id = {$user->user_id}";
            $total = (int)$this->db->query($sql)->row()->total;
            if ($total <= 12) {

                // Re-entry 
                $this->activateBoosting($user->user_id);
            }
        }

        return $this->db->id();
    }

    function creditWallet(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '')
    {
        $sb                = [];
        $sb['user_id']     = $user_id;
        $sb['amount']      = $amount;
        $sb['notes']       = $notes;
        $sb['cr_dr']       = 'cr';
        $sb['created']     = date("Y-m-d H:i:s");
        $sb['ref_id']      = $ref_id;
        $sb['comments']    = $comment;
        $sb['wallet_type'] = 1;                    // Magic wallet
        $sb['status']      = 1;
        $this->db->insert("ai_wallet", $sb);
    }

    function debitWallet(int $user_id, float $amount, string $notes, int $ref_id = 0, string $comment = '')
    {
        $sb                = [];
        $sb['user_id']     = $user_id;
        $sb['amount']      = $amount;
        $sb['notes']       = $notes;
        $sb['cr_dr']       = 'dr';
        $sb['created']     = date("Y-m-d H:i:s");
        $sb['ref_id']      = $ref_id;
        $sb['comments']    = $comment;
        $sb['wallet_type'] = 1;                    // Magic wallet
        $sb['status']      = 1;
        $this->db->insert("ai_wallet", $sb);
    }

    public function getSponsor(int $user_id)
    {
        $sql = "SELECT sponsor_id FROM ai_users WHERE id = '$user_id' LIMIT 1";
        $ob  = $this->db->query($sql)->row();
        return $ob->sponsor_id;
    }

    public function debitMagicBalance(int $user_id, int $ref_id = 0)
    {
        // Check magic balance
        $bal = $this->getWalletBalance($user_id);
        if ($bal > 0) {
            // Check direct child
            $sql = "SELECT COUNT(*) AS total FROM ai_users WHERE sponsor_id = '$user_id' AND ac_status = 1";
            $childs = $this->db->query($sql)->row()->total;
            if ($childs == 1) {
                // Credit 100
                $this->debitWallet($user_id, 100, FUND_TRANSFER, $ref_id, "1st MAGIC BALANCE");
                $this->credit($user_id, 100, INCOME_MAGIC, 0, "1st MAGIC BALANCE");

                // Get Sponsor
                $sponsor = $this->getSponsor($user_id);
                $bal1 = $this->getWalletBalance($sponsor);
                if ($bal1 > 0) {
                    $this->debitWallet($sponsor, 200, FUND_TRANSFER, $ref_id, "2nd Level Magic");
                    $this->credit($sponsor, 200, INCOME_MAGIC, 0, "2nd Level Magic");
                }
            } else if ($childs == 2) {
                // Credit 200
                $this->debitWallet($user_id, 200, FUND_TRANSFER, $ref_id, "2nd MAGIC BALANCE");
                $this->credit($user_id, 200, INCOME_MAGIC, 0, "2nd MAGIC BALANCE");
            } else if ($childs == 4) {
                // Credit 600
                $this->debitWallet($user_id, 600, FUND_TRANSFER, $ref_id, "4th MAGIC BALANCE");
                $this->credit($user_id, 600, INCOME_MAGIC, 0, "4th MAGIC BALANCE");
            }
        }
    }

    public function sendROILabelIncome(int $user_id, float $amount, int $ref_id, ?string $comments = '')
    {
        $rates = [7, 9, 6, 5, 4, 3, 2, 1, 1, 1];
        $ids = $this->getUplineIds($user_id);
        $dt = date("Ymd");
        foreach ($ids as $index => $id) {
            $netAmt = $amount * $rates[$index] / 100;
            $txn_id = "roi-level-$ref_id-$index-$dt";
            $comments .= '/' . ($index + 1);
            $this->credit($id, $netAmt, INCOME_ROI_LEVEL, $ref_id, $comments, $txn_id);
        }
    }

    public function submitRecharge(int $user_id, int $amount, string $mobile, string $operator): array
    {
        $sb             = [];
        $sb['user_id']  = $user_id;
        $sb['amount']   = $amount;
        $sb['mobile']   = $mobile;
        $sb['operator'] = $operator;
        $sb['status']   = 0;
        $sb['created']  = date("Y-m-d H:i:s");
        $sb['updated']  = null;
        $this->db->insert("ai_recharge_req", $sb);
        $insert_id = $this->db->id();

        $debit_id = $this->debitRechargeFund($user_id, $amount, "recharge", $insert_id, "RECHARGE/$mobile");
        $res = $this->doRecharge($mobile, $amount, $operator, $insert_id);
        if (is_object($res)) {
            if ($res->Status == "SUCCESS") {
                $sb = [];
                $sb['OPID'] = $res->OPID;
                $sb['RBID'] = $res->RBID;
                $sb['MSG']  = $res->MSG;
                $sb['status'] = 1;
                $sb['updated'] = date("Y-m-d H:i:s");
                $this->db->update('ai_recharge_req', $sb, ['id' => $insert_id]);

                // Apply cashback
                $this->creditRechargeCommission($user_id, $amount, $insert_id);
                return ['success' => true, 'message' => 'Recharge Successful', 'data' => $insert_id];
            } else if ($res->Status == "FAILED") {
                $this->creditRechargeFund($user_id, $amount, FUND_TRANSFER, $debit_id, "RECHARGE FAILED");
            }
        } else {
            $this->creditRechargeFund($user_id, $amount, FUND_TRANSFER, $debit_id, "RECHARGE FAILED");
        }
        return ['success' => false, 'message' => 'Recharge failed.'];
    }


    public function doRecharge(string $mobile, float $amount, string $operator, int $ref_id)
    {
        $opt = 0;
        if (ucwords(strtolower($operator)) == 'Airtel') {
            $opt = 1;
        } else if (ucwords(strtolower($operator)) == 'Idea') {
            $opt = 2;
        } else if (ucwords(strtolower($operator)) == 'Vodafone') {
            $opt = 3;
        } else if (ucwords(strtolower($operator)) == 'Jio') {
            $opt = 4;
        } else {
            die("Operator not supported by Api Provider");
        }
        $url             = 'https://ecppanonline.com/api/RechargeWebService/apitransaction?';
        $data            = [];
        $data['api_key'] = 'ea7ee3-015245-dabc55-4e4c7f-6c6191';
        $data['mobile']  = $mobile;
        $data['opt']     = $opt;
        $data['amount']  = $amount;
        $data['agentid'] = '1010' . $ref_id;

        $param = http_build_query($data);
        $url .= $param;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $resp = curl_exec($ch);
        file_put_contents("result.html", $resp);
        curl_close($ch);
        return json_decode($resp);
    }

    public function  creditRechargeCommission(int $user_id, float $amount, int $ref_id): void
    {

        $selfCashback = $amount * 0.005; // self credit
        $uplineCashback = $amount * 0.001; // upline credit

        $tree = $this->getUplineIds($user_id);
        $ids = array_splice($tree, 0, 10);

        $this->creditRechargeFund($user_id, $selfCashback, RECHARGE_CASHBACK, $ref_id, "SELF CASHBACK");
        foreach ($ids as $index =>  $id) {
            $level = ($index + 1);
            $this->creditRechargeFund($id, $uplineCashback, RECHARGE_CASHBACK, $ref_id, "LEVEL $level CASHBACK");
        }
    }

    public function getRechargeFundBalance(int $user_id): float
    {
        $sql = "SELECT sum(amount) as total FROM ai_recharge_fund WHERE user_id ='$user_id' AND cr_dr = 'cr'";
        $sumCr = (float) $this->db->query($sql)->row()->total;
        $sql = "SELECT sum(amount) as total FROM ai_recharge_fund WHERE user_id ='$user_id' AND cr_dr = 'dr'";
        $sumDr = (float) $this->db->query($sql)->row()->total;

        $bal = $sumCr - $sumDr;
        return number_format($bal, 2, '.', '');
    }
}
