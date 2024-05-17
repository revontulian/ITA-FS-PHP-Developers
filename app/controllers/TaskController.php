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
  public function afterFilters()
  {
    parent::afterFilters();

    if (in_array($this->_action, $this->actionsToRedirect)) {
      header('Location: /');
      exit();
    }
  }
}

//TODO Crear una redirección al index con afterFilters (sabia sugerencia de Amanda)
