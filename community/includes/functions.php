<?php

/**
 * Fuerza a la pagina a ser redirigida hacia otra.
 *
 * @author joksnet
 * @version 1.3
 * @param string $url
 */
function redirect( $url )
{
    if ( strlen($url) > 0 )
    {
        $url = html_entity_decode($url);

        if ( headers_sent() )
            echo '<script language="javascript" type="text/javascript">document.location.href=\'' .  $url . '\';</script>';
        else
            header("Location: " . $url);
        exit();
    }
}

/**
 * Recorta una cadena a $chars caracteres y si
 * es mas grande, le agrega 3 puntos al final.
 *
 * @param string $string
 * @param int $chars
 * @return string
 */
function cut( $string, $chars )
{
    return ( strlen($string) > $chars ) ? substr($string, 0, $chars) . '...' : $string;
}

/**
 * Carga las propiedades de la tabla 'options' en un array.
 *
 * @author joksnet
 * @version 1.0
 */
function load_preferences()
{
    global $db;
    global $preferences;

    $q = new Query($db);
    $q->exec('SELECT options.name
                   , options.value
              FROM options');

    for ( $i = 0; $i < $q->numrows; $i++ )
    {
        $preferences[$q->data['name']] = $q->data['value'];
        $q->nxt();
    }

    $q->free();
}

/**
 * Obtiene los posts de cada Blog, si el $id_users
 * es cero, entonces son de la pagina principal.
 *
 * @param int $id_posts
 * @param int $id_users
 * @param int $start
 * @return array
 */
function get_posts( $id_posts = 0, $id_users = 0, $start = 0, $active = 'yes' )
{
    global $db;
    global $preferences;
    global $_GET;

    $where = '';
    $where .= ( $active != 'all' ) ? ' posts.active = \'' . $active . '\'' : ' 1';
    $where .= ( $id_posts > 0 ) ? ' AND posts.id_posts = ' . $id_posts : '';
    $where .= ( $id_users > 0 ) ? ' AND posts.id_users = ' . $id_users : '';

    $q = new Query($db);
    $q->exec('SELECT posts.id_posts
                   , posts.id_users
                   , posts.title
                   , posts.content
                   , posts.date
                   , posts.modified
                   , posts.active
                   , users.name AS user
                   , users.username
              FROM posts
              LEFT JOIN users ON ( users.id_users = posts.id_users )
              WHERE
              ' . $where . '
              ORDER BY posts.date DESC
              LIMIT ' . $start . ', ' . $preferences['per_page']);

    $data = array();
    $posts = '';
    $pagination = '';
    $comments = '';

    for ( $i = 0; $i < $q->numrows; $i++ )
    {
        $data[$q->data['id_posts']] = $q->data;
        $posts .= '
<h1><a href="?id_posts=' . $q->data['id_posts'] . '">' . $q->data['title'] . '</a></h1>
' . ( ( $q->data['id_users'] > 0 ) ? '<a href="?id_users=' . $q->data['id_users'] . '">' . $q->data['user'] . '</a>' : 'Anonymous' ) . ' writes ' . $q->data['content'] . '
<div class="article_menu">
    <b>Posted on ' . date($preferences['date_format'], strtotime($q->data['date'])) . '</b> <a href="?id_posts=' . $q->data['id_posts'] . '">' . get_count('comments', 'id_posts = ' . $q->data['id_posts']) . ' Comments</a>
</div>';

        $q->nxt();
    }

    $q->free();

    if ( $id_posts < 1 )
    {
        foreach ( $_GET as $key => $value )
        {
            if ( $key != 'start' )
                $php_self .= ( ( strpos($php_self, '?') !== false ) ? '&amp;' : '?' ) . $key . '=' . $value;
        }

        $pagination = generate_pagination($php_self, get_count('posts', ( $id_users > 0 ) ? 'id_users = ' . $id_users : ''), $preferences['per_page'], $start);
    }

    if ( $id_posts > 0 )
        list($comments_data, $comments) = get_comments($id_posts);

    return array($data, $posts, $pagination, $comments);
}

/**
 * Obtiene los comentarios de un post en particular.
 *
 * @param int $id_posts
 * @return array
 */
function get_comments( $id_posts )
{
    global $db;

    $q = new Query($db);
    $q->exec('SELECT comments.id_comments
                   , comments.id_posts
                   , comments.author
                   , comments.author_email
                   , comments.author_url
                   , comments.date
                   , comments.content
             FROM comments
             WHERE comments.approved = \'yes\'
             AND comments.id_posts = ' . $id_posts . '
             ORDER BY comments.date DESC');

    $data = array();
    $comments = '';

    for ( $i = 0; $i < $q->numrows; $i++ )
    {
        $data[$q->data['id_comments']] = $q->data;
        $class = ( $i % 2 == 0 ) ? 'comment_odd' : 'comment_even';
        $author_link = ( strlen($q->data['author_url']) > 0 ) ? $q->data['author_url'] : 'mailto:' . $q->data['author_email'];
        $comments .= '
<div class="' . $class . '">
    <span class="comnum">' . ( $q->numrows - $i ) . '</span>
    <p><a href="' . $author_link . '">' . $q->data['author'] . '</a> comments:</p>
    <p>' . $q->data['content'] . '</p>
</div>';

        $q->nxt();
    }

    $q->free();

    return array($data, $comments);
}

/**
 * Obtiene todos los labels !!!
 *
 * @param int $id_posts
 * @return array
 */
function get_labels( $id_posts = 0 )
{
    global $db;

    $q = new Query($db);
    $q->exec('SELECT labels.id_labels
                   , labels.name
              FROM labels
              ORDER BY labels.name');

    for ( $i = 0; $i < $q->numrows; $i++ )
    {
        $data[$q->data['id_labels']] = $q->data;
        $csv .= '"' . $q->data['id_labels'] . '",';
        $csv_name .= '"' . $q->data['name'] . '",';

        if ( $id_posts > 0 )
        {
            $q2 = new Query($db);
            $q2->exec('SELECT posts2labels.id_posts
                            , posts2labels.id_labels
                       FROM posts2labels
                       WHERE posts2labels.id_posts = ' . $id_posts . '
                       AND posts2labels.id_labels = ' . $q->data['id_labels']);

            if ( $q2->numrows > 0 )
                $csv_remove .= '"' . $q->data['id_labels'] . '",';
            else
                $csv_apply .= '"' . $q->data['id_labels'] . '",';

            $q2->free();
        }
        else
            $csv_apply .= '"' . $q->data['id_labels'] . '",';

        $q->nxt();
    }

    $csv = substr($csv, 0, strlen($csv) - 1);
    $csv_name = substr($csv_name, 0, strlen($csv_name) - 1);
    $csv_apply = substr($csv_apply, 0, strlen($csv_apply) - 1);

    if ( $id_posts > 0 )
        $csv_remove = substr($csv_remove, 0, strlen($csv_remove) - 1);

    $q->free();

    return array(
        'data' => $data,
        'csv' => $csv,
        'csv_name' => $csv_name,
        'csv_apply' => $csv_apply,
        'csv_remove' => $csv_remove
    );
}

/**
 * Obtiene la cantidad de registros en $table
 * con una clausura opcional.
 *
 * @param string $table
 * @param string $where
 * @return int
 */
function get_count( $table, $where = '' )
{
    global $db;

    $q = new Query($db);
    $q->exec('SELECT COUNT(*) AS cantidad
              FROM ' . $table . ( ( strlen($where) > 0 ) ? '
              WHERE ' . $where : '' ));

    if ( $q->numrows > 0 )
        $cantidad = $q->data['cantidad'];
    else
        $cantidad = 0;

    $q->free();

    return $cantidad;
}

/**
 * Obtiene los usuarios y los <li> del
 * submenu de la derecha.
 *
 * @return array
 */
function get_users()
{
    global $db;
    global $id_users;

    $q = new Query($db);
    $q->exec('SELECT users.id_users
                   , users.username
                   , users.name
                   , users.description
              FROM users');

    $data = array();
    $submenu = '';

    for ( $i = 0; $i < $q->numrows; $i++ )
    {
        $data[$q->data['id_users']] = $q->data;
        $selected = ( $id_users == $q->data['id_users'] ) ? ' class="selected"' : '';
        $submenu .= '
<li><a href="?id_users=' . $q->data['id_users'] . '"' . $selected . '>' . $q->data['name'] . '</a></li>';

        $q->nxt();
    }

    $q->free();

    return array($data, $submenu);
}

/**
 * Pagination routine, generates
 * page number sequence
 *
 * @param string $base_url
 * @param int $num_items
 * @param int $per_page
 * @param int $start_item
 * @param boolean $add_prevnext_text
 * @return string
 */
function generate_pagination( $base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true )
{
    $total_pages = ceil($num_items / $per_page);

    if ( $total_pages == 1 )
        return '';

    $on_page = floor($start_item / $per_page) + 1;
    $page_string = '';

    if ( $total_pages > 10 )
    {
        $init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

        for ( $i = 1; $i < $init_page_max + 1; $i++ )
        {
            $page_string .= ( $i == $on_page ) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) . '">' . $i . '</a>';

            if ( $i <  $init_page_max )
                $page_string .= ", ";
        }

        if ( $total_pages > 3 )
        {
            if ( $on_page > 1  && $on_page < $total_pages )
            {
                $page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

                $init_page_min = ( $on_page > 4 ) ? $on_page : 5;
                $init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

                for ( $i = $init_page_min - 1; $i < $init_page_max + 2; $i++ )
                {
                    $page_string .= ($i == $on_page) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) . '">' . $i . '</a>';

                    if ( $i <  $init_page_max + 1 )
                        $page_string .= ', ';
                }

                $page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
            }
            else
                $page_string .= ' ... ';

            for ( $i = $total_pages - 2; $i < $total_pages + 1; $i++ )
            {
                $page_string .= ( $i == $on_page ) ? '<strong>' . $i . '</strong>'  : '<a href="' . $base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) . '">' . $i . '</a>';

                if( $i <  $total_pages )
                    $page_string .= ", ";
            }
        }
    }
    else
    {
        for ( $i = 1; $i < $total_pages + 1; $i++ )
        {
            $page_string .= ( $i == $on_page ) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "&amp;start=" . ( ( $i - 1 ) * $per_page ) . '">' . $i . '</a>';

            if ( $i <  $total_pages )
                $page_string .= ', ';
        }
    }

    if ( $add_prevnext_text )
    {
        if ( $on_page > 1 )
            $page_string = ' <a href="' . $base_url . "&amp;start=" . ( ( $on_page - 2 ) * $per_page ) . '">Anterior</a>&nbsp;&nbsp;' . $page_string;

        if ( $on_page < $total_pages )
            $page_string .= '&nbsp;&nbsp;<a href="' . $base_url . "&amp;start=" . ( $on_page * $per_page ) . '">Siguiente</a>';
    }

    return $page_string;
}

/**
 * Truncate special chars
 *
 * @param string $string
 * @return string
 */
function specialchars( $text )
{
    // nada de mierdas por favor...
    $text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1&#058;", $text);
    $text = " " . htmlspecialchars( htmlentities($text) );

    if ( !( strpos($text, "[") && strpos($text, "]") ) )
        return trim($text);

    $text = str_replace('[quote]', '<blockquote><p>', $text);
    $text = str_replace('[/quote]', '</p></blockquote>', $text);

    $text = str_replace('[title]', '<h2>', $text);
    $text = str_replace('[/title]', '</h2>', $text);

    $text = str_replace('[list]', "\n<ul>\n", $text);
    $text = str_replace('[/list]', "\n</ul>\n", $text);

    $text = str_replace('[li]', '<li>', $text);
    $text = str_replace('[/li]', '</li>', $text);

    $text = str_replace('[b]', '<strong>', $text);
    $text = str_replace('[/b]', '</strong>', $text);

    $text = str_replace('[u]', '<u>', $text);
    $text = str_replace('[/u]', '</u>', $text);

    $text = str_replace('[i]', '<em>', $text);
    $text = str_replace('[/i]', '</em>', $text);

    $text = preg_replace("#\[img\]((http|ftp|https|ftps)://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png)))\[/img\]#sie", '[img]\\1' . str_replace(' ', '%20', '\\3') . '[/img]', $text);

    $patterns = array();
    $replacements = array();

    $patterns[] = "#\[img\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i";
    $replacements[] = '<img src="\\1" border="0" />';

    $patterns[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
    $replacements[] = '<a href="\\1">\\1</a>';

    $patterns[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
    $replacements[] = '<a href="http://\\1">\\1</a>';

    $patterns[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
    $replacements[] = '<a href="\\1">\\2</a>';

    $patterns[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
    $replacements[] = '<a href=http://\\1">\\3</a>';

    $patterns[] = "#\[email\]([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#si";
    $replacements[] = '<a href="mailto:\\1">\\1</a>';

    $text = preg_replace($patterns, $replacements, $text);

    return trim($text);
}

/**
 * Genera el RSS.XML con una clase bajada de internet,
 * ya voy a mejorar eso, pero por ahora NO.
 *
 * @return string
 */
function make_rss()
{
    global $db, $preferences;

    $rss = new UniversalFeedCreator();
    $rss->useCached(); // use cached version if age < 1 hour
    $rss->title = preg_replace("/(\<)(.*?)(\>)/mi", "", $preferences['site_name']);
    $rss->description = $preferences['site_description'];

    $rss->link = $preferences['site_url'];
    $rss->syndicationURL = $preferences['site_url'] . '/rss.xml';

    list($data) = get_posts();

    foreach ( $data as $key => $value )
    {
        $item = new FeedItem();
        $item->title = preg_replace("/(\<)(.*?)(\>)/mi", "", $value['title']);
        $item->link = $preferences['site_url'] . '/?id_posts=' . $value['id_posts'];
        $item->description = $value['content'];

        $item->date = $value['date'];
        $item->source = $preferences['site_url'];
        $item->author = $value['user'];

        $rss->addItem($item);
    }

    return $rss->saveFeed("RSS1.0", "../rss.xml", false);
}

/**
 * Lista el contenido de un directorio, si es -1
 * hace una busqueda recursiva.
 *
 * @param string $path # path to browse
 * @param string $maxdepth # how deep to browse ( -1 = unlimited )
 * @param string $mode # "FULL"|"DIRS"|"FILES"
 * @param unknown_type $d # must not be defined
 * @return array
 */
function searchdir( $path , $maxdepth = -1 , $mode = "FULL" , $d = 0 )
{
    if ( substr ( $path, strlen( $path ) - 1 ) != '/' )
        $path .= '/';

    $dirlist = array();

    if ( $mode != "FILES" )
        $dirlist[] = $path;

    if ( $handle = opendir($path) )
    {
        while ( false !== ( $file = readdir($handle) ) )
        {
            if ( $file != '.' && $file != '..' )
            {
                $file = $path . $file;

                if ( ! is_dir($file) ) { if ( $mode != "DIRS" ) { $dirlist[] = $file; } }
                elseif ( $d >= 0 && ( $d < $maxdepth || $maxdepth < 0 ) )
                {
                    $result = searchdir($file . '/', $maxdepth, $mode, $d + 1);
                    $dirlist = array_merge($dirlist, $result);
                }
            }
        }

        closedir($handle);
    }

    if ( $d == 0 )
        natcasesort($dirlist);

    return $dirlist;
}

?>