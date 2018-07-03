<?php 
require_once("includes/connection.php"); 
require_once("includes/header.php");

if(isset($_POST["register"])){
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$query = $db->query("SELECT * FROM user WHERE login='".$username."'");
	$numrows = $query->fetchColumn();
	if($numrows == 0){
	$sql = "INSERT INTO user (login,password) VALUES( '$username', '$password')";
	$result = $db->exec($sql);
            if($result){
                $message = "Аккаунт успешно создан";
            } else {
                $message = "Не удалось вставить данные!";
            }
	} else {
            $message = "Это имя пользователя уже существует! Пожалуйста, введите другое имя!";
	}
    } else {
	$message = "Все поля обязательны для заполнения!";
    }
}
?>


<?php if (!empty($message)) {echo "<p class=\"error\">" . "MESSAGE: ". $message . "</p>";} ?>
	
<div class="container mregister">
    <div id="login">
    <h1>РЕГИСТРАЦИЯ</h1>
    <form name="registerform" id="registerform" action="register.php" method="post">
    <p>
	<label for="user_pass">Имя пользователя<br />
	<input type="text" name="username" id="username" class="input" value="" size="20" /></label>
    </p>
    <p>
	<label for="user_pass">Пароль<br />
	<input type="password" name="password" id="password" class="input" value="" size="32" /></label>
    </p>	
	<p class="submit">
	<input type="submit" name="register" id="register" class="button" value="Зарегистрироваться" />
    </p>
    <p class="regtext">Уже зарегистрирован? <a href="login.php" >Войти тут</a>!</p>
    </form>
    </div>
</div>
	
	
