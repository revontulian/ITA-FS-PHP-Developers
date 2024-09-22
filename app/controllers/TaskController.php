<?php
require_once ROOT_PATH. '/app/models/ModelCRUD.class.php';

class TaskController extends Controller{
   protected $modelCrud;

   public function __construct() {
    $this->$modelCrud = new Task();
   }
    public function indexAction (){
        echo 'Bicha pajua jajajaja';
    }
}