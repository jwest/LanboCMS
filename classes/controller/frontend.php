<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Frontend extends Controller {

    /**
     * Index page name
     */
    const PAGE_INDEX = 'index';

    /**
     * 404 page name
     */
    const PAGE_404 = '404';

    /**
     * Default action for show pages
     */
	public function action_index()
	{
		$name = $this->request->param('name');

        if ( $name === NULL )
        {
            $name = self::PAGE_INDEX;
        }

        $obj = Object::Factory('page')->find($name);

        if ( $obj === NULL OR $obj->publish != 1 )
        {
            $obj = Object::Factory('page')->find(self::PAGE_404);
            $this->request->status = 404;
        }

        $this->response->body( $this->_process_view($obj)->render() );
	}

    /**
     * Prepare view with object for response
     * @param  array $obj
     * @return object View
     */
    protected function _process_view($obj)
    {
        return View::factory('layout', (array)$obj);
    }

} // End Controller_Frontend
