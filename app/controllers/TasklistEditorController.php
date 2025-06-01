<?php

declare(strict_types=1);

class TasklistEditorController extends ApplicationController
{
    private Tasklist $tasklistModel;
    private JsonCRUD $jsonManager;

    public function init()
    {
        parent::init();
        $this->tasklistModel = new Tasklist();
        $this->jsonManager = new JsonCRUD('tasklists.json');
    }

    public function editAction()
    {
        $this->requireLogin();
        
        $tasklistId = $this->_getParam('id');
        

        if (!$tasklistId) {
            $this->redirectWithError('/tasks/mainPage', 'Tasklist ID not provided.');
            return;
        }

        // Obtain the tasklist from the JSON file
        $tasklist = $this->jsonManager->read($tasklistId);


        if (!$tasklist) {
            $this->redirectWithError('/tasks/mainPage', 'Tasklist not found.');
            return;
        }

        // Verify that the current user is the owner of the tasklist
        $currentUser = $this->getCurrentUser();
        if ($tasklist['user_id'] !== $currentUser['id']) {
            $this->redirectWithError('/tasks/mainPage', 'Access denied.');
            return;
        }
        
        
        $this->view->tasklist = $tasklist;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newName = $this->sanitizeInput($_POST['name'] ?? '');
            throw new Exception('Tasklist to be changed to: ' . $newName);
            
            if (empty($newName)) {
                $this->view->errorMessage = 'Tasklist name is required.';
                return;
            }

            // Verifica che il nuovo nome non esista già
            if ($newName !== $tasklist['name'] && 
                !$this->tasklistModel->verifyTaskListDoesNotExist($newName, $currentUser['id'])) {
                $this->view->errorMessage = 'A tasklist with this name already exists.';
                return;
            }

            $success = $this->jsonManager->update($tasklistId, ['name' => $newName]);
            $this->view->tasklist = $tasklist;
            
            if ($success) {
                $this->redirectWithSuccess('/tasks/mainPage', 'Tasklist updated successfully!');
            } else {
                $this->view->errorMessage = 'Error updating tasklist.';
            }
        }
    }
        
        
    
}
