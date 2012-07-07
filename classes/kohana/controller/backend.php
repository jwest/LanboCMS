<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Backend extends Controller_Template {


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
     * @var string
     */
    public $media_path = 'media';


    /**
     * if object is only update
     * @var bool
     */
    public $object_only_update = FALSE;


    /**
     * Config for lanbo objects
     * @var Config
     */
    private $_config = NULL;


    /**
     * Prepare template and controller
     */
    public function before()
    {
        $this->_config = Kohana::$config->load('lanbocms');

        //check auth
        if ( $this->request->action() === 'signin' OR $this->request->action() === 'signout' )
        {
            return;
        }

        if ( Auth::instance()->logged_in('admin') == 0 )
        {            
            $this->request->redirect('admin/signin');
            return;
        }

        parent::before();

        $this->template->object_name = $this->object_name = $this->request->param( 'object' );        
        $this->object_model = Object::factory( Inflector::singular($this->object_name) );
        $this->template->only_update = $this->object_only_update = $this->object_model->is_only_update();        
        $this->template->media_path = $this->media_path;

        $this->template->menu = $this->_config->get('objects');
        $this->template->wysiwyg = $this->_config->get('wysiwyg');
    }


    /**
     * Signin to admin panel
     */
    public function action_signin()
    {
        if ( Auth::instance()->logged_in('admin') != 0 )
        {
            $this->request->redirect('admin/');
            return;
        }

        $error = false;

        if ( $this->request->method() === Request::POST )
        {
            $status = Auth::instance()->login($this->request->post('username'), $this->request->post('password'));

            if ($status)
            {
                $this->request->redirect('admin/');
                return;
            }

            $error = true;
        }

        $this->auto_render = FALSE;
        $this->response->body( View::factory( 'backend/signin', array('error' => $error, 'media_path' => $this->media_path)) );
    }

    /**
     * Signout action
     */
    public function action_signout()
    {
        Auth::instance()->logout();        
        $this->request->redirect('/');
    }

    /**
     * Default action for show items
     */
    public function action_index()
    {
        $rows = array();
        
        if ( $this->request->param('id') === NULL )
        {
            $rows = $this->object_model->find_all();
        }
        else
        {
            $params = explode(':', $this->request->param('id'));
            $rows = $this->object_model->find_all_where($params[0], $params[1]);
        }

        $view = View::factory( 'backend/lists' );
        $view->object_name = $this->object_name;
        $view->fields = $this->object_model->get_fields(Object::SHOW);        
        $view->only_update = $this->object_only_update;
        $view->rows = $rows;

        $this->template->content = $view;
    }

    /**
     * Create new object
     */
    public function action_create()
    {
        if ( $this->object_only_update )
        {
            $this->request->redirect( 'admin/' . $this->object_name );
            return;
        }

        $view = View::factory( 'backend/form' );
        $view->id = NULL;
        $view->error = NULL;

        if ( $this->request->method() === Request::POST )
        {
            try
            {
                $post = Arr::extract($this->request->post(), $this->object_model->get_fields(Object::EDIT, TRUE));
                $this->object_model->from_array($post)->save();
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
        $view->fields_inputs = LanboCMS_Objects::factory()->fields_views($this->object_model);

        $this->template->content = $view;
    }

    /**
     * Update object
     */
    public function action_update()
    {
        $id = $this->request->param( 'id' );
        $this->object_model->find($id);

        $view = View::factory( 'backend/form' );
        $view->id = $id;
        $view->error = NULL;

        if ( $this->request->method() === Request::POST )
        {
            try
            {                
                $post = Arr::extract($this->request->post(), $this->object_model->get_fields(Object::EDIT, TRUE));
                $this->object_model->from_array($post)->save();
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
        $view->only_update = $this->object_only_update;
        $view->fields_inputs = LanboCMS_Objects::factory()->fields_views($this->object_model->from_array($_POST));

        $this->template->content = $view;
    }

    /**
     * action for delete
     */
    public function action_delete()
    {
        if ( $this->object_only_update )
        {
            $this->request->redirect( 'admin/' . $this->object_name );
        }

        $id = $this->request->param( 'id' );
        $this->object_model->find($id)->delete();
        $this->request->redirect( 'admin/' . $this->object_name );
    }

} // End Controller_Frontend
