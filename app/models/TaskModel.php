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
        DateTimeImmutable $endDate
        ): bool {
        $crud = $this->getAll();
       

        foreach($crud as $task) {
            if($task['nameTask'] === $nameTask) {
                return false;
            }
        }

        $this-> crud->create( [
            'nameTask' => $nameTask,
            'taskStatus' => $taskStatus->value,
            'startDate' => $startTime->format('Y-m-d'),
            'description' => $description,
            'endDate' => $endDate->format('Y-m-d') ]);
        return true;

       
        
        
    }

    public function view(): ?array {
        $crud = $this->getAll();
        foreach ($crud as $task) {
            return $task; 
            }
    }

    public function getTaskbyId(string $id):?array {
      return $this->crud->read($id);
    }

       
    /**
     * Update user data.
     * @param string $id
     * @param array $newData (nameTask, taskStatus, startDate, description, endDate )
     * @return bool
     */
  

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
    public function filterStatus(TaskStatus $taskStatus ) { 
        
        return $tasks= $this->crud->search('taskStatus', $taskStatus->value);
    
        
        
    }

}
?>