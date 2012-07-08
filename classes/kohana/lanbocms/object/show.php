<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Objects manage
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Kohana_LanboCMS_Object_Show extends LanboCMS_Object {

    /**
     * Prepare view for relations 
     * @param string $field field name
     * @param int $mask
     * @return void
     */
    protected function _field_process_relation_many_to_one($field, $mask)
    {
    	$value_name = NULL;

    	if ( $this->_object->$field !== NULL )
    	{
    		$value = Object::factory($field)->find($this->_object->$field);
        	$value_name = $value->get_default_value();
    	}

        $this->_view_values = array('value_name' => $value_name);
        return;
    }
    
    protected function _field_process_relation_one_to_many($field, $mask)
    {        
        $relation_model = Object::factory(Inflector::singular($field));
        $count = $relation_model->count_all_where($this->_object->get_type(), $this->_object->id);

        $this->_view_values = array('id' => $this->_object->id, 'count' => $count);
    }

} // End LanboCMS_Objects
