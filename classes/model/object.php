<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for objects
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
abstract class Model_Object extends ORM {

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
    const MAIN = 4;

    const FIELD_CHECKBOX = 8;
    const FIELD_TEXTAREA = 16;
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
    protected $_table_name = 'objects';


    /**
     * Get with mask
     * @return array
     */
    public static function items($mask = NULL)
    {
        $obj = Object::factory('page');

        if ( $mask === NULL )
        {
            return $obj->_items();
        }

        $output = array();

        foreach ( $obj->_items() as $field => $item )
        {
            if ( $item & $mask )
            {
                $output[$field] = $item;
            }
        }

        return $output;
    }


    /**
     * Declare items for pages
     * @return array
     */
    abstract protected function _items();


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
    public function get($name)
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
                ->where('object_id', '=', 0)
                ->where('name', '=', $name)
                ->where('object_type', '=', $this->_object_type)
                ->find();

            if ( $base_object->id === NULL )
            {
                return NULL;
            }
        }

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


    public function get_all()
    {
        $output = array();

        $base_objects = self::factory($this->_object_type)
            ->where('object_id', '=', 0)
            ->where('object_type', '=', $this->_object_type)
            ->find_all();

        foreach ( $base_objects as $base_object )
        {
            $output[$base_object->name] = $this->get($base_object);
        }

        return $output;
    }

} // End Model_Object
