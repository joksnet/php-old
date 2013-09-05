<?php

$external = false;
$valid_ip = '10.0.0.';
$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] :
             ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );

if ( substr($client_ip, 0, strlen($valid_ip)) != $valid_ip )
    $external = true;

session_start();

function print_page_header( $page_id, $page_title = null )
{
    $page_title = ( $page_title ) ? $page_title : $page_id;

    echo <<<HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>i3t :: {$page_title}</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <style type="text/css">
        * { padding: 0px; margin: 0px; }
        body { margin: 0px; padding: 0px; font: 11px 'Trebuchet MS', Tahoma, Arial, sans-serif; color: #555; cursor: default; }

        a { color: blue; font: 11px 'Trebuchet MS', Tahoma, Arial, sans-serif; text-decoration: none; }

        .h1 { clear: left; width: 710px; padding: 1px 20px; margin: 3px auto; background-color: #FFFFE0; border: 1px solid #FFD685; color: #999; position: relative; }
        .h1 span { font: 18px 'Trebuchet MS', Tahoma, Arial, sans-serif; }
        .h1 div { font: 11px 'Trebuchet MS', Tahoma, Arial, sans-serif; position: absolute; top: 5px; right: 10px; }

        form#frm { float: right; }
        /** form#frm input, form#frm select { float: left; } */
        form#frm div.field { float: left; }

        form.page { width: 710px; padding: 0px 20px; margin: 0px auto; }
        form.page .field { width: 200px; float: left; }
        form.page .field label { display: block; }
        form.page .field input[type="text"], form.page .field select { width: 180px; }
        form.page input.go { background: #FFF; border: 0px; padding: 0px; margin: 0px; color: blue; font: 11px 'Trebuchet MS', Tahoma, Arial, sans-serif; }
        form.page .go { clear: left; }

        .addon { display: none; }

        form input, form select, form textarea { padding: 2px; margin-right: 6px; margin-bottom: 3px; background-color: #F9F9F9; border: 1px solid #DDDDDD; color: #999999;}
        form input:hover, form select:hover, form textarea:hover { background-color: #FFF; }
        form input:focus, form select:focus, form textarea:focus { background-color: #EDFAE2 !important; border-color: #76DB1F; color: #000000; }
        form input.go { border-width: 1px 1px 1px 4px; }

        form select optgroup { color: #777777; text-decoration: none; }
        form select optgroup option { padding-left: 10px; color: #000; }
        form select option { padding-left: 3px; }
        form select option[disabled="disabled"] { font-family: arial, sans-serif; font-size: 100%; }

        #login form#frm { width: 255px; margin: 0px auto; padding: 10px; float: none !important; }
        #login form#frm label { float: left; padding-top: 3px; padding-right: 10px; text-align: right; width: 75px; }
        #login form#frm input { float: left; }
        #login form#frm br { clear: left; }

        #welcome { width: 730px; height:24px; padding: 6px 10px; margin: 0 auto; background-color: #F0FFD1; border: 1px solid #76DB1F; border-top: 0px; }
        #welcome span { padding-top: 3px; float: left; font: 15px Tahoma, Aria, sans-serif; }
        #welcome #line { height: 10px; clear: both; background-color: #FFF; display: none; }

        #message { padding: 5px; text-align: center; }

        table { width: 752px; padding: 1px;  margin: 0px auto; border: 1px solid #B2C5EC; }
        table thead tr, table thead tr { height: 26px; background: #EBF7FF; }
        table thead tr th, table thead tr th { text-align: center; font-size: 13px; font-weight: bold; color: #618AD9;  border-bottom: 1px solid #B2E0FF; }
        table tbody tr td { padding: 3px; text-align: center; }
        table tfoot tr td { padding: 4px;  text-align: center; font-size: 14px; border-top: 1px solid #B2C5EC; }
        table tbody tr.row2 { background-color: #FCFCFC; }
        table tbody tr.row1 { background-color: #FFF; }
        table tbody tr.row2:hover td, table tbody tr.row1:hover td { background-color: #F2FAFF; }

        table#work tbody tr:hover td { background-color: #F2FAFF; }
        table#work tbody tr td { padding: 3px; text-align: center; background-color: #fafafa; border-top: 1px solid #FFF; }

        table#categories tbody.category tr { background-color: #EDFAE2; }
        table#categories tbody.category tr td { text-align: left; }
        table#categories tbody tr td.actions { text-align: right; }
        table#categories tbody tr td.actions a { padding-right: 5px; }
        table#categories tbody tr td.name { text-align: left; }

        #repository div.codeview { width: 730px; margin: -3px auto; }
        #repository div.codeview div.info { background-color: #FFFFE0; border: 1px solid #FFD685; border-top: 0px; margin-bottom: 3px; padding-left: 6px; }
        #repository div.codeview div.code { padding-left: 0.5em; text-align: left; background-color: #FAF5FF; border: 1px solid #EBD6FF; margin-bottom: 6px; }
        #repository div.codeview div.code code span.html { color: black; background-color: transparent; }
        #repository div.codeview div.code code span.comment { color: #FF8000; background-color: transparent; }
        #repository div.codeview div.code code span.default { color: #0000BB; background-color: transparent; }
        #repository div.codeview div.code code span.keyword { color: #007700; background-color: transparent; }
        #repository div.codeview div.code code span.string { color: #DD0000; background-color: transparent; }

        #todo form.page { width: 750px; padding: 0px; }
        #todo form.page table tr td .low { color: green; }
        #todo form.page table tr td .normal { color: blue; }
        #todo form.page table tr td .high { color: red; }

        #todo form.page table tfoot.new { background-color: #FFD9D3; }
        #todo form.page table tfoot.new tr td { font-size: 12px; padding: 4px 4px 2px 0px; }
        #todo form.page table tfoot tr td select { width: 100%; }
        #todo form.page table tfoot tr td input.go { margin-bottom: 4px; background-color: #FFD9D3 !important; }

        #todo form.page table tbody.project tr { background-color: #EDFAE2; }
        #todo form.page table tbody.project tr td { text-align: left; }
        #todo form.page table tbody tr td.todo { text-align: left; padding-left: 7px; }
        #todo form.page table tbody tr td input[type="checkbox"] { cursor: pointer; margin: 0px auto; }
        #todo form.page table tbody tr td span.date { font-weight: bold; }
        #todo form.page table tbody tr td span.username { font-weight: bold; }
        #todo form.page table tbody tr.completed td { color: #BBB !important; }
        #todo form.page table tbody tr.completed td span { color: #BBB !important; }

        #food table thead tr.cost { height: 10px; }
        #food table thead tr.cost th { font-size: 11px !important; text-align: right; padding-right: 26px; }
        #food table tbody.day tr { background-color: #EDFAE2; }
        #food table tbody.day tr td { text-align: left; }
        #food table tbody.day tr td.cost { font-size: 0pt; }
        #food table .cost { text-align: right !important; padding-right: 26px; }

        #food form.page #fields .food .field { width: 60px; }
        #food form.page #fields .food .field input { width: 38px; text-align: center; }
        #food form.page #fields .food .select { width: 650px; }
        #food form.page #fields .food .select select { width: 80%; }
        #food form.page #fields .food .field { position: relative; }
        #food form.page #fields .food .field input.new { width: 79%; text-align: left; }
        #food form.page #fields .food .field .actions { margin-bottom: 4px; position: absolute; bottom: 4px; }
        #food form.page .complete { display: block; width: 100%; }
        #food form.page .go { width: 100%; }
        #food form.page .go input.go { width: 27px; }

        #financials form.page { width: 750px; padding: 0px; }

        #financials table tfoot tr.new { background-color: #FFD9D3; }
        #financials table tfoot tr.new td { font-size: 12px; padding: 4px 4px 2px 0px; }
        #financials table tfoot tr.new td input { width: 100%; margin: 0px; margin-bottom: 2px; }
        #financials table tfoot tr.new td input.cost { width: 35px !important; padding-right: 2px; }
        #financials table tfoot tr.new td input.go { margin-bottom: 4px; background-color: #FFD9D3 !important; }

        #financials table thead tr.cost { height: 10px; }
        #financials table thead tr.cost th { font-size: 11px !important; text-align: right; padding-right: 26px; }
        #financials table tbody.day tr { background-color: #EDFAE2; }
        #financials table tbody.day tr td { text-align: left; }
        #financials table tbody.day tr td.cost { font-size: 0pt; }
        #financials table tbody tr.in td.current { color: green; }
        #financials table tbody tr.out td.current { color: red; }
        #financials table .description { text-align: left !important; }
        #financials table .cost { text-align: right !important; padding-right: 26px; }

        #footer { clear: left; width: 730px; margin: 3px auto; padding: 6px 10px; text-align: center; background: #FAF5FF; border: 1px solid #EBD6FF; }
    </style>
    <script type="text/javascript" src="?q=javascript"></script>
    <script type="text/javascript" src="?q=$page_id&amp;a=javascript"></script>
</head>

<body id="{$page_id}">
HEAD;
}

function print_page_welcome()
{
    global $username;
    global $user_id, $cu;
    global $Y, $m;

    $q = ( strlen($_GET['q']) > 0 ) ? $_GET['q'] : 'log';
    $tabs = str_repeat(' ', 16);

    if ( $username == 'joksnet' )
    {
        $cu = ( isset($_GET['u']) && is_numeric($_GET['u']) && $_GET['u'] > 0 ) ? $_GET['u'] : $user_id;

        if ( $q == 'todo' )
            $users = create_users_select('form', true);
        else
            $users = create_users_select();
    }
    else
        $cu = $user_id;

    for ( $i = 1; $i <= 12; $i++ )
    {
        $M = date('M', mktime(0, 0, 0, $i, 1, $Y));
        $selected = ( $i == $m ) ? ' selected="selected"' : '';
        $month_options .= sprintf('%s<option value="%d"%s>%s</option>', $tabs, $i, $selected, $M) . "\n";
    }

    $flag = true;

    for ( $i = $Y - 2; $i < $Y + 3; $i++ )
    {
        $selected = ( $i == $Y ) ? ' selected="selected"' : '';
        $year_options .= sprintf('%s<option value="%d"%s>%d</option>', $tabs, $i, $selected, $i) . "\n";

        if ( strlen($selected) > 0 )
            $flag = false;
    }

    if ( $flag )
        $year_options .= sprintf('%s<option value="%d" selected="selected">%d</option>', $tabs, $Y, $Y) . "\n";

    echo <<<WELCOME

    <div id="welcome">
        <span>Welcome, {$username}</span>
        <form id="frm" action="" method="get">
            <div class="hiddens">
                <input type="hidden" name="q" id="q" value="$q" />
            </div>
{$users}            <div class="field select">
                <select id="m" name="m">
{$month_options}                </select>
            </div>
            <div class="field select">
                <select id="Y" name="Y">
{$year_options}                </select>
            </div>
            <div class="field go">
                <input type="submit" value="go" class="go" />
            </div>
        </form>
    </div>
    <div id="line"><!-- --></div>
WELCOME;
}

function print_page_footer()
{
    global $is_login;
    global $client_ip;
    global $username;
    global $view;

    $viewonly = '';

    $links = array();
    $links[] = '<a href="javascript:history.go();">refresh</a>';

    if ( $is_login && !( isset($_POST['username']) ) )
    {
        $links[] = '<a href="?q=log">resume</a>';
        $links[] = '<a href="?q=todo">todo</a>';
        $links[] = '<a href="?q=food">food</a>';
        $links[] = '<a href="?q=repository">repository</a>';

        if ( $username == 'joksnet' )
            $links[] = '<a href="?q=financials">financials</a>';

        $links[] = '<a href="?q=logout">logout</a>';

        $viewonly = ( $view ) ? ' | view only' : '';
    }

    $str_links = implode(' | ', $links);

    $Y = date('Y');
    $Y = ( 2007 == $Y ) ? $Y : '2007-' . $Y;

    echo <<<FOOT

    <div id="footer">
        {$str_links}$viewonly | {$client_ip} | Copyright {$Y} Bundle Software
    </div>
</body>
</html>
FOOT;
}

function print_simple_message( $page_id, $message )
{
    print_page_header($page_id);
    printf('<p id="message">%s</p>', $message);
    print_page_footer();

    exit();
}

$is_login = ( isset($_SESSION['i3t']) || isset($_POST['username']) );

if ( !( $is_login ) )
{
    if ( $_GET['q'] == 'javascript' )
        exit();

    if ( $_GET['a'] == 'javascript' )
        exit();

    print_page_header('login');

    $view_only_hidden = '';
    $view_only = '';

    if ( $external )
    {
        $view_only_hidden = <<<VIEWONLY

        <div class="hiddens">
            <input type="hidden" id="view" name="view" value="1" />
        </div>
VIEWONLY;
    }
    else
    {
        $view_only = <<<VIEWONLY

        <div class="field checkbox">
            <label for="view">view only</label>
                <input type="checkbox" id="view" name="view" value="1" />
            <br />
        </div>
VIEWONLY;
    }

    echo <<<LOGIN

    <form id="frm" action="?q=login" method="post">$view_only_hidden
        <div class="field">
            <label for="username">username</label>
                <input type="text" id="username" name="username" maxlength="18" />
            <br />
        </div>
        <div class="field">
            <label for="password">password</label>
                <input type="password" id="password" name="password" maxlength="32" />
            <br />
        </div>$view_only
        <div class="field submit">
            <label>&nbsp;</label>
                <input type="submit" value="login" class="go" />
            <br />
        </div>
    </form>
LOGIN;

    print_page_footer();
    exit();
}

$db = @mysql_connect('localhost', 'root', '');
$db = @mysql_select_db('i3t');

function update_log( $user_id )
{
    $time = time();

    $m = date('m');
    $d = date('d');
    $Y = date('Y');

    $today_start = mktime(0, 0, 0, $m, $d, $Y);
    $today_end = mktime(23, 59, 59, $m, $d, $Y);

    $sql = 'SELECT users_log.log_id
            FROM users_log
            WHERE users_log.user_id = "' . $user_id . '"
            AND users_log.login > "' . $today_start . '"
            AND users_log.login < "' . $today_end . '"';

    if ( !( $result = @mysql_query($sql) ) )
        print_simple_message('login', strtolower(mysql_error()));

    if ( @mysql_num_rows($result) > 0 )
    {
        list($log_id) = mysql_fetch_row($result);

        $sql = 'UPDATE users_log
                SET logout = "' . $time . '"
                WHERE log_id = "' . $log_id . '"';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('login', strtolower(mysql_error()));
    }
    else
    {
        $sql = 'INSERT INTO users_log (user_id, login, logout)
                VALUES ("' . $user_id . '", "' . $time . '", "' . $time . '")';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('login', strtolower(mysql_error()));

        $log_id = mysql_insert_id();
    }

    return $log_id;
}

function calculate_work( $login, $logout, $extras = 0 )
{
    list($H1, $i1, $s1) = explode(':', $login);
    list($H2, $i2, $s2) = explode(':', $logout);

    $H2 = $H2 + $extras;

    $H3 = $H2 - $H1;
    $i3 = $i2 - $i1;
    $s3 = $s2 - $s1;

    if ( $i3 < 0 )
    {
        $i3 = 60 + $i3;
        $H3--;
    }

    if ( $s3 < 0 )
    {
        $s3 = 60 + $s3;
        $i3--;

        if ( $i3 < 0 )
        {
            $i3 = 60 + $i3;
            $H3--;
        }
    }

    $H3 = str_pad($H3, 2, '0', STR_PAD_LEFT);
    $i3 = str_pad($i3, 2, '0', STR_PAD_LEFT);
    $s3 = str_pad($s3, 2, '0', STR_PAD_LEFT);

    return $H3 . ':' . $i3 . ':' . $s3;
}

function calculate_color( $work, $hours = 7 )
{
    list($H, $i, $s) = explode(':', $work);

    if ( $H >= $hours )
        $color = 'green';
    elseif ( $H == $hours - 1 && $i >= 45 )
        $color = 'green';
    elseif ( $H >= $hours - 2 )
        $color = 'orange';
    else
        $color = 'red';

    return $color;
}

function calculate_sum( $sum0, $sum1 )
{
    list($H1, $i1, $s1) = explode(':', $sum0);
    list($H2, $i2, $s2) = explode(':', $sum1);

    $H3 = $H1 + $H2;
    $i3 = $i1 + $i2;
    $s3 = $s2 + $s1;

    if ( $s3 >= 60 )
    {
        $ts = floor( $s3 / 60 );
        $s3 = $s3 - ( $ts * 60 );
        $i3 = $i3 + $ts;
    }

    if ( $i3 >= 60 )
    {
        $ti = floor( $i3 / 60 );
        $i3 = $i3 - ( $ti * 60 );
        $H3 = $H3 + $ti;
    }

    $H3 = str_pad($H3, 2, '0', STR_PAD_LEFT);
    $i3 = str_pad($i3, 2, '0', STR_PAD_LEFT);
    $s3 = str_pad($s3, 2, '0', STR_PAD_LEFT);

    return $H3 . ':' . $i3 . ':' . $s3;
}

function get_month_days( $month, $year = null )
{
    $year = ( $year ) ? $year : date('Y');

    return cal_days_in_month(CAL_GREGORIAN, $month, $year);

    switch ( $month )
    {
        case  1: $month_total = 31; break;
        case  2: $month_total = 28; break;
        case  3: $month_total = 31; break;
        case  4: $month_total = 30; break;
        case  5: $month_total = 31; break;
        case  6: $month_total = 30; break;
        case  7: $month_total = 31; break;
        case  8: $month_total = 31; break;
        case  9: $month_total = 30; break;
        case 10: $month_total = 31; break;
        case 11: $month_total = 30; break;
        case 12: $month_total = 31; break;
    }

    return $month_total;
}

function calculate_month_work( $month, $year = null )
{
    $work = 0;
    $days = array();
    $all = array();

    $holidays = array(array(1), array(), array(24), array(2, 5, 6), array(1, 25), array(20),
                      array(9), array(17), array(), array(12), array(), array(8, 25));

    $year = ( $year ) ? $year : date('Y');
    $month_total = get_month_days($month, $year);


    for ( $d = 1; $d <= $month_total; $d++ )
    {
        $all[] = $d;

        if ( !( in_array($d, $holidays[$month - 1]) ) )
        {
            $time = mktime(0, 0, 0, $month, $d, $year);
            $w = date('w', $time);

            if ( $w > 0 && $w < 6 )
            {
                $days[] = $d;
                $work++;
            }
        }
    }

    return array($work, $days, $all);
}

function create_users_select( $for = 'form', $anyone = false )
{
    global $cu;

    $sql = 'SELECT users.user_id
                 , users.username
                 , users.hours
            FROM users';

    if ( !( $result = @mysql_query($sql) ) )
        print_simple_message('login', strtolower(mysql_error()));

    if ( @mysql_num_rows($result) == 0 )
        return '';
    else
    {
        $for = ( is_null($for) ) ? 'form' : $for;
        $users = '';

        while ( $row = @mysql_fetch_assoc($result) )
        {
            $user_id = $row['user_id'];
            $username = $row['username'];
            $hours = $row['hours'];

            if ( ( $for != 'todo' && $_GET['q'] != 'todo' ) || isset($_GET['u']) && (int) $_GET['u'] > 0 )
                $selected = ( $user_id == $cu ) ? ' selected="selected"' : '';

            $users .= sprintf('%s<option value="%d"%s>%s (%dh)</option>', str_repeat(' ', 4 * 5), $user_id, $selected, $username, $hours) . "\n";
        }

        if ( $for == 'todo' )
            $anyone = true;

        if ( $anyone )
        {
            $users = sprintf('%s<option value="0"%s>%s</option>', str_repeat(' ', 4 * 5), ' selected="selected"', 'Anyone') . "\n"
                   . sprintf('%s<option value="0"%s>%s</option>', str_repeat(' ', 4 * 5), ' disabled="disabled"', '--------') . "\n"
                   . $users;
        }

        if ( $for == 'form' )
        {
            return str_repeat(' ', 4 * 3) . '<div class="field select">' . "\n"
                 . str_repeat(' ', 4 * 4) . '<select id="u" name="u">' . "\n" . $users
                 . str_repeat(' ', 4 * 4) . '</select>' . "\n"
                 . str_repeat(' ', 4 * 3) . '</div>' . "\n";
        }
        else
        {
            $id = ( strlen($for) > 0 ) ? 'u_' . $for : 'u';

            return str_repeat(' ', 4 * 4) . '<select id="' . $id . '" name="' . $id . '">' . "\n" . $users
                 . str_repeat(' ', 4 * 4) . '</select>' . "\n";
        }
    }
}

function __init()
{
    global $userdata, $username;
    global $user_id, $log_id;
    global $view, $hours;
    global $Y, $m;

    $userdata = unserialize($_SESSION['i3t']);

    $user_id = $userdata['user_id'];
    $view = ( $userdata['view'] ) ? true : false;

    $username = $userdata['username'];
    $hours = $userdata['hours'];

    if ( !( $view ) )
    {
        $log_id = update_log($user_id);

        $_SESSION['i3t'] = serialize(
            array(
                'user_id' => $user_id,
                'username' => $username,
                'hours' => $hours,
                'log_id' => $log_id,
                'view' => $view
            )
        );
    }

    if ( isset($_GET['Y']) && is_numeric($_GET['Y']) )
        $Y = $_GET['Y'];
    else
        $Y = date('Y');

    if ( isset($_GET['m']) && $_GET['m'] >= 1 && $_GET['m'] <= 12 )
        $m = $_GET['m'];
    else
        $m = date('m');
}

function redirect( $url = null )
{
    /**
     * echo '
     * <script language="javascript" type="text/javascript">
     *     document.location.href = "' .  html_entity_decode($url) . '";
     * </script>';
     */

    if ( strlen($url) > 0 )
    {
        if ( headers_sent() )
            exit();
        else
            header("Location: " . $url);
        exit();
    }
}

function highlight_php( $code )
{
    ini_set('highlight.comment', 'comment');
    ini_set('highlight.default', 'default');
    ini_set('highlight.keyword', 'keyword');
    ini_set('highlight.string', 'string');
    ini_set('highlight.html', 'html');

    $highlighted = highlight_string($code, true);
    $highlighted = str_replace(
        array('<font color="', '<span style="color: ', '</font>', "\r\n", "\n"),
        array('<span class="', '<span class="', '</span>', '<br />', '<br />'),
        $highlighted
    );

    return $highlighted;
}

function highlight_js( $code )
{
    return highlight_php($code);
}

function highlight_css( $code )
{
    return highlight_php($code);
}

function highlight_html( $code )
{
    return highlight_php($code);
}

function mktime2( $string )
{
    global $d, $m, $Y;

    $tmp = explode(':', $string);
    $time = mktime( $tmp[0], $tmp[1], $tmp[2], $m, $d, $Y );

    return $time;
}

if ( isset($_POST['username']) && !( isset($_GET['q']) ) )
    $_GET['q'] = 'login';

switch ( $_GET['q'] )
{
    case 'login':
        if ( $_GET['a'] == 'javascript' )
            exit();

        if ( isset($_POST['username']) && isset($_POST['password']) )
        {
            $username = htmlentities(trim($_POST['username']));
            $password = htmlentities(trim($_POST['password']));

            if ( $external )
                $view = true;
            else
                $view = ( $_POST['view'] == 1 ) ? true : false;

            if ( strlen($username) == 0 || strlen($password) == 0 )
            {
                print_simple_message('login', 'login params not sets');
                exit();
            }

            $sql = 'SELECT users.user_id
                         , users.hours
                    FROM users
                    WHERE users.username = "' . $username . '"
                    AND users.password = "' . $password . '"
                    LIMIT 0,1';

            if ( !( $result = @mysql_query($sql) ) )
                print_simple_message('login', strtolower(mysql_error()));

            if ( @mysql_num_rows($result) != 1 )
                print_simple_message('login', 'bad user or password');
            else
            {
                list($user_id, $hours) = mysql_fetch_row($result);

                if ( !( $view ) )
                    $log_id = update_log($user_id);

                $_SESSION['i3t'] = serialize(
                    array(
                        'user_id' => $user_id,
                        'username' => $username,
                        'hours' => $hours,
                        'log_id' => $log_id,
                        'view' => $view
                    )
                );

                header('Location: ?success=login');
            }
        }
        else
            print_simple_message('login', 'login params not sets');
        break;
    case 'logout':
        if ( $_GET['a'] == 'javascript' )
            exit();

        $userdata = unserialize($_SESSION['i3t']);

        if ( !( $userdata['view'] ) )
        {
            $user_id = $userdata['user_id'];
            $log_id = update_log($user_id);
        }

        $_SESSION['i3t'] = null;

        header('Location: ?success=logout');
        break;
    case 'javascript':
        echo <<<JS

function \$( element, tagName )
{
    if ( typeof element == 'string' )
    {
        var elementString = element;
        var element = document.getElementById(elementString);
    }

    if ( element == null )
        return null;
    else if ( typeof tagName == 'string' && tagName.length > 0 )
    {
        if ( element.nodeType == 1 && element.tagName.toLowerCase() == tagName.toLowerCase() )
            return element;
        else
            return \$(element.parentNode, tagName);
    }

    return element;
}

function \$new( type, parent, attribs )
{
    var element = null;
    var textNode = ( type == 'string' || type == 'text' );

    if ( textNode )
        element = document.createTextNode(attribs);
    else
    {
    	if ( document.createElementNS )
    		element = document.createElementNS("http://www.w3.org/1999/xhtml", type);
    	else
    		element = document.createElement(type);
    }

    if ( typeof parent != 'undefined' && parent )
    {
        if ( typeof parent == 'string' )
            parent = \$(parent);

        parent.appendChild(element);
    }

    if ( !( textNode ) )
    	if ( typeof attribs == 'object' )
    	    for ( var k in attribs )
    	        element.setAttribute(k, attribs[k]);

	return element;
}

function \$extend( destination, source )
{
    for ( var property in source )
        destination[property] = source[property];

    return destination;
}

function \$ajax( url, parameters, events, options )
{
    var __this__ = this;

    this.XML_READY_STATE_UNINITIALIZED = 0;
    this.XML_READY_STATE_LOADING       = 1;
    this.XML_READY_STATE_LOADED        = 2;
    this.XML_READY_STATE_INTERACTIVE   = 3;
    this.XML_READY_STATE_COMPLETED     = 4;

    this.toQueryString = function( source )
    {
        var queryString = [];

        for ( var property in source )
            queryString.push(encodeURIComponent(property)
                + '='
                + encodeURIComponent(source[property])
            );

        return queryString.join('&');
    }

    this.getTransport = function()
    {
        var transport = null;
        var ACTIVE_X_IE_CANDIDATES = [
            "MSXML2.xmlHttpObject.5.0",
            "MSXML2.xmlHttpObject.4.0",
            "MSXML2.xmlHttpObject.3.0",
            "MSXML2.XMLHTTP",
            "MICROSOFT.xmlHttpObject.1.0",
            "MICROSOFT.xmlHttpObject.1",
            "MICROSOFT.XMLHTTP"
        ]

        if ( typeof XMLHttpRequest != 'undefined' )
            transport = new XMLHttpRequest();
        else if ( typeof ActiveXObject != 'undefined' )
        {
            for ( var i = 0; i < ACTIVE_X_IE_CANDIDATES.length; i++ )
            {
                var candidate = ACTIVE_X_IE_CANDIDATES[i];

                try {
                    transport = new ActiveXObject(candidate);
                    break;
                }
                catch ( e ) {}
            }
        }

        return transport;
    }

    this.request = function()
    {
        var params = __this__.toQueryString(__this__.parameters);

        if ( __this__.options.method == 'get' )
            __this__.url += ( __this__.url.test('?') ? '&' : '?' ) + params;
        else if ( /Konqueror|Safari|KHTML/.test(navigator.userAgent) )
            params += '&_=';

        try {
            __this__.transport.open(
                __this__.options.method.toUpperCase(),
                __this__.url,
                __this__.options.asynchronous
            );

            __this__.transport.onreadystatechange = __this__.onStateChange;
            __this__.setRequestHeaders();

            __this__.body = ( __this__.options.method == 'post' ) ? ( __this__.options.postBody || params ) : null;
            __this__.transport.send(__this__.body);
        }
        catch ( e ) {}

        return __this__;
    }

    this.setRequestHeaders = function()
    {
        if ( __this__.options.method == 'post' )
        {
            __this__.headers['Content-type'] = __this__.options.contentType +
                ( __this__.options.encoding ? '; charset=' + __this__.options.encoding : '' );
        }

        for ( var name in this.headers )
            __this__.transport.setRequestHeader(name, __this__.headers[name]);

        return __this__;
    }

    this.getHeader = function( name )
    {
        try {
            return __this__.transport.getResponseHeader(name);
        }
        catch ( e ) { return null; }
    }

    this.onStateChange = function()
    {
        var readyState = -1;

        try {
            readyState = __this__.transport.readyState;
        }
        catch ( e ) {}

        if ( readyState > __this__.XML_READY_STATE_LOADING )
        {
            var events = ['Uninitialized', 'Loading', 'Loaded', 'Interactive', 'Complete']
            var state = events[readyState];

            switch ( readyState )
            {
                case __this__.XML_READY_STATE_COMPLETED:
                    try {
                        __this__.complete = true;

                        __this__.responses.text = __this__.getResponseText();
                        __this__.responses.eval = __this__.getResponseTextEval();
                        __this__.responses.xml = __this__.getResponseXML();
                        __this__.responses.json = __this__.getResponseJSON();
                    }
                    catch ( e ) {}
                    break;
            }

            try {
                __this__.events['on' + state](__this__.transport, __this__.responses[__this__.options.responseType]);
            }
            catch ( e ) {}

            if ( readyState == __this__.XML_READY_STATE_COMPLETED )
                __this__.transport.onreadystatechange = function() {}
        }

        return __this__;
    }

    this.getResponseText = function()
    {
        return __this__.transport.responseText;
    }

    this.getResponseTextEval = function()
    {
        try {
            return eval(__this__.transport.responseText);
        }
        catch ( e ) {}
    }

    this.getResponseXML = function()
    {
        return __this__.transport.responseXML;
    }

    this.getResponseJSON = function()
    {
        try {
            return ( __this__.getHeader('Content-Type').search('json') )
                ? eval('(' + __this__.getResponseText() + ')')
                : null;
        }
        catch ( e ) { return null; }
    }

    this.setHeader = function( name, value ) { __this__.headers[name] = value; return __this__; }
    this.setOption = function( name, value ) { __this__.options[name] = value; return __this__; }
    this.setParameter = function( name, value ) { __this__.parameters[name] = value; return __this__; }
    this.setEvent = function( name, value ) { __this__.events[name] = value; return __this__; }

    this.headers = {
        'X-Requested-With' : 'XMLHttpRequest',
        'Accept' : 'text/javascript, text/html, application/json, application/xml, text/xml, */*'
    }
    this.options = {
        'url'          : url,
        'parameters'   : parameters,
        'events'       : events,
        'method'       : 'post',
        'asynchronous' : true,
        'contentType'  : 'application/x-www-form-urlencoded',
        'encoding'     : 'utf-8',
        'responseType'     : 'json'
    }
    this.events = {
        'onUninitialized' : function() {},
        'onLoading'       : function() {},
        'onLoaded'        : function() {},
        'onInteractive'   : function() {},
        'onComplete'      : function() {},
        'onError'         : function() {}
    }
    this.responses = {
        'text' : '',
        'eval' : null,
        'xml'  : null,
        'json' : null
    }

    this.complete = false;

    this.url = url;
    this.parameters = parameters;
    this.transport = this.getTransport();

    \$extend(this.options, options);
    \$extend(this.events, events);

    // this.request();
}
JS;
        exit();
        break;
    case 'todo':
        __init();

        switch ( $_GET['a'] )
        {
            case 'javascript':
                echo <<<JS

var Todo = {
    showAddItem : function()
    {
        \$('item_new').className = "new";
        \$('item').focus();
    },

    foldProject : function( project_id )
    {
        var project = \$('project_' + project_id);
        var project_link = \$('project_link_' + project_id);

        project_link.href = "javascript:Todo.unfoldProject(" + project_id + ");";
        project_link.innerHTML = '+';
        project.style.display = 'none';
    },

    unfoldProject : function( project_id )
    {
        var project = \$('project_' + project_id);
        var project_link = \$('project_link_' + project_id);

        project_link.href = "javascript:Todo.foldProject(" + project_id + ");";
        project_link.innerHTML = '-';
        project.style.display = '';
    },

    changeState : function( todo_id )
    {
        document.location.href = '?q=todo&a=change&tid=' + todo_id;
    },

    showCompleted : function()
    {
        var link = \$('all');
            link.href = "javascript:Todo.hideCompleted(this);";
            link.innerHTML = "hide completed";

        var elementsTr = document.getElementsByTagName('tr');

        for ( i in elementsTr )
        {
            var elementTr = elementsTr[i];

            if ( typeof elementTr != 'undefined' )
            {
                if ( elementTr.nodeType == document.ELEMENT_NODE )
                {
                    if ( elementTr.tagName.toLowerCase() == 'tr' )
                    {
                        if ( elementTr.className.indexOf('addon') != -1 )
                        {
                            var newClass = []
                            var classes = elementTr.className.split(' ');

                            for ( e in classes )
                            {
                                var currentClass = classes[e];

                                if ( currentClass != 'addon' )
                                    newClass.push(currentClass);
                            }

                            elementTr.className = newClass.join(' ');
                        }
                    }
                }
            }
        }
    },

    hideCompleted : function()
    {
        var link = \$('all');
            link.href = "javascript:Todo.showCompleted(this);";
            link.innerHTML = "show completed";

        var elementsTr = document.getElementsByTagName('tr');

        for ( i in elementsTr )
        {
            var elementTr = elementsTr[i];

            if ( typeof elementTr != 'undefined' )
            {
                if ( elementTr.nodeType == document.ELEMENT_NODE )
                {
                    if ( elementTr.tagName.toLowerCase() == 'tr' )
                    {
                        if ( elementTr.className.indexOf('completed') != -1 )
                        {
                            elementTr.className += ' addon';
                        }
                    }
                }
            }
        }
    }
}
JS;
                exit();
                break;
            case 'change':
                if ( (int) $_GET['tid'] > 0 )
                {
                    $sql = 'UPDATE todo
                            SET todo_completed = NOT todo_completed
                              , todo_completed_date = "' . time() . '"
                              , todo_completed_user = "' . $user_id . '"
                            WHERE todo_id = "' . $_GET['tid'] . '"';

                    if ( !( $result = @mysql_query($sql) ) )
                        print_simple_message('todo', strtolower(mysql_error()));
                }

                redirect('?q=todo');
                break;
            case 'item_add':
                $priority = $_POST['priority'];
                $project = (int) $_POST['project'];
                $item = $_POST['item'];

                if ( strlen($priority) > 0 && $project > 0 && strlen($item) > 0 )
                {
                    $u = 0;

                    if ( $username == 'joksnet' && (int) $_POST['u_todo'] >= 0 )
                        $u = $_POST['u_todo'];
                    else
                        $u = $user_id;

                    $sql = 'INSERT INTO todo ( todo_priority, user_id, project_id, todo )
                            VALUES ( "' . $priority . '", "' . $u . '", "' . $project . '", "' . $item . '" )';

                    if ( !( $result = @mysql_query($sql) ) )
                        print_simple_message('todo', strtolower(mysql_error()));
                }

                redirect('?q=todo');
                break;
        }

        print_page_header('todo');
        print_page_welcome();

        if ( (int) $_GET['u'] > 0 && $username == 'joksnet' )
            $where = 'WHERE ( users.user_id = "' . $_GET['u'] . '" OR users.user_id = 0 )';
        else
            $where = ( $username == 'joksnet' ) ? '' : 'WHERE ( users.user_id = ' . $user_id . ' OR users.user_id = 0 )';

        $sql = 'SELECT todo_projects.project_id
                     , todo_projects.project_name
                     , todo.todo_id
                     , todo.todo_priority
                     , todo.todo_completed
                     , todo.todo_completed_date
                     , todo.todo
                     , users.user_id
                     , users.username
                     , completed_users.user_id AS completed_user_id
                     , completed_users.username AS completed_username
                FROM todo_projects
                LEFT JOIN todo
                       ON ( todo.project_id = todo_projects.project_id )
                LEFT JOIN users
                       ON ( users.user_id = todo.user_id )
                LEFT JOIN users AS completed_users
                       ON ( completed_users.user_id = todo.todo_completed_user )
                ' . $where . '
                ORDER BY todo_projects.project_name
                       , todo.todo_completed
                       , todo.todo_priority DESC';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('todo', strtolower(mysql_error()));

        if ( ( $num_rows = mysql_num_rows($result) ) > 0 )
        {
            $content = '';
            $i = 0; $row = mysql_fetch_assoc($result);

            while ( $i < $num_rows )
            {
                $empty = false;

                $project_id = $row['project_id'];
                $project_name = $row['project_name'];

                $todos = '';
                $style = '';

                while ( $i < $num_rows && $project_id == $row['project_id'] )
                {
                    $style = ( $style == 'row1' ) ? 'row2' : 'row1';

                    $todo_id = $row['todo_id'];
                    $todo_priority = $row['todo_priority'];
                    $todo_completed = $row['todo_completed'];
                    $todo_completed_date = $row['todo_completed_date'];
                    $todo = $row['todo'];
                    $todo_user_id = $row['user_id'];
                    $todo_username = ( $todo_user_id == 0 ) ? 'Anyone' : $row['username'];

                    if ( !( is_null($todo_id) ) && $todo_id > 0 )
                    {
                        $user_td = ( $username == 'joksnet' ) ? "\n                    <td>$todo_username</td>" : '';
                        $checked = ( $todo_completed == 1 ) ? ' checked="checked"' : '';

                        $todo_completed_date = ( $todo_completed == 1 && $todo_completed_date > 0 ) ? '<span class="date">' . date('M D d', $todo_completed_date) . '</span> ' : '';
                        $todo_completed_username = ( $todo_completed == 1 && $row['completed_user_id'] > 0 ) ? ' <span class="username">by ' . $row['completed_username'] . '</span>.' : '';

                        if ( $todo_completed == 1 )
                            $style .= ' completed addon';

                        $todos .= <<<TODOS

                <tr class="$style">
                    <td><input type="checkbox" name="todo_$todo_id" id="todo_$todo_id" value="1"$checked onclick="Todo.changeState($todo_id);" /></td>
                    <td><span class="$todo_priority">$todo_priority</span></td>$user_td
                    <td class="todo" colspan="2">$todo_completed_date$todo$todo_completed_username</td>
                </tr>
TODOS;
                    }
                    else
                    {
                        $empty = true;
                        $colspan = ( $username == 'joksnet' ) ? 5 : 4;
                        $todos .= <<<TODOS

                <tr>
                    <td colspan="$colspan">empty project</td>
                </tr>
TODOS;
                    }

                    $i++; $row = mysql_fetch_assoc($result);
                }

                $project_display = ( $empty ) ? ' style="display: none;"' : '';
                $project_link = ( $empty )
                    ? "<a href=\"javascript:Todo.unfoldProject($project_id);\" id=\"project_link_$project_id\">+</a>"
                    : "<a href=\"javascript:Todo.foldProject($project_id);\" id=\"project_link_$project_id\">-</a>";

                $colspan = ( $username == 'joksnet' ) ? 4 : 3;
                $content .= <<<PROJECT

            <tbody class="project">
                <tr>
                    <th>$project_link</th>
                    <td colspan="$colspan">$project_name</td>
                </tr>
            </tbody>
            <tbody id="project_$project_id"$project_display>$todos
            </tbody>
PROJECT;
            }
        }
        else
        {
            $colspan = ( $username == 'joksnet' ) ? 5 : 4;
            $content = <<<NONE

            <tbody>
                <tr>
                    <td colspan="$colspan">nothing found</td>
                </tr>
            </tbody>
NONE;
        }

        $user_th = ( $username == 'joksnet' ) ? "\n                    <th>user</th>" : '';
        $user_td = ( $username == 'joksnet' ) ? "\n                    <td style=\"width: 98px;\">\n" . create_users_select('todo') . "                    </td>" : '';

        $sql = 'SELECT project_id
                     , project_name
                     , project_default
                FROM todo_projects
                ORDER BY project_name';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('todo', strtolower(mysql_error()));

        while ( list($project_id, $project_name, $project_default) = mysql_fetch_row($result) )
        {
            $selected = ( $project_default ) ? ' selected="selected"' : '';
            $projects .= <<<PROJECT

                                <option value="$project_id" label="$project_name"$selected>$project_name</option>
PROJECT;
        }

        mysql_free_result($result);

        echo <<<TODO

    <div class="h1">
        <span>Todo</span>
        <div>
            <a href="javascript:Todo.showCompleted(this);" id="all">show completed</a> |
            <a href="javascript:Todo.showAddItem();">add item</a>
        </div>
    </div>
    <form id="item_add" class="page" action="?q=todo&amp;a=item_add" method="post">
        <table id="items" width="768" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 45px;">#</th>
                    <th style="width: 98px;">priority</th>$user_th
                    <th style="width: 98px;">project</th>
                    <th>item</th>
                </tr>
            </thead>
            <tfoot id="item_new" class="new addon">
                <tr>
                    <td>new</td>
                    <td>
                        <select name="priority" id="priority">
                            <option value="low" class="low">low</option>
                            <option value="normal" class="normal" selected="selected">normal</option>
                            <option value="high" class="high">high</option>
                        </select>
                    </td>$user_td
                    <td>
                        <select name="project" id="project">$projects
                        </select>
                    </td>
                    <td>
                        <input type="text" name="item" id="item" value="" style="width: 87%;" />
                        <input type="submit" value="add" class="go" />
                    </td>
                </tr>
            </tfoot>$content
        </table>
    </form>
TODO;

        print_page_footer();
        break;
    case 'food':
        __init();

        function get_foods( $format = 'options' )
        {
            $sql = 'SELECT food.food_id
                         , food.food_name
                    FROM food';

            if ( !( $result = @mysql_query($sql) ) )
                print_simple_message('food', strtolower(mysql_error()));

            $return = '';
            $javascript = array();

            while ( list($food_id, $food_name) = mysql_fetch_row($result) )
            {
                switch ( $format )
                {
                    case 'options':
                        $spaces = str_repeat(' ', 5 * 4);
                        $return .= "\n$spaces<option value=\"$food_id\">$food_name</option>";
                        break;
                    case 'javascript':
                        $food_name = addslashes($food_name);
                        $javascript[] = "$food_id : '$food_name'";
                        break;
                }
            }

            if ( $format == 'javascript' )
                $return = '{' . implode(', ', $javascript) . '}';

            return $return;
        }

        switch ( $_GET['a'] )
        {
            case 'javascript':
                $foods = get_foods('javascript');
                echo <<<JS

var Food = {

    foods : $foods,

    showToday : function()
    {
        \$('today_add').style.display = 'block';
    },

    hideToday : function()
    {
        \$('today_add').style.display = 'none';
    },

    appendTodayFood : function()
    {
        var foodCount = \$('food_count');
        var fields = \$('fields');

        var newField = \$new('div', fields, { id : 'food_' + foodCount.value, class : 'food' });

        var amountField = \$new('div', newField, { class : 'field' });
        var foodField = \$new('div', newField, { class : 'field select' });

        \$new('label', amountField, { for : 'today_amount_' + foodCount.value }).innerHTML = 'Amount';
        \$new('label', foodField, { for : 'today_food_' + foodCount.value }).innerHTML = 'Food';

        \$new('input', amountField, {
            type : 'text',
            size : 1,
            id : 'today_amount_' + foodCount.value,
            name : 'today_amount_' + foodCount.value,
            value : 1
        });

        var select = \$new('select', foodField, {
            id : 'today_food_' + foodCount.value,
            name : 'today_food_' + foodCount.value
        });

        var actions = \$new('span', foodField, { class : 'actions' });

        \$new('option', select, {
            selected : 'selected',
            disabled : 'disabled',
            value : '-'
        }).innerHTML = '-Foods-';

        for ( i in Food.foods )
            \$new('option', select, { value : i }).innerHTML = Food.foods[i];

        \$new('a', actions, { href : 'javascript:Food.removeFood(' + foodCount.value + ');' }).innerHTML = 'remove';

        foodCount.value = parseInt( foodCount.value ) + 1;
    },

    addFood : function()
    {
        var foodCount = \$('food_count');
        var fields = \$('fields');

        var newField = \$new('div', fields, { id : 'food_' + foodCount.value, class : 'food' });

        var amountField = \$new('div', newField, { class : 'field' });
        var foodField = \$new('div', newField, { class : 'field select' });

        \$new('label', amountField, { for : 'today_amount_' + foodCount.value }).innerHTML = 'Amount';
        \$new('label', foodField, { for : 'today_food_' + foodCount.value }).innerHTML = 'Food';

        \$new('input', amountField, {
            type : 'text',
            size : 1,
            id : 'today_amount_' + foodCount.value,
            name : 'today_amount_' + foodCount.value,
            value : 1
        });

        \$new('input', foodField, {
            type : 'text',
            id : 'today_food_new_' + foodCount.value,
            name : 'today_food_new_' + foodCount.value,
            class : 'new',
            value : ''
        });

        var actions = \$new('span', foodField, { class : 'actions' });

        \$new('a', actions, { href : 'javascript:Food.removeFood(' + foodCount.value + ');' }).innerHTML = 'remove';
        \$new('string', actions, ' or ');
        \$new('a', actions, { href : 'javascript:Food.saveFood();' }).innerHTML = 'save';

        foodCount.value = parseInt( foodCount.value ) + 1;
    },

    removeFood : function( food_id )
    {
        var foodCount = \$('food_count');
        var fields = \$('fields');
            fields.removeChild( \$('food_' + food_id) );
            foodCount.value = parseInt( foodCount.value ) - 1;
    },

    checkForm : function( frm )
    {
        var check = true;
            check = ( \$('food_count').value > 0 );

        return check;
    },

    foldDay : function( day_number )
    {
        var day = \$('day_' + day_number);
        var day_link = \$('day_link_' + day_number);
        var day_subtotal = \$('day_subtotal_' + day_number);

        day_link.href = "javascript:Food.unfoldDay(" + day_number + ");";
        day_link.innerHTML = '+';
        day.style.display = 'none';
        day_subtotal.style.fontSize = '11px';
    },

    unfoldDay : function( day_number )
    {
        var day = \$('day_' + day_number);
        var day_link = \$('day_link_' + day_number);
        var day_subtotal = \$('day_subtotal_' + day_number);

        day_link.href = "javascript:Food.foldDay(" + day_number + ");";
        day_link.innerHTML = '-';
        day.style.display = '';
        day_subtotal.style.fontSize = '0pt';
    }
}
JS;
                exit();
                break;
            case 'today_add':
                $food_count = (int) $_POST['food_count'];

                if ( $food_count > 0 )
                {
                    for ( $i = 0; $i < $food_count; $i++ )
                    {
                        $time = time();

                        if ( isset($_POST['today_d']) )
                        {
                            $tmp = explode(' ', date('H i s m Y'));
                            $time = mktime($tmp[0], $tmp[1], $tmp[2], $tmp[3], $_POST['today_d'], $tmp[4]);
                        }

                        $food_id = false;
                        $food_amount = false;
                        $food_cost = 0;

                        if ( isset($_POST['today_amount_' . $i]) )
                            $food_amount = (float) $_POST['today_amount_' . $i];

                        switch ( true )
                        {
                            case isset($_POST['today_food_' . $i]) && $_POST['today_food_' . $i] != '-':
                                $food_id = (int) $_POST['today_food_' . $i];
                                break;
                            case isset($_POST['today_food_new_' . $i]) && strlen($_POST['today_food_new_' . $i]) > 0:
                                break;
                        }

                        if ( $food_id !== false && $food_amount !== false )
                        {
                            if ( !( $result = @mysql_query('SELECT food_cost FROM food WHERE food_id = "' . $food_id . '"') ) )
                                print_simple_message('food', strtolower(mysql_error()));

                            list($food_cost) = mysql_fetch_row($result);

                            //-->

                            $sql = 'INSERT INTO food_log ( food_id, log_date, log_amount )
                                    VALUES ( "' . $food_id . '", "' . $time . '", "' . $food_amount . '" )';

                            if ( !( $result = @mysql_query($sql) ) )
                                print_simple_message('food', strtolower(mysql_error()));

                            $log_id = mysql_insert_id();

                            //-->

                            $sql = 'INSERT INTO food_financials ( log_id, financial_date, money_food, description )
                                    VALUES ( "' . $log_id . '", "' . $time . '", "' . $food_cost . '", "." )';

                            if ( !( $result = @mysql_query($sql) ) )
                                print_simple_message('food', strtolower(mysql_error()));
                        }
                    }
                }

                redirect("?q=food&m=$m&Y=$Y");
                break;
            case 'menu':
                print_page_header('food');
                print_page_welcome();

                echo <<<MENU

    <div class="h1">
        <span>Menu</span>
    </div>
MENU;

                print_page_footer();
                exit();
                break;
        }

        $today = false;

        $total = 0;
        $total_money = 0;

        $pretotal = 0;
        $pretotal_money = 0;

        $m2 = ( $m == 12 ) ? 1 : $m + 1;

        $month_start = mktime(0, 0, 0, $m, 1, $Y);
        $month_end = mktime(0, 0, 0, $m2, 1, $Y);

        //-->

        $sql = 'SELECT SUM(financials.money_account - financials.money_food)
                FROM food_financials AS financials
                WHERE financials.financial_date < "' . $month_start . '"
                   OR financials.log_id = 0';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('food', strtolower(mysql_error()));

        list($pretotal_money) = mysql_fetch_row($result);

        //-->

        $sql = 'SELECT SUM(food.food_cost * food_log.log_amount)
                FROM food_log
                INNER JOIN food
                        ON ( food.food_id = food_log.food_id )
                WHERE food_log.log_date < "' . $month_start . '"
                   OR food_log.log_date > "' . $month_end . '"';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('food', strtolower(mysql_error()));

        list($pretotal) = mysql_fetch_row($result);

        //-->

        $sql = 'SELECT food_log.log_id
                     , food_log.log_date
                     , food_log.log_amount
                     , food.food_id
                     , food.food_name
                     , food.food_cost
                     , food_financials.financial_id
                     , food_financials.money_food
                FROM food_log
                INNER JOIN food
                        ON ( food.food_id = food_log.food_id )
                LEFT JOIN food_financials
                       ON ( food_financials.log_id = food_log.log_id )
                WHERE food_log.log_date > "' . $month_start . '"
                  AND food_log.log_date < "' . $month_end . '"';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('food', strtolower(mysql_error()));

        $current_money = $pretotal_money;

        if ( ( $num_rows = mysql_num_rows($result) ) > 0 )
        {
            $content = '';
            $row = mysql_fetch_assoc($result); $i = 0;

            while ( $i < $num_rows )
            {
                $subtotal = 0;
                $foods = '';
                $row_style = '';

                $M = date('M', $row['log_date']);
                $d = intval( date('d', $row['log_date']) );

                while ( $i < $num_rows && $d == date('d', $row['log_date']) )
                {
                    $row_style = ( $row_style == 'row1' ) ? 'row2' : 'row1';

                    $food_amount = $row['log_amount'];
                    $food_name = $row['food_name'];

                    $food_cost = $row['money_food'];

                    if ( $food_cost == 0 )
                        $food_cost = $row['food_cost'];

                    $food_cost = $food_cost * $food_amount;

                    $num = $i + 1;

                    $subtotal += $food_cost;
                    $total += $food_cost;

                    $current_money = $current_money - $food_cost;
                    $total_money += $food_cost;

                    $food_amount = number_format($food_amount, 2);
                    $food_cost = number_format($food_cost, 2);
                    $current_money = number_format($current_money, 2);

                    if ( $username == 'joksnet' )
                        $td_money = "\n                <td class=\"cost\">Ar$ $current_money</td>";
                    else
                        $td_money = '';

                    $foods .= <<<CODE

            <tr class="$row_style">
                <td>$num</td>
                <td>$food_amount</td>
                <td>$food_name</td>$td_money
                <td class="cost">$food_cost</td>
            </tr>
CODE;

                    $row = mysql_fetch_assoc($result); $i++;
                }

                $td_colspan = ( $username == 'joksnet' ) ? 3 : 2;

                $subtotal = number_format($subtotal, 2);
                $content .= <<<DAY

        <tbody class="day">
            <tr>
                <th><a href="javascript:Food.foldDay($d);" id="day_link_$d">-</a></th>
                <td colspan="$td_colspan">$M $d</td>
                <td class="cost" id="day_subtotal_$d">$subtotal</td>
            </tr>
        </tbody>
        <tbody id="day_$d">$foods
        </tbody>
DAY;
            }
        }
        else
        {
            $td_colspan = ( $username == 'joksnet' ) ? 5 : 4;
            $content = <<<NONE

        <tbody>
            <tr>
                <td colspan="$td_colspan">nothing found</td>
            </tr>
        </tbody>
NONE;
        }

        $total = number_format($pretotal + $total, 2);
        $total_money = number_format($pretotal_money - $total_money, 2);

        $pretotal = number_format($pretotal, 2);
        $pretotal_money = number_format($pretotal_money, 2);

        print_page_header('food');
        print_page_welcome();

        $th_money = '';
        $th_premoney = '';
        $th_totalmoney = '';

        if ( $username == 'joksnet' )
        {
            $th_money = "\n                <th style=\"width: 95px;\">money</th>";
            $th_premoney = "\n                <th class=\"cost\">Ar$ $pretotal_money</th>";
            $th_totalmoney = "\n                <td class=\"cost\">Ar$ $total_money</td>";
        }

        $day_options = '';
        $day_total = get_month_days($m, $Y);
        $d = date('d');

        $tabs = str_repeat(' ', 7*4);

        for ( $i = 1; $i <= $day_total; $i++ )
        {
            $selected = ( $d == $i ) ? ' selected="selected"' : '';
            $day_options .= "\n" . sprintf('%s<option value="%d"%s>%d</option>', $tabs, $i, $selected, $i);
        }

        echo <<<FOOD

    <div class="h1">
        <span>Food</span>
        <div>
            <a href="?q=food&amp;a=menu">menu</a> |
            <a href="javascript:Food.showToday();">today</a>
        </div>
    </div>
    <table id="items" width="768" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 45px;">#</th>
                <th style="width: 75px;">amount</th>
                <th>food</th>$th_money
                <th style="width: 75px;">cost</th>
            </tr>
            <tr class="cost">
                <th colspan="3">&nbsp;</th>$th_premoney
                <th class="cost">$pretotal</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="3">&nbsp;</td>$th_totalmoney
                <td class="cost">$total</td>
            </tr>
        </tfoot>$content
    </table>
    <div id="today_add" class="addon">
        <div class="h1">
            <span>Today</span>
            <div>
                <a href="javascript:Food.appendTodayFood();">append</a> |
                <a href="javascript:Food.addFood();">add</a> |
                <a href="javascript:Food.hideToday();">hide</a>
            </div>
        </div>
        <form id="food_add" class="page" action="?q=food&amp;a=today_add&amp;m=$m&amp;Y=$Y" method="post" onsubmit="return Food.checkForm(this);">
            <div class="hiddens">
                <input type="hidden" name="food_count" id="food_count" value="0" />
            </div>
            <div class="field complete">
                <label for="today_d">Day</label>
                <select name="today_d" id="today_d" style="margin-left: 12px;">$day_options
                </select>
            </div>
            <div id="fields"><!-- --></div>
            <div class="field go">
                <input type="submit" value="save" class="go" /> or <a href="javascript:Food.hideToday();">cancel</a>
            </div>
        </form>
    </div>
FOOD;

        print_page_footer();
        break;
    case 'repository':
        __init();

        switch ( $_GET['a'] )
        {
            case 'javascript':
                echo <<<JS

var Repository = {

    showAddCategory : function()
    {
        \$('category_add').style.display = 'block';
        \$('category_name').focus();
    },

    hideAddCategory : function()
    {
        \$('category_add').style.display = 'none';
    },

    showAddCode : function( category_id )
    {
        \$('code_add').style.display = 'block';
        \$('code_category_id').value = category_id;
        \$('code_category_name').innerHTML = \$('category_name_' + category_id).value;
        \$('code_name').focus();
    },

    hideAddCode : function()
    {
        \$('code_add').style.display = 'none';
    },

    foldCategory : function( category_id )
    {
        var category = \$('category_' + category_id);
        var category_link = \$('category_link_' + category_id);

        category_link.href = "javascript:Repository.unfoldCategory(" + category_id + ");";
        category_link.innerHTML = '+';
        category.style.display = 'none';
    },

    unfoldCategory : function( category_id )
    {
        var category = \$('category_' + category_id);
        var category_link = \$('category_link_' + category_id);

        category_link.href = "javascript:Repository.foldCategory(" + category_id + ");";
        category_link.innerHTML = '-';
        category.style.display = '';
    }
}
JS;
                exit();
                break;
            case 'category_add':
                $category_name = $_POST['category_name'];

                if ( strlen($category_name) > 0 )
                {
                    $sql = 'INSERT INTO repository_categories ( category_name )
                            VALUES ( "' . $category_name . '" )';

                    if ( !( $result = @mysql_query($sql) ) )
                        print_simple_message('repository', strtolower(mysql_error()));
                }

                redirect('?q=repository');
                break;
            case 'code_add':
                $category_id = $_POST['code_category_id'];

                $code_name = $_POST['code_name'];
                $code_type = $_POST['code_type'];
                $code_lang = $_POST['code_lang'];
                $code = $_POST['code'];
                $code_time = time();

                if ( strlen($code_name) > 0 && strlen($code) > 0 )
                {
                    $sql = "INSERT INTO repository_categories_codes ( category_id, user_id, code_name, code_type, code_lang, code, code_time )
                            VALUES ( $category_id, $user_id, \"$code_name\", \"$code_type\", \"$code_lang\", \"$code\", $code_time )";

                    if ( !( $result = @mysql_query($sql) ) )
                        print_simple_message('repository', strtolower(mysql_error()));
                }

                redirect('?q=repository');
                break;
        }

        print_page_header('repository');
        print_page_welcome();

        switch ( $_GET['a'] )
        {
            case 'remove':
                if ( !( isset($_GET['cid']) || (int) $_GET['cid'] > 0 ) )
                    print_simple_message('repository', 'code id missing');

                break;
            case 'edit':
                if ( !( isset($_GET['cid']) || (int) $_GET['cid'] > 0 ) )
                    print_simple_message('repository', 'code id missing');

                break;
            case 'view':
                if ( !( isset($_GET['cid']) || (int) $_GET['cid'] > 0 ) )
                    print_simple_message('repository', 'code id missing');

                $sql = 'SELECT codes.code_id
                             , codes.code_name
                             , codes.code_type
                             , codes.code_lang
                             , codes.code
                             , codes.code_time
                             , categories.category_id
                             , categories.category_name
                             , users.user_id
                             , users.username
                        FROM repository_categories_codes AS codes
                        INNER JOIN repository_categories AS categories
                                ON ( categories.category_id = codes.category_id )
                        INNER JOIN users AS users
                                ON ( users.user_id = codes.user_id )
                        WHERE codes.code_id = ' . $_GET['cid'] . '';

                if ( !( $result = @mysql_query($sql) ) )
                    print_simple_message('repository', strtolower(mysql_error()));

                if ( mysql_num_rows($result) == 0 )
                    print_simple_message('repository', 'code not found');

                list($code_id, $code_name, $code_type
                   , $code_lang, $code, $code_time
                   , $code_category_id, $code_category_name
                   , $code_user_id, $code_username) = mysql_fetch_row($result);

                $code_time = date('D d H:i', $code_time);
                $code_highlight_fn = "highlight_$code_lang";
                $code_highlight = $code_highlight_fn($code);

                echo <<<VIEW

    <div class="h1">
        <span>View $code_name @ $code_category_name</span>
        <div>
            <a href="?q=repository&amp;a=edit&amp;cid=$code_id">edit</a> |
            <a href="?q=repository&amp;a=remove&amp;cid=$code_id">remove</a>
        </div>
    </div>
    <div class="codeview">
        <div class="info">by $code_username at $code_time</div>
        <div class="code">
<!-- CODE BEGIN -->
$code_highlight
<!-- CODE END -->
        </div>
    </div>
VIEW;

                break;
            default:
                $title = 'Repository';

                $sql = 'SELECT categories.category_id
                             , categories.category_name
                             , codes.code_id
                             , codes.code_name
                             , codes.code_type
                             , codes.code_lang
                        FROM repository_categories AS categories
                        LEFT JOIN repository_categories_codes AS codes
                               ON ( codes.category_id = categories.category_id )
                        ORDER BY categories.category_name
                               , codes.code_name';

                if ( !( $result = @mysql_query($sql) ) )
                    print_simple_message('repository', strtolower(mysql_error()));

                if ( ( $num_rows = mysql_num_rows($result) ) > 0 )
                {
                    $code_num = 0;
                    $categories = '';

                    $row = mysql_fetch_assoc($result);
                    $row_num = 0;

                    while ( $row_num < $num_rows )
                    {
                        $empty = false;
                        $codes = '';
                        $row_style = '';

                        $category_id = $row['category_id'];
                        $category_name = $row['category_name'];

                        while ( $row_num < $num_rows && $row['category_id'] == $category_id )
                        {
                            $row_style = ( $row_style == 'row1' ) ? 'row2' : 'row1';

                            $code_id = $row['code_id'];
                            $code_name = $row['code_name'];
                            $code_type = $row['code_type'];
                            $code_lang = $row['code_lang'];

                            if ( !( is_null($code_id) ) && $code_id > 0 )
                            {
                                $code_num++;
                                $codes .= <<<CODE

            <tr class="$row_style">
                <td>$code_num</td>
                <td class="name">$code_name</td>
                <td>$code_type</td>
                <td>$code_lang</td>
                <td class="actions"><a href="?q=repository&amp;a=view&amp;cid=$code_id">view</a></td>
            </tr>
CODE;
                            }
                            else
                            {
                                $empty = true;
                                $codes .= <<<CODE

            <tr>
                <td colspan="5">empty category</td>
            </tr>
CODE;
                            }

                            $row = mysql_fetch_assoc($result);
                            $row_num++;
                        }

                        $category_display = ( $empty ) ? ' style="display: none;"' : '';
                        $category_link = ( $empty )
                            ? "<a href=\"javascript:Repository.unfoldCategory($category_id);\" id=\"category_link_$category_id\">+</a>"
                            : "<a href=\"javascript:Repository.foldCategory($category_id);\" id=\"category_link_$category_id\">-</a>";

                        $categories .= <<<CATEGORY

        <tbody class="category">
            <tr>
                <th>$category_link</th>
                <td colspan="3"><input type="hidden" name="category_name_$category_id" id="category_name_$category_id" value="$category_name" />$category_name</td>
                <td class="actions"><a href="javascript:Repository.showAddCode($category_id);">add code</a></td>
            </tr>
        </tbody>
        <tbody id="category_$category_id"$category_display>$codes
        </tbody>
CATEGORY;
                    }

                    $content = <<<CATEGORIES

    <table id="categories" width="768" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>type</th>
                <th>lang</th>
                <th></th>
            </tr>
        </thead>$categories
    </table>
CATEGORIES;
                }
                else
                    $content = "\n" . '    <p id="message">no result</p>';

                echo <<<REPOSITORY

    <div class="h1">
        <span>Repository</span>
        <div>
            <a href="javascript:Repository.showAddCategory();">add category</a>
        </div>
    </div>$content
    <div id="category_add" class="addon">
        <div class="h1">
            <span>New Category</span>
            <div>
                <a href="javascript:Repository.hideAddCategory();">hide</a>
            </div>
        </div>
        <form id="category_form_add" class="page" action="?q=repository&amp;a=category_add" method="post">
            <div class="field">
                <label for="category_name">Name</label>
                <input type="text" name="category_name" id="category_name" value="" />
            </div>
            <div class="field go">
                <input type="submit" value="add category" class="go" /> or <a href="javascript:Repository.hideAddCategory();">cancel</a>
            </div>
        </form>
    </div>
    <div id="code_add" class="addon">
        <div class="h1">
            <span>New Code for <span id="code_category_name"></span></span>
            <div>
                <a href="javascript:Repository.hideAddCode();">hide</a>
            </div>
        </div>
        <form id="code_form_add" class="page" action="?q=repository&amp;a=code_add" method="post">
            <div class="hiddens">
                <input type="hidden" name="code_category_id" id="code_category_id" value="0" />
            </div>
            <div class="field">
                <label for="code_name">Name</label>
                <input type="text" name="code_name" id="code_name" value="" />
            </div>
            <div class="field select">
                <label for="code_type">Type</label>
                <select name="code_type" id="code_type">
                    <option value="fn">Function</option>
                    <option value="class">Class</option>
                    <option value="other" selected="selected">Other</option>
                </select>
            </div>
            <div class="field select">
                <label for="code_lang">Language</label>
                <select name="code_lang" id="code_lang">
                    <option value="php" selected="selected">PHP</option>
                    <option value="js">JavaScript</option>
                    <option value="css">CSS</option>
                    <option value="html">HTML</option>
                </select>
            </div>
            <div class="field textarea">
                <label for="code">Code</label>
                <textarea name="code" id="code" rows="6" cols="78"></textarea>
            </div>
            <div class="field go">
                <input type="submit" value="add code" class="go" /> or <a href="javascript:Repository.hideAddCode();">cancel</a>
            </div>
        </form>
    </div>
REPOSITORY;
                break;
        }

        print_page_footer();
        break;
    case 'financials':
        __init();

        switch ( $_GET['a'] )
        {
            case 'javascript':
                echo <<<JS

var Financials = {

    showAddMovement : function()
    {
        \$('new_movement').className = 'new';
        \$('description').focus();
    },

    onMoneyIn : function()
    {
        \$('money_out').value = '0.00';
    },

    onMoneyOut : function()
    {
        \$('money_in').value = '0.00';
    }
}
JS;
                exit();
                break;
            case 'new_movement':
                $m = intval($m);

                $money_d = (int) $_POST['money_d'];
                $description = $_POST['description'];

                $money_in = floatval( $_POST['money_in'] );
                $money_out = floatval( $_POST['money_out'] );

                list($H, $i, $s) = explode(':', date('H:i:s'));

                if ( ( $money_in > 0 || $money_out ) > 0 && strlen($description) > 0 && $username == 'joksnet' )
                {
                    $time = mktime($H, $i, $s, $m, $money_d, $Y);

                    $sql = 'INSERT INTO financials ( user_id, money_in, money_out, description, financial_date )
                            VALUES ( "' . $user_id . '", "' . $money_in . '", "' . $money_out . '", "' . $description . '", "' . $time . '" )';

                    if ( !( $result = @mysql_query($sql) ) )
                        print_simple_message('financials', strtolower(mysql_error()));
                }

                redirect("?q=financials&m=$m&Y=$Y");
                break;
        }

        $pretotal = 0;
        $total = 0;

        $m2 = ( $m == 12 ) ? 1 : $m + 1;

        $month_start = mktime(0, 0, 0, $m, 1, $Y);
        $month_end = mktime(0, 0, 0, $m2, 1, $Y);

        //-->

        $sql = 'SELECT SUM(financials.money_in - financials.money_out)
                FROM financials
                WHERE financials.financial_date < "' . $month_start . '"';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('financials', strtolower(mysql_error()));

        list($pretotal) = mysql_fetch_row($result);

        //-->

        $sql = 'SELECT financials.financial_id
                     , financials.money_in
                     , financials.money_out
                     , financials.description
                     , financials.financial_date
                     , financials.financial_type
                FROM financials
                WHERE financials.financial_date > "' . $month_start . '"
                  AND financials.financial_date < "' . $month_end . '"
                ORDER BY financials.financial_date ASC';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('financials', strtolower(mysql_error()));

        $total = $pretotal;

        if ( ( $num_rows = mysql_num_rows($result) ) > 0 )
        {
            $i = 0;
            $content = '';
            $row = mysql_fetch_assoc($result);

            while ( $i < $num_rows )
            {
                $movements = '';
                $row_style = '';

                $M = date('M', $row['financial_date']);
                $d = intval( date('d', $row['financial_date']) );

                while ( $i < $num_rows && $d == date('d', $row['financial_date']) )
                {
                    $row_i = $i + 1;
                    $row_style = ( $row_style == 'row1' ) ? 'row2' : 'row1';

                    $description = $row['description'];
                    $money_in = $row['money_in'];
                    $money_out = $row['money_out'];

                    $minus = ( $money_out > 0 ) ? '-' : '';

                    $row_style_in = '';
                    $row_style_out = '';

                    if ( $money_in > $money_out )
                    {
                        $row_style .= ' in';
                        $row_style_in = ' current';
                    }
                    elseif ( $money_out > $money_in )
                    {
                        $row_style .= ' out';
                        $row_style_out = ' current';
                    }

                    $total += $money_in - $money_out;

                    $money_in = number_format($money_in, 2);
                    $money_out = number_format($money_out, 2);
                    $money_total = number_format($total, 2);

                    $movements .= <<<MOVEMENT

                <tr class="$row_style">
                    <td>$row_i</td>
                    <td>&nbsp;</td>
                    <td class="description">$description</td>
                    <td class="cost$row_style_in">$money_in</td>
                    <td class="cost$row_style_out">$minus$money_out</td>
                    <td class="cost">$money_total</td>
                </tr>
MOVEMENT;

                    $i++; $row = mysql_fetch_assoc($result);
                }

                $content .= <<<DAY

            <tbody class="day">
                <tr>
                    <th><a href="javascript:Financials.foldDay($d);" id="day_link_$d">-</a></th>
                    <td colspan="5">$M $d</td>
                </tr>
            </tbody>
            <tbody id="day_$d">$movements
            </tbody>
DAY;
            }
        }
        else
        {
            $content = <<<NONE

            <tbody>
                <tr>
                    <td colspan="6">nothing found</td>
                </tr>
            </tbody>
NONE;
        }

        $pretotal = number_format($pretotal, 2);
        $total = number_format($total, 2);

        $day_options = '';
        $day_total = get_month_days($m, $Y);
        $d = date('d');

        $tabs = str_repeat(' ', 7*4);

        for ( $i = 1; $i <= $day_total; $i++ )
        {
            $selected = ( $d == $i ) ? ' selected="selected"' : '';
            $day_options .= "\n" . sprintf('%s<option value="%d"%s>%d</option>', $tabs, $i, $selected, $i);
        }

        print_page_header('financials');
        print_page_welcome();

        echo <<<FINANCIALS

    <div class="h1">
        <span>Financials</span>
        <div>
            <a href="javascript:Financials.showAddMovement();">add movement</a>
        </div>
    </div>
    <form id="new" class="page" action="?q=financials&amp;a=new_movement&amp;m=$m&amp;Y=$Y" method="post">
        <table id="items" width="768" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 45px;">#</th>
                    <th style="width: 75px;">day</th>
                    <th>description</th>
                    <th style="width: 75px;">in</th>
                    <th style="width: 75px;">out</th>
                    <th style="width: 75px;">total</th>
                </tr>
                <tr class="cost">
                    <th colspan="5">&nbsp;</th>
                    <th class="cost">$pretotal</th>
                </tr>
            </thead>
            <tfoot>
                <tr id="new_movement" class="new addon">
                    <td>new</td>
                    <td>
                        <select name="money_d" id="money_d">$day_options
                        </select>
                    </td>
                    <td><input type="text" name="description" id="description" /></td>
                    <td><input type="text" name="money_in" id="money_in" value="0.00" class="cost" onblur="Financials.onMoneyIn();" /></td>
                    <td><input type="text" name="money_out" id="money_out" value="0.00" class="cost" onblur="Financials.onMoneyOut();" /></td>
                    <td><input type="submit" value="add" class="go" /></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td class="cost">$total</td>
                </tr>
            </tfoot>$content
        </table>
    </form>
FINANCIALS;

        print_page_footer();
        break;
    default:
        __init();

        switch ( $_GET['a'] )
        {
            case 'javascript':
                echo <<<JS

var Log = {

    showNonwork : function()
    {
        var link = \$('nonwork');
            link.href = "javascript:Log.hideNonwork(this);";
            link.innerHTML = "hide non-work days";

        var elementsTr = document.getElementsByTagName('tr');

        for ( i in elementsTr )
        {
            var elementTr = elementsTr[i];

            if ( typeof elementTr != 'undefined' )
            {
                if ( elementTr.nodeType == document.ELEMENT_NODE )
                {
                    if ( elementTr.tagName.toLowerCase() == 'tr' )
                    {
                        if ( elementTr.className.indexOf('addon') != -1 )
                        {
                            var newClass = []
                            var classes = elementTr.className.split(' ');

                            for ( e in classes )
                            {
                                var currentClass = classes[e];

                                if ( currentClass != 'addon' )
                                    newClass.push(currentClass);
                            }

                            elementTr.className = newClass.join(' ');
                        }
                    }
                }
            }
        }
    },

    hideNonwork : function()
    {
        var link = \$('nonwork');
            link.href = "javascript:Log.showNonwork(this);";
            link.innerHTML = "show non-work days";

        var elementsTr = document.getElementsByTagName('tr');

        for ( i in elementsTr )
        {
            var elementTr = elementsTr[i];

            if ( typeof elementTr != 'undefined' )
            {
                if ( elementTr.nodeType == document.ELEMENT_NODE )
                {
                    if ( elementTr.tagName.toLowerCase() == 'tr' )
                    {
                        if ( elementTr.className.indexOf('nonwork') != -1 )
                        {
                            elementTr.className += ' addon';
                        }
                    }
                }
            }
        }
    }
}
JS;
                exit();
                break;
            case 'plus':
            case 'minus':
                if ( !( isset($_GET['lid']) || (int) $_GET['lid'] > 0 || $_GET['lid'] == 'new' ) )
                    print_simple_message('log', 'log id missing');

                if ( $username == 'joksnet' )
                {
                    $diff = '01:00:00';

                    if ( $_GET['lid'] == 'new' )
                    {
                        if ( isset($_GET['uid']) && (int) $_GET['uid'] > 0
                          && isset($_GET['d']) && (int) $_GET['d'] > 0
                          && $_GET['a'] == 'plus' )
                        {
                            $uid = (int) $_GET['uid'];
                            $login = mktime(0, 0, 0, $m, $_GET['d'], $Y);
                            $logout = mktime(0, 0, 1, $m, $_GET['d'], $Y);
                            $extras = mktime2($diff);

                            $sql = "INSERT INTO users_log ( user_id, login, logout, extras )
                                    VALUES ( '$uid', '$login', '$logout', '$extras' )";

                            if ( !( $result = @mysql_query($sql) ) )
                                print_simple_message('log', strtolower(mysql_error()));
                        }
                    }
                    else
                    {
                        $sql = 'SELECT log.extras
                                FROM users_log AS log
                                WHERE log.log_id = ' . $_GET['lid'] . '';

                        if ( !( $result = @mysql_query($sql) ) )
                            print_simple_message('log', strtolower(mysql_error()));

                        if ( mysql_num_rows($result) > 0 )
                        {
                            list($extras) = mysql_fetch_row($result);

                            if ( $extras > 0 )
                            {
                                $extras = date('H:i:s', $extras);

                                if ( $_GET['a'] == 'plus' )
                                    $time = calculate_sum($extras, $diff);
                                else
                                {
                                    $time = calculate_work($diff, $extras);

                                    if ( strncmp($time, '-', 1) == 0 )
                                        $time = '00:00:00';
                                }
                            }
                            else
                                $time = $diff;

                            if ( strlen($time) > 0 )
                            {
                                $time = mktime2($time);

                                $sql = 'UPDATE users_log
                                        SET extras = "' . $time . '"
                                        WHERE log_id = ' . $_GET['lid'] . '';

                                if ( !( $result = @mysql_query($sql) ) )
                                    print_simple_message('log', strtolower(mysql_error()));
                            }
                        }
                    }
                }

                if ( isset($_GET['uid']) && (int) $_GET['uid'] > 0 )
                    redirect('?q=log&u=' . $_GET['uid']);
                else
                    redirect('?q=log');
                break;
            case 'complete':
                if ( !( isset($_GET['lid']) || (int) $_GET['lid'] > 0 || $_GET['lid'] == 'new' ) )
                    print_simple_message('log', 'log id missing');

                if ( $username == 'joksnet' )
                {
                    $total = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':00:00';

                    if ( $_GET['lid'] == 'new' )
                    {
                        if ( isset($_GET['uid']) && (int) $_GET['uid'] > 0 && isset($_GET['d']) && (int) $_GET['d'] > 0 )
                        {
                            $uid = (int) $_GET['uid'];
                            $login = mktime(0, 0, 0, $m, $_GET['d'], $Y);
                            $logout = mktime(0, 0, 1, $m, $_GET['d'], $Y);
                            $extras = mktime2($total);

                            $sql = "INSERT INTO users_log ( user_id, login, logout, extras )
                                    VALUES ( '$uid', '$login', '$logout', '$extras' )";

                            if ( !( $result = @mysql_query($sql) ) )
                                print_simple_message('log', strtolower(mysql_error()));
                        }
                    }
                    else
                    {
                        $sql = 'SELECT log.log_id
                                     , log.login
                                     , log.logout
                                FROM users_log AS log
                                WHERE log.log_id = ' . $_GET['lid'] . '';

                        if ( !( $result = @mysql_query($sql) ) )
                            print_simple_message('log', strtolower(mysql_error()));

                        if ( mysql_num_rows($result) > 0 )
                        {
                            list($log_id, $login, $logout) = mysql_fetch_row($result);

                            $login = date('H:i:s', $login);
                            $logout = date('H:i:s', $logout);

                            $work = calculate_work($login, $logout);
                            $tmp = explode(':', $work);

                            if ( (int) $tmp[0] < $hours )
                            {
                                $pending = calculate_work($work, $total);
                                $time = mktime2($pending);

                                $sql = 'UPDATE users_log
                                        SET extras = "' . $time . '"
                                        WHERE log_id = ' . $_GET['lid'] . '';

                                if ( !( $result = @mysql_query($sql) ) )
                                    print_simple_message('log', strtolower(mysql_error()));
                            }
                        }
                    }
                }

                if ( isset($_GET['uid']) && (int) $_GET['uid'] > 0 )
                    redirect('?q=log&u=' . $_GET['uid'] . '&m=' . $m . '&Y=' . $Y);
                else
                    redirect('?q=log&m=' . $m . '&Y=' . $Y);
                break;
        }

        list($month_work, $month_days_work, $month_days_all) = calculate_month_work($m, $Y);

        print_page_header('log');
        print_page_welcome();

        $m2 = ( $m == 12 ) ? 1 : $m + 1;
        $Y2 = ( $m == 12 ) ? $Y + 1 : $Y;

        $month_start = mktime(0, 0, 0, $m, 1, $Y);
        $month_end = mktime(0, 0, 0, $m2, 1, $Y2);

        $sql = 'SELECT users_log.log_id
                     , users_log.login
                     , users_log.logout
                     , users_log.extras
                FROM users_log
                WHERE users_log.user_id = "' . $cu . '"
                AND users_log.login > "' . $month_start . '"
                AND users_log.login < "' . $month_end . '"
                ORDER BY users_log.login';

        if ( !( $result = @mysql_query($sql) ) )
            print_simple_message('log', strtolower(mysql_error()));

        $day_work = mysql_num_rows($result);

        if ( $day_work == 0 )
            echo "\n" . '    <p id="message">no result</p>';
        else
        {
            $actions = array();

            if ( $username == 'joksnet' )
            {
                $actions[] = '<a href="javascript:Log.showNonwork();" id="nonwork">show non-work days</a>';
            }

            $actions_string = ( sizeof($actions) > 0 ) ? implode(' | ', $actions) : '<!-- -->';

            $table = <<<TABLE

    <div class="h1">
        <span>Resume</span>
        <div>$actions_string</div>
    </div>
    <table id="resume" width="768" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>day</th>
                <th>login</th>
                <th>logout</th>
                <th>extras</th>
                <th>work</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>%s</td>
                <td></td>
            </tr>
        </tfoot>
        <tbody>
TABLE;

            $i = 0;
            $cd = 0;
            $plus = 0;

            $total = '00:00:00';
            $totalperday = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':00:00';

            while ( $row = @mysql_fetch_assoc($result) )
            {
                $d = intval( date('d', $row['login']) );

                if ( $username == 'joksnet' )
                {
                    while ( $d != ( $dd = array_shift($month_days_all) ) )
                    {
                        $dayTime = mktime(0, 0, 0, $m, $dd, $Y);
                        $day = date('D d', $dayTime);
                        $style = ( ( $i + $plus ) % 2 == 0 ) ? 1 : 2;
                        $table .= <<<TABLE

            <tr class="row{$style} nonwork addon">
                <td>{$day}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="actions">
                    <a href="?q=log&amp;a=plus&amp;lid=new&amp;d=$dd&amp;m=$m&amp;Y=$Y&amp;uid=$cu">more</a> |
                    <a href="?q=log&amp;a=minus&amp;lid=new&amp;d=$dd&amp;m=$m&amp;Y=$Y&amp;uid=$cu">less</a> |
                    <a href="?q=log&amp;a=complete&amp;lid=new&amp;d=$dd&amp;m=$m&amp;Y=$Y&amp;uid=$cu">complete</a>
                </td>
            </tr>
TABLE;
                        $plus++;
                    }
                }

                $log_id = $row['log_id'];
                $day = date('D d', $row['login']);
                $login = date('H:i:s', $row['login']);
                $logout = date('H:i:s', $row['logout']);

                $work = calculate_work($login, $logout);
                # $pending = calculate_work($work, $totalperday);

                if ( (int) $row['extras'] > 0 )
                {
                    $extras = date('H:i:s', $row['extras']);
                    $work = calculate_sum($work, $extras);
                }
                else
                    $extras = '00:00:00';

                $color = calculate_color($work, $hours);
                $total = calculate_sum($total, $work);

                #
                # Faltan los dias que no van listados, por eso genera mal el
                # total pendiente.
                #
                # if ( doubleval( str_replace(':', '', $pending) ) < 0 )
                #     $total_pendding = calculate_work( str_replace('-', '0', $pending), $total_pendding );
                # else
                #     $total_pendding = calculate_sum($total_pendding, $pending);

                $actions = array();

                if ( $username == 'joksnet' )
                {
                    $actions[] = '<a href="?q=log&amp;a=plus&amp;lid=' . $log_id . '&amp;m=' . $m . '&amp;Y=' . $Y . '&amp;uid=' . $cu . '">more</a>';
                    $actions[] = '<a href="?q=log&amp;a=minus&amp;lid=' . $log_id . '&amp;m=' . $m . '&amp;Y=' . $Y . '&amp;uid=' . $cu . '">less</a>';
                    $actions[] = '<a href="?q=log&amp;a=complete&amp;lid=' . $log_id . '&amp;m=' . $m . '&amp;Y=' . $Y . '&amp;uid=' . $cu . '">complete</a>';
                }

                $actions_string = implode(' | ', $actions);

                $style = ( ( $i + $plus ) % 2 == 0 ) ? 1 : 2;
                $table .= <<<TABLE

            <tr class="row{$style}">
                <td>{$day}</td>
                <td>{$login}</td>
                <td>{$logout}</td>
                <td>{$extras}</td>
                <td style="color: {$color};">{$work}</td>
                <td class="actions">$actions_string</td>
            </tr>
TABLE;

                $i++;
            }

            $table .= <<<TABLE

        </tbody>
    </table>
TABLE;

            printf($table, $total);
        }

        $month_work_hours = $month_work * $hours;
        $month_work_hours_format = ( $month_work * $hours ) . ':00:00';

        if ( $username == 'joksnet' && $cu != $user_id )
        {
            //
            // $hours son las de usuario logueado, no el que estamos viendo.
            //
            // if ( !( isset($hours) || $hours > 0 ) )
            // {
                $sql = 'SELECT users.hours
                        FROM users
                        WHERE users.user_id = "' . $cu . '"
                        LIMIT 0,1';

                $result = mysql_query($sql);

                list($cu_hours) = mysql_fetch_row($result);
            // }

            $month_work_hours = $month_work * $cu_hours;
        }

        $day_work_pendding = $month_work - $day_work;

        if ( doubleval( str_replace(':', '', $month_work_hours_format) ) >
             doubleval( str_replace(':', '', $total) )  )
            $total_pendding = calculate_work($total, $month_work_hours_format);
        else
            $total_pendding = '-' . calculate_work($month_work_hours_format, $total);

        echo <<<TABLE

    <div class="h1">
        <span>Work</span>
    </div>
    <table id="work" width="768" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>days</th>
                <th>hours</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>total</td>
                <td>{$month_work}</td>
                <td>{$month_work_hours_format}</td>
            </tr>
            <tr>
                <td>work</td>
                <td>{$day_work}</td>
                <td>{$total}</td>
            </tr>
            <tr>
                <td>pedding</td>
                <td>{$day_work_pendding}</td>
                <td>{$total_pendding}</td>
            </tr>
        </tbody>
    </table>
TABLE;

        print_page_footer();
        break;
}

?>
