<?php
require_once ('connect.php');

function kysiKaupadeAndmed($sorttulp="tatantsupaar", $otsisona=""){
    global $yhendus;
    $lubatudtulbad=array("tatantsupaar", "punktid", "kommentaarid");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT tantsud.id, tatantsupaar, punktid, kommentaarid, avalik, avaliku_paev
       FROM tantsud
       WHERE tatantsupaar LIKE '%$otsisona%'
       ORDER BY $sorttulp");
    //echo $yhendus->error;
    $kask->bind_result($id, $tatantsupaar, $punktid, $kommentaarid, $avalik, $avaliku_paev);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $kaup=new stdClass();
        $kaup->id=$id;
        $kaup->tatantsupaar=htmlspecialchars($tatantsupaar);
        $kaup->punktid=htmlspecialchars($punktid);
        $kaup->kommentaarid=$kommentaarid;
        $kaup->avalik=$avalik;
        $kaup->avaliku_paev=$avaliku_paev;
        array_push($hoidla, $kaup);
    }
    return $hoidla;
}

?>
<?php ?>
