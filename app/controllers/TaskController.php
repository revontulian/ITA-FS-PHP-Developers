<?php
require_once ROOT_PATH . '/app/models/ModelTask.class.php';

class TaskController extends Controller{
   protected $modelTask;

   public function __construct() {
    $this->modelTask = new ModelTask();
   }
    public function indexAction (){
        $allTask = $this->modelTask->fetchAll();
        $this->view->allTask = $allTask;
    }

}