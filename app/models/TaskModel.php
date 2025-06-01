<?php

declare(strict_types=1);
require_once __DIR__ . '/TaskStatus.php';


class TaskModel {
    private JsonCRUD $crud;

    public function __construct() {

     $this->crud = new JsonCRUD('task.json');
        
    }

    public function getAll(): array {
        return $this->crud->read();
    }

    public function addTask(
        string $nameTask, 
        TaskStatus $taskStatus, 
        DateTimeImmutable $startTime,
        string $description, 
        ?DateTimeImmutable $endDate = null,
        ?string $currentListId=null
    ): bool {

      

       return $this->crud->create([
            'nameTask' => $nameTask,
            'taskStatus' => $taskStatus->value,
            'startDate' => $startTime->format('Y-m-d'),
            'description' => $description,
            'endDate' => $endDate ? $endDate->format('Y-m-d') : null, 
            'tasklist_id' => $currentListId 
        ]);
        
    }

    public function view(): ?array {
        $crud = $this->getAll();
        foreach ($crud as $task) {
            return $task; 
            }
            return null;
    }

    public function getTaskbyId(string $id):?array {
      return $this->crud->read($id);
    }
    
    public function updateTaskid(string $id,array $newData):bool{
        $task =$this->crud->update($id, $newData);
        if ($task) {
            return true; 
        }
        return false;
    }

    public function deleteTaskId(string $id): bool {
        
        return $this->crud->delete($id);
    }
    
    public function filterStatus(TaskStatus $taskStatus, string $tasklistId): array { 
        $tasksInList = $this->getTasksByTasklistId($tasklistId);
        $filteredTasks = [];

        foreach ($tasksInList as $task) {
            if (($task['taskStatus'] ?? null) === $taskStatus->value) {
                $filteredTasks[] = $task;
            }
        }
        
        return $filteredTasks;
    }


    

       public function getTasksByTasklistId(string $tasklistId): array
    {
        $allTasks = $this->getAll();
        $tasksInList = [];
        
        foreach ($allTasks as $task) {
            if (($task['tasklist_id'] ?? null) === $tasklistId) {
                $tasksInList[] = $task;
            }
        }
        
        return $tasksInList;
    }

    public function deleteTasksForList(string $tasklistId){
        $allTasksForlist = $this->getTasksByTasklistId($tasklistId);
       
        foreach($allTasksForlist as $task){
             $this->crud->delete($task['id']);
        }
        
        
    }
    


    }

 
        

   
?>