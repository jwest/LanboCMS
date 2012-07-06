<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for pages
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Page extends Object {

    /**
     * Route for page
     * @var string
     */
    public $route = array(Object::SHOW, Object::EDIT, Object::FIELD_TEXT);

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
     * Last update date
     * @var date
     */
    public $updated_at = array(Object::SHOW, Object::FIELD_TEXT);

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


} // End Model_Page
