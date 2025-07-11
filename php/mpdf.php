<?php
ini_set('display_errors', E_ALL);
include 'conf.php';
$data = json_decode($_POST['obJson']);
date_default_timezone_set('America/Mexico_City');
$nomArch=$_POST['referencia']."_".date("Y").date("m").date("d").".pdf";
$carpeta=carpetaBase.$_POST['cte']."/".$_POST['referencia']."/05-EXP-DIGITAL/";
if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
}
$cmd = "gs -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$carpeta$nomArch ";
foreach($data as $opcion){
	$cmd .= "'".$opcion."' ";
}
echo $cmd."<br><br><br><br>";
$result = shell_exec($cmd);
echo $result;
?>
