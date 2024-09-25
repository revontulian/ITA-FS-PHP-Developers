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

        $url = explode('/',$_SERVER['REQUEST_URI']);
        $taskId = end($url);  
        
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
    public function deleteAction()
    {
        // Verificar si se ha enviado un formulario con el ID para eliminar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener el ID del formulario
            $taskId = $_POST['task_id'];
    
            // Validar si existe la tarea en la base de datos
            $task = $this->modelTask->fetchId($taskId);  // Suponiendo que el modelo tiene un método `find` para buscar por ID
    
            if ($task) {
                // Si la tarea existe, proceder a eliminarla
                $deleted = $this->modelTask->delete($taskId);  // Método delete devuelve true si se elimina con éxito
    
                if ($deleted) {
                    // Redireccionar o mostrar mensaje de éxito
                    header('Location: ' . WEB_ROOT . '/index');
                    exit;
                } else {
                    // Si hubo un error al eliminar
                    echo "Error al eliminar la tarea.";
                }
            } else {
                // Mostrar mensaje de error si la tarea no existe
                echo "No existe una tarea con el ID proporcionado.";
            }
        }
    
        
    }
    
}

