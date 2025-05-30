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
        $userId = $this->getCurrentUser()['id'] ?? null;
        $tasklistName = $_POST['tasklist_name'] ?? null;

        if (!$this->tasklistModel->verifyTaskListDoesNotExist($tasklistName)) {
            throw new Exception("Tasklist with this name already exists.");
            return;
        } else {
            // If it doesn't exist, proceed to create a new tasklist
            $this->jsonManager->create([
                'name' => $tasklistName,
                'user_id' => $userId,
            ]);
            $this->view->tasklists = $this->tasklistModel->getTasklistsByUserId($userId);
            header('Location: ' . $this->view->baseUrl());
        }
    }

    public function selectAction(): void
    {
        $_SESSION['tasklistId'] = $_GET['id'] ?? null;
        header('Location: ' . $this->view->baseUrl());
    }

    public function editAction(): void
    {
        throw new Exception("This action is not implemented in TasklistController. Use TasklistEditorController instead.");
        $tasklistId = $this->_getParam('id');
        $userId = $this->getCurrentUser()['id'] ?? null;
        $tasklists = $this->tasklistModel->getTasklistsByUserId($userId);

        //Continuar aquí
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

        foreach ($userTasklists as $key => $tasklist) {
            if ($tasklist['id'] === $tasklistId) {
                unset($tasklists[$key]);
                $this->jsonManager->delete($tasklistId);
                break;
            }
        }
        header('Location: ' . $this->view->baseUrl());
    }
}
