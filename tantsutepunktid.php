<?php
require_once ('connect.php');
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ablogin.php');
    exit();
}
//if ($_SESSION['onAdmin']){

//}
function isAdmin(){
    return isset($_SESSION['onAdmin'])&&$_SESSION['onAdmin'];
}
//Uue tantsupaari lisamine
if (!empty($_REQUEST['paarinimi']) && isAdmin()){
    global $yhendus;
    $kask=$yhendus->prepare('INSERT INTO tantsud (tatantsupaar,avaliku_paev,pilt) VALUES (?,NOW(),?)');
    $kask->bind_param("ss",$_REQUEST['paarinimi'],$_REQUEST['pilt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//kommentaaride lisamine
if (isset($_REQUEST['uuskomment'])){
    if (!empty($_REQUEST['komment'])){
        $kask = $yhendus->prepare('UPDATE tantsud SET kommentaarid=CONCAT(kommentaarid,?) WHERE id=?');
        $kommentplus=$_REQUEST['komment']."\n";
        $kask->bind_param("si",$kommentplus,$_REQUEST['uuskomment']);
        $kask->execute();
        header("Location: $_SERVER[PHP_SELF]");
        }
    else{
        echo '<script>alert("Sisesta komment")</script>';
    }
}
//punktide lisamine
if(isset($_REQUEST['punkt'])){
    $kask=$yhendus->prepare('UPDATE tantsud SET punktid=punktid+1 WHERE id=?');
    $kask->bind_param("i",$_REQUEST['punkt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv21 tantsud</title>
    <link href="tant.css" rel="stylesheet"/>
</head>
<body>
<header>
    <h1>Tantsud TARpv21</h1>
    <h2>Kasutaja leht</h2>
    <nav>
        <a href="tantsutepunktid.php">Kasutaja leht</a>
        <a href="admin.php">Admin leht</a>
    </nav>
    <?php
    echo $_SESSION['kasutaja']?> on sisse logitud
    <form action="logout.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
</header>
<table>
    <tr>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid
        </th>
        <th>
            Haldus
        </th>
        <th>
            Kommentaarid
        </th>
        <th>Pilt</th>
    </tr>
    <?php
    global $yhendus;
    $kask=$yhendus->prepare('SELECT id, tatantsupaar,punktid,kommentaarid,pilt FROM tantsud WHERE avalik=1');
    $kask->bind_result($id,$tatantsupaar,$punktid,$kommentaarid,$pilt);
    $kask->execute();
    while($kask->fetch()){
        echo "<tr>";
        echo "<td>".$tatantsupaar."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td><a href='?punkt=$id'>Lisa 1 punkt</a></td>";
        $kommentaarid=nl2br(htmlspecialchars($kommentaarid));
        echo "<td>".$kommentaarid."</td>";
        echo "<td><img src='$pilt' alt='pilt' width='150px' height='150px'></td>";
        echo "<td>
<form action='?'>
<input type='hidden' value='$id' name='uuskomment'>
<input type='text' name='komment'>
<input type='submit' value='OK'>
</form>
</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php if(isAdmin()){ ?>
<div>
    <h2>Uue tantsupaari lisamine</h2>
    <form action="?">
        <input type="text" placeholder="Tantsupaari nimed" name="paarinimi">
        <textarea name="pilt" placeholder="Siia lisa pildi aadress"></textarea>
        <input type="submit" value="Ok">
    </form>
</div>
<?php } ?>
</body>
