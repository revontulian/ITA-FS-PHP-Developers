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

            $taskStatus = TaskStatus::from($taskStatusStr);

            $startDateObj = new DateTimeImmutable($startDate);
            $endDateObj = $endDate ? new DateTimeImmutable($endDate) : $startDateObj;

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
                header('Location: ' . WEB_ROOT . '/tasks/view');
                // Redirige a la vista de tareas
                exit();
            } else {
                $this->view->error = "La tarea ya existe.";
            }
        }
    }
    
    public function deleteAction(): void{
        
        $id = $this->_getParam('id');
        $this->view->error = '';

        $success = $this->taskModel->deleteTaskId($id);
         if ($success) {
                $_SESSION['success'] = "Tarea eliminado correctamente";
                header('Location: ' . WEB_ROOT . '/tasks/view');
                exit();
            } else {
                $this->view->error = "no ha sido eleminada";
            }
            

    }
    
    public function updateAction(): void
    {
       echo $id = $this->_getParam('id');
        if (!$id) {
            echo $this->view->error = "ID de tarea no proporcionado.";
            return;
        }
        $task = $this->taskModel->getTaskById($id);
        if (!$task) {
            echo $this->view->error = "Tarea no encontrada.";
           
            return;
        }
        $this->view->task = $task;
        
        $this->view->error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nameTask = $_POST['nameTask'] ?? '';
            $taskStatusStr = $_POST['taskStatus'] ?? 'pending';
            $description = $_POST['description'] ?? '';
            $startDate = $_POST['startDate'] ?? '';
            $endDate = $_POST['endDate'] ?? '';
            $provity = (int)($_POST['provity'] ?? 1);

            $taskStatus = TaskStatus::from($taskStatusStr);
            $startDateObj = new DateTimeImmutable($startDate);
            $endDateObj = $endDate ? new DateTimeImmutable($endDate) : $startDateObj;

            if (empty($nameTask) || empty($startDate)) {
                $this->view->error = "Todos los campos son obligatorios.";
                return;
            }
          
            $newTask = [
                'nameTask' => $nameTask,
                'taskStatus' => $taskStatus->value,
                'startDate' => $startDateObj->format('Y-m-d'),
                'description' => $description,
                'endDate' => $endDateObj->format('Y-m-d'),
                'provity' => $provity

            ];
            $success = $this->taskModel->updateTaskid($id, $newTask);

            if ($success) {
                echo $_SESSION['success'] = "Tarea actualizada exitosamente";
                header('Location: ' . WEB_ROOT . '/tasks/view');
                exit();
            } else {
                 echo $this->view->error = "Error al actualizar la tarea.";
            }

        }
    }
    
}
?>