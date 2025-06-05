<?php

/*Directorio Activo*/
define('DOMINIO', 'aapascal.net');
define('DN', 'dc=aapascal,dc=net');

/*Base de Datos*/
define('usuario', 'sysdba');
define('passwd', 'masterkey');
define('server', '192.168.0.199:Pascal-Casa');

/*Direccion bae de carpetas*/
//define('carpetaBase', '../../Gestion/');
define('carpetaBase', '/var/Gestion/');

/*Directorios*/ 
define('carpetaOperativa', '06-OPERATIVA/');
define('fotosPrevios', '07-FOTOS-PREVIO/');

/*Mensajes*/
define('RmsgCabecera', '# referencias');
define('RmsgPie', 'Operaciones en el per&iacute;odo<br>#periodo');
define('CGmsgCabecera', 'Cuenta de Gastos');
define('CGmsgPie', '# Archivos');
define('EAmsgCabecera', 'Expediente Aduanal');
define('EAmsgPie', '# Archivos');
define('CFmsgCabecera', 'Comprobantes Fiscales');
define('CFmsgPie', '# Archivos');
define('CmsgCabecera', 'COVE\'s');
define('CmsgPie', '# Archivos');
define('APmsgCabecera', 'Anexos pedimento-edocs');
define('APmsgPie', '# Archivos');
define('EDmsgCabecera', 'Expediente Digital');
define('EDmsgPie', '# Archivos');
?>
