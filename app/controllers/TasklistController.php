<?php

declare(strict_types=1);
require_once ROOT_PATH . '/lib/JsonCRUD.php';

class TasklistController extends ApplicationController
{
    public function createAction(string $tasklistName, string $userId): void
    {
        $userId = $this->getCurrentUser['id'] ?? null;
        $tasklistModel = new Tasklist();
        $jsonManager = new JsonCRUD('tasklist.json');
        $tasklists = $jsonManager->read();
        $tasklistName = $_POST['name'] ?? null;
        if ($tasklistName) {
            // Check if the tasklist name already exists
            if (!$tasklistModel->verifyTaskListDoesNotExist($tasklistName)) {
                echo "Tasklist with this name already exists.";
                return;
            } else {
                // If it doesn't exist, proceed to create a new tasklist
                $tasklistModel->createTasklist($tasklistName, $userId);
                header('Location: ' . $this->view->baseUrl()  . '/index.php?controller=Tasklist&action=index');
            }
        } else {
            echo "Tasklist name is required.";
        }
    }

    public function editAction(): void
    {
        $tasklistId = $_GET['id'] ?? null;
        $tasklistModel = new Tasklist();
        $jsonManager = new JsonCRUD('tasklist.json');
        $tasklists = $tasklistModel->getTasklistsById($tasklistId);
        if ($tasklistId && $tasklists) {
            $jsonManager->update($tasklistId, $_POST);
            header('Location: ' . $this->view->baseUrl()  . '/index.php?controller=Tasklist&action=index');
        } else {
            echo "Tasklist not found.";
        }
    }

    public function deleteAction(): void
    {
        $tasklistId = $_GET['id'] ?? null;
        if ($tasklistId) {
            $tasklistModel = new Tasklist();
            $jsonManager = new JsonCRUD('tasklist.json');
            $tasklists = $tasklistModel->getTasklistsById($tasklistId);
            foreach ($tasklists as $key => $tasklist) {
                if ($tasklist['id'] === $tasklistId) {
                    unset($tasklists[$key]);
                    $jsonManager->delete($tasklistId);
                    break;
                }
            }
        }
        header('Location: ' . $this->view->baseUrl()  . '/index.php?controller=Tasklist&action=index');
    }
}
