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
        foreach($this->allTask as $task){
            if($task['id'] == $data['id']){
                $data['title'] = $task['title'];
                $data['status'] = $task['status'];
                $data['starTime'] = $task['starTime'];
                $data['deadLine'] = $task['deadLine'];
                $data['user'] = $task['user'];
            }
        }
        file_put_contents($this->dbRoute, json_encode($this->allTasks, JSON_PRETTY_PRINT));
    }
    public function delete($data){
        $delete = false;
        foreach($this->allTask as $task){
            if($task['id'] == $data['id']){
                unset($this->allTask[$task]);
                $delete = true;   
            }
        }
        if ($delete){
        file_put_contents($this->dbRoute, json_encode($this->allTasks, JSON_PRETTY_PRINT));
        }
        return $delete;
    }
}