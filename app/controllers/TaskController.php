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
    public function createAction(){
        var_dump(WEB_ROOT);
        if($_SERVER["REQUEST_METHOD"]=== "POST"){
            
            $title = $_POST['title'];
            $status = $_POST['status'];
            $starTime = $_POST['starTime'];
            $deadLine = $_POST['deadLine'];
            $user = $_POST['user'];

            $data = [
                'title' => $title,
                'status' => $status,
                'starTime' => $starTime,
                'deadLine' => $deadLine,
                'user' => $user,
            ];
        $this->modelTask->create($data);
        header('Location: ' . WEB_ROOT . '/index');
        exit;
        }
    }
    public function updateAction(){

        //$url = explode('/',$_SERVER['REQUEST_URI']);
        //$taskId = end($url);
        if($_SERVER["REQUEST_METHOD"]=== "POST"){
            
            $id = $_POST['id'];
            $title = $_POST['title'];
            $status = $_POST['status'];
            $starTime = $_POST['starTime'];
            $deadLine = $_POST['deadLine'];
            $user = $_POST['user'];

            $data = [
                'id' =>$id,
                'title' => $title,
                'status' => $status,
                'starTime' => $starTime,
                'deadLine' => $deadLine,
                'user' => $user,
            ];
        
        $taskupdate = $this->modelTask->fetchId($id);
        if(!$taskupdate){
            die('tarea no encontrada y tus nalgas tampoco ');
        header('Location: ' . WEB_ROOT . '/index');
        exit;
        } else{
            $this->modelTask->update($data);
            header('Location: ' . WEB_ROOT . '/index');
        exit;
        }
        }
    }
    public function deleteAction(){
        // Obtener el ID de la tarea desde la URL
        //$url = explode('/', $_SERVER['REQUEST_URI']);
       // $taskId = end($url);
    
       if($_SERVER["REQUEST_METHOD"]=== "POST"){
            
        $id = $_POST['id'];
        $task = $this->modelTask->fetchId($id);
        if (!$task) {
            echo "La tarea no existe.";
            exit;
        }
        
        $deleted = $this->modelTask->delete($taskId);
        if ($deleted) {
            echo "Tarea eliminada correctamente.";
        } else {
            echo "No se pudo eliminar la tarea.";
        }
        header('Location: ' . WEB_ROOT . '/index');
        exit;
    }
}

}