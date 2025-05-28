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
        $refresh = false;
        $this->requireLogin();
        $tasks = $this->taskModel->getAll();
        $this->view->tasks = $tasks;

        $tasklistModel = new Tasklist();
        $currentUser = $this->getCurrentUser();
        $userId = $currentUser['id'] ?? null;

        if ($userId) {
            $tasklists = $tasklistModel->getTasklistsByUserId($userId);
        } else {
            $tasklists = [];
        }

        $this->view->tasklists = $tasklists;
    }

    public function createAction(): void
    {
        $this->requireLogin();
        $this->view->tasks = $this->taskModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nameTask = $_POST['nameTask'] ?? '';
            $taskStatusStr = $_POST['taskStatus'] ?? 'pending';
            $description = $_POST['description'] ?? '';
            $startDate = $_POST['startDate'] ?? '';
            $endDate = $_POST['endDate'] ?? '';

            $taskStatus = TaskStatus::from($taskStatusStr);
            $startDateObj = new DateTimeImmutable($startDate);
            $endDateObj = $endDate ? new DateTimeImmutable($endDate) : $startDateObj;

            if (empty($nameTask) || empty($startDate)) {
                $this->view->error = "All fields are required.";
                return;
            }

            $success = $this->taskModel->addTask(
                $nameTask,
                $taskStatus,
                $startDateObj,
                $description,
                $endDateObj
            );


            if ($success) {
                $_SESSION['success'] = "Tarea creada exitosamente";
                //header('Location: ' . WEB_ROOT . '/tasks/mainPage');
                // Redirige a la vista de tareas
                exit();
                $this->redirect('/tasks/mainPage');
            } else {
                $this->view->error = "Task already exists.";
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
            $endDate = $_POST['endDate'] ?? '';

            $taskStatus = TaskStatus::from($taskStatusStr);
            $startDateObj = new DateTimeImmutable($startDate);
            $endDateObj = $endDate ? new DateTimeImmutable($endDate) : $startDateObj;

            if (empty($nameTask) || empty($startDate)) {
                $this->view->error = "All fields are required.";
                return;
            }

            $newTask = [
                'nameTask' => $nameTask,
                'taskStatus' => $taskStatus->value,
                'startDate' => $startDateObj->format('Y-m-d'),
                'description' => $description,
                'endDate' => $endDateObj->format('Y-m-d'),
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

        // Get filter status from GET parameters
        $taskStatus = $_GET['taskStatus'] ?? 'all';

        // Get filtered tasks
        if ($taskStatus === 'all') {
            $tasks = $this->taskModel->getAll();
        } else {
            try {
                $taskStatusEnum = TaskStatus::from($taskStatus);
                $tasks = $this->taskModel->filterStatus($taskStatusEnum);
            } catch (ValueError $e) {
                $tasks = $this->taskModel->getAll();
            }
        }

        // Set view variables
        $this->view->tasks = $tasks;
        $this->view->currentStatus = $taskStatus;

        // En lugar de hacer redirect, renderizamos la vista mainPage directamente
        $this->view->render('task/mainPage.phtml');
    }
}
