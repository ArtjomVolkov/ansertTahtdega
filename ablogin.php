<?php
require_once ('connect.php');
session_start();
if (isset($_SESSION['tuvastamine'])) {
    header('Location: admin.php');
    exit();

}
//create table kasutajad (
//        id int PRIMARY KEY AUTO_INCREMENT,
//    kasutaja varchar(20),
//    parool varchar(250))
//kontrollime kas väljad on täidetud
if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    global $yhendus;
    //eemaldame kasutaja sisestusest kahtlase pahna
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    //SIIA UUS KONTROLL
    $sool = 'taiestisuvalinetekst';
    $kryp = crypt($pass, $sool);
    //kontrollime kas andmebaasis on selline kasutaja ja parool
    //$paring = "SELECT * FROM kasutajad WHERE kasutaja='$login' AND parool='$kryp'";
    //$valjund = mysqli_query($yhendus, $paring);
    //kui on, siis loome sessiooni ja suuname

    $kask=$yhendus->prepare("SELECT kasutaja,onAdmin,koduleht FROM kasutajad WHERE kasutaja=? AND parool=?");
    $kask->bind_param("ss",$login,$kryp);
    $kask->bind_result($nimi,$onAdmin,$koduleht);
    $kask->execute();
    if ($kask->fetch()){
    //if (mysqli_num_rows($valjund)==1) {
        $_SESSION['tuvastamine'] = 'misiganes';
        $_SESSION['kasutaja']=$nimi;
        $_SESSION['onAdmin']=$onAdmin;
        if(isset($koduleht) && $onAdmin==1){
            header("Location: $koduleht");
        } else{
            header("Location: $koduleht");
            exit();
        }
    } else {
        echo "kasutaja $login või parool $kryp on vale";
    }
}
?>
<head>
    <meta charset="UTF-8">
    <link href="tant.css" rel="stylesheet"/>
</head>
<h1>Login</h1>
<form action="" method="post">
    Login: <input type="text" name="login"><br>
    Password: <input type="password" name="pass"><br>
    <input type="submit" value="Logi sisse">    <br>
    <a href="register.php">1. Register</a>
    <a href="register2.php">2. Register</a>
</form>
