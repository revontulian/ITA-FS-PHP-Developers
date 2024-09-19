<?php

class ModelCRUD extends Model {
    protected array $alltask;
    protected string $dbRoute = ROOT_PATH . '\app\dataBase\data.json'; 
    
    public function __construct() {
        $this->loadTask();
    }
    public function loadTask(){
        $this->alltask = json_decode(file_get_contents($dbRoute), true) ?? [];
    }
    public function create(array $data){
        $id = count($this->alltask)+ 1;
        $task = [
            'id' => $id,
            'title' => $data['title'],
            'status' => 'pending',
            'startTime' => $data['starTime'],
            'deadLine' => $data['deadLine'],
        ];
        $this->allTask[] = $task;
        file_put_contents($this->dataRoute, json_encode($this->allTasks, JSON_PRETTY_PRINT));
    }
    
}