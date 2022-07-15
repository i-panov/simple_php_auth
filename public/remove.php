<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../bootstrap.php';

use App\Models\User;

if (empty($_SESSION['user_login'])) {
    redirect('/login.php');
}

$selectedUserLogin = $_GET['login'] ?? '';

if (!$selectedUserLogin) {
    throw new InvalidArgumentException("User $selectedUserLogin not found");
}

$user = User::find($selectedUserLogin);

if (!$user) {
    throw new InvalidArgumentException("User $selectedUserLogin not found");
}

$user->remove();
redirect('/');
