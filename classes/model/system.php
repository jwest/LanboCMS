<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for articles
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_System extends Model_Object {


    /**
     * Only update objects (without create and delete)
     * @var boolean
     */
    protected $_only_update = TRUE;


    /**
     * Declare fields for page object
     * @return array
     */
    protected function _items()
    {
        return array
        (
            'obj' => Object::SHOW | Object::NOT_NULL,
            'value' => Object::SHOW | Object::EDIT,
            'updated_at' => Object::SHOW,
        );
    }


} // End Model_Article
