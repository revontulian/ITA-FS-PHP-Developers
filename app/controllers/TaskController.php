<?php

class TaskController extends ApplicationController
{
    private TaskModel $taskModel;

    public function init(): void
    {
        parent::init();
        $this->taskModel = new TaskModel();
    }

    public function mainPageAction(): void
{
    $this->requireLogin();
    $tasklistModel = new Tasklist();
    $currentUser = $this->getCurrentUser();
    $userId = $currentUser['id'] ?? null;

    if ($userId) {
        $tasklists = $tasklistModel->getTasklistsByUserId($userId);
    } else {
        $tasklists = [];
    }
    $this->view->tasklists = $tasklists;

    if (empty($tasklists)) {
        $this->view->noTasklists = true; 
        $this->view->tasks = [];
        $this->view->currentListId = null;
        return;
    } else {
        $this->view->noTasklists = false; 
    }

    $currentListId = $this->getCurrentList();
    $this->view->currentListId = $currentListId;


    $statusFilter = $_SESSION['taskStatus'] ?? $_GET['taskStatus'] ?? 'all';
    if (isset($_GET['taskStatus'])) {
        $_SESSION['taskStatus'] = $statusFilter;
    }
    $this->view->currentStatus = $statusFilter;

    

    if ($currentListId) {
        if ($statusFilter === 'all') {
            $this->view->tasks = $this->taskModel->getTasksByTasklistId($currentListId);
        } else {
            $taskStatus = TaskStatus::from($statusFilter);
            $this->view->tasks = $this->taskModel->filterStatus($taskStatus, $currentListId);
        }
    } else {
        $this->view->tasks = [];
        
    }
}

public function createAction(): void
{
    $this->requireLogin();
    $currentListId = $this->getCurrentList();

   
    if (!$currentListId) {
        $_SESSION['error'] = 'You must select a tasklist before creating tasks.';
        $this->redirect('/tasks/mainPage'); 
        return; 
    }
  

    $this->view->listid = $currentListId;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nameTask = $_POST['nameTask'] ?? '';
        $taskStatusStr = $_POST['taskStatus'] ?? 'pending';
        $description = $_POST['description'] ?? '';
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';

        $taskStatus = TaskStatus::from($taskStatusStr);
        $startDateObj = new DateTimeImmutable($startDate);
        $endDateObj = !empty($endDate) ? new DateTimeImmutable($endDate) : null;

        if (empty($nameTask) || empty($startDate)) {
            $this->view->error = "All fields are required."; 
            return;
        }
        
        if ($endDateObj && $endDateObj < $startDateObj) {
            $this->view->error = "End date cannot be before start date.";
            return;
        }

        $success = $this->taskModel->addTask(
            $nameTask,
            $taskStatus,
            $startDateObj,
            $description,
            $endDateObj,
            $currentListId
        );

        if ($success) {
            $_SESSION['success'] = "Task created successfully";
            $this->redirect('/tasks/mainPage');
        } else {
          
            $this->view->error = "Error creating task. 
                                It might already exist or there was a data issue.";
        }
    }
}



    public function deleteAction(): void
    {
        $this->requireLogin();
        $id = $this->_getParam('id');

        $success = $this->taskModel->deleteTaskId($id);
        if ($success) {
            $this->redirect('/tasks/mainPage');
        } else {
            $this->view->error = "Task could not be deleted.";
        }
    }

    public function updateAction(): void
    {
        $this->requireLogin();
        $id = $this->_getParam('id');

        if (!$id) {
            $this->view->error = "Task ID not provided.";
            return;
        }

        $task = $this->taskModel->getTaskById($id);
        if (!$task) {
            $this->view->error = "Task not found.";
            return;
        }

        $this->view->task = $task;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nameTask = $_POST['nameTask'] ?? '';
            $taskStatusStr = $_POST['taskStatus'] ?? 'pending';
            $description = $_POST['description'] ?? '';
            $startDate = $_POST['startDate'] ?? '';
            $endDate = $_POST['endDate'] ?? null;

        $taskStatus = TaskStatus::from($taskStatusStr);
        $startDateObj = new DateTimeImmutable($startDate);
        $endDateObj = $endDate ? new DateTimeImmutable($endDate) : null;

        if (empty($nameTask) || empty($startDate)) {
            $this->view->error = "All fields are required.";
            return;
        }

        $newTask = [
            'nameTask' => $nameTask,
            'taskStatus' => $taskStatus->value,
            'startDate' => $startDateObj->format('Y-m-d'),
            'description' => $description,
            'endDate' => $endDateObj ? $endDateObj->format('Y-m-d') : null,
        ];

            $success = $this->taskModel->updateTaskid($id, $newTask);

            if ($success) {
                $this->redirect('/tasks/mainPage');
            } else {
                $this->view->error = "Error updating task.";
            }
        }
    }
   public function filterStatusAction(): void
    {
         $this->requireLogin();
        $statusFilter = $_GET['taskStatus'] ?? 'all';
        $_SESSION['taskStatus'] = $statusFilter; 
        $this->redirect('/tasks/mainPage');
    }


}






