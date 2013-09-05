<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

if ( dhIsLogin() )
    dhRedirect('index');

if ( dhIsPost() )
{
    $isValid = dhValid(array(
        'fullname' => 'required',
        'name'     => 'required',
        'pass'     => 'required',
        'pass2'    => array( 'required' => true, 'equal' => 'pass' ),
        'question' => 'required',
        'anwser'   => 'required'
    ));

    if ( $isValid )
    {
        dhQueryInsert('users', array(
            'fullname' => dhPost('fullname'),
            'name'     => dhPost('name'),
            'pass'     => md5( dhPost('pass') ),
            'question' => dhPost('question'),
            'anwser'   => dhPost('anwser'),
            'register' => time()
        ));

        dhRedirect('register-success', array('name', dhPost('name')));
    }
}

dhTheme('register');