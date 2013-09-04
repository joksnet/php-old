<?php

class Mappy
{
    const CONFIG_USERNAME = '';
    const CONFIG_PASSWORD = '';

    public function getToken()
    {
        $username = self::CONFIG_USERNAME;
        $password = self::CONFIG_PASSWORD;

        $time = time();
        $hash = md5("$username@$password@$time");
        $auth = "$username@$time@$hash";

        $ip = ( isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '';

        $url = 'http://axe.mappy.com/1v1/token/generate.aspx?'
             . 'auth=' . urlencode($auth) . '&'
             . 'ip=' . urldecode($ip);

        /**
        $remote = @fopen($url, 'rb');

        if ( false === $remote )
            return false;

        $token = '';

        while ( !( feof($remote) ) )
            $token .= fread($remote, 8192);

        fclose($remote);
         */

        $curl = new Curl($url);
        $curl->request();
        $resp = $curl->response();
        $curl->disconnect();

        return $resp['body'];
    }
}
