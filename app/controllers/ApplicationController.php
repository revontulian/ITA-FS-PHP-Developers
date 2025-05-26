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
        
        // Pass flash messages to the view WITHOUT deleting them
        $this->view->successMessage = $_SESSION['success_message'] ?? null;
        $this->view->errorMessage = $_SESSION['error_message'] ?? null;
        
        // Mark messages to be deleted on the next request
        if (isset($_SESSION['success_message'])) {
            $_SESSION['_delete_success_next'] = true;
        }
        if (isset($_SESSION['error_message'])) {
            $_SESSION['_delete_error_next'] = true;
        }
        
        //  Delete messages marked in the PREVIOUS request
        if (isset($_SESSION['_delete_success_next']) && $_SESSION['_delete_success_next'] === true) {
            unset($_SESSION['success_message']);
            unset($_SESSION['_delete_success_next']);
        }
        if (isset($_SESSION['_delete_error_next']) && $_SESSION['_delete_error_next'] === true) {
            unset($_SESSION['error_message']);
            unset($_SESSION['_delete_error_next']);
        }
    }

    // === REST OF THE METHODS ===
    
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function requireLogin(string $redirectTo = '/login'): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . WEB_ROOT . $redirectTo);
            exit;
        }
    }

    protected function requireLogout(string $redirectTo = '/profile'): void
    {
        if ($this->isLoggedIn()) {
            header('Location: ' . WEB_ROOT . $redirectTo);
            exit;
        }
    }

    protected function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * REDIRECT with message - SIMPLE VERSION
     */
    protected function redirectWithMessage(string $url, string $message, bool $isSuccess = true): void
    {
        // ✅ Set the new message (do NOT delete here)
        if ($isSuccess) {
            $_SESSION['success_message'] = $message;
        } else {
            $_SESSION['error_message'] = $message;
        }
        
        header('Location: ' . WEB_ROOT . $url);
        exit;
    }
}
