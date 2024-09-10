<?php 

class Task extends Model {
    protected array $listTask;
    protected String $dataBaseRoute = ROOT_PATH . "\app\dataBase\data.json";

    public function __construct() {
        $this->loadTask();
    }
    
    private function loadTasks(){
    if (!file_exists($this->dataBaseRoute)) {
      file_put_contents($this->dataBaseRoute, json_encode([]));
    }

    $jsonString = file_get_contents($this->dataBaseRoute);
    $this->listTask = json_decode($jsonString, false) ?? [];
    }
}