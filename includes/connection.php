
<?php
//$db = new PDO('mysql:host=http://university.netology.ru/u/litvink/;dbname=litvink', "litvink", "neto1742");

//$db->exec("set names utf8");


/*$servername = "http://university.netology.ru/u/litvink/";
$dbname = "todo-list";
$username = "litvink";
$password = "neto1742";*/
$servername = "localhost";
$dbname = "todo-litvink";
$username = "root";
$password = "";
$db = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
?>