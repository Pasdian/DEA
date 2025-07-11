<?php
require("conf.php");

$arch=carpetaBase.$_POST["a"];
$ext=strtoupper(substr($arch,strrpos($arch,".")));
$tipo='" width="100%" height="100%" ';
$string =file_get_contents($arch);
switch ($ext){
	case '.PDF':
        echo '<object id="objVisor" data="'."/Gestion/".$_POST["a"].'" style="width: 100%; height: calc(100% - 5px); margin: 0;" width="100%" height="100%;" type="application/pdf"></object>';
		break;
	case '.XLS':
		//echo '<object id="objVisor" data="'.$arch.'" width="100%" height="100%" type="application/vnd.ms-excel"></object>';
		break;
	case '.XLSX':
		//echo '<object id="objVisor" data="'.$arch.'" width="100%" height="100%" type="application/vnd.ms-excel"></object>';
		break;
	case '.XML':
        $string = str_replace(array('<', '>'),array('&lt;', '&gt;'),$string);
        echo '<pre style="word-wrap: break-word; white-space: pre-wrap;">'.$string.'</pre>';
		break;
	default:
		echo '<object id="objVisor" data="'."/Gestion/".$_POST["a"].'" style="width: 100%; height: calc(100% - 2px); margin: 0;" width="100%" height="100%;" type="text/plain"/>';
        
}
$objeto='<object id="objVisor" " data="'.$arch.'"';
?>






