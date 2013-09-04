<?php

class Twitter
{
    const CONFIG_KEY = '';
    const CONFIG_SECRET = '';

    const URL_REQUEST = 'http://twitter.com/oauth/request_token';
    const URL_ACCESS = 'http://twitter.com/oauth/access_token';
    const URL_AUTHORIZE = 'http://twitter.com/oauth/authorize';
    const URL_API = 'http://api.twitter.com/1';

    const STATE_NOTHING   = 0;
    const STATE_AUTH      = 1;
    const STATE_CONNECTED = 2;
    const STATE_VERIFIED  = 3;

    /**
     * @var OAuth
     */
    protected $oauth;

    /**
     * 0 : Sin conectar
     * 1 : En proceso de Autentificacion
     * 2 : Conectado
     * 3 : Verificado
     *
     * @var integer
     */
    protected $state = 0;

    /**
     * @var array
     */
    protected $user = array();

    /**
     * @var string
     */
    protected $token = '';
    protected $tokenSecret = '';

    public function __construct()
    {
        $this->oauth = new OAuth(self::CONFIG_KEY, self::CONFIG_SECRET);
        $this->state = ( Session::has('Twitter_State') ) ? Session::get('Twitter_State') : self::STATE_NOTHING;

        $this->token = ( Session::has('Twitter_Token') ) ? Session::get('Twitter_Token') : '';
        $this->tokenSecret = ( Session::has('Twitter_TokenSecret') ) ? Session::get('Twitter_TokenSecret') : '';

        if ( Request::hasQuery('oauth_token') )
        {
            $this->state = self::STATE_AUTH;
            $this->token = Request::getQuery('oauth_token');
        }

        if ( empty($this->token) )
            $this->state = self::STATE_NOTHING; // $this->destroy();

        switch ( $this->state )
        {
            case self::STATE_NOTHING:
                break;

            case self::STATE_AUTH:
                $this->connect();
                break;

            case self::STATE_CONNECTED:
                $this->oauth->setToken(
                    $this->token,
                    $this->tokenSecret
                );

                $this->credentials();
                break;
        }
    }

    protected function connect()
    {
        $this->oauth->setToken(
            $this->token,
            $this->tokenSecret
        );

        $accessToken = $this->oauth->getAccessToken(self::URL_ACCESS
            . ( Request::hasQuery('oauth_verifier') ? '?oauth_verifier=' . Request::getQuery('oauth_verifier') : '' )
        );

        $this->state = self::STATE_CONNECTED;
        $this->token = $accessToken['oauth_token'];
        $this->tokenSecret = $accessToken['oauth_token_secret'];

        Session::set('Twitter_Token', $this->token);
        Session::set('Twitter_TokenSecret', $this->tokenSecret);
        Session::set('Twitter_State', $this->state);

        TwCensus::redirect('/');
    }

    public function connected()
    {
        return ( $this->state >= self::STATE_CONNECTED );
    }

    public function destroy()
    {
        Session::del('Twitter_Token');
        Session::del('Twitter_TokenSecret');
        Session::del('Twitter_State');

        $this->oauth = new OAuth(self::CONFIG_KEY, self::CONFIG_SECRET);
        $this->state = self::STATE_NOTHING;

        $this->token = '';
        $this->tokenSecret = '';

        TwCensus::redirect('/');
    }

    public function credentials()
    {
        if ( !( empty($this->user) ) )
            return $this->user;

        static $fields = array(
            'id'                => 'id',
            'name'              => 'name',
            'screen_name'       => 'username',
            'location'          => 'location',
            'description'       => 'description',
            'profile_image_url' => 'image',
            'url'               => 'url'
        );

        $userxml = $this->oauth->fetch2(self::URL_API . '/account/verify_credentials.xml');

        if ( empty($userxml) )
            $this->destroy();

        $user = Xml::read($userxml);

        if ( isset($user[0]) && $user[0]['tag'] == 'user' && isset($user[0]['elements']) )
        {
            foreach ( $user[0]['elements'] as $element )
            {
                if ( isset($element['value']) && in_array( $element['tag'], array_keys( $fields ) ) )
                    $this->user[$fields[$element['tag']]] = $element['value'];
            }
        }

        return $this->user;
    }

    public function getAuthorizationUrl()
    {
        $requestToken = $this->oauth->getRequestToken(self::URL_REQUEST);

        Session::set('Twitter_TokenSecret', $requestToken['oauth_token_secret']);
        # Session::set('Twitter_State', self::STATE_AUTH);

        return self::URL_AUTHORIZE . '?oauth_token=' . $requestToken['oauth_token'];
    }

    public function getID()
    {
        if ( $this->user )
            return $this->user['id'];

        return 0;
    }

    public function getUserName()
    {
        if ( $this->user )
        {
            if ( !( empty($this->user['name']) ) )
                return $this->user['name'];
            elseif ( !( empty($this->user['username']) ) )
                return $this->user['username'];
        }

        return '';
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }
}
