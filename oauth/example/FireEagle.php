<?php

include_once '../Curl.php';
include_once '../OAuth.php';

define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');

define('URL_REQUEST_TOKEN', 'https://fireeagle.yahooapis.com/oauth/request_token');
define('URL_ACCESS_TOKEN', 'https://fireeagle.yahooapis.com/oauth/access_token');
define('URL_AUTHORIZE', 'https://fireeagle.yahoo.net/oauth/authorize');
define('URL_API', 'https://fireeagle.yahooapis.com/api/0.1');

session_start();

// In state=1 the next request should include an oauth_token.
// If it doesn't go back to 0
if ( !( isset($_GET['oauth_token']) ) && $_SESSION['fireeagle_state'] == 1 )
    $_SESSION['fireeagle_state'] = 0;

try {
    $oauth = new OAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $oauth->setCallback('http://localhost/oauth/example/FireEagle.php');

    if ( !( isset($_GET['oauth_token']) ) && !( $_SESSION['fireeagle_state'] ) )
    {
        $requestToken = $oauth->getRequestToken(URL_REQUEST_TOKEN);

        $_SESSION['fireeagle_secret'] = $requestToken['oauth_token_secret'];
        $_SESSION['fireeagle_state'] = 1;

        header('Location: ' . URL_AUTHORIZE . '?oauth_token=' . $requestToken['oauth_token']);
        exit();
    }
    elseif ( $_SESSION['fireeagle_state'] == 1 )
    {
        $oauth->setToken($_GET['oauth_token'], $_SESSION['fireeagle_secret']);

        $accessToken = $oauth->getAccessToken(URL_ACCESS_TOKEN . '?oauth_verifier=' . $_GET['oauth_verifier']);

        $_SESSION['fireeagle_state'] = 2;
        $_SESSION['fireeagle_token'] = $accessToken['oauth_token'];
        $_SESSION['fireeagle_secret'] = $accessToken['oauth_token_secret'];
    }

    $oauth->setToken($_SESSION['fireeagle_token'], $_SESSION['fireeagle_secret']);

    $response = $oauth->fetch(URL_API . '/user');
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
