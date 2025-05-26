<?php
declare(strict_types=1);


class UserController extends ApplicationController 
{
    public function loginAction()
    {
        $this->requireLogout('/tasks/mainPage');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$this->isValidEmail($email)) {
                $this->view->errorMessage = 'Please enter a valid email address.';
                return;
            }

            $userModel = new ModelUser();
            $user = $userModel->checkLogin($email, $password);
            
            if ($user) {
                $_SESSION['user'] = $user;
                $this->redirect('/tasks/mainPage'); // ✅ Pulito e consistente
            } else {
                $this->view->errorMessage = 'Invalid email or password.';
            }
        }
    }

    public function registerAction()
    {
        $this->requireLogout(); 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $name = $this->sanitizeInput($_POST['name'] ?? '');
            $surname = $this->sanitizeInput($_POST['surname'] ?? '');
            $date_of_birth = $_POST['date_of_birth'] ?? '';

            // Validations
            if (!$this->isValidEmail($email)) {
                $this->view->errorMessage = 'Please enter a valid email address.';
                return;
            }
            
            if ($password !== $confirm) {
                $this->view->errorMessage = 'Passwords do not match.';
                return;
            }

            $userModel = new ModelUser();
            if ($userModel->emailExists($email)) {
                $this->view->errorMessage = 'Email already registered.';
                return;
            }

            $userModel->addUser($email, $password, $name, $surname, $date_of_birth);
            $this->redirect('/login');  // ✅ Redirect semplice
        }
    }

    public function profileAction()
    {
        $this->requireLogin(); 
        $this->view->user = $this->getCurrentUser();
    }

    public function logoutAction()
    {
        $this->requireLogin(); 
        session_destroy();
        $this->redirect('/login');  // ✅ Redirect semplice
    }

    public function editAction()
    {
        $this->requireLogin();
    
        $this->view->user = $this->getCurrentUser();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser(); // Per le operazioni interne
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $name = $this->sanitizeInput($_POST['name'] ?? '');
            $surname = $this->sanitizeInput($_POST['surname'] ?? '');
            $date_of_birth = $_POST['date_of_birth'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            // Validations
            if (!$this->isValidEmail($email)) {
                $this->view->errorMessage = 'Please enter a valid email address.';
                return; 
            }

            if (!empty($password) && $password !== $confirm) {
                $this->view->errorMessage = 'Passwords do not match.';
                return; 
            }

            $userModel = new ModelUser();
            $newData = [
                'email' => $email,
                'name' => $name,
                'surname' => $surname,
                'date_of_birth' => $date_of_birth
            ];
            
            if (!empty($password)) {
                $newData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $userModel->updateUser($user['id'], $newData);

            // Update session
            $updatedUsers = $userModel->getAll();
            foreach ($updatedUsers as $u) {
                if ($u['id'] === $user['id']) {
                    $_SESSION['user'] = $u;
                    break;
                }
            }

            $this->redirect('/profile');  // ✅ Redirect semplice
        }
    }

    public function deleteAction()
    {
        $this->requireLogin(); // require method from ApplicationController.php
        
        $user = $this->getCurrentUser();
        $userModel = new ModelUser();
        $userModel->deleteUser($user['id']);
        
        session_destroy();
        $this->redirect('/login');  // ✅ Redirect semplice
    }
}