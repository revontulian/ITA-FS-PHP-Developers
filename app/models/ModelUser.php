<?php
declare(strict_types=1);

class ModelUser
{
    private JsonCRUD $crud;

    public function __construct()
    {
        $this->crud = new JsonCRUD('users.json');
    }

    public function getAll(): array
    {
        return $this->crud->read();
    }

    public function addUser(string $email, string $password, string $name, string $surname, string $date_of_birth): bool
    {
        // Check if email already exists
        foreach ($this->crud->read() as $user) {
            if ($user['email'] === $email) {
                return false;
            }
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->crud->create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'surname' => $surname,
            'date_of_birth' => $date_of_birth
        ]);
        return true;
    }
    
    public function checkLogin(string $email, string $password): ?array
    {
        $users = $this->crud->read();
        
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return null; 
    }

    public function emailExists(string $email): bool
    {
        foreach ($this->crud->read() as $user) {
            if ($user['email'] === $email) {
                return true;
            }
        }
        return false;
    }


    public function updateUser(string $id, array $newData): bool
    {
        if (isset($newData['password']) && !empty($newData['password'])) {
            $newData['password'] = password_hash($newData['password'], PASSWORD_DEFAULT);
        }
        
        $user = $this->crud->update($id, $newData);
        return $user !== null;
    }

    public function deleteUser(string $id): bool
    {
        return $this->crud->delete($id);
    }
}