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
     * id field is required
     * @var int
     */
    public $id = array(Object::FIELD_DEFAULT, Object::SHOW);

    /**
     * filename
     * @var string
     */
    public $file = array(Object::FIELD_DEFAULT, Object::SHOW, Object::EDIT, Object::FIELD_FILE, Object::FIELD_NOT_NULL);

    /**
     * Last update date
     * @var date
     */
    public $updated_at = array(Object::SHOW, Object::FIELD_TEXT);

    /**
     * File upload processed
     * @param  string $value file name
     * @return string
     */
    protected function _process_field_file( $value )
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
