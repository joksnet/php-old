<?php

require_once('includes/common.php');

$id_posts = ( isset($_GET['id_posts']) ) ? $_GET['id_posts'] : ( ( isset($_POST['id_posts']) ) ? $_POST['id_posts'] : 0 );
$id_users = ( isset($_GET['id_users']) ) ? $_GET['id_users'] : ( ( isset($_POST['id_users']) ) ? $_POST['id_users'] : 0 );

list($users_data, $users_submenu) = get_users();
list($data, $posts, $pagination, $comments) = get_posts($id_posts, $id_users, $start);

if ( $id_posts > 0 )
{
    if ( isset($_POST['author']) )
    {
        $ins = new Insert('comments', $db);
        $ins->col['id_posts'] = $id_posts;
        $ins->col['author'] = $_POST['author'];
        $ins->col['author_email'] = $_POST['author_email'];
        $ins->col['author_url'] = $_POST['author_url'];
        $ins->col['author_ip'] = ( getenv('HTTP_X_FORWARDED_FOR') ) ?  getenv('HTTP_X_FORWARDED_FOR') : $_SERVER['REMOTE_ADDR'];
        $ins->col['agent'] = $_SERVER['HTTP_USER_AGENT'];
        $ins->col['date'] = 'NOW()';
        $ins->col['content'] = strip_tags(specialchars($_POST['comment']), '<h1><b><i><a><ul><li><pre><hr><blockquote><img><p>');
        $ins->update();

        redirect('?id_posts=' . $id_posts);
    }

    $id_users = $data['id_users'];
    $comments_reply = '
<h1>Leave a Comment</h1>
<form name="frm" id="frm" action="" method="post">
    <label for="author">Author</label><input type="text" name="author" id="author" /><br />
    <label for="author_email">Email</label><input type="text" name="author_email" id="author_email" /><br />
    <label for="author_url">URL</label><input type="text" name="author_url" id="author_url" /><br />
    <label for="comment">Comment</label><textarea name="comment" id="comment"></textarea><br />
</form>
<p align="center"><a href="javascript:send();">Send</a></p>
<script language="javascript" type="text/javascript">
<!--
    function send()
    {
        var frm = document.frm;
        var msg = "";

        if ( frm.author.value.length == 0 )
            msg += "Complete your name\n";
        if ( frm.author_email.value.length == 0 )
            msg += "Complete your email\n";
        if ( frm.comment.value.length == 0 )
            msg += "Complete the comment\n";

        if ( msg.length > 0 )
            alert(msg);
        else
            frm.submit();
    }
-->
</script>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?=preg_replace("/(\<)(.*?)(\>)/mi", "", $preferences['site_name'])?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="community.css" type="text/css" media="screen,projection" />
</head>
<body>
    <div id="container">
        <div id="header">
            <h1><a href="?rand=<?=rand()?>"><?=$preferences['site_name']?></a></h1>
            <h2><?=$preferences['site_slogan']?></h2>
            <ul id="nav">
                <li><a href="?rand=<?=rand()?>" title="Main" accesskey="m"><em>M</em>ain</a></li>
                <li><a href="?rand=<?=rand()?>" title="Photos" accesskey="p"><em>P</em>hotos</a></li>
            </ul>
        </div>
        <div id="sidebar">
            <h1><?=( $id_users > 0 ) ? $users_data[$id_users]['name'] : $preferences['site_name']?></h1>
            <p><?=( $id_users > 0 ) ? $users_data[$id_users]['description'] : $preferences['site_description']?></p>
            <h1>Bloggers</h1>
            <ul class="submenu"><?=$users_submenu?>

            </ul>
            <p class="note">Read this blog in <img src="images/feed-icon.png" alt="Feed Icon" /> <a href="<?=$preferences['site_url']?>/rss.xml">RSS</a> feeds.</p>
        </div>
        <div id="content"><?=$posts?><?=$pagination?><?=$comments?><?=$comments_reply?>

        </div>
        <div id="footer">
            <p>
                Template design by <a href="http://www.sixshootermedia.com">Six Shooter Media</a>.<br />
                Featured on <a href="http://www.sixshootermedia.com/Pretty_as_a_Picture">Pretty as a Picture</a>, <a href="http://www.openwebdesign.org">OWD.org</a> and <a href="http://www.oswd.org">OSWD.org</a>.<br />
                &copy; All your copyright information here.
            </p>
        </div>
    </div>
</body>
</html>