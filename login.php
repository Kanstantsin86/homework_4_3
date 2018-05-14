<?php
session_start();
?>

<?php require_once("includes/connection.php"); ?>
<?php include("includes/header.php"); ?>

<?php

if(isset($_SESSION["session_username"])){
 //echo "Session is set"; // for testing purposes
header("Location: intropage.php");
}

if(isset($_POST["login"])){

if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $username=$_POST['username'];
    $password=$_POST['password'];

    $query =$db->query("SELECT * FROM user WHERE username='".$username."' AND password='".$password."'");

    $numrows=$query->fetch();

    if($numrows!=0)

    {

    if($username == $numrows['username'] && $password == $numrows['password'])

    {


    $_SESSION['session_username']=$username;

    /* Redirect browser */
    header("Location: intropage.php");
    }
    } else {

 $message =  "Неправильное имя пользователя или пароль!";
    }

} else {
    $message = "Все поля обязательны для заполнения!";
}
}
?>


    <div class="container mlogin">
            <div id="login">
    <h1>ВОЙТИ</h1>
<form name="loginform" id="loginform" action="" method="POST">
    <p>
        <label for="user_login">Имя пользователя<br />
        <input type="text" name="username" id="username" class="input" value="" size="20" /></label>
    </p>
    <p>
        <label for="user_pass">Пароль<br />
        <input type="password" name="password" id="password" class="input" value="" size="20" /></label>
    </p>
        <p class="submit">
        <input type="submit" name="login" class="button" value="Войти" />
    </p>
        <p class="regtext">Не зарегистрирован? <a href="register.php" >Зарегистрируйся</a>!</p>
</form>

    </div>

    </div>
	

	<?php if (!empty($message)) {echo "<p class=\"error\">" . "MESSAGE: ". $message . "</p>";} ?>
	