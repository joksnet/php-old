<?php

class Admin_Synchronize extends Controller
{
    public function init()
    {
        if ( !( $this->session->login ) )
            return '/admin/login';

        return true;
    }

    public function get()
    {
        return array(
            'models' => $this->sync()
        );
    }

    protected function sync( $root = '' )
    {
        $directory = dir('application/models/' . $root);
        $models = array();

        while ( false !== ( $entry = $directory->read() ) )
        {
            if ( strncmp($entry, '.', 1) == 0 )
                continue;

            $filename = $directory->path . $entry;

            if ( is_dir($filename) )
            {
                $models = array_merge($models,
                    $this->sync($root . $entry . '/')
                );

                continue;
            }

            include_once $filename;

            $class = str_replace('/', '_', $root)
                   . basename($entry, '.php');

            if ( !( class_exists($class) ) )
                continue;

            $instance = new $class();
            $instance->synchronize();

            $models[] = $class;
        }

        if ( $directory )
            $directory->close();

        return $models;
    }
}