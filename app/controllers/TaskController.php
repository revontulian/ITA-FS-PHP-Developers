<?php

class TaskController extends Controller
{
    private TaskModel $taskModel; 

    public function init(): void 
    {
        parent::init(); // Llama al init() del controlador padre
        $this->taskModel = new TaskModel();

    }
    public function viewAction(): void
    {
        $tasks = $this->taskModel->getAll();
        $this->view->tasks = $tasks;
    }

    

    public function createAction(): void
    {
        $this->view->tasks = $this->taskModel->getAll();
        $this->view->error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nameTask = $_POST['nameTask'] ?? '';
            $taskStatusStr = $_POST['taskStatus'] ?? 'pending';
            $description = $_POST['description'] ?? '';
            $startDate = $_POST['startDate'] ?? '';
            $endDate = $_POST['endDate'] ?? '';
            $provity = (int)($_POST['provity'] ?? 1);

            // Convierte el string a enum TaskStatus
            $taskStatus = TaskStatus::from($taskStatusStr);

            // Asegúrate de crear los objetos DateTimeImmutable correctamente
            $startDateObj = new DateTimeImmutable($startDate);
            $endDateObj = $endDate ? new DateTimeImmutable($endDate) : $startDateObj;

            // Validación de campos obligatorios
            if (empty($nameTask) || empty($startDate)) {
                $this->view->error = "Todos los campos son obligatorios.";
                return;
            }

            $success = $this->taskModel->addTask(
                $nameTask,
                $taskStatus,
                $startDateObj,
                $description,
                $endDateObj,
                $provity
            );

            if ($success) {
                $_SESSION['success'] = "Tarea creada exitosamente";
                header('Location: ' . WEB_ROOT . '/tasks/create');
                exit();
            } else {
                $this->view->error = "La tarea ya existe.";
            }
        }
    }
    public function deleteAction(string $id): void{
        $this->view->tasks = $this->taskModel->getAll();
        $this->view->error = '';
        $success = $this->taskModel->deleteTaskId();

         if ($success) {
                $_SESSION['success'] = "Tarea eliminado correctamente";
                header('Location: ' . WEB_ROOT . '/tasks/delate');
                exit();
            } else {
                $this->view->error = "no ha sido eleminada";
            }





      

    }
    
}
?>