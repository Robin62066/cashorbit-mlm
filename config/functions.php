<?php

function base_url($file = '')
{
    global $config;
    $url = $config['base_url'];
    return $url . $file;
}

function site_url($file = '')
{
    global $config;
    $url = $config['base_url'];
    return $url . $file;
}

function admin_url($file = '')
{
    global $config;
    $url = $config['base_url'];
    return $url . $config['admin_folder'] . '/' . $file;
}

function current_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return $protocol . $host . $uri;
}

function redirect($url, $msg = '', $msg_type = 'info')
{
    if ($msg != '') {
        $key = $msg_type . "_msg";
        set_flashdata($key, $msg);
    }
    header('location: ' . $url);
    die;
}

function appload()
{
    include ROOT_PATH . '/config/autoload.php';
}

function upload_dir($file = '')
{
    global $config;
    $url = $config['upload_folder'] . DIRECTORY_SEPARATOR;
    return $url . $file;
}

function do_upload($name)
{
    $file_name = '';
    if (isset($_FILES[$name]['name']) && $_FILES[$name]['name'] != '') {
        $extension = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
        // $chkFolder = upload_dir(date("Y/m/"));
        // if (!is_dir($chkFolder)) {
        //     mkdir($chkFolder);
        // }
        $file_name = time() . '-' . bin2hex(random_bytes(10)) . '.' . $extension;
        $target_path =  upload_dir($file_name);
        @move_uploaded_file($_FILES[$name]['tmp_name'], $target_path);
    }
    return $file_name;
}

function input_post($name)
{
    return isset($_POST[$name]) ? $_POST[$name] : null;
}

function input_get($name)
{
    return isset($_GET[$name]) ? $_GET[$name] : null;
}


function set_value($name, $default_value = '')
{
    return isset($_POST[$name]) ?? $default_value;
}

function segment(int $index)
{
    $routes = explode('/', trim($_SERVER['PATH_INFO'], '/'));
    return $routes[$index - 1] ?? null;
}

function render(bool $success, string $message = '',  $data = null,  int $response_code = 200)
{
    http_response_code($response_code);
    if ($message == null) $message = '';
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
}

function is_admin_login()
{
    $user = userdata('admin');
    return $user !== null;
}

function id2userid($id)
{
    $prefix = "CB";
    $sid = $prefix .  str_pad($id, 6, '0', STR_PAD_LEFT);
    return $sid;
}

function userid2id($sid)
{
    $prefix = "CB";
    $sid = substr($sid, strlen($prefix));
    return intval($sid);
}
