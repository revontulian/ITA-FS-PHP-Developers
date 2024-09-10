<?php 

class Task extends Model {
    protected array $listTask;
    protected String $dataBaseRoute = ROOT_PATH . "\app\dataBase\data.json";

    public function __construct() {
        $this->loadTask();
    }
    
    private function loadTask() {
        
    }
}