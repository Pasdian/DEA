<?php
require("conf.php");
date_default_timezone_set('America/Mexico_City');

$data = file_get_contents('php://input');
$objeto = json_decode($data, true );
$rutaFuente=carpetaBase.$objeto["cliente"]."/";
$archZIP="DM.".date("Y").date("m").date("d").".zip";

$zip = new ZipArchive();
$zip->open($archZIP,ZipArchive::CREATE);

foreach($objeto as $refe => $archivos){
    if($refe!="cliente" and count($archivos)>=1){
        $zip->addEmptyDir($refe);
        foreach ($archivos as $archivo){
            list($carpetaOld,$arch)=explode("/",$archivo);
            $zip->addFile($rutaFuente.$refe."/".$archivo,$refe."/".$arch);
        }  
    }   
}
$zip->close();
header("Content-type: application/zip");
header("Content-disposition: attachment; filename=".$archZIP);
readfile($archZIP);
unlink($archZIP);
?>