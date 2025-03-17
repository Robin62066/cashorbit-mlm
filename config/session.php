<?php

function set_flashdata($key, $value)
{
    $_SESSION[$key] = $value;
}

function flashdata($key)
{
    $msg = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    unset($_SESSION[$key]);
    return $msg;
}

function set_userdata($key, $value)
{
    $_SESSION[$key] = $value;
}

function userdata($key)
{
    $msg = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    return $msg;
}

function session()
{
    return new MYSession();
}

class MYSession
{

    function set_flashdata($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    function set_userdata($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    function flashdata($key)
    {
        $msg = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        unset($_SESSION[$key]);
        return $msg;
    }

    function userdata($key)
    {
        $msg = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        return $msg;
    }

    function set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    function remove($key)
    {
        unset($_SESSION[$key]);
    }

    function __get($name)
    {
        return $_SESSION[$name] ?? null;
    }

    function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    function destroy()
    {
        session_destroy();
    }
}
