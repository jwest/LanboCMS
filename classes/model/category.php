<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model for categories
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Category extends Object {


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
            'pages' => Object::EDIT | Object::FIELD_RELATION,
            'updated_at' => Object::SHOW,
        );
    }


} // End Model_Category
