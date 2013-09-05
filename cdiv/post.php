<?php

$root = realpath( dirname( __FILE__ ) );

define('PAGE_POST', true);

include_once "$root/config.php";
include_once "$root/common.php";

$invalid = array();

if ( sizeof($_POST) > 0 )
{
    if ( !( empty($_POST['drugs']) ) )
        header("Location: $rootURL/post");

    $time = time();
    $id = md5( $time );

    if ( empty($_POST['name']) )
        $invalid['name'] = true;

    if ( empty($_POST['url']) )
        $invalid['url'] = true;
    elseif ( !( isValidURL($_POST['url']) ) )
        $invalid['url'] = true;

    if ( empty($_POST['tags']) )
        $invalid['tags'] = true;

    if ( empty($invalid) )
    {
        if ( $_FILES['screen']['error'] == UPLOAD_ERR_OK )
        {
            $exts = array( 'jpg' => true, 'png' => true );
            $extParts = explode('.', $_FILES['screen']['name']);
            $ext = array_pop($extParts);

            if ( isset($exts[$ext]) )
            {
                move_uploaded_file($_FILES['screen']['tmp_name'], "$root/upload/orig/$id.$ext");

                /**
                if ( class_exists('DefinedImage') )
                {
                    DefinedImage::load("$root/upload/orig/$id.$ext")

                        ->resize(320, 240)
                        ->save("$root/upload/small/$id.$ext")

                        ->resize(100, 75)
                        ->save("$root/upload/thumb/$id.$ext");
                }
                 */

                if ( class_exists('hft_image') )
                {
                    $img = new hft_image("$root/upload/orig/$id.$ext");
                    $img->resize(320, 240);
                    $img->output_resized("$root/upload/small/$id.$ext");

                    $img = new hft_image("$root/upload/orig/$id.$ext");
                    $img->resize(100, 75);
                    $img->output_resized("$root/upload/thumb/$id.$ext");
                }
                else
                {
                    copy("$root/upload/orig/$id.$ext", "$root/upload/small/$id.$ext");
                    copy("$root/upload/orig/$id.$ext", "$root/upload/thumb/$id.$ext");
                }

                mysql_query(
                    "INSERT INTO errors ( id, name, url, posted )
                     VALUES ( '$id', '{$_POST['name']}', '{$_POST['url']}', '$time' )"
                );

                $tags = explode(' ', $_POST['tags']);

                foreach ( $tags as $tag )
                {
                    $tag = strtolower( $tag );
                    $tag = str_replace(',', '', $tag);

                    mysql_query(
                        "INSERT INTO errors_tags ( id, tag )
                         VALUES ( '$id', '$tag' )"
                    );
                }

                header("Location: $rootURL/$id");
                exit();
            }
        }

        $invalid['screen'] = true;
    }
}

require_once "$root/theme/post.php";