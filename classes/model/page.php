<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for pages
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Page extends Model_Object {


    /**
     * Declare fields for page object
     * @return array
     */
    protected function _items()
    {
        return array
        (
            'obj' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
            'title' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
            'description' => Object::SHOW | Object::EDIT | Object::FIELD_TEXTAREA,
            'content' => Object::EDIT | Object::FIELD_WYSIWYG,
        );
    }


} // End Model_Page
