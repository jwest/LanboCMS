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

        $obj = Object::Factory('page')->find_obj($name);

        if ( $obj === NULL )
        {
            $obj = Object::Factory('page')->find_obj(self::PAGE_404);
        }

        var_dump($obj);
	}

} // End Controller_Frontend
