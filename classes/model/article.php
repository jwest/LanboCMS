<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for articles
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Article extends Model_Object {


    /**
     * Declare fields for page object
     * @return array
     */
    protected function _items()
    {
        return array
        (
            'obj' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
            'updated_at' => Object::SHOW,
            'title' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
            'content' => Object::EDIT | Object::FIELD_WYSIWYG,
            'file' => Object::EDIT | Object::SHOW | Object::FIELD_FILE,
            'tags' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
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
            Upload::$default_directory = 'media/data/';
            Upload::save($file, $name);

            return $name;
        }

        return $value;
    }


} // End Model_Article
