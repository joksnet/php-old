<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

if ( !( isset($_GET['pid']) ) )
    die('Missing parameter');

$pid = (int) $_GET['pid'];

if ( !( Session::$logged ) )
    header("Location: {$config['root']}signin/?next={$config['root']}dd/projects/$pid/access/");

$title = __('ProjectsAccessTitle');

$project = Db::fetchRow(
    "SELECT name, description, uid
     FROM projects
     WHERE pid = '$pid'"
);

if ( $project )
{
    $accesses = Db::fetchAssoc(
        "SELECT aid, uid, email, message
         FROM projects_access
         WHERE pid = '$pid'
         ORDER BY aid"
    );

    if ( $project['uid'] == Session::_('uid') )
        $access = true;
}

if ( !( empty($_POST) ) )
{
    $email = strtolower( $_POST['email'] );
    $message = $_POST['message'];

    if ( !( isEmail($email) ) )
        $error['email'] = true;

    if ( !( empty($message) ) && strlen($message) > 140 )
        $error['message'] = true;

    if ( empty($error) )
    {
        $emailexists = Db::fetchOne(
            "SELECT uid FROM users
             WHERE email = '$email'"
        );

        $uid = ( !( empty($emailexists) ) ) ? $emailexists : 0;
        $notice = ( !( empty($emailexists) ) ) ? 1 : 0;

        /**
         * @todo Send email.
         */

        Db::insert('projects_access', array(
            'pid'     => $pid,
            'uid'     => $uid,
            'email'   => $email,
            'message' => $message,
            'notice'  => $notice
        ));

        header("Location: {$config['root']}dd/projects/$pid/access/");
    }
}

require_once "$root/themes/$theme/ddprojectsaccess.php";