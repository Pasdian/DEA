<?php
$archivo=$_POST["archivo"];
//$archivo=$_GET["archivo"];
$datos=file_get_contents($archivo);
$_json= json_decode($datos, true);
header('Content-Type: application/json');
echo json_encode($_json, JSON_FORCE_OBJECT);
?>