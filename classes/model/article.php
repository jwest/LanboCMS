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
            'tags' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
        );
    }


} // End Model_Page
