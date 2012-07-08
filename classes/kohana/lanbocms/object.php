<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Objects manage
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
abstract class Kohana_LanboCMS_Object {

    /**
     * use process fields for edit
     */
    const SHOW = 'Show';

    /**
     * Use process fields for show 
     */
    const EDIT = 'Edit';

    /**
     * Template path
     * @var string
     */
    protected $_view_path = 'backend/field/';

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
     * Type
     * @var string
     */
    protected $_type = NULL;
    
    /**
     * Factory, get new object instance
     * @param  string $type   show|edit
     * @param  Object $object
     * @return LanboCMS_Objects
     */
    public static function factory($type, Object $object)
    {
        $class_name = 'LanboCMS_Object_' . $type;
        return new $class_name($object);
    }

    /**
     * Constructor
     * @param Object $object
     */
    protected function __construct(Object $object)
    {
        $this->_type = str_replace('LanboCMS_Object_', '', get_called_class());
        $this->_view_path = $this->_view_path . strtolower($this->_type).'/';
        $this->_type = strtoupper($this->_type);
        $this->_object = $object;   
    }
    
    /**
     * Process field and prepare values for views
     * 
     * @param string $name
     * @param string $field field name
     * @param int $mask
     * @return strin input name, must have!
     */
    protected function _fields_process($name, $field, $mask)
    {
        $this->_view_values = array();
        $method_name = '_field_process_'.$name;
        
        if ( method_exists($this, $method_name) )
        {
            $this->$method_name($field, $mask);
        }
        
        return $name;
    }

    /**
     * Get fields view
     * @return array View
     */
    public function prepare()
    {
        $fields = $this->_object->get_fields(constant('Object::' . $this->_type));
        $fields_inputs = array();

        foreach ( $fields as $field => $mask )
        {
            $fields_inputs[$field] = $this->_field_declaration_for_view($field, $mask);               
        }

        return $fields_inputs;
    }

    /**
     * find field type with mask
     * @param  string $field
     * @param  int    $mask
     * @return string
     */
    protected function _field_type($field, $mask)
    {
        $wysiwyg = Kohana::$config->load('lanbocms')->get('wysiwyg');

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
            
        try
        {
            return View::factory($this->_view_path . $input)
                ->set('field_name', $field)
                ->set('mask', $mask)
                ->set('value', isset($this->_object->$field) ? $this->_object->$field : NULL )
                ->set('object_name', $this->_object->get_type())
                ->set($this->_view_values)
                ->render();
        }
        catch(View_Exception $e)
        {
            return $this->_object->$field;
        }
    }    

} // End LanboCMS_Objects
