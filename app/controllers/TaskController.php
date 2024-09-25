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
    public function updateIdAction() {
        // Si el formulario de ID es enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['taskId'];
    
            // Verifica que el ID exista en las tareas
            $task = $this->modelTask->fetchId($taskId); // Método para obtener la tarea por ID
    
            if ($task) {
                // Si la tarea existe, redirige a la vista de actualización
                header('Location: ' . WEB_ROOT . '/update/' . $taskId);
                exit;
            } else {
                // Si no existe, muestra un mensaje de error
                $this->view->error = "La tarea con el ID $taskId no existe.";
            }
        }
    
        // Muestra la vista para pedir el ID
        //$this->view->render('task/updateId.phtml');
    }
    public function updateAction($taskId) {
        // Obtener la tarea por ID
        $task = $this->modelTask->fetchId($taskId);
    
        if (!$task) {
            // Si la tarea no existe, redirige o muestra un error
            $this->view->error = "La tarea con el ID $taskId no existe.";
            header('Location: ' . WEB_ROOT . '/updateId');
            exit;
        }
    
        // Si el formulario de actualización es enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedData = [
                'title' => $_POST['title'],
                'status' => $_POST['status'],
                'startTime' => $_POST['startTime'],
                'deadLine' => $_POST['deadLine'],
                'user' => $_POST['user'],
            ];
    
            // Actualizar la tarea
            $this->modelTask->update($taskId, $updatedData);
    
            // Redirigir al index después de la actualización
            header('Location: ' . WEB_ROOT . '/index');
            exit;
        }
    
        // Pasar los datos de la tarea a la vista
        $this->view->task = $task;
        $this->view->render('task/update.phtml');
    }
    
    

    public function deleteAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          
            $taskId = $_POST['task_id'];
            $task = $this->modelTask->fetchId($taskId);  
    
            if ($task) {
                
                $deleted = $this->modelTask->delete($taskId); 
            
                if ($deleted) {
                    header('Location: ' . WEB_ROOT . '/index');
                    exit;
                } else {
                    echo "Error al eliminar la tarea.";
                }
            } else {
                echo "No existe una tarea con el ID proporcionado.";
            }
        }
    
        
    }
    
}

