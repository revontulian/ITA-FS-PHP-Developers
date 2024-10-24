<?php

class ModelTask extends Model {
    protected array $allTask;
    protected string $dbRoute = ROOT_PATH . '\app\dataBase\data.json'; 
    
    public function __construct() {
        $this->loadTask();
    }
    private function loadTask(){
        $this->allTask = json_decode(file_get_contents($this->dbRoute), true) ?? [];
    }
    public function create(array $data){
        $id = count($this->allTask)+ 1;
        $task = [
            'id' => $id,
            'title' => $data['title'],
            'status' => $data['status'],
            'starTime' => $data['starTime'],
            'deadLine' => $data['deadLine'],
            'user' => $data['user'],
        ];
        $this->allTask[] = $task;
        file_put_contents($this->dbRoute, json_encode($this->allTask, JSON_PRETTY_PRINT));
    }
    public function fetchAll(){
        return $this->allTask;
    }
    public function fetchId($id){
        foreach($this->allTask as $task){
            if($task['id']== $id){
                return $task;
            }
        }
        return null;
    }
    public function update($data){
        
        foreach($this->allTask as &$task){
            
              
            if($task['id'] == $data['id']){
                
                $task['title'] = $data['title'];
                $task['status'] = $data['status'];
                $task['starTime'] = $data['starTime'];
                $task['deadLine'] = $data['deadLine'];
                $task['user'] = $data['user'];
                break;
                
            }
        }
        
        file_put_contents($this->dbRoute, json_encode($this->allTask, JSON_PRETTY_PRINT));
        
    }

        
    public function delete($id)
    {
        foreach ($this->allTask as $key => $task) {
            if ($task['id'] == $id) {
                unset($this->allTask[$key]);
                file_put_contents($this->dbRoute, json_encode($this->allTask, JSON_PRETTY_PRINT));
                return true; 
            }
        }
        return false;  
    }
    public function fetchIdBool($id)
    {
        foreach ($this->allTask as $key => $task) {
            if ($task['id'] == $id) {
                return true; 
            }
        }
        return false;  
    }
}