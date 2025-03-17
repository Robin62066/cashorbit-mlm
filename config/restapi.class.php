<?php
class RestApi
{
    var $success = false;
    var $fields = null;
    var $message = '';
    var $data = null;

    function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
    }

    public function setOK($message = '')
    {
        $this->success = true;
        if ($message != '') {
            $this->message = $message;
        }
    }

    public function setError($message = '')
    {
        $this->success = false;
        if ($message != '') {
            $this->message = $message;
        }
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setMessage($msg)
    {
        $this->message = $msg;
    }

    public function checkINPUT($keys, $data = [])
    {
        $this->fields = implode(',', $keys);
        $flag = true;
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                if (strpos($key, '.')) {
                    $ar = explode('.', $key);
                    if (!isset($data[$ar[0]][$ar[1]])) {
                        $flag = false;
                    }
                } else {
                    if (!isset($data[$key])) {
                        $flag = false;
                    }
                }
            }
        }
        if ($flag == false) {
            $this->missing();
        }
        return $flag;
    }

    public function checkPOST($keys)
    {
        $this->fields = implode(',', $keys);
        $flag = true;
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                if (!isset($_POST[$key])) {
                    $flag = false;
                }
            }
        }
        if ($flag == false) {
            $this->missing();
        }
        return $flag;
    }

    public function check($keys)
    {
        $this->fields = implode(',', $keys);
        $flag = true;
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                if (!isset($_GET[$key])) {
                    $flag = false;
                }
            }
        }
        if ($flag == false) {
            $this->missing();
        }
        return $flag;
    }

    public function missing()
    {
        $this->setError("Required Parameter Missing !!");
    }

    public function render()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this);
    }

    function get($name)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        } else {
            $this->missing($name);
            $this->render();
            die;
        }
    }

    function post($name)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        } else {
            $this->missing($name);
            $this->render();
            die;
        }
    }

    function input($name)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data[$name])) {
            return $data[$name];
        } else {
            $this->missing($name);
            $this->render();
            die;
        }
    }
}
