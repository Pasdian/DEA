<?php 
require("conf.php");
require("objetos.php");
try
{
  $usr=$_GET["usuario"];
  $pwd=$_GET["password"];
  echo "usr ".$usr." pwd ".$pwd;
  #$AD=new LDAP($_POST["usr"],$_POST["pwd"]);
  $AD=new LDAP($usr,$pwd);
  $reg=array();
  $reg["usuario"]=$AD->Usuario(); 
  $reg["nombre"]=$AD->Nombre();
  $reg["Auth"]=$AD->Autentificado();
  $temp=array();
  $temp2=array();
  print_r($reg);
  foreach($AD->Grupos() as $k=>$v){
        if(preg_match('/#/',$v)){
                $temp[]=preg_replace('([^A-Za-z0-9ñÃ)','',$v);
        }
        if(preg_match('/%/',$v)){
                $temp2[]=preg_replace('([^A-Za-z0-9ñÃ)','',$v);
        }
  }

  $reg["grupos"]=$temp;
  $reg["gruposSIC"]=$temp2;
  $reg["periodo"]=array();
  $reg["periodo"]['desde']='';
  $reg["periodo"]['hasta']='';
  $reg["clienteAct"]=$temp[0];

} catch(Exception $e)
{
  echo "Excepcion ". $e->getMessage()."\n";
}
?>
