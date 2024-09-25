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
    public function update($id, $data){
        
            foreach ($this->allTask as $key => $task) {
                if ($task['id'] == $id) {
                    // Actualizar los datos de la tarea
                    $this->allTask[$key] = array_merge($task, $data);
                    
                    // Guardar los cambios en el archivo
                    file_put_contents($this->dbRoute, json_encode($this->allTask, JSON_PRETTY_PRINT));
                    
                    return true;  // Retornar verdadero si se ha actualizado con éxito
                }
            }
            return false;  // Retornar falso si no se encuentra el ID
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
    
}