<?php
require("abifunktsioonid1.php");
require_once ('connect.php');
session_start();
if (!isset($_SESSION['tuvastamine'])) {
  header('Location: ablogin.php');
  exit();
  }
$sorttulp = "tatantsupaar";
$otsisona = "";
if (isset($_REQUEST["sorttulp"])) {
    $sorttulp = $_REQUEST["sorttulp"];
}
if (isset($_REQUEST["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}
if (isset($_REQUEST['kustutaid'])){
    global $yhendus;
    $kask = $yhendus->prepare('DELETE FROM tantsud WHERE id=?');
    $kask->bind_param("i",$_REQUEST['kustutaid']);
    $kask->execute();
}
//kommentaaride kustatamine
if (isset($_REQUEST['kuskom'])){
    $kask = $yhendus->prepare('UPDATE tantsud SET kommentaarid=" " WHERE id=?');
    $kask->bind_param("s",$_REQUEST['kuskom']);
    $kask->execute();
}
//punktide nulliks
if(isset($_REQUEST['punkt0'])){
    $kask=$yhendus->prepare('UPDATE tantsud SET punktid=0 WHERE id=?');
    $kask->bind_param("i",$_REQUEST['punkt0']);
    $kask->execute();
}
//peitmine
if(isset($_REQUEST['peitmine'])){
    $kask=$yhendus->prepare('UPDATE tantsud SET avalik=0 WHERE id=?');
    $kask->bind_param("i",$_REQUEST['peitmine']);
    $kask->execute();
}
//näitamine
if(isset($_REQUEST['naitamine'])){
    $kask=$yhendus->prepare('UPDATE tantsud SET avalik=1 WHERE id=?');
    $kask->bind_param("i",$_REQUEST['naitamine']);
    $kask->execute();
}
$kaubad = kysiKaupadeAndmed($sorttulp, $otsisona);
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
    <h2>Administraatori leht</h2>
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
<table action="admin.php">
    <form action="admin.php">
        <input type="text" name="otsisona" placeholder="Otsi...">
    </form>
    <tr>
        <th>
            <a href="admin.php?sorttulp=tatantsupaar">Tantsupaar</a>
        </th>
        <th>
            <a href="admin.php?sorttulp=punktid">Punktid<br>
                Punktid nulliks</a>
        </th>
        <th>
            <a href="admin.php?sorttulp=kommentaarid">Kommentaarid
                <br>Kustuta kommentaarid</a>
        </th>
        <th>
            Avalikustamine staatus
        </th>
        <th>
            Avalikustamise päev
        </th>
    </tr>
<?php
global $yhendus;
$kask=$yhendus->prepare('SELECT id, tatantsupaar,punktid,kommentaarid,avaliku_paev,avalik FROM tantsud');
$kask->bind_result($id,$tatantsupaar,$punktid,$kommentaarid,$avaliku_paev,$avalik);
$kask->execute();
foreach ($kaubad as $kaup):?>
    <tr>
    <td><?= $kaup->tatantsupaar ?><br><a href='admin.php?kustutaid=<?= $kaup->id ?>' onclick="return confirm('Kas ikka soovid kustutada?')">Kustuta kõik</a></td>
    <td><?= $kaup->punktid ?><br><a href='admin.php?punkt0=<?= $kaup->id ?>'>Nulliks</a></td>
    <td><?= $kaup->kommentaarid ?><br><a href='admin.php?kuskom=<?= $kaup->id ?>'>Kustuta</a></td>
        <?php
    $tekst='Näita';
    $seisund='naitamine';
    $kasutajatekst='Kasutaja ei näe';
    if ($avalik==1){
        $tekst='Peida';
        $seisund='peitmine';
        $kasutajatekst='Kasutaja näeb';}
    echo "<td>".$kasutajatekst ."<br> <a href='?seisund=$kaup->id'>$tekst</a></td>";?>
    <td><?= $kaup->avaliku_paev ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
