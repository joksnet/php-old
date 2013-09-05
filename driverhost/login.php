<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( dhIsLogin() )
    dhRedirect('index');

$loginFail = false;

if ( dhIsPost() )
{
    $name = dhPost('name');
    $pass = dhPost('pass');

    $isValid = dhValid(array(
        'name'     => 'required',
        'pass'     => 'required'
    ));

    $pass = md5($pass);

    if ( $isValid )
    {
        $data = dhQuery(
            "SELECT u.id_users
             FROM users u
             WHERE u.name = '$name'
               AND u.pass = '$pass'"
        );

        if ( $data )
        {
            dhLogin( $data[0]['id_users'] );
            dhQueryUpdate('users', array( 'login' => time() ), "id_users={$data[0]['id_users']}");
            dhRedirect('index');
        }

        $loginFail = true;
    }
}

dhTheme('login');