<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for objects
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Model_Object extends Model {

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
     * if field is fileupload
     */
    const FIELD_FILE = 64;

    /**
     * Object type
     * @var string
     */
    protected $_object_type;


    /**
     * declare table name
     * @var string
     */
    protected $_table_name = 'values';


    /**
     * Only update objects (without create and delete)
     * @var boolean
     */
    protected $_only_update = FALSE;


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
     * If only update objects
     * @return bool
     */
    public function only_update()
    {
        return $this->_only_update;
    }


    /**
     * Initialize objects
     * @return void
     */
    protected function __construct()
    {
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

    public function _query_select( $columns = NULL )
    {
        return DB::select()->from($this->_table_name)
            ->as_object()
            ->where('object_type', '=', $this->_object_type);
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
            $base_object = $this->_query_select()
                ->and_where('object_id', '=', NULL)
                ->and_where('name', '=', $name)
                ->execute()->as_array();

            if ( !isset( $base_object[0] ) )
            {
                return NULL;
            }

            $base_object = $base_object[0];
        }

        $output['id'] = (int) $base_object->id;
        $output['obj'] = $base_object->name;

        $objects = $this->_query_select()
                ->and_where('object_id', '=', $base_object->id)
                ->execute();

        foreach ( $objects as $object )
        {
            if ( key_exists($object->name, $output) )
            {
                $output[$object->name] = $object->value;
            }
        }

        return (object) $output;
    }

    /**
     * Get all object from one type
     * @return array
     */
    public function find_obj_all()
    {
        $base_output = array();

        $base_objects = $this->_query_select()
            ->and_where('object_id', '=', NULL)
            ->execute();

        foreach ( $base_objects as $base_object )
        {
            $output = $this->_prepare_value();
            $output['id'] = (int) $base_object->id;
            $output['obj'] = $base_object->name;

            $objects = $this->_query_select()
                ->and_where('object_id', '=', $base_object->id)
                ->execute();

            foreach ( $objects as $object )
            {
                if ( key_exists($object->name, $output) )
                {
                    $output[$object->name] = $object->value;
                }
            }

            $base_output[$output['obj']] = (object) $output;
        }

        return $base_output;
    }

    /**
     * Save object
     * @param string $name
     * @param string $value
     * @param int $object_id int or object
     * @return object
     */
    protected function _create_obj( $name, $value, $object_id = NULL )
    {
        $fields = array('object_id', 'object_type', 'name', 'value');
        $values = array($object_id, $this->_object_type, $name, $value);
        
        $output = DB::insert( $this->_table_name, $fields )->values( $values )->execute();

        return (object) array( 'id' => $output[0], 'object_type' => $this->_object_type, 'object_id' => $object_id, 'name' => $name, 'value' => $value);
    }

    /**
     * Save object
     * @param string $name
     * @param string $value
     * @param int $object_id int or object
     * @param int $id id row if you have update name
     * @return object
     */
    protected function _update_obj( $name, $value, $object_id, $id = NULL )
    {
        $query = DB::update( $this->_table_name );

        if ( $id === NULL )
        {            
            $query
                ->set( array( 'value' => $value ) )
                ->where( 'object_id', '=', $object_id )
                ->and_where( 'object_type', '=', $this->_object_type )
                ->and_where( 'name', '=', $name );
        }
        else
        {
            $query
                ->set( array( 'name' => $name, 'value' => $value ) )
                ->where( 'id', '=', $id )
                ->and_where( 'object_type', '=', $this->_object_type );       
        }

        $query->execute();

        return (object) array( 'object_type' => $this->_object_type, 'object_id' => $object_id, 'name' => $name, 'value' => $value);
    }

    /**
     * create value in object
     * @param array $values
     * @return Object
     */
    public function create_obj( $values )
    {
        if ( $this->_only_update )
        {
            return NULL;
        }

        Database::instance()->begin();

        $obj = $this->_create_obj( $this->_process_obj($values['obj']), NULL );
        $items = $this->items();

        unset ( $items['obj'] );

        foreach ( $items as $field => $mask )
        {
            $value = isset( $values[$field] ) ? $values[$field] : NULL;

            if ( $mask & self::NOT_NULL )
            {
                $this->_validation_obj( $field, $value );
            }

            $method_name = '_process_' . $field;

            $value = ( method_exists( $this, $method_name ) )
                ? $this->$method_name( $value, $obj->id )
                : $value;

            $this->_create_obj( $field, $value, $obj->id );
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

        $obj = $this->_update_obj( $this->_process_obj($values['obj'], $values['id']), NULL, NULL, $values['id'] );
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
                ? $this->$method_name( $value, $values['id'] )
                : $value;

            $this->_update_obj($field, $value, $values['id']);
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
        $values = (array) $values;

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
     * Delete object
     * @param string $name
     * @return bool
     */
    public function delete_obj( $name )
    {
        Database::instance()->begin();

        if ( $this->_only_update )
        {
            return NULL;
        }

        $obj = $this->find_obj($name);
        
        if ( $obj === NULL )
        {
            return FALSE;
        }

        DB::delete( $this->_table_name )
            ->where( 'object_id', '=', $obj->id )
            ->and_where( 'object_type', '=', $this->_object_type )
            ->execute();

        DB::delete( $this->_table_name )
            ->where( 'id', '=', $obj->id )
            ->and_where( 'object_type', '=', $this->_object_type )
            ->execute();

        Database::instance()->commit();

        return TRUE;
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
     * @param int $id id base object
     * @return string
     */
    protected function _process_obj( $value, $id = NULL )
    {
        $this->_validation_obj('obj', $value);
        $this->_validation_obj('obj', $value, array($this, 'unique_obj'), array($id, ':value'));

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

    /**
     * check if base object name is unique
     * @param int $id
     * @param string $value
     * @return bool
     */
    public function unique_obj( $id, $value )
    {
        $obj = $this->_query_select()
            ->and_where( 'name', '=', $value )
            ->and_where('object_id', '=', NULL)
            ->execute()->as_array();

        if ( ! isset ($obj[0]) )
        {
            return TRUE;
        }

        return ( $obj[0]->id == $id );
    }

} // End Model_Object