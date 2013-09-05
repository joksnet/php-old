<?php

$root = '.';

include_once "$root/config.php";
include_once "$root/common.php";

$title = __('TLDs');

$tlds = Db::fetchAssoc(
    "SELECT tid, domain, description
     FROM tlds
     WHERE suggest = 0
     ORDER BY domain"
);

require_once "$root/themes/$theme/tlds.php";