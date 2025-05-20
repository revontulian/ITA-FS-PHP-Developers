<?php
declare(strict_types=1);


class UserController extends ApplicationController 
{
    public function loginAction()
    {
        $this->view->error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $userModel = new ModelUser();
            $user = $userModel->checkLogin($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: ' . WEB_ROOT . '/profile');
                exit;
            } else {
                $this->view->error = 'Email o password non validi.';
            }
        }
    }

    public function registerAction()
    {
        $this->view->error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $name = $_POST['name'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $date_of_birth = $_POST['date_of_birth'] ?? '';

            if ($password !== $confirm) {
                $this->view->error = 'Le password non coincidono.';
                return;
            }

            $userModel = new ModelUser();
            if ($userModel->emailExists($email)) {
                $this->view->error = 'Email già registrata.';
                return;
            }

            $userModel->addUser($email, $password, $name, $surname, $date_of_birth);
            header('Location: ' . WEB_ROOT . '/login');
            exit;
        }
    }

    public function profileAction()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . WEB_ROOT . '/login');
            exit;
        }
        $this->view->user = $_SESSION['user'];
    }

    public function logoutAction()
    {
        session_destroy();
        header('Location: ' . WEB_ROOT . '/login');
        exit;
    }
}