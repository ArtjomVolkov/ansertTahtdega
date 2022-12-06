<?php
$parool = 'sa';
$sool = 'taiestisuvalinetekst';
$kryp = crypt($parool, $sool);
echo $kryp;
?>