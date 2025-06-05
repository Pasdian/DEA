<?php
// Datos de conexión
$usuario = $_POST["Mguevara"]; // Usuario sin el dominio
$contrasena = $_POST["Palemon$1988"];
$dominio = "grupopascal"; // Reemplaza con tu dominio (por ejemplo: CONTOSO)
$servidor_ldap = "ldap://192.168.0.5"; // Reemplaza con tu servidor LDAP o IP

// Conexión al servidor LDAP
$conexion = ldap_connect($servidor_ldap);
ldap_set_option($conexion, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($conexion, LDAP_OPT_REFERRALS, 0);

// Intentar autenticación
$bind = @ldap_bind($conexion, "$dominio\\$usuario", $contrasena);

if ($bind) {
    echo "✅ Autenticación exitosa con el dominio.";
    // Aquí puedes hacer búsquedas LDAP si lo necesitas
} else {
    echo "❌ Error de autenticación: " . ldap_error($conexion);
}

ldap_unbind($conexion);
?>
