<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for pages
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Page extends Object {


    /**
     * Declare fields for page object
     * @return array
     */
    protected function _items()
    {
        return array
        (
            'obj' => Object::SHOW | Object::EDIT | Object::NOT_NULL,
            'category' => Object::SHOW | Object::EDIT | Object::FIELD_RELATION | Object::NOT_NULL,
            'title' => Object::SHOW | Object::EDIT | Object::NOT_NULL,            
            'content' => Object::EDIT | Object::FIELD_WYSIWYG,
            'publish' => Object::EDIT | Object::FIELD_CHECKBOX,
            'description' => Object::SHOW | Object::EDIT | Object::FIELD_TEXTAREA,
            'updated_at' => Object::SHOW,
        );
    }


} // End Model_Page
