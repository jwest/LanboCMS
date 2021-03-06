<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Objects manage
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Kohana_LanboCMS_Object_Edit extends LanboCMS_Object {
    
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
    
    /**
     * Prepare view for relations
     * @param string $field field name
     * @param int $mask
     * @return void
     */
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
