<?php

include_once "$root/includes/String.php";
include_once "$root/includes/Config.php";
include_once "$root/includes/Db.php";
include_once "$root/includes/Request.php";
include_once "$root/includes/Theme.php";
include_once "$root/includes/Formats.php";

Db::open();

$last = array();
$lastData = Db::query(
    "SELECT s.id
          , s.filename
     FROM subs s
     ORDER BY s.time DESC
     LIMIT 10"
);

foreach ( (array) $lastData as $row )
    $last[$row['id']] = $row['filename'];

$lastSearch = array();
$lastSearchData = Db::query(
    "SELECT s.id
          , s.query
          , s.cant
     FROM search s
     ORDER BY s.time DESC
     LIMIT 10"
);

foreach ( (array) $lastSearchData as $row )
    $lastSearch[$row['id']] = array( 'query' => $row['query'], 'cant' => $row['cant'] );

$topCredits = array();
$topCreditsData = Db::query(
    "SELECT s.credits
          , COUNT(s.id) AS total
     FROM subs s
     GROUP BY s.credits
     ORDER BY total DESC
     LIMIT 10"
);

foreach ( (array) $topCreditsData as $row )
    $topCredits[$row['credits']] = $row['total'];