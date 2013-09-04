<?php

class Inmuebles_Fotos extends Model
{
    public static $sizes = array(
        'original'  => true,
        'large'     => array(1024, 768),
        'medium'    => array(500, 375),
        'small'     => array(240, 180),
        'thumbnail' => array(100, 75)
    );

    public function move( $new )
    {
        if ( !( $this->found ) )
            return false;

        if ( $this->posicion == $new )
            return false;

        if ( $this->posicion > $new )
        {
            Db::execute(
                "UPDATE `inmuebles_fotos`
                 SET `posicion` = `posicion` + 1
                 WHERE `posicion` < $this->posicion
                   AND `posicion` >= $new
                   AND `inmueble_id` = $this->inmueble_id"
            );
        }
        else
        {
            Db::execute(
                "UPDATE `inmuebles_fotos`
                 SET `posicion` = `posicion` - 1
                 WHERE `posicion` > $this->posicion
                   AND `posicion` <= $new
                   AND `inmueble_id` = $this->inmueble_id"
            );
        }

        $this->posicion = $new;
        $this->update();

        return true;
    }

    public static function one( $id )
    {
        return parent::_one(__CLASS__, $id);
    }

    public static function all( $where = null, $order = null, $limit = null )
    {
        return parent::_all(__CLASS__, $where, $order, $limit);
    }

    public static function count( $where = null )
    {
        return parent::_count(__CLASS__, $where);
    }

    public static function pos( $inmueble )
    {
        $pos = Db::fetchOne(
            Db::select(array('pos' => 'MAX(posicion)'), 'inmuebles_fotos', array('inmueble_id' => $inmueble))
        );

        if ( empty($pos) )
            $pos = 0;

        return $pos;
    }

    public static function upload( $tmp, $inmueble, $foto )
    {
        $root = "upload/$inmueble";

        if ( !( is_dir($root) ) )
        {
            mkdir($root, 0777);
            chmod($root, 0777);
        }

        $imagen = new Image($tmp);

        foreach ( Inmuebles_Fotos::$sizes as $folder => $size )
        {
            if ( !( is_dir("$root/$folder") ) )
            {
                mkdir("$root/$folder", 0777);
                chmod("$root/$folder", 0777);
            }

            if ( true === $size )
            {
                $imagen->clear();
                $imagen->save(
                    "$root/$folder/$foto.jpg", Image::FORMAT_JPG
                );

                continue;
            }

            $width  = array_shift($size);
            $height = array_shift($size);

            $imagen->clear();
            $imagen->resize($width, $height);
            $imagen->save(
                "$root/$folder/$foto.jpg", Image::FORMAT_JPG
            );
        }

        return true;
    }

    public static function destroy( $foto, $inmueble = null )
    {
        if ( !( $foto instanceof Inmuebles_Fotos ) )
            $foto = new Inmuebles_Fotos($foto);

        if ( !( $foto->found() ) )
            return false;

        if ( null === $inmueble )
            $inmueble = new Inmuebles($foto->inmueble_id);
        elseif ( !( $inmueble instanceof Inmuebles ) )
            $inmueble = new Inmuebles($inmueble);

        if ( !( $inmueble->found() ) )
            return false;

        foreach ( Inmuebles_Fotos::$sizes as $folder => $size )
        {
            if ( file_exists("upload/$inmueble->codigo/$folder/$foto->codigo.jpg") )
                unlink("upload/$inmueble->codigo/$folder/$foto->codigo.jpg");
        }

        return $foto->delete();
    }
}