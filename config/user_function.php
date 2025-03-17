<?php

function is_login()
{
    $user = userdata('user');
    return $user !== null;
}

function user_id()
{
    $user = userdata('user');
    return $user !== null ? $user->id : null;
}

function is_admin_login()
{
    $user = userdata('admin');
    return $user !== null;
}

function admin_id()
{
    $user = userdata('admin');
    return $user !== null ? $user->id : null;
}

function db_user($user_id)
{
    $db = db_connect();
    $user = $db->select('ai_users', ['id' => $user_id], 1)->row();
    if (is_object($user)) {
        $user->name = $user->first_name . ' ' . $user->last_name;
        $user->is_verified = $user->kyc_status == 1 ? true : false;
        return $user;
    } else {
        return null;
    }
}

function user_type_string($user_type)
{
    $str = $user_type;
    if ($user_type == USER_CUSTOMER) $str = 'Customer';
    elseif ($user_type == USER_LAND_OWNER) $str = 'Land Owner';
    elseif ($user_type == USER_BROKER) $str = 'Broker';
    elseif ($user_type == USER_MUNSI) $str = 'Munsi';
    elseif ($user_type == USER_AMIN) $str = 'Amin';
    elseif ($user_type == USER_CO) $str = 'CO';
    elseif ($user_type == USER_SDO) $str = 'SDO';
    elseif ($user_type == USER_BHUMI_LOCKER) $str = 'Bhumi Locker';
    elseif ($user_type == USER_LABOUR) $str = 'Labours';
    elseif ($user_type == USER_BRICKS_MFG) $str = 'Bricks Mfg.';
    elseif ($user_type == USER_SAND_SUPPLIER) $str = 'Sand Supplier';
    return $str;
}

function businessAccountType($acType)
{
    $str = '-';
    if ($acType == 1) $str = 'Individual';
    elseif ($acType == 2) $str = 'Sole Proprietorship';
    elseif ($acType == 3) $str = 'Partnership Firm';
    elseif ($acType == 4) $str = 'Private Limited Company';
    return $str;
}


function add_mutation_data($app_id, $meta_name, $meta_value)
{
    $db = db_connect();
    $item = $db->select('ai_mutation_data', ['meta_name' => $meta_name, 'app_id' => $app_id], 1)->row();
    if (is_object($item)) {
        $sb = [];
        $sb['meta_name']  = $meta_name;
        $sb['meta_value'] = $meta_value;
        $db->update('ai_mutation_data', $sb, ['id' => $item->id]);
    } else {
        $sb = [];
        $sb['app_id'] = $app_id;
        $sb['meta_name']  = $meta_name;
        $sb['meta_value'] = $meta_value;
        $sb['created'] = date("Y-m-d H:i:s");
        $db->insert('ai_mutation_data', $sb);
    }
}

function getPermission()
{
    // return new Permission($_SESSION['admin']->permissions);
}
