<?php

declare(strict_types=1);
require_once __DIR__ . '/TaskStatus.php';


class TaskModel {
    private string $file;

    public function __construct() {
        $this->file = __DIR__ . '/../../lib/data/task.json';

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
            chmod($this->file, 0666); // Permisos de lectura/escritura
        }
    }

    public function getAll(): array {
        if (!file_exists($this->file)) {
            error_log("Archivo no encontrado: " . $this->file);
            return [];
        }
        $json_data = file_get_contents($this->file);
        if ($json_data === false) {
            error_log("Error al leer el archivo JSON");
            return [];
        }
        $tasks = json_decode($json_data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error al decodificar JSON: " . json_last_error_msg());
            return [];
        }
        

        if(!is_array($tasks)) {
            error_log("El contenido del archivo no es un array");
            return [];
        }

        return is_array($tasks) ? $tasks : [];
}

    public function addTask(
        string $nameTask, 
        TaskStatus $taskStatus, 
        DateTimeImmutable $startTime,
        string $description, 
        DateTimeImmutable $endDate,
        int $provity =-1
        ): bool {
        $tasks = $this->getAll();

        foreach($tasks as $task) {
            if($task['nameTask'] === $nameTask) {

    declare(strict_types=1);

   enum TaskStatus: string {
        case PENDING = 'pending';
        case IN_PROGRESS = 'in_progress';
        case COMPLETED = 'completed';
    }

    Class TaskModel  {
        
    private string $file;

    public function __construct(string $file) {
        
        $this->file = $file;
    }

    public function getAll(): array {

         if (!file_exists($this->file)) {
            return [];
        }
        $json = file_get_contents($this->file);
        $task = json_decode($json, true);
        return is_array($tasks) ? $tasks : [];
    }

    public function addTask( string $nameTask, TaskStatus $TaskStatus, DateTimeImmutable  $startTime,
     string $description,  DateTimeImmutable  $endDate):bool{

        //filtar si exite 
        $tasks = $this->getAll();


        foreach($tasks as $task){
            if($task['nameTask'] === $nameTask){
                return false;
            }
        }


        $newtasks = [
            'id' => uniqid(),
            'nameTask' => $nameTask,
            'taskStatus' => $taskStatus->value,
            'startDate' => $startTime->format('Y-m-d'),
            'description' => $description,
            'endDate' => $endDate->format('Y-m-d'),
            'provity' => $provity ];

        $tasks[] = $newtasks;
        
        file_put_contents($this->file, json_encode($tasks, JSON_PRETTY_PRINT));
        return true;
    }

    public function viewTask(string $id){
        $tasks = $this->getAll();// Obtener todas las tareas
        $viewTask = array_filter($tasks, fn($task) => $task['id'] === $id);
        if(count($viewstack) === 1){
            return reset($viewstack);
        }

        return $filtered ? reset($filtered) : null;

    }
    // task valid
    public function updateTaskid($task){

        if(deleteTaskId($task["id"])) {
            return addTask($task); 
        }
        return false; 

    }

    public function deleteTaskId(string $id): bool {
        $tasks = $this->getAll();// Obtener todas las tareas
        $newTasks = array_filter($tasks, fn($task) => $task['id'] !== $id);

        if (count($tasks) === count($newTasks)) {
            return false; // No se encontró la tarea
        }

        file_put_contents($this->file, json_encode(array_values($newTasks), JSON_PRETTY_PRINT));
        return true;
    }
    
    public function deleteTaskByName(string $nameTask): bool {
        $tasks = $this->getAll();// Obtener todas las tareas
        $newTasks = array_filter($tasks, fn($task) => $task['nameTask'] !== $nameTask);

        if (count($tasks) === count($newTasks)) {
            return false; // No se encontró la tarea
        }

        file_put_contents($this->file, json_encode(array_values($newTasks), JSON_PRETTY_PRINT));
        return true;
    }
}


          $newtasks= [
            'nameTask' => $nameTask,
            'TaskStatus' => $status->value,// the enum 
            'startDate' => $startTime->format('Y-m-d'),// date
            'description' => $description,
            'endDate' => $endDate->format('Y-m-d')// data
        ];

        $taks[] =$newtasks;// lo almacenada en la array
        
        //guarda en el archibo json
        file_put_contents($this->file, json_encode($taks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return true;

     }

    }


   


?>