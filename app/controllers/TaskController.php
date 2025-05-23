<?php

    class TaskController extends Controller {
        private $taskModel;

        public function init(): void {
            parent::init();
            $this->taskModel = new TaskModel();
        }

        public function viewAction(): void {
            $tasks = $this->taskModel->getAll();
            $this->view->tasks = $tasks;
        }

        public function createAction(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nameTask = $_POST["nameTask"];
                $taskStatus = TaskStatus::from($_POST["taskStatus"] ?? 'pending');
                $startDate = new DateTimeImmutable($_POST["startDate"] ?? date('Y-m-d'));
                $description = $_POST["description"] ?? '';
                $endDate = new DateTimeImmutable($_POST["endDate"] ?? date('Y-m-d'));
                $provity = (int)($_POST["provity"] ?? -1);

                // Check required fields
                if (empty($_POST["nameTask"]) || empty($_POST["taskStatus"])) {
                    $this->view->error = "Campos obligatorios";
                    return;
                }

                $success = $this->taskModel->addTask(
                    $nameTask, 
                    $taskStatus,
                    $startDate,
                    $description, 
                    $endDate,
                    $provity
                );

                if ($success) {
                    $_SESSION['success'] = "Tarea creada exitosamente";
                    header('Location: ' . WEB_ROOT . '/tasks/view');
                    exit();
                } else {
                    $this->view->error = "La tarea ya existe.";
                }
            }
        }
    }
?>