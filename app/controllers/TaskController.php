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
    public function updateAction()
{
    // Paso 1: Mostrar un formulario para ingresar el ID si no se ha enviado aún
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
        $taskId = $_POST['task_id'];
        
        // Paso 2: Buscar la tarea en la base de datos
        $task = $this->modelTask->fetchId($taskId);
        
        if ($task) {
            // Mostrar el formulario de edición con los datos de la tarea
            $this->view->task = $task;  // Pasar la tarea a la vista para mostrarla
            $this->view->render('task/update');
        } else {
            // Mostrar mensaje de error si la tarea no existe
            echo "No existe una tarea con el ID proporcionado.";
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
        // Paso 4: Guardar los datos editados
        $taskId = $_POST['id'];
        $data = [
            'title' => $_POST['title'],
            'status' => $_POST['status'],
            'startTime' => $_POST['startTime'],
            'deadLine' => $_POST['deadLine'],
            'user' => $_POST['user'],
        ];
        
        $updated = $this->modelTask->update($taskId, $data);
        
        if ($updated) {
            // Redirigir al índice si se ha editado con éxito
            header('Location: ' . WEB_ROOT . '/index?message=TaskUpdated');
            exit;
        } else {
            // Si hubo un error al guardar los datos
            echo "Error al actualizar la tarea.";
        }
    } else {
        // Mostrar el formulario de ID si no se ha hecho aún
        $this->view->render('task/updateId');
    }
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

