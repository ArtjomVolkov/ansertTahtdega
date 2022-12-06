<?php
$kasutaja='tarpv21'; //d113378_artjom
$server='localhost'; //d113378.mysql.zonevs.eu
$andmebaas='tarpv21'; //d113378_baasvolk
$salasyna='123456';
//teeme käsk mis ühendab andmebaasiga
$yhendus=new mysqli($server,$kasutaja,$salasyna,$andmebaas);
$yhendus->set_charset('UTF8');
/*
    create table tantsud(
    id int primary key auto_increment,
    tatantsupaar varchar(30) not null,
    punktid int default 0,
    kommentaarid varchar(250) default ' ',
    avalik int default 1,
    avaliku_paev datetime
);
 */
?>

