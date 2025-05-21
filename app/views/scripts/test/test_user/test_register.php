<?php
require_once __DIR__ . '/../../../../models/UserModel.php';

$model = new UserModel();

// Prova a registrare un nuovo utente
$username = 'mario';
$email = 'mario@example.com';
$password = 'password123';

$result = $model->addUser($username, $email, $password);

if ($result) {
    echo "Registrazione avvenuta con successo!";
} else {
    echo "Username o email già esistenti!";
}