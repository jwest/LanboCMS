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
    const FIELD_TEXT = 8;

    /**
     * Use for field type wysiwyg
     */
    const FIELD_WYSIWYG = 16;

    /**
     * Use for field type textarea
     */
    const FIELD_TEXTAREA = 24;

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
                foreach ( $value as $option )
                {
                    if ( isset ( $this->_fields_declaration[$name] ) )
                    {
                        $this->_fields_declaration[$name] = $this->_fields_declaration[$name] | $option;
                    }
                    else
                    {
                        $this->_fields_declaration[$name] = $option;   
                    }
                }
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
            if ( isset ( $this->$key ) )
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

        if ( empty ( $values ) )
        {
            return NULL;
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

    public function find_all(){}
    
    
    public function save(){}
    public function delete(){}

} // End Model_Object