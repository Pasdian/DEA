<?php

require("conf.php");
/*Objeto para conectarse a Active Directory*/

class LDAP {
private $auth=false;
private $a;

function __construct($usr,$pwd) {
	$ldaprdn = trim($usr).'@'.DOMINIO;  
	$ldappass = trim($pwd);  
	$ds = DOMINIO;  
	$dn = DN;   
	$puertoldap = 389;  
	$ldapconn = ldap_connect($ds,$puertoldap); 
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3);  
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0);  
	$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);  
	if ($ldapbind){ 
		$filter="(|(SAMAccountName=".trim($usr)."))"; 
		$fields = array("SAMAccountName"); 
		$sr = @ldap_search($ldapconn, $dn, $filter/*, $fields*/);  
		$info = @ldap_get_entries($ldapconn, $sr);
		$this->a = $info/*[0]["samaccountname"][0]*/;
		if(count($this->a)>0)$this->auth=true;
	}else{  
		$this->a=0; 
	}  
	ldap_close($ldapconn);  			
}
 
function Autentificado() {
	return $this->auth;
}
	
function Usuario() {
	if($this->auth){
		return $this->a[0]["samaccountname"][0];
	} else {
		return "UsuarioInvalido";
	}
}

function Nombre() {
	if($this->auth){
		return $this->a[0]["displayname"][0];
	} else {
		return "UsuarioInvalido";
	}
}

function Grupos() {
	$temp=$this->a[0]["memberof"];
	$matriz=array();
	if($this->auth){
		foreach($temp as $c=>$v){
			if($c=="count") 
				$c=0;				
			$matriz[$c] = substr(str_replace ("CN=","",$v),0,strpos($v,",")-3);	
			
				$matriz[$c]=preg_replace('([^A-Za-z0-9nÑ#%])', '', $matriz[$c]);
		}
	} else {
		$matriz[] = "UsuarioInvalido";
	}
	sort($matriz, 0);
	return $matriz;
}

function __destruct() {}	
}
/*Objeto paraRealizar operaciones en base de datos FireBird*/
class FireBird {
	private $msg="Algo no funciona",$conn;
	function __construct($usr,$pwd,$srv){
		$this->conn=ibase_connect($srv, $usr, $pwd) or die ($this->msg="Acceso Denegado!");
		$this->msg="Acceso concedido!";
	}
	function mensajes(){
		return $this->msg;
	}
	
	function conexion(){
		return $this->conn;
	}	
	
	function consulta($strSQL){
		$resul=array();
		$objFB=ibase_query($this->conn, $strSQL);
		
		while( $resul[]=ibase_fetch_assoc($objFB)){}
        
		return $resul;
	}
    
    function terminar(){
        ibase_close($this->conn);
    }
}
/*Objeto paraRealizar operaciones en base de datos MySQL*/
class MySQL {
	
}
/*Objeto paraRealizar operaciones en base de datos Oracle*/
class Oracle {
	
}
/*Objeto paraRealizar operaciones en base de datos msSQL*/
class msSQL {
	
}

class Carpetas {
    private static $arreglo = array('01-CTA-GASTOS','02-EXPEDIENTE-ADUANAL','03-FISCALES','04-VUCEM','05-EXP-DIGITAL');
    public function get()
    {
       return self::$arreglo;
    } 
}

?>
