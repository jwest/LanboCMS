<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Objects manage
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class LanboCMS_Objects {

    /**
     * Factory, get new object instance
     * @return LanboCMS_Objects
     */
    public static function factory()
    {
        return new self();
    }

    /**
     * Get object list
     * @todo get from config
     * @return array
     */
    public function objects()
    {
        return Kohana::$config->load('lanbocms')->get('objects');
    }

    /**
     * Get default wysiwyg name
     * @return string
     */
    public function wysiwyg()
    {
        return Kohana::$config->load('lanbocms')->get('wysiwyg');
    }

    /**
     * Get fields view
     * @return array View
     */
    public function fields_views($object_name, $obj = NULL)
    {
        $fields = Object::factory( Inflector::singular($object_name) )->items(Object::EDIT);
        $fields_inputs = array();

        foreach ( $fields as $field => $mask )
        {
            $input = 'input';
            $input = ( $mask & Object::FIELD_TEXTAREA ) ? 'textarea' : $input;
            $input = ( $mask & Object::FIELD_WYSIWYG ) ? 'wysiwyg-'.$this->wysiwyg() : $input;
            $input = ( $mask & Object::FIELD_CHECKBOX ) ? 'checkbox' : $input;

            $fields_inputs[$field] = View::factory('backend/field/' . $input)
                ->set('field_name', $field)
                ->set('value', isset($obj[$field]) ? $obj[$field] : NULL )
                ->render();
        }

        return $fields_inputs;
    }

} // End LanboCMS_Objects
