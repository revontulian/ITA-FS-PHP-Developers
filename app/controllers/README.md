# Controllers README

This directory contains all the controllers for the application. Controllers are responsible for handling user requests, processing input, interacting with models, and selecting views to render responses. They are a core part of the MVC (Model-View-Controller) architecture used in this project.

---

## General Structure

- **All controllers are located in this folder:**  
  `app/controllers/`

- **Naming convention:**  
  Each controller class name ends with `Controller` (e.g., `UserController`, `TaskController`).

- **Inheritance:**  
  All controllers extend the base `ApplicationController`, which itself extends the framework's `Controller` class.  
  This allows you to define shared logic (like authentication checks or message handling) in `ApplicationController`.

- **Actions:**  
  Public methods that handle requests are called "actions" and must end with `Action` (e.g., `loginAction`, `createAction`).  
  Each action typically corresponds to a route and a view.

---

## Lifecycle of a Controller Request

1. **Routing:**  
   The router (see `config/routes.php`) maps a URL to a controller and an action.

2. **Initialization:**  
   The controller's `init()` method is called (useful for setup, like loading models).

3. **Before Filters:**  
   The `beforeFilters()` method is called if defined (for logic to run before every action, e.g., authentication).

4. **Action Execution:**  
   The requested action method is executed (e.g., `mainPageAction()`).

5. **After Filters:**  
   The `afterFilters()` method is called if defined (for logic to run after every action).

6. **View Rendering:**  
   The view corresponding to the action is rendered automatically (e.g., `app/views/scripts/user/login.phtml` for `UserController::loginAction()`).

---

## Main Controllers

- **ApplicationController.php**  
  Base application controller. Handles session messages, authentication helpers, and common utilities.

- **UserController.php**  
  Handles user registration, login, logout, profile management, and account deletion.

- **TaskController.php**  
  Handles CRUD operations for tasks, task filtering, and main task page.

- **TasklistController.php**  
  Handles creation, editing, and deletion of task lists.

- **ErrorController.php**  
  Handles application errors and displays error messages.

- **TestController.php**  
  Used for testing and demonstration purposes.

---

## Common Patterns

- **Accessing Request Data:**  
  Use `$this->_getParam('key', $default)` to access GET/POST parameters.

- **Redirects and Messages:**  
  Use `$this->redirect($url)`, `$this->redirectWithSuccess($url, $message)`, or `$this->redirectWithError($url, $message)` for navigation and user feedback.

- **Authentication:**  
  Use `$this->requireLogin()` to restrict actions to logged-in users.

- **Passing Data to Views:**  
  Assign variables to `$this->view` (e.g., `$this->view->tasks = $tasks;`) to make them available in the view template.

---

## Example: Basic Controller

```php
<?php

class ExampleController extends ApplicationController
{
    public function indexAction()
    {
        $this->view->message = "Hello from ExampleController!";
    }
}