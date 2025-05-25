<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ApplicationController extends Controller 
{
    /**
     * Metodo chiamato prima di ogni action
     */
    public function init()
    {
        parent::init();
        
        // Passa sempre i messaggi di successo/errore alle viste
        $this->view->successMessage = $this->getSuccessMessage();
        $this->view->errorMessage = $this->getErrorMessage();
    }

    // === METODI DI AUTENTICAZIONE ===
    
    /**
     * Controlla se l'utente è loggato
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    /**
     * Ottiene l'utente corrente dalla sessione
     */
    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Reindirizza se l'utente NON è loggato
     */
    protected function requireLogin(string $redirectTo = '/login'): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . WEB_ROOT . $redirectTo);
            exit;
        }
    }

    /**
     * Reindirizza se l'utente È GIÀ loggato
     */
    protected function requireLogout(string $redirectTo = '/profile'): void
    {
        if ($this->isLoggedIn()) {
            header('Location: ' . WEB_ROOT . $redirectTo);
            exit;
        }
    }

    // === MESSAGGI DI FEEDBACK ===
    
    /**
     * Imposta un messaggio di successo
     */
    protected function setSuccessMessage(string $message): void
    {
        $_SESSION['success_message'] = $message;
    }

    /**
     * Ottiene e cancella il messaggio di successo
     */
    protected function getSuccessMessage(): ?string
    {
        $message = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);
        return $message;
    }

    /**
     * Imposta un messaggio di errore
     */
    protected function setErrorMessage(string $message): void
    {
        $_SESSION['error_message'] = $message;
    }

    /**
     * Ottiene e cancella il messaggio di errore
     */
    protected function getErrorMessage(): ?string
    {
        $message = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        return $message;
    }

    // === VALIDAZIONE ===
    
    /**
     * Valida che l'email sia corretta
     */
    protected function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // === UTILITY ===
    
    /**
     * Sanifica l'input dell'utente
     */
    protected function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Redirect con messaggio
     */
    protected function redirectWithMessage(string $url, string $message, bool $isSuccess = true): void
    {
        if ($isSuccess) {
            $this->setSuccessMessage($message);
        } else {
            $this->setErrorMessage($message);
        }
        header('Location: ' . WEB_ROOT . $url);
        exit;
    }
}
