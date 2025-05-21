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
    '/' => 'user#login',
    '/login' => 'user#login',
    '/register' => 'user#register',
    '/logout' => 'user#logout',
    '/profile' => 'user#profile',
    '/edit-profile' => 'user#edit',
    '/test' => 'test#index',
);
