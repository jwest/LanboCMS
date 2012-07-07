<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model for categories
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Category extends Object {

    /**
     * id field is required
     * @var int
     */
    public $id = array();

    /**
     * category title
     * @var array
     */
    public $title = array(Object::FIELD_DEFAULT, Object::SHOW, Object::EDIT);

    /**
     * pages relation field
     * @var array
     */
    public $pages = array(Object::FIELD_ONE_TO_MANY, Object::SHOW, Object::EDIT);

    /**
     * updated at field
     * @var date
     */
    public $updated_at = array(Object::SHOW);

} // End Model_Category
