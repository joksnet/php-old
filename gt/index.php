<?php

$root = realpath( dirname( __FILE__ ) );

include_once "$root/includes/functions.php";
include_once "$root/includes/common.php";

$i = ( isset($_GET['i']) ) ? $_GET['i'] : 0;

$t = ( isset($_GET['t']) ) ? $_GET['t'] : 'Inbox';
$p = ( isset($_GET['p']) ) ? $_GET['p'] : 1;

if ( !( $mbox = gtLogin($t) ) )
{
    gtLogout();
    gtRedirect('login');
}

$tags = imap_list($mbox, $ref, '%');

foreach ( $tags as $key => $tag )
{
    $tags[$key] = $tag = str_replace($ref, '', $tag);

    if ( $tag == 'INBOX' )
        unset($tags[$key]);
}

// $last7days = imap_search($mbox, 'BEFORE "22-May-2008"');

if ( $i )
{
    $structure = imap_fetchstructure($mbox, $i);
    $mailsOverview = imap_fetch_overview($mbox, "$i:$i");
    $mailOverview  = $mailsOverview[0];

    $subject = trim( $mailOverview->subject );
    $from    = trim( strip_tags( $mailOverview->from ) );
    $date    = $mailOverview->date;
    $message = '';

    foreach ( $structure->parts as $partNum => $part )
    {
        if ( $part->type == 0 && ( $part->ifsubtype && strtolower($part->subtype) == 'plain' ) )
            $message = imap_fetchbody($mbox, $i, $partNum + 1);
    }

    if ( !( empty($message) ) )
        $message = wordwrap($message, 80, '<br />');
}
else
{
    $to   = imap_num_msg($mbox) - ( ($p - 1) * 25 );
    $from = $to - 24;

    $mailsOverview = imap_fetch_overview($mbox, "$from:$to");
    $mails = array();

    $unread = 0;

    foreach ( $mailsOverview as $i => $mail )
    {
        $time    = strtotime($mail->date);
        $subject = trim( $mail->subject );
        $from    = trim( strip_tags( $mail->from ) );
        $email   = '';

        // var_dump($mail->date . ' = ' . $time . ' (' . date('d/m/Y H:i:s', $time) . ')');

        if ( strlen($subject) > 94 )
            $subject = substr($subject, 0, 91) . '...';

        if ( strlen($from) > 27 )
            $from = substr($from, 0, 24) . '...';

        if ( preg_match('/<(.*)>/', $mail->from, $matches) )
            $email = array_pop($matches);

        $mails[$time] = array(
            'num'     => $mail->msgno,
            'subject' => $subject,
            'from'    => $from,
            'email'   => $email,
            'date'    => date('d/m/Y H:i', $time),
            'seen'    => $mail->seen
        );

        if ( $mail->seen == 0 )
            $unread++;

        unset($subject);
    }

    krsort($mails);

    $pPrev = 0;
    $pNext = 0;

    if ( $p > 1 )
        $pPrev = $p - 1;

    if ( $to - 25 > 0 )
        $pNext = $p + 1;

    $_SESSION['unread'] = $unread;
}

@imap_close($mbox);

include_once "$root/includes/theme.php";