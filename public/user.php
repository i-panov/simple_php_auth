<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../bootstrap.php';

use App\Models\User;

if (empty($_SESSION['user_login'])) {
    redirect('/login.php');
}

$selectedUserLogin = $_GET['login'] ?? '';

if ($selectedUserLogin) {
    $user = User::find($selectedUserLogin);

    if (!$user) {
        throw new InvalidArgumentException("User $selectedUserLogin not found");
    }
} else {
    $user = User::empty();
}

$validationErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationFields = ['firstname', 'lastname', 'age', ];

    if ($selectedUserLogin) {
        $user->login = $selectedUserLogin;

        $user->load([
            'firstname' => $_POST['firstname'] ?? '',
            'lastname' => $_POST['lastname'] ?? '',
            'age' => $_POST['age'] ?? '',
        ]);
    } else {
        $validationFields = array_merge($validationFields, ['login', 'password']);
        $user->load($_POST);
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
    }

    $validationErrors = $user->validate($validationFields);

    if (!$validationErrors) {
        $user->save();
        redirect('/');
    }
}
?>

<form method="post">
    <div>
        <input name="firstname" placeholder="Имя" required value="<?php echo $user->firstname ?>">
        <?php if (!empty($validationErrors['firstname'])) { ?>
            <span><?php echo $validationErrors['firstname'] ?></span>
        <?php } ?>
    </div>
    <div>
        <input name="lastname" placeholder="Фамилия" required value="<?php echo $user->lastname ?>">
        <?php if (!empty($validationErrors['lastname'])) { ?>
            <span><?php echo $validationErrors['lastname'] ?></span>
        <?php } ?>
    </div>
    <div>
        <input type="number" name="age" placeholder="Возраст" required min="0" value="<?php echo $user->age ?>">
        <?php if (!empty($validationErrors['age'])) { ?>
            <span><?php echo $validationErrors['age'] ?></span>
        <?php } ?>
    </div>
    <?php if (!$selectedUserLogin) { ?>
    <div>
        <input name="login" placeholder="Логин" required value="<?php echo $user->login ?>">
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
    <?php } ?>
    <input type="submit" value="Сохранить">
</form>
