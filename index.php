<?php
session_start();
require_once("includes/connection.php");
require_once("includes/header.php");

$user_id = $_SESSION['id'];
if (isset($_GET['id'])){
    $id = (int)$_GET['id'];
}

if (isset($_GET['action'])){
    $action = $_GET['action'];
    if($action == "done") {
        $sth = $db->prepare("UPDATE task SET is_done='Выполнено' WHERE id = ? AND user_id = ?");
        $sth->execute(array($id, $user_id));
    }
    if($action == "delete") {
        $sth = $db->prepare("DELETE FROM task WHERE id = ? AND user_id = ?");
        $sth->execute(array($id, $user_id));
    }
}

$sth = $db->prepare("SELECT * FROM task WHERE user_id = ?");
        $sth->execute(array($user_id));
?>

<html>
<head>
    <title>TODO List</title>
    <meta charset="utf-8" lang="ru">
    <style>
        table {
            width: 100%;
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

<h1>Здравствуйте, <span><?php echo $_SESSION['session_username'];?>!</span> Вот ваш список дел:</h1>
<p><a href="logout.php">Выйти</a></p>

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
    if(isset($_POST['description'])) {
        $description = $_POST['description'];
        $is_done = "В процессе";
        date_default_timezone_set('Europe/Minsk');
        $date_added = date ('Y-m-d H:i:s');
        $rows = $db->prepare("INSERT INTO task(id, description, is_done, date_added, user_id, assigned_user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $rows->execute(array(null, $description, $is_done, $date_added, $user_id, null));
        $sth = $db->prepare("SELECT * FROM task WHERE user_id = ?");
        $sth->execute(array($user_id));
    }

    while ($rows = $sth->fetch()) { ?>
            <tr>
                <td><?php echo $rows ['id'] ?></td>
                <td><?php echo $rows ['description'] ?></td>
                <td><?php echo $rows ['date_added'] ?></td>
                <td><span style="color: goldenrod;"><?php echo $rows ['is_done'] ?></span></td>
                <td><a href="?id=<?= $rows ['id'];?>&action=done">Выполнить</a>
                    <a href="?id=<?= $rows ['id'];?>&action=delete">Удалить</a></td>
                <td><?php $auth = $db->prepare("SELECT user.login FROM `user` JOIN task ON user.id = task.user_id WHERE user_id = $user_id");
                          $auth->execute();
                          $author = $auth->fetch();
                          echo $author['login'];?>
                </td>
                <td><?php if(isset($_POST['assigned_user'])) {
                            $result = $_POST['assigned_user_id'];
                            $result_explode = explode('_', $result);//получаем user_id и id задачи
                            $stu = $db->prepare("UPDATE task SET assigned_user_id = ? WHERE id = ?");//изменяем assigned_user_id по id задачи
                            $stu->execute(array($result_explode[0], $result_explode[1])); //подставляем user_id и id задачи
                            $ass = $stu->fetch();
                            $assigned_user = $db->prepare("SELECT user.login FROM `user` JOIN task ON user.id = task.assigned_user_id WHERE task.id =" . $rows['id']);
                            $assigned_user->execute();
                            $assigned = $assigned_user->fetch();
                            echo $assigned['login'];
                            } else {
                            echo $author['login'];
                          }?>
                </td>
                <td><form action="" method="post">
                        <p><select name="assigned_user_id" size="5" multiple>
                                <option disabled>Выберите пользователя</option>
                                <?php
                                $usernames = $db -> query("SELECT id, login FROM user");
                                $username = $usernames->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($username as $value) {
                                    echo '<option value="'.$value['id'].'_'.$rows ['id'].'">'.$value['login'].'</option>';
                                }?>
                            </select></p>
                        <p><input type="submit" name="assigned_user" value="Переложить ответственность"></p>
                        
                    </form>
                </td>
            </tr>
    <?php } ?>
    </tbody>
</table>
<!------------------------------------------------------------//////////////////////////////////////////////////////////////////////////////-->
 <?php $assigned_user_id = $_SESSION['id'];
if (isset($_GET['id'])){
    $id = (int)$_GET['id'];
}

if (isset($_GET['action'])){
    $action = $_GET['action'];
    if($action == "done") {
        $sth = $db->prepare("UPDATE task SET is_done='Выполнено' WHERE id = ? AND assigned_user_id = ?");
        $sth->execute(array($id, $assigned_user_id));
    }
    if($action == "delete") {
        $sth = $db->prepare("DELETE FROM task WHERE id = ? AND assigned_user_id = ?");
        $sth->execute(array($id, $assigned_user_id));
    }
}

$sth = $db->prepare("SELECT * FROM task WHERE assigned_user_id = ?");
        $sth->execute(array($assigned_user_id));
?>
<h2><span>Также, посмотрите, что от Вас требуют другие люди:</span></h2>
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
        
    </tr>
    </thead>

    <tbody>
    <?php
    
        /*$description = $_POST['description'];
        $is_done = "В процессе";
        date_default_timezone_set('Europe/Minsk');
        $date_added = date ('Y-m-d H:i:s');
        $rows = $db->prepare("INSERT INTO task(id, description, is_done, date_added, user_id, assigned_user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $rows->execute(array(null, $description, $is_done, $date_added, $user_id, null));*/
        $sth = $db->prepare("SELECT * FROM task WHERE assigned_user_id = ?");
        $sth->execute(array($assigned_user_id));
    

    while ($rows = $sth->fetch()) { ?>
            <tr>
                <td><?php echo $rows ['id'] ?></td>
                <td><?php echo $rows ['description'] ?></td>
                <td><?php echo $rows ['date_added'] ?></td>
                <td><span style="color: goldenrod;"><?php echo $rows ['is_done'] ?></span></td>
                <td><a href="?id=<?= $rows ['id'];?>&action=done">Выполнить</a>
                    <a href="?id=<?= $rows ['id'];?>&action=delete">Удалить</a></td>
                <td><?php $auth = $db->prepare("SELECT user.login FROM `user` JOIN task ON user.id = task.user_id WHERE assigned_user_id = $assigned_user_id");
                          $auth->execute();
                          $author = $auth->fetch();
                          echo $author['login'];?>
                </td>
                <td><?php /*if(isset($_POST['assigned_user'])) {
                            $result = $_POST['assigned_user_id'];
                            $result_explode = explode('_', $result);//получаем user_id и id задачи
                            $stu = $db->prepare("UPDATE task SET assigned_user_id = ? WHERE id = ?");//изменяем assigned_user_id по id задачи
                            $stu->execute(array($result_explode[0], $result_explode[1])); //подставляем user_id и id задачи
                            $ass = $stu->fetch();*/
                            $assigned_user = $db->prepare("SELECT user.login FROM `user` JOIN task ON user.id = task.assigned_user_id WHERE task.id =" . $rows['id']);
                            $assigned_user->execute();
                            $assigned = $assigned_user->fetch();
                            echo $assigned['login'];
                            /* } else {
                            echo $author['login'];
                          }*/?>
                </td>
                <!--td><form action="" method="post">
                        <p><select name="assigned_user_id" size="5" multiple>
                                <option disabled>Выберите пользователя</option>
                                <!?php
                                $usernames = $db -> query("SELECT id, login FROM user");
                                $username = $usernames->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($username as $value) {
                                    echo '<option value="'.$value['id'].'_'.$rows ['id'].'">'.$value['login'].'</option>';
                                }?>
                            </select></p>
                        <p><input type="submit" name="assigned_user" value="Переложить ответственность"></p>
                        
                    </form>
                </td-->
            </tr>
    <?php } ?>
    </tbody>
</table>