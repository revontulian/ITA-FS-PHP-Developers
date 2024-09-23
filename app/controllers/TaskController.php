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
        if($_SERVER["REQUEST_METHOD"]=== "POST"){
            
            $title = $_POST['title'];
            $status = $_POST['status'];
            $startTime = $_POST['starTime'];
            $deadLine = $_POST['deadLine'];
            $user = $_POST['user'];

            $data = [
                'title' => $title,
                'status' => $status,
                'startTime' => $starTime,
                'deadLine' => $deadLine,
                'user' => $user,
            ];
        $this->modelTask->create($data);
        header('Location: ' . WEB_ROOT . '/index');
        exit;
        }
    }
    public function updateAction(){

        $url = explode('/',$SERVER['REQUEST_URI']);
        $taskId = end($url);
        if($_SERVER["REQUEST_METHOD"]=== "POST"){
            
            $id = $_POST['id'];
            $title = $_POST['title'];
            $status = $_POST['status'];
            $startTime = $_POST['starTime'];
            $deadLine = $_POST['deadLine'];
            $user = $_POST['user'];

            $data = [
                'id' =>$id,
                'title' => $title,
                'status' => $status,
                'startTime' => $starTime,
                'deadLine' => $deadLine,
                'user' => $user,
            ];
        $this->modelTask->update($data);
        header('Location: ' . WEB_ROOT . '/index');
        exit;
        } else {
        $taskupdate = $this->modelTask->fetchId($taskId);
        if(!$taskupdate){
            die('tarea no encontrada');
        }
        $this->view->task = $taskupdate;
        }
    }
    public function deleteAction(){
        $url = explode('/',$SERVER['REQUEST_URI']);
        $taskId = end($url);
        $this->modelTask->delete($taskId);
        header('Location: ' . WEB_ROOT . '/index');
        exit;
    }
}