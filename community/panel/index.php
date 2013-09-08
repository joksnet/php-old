<?php

session_start();

require_once('../includes/common.php');
require_once('../includes/feedcreator.class.php');
require_once('../includes/hft_image.class.php');

$action = ( isset($_GET['action']) ) ? $_GET['action'] : ( ( isset($_POST['action']) ) ? $_POST['action'] : '' );
$head = "\n";

if ( isset($_SESSION['login']) )
{
    if ( $_GET['rss'] == 'true' )
        make_rss();

    switch ( $action )
    {
        case 'settings':
            $page_title = 'Account Settings';
            $content = '';
            break;
        case 'profile':
            if ( isset($_POST['username']) )
            {
                $upd = new DoUpdate('users', $db, 'id_users', $_SESSION['login']['id_users']);
                // $upd->col['username'] = $_POST['username'];
                $upd->col['name'] = htmlentities($_POST['name']);
                $upd->col['description'] = htmlentities($_POST['description']);
                $upd->update();

                // $_SESSION['login']['username'] = $_POST['username'];
                $_SESSION['login']['name'] = htmlentities($_POST['name']);
                $_SESSION['login']['description'] = htmlentities($_POST['description']);

                redirect('?rand=' . rand());
            }

            $page_title = 'Profile';
            $content = '
        <form name="frm" id="frm" action="" method="post">
            <input type="hidden" name="action" id="action" value="' . $action . '" />
            <label for="username">Username</label><input type="text" name="username" id="username" value="' .$_SESSION['login']['username'] . '" /><br />
            <label for="name">Name</label><input type="text" name="name" id="name" value="' .$_SESSION['login']['name'] . '" /><br />
            <label for="description">Description</label><textarea name="description" id="description" cols="18" rows="3">' . $_SESSION['login']['description'] . '</textarea><br />
            <div align="center"><a href="javascript:send();">save</a>&nbsp;<a href="?rand=' . rand() . '">back</a></div>
        </form>';
            break;
        case 'password':
            if ( isset($_POST['password']) )
            {
                $upd = new DoUpdate('users', $db, 'id_users', $_SESSION['login']['id_users']);
                $upd->col['password'] = md5($_POST['password']);
                $upd->update();

                redirect('?rand=' . rand());
            }

            $page_title = 'Password';
            $content = '
            I hate to validate. So, if you write a bad password...
        <form name="frm" id="frm" action="" method="post">
            <label for="password">New Password</label><input type="password" name="password" id="password" /><br />
            <div align="center"><a href="javascript:send();">save</a>&nbsp;<a href="?rand=' . rand() . '">back</a></div>
        </form>';
            break;
        case 'ajaxnewlabel':
            if ( isset($_GET['label']) )
            {
                $ins = new Insert('labels', $db);
                $ins->col['name'] = $_GET['label'];
                $ins->update();

                $id_labels = mysql_insert_id($db->id);

                echo $id_labels;
            }
            else
                echo 0;

            exit();
            break;
        case 'writepost':
        case 'writepage':
        case 'editpost':
        case 'editpage':
            if ( $action == 'editpost' || $action == 'editpage' )
            {
                if ( !isset($_GET['id_posts']) || !is_numeric($_GET['id_posts']) || $_GET['id_posts'] < 1 )
                    redirect('?action=manageposts&error=badparam');

                list($data, $posts, $pagination, $comments) = get_posts($_GET['id_posts'], 0, 0, 'all');

                $value = $data[$_GET['id_posts']];

                if ( $value['id_users'] != $_SESSION['login']['id_users'] )
                    redirect('?action=manageposts&error=baduser');
            }

            if ( isset($_POST['title']) )
            {
                if ( $action == 'editpost' || $action == 'editpage' )
                {
                    $upd = new DoUpdate('posts', $db, 'id_posts', $_GET['id_posts']);

                    // $upd->col['title'] = specialchars($_POST['title']);
                    // $upd->col['content'] = specialchars($_POST['description']);
                    $upd->col['title'] = $_POST['title'];
                    $upd->col['content'] = $_POST['description'];

                    if ( $_POST['publish'] == 'true' )
                        $upd->col['active'] = 'yes';

                    $upd->col['modified'] = 'NOW()';
                    $upd->update();

                    $id_posts = $_GET['id_posts'];

                    $q = new Query($db);
                    $q->exec('DELETE FROM posts2labels
                              WHERE posts2labels.id_posts = ' . $id_posts);
                }
                else
                {
                    $ins = new Insert('posts', $db);
                    $ins->col['id_users'] = $_SESSION['login']['id_users'];

                    // $ins->col['title'] = specialchars($_POST['title']);
                    // $ins->col['content'] = specialchars($_POST['description']);
                    $ins->col['title'] = $_POST['title'];
                    $ins->col['content'] = $_POST['description'];

                    $ins->col['active'] = ( $_POST['draft'] == 'true' ) ? 'draft' : 'yes';
                    $ins->col['date'] = 'NOW()';
                    $ins->col['modified'] = 'NOW()';
                    $ins->update();

                    $id_posts = mysql_insert_id($db->id);
                }

                foreach ( $_POST['labels'] as $key => $value )
                {
                    $ins = new Insert('posts2labels', $db);
                    $ins->col['id_posts'] = $id_posts;
                    $ins->col['id_labels'] = $value;
                    $ins->update();
                }

                redirect('?rand=' . rand() . '&amp;rss=true');
            }

            $page_title = ( ( $action == 'editpost' || $action == 'editpage' ) ? 'Edit ' : 'Write ' ) . ( ( $action == 'writepage' || $action == 'editpage' ) ? 'Page' : 'Post' );
            $head = '
<style type="text/css">
    label {
        width: 70px;
    }
</style>
';

            $labels = get_labels( ( ( isset($_GET['id_posts']) && is_numeric($_GET['id_posts']) && $_GET['id_posts'] > 0 ) ? $_GET['id_posts'] : 0 ) );

            $content = '
        There no validation. So you can include all tags you want. Use it at your own risk.
        <!-- 03.05.2006 - Now you can __not__ add HTML code, then you have a <a href="#">Freak Code</a> (all RegEx). HTML entities will be ignored, type the fucking character. -->
        <form name="frm" id="frm" action="' . ( ( $action == 'editpost' || $action == 'editpage' ) ? '?action=' . $action . '&amp;id_posts=' . $_GET['id_posts'] : '' ) . '" method="post">
            <label for="title">Title</label><input type="text" name="title" id="title" size="50"' . ( ( $action == 'editpost' || $action == 'editpage' ) ? ' value="' . $value['title'] . '"' : '' ) . ' /><br />
            <label for="description">Content</label>
                <textarea name="description" id="description" cols="48" rows="12"></textarea>
                <div id="preview" style="float: left; width: 363px; margin-top: 1px; padding: 3px; background-color: #f4f4f4; border: 1px solid #b2b2b2; display: none;"></div>
            <br />
            <label for="add_labels">Labels</label><select name="add_labels" id="add_labels" style="margin-top: 2px;" onchange="manageLabels();">' . $options . '</select><div id="labels" style="padding-top: 7px;"></div><br />
            ' . ( ( $action == 'editpost' || $action == 'editpage' ) ? '<div align="center"><a href="javascript:post();">save</a>&nbsp;' . ( ( $value['active'] == 'draft' ) ? '<a href="javascript:publish();">publish</a>&nbsp;' : '' ) . '<a id="preview_link" href="javascript:preview();">preview</a>&nbsp;<a href="?action=manageposts">back</a></div>' : '<div align="center"><a href="javascript:post();">write</a>&nbsp;<a href="javascript:draft();">draft</a>&nbsp;<a id="preview_link" href="javascript:preview();">preview</a>&nbsp;<a href="?rand=' . rand() . '">back</a></div>' ) . '
        </form>
<script language="javascript" type="text/javascript">
<!--
    function createHiddenInput( name, value )
    {
        var newinput = document.createElement(\'input\') ;
            newinput.type = \'hidden\';
            newinput.id = name;
            newinput.name = name;
            newinput.value = value;

    	document.frm.appendChild(newinput);
    }

    function preview()
    {
        document.getElementById(\'description\').style.display = \'none\';
        document.getElementById(\'preview\').innerHTML = document.getElementById(\'description\').value;
        document.getElementById(\'preview\').style.display = \'block\';

        var preview_link = document.getElementById(\'preview_link\');
            preview_link.href = \'javascript:edit();\';
            preview_link.innerHTML = \'continue editing\';
    }

    function edit()
    {
        document.getElementById(\'description\').style.display = \'block\';
        document.getElementById(\'preview\').style.display = \'none\';

        var preview_link = document.getElementById(\'preview_link\');
            preview_link.href = \'javascript:preview();\';
            preview_link.innerHTML = \'preview\';
    }

    var labels = new Array(' . $labels['csv'] . ');
    var labels_name = new Array(' . $labels['csv_name'] . ');
    var labels_apply = new Array(' . $labels['csv_apply'] . ');
    var labels_remove = new Array(' . $labels['csv_remove'] . ');

    function post()
    {
        for ( i in labels_remove )
        {
            createHiddenInput(\'labels[\' + i + \']\', labels_remove[i]);
        }

        document.frm.submit();
    }

    function manageLabels()
    {
        var combo = document.getElementById(\'add_labels\');
        var value = combo.value;
        var id_labels = value.substr(1);

        if ( value.substr(0, 1) == \'a\' )
            moveArrayValue(labels_apply, labels_remove, id_labels);
        if ( value.substr(0, 1) == \'r\' )
            moveArrayValue(labels_remove, labels_apply, id_labels);
        if ( value.substr(0, 1) == \'n\' )
        {
            var new_label = prompt(\'Please enter a new label name:\');

            // Bug, si se cancela el prompt, terminaba la ejecucion.
            combo.selectedIndex = 0;

            if ( new_label.length > 0 )
            {
                var xml = new XMLHttpRequest();

                xml.open(\'GET\', \'?action=ajaxnewlabel&label=\' + new_label, false);
                xml.send(\'\');

                var response = xml.responseText;

                labels.push(response);
                labels_name.push(new_label);
                labels_remove.push(response);
            }
        }

        fillCombo(combo);
    }

    function fillCombo( combo )
    {
        combo.length = 0;

        // if ( labels_apply.length > 0 )
        // {
            combo.length++;
            combo.options[combo.length - 1] = new Option(\'-Apply Labels-\', \'\');
            combo.options[combo.length - 1].disabled = true;

            if ( labels_apply.length > 0 )
                labels_apply = labels_apply.sort();

            for ( var i = 0; i < labels_apply.length; i++ )
            {
                combo.length++;
                combo.options[combo.length - 1] = new Option(\'  \' + getLabelName(labels_apply[i]), \'a\' + labels_apply[i]);
            }

            combo.length++;
            combo.options[combo.length - 1] = new Option(\'  New Label...\', \'n\');
        // }

        labels_div = document.getElementById(\'labels\');
        labels_div.innerHTML = "";

        if ( labels_remove.length > 0 )
        {
            labels_remove = labels_remove.sort();

            combo.length++;
            combo.options[combo.length - 1] = new Option(\'-Remove Labels-\', \'\');
            combo.options[combo.length - 1].disabled = true;

            for ( var e = 0; e < labels_remove.length; e++ )
            {
                combo.length++;
                combo.options[combo.length - 1] = new Option(\'  \' + getLabelName(labels_remove[e]), \'r\' + labels_remove[e]);

                labels_div.innerHTML += \'&nbsp;\' + getLabelName(labels_remove[e]);
            }
        }

        combo.selectedIndex = 0;
    }

    fillCombo( document.getElementById(\'add_labels\') );

    function moveArrayValue( from, to, value )
    {
        for ( i in from )
        {
            if ( from[i] == value )
            {
                from.splice(i, 1);
                to.push(value);
            }
        }
    }

    function getLabelName( id_labels )
    {
        for ( i in labels )
        {
            if ( labels[i] == id_labels )
            {
                return labels_name[i];
            }
        }
    }

    ' . ( ( $action == 'editpost' || $action == 'editpage' ) ? '
    document.getElementById("description").value = fixContent("' . str_replace("\"", "\\\"", str_replace("\r\n", "RN", str_replace('</', '<\/', $value['content']))) . '");

    function fixContent(html)
    {
        return html.replace(new RegExp(\'RN\', \'gi\'),\'\n\');
    }

    function publish()
    {
        createHiddenInput(\'publish\', \'true\');
        post();
    }
    ' : '
    function draft()
    {
    	createHiddenInput(\'draft\', \'true\');
        post();
    }
    ' ) . '
-->
</script>';
            break;
        case 'upload':
            if ( $_FILES['file']['size'] > 0 )
            {
                if ( strpos($_FILES['file']['type'], 'image') !== false )
                {
                    list($filename, $ext) = explode('.', strtolower($_FILES['file']['name']));

                    $image = new hft_image($_FILES['file']['tmp_name']);
                    $image->output_original('../upload/' . $filename . '_orig.' . $ext);
                    $image->resize($preferences['thumb_width'], $preferences['thumb_height']);
                    $image->output_resized('../upload/' . $filename . '_thumb.' . $ext);
                }
                else
                {
                    move_uploaded_file($_FILES['file']['tmp_name'], '../upload/' . strtolower($_FILES['file']['name']));
                }

                redirect('?rand=' . rand());
            }

            $page_title = 'Upload';
            $content = '
        You can upload files up to ' . ini_get('upload_max_filesize') . '.
        This will be saved en <em>upload</em> folder.
        To link to your upload file you need to refer as <em>\'upload/filename.ext\'</em>.
        If you upload an image, it will create two files: <em>\'filename_orig.ext\'</em> and <em>\'filename_thumb.ext\'</em>.
        <form name="frm" id="frm" action="" method="post" enctype="multipart/form-data">
            <label for="file">Upload file</label><input type="file" name="file" id="file" /><br />
            <div align="center"><a href="javascript:send();">upload</a>&nbsp;<a href="?rand=' . rand() . '">back</a></div>
        </form>';
            break;
        case 'manageuploads':
            $uploads = searchdir('../upload/', '0', 'FILES');

            foreach ( $uploads as $key => $value )
            {
                $rows .= '
            <tr style="height: 35px;">
                <td><a href="' . $value .  '">' . substr($value, strpos($value, '/', 3) + 1) . '</a></td>
                <td>' . mime_content_type($value) . '</td>
                <td>' . filesize($value) . '</td>
                <td>' . date($preferences['date_format'], filemtime($value)) . '</td>
                <td><a href="?action=deleteupload&amp;file=' . substr($value, strpos($value, '/', 3) + 1) . '">delete</a></td>
            </tr>';
            }

            $page_title = 'Manage Uploads';
            $content = '
        <table width="100%" cellpadding="3" cellspacing="3">
            <tr style="height: 35px;">
                <th scope="col">File</th>
                <th scope="col">Type</th>
                <th scope="col">Size</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </tr>
' . $rows . '
        </table>';
            break;
        case 'deleteupload':
            if ( file_exists('../upload/' . $_GET['file']) )
                unlink('../upload/' . $_GET['file']);

            redirect('?rand=' . rand());
            break;
        case 'manageposts':
            list($data, $posts, $pagination, $comments) = get_posts(0, 0, $start, 'all');

            foreach ( $data as $key => $value )
            {
                $active = ( $value['id_users'] == $_SESSION['login']['id_users'] ) ? '<a href="?action=changestatus&amp;id_posts=' . $value['id_posts'] . '&amp;status=inactive">Active</a>' : 'Active';
                $inactive = ( $value['id_users'] == $_SESSION['login']['id_users'] ) ? '<a href="?action=changestatus&amp;id_posts=' . $value['id_posts'] . '&amp;status=active">Inactive</a>' : 'Inactive';

                $rows .= '
            <tr style="height: 35px;">
                <td><a href="' . ( ( $value['id_users'] == $_SESSION['login']['id_users'] ) ? '?action=editpost&amp;id_posts=' . $value['id_posts'] : '?action=viewpost&amp;id_posts=' . $value['id_posts'] ) . '">' . cut($value['title'], 12) . '</a></td>
                <td>' . date($preferences['date_format'], strtotime($value['date'])) . '</td>
                <td>' . date($preferences['date_format'], strtotime($value['modified'])) . '</td>
                <td>' . $value['username'] . '</td>
                <td>' . ( ( $value['active'] == 'draft' ) ? 'Draft' : ( ( $value['active'] == 'no' ) ? $inactive : $active ) ) . '</td>
            </tr>';
            }

            $page_title = 'Manage Posts';
            $content = '
        <table width="100%" cellpadding="3" cellspacing="3">
            <tr style="height: 35px;">
                <th scope="col">Title</th>
                <th scope="col">Date</th>
                <th scope="col">Modified</th>
                <th scope="col">Username</th>
                <th scope="col">Status</th>
            </tr>
' . $rows . '
        </table>
' . $pagination;
            break;
        case 'changestatus':
            if ( isset($_GET['id_posts']) && is_numeric($_GET['id_posts']) && $_GET['id_posts'] > 0 )
            {
                $posts = get_posts($_GET['id_posts'], 0, 0, 'all');

                if ( $posts[0][$_GET['id_posts']]['id_users'] != $_SESSION['login']['id_users'] )
                    redirect('?action=manageposts&is_users=' . $posts[0][$_GET['id_posts']]['id_users']);

                if ( $_GET['status'] == 'active' )
                {
                    $upd = new DoUpdate('posts', $db, 'id_posts', $_GET['id_posts']);
                    $upd->col['active'] = 'yes';
                    $upd->update();
                }
                elseif ( $_GET['status'] == 'inactive' )
                {
                    $upd = new DoUpdate('posts', $db, 'id_posts', $_GET['id_posts']);
                    $upd->col['active'] = 'no';
                    $upd->update();
                }
            }

            redirect('?action=manageposts&amp;rss=true');
            break;
        case 'viewpost':
            if ( !isset($_GET['id_posts']) || !is_numeric($_GET['id_posts']) || $_GET['id_posts'] < 1 )
                redirect('?action=manageposts');

            list($data, $posts, $pagination, $comments) = get_posts($_GET['id_posts'], 0, 0, 'all');

            $page_title = 'View Post';
            $content = '';

            foreach ( $data as $key => $value )
            {
                $content .= "\n";
                $content .= '<h2>' . $value['title'] . '</h2>' . "\n";
                $content .= $value['username'] . ' writes ' . $value['content'];
            }

            $content .= '
        <div align="center"><a href="?action=manageposts">back</a></div>';
            break;
        case 'manageoptions':
            if ( isset($_POST['site_name']) )
            {
                $preferences = $_POST;

                foreach ( $preferences as $key => $value )
                {
                    $upd = new DoUpdate('options', $db, 'name', '\'' . $key . '\'');
                    $upd->col['value'] = $value;
                    $upd->update();
                }

                redirect('?rand=' . rand());
            }

            foreach ( $preferences as $key => $value )
            {
                $rows .= '<label for="' . $key . '">' . $key . '</label><input type="text" name="' . $key . '" id="' . $key . '" value="' . $value . '" /><br />';
            }

            $page_title = 'Manage Options';
            $content = '
        This is not validated. So you can put an string value where may be a integer.
        <form name="frm" id="frm" action="" method="post">
' . $rows . '
            <div align="center"><a href="javascript:send();">save</a>&nbsp;<a href="?rand=' . rand() . '">back</a></div>
        </form>';
            break;
        case 'logout':
            unset($_SESSION['login']);
            redirect('?rand=' . rand());
            break;
        default:
            $page_title = 'Account';
            $content = '
        <h2>Welcome back ' . $_SESSION['login']['username'] . '. What would you like to do?</h2>
        <ul>
            <li><a href="?action=settings">Change my account settings</a></li>
            <li><a href="?action=profile">Change my profile</a></li>
            <li><a href="?action=password">Change my password</a></li>
        </ul>
        <h2>Write</h2>
        <ul>
            <li><a href="?action=writepost">Write Post</a></li>
            <li><a href="?rss=true">Write RSS</a></li>
        </ul>
        <h2>Upload</h2>
        <ul>
            <li><a href="?action=upload">Upload File</a></li>
        </ul>
        <h2>Manage</h2>
        <ul>
            <li><a href="?action=manageuploads">Uploads</a></li>
            <li><a href="?action=manageposts">Posts</a></li>
            <li><a href="?action=manageoptions">Options</a></li>
        </ul>';
            break;
    }
}
else
{
    if ( isset($_POST['username']) )
    {
        $success = false;

        $q = new Query($db);
        $q->exec('SELECT users.*
                  FROM users
                  WHERE users.username = \'' . $_POST['username'] . '\'
                  AND users.password = \'' . md5($_POST['password']) . '\'');

        if ( $q->numrows > 0 )
        {
            $success = true;
            $_SESSION['login'] = $q->data;
            $_SESSION['login']['password'] = ''; // Security
        }

        $q->free();

        if ( $success )
            redirect('?rand=' . rand());
    }

    $page_title = 'Login';
    $content = ( isset($success) && !$success ) ? 'Not Success. Sorry.' : '';
    $content .= '
        <form name="frm" id="frm" action="" method="post">
            <label for="username">Username</label><input type="text" name="username" id="username" value="' .$_POST['username'] . '" /><br />
            <label for="password">Password</label><input type="password" name="password" id="password" /><br />
            <div align="center"><a href="javascript:send();">login</a></div>
        </form>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?=preg_replace("/(\<)(.*?)(\>)/mi", "", $preferences['site_name'])?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="panel.css" type="text/css" media="screen,projection" /><?=$head?>
<script language="javascript" type="text/javascript">
<!--
    function send()
    {
        document.frm.submit();
    }
-->
</script>
</head>
<body>
    <h2>Control Panel :: <?=$page_title?></h2>
    <div id="content"><?=$content?>

    </div>
    <?=( isset($_SESSION['login']) ) ? '<div style="margin: 0 auto; width: 460px; text-align: right;"><a href="?rand=' . rand() . '">home</a>&nbsp;<a href="?action=logout">logout</a></div>' : ''?>

</body>
</html>