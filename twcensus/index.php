<?php

class TwCensus
{
    protected static $root = '';
    protected static $base = '';

    protected static $config = array(
        'db' => array(
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'twcensus'
        ),

        'perPage' => 20,

        /**
         * ISO 3166 Code
         *
         * @link http://userpage.chemie.fu-berlin.de/diverse/doc/ISO_3166.html
         * @var integer
         */
        'country' => 250 // France
    );

    /**
     * @var Mappy
     */
    protected $mappy;

    /**
     * @var Twitter
     */
    protected $twitter;

    protected $action = '';

    protected $user = array();
    protected $area = null;

    protected $kmlVisible = false;
    protected $kmlInvisible = false;

    public function __construct()
    {
        self::$root = rtrim(dirname( $_SERVER['SCRIPT_FILENAME'] ), DIRECTORY_SEPARATOR);
        self::$base = rtrim(dirname( $_SERVER['SCRIPT_NAME'] ), DIRECTORY_SEPARATOR);

        spl_autoload_register(
            array('TwCensus', 'loader')
        );

        Session::init();
        Session::$prefix = 'TwCensus_';

        Db::connect(
            self::$config['db']
        );

        Request::init();

        if ( Request::hasQuery('kml') )
            $this->actionKML();
        else
            $this->actionWebsite();
    }

    public function __destruct()
    {
        Db::disconnect();
    }

    public function dispatch()
    {
        require_once self::$root . "/template$this->action.phtml";
    }

    protected function actionKML()
    {
        $this->action = 'kml';

        $countryISO = Request::getQuery('kml', self::$config['country']);
        $country = Db::fetchRow(
            "SELECT countries.id_country
                  , countries.code
                  , countries.name
                  , countries.coordinates
             FROM twcensus_countries AS countries
             WHERE countries.iso = '$countryISO'
             LIMIT 1"
        );

        if ( $country )
        {
            if ( Request::hasQuery('area') )
            {
                $areaCode = Request::getQuery('area', '');
                $area = Db::fetchRow(
                    "SELECT areas.id_area
                          , areas.code
                          , areas.name
                          , areas.coordinates
                     FROM twcensus_areas AS areas
                     INNER JOIN twcensus_countries AS countries
                             ON countries.id_country = areas.id_country
                            AND countries.iso = '" . self::$config['country'] . "'
                     WHERE areas.code = '$areaCode'
                     LIMIT 1"
                );

                if ( $area )
                {
                    $count = Db::fetchOne(
                        "SELECT COUNT(*)
                         FROM twcensus_users AS users
                         WHERE users.id_country = '{$country['id_country']}'
                           AND users.id_area = '{$area['id_area']}'
                           AND users.saved > 0"
                    );

                    $page = Request::getQuery('page', 1);
                    $perPage = self::$config['perPage'];
                    $pages = ceil( $count / $perPage );
                    $start = $perPage * ( $page - 1 );

                    $area['count'] = $count;
                    $area['page']  = $page;
                    $area['pages'] = $pages;

                    $this->kmlVisible = $area;
                    $this->kmlInvisible = Db::fetchAssoc(
                        "SELECT users.id_user AS id
                              , users.username AS code
                              , users.name
                              , users.location
                              , users.description
                              , users.image
                              , users.url
                              , users.sex
                              , users.age
                              , CONCAT(users.x, ',', users.y, ',0') AS coordinates
                              , localities.id_locality
                              , localities.code AS locality_code
                              , localities.name AS locality_name
                         FROM twcensus_users AS users
                         INNER JOIN twcensus_localities AS localities
                                 ON localities.id_locality = users.id_locality
                         WHERE users.id_country = '{$country['id_country']}'
                           AND users.id_area = '{$area['id_area']}'
                           AND users.saved > 0
                         ORDER BY users.saved DESC
                         LIMIT $start, $perPage"
                    );
                }
            }
            else
            {
                $this->kmlVisible = $country;
                $this->kmlInvisible = Db::fetchAssoc(
                    "SELECT areas.id_area
                          , areas.code
                          , areas.name
                          , areas.coordinates
                          , COUNT(users.id_user) AS total
                          , AVG(users.age) AS age
                     FROM twcensus_areas AS areas
                     LEFT JOIN twcensus_users AS users
                            ON users.id_country = areas.id_country
                           AND users.id_area = areas.id_area
                     WHERE areas.id_country = '{$country['id_country']}'
                     GROUP BY areas.id_area
                     ORDER BY areas.name"
                );
            }
        }

        header('Content-type: text/xml');
    }

    protected function actionWebsite()
    {
        // Default action
        // $this->action = '';

        $this->mappy = new Mappy();
        $this->twitter = new Twitter();

        if ( Request::isPost() )
        {
            $saved = false;

            if ( Request::hasPost('save') && $this->twitter->connected() )
            {
                $idCountry  = Request::getPost('country', 0);
                $idArea     = Request::getPost('area', 0);
                $idLocality = Request::getPost('locality', 0);

                if ( !( $idCountry ) )
                {
                    $countryName = Request::getPost('country_name', '');
                    $countryISO  = Request::getPost('country_iso', '');
                    $countryCode = self::encode($countryName);

                    $country = Db::fetchRow(
                        "SELECT countries.id_country
                              , countries.code
                              , countries.name
                              , countries.iso
                         FROM twcensus_countries AS countries
                         WHERE countries.code = '$countryCode'
                           AND countries.iso = '$countryISO'"
                    );

                    if ( $country )
                        $idCountry = $country['id_country'];
                    else
                    {
                        $idCountry = Db::insert('twcensus_countries', array(
                            'code' => $countryCode,
                            'name' => $countryName,
                            'iso'  => $countryISO
                        ));
                    }
                }

                if ( !( $idArea ) )
                {
                    $areaName = Request::getPost('area_name', '');
                    $areaCode = self::encode($areaName);

                    $area = Db::fetchRow(
                        "SELECT areas.id_area
                              , areas.code
                              , areas.name
                         FROM twcensus_areas AS areas
                         WHERE areas.id_country = '$idCountry'
                           AND areas.code = '$areaCode'"
                    );

                    if ( $area )
                        $idArea = $area['id_area'];
                    else
                    {
                        $idArea = Db::insert('twcensus_areas', array(
                            'id_country' => $idCountry,
                            'code'       => $areaCode,
                            'name'       => $areaName
                        ));
                    }
                }

                if ( !( $idLocality ) )
                {
                    $localityName = Request::getPost('locality_name', '');
                    $localityCode = self::encode($localityName);

                    $locality = Db::fetchRow(
                        "SELECT localities.id_locality
                              , localities.code
                              , localities.name
                         FROM twcensus_localities AS localities
                         WHERE localities.id_area = '$idArea'
                           AND localities.code = '$localityCode'"
                    );

                    if ( $locality )
                        $idLocality = $locality['id_locality'];
                    else
                    {
                        $idLocality = Db::insert('twcensus_localities', array(
                            'id_area' => $idArea,
                            'code'    => $localityCode,
                            'name'    => $localityName
                        ));
                    }
                }

                $info = array(
                    'id_country'  => $idCountry,
                    'id_area'     => $idArea,
                    'id_locality' => $idLocality,

                    'address' => Request::getPost('address', ''),

                    'x' => Request::getPost('coord_x', 0),
                    'y' => Request::getPost('coord_y', 0),

                    'sex' => Request::getPost('sex', 0),
                    'age' => Request::getPost('age', 0),

                    'saved' => time()
                );

                Db::update('twcensus_users', $info, array(
                    'id_user' => $this->twitter->getID()
                ));

                $saved = true;
            }

            TwCensus::redirect(
                '/' . ( $saved ? '?saved' : '' )
            );
        }
        elseif ( Request::hasQuery('destroy') )
        {
            $this->twitter->destroy();
        }
        elseif ( $this->twitter->connected() )
        {
            $data = $this->twitter->credentials();
            $info = array(
                'name'        => $data['name'],
                'username'    => $data['username'],
                'location'    => $data['location'],
                'description' => $data['description'],
                'image'       => $data['image'],
                'url'         => $data['url'],
                'token'       => $this->twitter->getToken(),
                'secret'      => $this->twitter->getTokenSecret()
            );

            if ( $this->exists($data['id']) )
            {
                $info['modified'] = time();

                Db::update('twcensus_users', $info, array(
                    'id_user' => $data['id']
                ));
            }
            else
            {
                $info['id_user'] = $data['id'];
                $info['created'] = time();

                Db::insert('twcensus_users', $info);
            }

            $this->select($data['id']);
        }
        elseif ( Request::hasQuery('area') )
        {
            $this->area = Db::fetchRow(
                "SELECT areas.id_area
                      , areas.code
                      , areas.name
                 FROM twcensus_areas AS areas
                 INNER JOIN twcensus_countries AS countries
                         ON countries.id_country = areas.id_country
                        AND countries.iso = '" . self::$config['country'] . "'
                 WHERE areas.code = '" . Request::getQuery('area', '') . "'
                 LIMIT 1"
            );
        }
    }

    protected function exists( $id )
    {
        $exists = Db::fetchOne(
            "SELECT COUNT(id_user)
             FROM twcensus_users
             WHERE id_user = '$id'"
        );

        return ( $exists > 0 );
    }

    protected function select( $id )
    {
        $this->user = Db::fetchRow(
            "SELECT users.id_user AS id
                  , users.name
                  , users.username
                  , users.location
                  , users.description
                  , users.image
                  , users.url
                  , users.token
                  , users.secret
                  , users.address
                  , users.x
                  , users.y
                  , users.sex
                  , users.age
                  , users.modified
                  , users.created
             FROM twcensus_users AS users
             WHERE id_user = '$id'"
        );

        return $this->user;
    }

    protected function get( $name, $default = null )
    {
        if ( !( $this->twitter->connected() ) )
            return $default;

        if ( isset($this->user[$name]) )
            return $this->user[$name];

        return $default;
    }

    protected function last()
    {
        return Db::fetchAssoc(
            "SELECT users.id_user AS id
                  , users.name
                  , users.username
                  , users.location
                  , users.description
                  , users.image
                  , users.url
             FROM twcensus_users AS users
             ORDER BY users.created DESC
             LIMIT 5"
        );
    }

    protected static function encode( $q )
    {
        static $abc = 'abcdefghijklmnopqrstuvwxyz0123456789-';
        static $dict = array(
            0xc0 => 'A', 0xc1 => 'A', 0xc2 => 'A', 0xc3 => 'A', 0xc4 => 'A', 0xc5 => 'A',
            0xc6 => 'Ae', 0xc7 => 'C',
            0xc8 => 'E', 0xc9 => 'E', 0xca => 'E', 0xcb => 'E',
            0xcc => 'I', 0xcd => 'I', 0xce => 'I', 0xcf => 'I',
            0xd0 => 'Th', 0xd1 => 'N',
            0xd2 => 'O', 0xd3 => 'O', 0xd4 => 'O', 0xd5 => 'O', 0xd6 => 'O', 0xd8 => 'O',
            0xd9 => 'U', 0xda => 'U', 0xdb => 'U', 0xdc => 'U',
            0xdd => 'Y', 0xde => 'th', 0xdf => 'ss',
            0xe0 => 'a', 0xe1 => 'a', 0xe2 => 'a', 0xe3 => 'a', 0xe4 => 'a', 0xe5 => 'a',
            0xe6 => 'ae', 0xe7 => 'c',
            0xe8 => 'e', 0xe9 => 'e', 0xea => 'e', 0xeb => 'e',
            0xec => 'i', 0xed => 'i', 0xee => 'i', 0xef => 'i',
            0xf0 => 'th', 0xf1 => 'n',
            0xf2 => 'o', 0xf3 => 'o', 0xf4 => 'o', 0xf5 => 'o', 0xf6 => 'o', 0xf8 => 'o',
            0xf9 => 'u', 0xfa => 'u', 0xfb => 'u', 0xfc => 'u',
            0xfd => 'y', 0xfe => 'th', 0xff => 'y',
            0xa1 => '!', 0xa2 => '{cent}', 0xa3 => '{pound}', 0xa4 => '{currency}',
            0xa5 => '{yen}', 0xa6 => '|', 0xa7 => '{section}', 0xa8 => '{umlaut}',
            0xa9 => '{C}', 0xaa => '{^a}', 0xab => '<<', 0xac => '{not}',
            0xad => '-', 0xae => '{R}', 0xaf => '_', 0xb0 => '{degrees}',
            0xb1 => '{+/-}', 0xb2 => '{^2}', 0xb3 => '{^3}', 0xb4 => "'",
            0xb5 => '{micro}', 0xb6 => '{paragraph}', 0xb7 => '*', 0xb8 => '{cedilla}',
            0xb9 => '{^1}', 0xba => '{^o}', 0xbb => '>>',
            0xbc => '{1/4}', 0xbd => '{1/2}', 0xbe => '{3/4}', 0xbf => '?',
            0xd7 => '*', 0xf7 => '/'
        );

        $r = '';
        $z = '';

        for ( $i = 0; $i < strlen($q); $i++ )
        {
            $chr = substr($q, $i, 1);
            $ord = ord($chr);

            if ( array_key_exists($ord, $dict) )
                $r .= $dict[$ord];
            elseif ( $ord >= 0x80 ) {}
            else $r .= $chr;
        }

        $r = strtolower($r);

        for ( $e = 0; $e < strlen($r); $e++ )
        {
            $chr = substr($r, $e, 1);

            if ( strpos($abc, $chr) === false )
                $z .= '-';
            else
                $z .= $chr;
        }

        return $z;
    }

    public static function loader( $class )
    {
        $class = str_replace('_', '/', $class);
        $filename = self::$root . '/includes/' . $class . '.php';

        require_once $filename;
    }

    public static function redirect( $url )
    {
        if ( substr($url, 0, 1) == '/' )
            $url = substr($url, 1);

        header('Location: ' . self::$base . '/' . $url); exit();
    }
}

$census = new TwCensus();
$census->dispatch();
