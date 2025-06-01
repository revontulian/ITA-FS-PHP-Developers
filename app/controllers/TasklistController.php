<?php

declare(strict_types=1);
require_once ROOT_PATH . '/lib/JsonCRUD.php';

class TasklistController extends ApplicationController
{
    private Tasklist $tasklistModel;
    private JsonCRUD $jsonManager;

    public function __construct()
    {
        $this->tasklistModel = new Tasklist();
        $this->jsonManager = new JsonCRUD('tasklists.json');
    }

    public function createAction(): void
    {
        $this->requireLogin();
        $userId = $this->getCurrentUser()['id'] ?? null;
        $tasklistName = trim($_POST['tasklist_name'] ?? ''); 

        if (empty($tasklistName)) {
            $_SESSION['error'] = "Tasklist name cannot be empty.";
            header('Location: ' . $this->view->baseUrl() . '/tasks/mainPage'); 
            exit();
        }

        if (!$userId) {
            $_SESSION['error'] = "User not identified. Please login again.";
            header('Location: ' . $this->view->baseUrl() . '/user/login');
            exit();
        }

        if (!$this->tasklistModel->verifyTaskListDoesNotExist($tasklistName, $userId)) {
            throw new Exception("Tasklist with this name already exists for this user.");
        } else {
            $newListData = [
                'name' => $tasklistName,
                'user_id' => $userId,
            ];
            $createdTasklist = $this->jsonManager->create($newListData); 

            if ($createdTasklist && isset($createdTasklist['id'])) {
                $_SESSION['tasklistId'] = $createdTasklist['id']; 
                $_SESSION['success'] = "Tasklist '" . htmlspecialchars($tasklistName) . "' created and selected.";
            } else {
                $_SESSION['error'] = "Could not create the tasklist properly.";
            }
            
            header('Location: ' . $this->view->baseUrl() . '/tasks/mainPage');
            exit();
        }
    }

    public function selectAction(): void
    {
        $this->requireLogin();
        $_SESSION['tasklistId'] = $_GET['id'] ?? null;
        unset($_SESSION['taskStatus']); 
        header('Location: ' . $this->view->baseUrl() . '/tasks/mainPage');
        exit();
    }

    public function editAction(): void
    {
        throw new Exception("This action is not implemented in TasklistController. Use TasklistEditorController instead.");
        $tasklistId = $this->_getParam('id');
        $userId = $this->getCurrentUser()['id'] ?? null;
        $tasklists = $this->tasklistModel->getTasklistsByUserId($userId);

        if ($tasklistId && $tasklists) {
            $this->jsonManager->update($tasklistId, $_POST);
            header('Location: ' . $this->view->baseUrl()  . '/task/mainPage');
        } else {
            throw new Exception("Tasklist not found.");
        }
    }

    public function deleteAction(): void
    {
        $tasklistId = $_GET['id'] ?? null;
        $userId = $this->getCurrentUser()['id'] ?? null;
        $userTasklists = $this->tasklistModel->getTasklistsByUserId($userId);
        $taskModel = new TaskModel();


        foreach ($userTasklists as $key => $tasklist) {
            if ($tasklist['id'] === $tasklistId) {
                $taskModel->deleteTasksForList($tasklistId); 

                unset($tasklists[$key]);
                $this->jsonManager->delete($tasklistId);
                break;
            }
        }
        header('Location: ' . $this->view->baseUrl());
    }
}
