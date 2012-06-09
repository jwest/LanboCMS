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
     * Object Model
     * @var Model_Object
     */
    public $object_model = NULL;


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

        $this->object_name = $this->request->param( 'object' );
        $this->object_model = Object::factory( Inflector::singular($this->object_name) );

        $this->template->object_name = $this->object_name;
        $this->template->media_path = $this->media_path;

        $this->template->menu = LanboCMS_Objects::factory()->objects();
    }


    /**
     * Default action for show items
     */
	public function action_index()
	{
        $view = View::factory( 'backend/lists' );
        $view->object_name = $this->object_name;
        $view->fields = $this->object_model->items(Object::SHOW);
        $view->rows = $this->object_model->find_obj_all();

        $this->template->content = $view;
	}

    /**
     * Create new object
     */
    public function action_create()
    {
        $view = View::factory( 'backend/form' );
        $view->create = TRUE;
        $view->error = NULL;

        if ( $this->request->method() === Request::POST )
        {
            try
            {
                $post = Arr::extract($this->request->post(), $this->object_model->items(Object::EDIT, TRUE));

                Object::factory( Inflector::singular($this->object_name) )->save_obj($post);

                $this->request->redirect( 'admin/' . $this->object_name );
            }
            catch(ORM_Validation_Exception $e)
            {
                Database::instance()->rollback();
                $view->error = $e->errors('validations');
            }
            catch(Validation_Exception $e)
            {
                Database::instance()->rollback();
                $view->error = $e->array->errors('validations');
            }
        }

        $view->object_name = $this->object_name;
        $view->fields_inputs = LanboCMS_Objects::factory()->fields_views($this->object_name, $this->request->post() );

        $this->template->content = $view;
    }

    /**
     * Update object
     */
    public function action_edit()
    {
        $obj_name = $this->request->param( 'id' );

        $obj = Object::factory( Inflector::singular($this->object_name) )->find_obj($obj_name);

        $view = View::factory( 'backend/form' );
        $view->update = TRUE;
        $view->error = NULL;

        if ( $this->request->method() === Request::POST )
        {
            try
            {
                $post = Arr::extract($this->request->post(), $this->object_model->items(Object::EDIT, TRUE));

                Object::factory( Inflector::singular($this->object_name) )->save_obj( array_merge($obj, $post) );

                $this->request->redirect( 'admin/' . $this->object_name );
            }
            catch(ORM_Validation_Exception $e)
            {
                Database::instance()->rollback();
                $view->error = $e->errors('validations');
            }
            catch(Validation_Exception $e)
            {
                Database::instance()->rollback();
                $view->error = $e->array->errors('validations');
            }
        }

        $view->object_name = $this->object_name;
        $view->fields_inputs = LanboCMS_Objects::factory()->fields_views($this->object_name, $obj);

        $this->template->content = $view;
    }

} // End Controller_Frontend
