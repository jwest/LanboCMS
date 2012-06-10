<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for files
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_File extends Object {


    /**
     * path to upload dir,
     * must be public and chmod 0777
     */
    const UPLOAD_DIR = 'media/data/';


    /**
     * Declare fields for file object
     * @return array
     */
    protected function _items()
    {
        return array
        (
            'obj' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
            'file' => Object::EDIT | Object::SHOW | Object::FIELD_FILE,
            'updated_at' => Object::SHOW,
        );
    }


    /**
     * File upload processed
     * @param  string $value file name
     * @return string
     */
    protected function _process_file( $value )
    {
        $file = $_FILES['file'];

        if ( $file['size'] > 0 )
        {
            //prepare name
            $name_part = explode('.', $file['name']);
            $ext = '.' . $name_part[ count($name_part) - 1 ];
            unset( $name_part[ count($name_part) - 1 ] );

            $name = URL::title( implode('', $name_part) ) . $ext;

            //upload file
            Upload::$default_directory = self::UPLOAD_DIR;
            Upload::save($file, $name);

            return URL::site(self::UPLOAD_DIR . $name, NULL, FALSE);
        }

        return $value;
    }


} // End Model_File
