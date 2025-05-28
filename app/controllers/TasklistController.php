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
            header('Location: ' . $this->view->baseUrl());

        }
    }

    public function editAction(): void
    {
        $tasklistId = $_GET['id'] ?? null;
        $tasklists = $this->tasklistModel->getTasklistsById($tasklistId);
        if ($tasklistId && $tasklists) {
            $this->jsonManager->update($tasklistId, $_POST);
            header('Location: ' . $this->view->baseUrl()  . '/index.php?controller=Tasklist&action=index');
        } else {
            echo "Tasklist not found.";
        }
    }

    public function deleteAction(): void
    {
        $tasklistId = $_GET['id'] ?? null;
        if ($tasklistId) {
            $tasklists = $this->tasklistModel->getTasklistsById($tasklistId);
            foreach ($tasklists as $key => $tasklist) {
                if ($tasklist['id'] === $tasklistId) {
                    unset($tasklists[$key]);
                    $this->jsonManager->delete($tasklistId);
                    break;
                }
            }
        }
        header('Location: ' . $this->view->baseUrl()  . '/index.php?controller=Tasklist&action=index');
    }
}
