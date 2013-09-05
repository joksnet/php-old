<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( Session::$logged )
    header("Location: {$config['root']}dd/");

$title = __('SignUpTitle');
$error = array();

if ( isset($_GET['email']) && isEmail($_GET['email']) )
    $email = $_GET['email'];

if ( !( empty($_POST) ) )
{
    $email = strtolower( $_POST['email'] );
    $password = $_POST['password'];
    $passwordagain = $_POST['passwordagain'];
    $captcha = strtolower( $_POST['captcha'] );

    if ( !( Session::captchaCompare('signup', $captcha) ) ) $error['captcha'] = true;
    if ( !( isEmail($email) ) ) $error['email'] = true;
    if ( strlen($password) < 6 ) $error['password'] = true;
    if ( $passwordagain != $password ) $error['passwordagain'] = true;

    if ( empty($error) )
    {
        $emailexists = Db::fetchOne(
            "SELECT email FROM users
             WHERE email = '$email'"
        );

        if ( !( empty($emailexists) ) )
            $error['email'] = true;
    }

    if ( empty($error) )
    {
        Db::insert('users', array(
            'email' => $email,
            'password' => md5($password)
        ));

        $uid = Db::inserted();

        Db::update('projects_access', array(
            'uid' => $uid
        ), "email = '$email'");

        header("Location: {$config['root']}signin/?email=$email");
    }
}

require_once "$root/themes/$theme/signup.php";