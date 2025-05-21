<?php

    class  TodoListController extends Controller{

        public function checkTask(): void{

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                //Verificar que sea una petición POST
                echo json_encode(["Metodo no permitido"]);
                exit(); 


             // campos obligatorios
            if(empty($_POST["nameTask"])|| empty("TaskStatus")){
            $erroObligation = "Campos obligatorios";
            exit();

            }
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $nameTask = test_input($_POST["nameTask"]);
                $status = test_input($_POST["status"]);
                $description = test_input($_POST["description"]);
                $startDate = test_input($_POST["startDate"]);
                $endDate = test_input($_POST["endDate"]);

            }
            //SAVE THE INFORMATION USER.JSON
            $taskModel= new ModelUser();
            $task = $taskModel->addTask($nameTask, $taskStatus, $startDate, $description, $endDate);
            header('Location: ' . WEB_ROOT . '/tasks?success=Nota creada correctamente');


            function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;

            }
        
       
        
    }
}
    }
?>