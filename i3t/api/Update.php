<?php

class Update
{
    public $userId = 0;
    public $viewOnly = true;
    public $type = 'application/json';

    protected $return = null;

    public function __construct()
    {
        global $config;

        if ( substr(Web::getIP(), 0, strlen($config['internal'])) == $config['internal'] )
            $this->viewOnly = false;

        $this->userId = Cookies::get('uid');
        $this->logId = $this->log();

        switch ( $_GET['w'] )
        {
            case 'time':
                $this->return = $this->time;
                break;

            case 'resume':
                $this->return = $this->work();
                break;

            default:
                $this->return = $this->logId;
                break;
        }
    }

    protected function work( $user_id = 0, $m = null, $Y = null )
    {
        if ( $user_id == 0 )
            $user_id = $this->userId;

        if ( $m === null ) $m = date('m');
        if ( $Y === null ) $Y = date('Y');

        if ( $m == 12 )
        {
            $m2 = 1;
            $Y2 = $Y + 1;
        }
        else
            $m2 = $m + 1;

        $month_start = mktime(0, 0, 0, $m, 1, $Y);
        $month_end = mktime(0, 0, 0, $m2, 1, $Y2);

        $sql = 'SELECT log_id, login, logout, extras
                FROM users_log
                WHERE user_id = "' . $user_id . '"
                AND login > "' . $month_start . '"
                AND login < "' . $month_end . '"
                ORDER BY login';

        if ( $data = Db::query($sql) )
            return $data;
        else
            return false;
    }

    protected function log( $user_id = 0 )
    {
        if ( $this->viewOnly )
            return 0;

        if ( $user_id == 0 )
            $user_id = $this->userId;

        $time = time();

        $m = date('m');
        $d = date('d');
        $Y = date('Y');

        $today_start = mktime(0, 0, 0, $m, $d, $Y);
        $today_end = mktime(23, 59, 59, $m, $d, $Y);

        $sql = 'SELECT log_id
                FROM users_log
                WHERE user_id = "' . $user_id . '"
                AND login > "' . $today_start . '"
                AND login < "' . $today_end . '"';

        if ( $log_id = Db::query($sql) )
        {
            $sql = 'UPDATE users_log
                    SET logout = "' . $time . '"
                    WHERE log_id = "' . $log_id . '"';

            $this->time = Db::query('SELECT login FROM users_log WHERE log_id = "' . $log_id . '"');
        }
        else
        {
            $sql = 'INSERT INTO users_log (user_id, login, logout)
                    VALUES ("' . $user_id . '", "' . $time . '", "' . $time . '")';

            $this->time = $time;
        }

        return Db::query($sql);
    }

    public function __toString()
    {
        return Json::encode($this->return);
    }
}