<?php 
//ini_set('display_errors', E_ALL);
require("conf.php");
require("objetos.php");

$Carpetas = Carpetas::get();
$objeto=$_POST["objJson"];
//echo $objeto["buscar"].$objeto["buscarCadena"];
$carpetas=Array();
$archivos=Array();
$mcte=is_array($objeto["clienteAct"]) ? $objeto["clienteAct"][0] : $objeto["clienteAct"];
$periodo=" FEC_ENTR BETWEEN '".(is_array($objeto["periodo"]["desde"]) ? $objeto["periodo"]["desde"][0] : $objeto["periodo"]["desde"])."' AND '".(is_array($objeto["periodo"]["hasta"]) ? $objeto["periodo"]["hasta"][0] : $objeto["periodo"]["hasta"])."'";
$cliente=" AND CVE_IMPO LIKE '".$mcte."'";
//$sql="SELECT NUM_REFE FROM SAAIO_PEDIME WHERE".$periodo.$cliente;
$sql="SELECT NUM_REFE FROM CTRAO_EMBAR WHERE".$periodo.$cliente;
$sql2="SELECT NOM_IMP, RFC_IMP FROM CTRAC_CLIENT WHERE CVE_IMP='".$mcte."'";

/*$sql30="SELECT ADU_DESP FROM SAAIO_PEDIME WHERE".$periodo.$cliente." GROUP BY ADU_DESP";
$sql31="SELECT CVE_PEDI FROM SAAIO_PEDIME WHERE".$periodo.$cliente." GROUP BY CVE_PEDI";
$sql32="SELECT IMP_EXPO FROM SAAIO_PEDIME WHERE".$periodo.$cliente." GROUP BY IMP_EXPO";*/

$sql30="SELECT ADU_DESP FROM CTRAO_EMBAR WHERE".$periodo.$cliente." GROUP BY ADU_DESP";
$sql31="SELECT CVE_PEDI FROM CTRAO_EMBAR WHERE".$periodo.$cliente." GROUP BY CVE_PEDI";
$sql32="SELECT IMP_EXPO FROM CTRAO_EMBAR WHERE".$periodo.$cliente." GROUP BY IMP_EXPO";

if($objeto["buscar"]>0){
    //$sql="SELECT NUM_REFE FROM SAAIO_PEDIME WHERE (NUM_REFE LIKE '%".$objeto["buscarCadena"]."%' OR NUM_PEDI LIKE '%".$objeto["buscarCadena"]."%') AND CVE_IMPO='".$mcte."'
    $sql="SELECT NUM_REFE FROM CTRAO_EMBAR WHERE NUM_REFE LIKE '%".$objeto["buscarCadena"]."%' AND CVE_IMPO='".$mcte."'
    UNION
    SELECT NUM_REFE FROM SAAIO_PEDIME WHERE (NUM_REFE LIKE '%".$objeto["buscarCadena"]."%' OR NUM_PEDI LIKE '%".$objeto["buscarCadena"]."%') AND CVE_IMPO='".$mcte."'
    UNION
    SELECT CP.NUM_REFE FROM CTRAO_PEDIDO CP JOIN SAAIO_PEDIME SP ON SP.NUM_REFE=CP.NUM_REFE WHERE CP.NUM_TRAF LIKE  '%".$objeto["buscarCadena"]."%' AND CVE_IMPO='".$mcte."'
    UNION
    SELECT SF.NUM_REFE FROM SAAIO_FACPAR SF JOIN SAAIO_PEDIME SP ON SP.NUM_REFE=SF.NUM_REFE WHERE NUM_PART LIKE  '%".$objeto["buscarCadena"]."%' AND CVE_IMPO='".$mcte."'
    UNION
    SELECT SF.NUM_REFE FROM SAAIO_FACTUR SF JOIN SAAIO_PEDIME SP ON SP.NUM_REFE=SF.NUM_REFE WHERE NUM_FACT LIKE  '%".$objeto["buscarCadena"]."%' AND CVE_IMPO='".$mcte."'
    UNION
    SELECT CF.NUM_REFE FROM CCGO_FOLIOS CF JOIN SAAIO_PEDIME SP ON SP.NUM_REFE=CF.NUM_REFE WHERE FOL_FACT LIKE  '%".$objeto["buscarCadena"]."%' AND CVE_IMPO='".$mcte."'
    UNION
    SELECT SC.NUM_REFE FROM SAAIO_COVESER SC JOIN SAAIO_PEDIME SP ON SP.NUM_REFE=SC.NUM_REFE WHERE NUM_SERI LIKE  '%".$objeto["buscarCadena"]."%' AND CVE_IMPO='".$mcte."'";
}

$objeto["sql"]=$sql;

$FB=new FireBird(usuario,passwd,server);
$resultados=$FB->consulta($sql);
$resCte=$FB->consulta($sql2);
$fa=$FB->consulta($sql30);
$fd=$FB->consulta($sql31);
$fo=$FB->consulta($sql32);
$FB->terminar();

$objeto["referenciasTablaHtml"]='';
$objeto["archivoSeleccionado"]="";
array_splice($objeto["filtros"]["aduanas"], 1);
array_splice($objeto["filtros"]["documentos"], 1);
array_splice($objeto["filtros"]["operacion"], 1);



foreach($resultados as $key => $value){
    foreach($value as $k => $v){
        $Ref[]= $v;
        //echo $v;
    }
}


if(sizeof($resultados)>1){
$RefL=array_unique($Ref);
natcasesort($RefL);
    foreach($RefL as $r){
        $dir=carpetaBase.$mcte.'/'.$r.'/';
        foreach($Carpetas as $c){
            $dirCarp=$dir.$c.'/';
            if(is_dir($dirCarp)){
                $directorio=opendir($dirCarp);
            $archivos=Array();
                while($arch = readdir($directorio)){

                    if(is_dir($dirCarp.$arch)){

                    }else{
                        $archivos[]=$arch;
                    }
                }
                natcasesort($archivos);
                $archivos = array_values($archivos);
            }
            $carpetas[$c]=$archivos;
        $archivos=null;
        }
        $objeto["referencias"][$r]=$carpetas;
        $objeto["referenciasTablaHtml"]=$objeto["referenciasTablaHtml"]."<tr><td referencia='$r'><i class='icon-ok' style='color: green;'></i>$r<div style='float: right;'><i class='icon-download'></i></div></td></tr>\n";      
    }    
}

$objeto["clienteActNom"]=utf8_encode($resCte[0]["NOM_IMP"]);

if($objeto["buscar"]<0){
    foreach($fa as $key => $value){
        foreach($value as $k => $v){
            $objeto["filtros"]["aduanas"][]= $v;
            //echo $v."<br>";
        }
    }
    foreach($fd as $key => $value){
        foreach($value as $k => $v){
            $objeto["filtros"]["documentos"][]= $v;
            //echo $v."<br>";
        }
    }
    foreach($fo as $key => $value){
        foreach($value as $k => $v){
            $objeto["filtros"]["operacion"][]= $v;
            //echo $v."<br>";
        }
    }    
}



echo json_encode($objeto);
?>

