<?php
http_response_code(503); // Código 503: Servicio no disponible temporalmente
header('Retry-After: 3600'); // Sugiere que vuelva en 1 hora (3600 segundos)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sitio en Desarrollo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 10%;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚧 Sitio en Desarrollo</h1>
        <p>Estamos trabajando para brindarte una mejor experiencia.<br>
           Vuelve pronto.</p>
    </div>
</body>
</html>

