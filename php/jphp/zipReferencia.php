<?php
require("conf.php");
//$objeto=$_POST["objJson"];
$msg = "Iniciando zip";
$log_dea="/tmp/log_dea.txt";
error_log($msg,3,$log_dea);
$data = file_get_contents('php://input');
$objeto = json_decode($data, true );
$rutaFinal=carpetaBase."/ZIP/".$objeto["rutaDestino"];
if(!file_exists($rutaFinal)){
  mkdir($rutaFinal);
}
/*var_dump($objeto);
echo $objeto["nombre"];*/

$zip = new ZipArchive();
$zip->open($objeto["nombre"],ZipArchive::CREATE);

foreach ($objeto["archivos"] as $carpeta => $archivos){
    $zip->addEmptyDir($carpeta);
    foreach ($archivos as $archivo){
        $zip->addFile(carpetaBase.$objeto["rutaFuente"].$carpeta."/".$archivo,$carpeta."/".$archivo);
    }   
}

$zip->close();

rename($objeto["nombre"], $rutaFinal."/".$objeto["nombre"]);

header("Content-type: application/zip");
header("Content-disposition: attachment; filename=".$objeto["nombre"]);
readfile($rutaFinal."/".$objeto["nombre"]);
unlink($objeto["nombre"]);

$msg = "Finalizando zip";
error_log($msg,3,$log_dea);
?>
