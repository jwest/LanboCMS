<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for objects
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Object extends ORM {

    /**
     * show field
     */
    const SHOW = 1;

    /**
     * edit field
     */
    const EDIT = 2;

    /**
     * if field is main
     */
    const NOT_NULL = 4;

    /**
     * if field is checkbox
     */
    const FIELD_CHECKBOX = 8;

    /**
     * if field is textarea
     */
    const FIELD_TEXTAREA = 16;

    /**
     * if field is wysiwyg
     */
    const FIELD_WYSIWYG = 32;


    /**
     * Object type
     * @var string
     */
    protected $_object_type;


    /**
     * declare table name
     * @var string
     */
    protected $_table_name = 'items';


    /**
     * Get with mask
     * @param $mask int
     * @param $only_keys bool if you have get only fields name
     * @return array
     */
    public function items($mask = NULL, $only_keys = FALSE)
    {
        $obj = Object::factory( $this->_object_type );

        if ( $mask === NULL )
        {
            return $obj->_items();
        }

        $output = array();

        foreach ( $obj->_items() as $field => $item )
        {
            if ( $item & $mask )
            {
                if ( $only_keys )
                {
                    $output[] = $field;
                }
                else
                {
                    $output[$field] = $item;
                }
            }
        }

        return $output;
    }


    /**
     * Declare items for pages
     * @return array
     */
    protected function _items()
    {
        return array();
    }


    /**
     * Initialize objects
     * @return void
     */
    protected function _initialize()
    {
        parent::_initialize();

        $this->_object_type = strtolower(substr(get_class($this), 6));
    }


    /**
     * Get items for values
     * @return array
     */
    protected function _prepare_value()
    {
        $output = array();

        foreach ( $this->items() as $field => $item )
        {
            $output[$field] = NULL;
        }

        return $output;
    }

    /**
     * Get object from name
     * @param mixed $name
     * @return array
     */
    public function find_obj($name)
    {
        $output = $this->_prepare_value();
        $base_object = NULL;

        if( $name instanceof Model_Object )
        {
            $base_object = $name;
        }
        else
        {
            $base_object = self::factory($this->_object_type)
                ->where('object_id', '=', NULL)
                ->where('name', '=', $name)
                ->where('object_type', '=', $this->_object_type)
                ->find();

            if ( $base_object->id === NULL )
            {
                return NULL;
            }
        }

        $output['id'] = (int) $base_object->id;
        $output['obj'] = $base_object->name;

        $objects = $this
            ->where('object_id', '=', $base_object->id)
            ->where('object_type', '=', $this->_object_type)
            ->find_all();

        foreach ( $objects as $object )
        {
            if ( key_exists($object->name, $output) )
            {
                $output[$object->name] = $object->value;
            }
        }

        return $output;
    }

    /**
     * Get all object from one type
     * @return array
     */
    public function find_obj_all()
    {
        $output = array();

        $base_objects = self::factory($this->_object_type)
            ->where('object_id', '=', NULL)
            ->where('object_type', '=', $this->_object_type)
            ->find_all();

        foreach ( $base_objects as $base_object )
        {
            $output[$base_object->name] = $this->find_obj($base_object);
        }

        return $output;
    }

    /**
     * Save object
     * @param string $name
     * @param string $value
     * @param mixed $object_id int or object
     * @return Object;
     */
    protected function _save_obj( $name, $value, $object_id = NULL, $loaded_id = NULL )
    {
        $obj = ORM::factory('object', $loaded_id);

        $obj->object_id = $object_id;        
        $obj->object_type = $this->_object_type;
        $obj->name = $name;
        $obj->value = $value;
        $obj->save();

        return $obj;
    }

    /**
     * create value in object
     * @param array $values
     * @return Object
     */
    public function create_obj( $values )
    {
        Database::instance()->begin();

        $obj = $this->_save_obj( $this->_process_obj($values['obj']), NULL );
        $items = $this->items();

        unset ( $items['obj'] );

        foreach ( $items as $field => $mask )
        {
            $value = isset( $values[$field] ) ? $values[$field] : NULL;

            if ( $mask & self::NOT_NULL )
            {
                $this->_validation_obj($field, $value);                
            }

            $method_name = '_process_' . $field;

            $value = ( method_exists($this, $method_name ) )
                ? $this->$method_name( $value )
                : $value;

            $this->_save_obj($field, $value, $obj->id);
        }

        Database::instance()->commit();

        return $obj;
    }

    /**
     * update values in object
     * @param array $values
     * @return Object
     */
    public function update_obj( $values )
    {
        Database::instance()->begin();

        $obj = $this->_save_obj( $this->_process_obj($values['obj']), NULL, NULL, $values['id'] );
        $items = $this->items();

        unset ( $items['obj'] );

        foreach ( $items as $field => $mask )
        {
            $value = isset( $values[$field] ) ? $values[$field] : NULL;

            if ( $mask & self::NOT_NULL )
            {
                $this->_validation_obj($field, $value);                
            }

            $method_name = '_process_' . $field;

            $value = ( method_exists($this, $method_name ) )
                ? $this->$method_name( $value )
                : $value;

            $obj_temp = Object::factory( $this->_object_type )
                ->where( 'name', '=', $field )
                ->where( 'object_id', '=', $values['id'] )
                ->find();

            $this->_save_obj($field, $value, $obj->id, $obj_temp->id);
        }

        Database::instance()->commit();

        return $obj;
    }

    /**
     * save objects (create or update)
     * @param array $values
     * @return Object
     */
    public function save_obj( $values )
    {
        if ( isset( $values['id'] ) AND $values['id'] > 0 )
        {
            return $this->update_obj( $values );
        }
        else
        {
            return $this->create_obj( $values );
        }
    }

    /**
     * Simple validation field
     * @param string $field field name
     * @param mixed $value value
     * @param string $rule rule name
     * @param array $rule_value validation rule value (eg. max length)
     * @return bool 
     */
    protected function _validation_obj( $field, $value, $rule = 'not_empty', $rule_value = NULL )
    {
        $validation = new Validation(array($field => $value));
        $validation->rule($field, $rule, $rule_value );

        if ( !$validation->check() )
        {
            throw new Validation_Exception($validation);
        }

        return true;
    }

    /**
     * Process main obj
     * @param mixed $value
     * @return string
     */
    protected function _process_obj( $value )
    {
        $this->_validation_obj('obj', $value);

        return URL::title( $value );
    }

    /**
     * Default value for updated_at fields
     * @param mixed $value
     * @return string
     */
    protected function _process_updated_at( $value )
    {
        return date('Y-m-d H:i');
    }

} // End Model_Object