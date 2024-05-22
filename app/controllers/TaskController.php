<?php

require_once ROOT_PATH . '/app/models/TaskModel.class.php';

class TaskController extends Controller
{


  public function __construct(private $taskModel = new Task, private array $actionsToRedirect = [])
  {
  }

  public function indexAction()
  {
    $allTasks = $this->taskModel->fetchAll();

    usort($allTasks, [$this, 'compareCreatedTimeDesc']);

    $this->view->allTasks = $allTasks;
  }

  public function searchAction()
  {
    if ($this->getRequest()->isPost()) {
      $term = $this->_getParam('search');
      $allTasks = $this->taskModel->search($term);
      $this->view->allTasks = $allTasks;
    } else {
      header('Location: /');
    }
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
        $this->actionsToRedirect[] = 'create';
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

      $newData = [
        'id' => $taskId,
        'name' => $name,
        'username' => $username,
        'completed_time' => $completed_time,
        'status' => $status
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

  public function deleteAction()
  {
    echo "task delete";
    if ($this->getRequest()->isPost()) {
      $id = $this->_getParam('id');
       

    //$taskDetail = $this->taskModel->fetchOne($taskId);
    //$this->view->taskDetail = $taskDetail;

    if ($this->taskModel->delete($id)) {
      echo "Tarea eliminada correctamente.";
      header('Location:/');
      /*
      TO DO: xq ovarios no funciona aqui??
      $this->actionsToRedirect[] = 'update'; 
     */
    } else {
      echo "Ha habido un error.";
    }
    exit();
  }
}

  public function afterFilters()
  {
    if (in_array($this->_action, $this->actionsToRedirect)) {
      header('Location: /');
      exit();
    }
  }

  function compareCreatedTimeDesc($task1, $task2)
  {
    return strtotime($task2->create_time) - strtotime($task1->create_time);
  }
}
