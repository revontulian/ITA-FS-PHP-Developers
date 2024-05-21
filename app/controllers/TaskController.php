<?php

require_once ROOT_PATH . '/app/models/TaskModel.class.php';

class TaskController extends Controller
{


  public function __construct(private $taskModel = new Task)
  {
  }

  public function indexAction()
  {
    $allTasks = $this->taskModel->fetchAll();
    $this->view->allTasks = $allTasks;
  }

  public function createAction()
  {
    if ($this->getRequest()->isPost()) {
      $name = $this->_getParam('name');
      $username = $this->_getParam('username');

      if (empty($name) || empty($username)) {
        echo "El nom de la tasca i el nom d'usuari són necessaris.";
        return;
      }

      $taskData = [
        'name' => $name,
        'username' => $username
      ];

      if ($this->taskModel->save($taskData)) {
        header('Location: /');
      } else {
        echo "Error en desar la tasca.";
      }
    } else {
      $this->view;
    }
  }

  public function updateAction()
{
    if ($this->getRequest()->isPost()) {
        $taskId = $this->_getParam('id');
        $name = $this->_getParam('name');
        $username = $this->_getParam('username');
        $completed_time = $this->_getParam('completed_time');
        $status = $this->_getParam('status');

        if (empty($taskId)) {
            echo "ID de tarea no proporcionado.";
            return;
        }
    
        $taskDetail = $this->taskModel->fetchOne($taskId);
       
        if (!$taskDetail) {
            echo "Task no encontrada.";
            return;
        }

        $newData =[
        'id'=> $taskId,
        'name' => $name,
        'username' => $username,
        'completed_time' => $completed_time,
        'status'=> $status
      ];

       if ($this->taskModel->update($newData)) {
            echo "Tarea actualizada correctamente.";
            $this->actionsToRedirect[] = 'update';
        } else {
            echo "Error al actualizar la tarea.";
        }
       
        $this->view->taskDetail = $taskDetail;
    } else {
        $id = $this->_getParam('id'); 
        $taskDetail = $this->taskModel->fetchOne($id);
        $this->view->taskDetail = $taskDetail;
    }
}

/*
  public function updateAction()
{
    if ($this->getRequest()->isPost()) {
        // Si es POST, actualizar la tarea
        $taskId = $this->_getParam('id'); // Obtener el ID de la tarea del POST
        $name = $this->_getParam('name'); // Recuperar el nombre del formulario
        $username = $this->_getParam('username'); // Recuperar el nombre de usuario del formulario

        if (empty($name) || empty($username)) {
            echo "El nombre de la tarea y el nombre de usuario son necesarios.";
            return;
        }

        // Construir los datos para la actualización
        $taskData = [
            'id' => $taskId,
            'name' => $name,
            'username' => $username
        ];

        if ($this->taskModel->update($taskData)) {
            echo "Tarea actualizada con éxito.";
            header('Location: /'); // Redirige después de actualizar
            exit; // Asegúrate de terminar el script después de la redirección
        } else {
            echo "Error al actualizar la tarea.";
        }
    } else {
        // Si no es POST, mostrar la vista de edición
        $id = $this->_getParam('id'); // Obtener el ID de la URL
        $taskDetail = $this->taskModel->fetchOne($id);

        if ($taskDetail) {
            $this->view->taskDetail = $taskDetail; // Pasar la tarea a la vista
        } else {
            throw new Exception("Tarea no encontrada.");
        }
    }
}*/
}

//TODO Crear una redirección al index con afterFilters (sabia sugerencia de Amanda)
