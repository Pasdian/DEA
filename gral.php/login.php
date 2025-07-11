<?php 
require("conf.php");
require("objetos.php");

header('Content-Type: application/json');
$AD=new LDAP($_POST["usr"],$_POST["pwd"]);
$reg=array();
$reg["usuario"]=$AD->Usuario(); 
$reg["nombre"]=$AD->Nombre();
$reg["Auth"]=$AD->Autentificado();
$temp=array();
$temp2=array();

foreach($AD->Grupos() as $k=>$v){
	if(preg_match('/#/',$v)){		
		$temp[]=preg_replace('([^A-Za-z0-9ñÑ])','',$v);
	}
	if(preg_match('/%/',$v)){		
		$temp2[]=preg_replace('([^A-Za-z0-9ñÑ])','',$v);
	}
}

$reg["grupos"]=$temp;
$reg["gruposSIC"]=$temp2;
$reg["periodo"]=array();
$reg["periodo"]['desde']='';
$reg["periodo"]['hasta']='';
$reg["clienteAct"]=$temp[0];
$filtros=array();
$filtros['aduanas']=array('TODO');
$filtros['documentos']=array('TODO');
$filtros['operacion']=array('TODO');
$reg["filtros"]=$filtros;
$reg["solicitud"]=getUserIpAddress();
$reg["referenciaSeleccionada"]='';
$referencias=array();
$reg["referencias"]=$referencias;
$mensajes['r']['cabecera']=RmsgCabecera;
$mensajes['r']['pie']=RmsgPie;
$mensajes['cg']['cabecera']=CGmsgCabecera;
$mensajes['cg']['pie']=CGmsgPie;
$mensajes['ea']['cabecera']=EAmsgCabecera;
$mensajes['ea']['pie']=EAmsgPie;
$mensajes['cf']['cabecera']=CFmsgCabecera;
$mensajes['cf']['pie']=CFmsgPie;
$mensajes['c']['cabecera']=CmsgCabecera;
$mensajes['c']['pie']=CmsgPie;
$mensajes['ap']['cabecera']=APmsgCabecera;
$mensajes['ap']['pie']=APmsgPie;
$mensajes['ed']['cabecera']=EDmsgCabecera;
$mensajes['ed']['pie']=EDmsgPie;
$reg["mensajes"]=$mensajes;
$reg["carpetaOperativa"]=carpetaOperativa;
$reg["fotosPrevios"]=fotosPrevios;
$reg["buscar"]=-1;
$reg["buscarCadena"]="";
echo json_encode($reg);

function getUserIpAddress() {
    foreach ( [ 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ] as $key ) {
        // Comprobamos si existe la clave solicitada en el array de la variable $_SERVER 
        if ( array_key_exists( $key, $_SERVER ) ) {
            // Eliminamos los espacios blancos del inicio y final para cada clave que existe en la variable $_SERVER 
            foreach ( array_map( 'trim', explode( ',', $_SERVER[ $key ] ) ) as $ip ) {
                if($ip=='192.168.0.1'){
                    //return $ip;
                    return 'REMOTA '.$ip;
                } else {
                    //return $ip;
                    return 'LOCAL'.$ip;
                }
                // Filtramos* la variable y retorna el primero que pase el filtro
                /*if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {*/
                    /*return $ip;
                }*/
            }
        }
    }
    return '?'; // Retornamos '?' si no hay ninguna IP o no pase el filtro
} 
?>
