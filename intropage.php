<?php 
session_start();
if(!isset($_SESSION["session_username"])) {
	header("location:login.php");
} else {
?>


<?php include("includes/header.php"); ?>
<div id="welcome">	
	<h2>Добро пожаловать, <span><?php echo $_SESSION['session_username'];?>! </span></h2>
    <p><a href="index.php">Список задач</a></p>
	<p><a href="logout.php">Выйти</a></p>

</div>


<?php
}
?>
