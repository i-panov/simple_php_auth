<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../bootstrap.php';

if (!empty($_SESSION['user_login'])) {
    unset($_SESSION['user_login']);
    redirect('/login.php');
}
