<?php
/**
 * Send an email with one word from Wikipedia quality entries.
 */

$SENDTO = array('johndoe@example.org' => 'John Doe');

class Curl
{
    public static function get( $url )
    {
        $curl = new self($url, CURLOPT_HTTPGET);
        $resp = $curl->request();
        $curl->disconnect();

        return $resp;
    }

    protected $_curl = null;
    protected $_response = '';
    protected $_url = '';
    protected $_method = CURLOPT_HTTPGET;
    protected $_port = 80;
    protected $_config = array(
        'timeout'       => 10,
        'maxredirects'  => 0,
        'httpversion'   => 1.1
    );

    public function __construct( $url, $method = CURLOPT_HTTPGET, $params = array() )
    {
        if ( !( extension_loaded('curl') ) )
            throw new Exception('cURL extension has to be loaded.');

        $this->_url = $url;
        $this->_method = $method;
        $this->_params = $params;

        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function connect()
    {
        if ( $this->_curl )
            $this->close();

        $this->_curl = curl_init();

        curl_setopt($this->_curl, CURLOPT_URL, $this->_url);

        if ( $this->_port != 80 )
            curl_setopt($this->_curl, CURLOPT_PORT, intval($this->_port));

        curl_setopt($this->_curl, CURLOPT_TIMEOUT, $this->_config['timeout']);
        curl_setopt($this->_curl, CURLOPT_MAXREDIRS, $this->_config['maxredirects']);

        if ( !( $this->_curl ) )
            throw new Exception("Unable to Connect to $this->_url:$this->_port");

        return $this;
    }

    public function request()
    {
        curl_setopt($this->_curl, ( $this->_config['httpversion'] = 1.1 ) ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_1_0, true);
        curl_setopt($this->_curl, $this->_method, true);
        curl_setopt($this->_curl, CURLOPT_HEADER, true);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);

        if ( $this->_method == CURLOPT_POST )
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $this->_params);

        $this->_response = curl_exec($this->_curl);

        if ( stripos($this->_response, "Transfer-Encoding: chunked\r\n") )
            $this->_response = str_ireplace("Transfer-Encoding: chunked\r\n", '', $this->_response);

        return $this->_response;
    }

    public function disconnect()
    {
        if ( is_resource($this->_curl) )
            curl_close($this->_curl);

        return $this;
    }
}

class Mail
{
    protected static $from = '';

    public static function from( $mail, $name = null )
    {
        self::$from = self::format($mail, $name);
    }

    public static function mail( $subject = null )
    {
        return new self($subject);
    }

    protected $_headers = array();
    protected $_subject = '(no subject)';
    protected $_to = array();
    protected $_body = '';

    public function __construct( $subject = null )
    {
        if ( null !== $subject )
            $this->_subject = $subject;
    }

    public function header( $name, $value )
    {
        if ( !( isset($this->_headers[$name]) ) )
            $this->_headers[$name] = $value;

        return $this;
    }

    public function to( $mail, $name = null )
    {
        if ( is_array($mail) )
        {
            foreach ( $mail as $key => $value )
            {
                if ( is_string($key) || is_array($key) )
                    $this->to($key, $value);
                elseif ( is_array($value) )
                    $this->to($value);
            }
        }
        elseif ( is_string($mail) && !( isset($this->_to[$mail]) ) )
            $this->_to[$mail] = $name;

        return $this;
    }

    public function body( $body )
    {
        if ( $body )
            $this->_body = $body;

        return $this;
    }

    public function send()
    {
        if ( sizeof($this->_to) == 0 )
            return false;

        $this->header('MIME-Version', '1.0')
             ->header('Content-Type', 'text/plain')
             ->header('From', self::$from)
             ->header('X-Mailer', 'PHP/' . phpversion());

        $emailToArray = array();
        $emailHeadersArray = array();

        foreach ( $this->_to as $mail => $name )
            $emailToArray[] = self::format($mail, $name);

        foreach ( $this->_headers as $name => $value )
            $emailHeadersArray[] = "$name: $value";

        $emailHeaders = implode("\n", $emailHeadersArray);
        $emailTo = implode(', ', $emailToArray);
        $emailSubject = $this->_subject;
        $emailBody = $this->_body;

        return ( @mail($emailTo, $emailSubject, $emailBody, $emailHeaders) );
    }

    public static function format( $mail, $name = null )
    {
        if ( is_null($name) )
            return $mail;
        else
        {
            if ( strpos($name, ' ') !== false )
                $name = "\"$name\"";

            return "$name <$mail>";
        }
    }
}

$response = Curl::get('http://fr.wikipedia.org/wiki/Wikip%C3%A9dia:Contenus_de_qualit%C3%A9');

if ( !( preg_match_all('/<a href="\/wiki\/([%\w\s\d]*)" title="(.*)">.*<\/a>/U', $response, $matches) ) )
    die('ERROR');
else
{
    $urls = $matches[1];
    $titles = $matches[2];

    do {
        $rand = rand(1, count($urls) - 1);
    } while ( !( $title = $titles[$rand] ) );

    $title = utf8_decode($title);

    Mail::from('no-reply@localhost');
    Mail::mail("[MOT] $title")
        ->to($SENDTO)
        ->body("Bonjour,\n\nTon mot d'aujourd'hui est **$title**. Bonne journée!\n\n-- Word a Day")
        ->send();

    die('OK');
}
