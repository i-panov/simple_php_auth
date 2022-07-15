<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../bootstrap.php';

use App\Models\User;

if (!empty($_SESSION['user_login'])) {
    redirect('/');
}

$inputUser = User::create($_POST);
$validationErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationErrors = $inputUser->validate(['login', 'password']);

    if (!$validationErrors) {
        $fetchedUser = User::find($inputUser->login);

        if (!$fetchedUser) {
            $validationErrors['login'] = 'Пользователь не найден';
        } else {
            if (password_verify($inputUser->password, $fetchedUser->password)) {
                $_SESSION['user_login'] = $fetchedUser->login;
                redirect('/');
            } else {
                $validationErrors['password'] = 'Пароль не верный';
            }
        }
    }
}
?>

<form method="post">
    <div>
        <input name="login" placeholder="Логин" required value="<?php echo $inputUser->login ?>">
        <?php if (!empty($validationErrors['login'])) { ?>
            <span><?php echo $validationErrors['login'] ?></span>
        <?php } ?>
    </div>
    <div>
        <input type="password" name="password" required placeholder="Пароль">
        <?php if (!empty($validationErrors['password'])) { ?>
            <span><?php echo $validationErrors['password'] ?></span>
        <?php } ?>
    </div>
    <input type="submit" value="Login">
</form>
