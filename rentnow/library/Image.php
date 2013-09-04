<?php

class Image
{
    const FORMAT_GIF = 'GIF';
    const FORMAT_JPG = 'JPG';
    const FORMAT_PNG = 'PNG';

    protected $options = array();

    protected $filename;
    protected $format;
    protected $mime;

    protected $original;
    protected $originalWidth;
    protected $originalHeight;

    protected $image;
    protected $imageWidth;
    protected $imageHeight;

    public function __construct( $filename, $options = array() )
    {
        if ( !( extension_loaded('gd') ) )
            throw new Exception("Extension GD is not present.");

        if ( !( file_exists($filename) ) )
            throw new Exception("File \"$filename\" not found.");

        $fileinfo = getimagesize($filename);

        if ( false === $fileinfo )
            throw new Exception("File \"$filename\" is not a valid image.");

        $this->filename = $filename;
        $this->mime = $fileinfo['mime'];

        switch ( $this->mime )
        {
            case 'image/gif':
                $this->format = Image::FORMAT_GIF;
                $this->original = imagecreatefromgif($filename);
                break;

            case 'image/jpeg':
                $this->format = Image::FORMAT_JPG;
                $this->original = imagecreatefromjpeg($filename);
                break;

            case 'image/png':
                $this->format = Image::FORMAT_PNG;
                $this->original = imagecreatefrompng($filename);
                break;

            default:
                throw new Exception(
                    "Image \"$filename\" format is not supported."
                );
        }

        $this->originalWidth  = imagesx($this->original);
        $this->originalHeight = imagesy($this->original);

        foreach ( $options as $name => $value )
            $this->set($name, $value);

        $this->clear();
    }

    public function __destruct()
    {
        if ( is_resource($this->original) )
            imagedestroy($this->original);

        if ( is_resource($this->image) )
            imagedestroy($this->image);
    }

    public function get( $name )
    {
        if ( isset($this->options[$name]) )
            return $this->options[$name];

        throw new Exception("Option \"$name\" not found.");
    }

    public function set( $name, $value )
    {
        if ( !( isset($this->options[$name]) ) )
            throw new Exception("Option \"$name\" not found.");

        $this->options[$name] = $value;
    }

    public function info()
    {
        return array(
            'mime'   => $this->mime,
            'format' => $this->format,

            'width'  => $this->imageWidth,
            'height' => $this->imageHeight
        );
    }

    public function clear()
    {
        $this->image       = $this->original;
        $this->imageWidth  = $this->originalWidth;
        $this->imageHeight = $this->originalHeight;

        return $this;
    }

    public function update()
    {
        $this->original       = $this->image;
        $this->originalWidth  = $this->imageWidth;
        $this->originalHeight = $this->imageHeight;

        return $this;
    }

    public function resize( $width = 0, $height = 0, $mode = '-' )
    {
        if ( !( is_numeric($width) ) ) $width = 0;
        if ( !( is_numeric($height) ) ) $height = 0;

        if ( !( in_array($mode, array('+', '-')) ) )
            $mode = '-';

        $subject       = $this->image;
        $subjectWidth  = $this->imageWidth;
        $subjectHeight = $this->imageHeight;

        $ratio = $subjectWidth / $subjectHeight;

        if ( 0 >= $width )
        {
            $newWidth = $ratio * $height;
            $newHeight = $height;
        }
        elseif ( 0 >= $height )
        {
            $newWidth = $width;
            $newHeight = $width / $ratio;
        }
        else
        {
            if ( ( $width < $height && $subjectWidth > $subjectHeight ) || ( $width > $height && $subjectWidth < $subjectHeight ) )
            {
                $tmpWidth  = $width;
                $tmpHeight = $height;

                $width  = $tmpHeight;
                $height = $tmpWidth;
            }

            $multiX = $width / $subjectWidth;
            $multiY = $height / $subjectHeight;

            switch ( $mode )
            {
                case '+':
                    if ( $subjectHeight * $multiX > $height )
                    {
                        $newWidth = $width;
                        $newHeight = $subjectHeight * $multiX;
                    }
                    else
                    {
                        $newWidth = $subjectWidth * $multiY;
                        $newHeight = $height;
                    }
                    break;

                case '-':
                    if ( $subjectHeight * $multiX < $height )
                    {
                        $newWidth = $width;
                        $newHeight = $subjectHeight * $multiX;
                    }
                    else
                    {
                        $newWidth = $subjectWidth * $multiY;
                        $newHeight = $height;
                    }
                    break;
            }
        }

        if ( function_exists('imagecreatetruecolor') )
            $this->image = imagecreatetruecolor($newWidth, $newHeight);
        else
            $this->image = imagecreate($newWidth, $newHeight);

        if ( function_exists('imagecopyresampled') )
            imagecopyresampled($this->image, $subject, 0, 0, 0, 0, $newWidth, $newHeight, $subjectWidth, $subjectHeight);
        else
            imagecopyresized($this->image, $subject, 0, 0, 0, 0, $newWidth, $newHeight, $subjectWidth, $subjectHeight);

        $this->imageWidth  = $newWidth;
        $this->imageHeight = $newHeight;

        return $this;
    }

    public function resizeFixed( $width = 0, $height = 0 )
    {
        $subject       = $this->image;
        $subjectWidth  = $this->imageWidth;
        $subjectHeight = $this->imageHeight;

        if ( 0 >= $width )
            $width = $subjectWidth;

        if ( 0 >= $height )
            $height = $subjectHeight;

        if ( function_exists('imagecreatetruecolor') )
            $this->image = imagecreatetruecolor($width, $height);
        else
            $this->image = imagecreate($width, $height);

        if ( function_exists('imagecopyresampled') )
            imagecopyresampled($this->image, $subject, 0, 0, 0, 0, $width, $height, $subjectWidth, $subjectHeight);
        else
            imagecopyresized($this->image, $subject, 0, 0, 0, 0, $width, $height, $subjectWidth, $subjectHeight);

        $this->imageWidth = $width;
        $this->imageHeight = $height;

        return $this;
    }

    public function resizePercent( $percent )
    {
        if ( !( is_numeric($percent) ) || 0 >= $percent )
            throw new Exception('Percent must be a positive number.');

        $subject       = $this->image;
        $subjectWidth  = $this->imageWidth;
        $subjectHeight = $this->imageHeight;

        $width  = $subjectWidth * $percent / 100;
        $height = $subjectHeight * $percent / 100;

        if ( function_exists('imagecreatetruecolor') )
            $this->image = imagecreatetruecolor($width, $height);
        else
            $this->image = imagecreate($width, $height);

        if ( function_exists('imagecopyresampled') )
            imagecopyresampled($this->image, $subject, 0, 0, 0, 0, $width, $height, $subjectWidth, $subjectHeight);
        else
            imagecopyresized($this->image, $subject, 0, 0, 0, 0, $width, $height, $subjectWidth, $subjectHeight);

        $this->imageWidth = $width;
        $this->imageHeight = $height;

        return $this;
    }

    public function rotate( $degrees )
    {
        if ( !( is_numeric($degrees) ) )
            throw new Exception('Degrees must be numeric.');

        if ( !( function_exists('imagerotate') ) )
            throw new Exception('Rotation is not supported.');

        $subject       = $this->image;
        $subjectWidth  = $this->imageWidth;
        $subjectHeight = $this->imageHeight;

        $this->image       = imagerotate($subject, $degrees, 0);
        $this->imageWidth  = $subjectHeight;
        $this->imageHeight = $subjectWidth;

        return $this;
    }

    public function crop( $x, $y, $width, $height )
    {
        if ( !( is_numeric($x) ) ) $x = 0;
        if ( !( is_numeric($y) ) ) $y = 0;

        if ( !( is_numeric($width) ) || 0 >= $width )
            throw new Exception('Width must be a positive number.');

        if ( !( is_numeric($height) ) || 0 >= $height )
            throw new Exception('Height must be a positive number.');

        $subject       = $this->image;
        $subjectWidth  = $this->imageWidth;
        $subjectHeight = $this->imageHeight;

        $width = $subjectWidth < $width ? $subjectWidth : $width;
        $height = $subjectHeight < $height ? $subjectHeight : $height;

        if ( 0 >= $x ) $x = 0;
        if ( 0 >= $y ) $y = 0;

        if ( $x + $width > $subjectWidth )
            $x = $subjectWidth - $width;
        if ( $y + $height > $subjectHeight )
            $y = $subjectHeight - $height;

        if ( function_exists('imagecreatetruecolor') )
            $this->image = imagecreatetruecolor($width, $height);
        else
            $this->image = imagecreate($width, $height);

        if ( function_exists('imagecopyresampled') )
            imagecopyresampled($this->image, $subject, 0, 0, $x, $y, $width, $height, $width, $height);
        else
            imagecopyresized($this->image, $subject, 0, 0, $x, $y, $width, $height, $width, $height);

        $this->imageWidth = $width;
        $this->imageHeight = $height;

        return $this;
    }

    public function cropCenter( $width, $height )
    {
        if ( !( is_numeric($width) ) || 0 >= $width )
            throw new Exception('Width must be a positive number.');

        if ( !( is_numeric($height) ) || 0 >= $height )
            throw new Exception('Height must be a positive number.');

        $subject       = $this->image;
        $subjectWidth  = $this->imageWidth;
        $subjectHeight = $this->imageHeight;

        $width = $subjectWidth < $width ? $subjectWidth : $width;
        $height = $subjectHeight < $height ? $subjectHeight : $height;

        $x = intval(($subjectWidth - $width) / 2);
        $y = intval(($subjectHeight - $height) / 2);

        return $this->crop($x, $y, $width, $height);
    }

    public function alpha( $red, $green, $blue )
    {
        if ( 0 > $red || $red > 255 )
            throw new Exception('Red must be between 0 and 255.');
        if ( 0 > $green || $green > 255 )
            throw new Exception('Green must be between 0 and 255.');
        if ( 0 > $blue || $blue > 255 )
            throw new Exception('Blue must be between 0 and 255.');

        switch ( $this->format )
        {
            case Image::FORMAT_GIF:
                // imagecolortransparent($this->image, imagecolorallocate($this->image, $red, $green, $blue));
                // imagetruecolortopalette($this->image, true, 256);
                break;

            case Image::FORMAT_JPG:
                break;

            case Image::FORMAT_PNG:
                // imagealphablending($this->image, false);
                // imagefill($this->image, 0, 0, imagecolorallocatealpha($this->image, $reg, $green, $blue, 0));
                // imagesavealpha($this->image, true);
                break;
        }

        return $this;
    }

    public function save( $filename, $format = null )
    {
        if ( is_null($format) )
            $format = $this->format;

        if ( !( in_array($format, array(Image::FORMAT_GIF, Image::FORMAT_JPG, Image::FORMAT_PNG)) ) )
            throw new Exception('Invalid format type specified');

        $dirname = dirname($filename);

        if ( !( is_dir($dirname) ) )
        {
            if ( !( mkdir($dirname, 077, true) ) )
                throw new Exception("Folder \"$dirname\" not found.");

            chmod($dirname, 0777);
        }

        if ( !( is_writeable($dirname) ) && !( chmod($dirname, 0777) ) )
            throw new Exception("Folder \"$dirname\" is not writeable.");

        switch ( $format )
        {
            case Image::FORMAT_GIF:
                imagegif($this->image, $filename);
                break;

            case Image::FORMAT_JPG:
                imagejpeg($this->image, $filename);
                break;

            case Image::FORMAT_PNG:
                imagepng($this->image, $filename);
                break;
        }

        return $this;
    }

    public function show( $raw = false )
    {
        if ( headers_sent() )
            throw new Exception('Cannot show image: Headers have already been sent.');

        if ( false === $raw )
            header("Content-type: $this->mime");

        switch ( $this->format )
        {
            case Image::FORMAT_GIF:
                imagegif($this->image);
                break;

            case Image::FORMAT_JPG:
                imagejpeg($this->image);
                break;

            case Image::FORMAT_PNG:
                imagepng($this->image);
                break;
        }

        return $this;
    }
}