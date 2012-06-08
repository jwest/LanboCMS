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
        $view->rows = Object::factory('page')->find_obj_all();

        $this->template->content = $view;
	}

    /**
     * Create new object
     */
    public function action_create()
    {
        if ( $this->request->method() === Request::POST )
        {
            try
            {
                Arr::extract($this->request->post(), Model_Page::items(Object::EDIT));
            }
            catch(ORM_Validation_Exception $e)
            {

            }
        }

        $view = View::factory('backend/create');

        $view->object_name = $this->object_name;
        $view->fields_inputs = LanboCMS_Objects::factory()->fields_views($this->object_name);

        $this->template->content = $view;
    }

    /**
     * Update object
     */
    public function action_edit()
    {
        $obj_name = $this->request->param('id');

        $obj = Object::factory( Inflector::singular($this->object_name) )->find_obj($obj_name);

        $view = View::factory('backend/create');

        $view->object_name = $this->object_name;
        $view->fields_inputs = LanboCMS_Objects::factory()->fields_views($this->object_name, $obj);

        $this->template->content = $view;
    }

} // End Controller_Frontend
