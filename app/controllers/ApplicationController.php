<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ApplicationController extends Controller 
{
    /**
     * Method called before every action
     */
    public function init()
    {
        parent::init();
        
        if (isset($_SESSION['success_message'])) {
            $this->view->successMessage = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        
        if (isset($_SESSION['error_message'])) {
            $this->view->errorMessage = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

        protected function getCurrentList(): ?string {
   
        return $_SESSION['tasklistId'] ?? null; 
    }



    protected function requireLogin(string $redirectTo = '/login'): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . $this->view->baseUrl() . $redirectTo);
            exit;
        }
    }

    protected function requireLogout(string $redirectTo = '/profile'): void
    {
        if ($this->isLoggedIn()) {
            header('Location: ' . $this->view->baseUrl() . $redirectTo);
            exit;
        }
    }

    protected function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    //Change input for security
    protected function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    protected function redirectWithSuccess(string $url, string $message): void
    {
        $_SESSION['success_message'] = $message;
        header('Location: ' . $this->view->baseUrl() . $url);
        exit;
    }

    protected function redirectWithError(string $url, string $message): void
    {
        $_SESSION['error_message'] = $message;
        header('Location: ' . $this->view->baseUrl() . $url);
        exit;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $this->view->baseUrl() . $url);
        exit;
    }
}
