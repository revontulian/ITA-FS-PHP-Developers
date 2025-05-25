<?php
declare(strict_types=1);


class UserController extends ApplicationController 
{
    public function loginAction()
    {
        $this->requireLogout(); // Evita che utenti loggati vedano il login

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$this->isValidEmail($email)) {
                $this->view->error = 'Please enter a valid email address.';
                return;
            }

            $userModel = new ModelUser();
            $user = $userModel->checkLogin($email, $password);
            
            if ($user) {
                $_SESSION['user'] = $user;
                $this->redirectWithMessage('/profile', 'Welcome back!');
            } else {
                $this->view->error = 'Invalid email or password.';
            }
        }
    }

    public function registerAction()
    {
        $this->requireLogout(); // Evita che utenti loggati vedano la registrazione

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $name = $this->sanitizeInput($_POST['name'] ?? '');
            $surname = $this->sanitizeInput($_POST['surname'] ?? '');
            $date_of_birth = $_POST['date_of_birth'] ?? '';

            // Validazioni
            if (!$this->isValidEmail($email)) {
                $this->view->error = 'Please enter a valid email address.';
                return;
            }
            
            if ($password !== $confirm) {
                $this->view->error = 'Passwords do not match.';
                return;
            }

            $userModel = new ModelUser();
            if ($userModel->emailExists($email)) {
                $this->view->error = 'Email already registered.';
                return;
            }

            $userModel->addUser($email, $password, $name, $surname, $date_of_birth);
            $this->redirectWithMessage('/login', 'Account created successfully! Please login.');
        }
    }

    public function profileAction()
    {
        $this->requireLogin(); // Protegge la pagina
        $this->view->user = $this->getCurrentUser();
    }

    public function logoutAction()
    {
        $this->requireLogin(); // Assicurati che sia loggato prima del logout
        
        session_destroy();
        $this->redirectWithMessage('/login', 'You have been logged out successfully.');
    }

    public function editAction()
    {
        $this->requireLogin();
        $user = $this->getCurrentUser();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $name = $this->sanitizeInput($_POST['name'] ?? '');
            $surname = $this->sanitizeInput($_POST['surname'] ?? '');
            $date_of_birth = $_POST['date_of_birth'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            // Validazioni
            if (!$this->isValidEmail($email)) {
                $this->view->error = 'Please enter a valid email address.';
                return;
            }

            if (!empty($password) && $password !== $confirm) {
                $this->view->error = 'Passwords do not match.';
                return;
            }

            $userModel = new ModelUser();
            $newData = [
                'email' => $email,
                'name' => $name,
                'surname' => $surname,
                'date_of_birth' => $date_of_birth
            ];
            
            // ✅ Cripta la password se fornita
            if (!empty($password)) {
                $newData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $userModel->updateUser($user['id'], $newData);

            // Aggiorna la sessione
            $updatedUsers = $userModel->getAll();
            foreach ($updatedUsers as $u) {
                if ($u['id'] === $user['id']) {
                    $_SESSION['user'] = $u;
                    break;
                }
            }

            $this->redirectWithMessage('/profile', 'Profile updated successfully!');
        }
        
        $this->view->user = $this->getCurrentUser();
    }

    public function deleteAction()
    {
        $this->requireLogin(); // Usa il metodo di ApplicationController
        
        $user = $this->getCurrentUser();
        $userModel = new ModelUser();
        $userModel->deleteUser($user['id']);
        
        session_destroy();
        $this->redirectWithMessage('/login', 'Account deleted successfully.');
    }
}