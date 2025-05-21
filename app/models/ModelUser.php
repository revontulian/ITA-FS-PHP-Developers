<?php
declare(strict_types=1);

class ModelUser
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../lib/data/users.json';
    }

    /**
     * Get all users from the JSON file.
     * @return array
     */
    public function getAll(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }
        $json = file_get_contents($this->file);
        $users = json_decode($json, true);
        return is_array($users) ? $users : [];
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
        $users = $this->getAll();

        // Check if email already exists
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return false;
            }
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $newUser = [
            'id' => uniqid(),
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'surname' => $surname,
            'date_of_birth' => $date_of_birth
        ];

        $users[] = $newUser;
        file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
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
        $users = $this->getAll();
        foreach ($users as $user) {
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
        $users = $this->getAll();
        foreach ($users as $user) {
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
        $users = $this->getAll();
        $updated = false;
        foreach ($users as &$user) {
            if ($user['id'] === $id) {
                // Update only provided fields
                foreach (['email', 'name', 'surname', 'date_of_birth'] as $field) {
                    if (isset($newData[$field])) {
                        $user[$field] = $newData[$field];
                    }
                }
                if (!empty($newData['password'])) {
                    $user['password'] = password_hash($newData['password'], PASSWORD_DEFAULT);
                }
                $updated = true;
                break;
            }
        }
        if ($updated) {
            file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
        }
        return $updated;
    }
}