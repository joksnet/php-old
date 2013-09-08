<?php

class Login
{
    public function __construct()
    {
        global $config;

        $username = strip_tags( addslashes( trim( $_POST['username'] ) ) );
        $password = md5( trim( $_POST['password'] ) );
        $viewonly = ( $_POST['view'] == 1 );

        $sql = "SELECT user_id, hours
                FROM users
                WHERE username = '$username'
                AND password = '$password'";

        if ( $data = Db::query($sql) )
        {
            $user_id = $data['user_id'];
            $hours = $data['hours'];

            if ( $config['expires'] )
                $expires = $config['expires'];
            else
                $expires = intval($hours) * 3600;

            # if ( headers_sent($file, $line) )
            #     die( "Headers Sent on $file:$line" );

            $modules = $this->getModules($user_id);

            $data = array(
                'username' => $username,
                'viewOnly' => $viewonly,
                'hours'    => $hours,
                'modules'  => $modules
            );

            Cookies::set('sid', md5($user_id), $expires);
            Cookies::set('uid', $user_id, $expires);
            Cookies::set('data', Json::encode($data), $expires);
        }

        header('Location: /');
    }

    protected function getModules( $user_id )
    {
        $sql = "SELECT m.module_id, m.name, m.filename
                FROM users_modules u
                INNER JOIN modules m
                        ON ( m.module_id = u.module_id )
                WHERE u.user_id = '$user_id'
                ORDER BY u.pos";

        if ( $data = Db::query($sql) )
            return $data;
        else
            return array();
    }
}