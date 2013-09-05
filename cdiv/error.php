<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/config.php";
include_once "$root/common.php";

$id = $_GET['i'];

if ( $id == 'rand' )
{
    define('RAND', true);

    $sql = "SELECT id
            FROM errors
            ORDER BY RAND()
            LIMIT 1";

    if ( !( $result = @mysql_query($sql) ) )
        die( mysql_error() );

    $row = @mysql_fetch_assoc($result);
    $id  = $row['id'];
}

$invalid = array();

if ( sizeof($_POST) > 0 )
{
    if ( !( empty($_POST['drugs']) ) )
        header("Location: $rootURL/$id");

    if ( empty($_POST['name']) )
        $invalid['name'] = true;

    if ( empty($_POST['email']) )
        $invalid['email'] = true;
    elseif ( !( isValidEmail($_POST['email']) ) )
        $invalid['email'] = true;

    if ( empty($_POST['url']) ) {}
        // $invalid['url'] = true;
    elseif ( !( isValidURL($_POST['url']) ) )
        $invalid['url'] = true;

    if ( empty($_POST['comment']) )
        $invalid['comment'] = true;

    $vote = ( $_POST['vote'] == '1' ) ? 1 : 0;

    if ( empty($invalid) )
    {
        $time = time();
        $ip   = $_SERVER['REMOTE_ADDR'];

        mysql_query(
            "INSERT INTO errors_comments ( error, name, email, url, comment, posted, ip, vote )
             VALUES ( '$id', '{$_POST['name']}', '{$_POST['email']}', '{$_POST['url']}', '{$_POST['comment']}', '$time', '$ip', '$vote' )"
        );

        $comment = mysql_insert_id();

        if ( $vote == 1 )
        {
            mysql_query(
                "UPDATE errors
                 SET votes = votes + 1
                 WHERE id = '$id'
                 LIMIT 1"
            );
        }

        header("Location: $rootURL/$id#comment-$comment");
        exit();
    }
}

$sql = "SELECT errors.id, name, url, tag
        FROM errors
        LEFT JOIN errors_tags
        ON errors_tags.id = errors.id
        WHERE errors.id = '$id'
        ORDER BY tag";

if ( !( $result = @mysql_query($sql) ) )
    die( mysql_error() );

if ( @mysql_num_rows($result) == 0 )
    header("Location: $rootURL/notfound");

$tags = array();
$name = null;

while ( $row = @mysql_fetch_assoc($result) )
{
    $tags[] = $row['tag'];

    if ( !( isset($name) ) || null === $name )
    {
        $name = $row['name'];
        $url = $row['url'];
    }
}

$related = array();

if ( $tags )
{
    $sql = "SELECT DISTINCT errors.id, name, COUNT(tag) as a
            FROM errors_tags
            INNER JOIN errors ON errors.id = errors_tags.id
            WHERE tag IN ( '" . implode("', '", $tags) . "' )
            GROUP BY errors.id
            ORDER BY a
            LIMIT 8";

    if ( !( $result = @mysql_query($sql) ) )
        die( mysql_error() );

    while ( $row = @mysql_fetch_assoc($result) )
        $related[$row['id']] = $row['name'];
}

$sql = "SELECT id, name, email, url, comment, posted
        FROM errors_comments
        WHERE error = '$id'
        ORDER BY posted ASC";

if ( !( $result = @mysql_query($sql) ) )
    die( mysql_error() );

$comments = array();

while ( $row = @mysql_fetch_assoc($result) )
    $comments[$row['id']] = array_slice($row, 1);

require_once "$root/theme/error.php";