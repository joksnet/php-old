<?php

include_once '../Curl.php';
include_once '../OAuth.php';

define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');

define('URL_REQUEST_TOKEN', 'http://twitter.com/oauth/request_token');
define('URL_ACCESS_TOKEN', 'http://twitter.com/oauth/access_token');
define('URL_AUTHORIZE', 'http://twitter.com/oauth/authorize');
define('URL_API', 'http://api.twitter.com/1');

session_start();

// In state=1 the next request should include an oauth_token.
// If it doesn't go back to 0
if ( !( isset($_GET['oauth_token']) ) && $_SESSION['twitter_state'] == 1 )
    $_SESSION['twitter_state'] = 0;

try {
    $oauth = new OAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $oauth->setCallback('http://localhost/oauth/example/Twitter.php');

    if ( !( isset($_GET['oauth_token']) ) && !( $_SESSION['twitter_state'] ) )
    {
        $requestToken = $oauth->getRequestToken(URL_REQUEST_TOKEN);

        $_SESSION['twitter_secret'] = $requestToken['oauth_token_secret'];
        $_SESSION['twitter_state'] = 1;

        header('Location: ' . URL_AUTHORIZE . '?oauth_token=' . $requestToken['oauth_token']);
        exit();
    }
    elseif ( $_SESSION['twitter_state'] == 1 )
    {
        $oauth->setToken($_GET['oauth_token'], $_SESSION['twitter_secret']);

        $accessToken = $oauth->getAccessToken(URL_ACCESS_TOKEN . '?oauth_verifier=' . $_GET['oauth_verifier']);

        $_SESSION['twitter_state'] = 2;
        $_SESSION['twitter_token'] = $accessToken['oauth_token'];
        $_SESSION['twitter_secret'] = $accessToken['oauth_token_secret'];
    }

    $oauth->setToken($_SESSION['twitter_token'], $_SESSION['twitter_secret']);

    $response = $oauth->fetch(URL_API . '/statuses/user_timeline.xml');
    $response = $oauth->getLastResponse();

    if ( isset($response['headers']['Content-Type']) )
        header('Content-Type: ' . $response['headers']['Content-Type']);

    echo ltrim($response['body']);
}
catch ( Exception $e ) {
    print_r($e);

    if ( $response = $oauth->getLastResponse() )
    {
        print '<hr />';
        print_r($response);
    }
}
