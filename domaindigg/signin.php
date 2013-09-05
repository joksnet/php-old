<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( Session::$logged )
    header("Location: {$config['root']}dd/");

$title = __('SignInTitle');
$error = array();

if ( isset($_GET['email']) && isEmail($_GET['email']) )
    $email = $_GET['email'];

if ( !( empty($_POST) ) )
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ( !( isEmail($email) ) ) $error['email'] = true;
    if ( strlen($password) < 6 ) $error['password'] = true;

    if ( empty($error) )
    {
        $data = Db::fetchRow(
            "SELECT uid, email, password
             FROM users
             WHERE email = '$email'
               AND password = MD5('$password')"
        );

        if ( empty($data) )
            $error['signin'] = true;
    }

    if ( empty($error) && $data )
    {
        Session::register($data);

        if ( isset($_POST['next']) )
            header("Location: {$_POST['next']}");
        else
            header("Location: {$config['root']}dd/");
    }
}

require_once "$root/themes/$theme/signin.php";