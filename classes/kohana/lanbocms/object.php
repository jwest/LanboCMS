<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Objects manage
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Kohana_LanboCMS_Object {

    /**
     * use process fields for edit
     */
    const FORM = 'Form';

    /**
     * Use process fields for show 
     */
    const EDIT = 'Edit';

    /**
     * Values for view input
     * @var array
     */
    protected $_view_values = array();
    
    /**
     * Object
     * @var string
     */
    protected $_object = NULL;
    
    /**
     * Factory, get new object instance
     * @param  string $type   show|edit
     * @param  Object $object
     * @return LanboCMS_Objects
     */
    public static function factory($type, Object $object)
    {
        $class_name = 'LanboCMS_Object_' . $type;
        return new $class_name();
    }

    /**
     * Constructor
     * @param Object $object
     */
    public function __construct(Object $object)
    {
        $this->_object = $object;   
    }

    /**
     * Prepare field for view
     * @return [type] [description]
     */
    public abstract function prepare();

    /**
     * find field type with mask
     * @param  string $field
     * @param  int    $mask
     * @return string
     */
    protected function _field_type($field, $mask)
    {
        $input = 'input';
        $input = ( $mask & Object::FIELD_FILE ) ? $this->_fields_process('file', $field, $mask) : $input;
        $input = ( $mask & Object::FIELD_TEXTAREA ) ? $this->_fields_process('textarea', $field, $mask) : $input;
        $input = ( $mask & Object::FIELD_WYSIWYG ) ? $this->_fields_process('wysiwyg-'.$wysiwyg, $field, $mask) : $input;
        $input = ( $mask & Object::FIELD_CHECKBOX ) ? $this->_fields_process('checkbox', $field, $mask) : $input;
        $input = ( $mask & Object::FIELD_MANY_TO_ONE ) ? $this->_fields_process('relation_many_to_one', $field, $mask) : $input;
        $input = ( $mask & Object::FIELD_ONE_TO_MANY ) ? $this->_fields_process('relation_one_to_many', $field, $mask) : $input;
        return $input;
    }

    /**
     * Create view for field
     * @param  string $field
     * @param  int    $mask
     * @return View
     */
    protected function _field_declaration_for_view($field, $mask)
    {
        $input = $this->_field_type($field, $mask);
            
        return View::factory('backend/field/' . $input)
            ->set('field_name', $field)
            ->set('mask', $mask)
            ->set('value', isset($this->_object->$field) ? $this->_object->$field : NULL )
            ->set('object_name', $this->_object->get_type())
            ->set('view_values', $this->_view_values);    
    }    

} // End LanboCMS_Objects
