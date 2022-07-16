<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../bootstrap.php';

use App\Models\User;

requireAuth();
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
