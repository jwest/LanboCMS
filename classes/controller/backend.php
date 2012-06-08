<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Backend extends Controller_Template {


    /**
     * Default layout
     * @var string
     */
    public $template = 'backend/layout';


    /**
     * Object name
     * @var string
     */
    public $object_name = NULL;


    /**
     * Media path
     */
    public $media_path = 'media';


    /**
     * Prepare template and controller
     */
    public function before()
    {
        parent::before();

        $this->object_name = $this->request->param('object');
        $this->template->object_name = $this->object_name;
        $this->template->media_path = $this->media_path;

        $this->template->menu = LanboCMS_Objects::factory()->objects();
    }


    /**
     * Default action for show pages
     */
	public function action_index()
	{
        $view = View::factory('backend/lists');
        $view->object_name = $this->object_name;
        $view->fields = Model_Page::items(Object::SHOW);
        $view->rows = Object::factory('page')->get_all();

        $this->template->content = $view;
	}

    /**
     * Create new object
     */
    public function action_create()
    {
        $object_name = $this->object_name;
        $fields = Model_Page::items(Object::EDIT);
        $fields_inputs = array();

        foreach ( $fields as $field => $mask )
        {
            $input = 'input';
            $input = ( $mask & Object::FIELD_TEXTAREA ) ? 'textarea' : $input;
            $input = ( $mask & Object::FIELD_WYSIWYG )  ? 'wysiwyg'  : $input;
            $input = ( $mask & Object::FIELD_CHECKBOX ) ? 'checkbox' : $input;

            $fields_inputs[] = View::factory('backend/field/' . $input)->set('field_name', $field)->render();
        }

        $view = View::factory('backend/create');

        $view->object_name = $object_name;
        $view->fields_inputs = $fields_inputs;

        $this->template->content = $view;
    }

} // End Controller_Frontend
