<?php

require_once 'Models/User.php';

session_start();

function redirect(string $location, bool $permanent = false) {
    $location = $location ?: '/';
    header("Location: $location", true, $permanent ? 301 : 302);
    die();
}

function requireAuth() {
    if (empty($_SESSION['user_login'])) {
        redirect('/login.php');
    }
}
