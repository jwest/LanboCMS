<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for pages
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Page extends Object {

    /**
     * id field is required
     * @var int
     */
    public $id = array();

    /**
     * Route for page
     * @var string
     */
    public $route = array(Object::FIELD_DEFAULT, Object::SHOW, Object::EDIT, Object::FIELD_TEXT, Object::FIELD_NOT_NULL);

    /**
     * Title for page
     * @var string
     */
    public $title = array(Object::SHOW, Object::EDIT, Object::FIELD_TEXT);

    /**
     * Html content
     * @var string
     */
    public $content = array(Object::EDIT, Object::FIELD_WYSIWYG);

    /**
     * category list
     * @var array
     */
    public $category = array(Object::SHOW, Object::EDIT, Object::FIELD_MANY_TO_ONE, Object::FIELD_NOT_NULL);    

    /**
     * Description page (for mate tag)
     * @var string
     */
    public $description = array(Object::SHOW, Object::EDIT, Object::FIELD_TEXTAREA);

    /**
     * if page is publish
     * @var boolean
     */
    public $publish = array(Object::SHOW, Object::EDIT, Object::FIELD_CHECKBOX);

    /**
     * Last update date
     * @var date
     */
    public $updated_at = array(Object::SHOW, Object::FIELD_TEXT);

    /**
     * process route field (check unique)
     * @param  string $value
     * @return string
     */
    protected function _process_field_route($value)
    {
        $this->_validation('route', $value, array($this, 'unique'), array('field'=>'route'));
        return $value;
    }

} // End Model_Page
