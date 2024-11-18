<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ApplicationController extends Controller 
{
    protected Database $database;

	public function __construct(){
        $this->database = new Database();
    }
}
