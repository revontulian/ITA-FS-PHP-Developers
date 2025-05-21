<?php

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