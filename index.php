<?php
session_start();
require_once("includes/connection.php");
include("includes/header.php");
$id = $_GET['id'];
$action = $_GET['action'];

if($action == "done") {
    $db->exec("UPDATE task SET is_done='Выполнено' WHERE id = $id");
}
if($action == "delete") {
    $db->exec("DELETE FROM task WHERE id = $id");
}

$st = $db->query('SELECT * FROM task');

?>

<html>
<head>
    <title>TODO List</title>
    <meta charset="utf-8" lang="ru">
    <style>
        table {
            width: 70%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #C2C2FF;
            padding: 3px 7px 2px 7px;
        }
        th {
            text-align: left;
            padding: 5px;
            background-color: #9999FF;
            color: #fff;
        }
        tr:hover { background-color: #E0E0FF; }
        td:hover { background-color: #FFFFE0; }
    </style>
</head>
<body>

<h1>Здравствуйте, <?php echo $_SESSION['session_username'];?>! Вот ваш список дел:</h1>


<form method="POST">
    <input type="text" name="description" placeholder="Описание задачи" value="">
    <input type="submit" name="save" value="Добавить">
</form>

<!--label for="sort">Сортировать по:</label>

<select name="sort_by">
    <option value="date_created">Дате добавления</option>
    <option value="is_done">Статусу</option>
    <option value="description">Описанию</option>
</select>


<input type="submit" name="sort" value="Отсортировать">

<form></form-->

<table>
    <thead>
    <tr>
        <td><h4>Идентификатор</h4></td>
        <td><h4>Описание задачи</h4></td>
        <td><h4>Дата добавления</h4></td>
        <td><h4>Статус</h4></td>
        <td><h4>Действия</h4></td>
        <td><h4>Автор</h4></td>
        <td><h4>Ответственный</h4></td>
        <td><h4>Закрепить задачу за пользователем</h4></td>
    </tr>
    </thead>


    <tbody>
    <?php
    if(!empty($_POST['description'])) {
        $thSave = $_POST['description'];
        $thDate = date ('Y-m-d H:i:s');
        $thDone = "В процессе";
        $session_username = $_SESSION['session_username'];
        //$assigned_user = $_POST['assigned_user'];
        //$thAuthor = $db->query("SELECT id FROM user WHERE username='".$session_username."'");
        //$thAuthor = $db->query("SELECT id FROM assigned_user WHERE username='".$assigned_user."'");
        $userlist = $db->query("SELECT id, username FROM user");
        $numrows=$userlist->fetchAll();
        print_r($numrows);
        $rows = $db->exec("INSERT INTO task(id, description, is_done, date_added, user_id, assigned_user_id ) VALUES (null, '".@$thSave."','".@$thDone."','".@$thDate."', '".@$session_username."', '".@thAuthor."')");
        $st = $db->query('SELECT * FROM task');
    }

    while ($rows = $st->fetch()) { ?>
            <tr>
                <td><?php echo $rows ['id'] ?></td>
                <td><?php echo $rows ['description'] ?></td>
                <td><?php echo $rows ['date_added'] ?></td>
                <td><span style="color: goldenrod;"><?php echo $rows ['is_done'] ?></span></td>
                <td><a href="?id=<?= $rows ['id'];?>&action=done">Выполнить</a>
                    <a href="?id=<?= $rows ['id'];?>&action=delete">Удалить</a></td>
                <td><?php echo $rows ['user_id'] ?></td>
                <td><?php echo $rows ['assigned_user_id'] ?></td>
                <td>
                    <form action="select1.php" method="post">
                        <p><select size="3" multiple name="hero[]">
                                <option disabled>Выберите пользователя</option>
                                <?php
                                require_once("includes/connection.php");
                                $usernames = $db -> query("SELECT username FROM `user`");
                                foreach ($usernames as $value) {
                                    foreach($value as $key => $val) {
                                        echo '<option value="'.$val.'">'.$val.'</option>';
                                    }
                                }
                                ?>
                            </select></p>
                        <p><input type="submit" value="Поручить задачу пользователю"></p>
                    </form>
                </td>


            </tr>


    <?php }?>


    </tbody>
</table>

<table>
    <thead>
    <tr>
        <td><h4>Идентификатор</h4></td>
        <td><h4>Описание задачи</h4></td>
        <td><h4>Дата добавления</h4></td>
        <td><h4>Статус</h4></td>
        <td><h4>Действия</h4></td>
        <td><h4>Автор</h4></td>
    </tr>
    </thead>

    <h1>Cписок дел, порученных Вам другими пользователями:</h1>

    <tbody>
    <?php
    if(!empty($_POST['description'])) {
        $thSave = $_POST['description'];
        $thDate = date ('Y-m-d H:i:s');
        $thDone = "В процессе";
        $session_username = $_SESSION['session_username'];
        //$assigned_user = $_POST['assigned_user'];
        //$thAuthor = $db->query("SELECT id FROM user WHERE username='".$session_username."'");
        //$thAuthor = $db->query("SELECT id FROM assigned_user WHERE username='".$assigned_user."'");
        $userlist = $db->query("SELECT id, username FROM user");
        $numrows=$userlist->fetchAll();
        print_r($numrows);
        $rows = $db->exec("INSERT INTO task(id, description, is_done, date_added, user_id, assigned_user_id ) VALUES (null, '".@$thSave."','".@$thDone."','".@$thDate."', '".@$session_username."', '".@thAuthor."')");
        $st = $db->query('SELECT * FROM task');
    }

    while ($rows = $st->fetch()) { ?>
        <tr>
            <td><?php echo $rows ['id'] ?></td>
            <td><?php echo $rows ['description'] ?></td>
            <td><?php echo $rows ['date_added'] ?></td>
            <td><span style="color: goldenrod;"><?php echo $rows ['is_done'] ?></span></td>
            <td><a href="?id=<?= $rows ['id'];?>&action=done">Выполнить</a>
                <a href="?id=<?= $rows ['id'];?>&action=delete">Удалить</a></td>
            <td><?php echo $rows ['assigned_user_id'] ?></td>
        </tr>


    <?php }?>


    </tbody>
</table>


</body>
</html>