<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Objects manage
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Kohana_LanboCMS_Objects {

    /**
     * Values for view input
     * @var array
     */
    protected $_view_values = array();
    
    /**
     * Object name
     * @var string
     */
    protected $_object_name = NULL;
    
    /**
     * Object value
     * @var array
     */
    protected $_object_value = NULL;
    
    /**
     * Factory, get new object instance
     * @return LanboCMS_Objects
     */
    public static function factory()
    {
        return new self();
    }

    /**
     * Get fields view
     * @return array View
     */
    public function fields_views(Object $object)
    {
        $this->_object = $object;
        $wysiwyg = Kohana::$config->load('lanbocms')->get('wysiwyg');
        
        $fields = $this->_object->get_fields(Object::EDIT);
        $fields_inputs = array();

        foreach ( $fields as $field => $mask )
        {
            $input = 'input';
            
            $input = ( $mask & Object::FIELD_FILE ) ? $this->_fields_process('file', $field, $mask) : $input;
            $input = ( $mask & Object::FIELD_TEXTAREA ) ? $this->_fields_process('textarea', $field, $mask) : $input;
            $input = ( $mask & Object::FIELD_WYSIWYG ) ? $this->_fields_process('wysiwyg-'.$wysiwyg, $field, $mask) : $input;
            $input = ( $mask & Object::FIELD_CHECKBOX ) ? $this->_fields_process('checkbox', $field, $mask) : $input;
            $input = ( $mask & Object::FIELD_MANY_TO_ONE ) ? $this->_fields_process('relation_many_to_one', $field, $mask) : $input;
            $input = ( $mask & Object::FIELD_ONE_TO_MANY ) ? $this->_fields_process('relation_one_to_many', $field, $mask) : $input;

            $fields_inputs[$field] = View::factory('backend/field/' . $input)
                ->set('field_name', $field)
                ->set('mask', $mask)
                ->set('value', isset($this->_object->$field) ? $this->_object->$field : NULL )
                ->set('object_name', $this->_object->get_type())
                ->set('view_values', $this->_view_values);                
        }

        return $fields_inputs;
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
     * Prepare view for relations 
     * @param string $field field name
     * @param int $mask
     * @return void
     */
    protected function _field_process_relation_many_to_one($field, $mask)
    {
        $values_output = array();
        $values = Object::factory($field)->find_all();
        
        if ( ! ( $mask & Object::FIELD_NOT_NULL ) )
        {
            Arr::unshift($values_output, NULL, NULL);
        }

        foreach ( $values as $value )
        {
            $values_output[$value->id] = $value->get_default_value();
        }
        
        $this->_view_values = array
        (
            'options' => $values_output,
        );
    }
    
    protected function _field_process_relation_one_to_many($field, $mask)
    {
        if ( ! $this->_object->is_loaded() )
        {
            $this->_view_values = array
            (
                'rows' => array(),
            );
            return;
        }
        
        $relation_model = Object::factory(Inflector::singular($field));
        $values_output = $relation_model->find_all_where($this->_object->get_type(), $this->_object->id);
        $fields = $relation_model->get_fields(Object::SHOW);

        $this->_view_values = array
        (
            'fields' => $fields,
            'rows' => $values_output,
        );
    }

} // End LanboCMS_Objects
