<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for articles
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_System extends Object {


    /**
     * Only update objects (without create and delete)
     * @var boolean
     */
    protected $_only_update = TRUE;

    /**
     * id field is required
     * @var int
     */
    public $id = array();

    /**
     * Setting name
     * @var string
     */
    public $name = array(Object::FIELD_DEFAULT, Object::SHOW, Object::EDIT, Object::FIELD_TEXT, Object::FIELD_NOT_NULL);

    /**
     * Setting value
     * @var array
     */
    public $value = array(Object::FIELD_TEXTAREA, Object::SHOW, Object::EDIT);

} // End Model_Article
