<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(
	'/' => 'task#index',
	'/index' => 'task#index',
	'/create' => 'task#create',
	'/update/:id' => 'task#update',
	'/updateId' => 'task#updateId',
	'/delete' => 'task#delete',
	'/test' => 'test#index'
	
);
