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
}