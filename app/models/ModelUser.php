<?php
declare(strict_types=1);

class ModelUser
{
    private JsonCRUD $crud;

    public function __construct()
    {
        $this->crud = new JsonCRUD(ROOT_PATH . '/lib/data/users.json');
    }

    /**
     * Get all users from the JSON file.
     * @return array
     */
    public function getAll(): array
    {
        return $this->crud->read();
    }

    /**
     * Add a new user.
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string $surname
     * @param string $date_of_birth
     * @return bool
     */
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

    /**
     * Check login credentials.
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public function checkLogin(string $email, string $password): array|false
    {
        foreach ($this->crud->read() as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Check if an email is already registered.
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        foreach ($this->crud->read() as $user) {
            if ($user['email'] === $email) {
                return true;
            }
        }
        return false;
    }

    /**
     * Update user data.
     * @param string $id
     * @param array $newData (email, password, name, surname, date_of_birth)
     * @return bool
     */
    public function updateUser(string $id, array $newData): bool
    {
        $user = $this->crud->update($id, $newData);
        return $user !== null;
    }

    /**
     * Delete a user.
     * @param string $id
     * @return bool
     */
    public function deleteUser(string $id): bool
    {
        return $this->crud->delete($id);
    }
}