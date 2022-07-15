<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../bootstrap.php';

use App\Models\User;

if (empty($_SESSION['user_login'])) {
    redirect('/login.php');
}

?>

<div>
    <a href="/user.php">Создать</a>
    <a href="/logout.php">Выйти</a>
</div>

<table>
    <thead>
        <tr>
            <th>Логин</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Возраст</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (User::all() as $user) { ?>
        <tr>
            <td><?php echo $user->login ?></td>
            <td><?php echo $user->firstname ?></td>
            <td><?php echo $user->lastname ?></td>
            <td><?php echo $user->age ?></td>
            <td>
                <a href="/user.php?login=<?php echo $user->login ?>">Редактировать</a>
                <a href="/remove.php?login=<?php echo $user->login ?>">Удалить</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
