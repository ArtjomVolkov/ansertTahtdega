<?php
require_once ('connect.php');
if (!empty($_REQUEST['login']) && !empty($_REQUEST['pass'])){
    global $yhendus;
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    //SIIA UUS KONTROLL
    $sool = 'taiestisuvalinetekst';
    $kryp = crypt($pass, $sool);
    $kask=$yhendus->prepare("INSERT INTO kasutajad(kasutaja,parool) VALUES (?,?)");
    $kask->bind_param("ss",$_REQUEST['login'],$kryp);
    $kask->execute();
}?>
<head>
    <meta charset="UTF-8">
    <link href="tant.css" rel="stylesheet"/>
</head>
<h1>Register</h1>
<form action="" method="post">
    Login: <input type="text" name="login"><br>
Password: <input type="password" name="pass"><br>
    <input type="submit" value="Register">
    <a href="ablogin.php">Login</a>
</form>
