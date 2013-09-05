<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

$title = __('TLDsSuggest');

if ( !( empty($_POST) ) )
{
    $domain = $_POST['domain'];
    $description = $_POST['description'];
    $captcha = strtolower( $_POST['captcha'] );

    if ( substr($domain, 0, 1) == '.' )
        $domain = substr($domain, 1);

    $domain = strtolower($domain);

    if ( !( Session::captchaCompare('tldssuggest', $captcha) ) )
        $error['captcha'] = true;

    if ( strlen($domain) < 2 || !( isTLD($domain) ) )
        $error['domain'] = true;

    if ( !( empty($description) ) && strlen($description) > 140 )
        $error['description'] = true;

    if ( empty($error) )
    {
        $tldexists = Db::fetchOne(
            "SELECT domain FROM tlds
             WHERE LCASE(domain) = '$domain'"
        );

        if ( !( empty($tldexists) ) )
            $error['domainexists'] = true;
    }

    if ( empty($error) )
    {
        Db::insert('tlds', array(
            'domain'      => $domain,
            'description' => $description,
            'suggest'     => 1
        ));

        header("Location: {$config['root']}tlds/");
    }
}

require_once "$root/themes/$theme/tldssuggest.php";