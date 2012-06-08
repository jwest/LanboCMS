<?php defined('SYSPATH') or die('No direct script access.');

Route::set('lanbocms/backend', 'admin(/<object>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'backend',
        'object'     => 'page',
		'action'     => 'index',
		'id'         => NULL,
	));

Route::set('lanbocms/frontend', '(<name>)', array('name' => '.+'))
	->defaults(array(
		'controller' => 'frontend',
		'action'     => 'index',
		'name'       => NULL,
	));
