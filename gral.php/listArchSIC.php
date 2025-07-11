<?php
$dir=$_GET["dir"];
$fecha=$_GET["fecha"];
$directorio = opendir($dir); 
$plantas=array(
    array("Planta 258",array()),
    array("Planta 303",array()),
    array("Planta 304",array()),
    array("Planta 305",array()),
    array("Planta 259",array()),
    array("Planta 107",array())
);
while ($archivo = readdir($directorio)) 
{
    if (!is_dir($archivo))//verificamos si no es un directorio
    {
        if (strrpos($archivo, $fecha) !== false) {
            $nomArch=explode("_", $archivo);
            if (strrpos(end($nomArch), "xls") !== false) {
                $temp=explode(".", end($nomArch))[0];
                if(is_numeric($temp)){
                    $plantas[$temp-1][1][]="<p><a href='".$dir."/".$archivo."' target='visor'>".$nomArch[1]."</a></p>";
                }
            }
            elseif (strrpos(end($nomArch), "pdf") !== false) {
                $temp=$nomArch[1];
                if(is_numeric($temp)){
                    $plantas[$temp-1][1][]="<p><a href='".$dir."/".$archivo."' target='visor'>".end($nomArch)."</a></p>";
                }
            }            
        }
    }
}
foreach($plantas as $v){
    echo "<br/><fieldset><legend>".$v[0]."</legend>";
    foreach($v[1] as $vv){
        echo "<p align='center'>".$vv."</p>";
    }
    echo "</fieldset>";
}

?>
