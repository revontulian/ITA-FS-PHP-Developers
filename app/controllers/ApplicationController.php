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

    protected function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Simple redirect without flash messages
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $this->view->baseUrl() . $url);
        exit();
    }
}
