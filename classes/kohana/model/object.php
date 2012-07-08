<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for objects
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class Kohana_Model_Object extends Model {

    /**
     * Use for show field
     */
    const SHOW = 1;

    /**
     * Use for field editable
     */
    const EDIT = 2;

    /**
     * Use for field type text
     */
    const FIELD_TEXT = 4;

    /**
     * Use for field type textarea
     */
    const FIELD_TEXTAREA = 8;

    /**
     * Use for field type wysiwyg
     */
    const FIELD_WYSIWYG = 16;

    /**
     * Use for field type checkbox
     */
    const FIELD_CHECKBOX = 32;

    /**
     * Use for relation one to many
     */
    const FIELD_ONE_TO_MANY = 64;

    /**
     * Use for relation many to one
     */
    const FIELD_MANY_TO_ONE = 128;

    /**
     * Use for file upload input
     */
    const FIELD_FILE = 256;

    /**
     * Use if field must be not null
     */
    const FIELD_NOT_NULL = 512;

    /**
     * Use if field is default for row
     */
    const FIELD_DEFAULT = 1024;

    /**
     * Table name
     */
    const TABLE_NAME = 'objects';

    /**
     * Table id field
     */
    const TABLE_FIELD_ID = 'id';

    /**
     * Table parent id field
     */
    const TABLE_FIELD_PARENT_ID = 'parent_id';

    /**
     * Table type field
     */
    const TABLE_FIELD_TYPE = 'type';

    /**
     * Table name field
     */
    const TABLE_FIELD_NAME = 'name';

    /**
     * Table value field
     */
    const TABLE_FIELD_VALUE = 'value';

    /**
     * id field is required
     * @var int
     */
    public $id = array(Object::SHOW);

    /**
     * table name
     * @var string
     */
    protected $_table = 'objects';

    /**
     * model only for show and update
     * @var boolean
     */
    protected $_only_update = false;

    /**
     * if object is loaded
     * @var boolean
     */
    protected $_loaded = false;

    /**
     * object type
     * @var string
     */
    protected $_object_type = NULL;

    /**
     * Default field name
     * @var string
     */
    protected $_default_field = 'id';

    /**
     * fields declaration from class propertis
     * @var array
     */
    protected $_fields_declaration = array();

    /**
     * create new object instance
     */
    public function __construct()
    {
        $this->_object_type = strtolower(substr(get_class($this), 6));
        $this->_clean_fields();
    }
    
    /**
     * Prepare fields and fields declaration
     * @return void
     */
    protected function _clean_fields()
    {
        foreach ( $this as $name => $value )
        {
            if ( $name[0] !== '_' )
            {
                $this->_fields_declaration[$name] = 0;

                foreach ( $value as $option )
                {
                    $this->_fields_declaration[$name] = $this->_fields_declaration[$name] | $option;                    
                }

                if ( $this->_fields_declaration[$name] & self::FIELD_DEFAULT )
                {
                    $this->_default_field = $name;
                }

                $this->$name = NULL;
            }
        }
    }

    /**
     * Get fields array
     * @return array
     */
    protected function _get_fields()
    {
        $fields = array();
        
        foreach ( $this as $name => $value )
        {
            if ( $name[0] !== '_' )
            {
                $fields[$name] = $value;
            }
        }
        
        return $fields;
    }

    /**
     * Get fields list with criteria (flags)
     * @param  int   $option flags
     * @return array
     */
    public function get_fields($option = NULL, $only_keys = FALSE)
    {
        if ( $option === NULL )
        {            
            return $only_keys ? array_keys($this->_fields_declaration) : $this->_fields_declaration;
        }

        $output = array();

        foreach ($this->_fields_declaration as $name => $value)
        {
            if ( $value & $option )
            {
                $output[$name] = $value;                
            }
        }

        return $only_keys ? array_keys($output) : $output;
    }
    
    /**
     * Get default value for object (use in relations)
     * @return mixed
     */
    public function get_default_value()
    {
        return $this->{$this->_default_field};
    }

    /**
     * Get object data as array
     * @param  string $key   for array key
     * @param  string $value for array value, if NULL get all fields
     * @return array
     */
    public function as_array($key = NULL, $value = NULL)
    {
        return $this->_get_fields();
    }
    
    /**
     * object from array
     * @param  array $arr
     * @return object
     */
    public function from_array($arr)
    {
        foreach ( $arr as $key => $value )
        {
            if ( isset ( $this->$key ) OR $this->$key === NULL )
            {
                $this->$key = $value;
            }
            else
            {
                throw new Exception('field `' . $key . '` is not exists!');
            }
        }

        return $this;
    }

    /**
     * object type name
     * @return string
     */
    public function get_type()
    {
        return $this->_object_type;
    }

    /**
     * object type plural name
     * @return string
     */
    public function get_type_plural()
    {
        return Inflector::plural($this->_object_type);
    }

    /**
     * check if model is loaded
     * @return boolean
     */
    public function is_loaded()
    {
        return (bool) $this->_loaded;
    }

    /**
     * Set loaded info
     * @param boolean $value
     * @return object
     */
    public function set_loaded($value)
    {
        $this->_loaded = (bool) $value;

        return $this;
    }

    /**
     * if model is only for show and update
     * @return boolean
     */
    public function is_only_update()
    {
        return (bool) $this->_only_update;
    }

    /**
     * Find object in db
     * @param  int    $id
     * @return object|NULL
     */
    public function find($id)
    {
        $values = DB::select()->from(self::TABLE_NAME)
            ->where(self::TABLE_FIELD_PARENT_ID, '=', $id)
            ->where(self::TABLE_FIELD_TYPE, '=', $this->_object_type)
            ->execute()->as_array(self::TABLE_FIELD_NAME, self::TABLE_FIELD_VALUE);
        
        if ( empty ( $values ) )
        {
            return NULL;
        }
        
        $values['id'] = $id;
        $this->from_array ( $values );
        $this->set_loaded ( TRUE );
        
        return $this;
    }

    /**
     * Find one object with where equals condition
     * @param  string $name  field name
     * @param  string $value field value
     * @return object|NULL
     */
    public function find_where($name, $value)
    {
        $output = $this->find_all_where($name, $value);
        return isset($output[0]) ? $output[0] : NULL;
    }

    /**
     * Find objects with where equals condition
     * @param  string $name  field name
     * @param  string $value field value
     * @return array
     */
    public function find_all_where($name, $value)
    {
        $values = DB::select('o3.*')->from(array(self::TABLE_NAME, 'o1'))
            ->join(array(self::TABLE_NAME, 'o2'))->on('o1.' . self::TABLE_FIELD_PARENT_ID, '=', 'o2.' . self::TABLE_FIELD_ID)
            ->join(array(self::TABLE_NAME, 'o3'))->on('o3.' . self::TABLE_FIELD_PARENT_ID, '=', 'o2.' . self::TABLE_FIELD_ID)            
            ->where('o1.' . self::TABLE_FIELD_NAME, '=', $name)
            ->where('o1.' . self::TABLE_FIELD_VALUE, '=', $value)
            ->where('o2.' . self::TABLE_FIELD_TYPE, '=', $this->_object_type)
            ->execute()->as_array();

        return $this->_prepare_objects_list($values);
    }

    /**
     * Count objects in db
     * @return int
     */
    public function count_all_where($name, $value)
    {
        $value = DB::select(array(DB::expr('COUNT(1)'), 'total'))->from(self::TABLE_NAME)
            ->where(self::TABLE_FIELD_TYPE, '=', $this->_object_type)
            ->where(self::TABLE_FIELD_NAME, '=', $name)
            ->where(self::TABLE_FIELD_VALUE, '=', $value)
            ->execute()->get('total');

        return $value;
    }

    /**
     * Find objects all objects
     * @return array
     */
    public function find_all()
    {
        $values = DB::select()->from(self::TABLE_NAME)
            ->where(self::TABLE_FIELD_TYPE, '=', $this->_object_type)
            ->where(self::TABLE_FIELD_PARENT_ID, '!=', NULL)
            ->execute()->as_array();

        return $this->_prepare_objects_list($values);
    }

    /**
     * Count objects in db
     * @return int
     */
    public function count_all()
    {
        $value = DB::select(array(DB::expr('COUNT(1)'), 'total'))->from(self::TABLE_NAME)
            ->where(self::TABLE_FIELD_TYPE, '=', $this->_object_type)
            ->where(self::TABLE_FIELD_PARENT_ID, '=', NULL)
            ->execute()->get('total');

        return $value;
    }

    /**
     * Prepare objects list with flat list from database
     * @param  array $values
     * @return array object
     */
    protected function _prepare_objects_list($values)
    {
        if ( empty ( $values ) )
        {
            return array();
        }

        $output = array();
        $values_arr = array();

        foreach ( $values as $value )
        {
            $values_arr[$value[self::TABLE_FIELD_PARENT_ID]][$value[self::TABLE_FIELD_NAME]] = $value[self::TABLE_FIELD_VALUE];            
        }
        
        foreach ( $values_arr as $id => $value )
        {
            $value['id'] = $id;
            $output[] = Model::factory($this->_object_type)->from_array($value)->set_loaded ( TRUE );
        }

        return $output;
    }
    
    /**
     * Save (create or update object)
     * @return int object id
     */
    public function save()
    {
        if ( $this->_loaded )
        {
            return $this->update();
        }
        else
        {
            return $this->create();
        }
    }

    /**
     * create object
     * @return int object id
     */
    public function create()
    {
        Database::instance()->begin();

        $id = $this->_create_row('object');
        $fields = $this->get_fields(NULL, TRUE);
        unset($fields['id']);

        foreach ( $fields as $field) 
        {
            $value = $this->_process_field($field, $this->$field);
            
            if ( $this->_fields_declaration[$field] & self::FIELD_NOT_NULL )
            {
                $this->_validation($field, $value, 'not_empty');
            }

            $this->_create_row($field, $value, $id);
        }

        Database::instance()->commit();
    }

    /**
     * create one db row
     * @param  string $name
     * @param  string $value
     * @param  int    $parent_id
     * @return int    row id
     */
    protected function _create_row($name, $value = NULL, $parent_id = NULL)
    {
        $fields = array(self::TABLE_FIELD_PARENT_ID, self::TABLE_FIELD_TYPE, self::TABLE_FIELD_NAME, self::TABLE_FIELD_VALUE);
        $values = array($parent_id, $this->_object_type, $name, $value);
        
        $output = DB::insert( self::TABLE_NAME, $fields )->values( $values )->execute();

        return $output[0];
    }

    /**
     * update object
     * @return int object id
     */
    public function update()
    {
        Database::instance()->begin();
        
        $fields = $this->get_fields(NULL, TRUE);
        unset($fields['id']);

        foreach ( $fields as $field) 
        {
            $value = $this->_process_field($field, $this->$field);
            
            if ( $this->_fields_declaration[$field] & self::FIELD_NOT_NULL )
            {
                $this->_validation($field, $value, 'not_empty');
            }

            $this->_update_row($field, $value, $this->id);
        }

        Database::instance()->commit();
    }

    /**
     * update one db row
     * @param  string $name
     * @param  string $value
     * @param  int    $parent_id
     * @return int    row id
     */
    protected function _update_row($name, $value = NULL, $parent_id = NULL)
    {
        $output = DB::update( self::TABLE_NAME )
            ->set( array( self::TABLE_FIELD_VALUE => $value ) )
            ->where( self::TABLE_FIELD_TYPE, '=', $this->_object_type )
            ->where( self::TABLE_FIELD_PARENT_ID, '=', $parent_id )
            ->where( self::TABLE_FIELD_NAME, '=', $name )
            ->execute();

        if ( $output === 0 )
        {
            $count = DB::select()
                ->from( self::TABLE_NAME )
                ->where( self::TABLE_FIELD_TYPE, '=', $this->_object_type )
                ->where( self::TABLE_FIELD_PARENT_ID, '=', $parent_id )
                ->where( self::TABLE_FIELD_NAME, '=', $name )
                ->execute()->count();

            if ( $count === 0 )
            {
                return $this->_create_row($name, $value, $parent_id);
            }
        }

        return $output;
    }

    /**
     * Remove object from table
     * @return void
     */
    public function delete()
    {
        Database::instance()->begin();

        DB::delete( self::TABLE_NAME )
            ->where( self::TABLE_FIELD_ID, '=', $this->id )
            ->and_where( self::TABLE_FIELD_TYPE, '=', $this->_object_type )
            ->execute();

        Database::instance()->commit();
    }

    /**
     * Simple validation field
     * @param string $field field name
     * @param mixed $value value
     * @param string $rule rule name
     * @param array $rule_value validation rule value (eg. max length)
     * @return bool
     */
    protected function _validation( $field, $value, $rule, $rule_value = NULL )
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
     * process field on create and update
     * @param  string $name
     * @param  mixed  $value
     * @return mixed
     */
    protected function _process_field($name, $value)
    {
        $method_name = '_process_field_'.strtolower($name);
        
        if ( method_exists($this, $method_name) )
        {
            return $this->$method_name($value);
        }

        return $value;
    }

    /**
     * Prepare date for updated_at field
     * @return string datetime
     */
    protected function _process_field_updated_at()
    {
        return date('Y-m-d H:i:s', time());
    }

} // End Model_Object