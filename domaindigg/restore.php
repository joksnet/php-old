<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( Session::$logged )
    header("Location: {$config['root']}dd/");

$title = __('RestoreTitle');
$error = array();

if ( isset($_GET['email']) && isEmail($_GET['email']) )
    $email = $_GET['email'];

if ( !( empty($_POST) ) )
{
    $email = $_POST['email'];

    if ( !( isEmail($email) ) )
        $error['email'] = true;
    else
    {
        $uid = Db::fetchOne(
            "SELECT uid FROM users
             WHERE email = '$email'"
        );

        if ( empty($uid) )
            $error['email'] = true;
    }

    if ( empty($error) )
    {
        $password = '';

        for ( $i = 0; $i < 8; $i++ )
            $password .= rand(0, 9);

        Db::update('users', array(
            'password' => md5($password)
        ), "uid = '$uid'");

        /**
         * @todo Send email.
         */

        header("Location: {$config['root']}signin/?email=$email");
    }
}

require_once "$root/themes/$theme/restore.php";