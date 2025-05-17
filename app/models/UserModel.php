<?php
declare(strict_types=1);

class UserModel
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../data/users.json';
    }

    /**
     * Get all users from the JSON file.
     * @return array
     */
    public function getAll(): array
    {
        $json = file_get_contents($this->file);
        $users = json_decode($json, true);
        return is_array($users) ? $users : [];
    }

    /**
     * Add a new user to the JSON file.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function addUser(string $username, string $email, string $password): bool
    {
        $users = $this->getAll();

        // Check if username or email already exist
        foreach ($users as $user) {
            if ($user['username'] === $username || $user['email'] === $email) {
                return false; // User exists
            }
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create a new user
        $newUser = [
            'id' => uniqid(),
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ];

        $users[] = $newUser;

        // Save to the JSON file
        file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));

        return true;
    }

    /**
     * Check user login credentials.
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public function checkLogin(string $email, string $password): array|false
    {
        $users = $this->getAll();
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                return $user; // Login successful, return user data
            }
        }
        return false; // Login failed
    }
}