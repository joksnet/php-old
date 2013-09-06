<?php

include_once 'includes/common.php';
include_once 'includes/logic.php';

$timeStart = microtime(true);

$rectWidth = 160;
$rectHeight = 52;

function collapse( $x, $y )
{
    global $rectWidth;
    global $rectHeight;

    $collapse = false;

    foreach ( Logic::$contacts as $contact )
    {
        if ( $contact->isDraw )
        {
            $borders = array(
                array($x, $y),
                array($x + $rectWidth, $y),
                array($x, $y + $rectHeight),
                array($x + $rectWidth, $y + $rectHeight)
            );

            foreach ( $borders as $border )
            {
                $otherX = $border[0];
                $otherY = $border[1];

                if ( $otherX >= $contact->rect['x'] && $otherX <= $contact->rect['x'] + $rectWidth && $otherY >= $contact->rect['y'] && $otherY <= $contact->rect['y'] + $rectHeight )
                {
                    $collapse = true;
                    break;
                }
            }
        }
    }

    return $collapse;
}

Logic::load();

$imageWidth = 1024 * 6;
$imageHeight = 768 * 6;

$image = @imagecreate($imageWidth, $imageHeight);

$backgroundColor  = imagecolorallocate($image, 248, 248, 248);
$fontColor        = imagecolorallocate($image, 160, 160, 160);
$fontColor2       = imagecolorallocate($image, 200, 200, 200);

$fontSize = 2;
$fontWidth = imagefontwidth($fontSize);
$fontHeight = imagefontheight($fontSize);

$relationDistance = 350;

//-->

if ( sizeof(Logic::$contacts) > 0 )
{
    $nextX = ceil( $imageWidth / 2 + $rectWidth / 2 );
    $nextY = ceil( $imageHeight / 2 + $rectHeight / 2 );

    foreach ( Logic::$contacts as $contact )
    {
        if ( !( $contact->color ) )
            $contact->color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));

        imagesetstyle($image, array($contact->color, $contact->color, $contact->color, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT));

        if ( $contact->meetInPerson )
            $color = $contact->color;
        else
            $color = IMG_COLOR_STYLED;

        if ( !( $contact->isDraw ) )
        {
            $contact->isDraw = true;

            $x = $contact->rect['x'] = $nextX; // rand(1, $imageWidth);
            $y = $contact->rect['y'] = $nextY; // rand(1, $imageHeight);

            imagerectangle($image, $x, $y, $x + $rectWidth, $y + $rectHeight, $color);

            $textPos = 0;

            if ( strlen($contact->nombre) > 0 )
            {
                imagestring($image, $fontSize, $x + $textX + 6, $y + $textY + 6, $contact->nombre, $fontColor);
                $textPos += $fontHeight;
            }

            if ( strlen($contact->empresa) > 0 )
                imagestring($image, $fontSize, $x + 6, $y + $textPos + 6, $contact->empresa, $fontColor2);
        }

        if ( ( $relationsCount = sizeof($contact->relations) ) > 0 )
        {
            $degree = 0;

            foreach ( $contact->relations as $relation )
            {
                if ( $relation->meetInPerson )
                    $color = $contact->color;
                else
                    $color = IMG_COLOR_STYLED;

                if ( !( $relation->isDraw ) )
                {
                    $relation->isDraw = true;

                    /*
                    $times = 0;

                    do {
                        $times++;

                        if ( $times > 10 )
                            die('Ya probe 10 veces');

                        $plusX = sin($degree) * $relationDistance;
                        $plusY = cos($degree) * $relationDistance;

                        $x = $relation->rect['x'] = $contact->rect['x'] + $rectWidth + $plusX;
                        $y = $relation->rect['y'] = $contact->rect['y'] + $rectHeight + $plusY;

                        $degree += 15;
                    } while( collapse($x, $y) );
                     */

                    $textPos = 0;

                    $plusX = sin($degree) * $relationDistance;
                    $plusY = cos($degree) * $relationDistance;

                    $x = $relation->rect['x'] = $contact->rect['x'] + $rectWidth + $plusX;
                    $y = $relation->rect['y'] = $contact->rect['y'] + $rectHeight + $plusY;

                    $degree += ceil( 360 / $relationsCount );

                    imagerectangle($image, $x, $y, $x + $rectWidth, $y + $rectHeight, $color);

                    if ( strlen($relation->nombre) > 0 )
                    {
                        imagestring($image, $fontSize, $x + $textX + 6, $y + $textY + 6, $relation->nombre, $fontColor);
                        $textPos += $fontHeight;
                    }

                    if ( strlen($relation->empresa) > 0 )
                        imagestring($image, $fontSize, $x + 6, $y + $textPos + 6, $relation->empresa, $fontColor2);

                    imageline($image, $contact->rect['x'] + $rectWidth, $contact->rect['y'] + $rectHeight, $x, $y, $color);
                }
                else
                {
                    $x = $relation->rect['x'];
                    $y = $relation->rect['y'];

                    imageline($image, $contact->rect['x'] + $rectWidth, $contact->rect['y'] + $rectHeight, $x, $y, $color);
                }
            }
        }

        do {
            $nextX = rand($rectWidth + $relationDistance, $imageWidth - ( $rectWidth + $relationDistance ) * 2);
            $nextY = rand($rectHeight + $relationDistance, $imageHeight - ( $rectHeight + $relationDistance ) * 2);
        } while ( collapse($nextX, $nextY) );

        // break;
    }
}

//-->

$timeEnd = microtime(true);
$time = round(1000 * ($timeEnd - $timeStart), 3);

imagestring($image, $fontSize, $imageWidth - 60, $imageHeight - 20, $time . ' ms', $fontColor);

header('Content-type: image/png');

imagepng($image);
imagedestroy($image);