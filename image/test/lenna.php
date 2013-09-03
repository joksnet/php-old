<?php

include_once '../Image.php';

$image = new Image('lenna.png');
$image
    ->resizePercent(50)
    ->rotate(180)
    ->cropCenter(200, 140)
    ->show();
    #->save('lenna240x180.png');
