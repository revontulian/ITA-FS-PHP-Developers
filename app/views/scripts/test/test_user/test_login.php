<?php

require_once __DIR__ . '/../../../../models/ModelUser.php';

$model = new UserModel();

$email = 'mario@example.com';
$password = 'password123';

$user = $model->checkLogin($email, $password);

if ($user) {
    echo "Login successful! Welcome, " . $user['username'];
} else {
    echo "Invalid email or password!";
}