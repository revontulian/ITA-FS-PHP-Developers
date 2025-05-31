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
                $this->redirectWithSuccess('/tasks/mainPage', 'Welcome back, ' . $user['name'] . '!');
            } else {
                $this->view->errorMessage = 'Invalid credentials. Please check your email and password.';
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
            
            if (strlen($password) < 6) {
                $this->view->errorMessage = 'Password must be at least 6 characters long.';
                return;
            }
            
            if ($password !== $confirm) {
                $this->view->errorMessage = 'Passwords do not match.';
                return;
            }

            $userModel = new ModelUser();
            if ($userModel->emailExists($email)) {
                $this->view->errorMessage = 'This email is already registered. Please use a different email.';
                return;
            }

            $success = $userModel->addUser($email, $password, $name, $surname, $date_of_birth);
            if ($success) {
                $this->redirectWithSuccess('/login', 'Account created successfully! Welcome to our platform, ' . $name . '. Please log in.');
            } else {
                $this->view->errorMessage = 'Error creating account. Please try again.';
            }
        }
    }

    public function profileAction()
    {
        $this->requireLogin(); 
        $this->view->user = $this->getCurrentUser();
    }

    public function logoutAction()
    {
        session_start(); 
        $_SESSION = array(); 
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        header('Location: ' . WEB_ROOT . '/login');
        exit;
    }

    public function editAction()
    {
        $this->requireLogin();
        $this->view->user = $this->getCurrentUser();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
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

            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $this->view->errorMessage = 'Password must be at least 6 characters long.';
                    return;
                }
                if ($password !== $confirm) {
                    $this->view->errorMessage = 'Passwords do not match.';
                    return;
                }
            }

            $userModel = new ModelUser();
            if ($userModel->emailExists($email) && $email !== $user['email']) {
                $this->view->errorMessage = 'This email is already used by another account.';
                return;
            }

            $newData = [
                'email' => $email,
                'name' => $name,
                'surname' => $surname,
                'date_of_birth' => $date_of_birth
            ];
            
            if (!empty($password)) {
                $newData['password'] = $password;
            }
            
            $success = $userModel->updateUser($user['id'], $newData);

            if ($success) {
                $updatedUsers = $userModel->getAll();
                foreach ($updatedUsers as $u) {
                    if ($u['id'] === $user['id']) {
                        $_SESSION['user'] = $u;
                        break;
                    }
                }

                $message = !empty($password) 
                    ? 'Profile and password updated successfully!' 
                    : 'Profile updated successfully!';
                $this->redirectWithSuccess('/profile', $message);
            } else {
                $this->view->errorMessage = 'Error updating profile. Please try again.';
            }
        }
    }

    public function deleteAction()
    {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $userName = $user['name'] ?? 'User';
        $userModel = new ModelUser();
        
        $success = $userModel->deleteUser($user['id']);
        
        if ($success) {
            session_destroy();
            session_start();
            $this->redirectWithSuccess('/login', 'Account deleted successfully. We\'re sorry to see you go, ' .
                                       $userName . '. Thank you for using our service.');
        } else {
            $this->redirectWithError('/profile', 'Error deleting account. Please try again or contact support.');
        }
    }
}