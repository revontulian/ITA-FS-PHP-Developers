<?php

    class  TaskController extends Controller{

        public function checkTask(): void{

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                //Verificar que sea una petición POST
                echo "Metodo no permitido";
                exit(); 
            }

            function test_input($data): string {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
            }  


             // campos obligatorios
            if(empty($_POST["nameTask"])|| empty("TaskStatus")){
            $erroObligation = "Campos obligatorios";
            exit();

            }
                // NO HACE FALTA UN IF FILTRO PORQUE EL PRIMER IF YA LO HACE
                $nameTask = test_input($_POST["nameTask"]);
                $taskStatus = TaskStatus::from(test_input($_POST["status"]));;
                $description = test_input($_POST["description"]);
                $startDate = new DateTimeImmutable(test_input($_POST["startDate"]));
                $endDate = new DateTimeImmutable(test_input($_POST["endDate"]));


            
            //SAVE THE INFORMATION USER.JSON
            $taskModel= new TaskModel("task.json");
            $task = $taskModel->addTask($nameTask, $taskStatus, $startDate, $description, $endDate);
            header('Location: ' . WEB_ROOT . '/tasks?success=Nota creada correctamente');


        
       
        
    }
}
    
?>