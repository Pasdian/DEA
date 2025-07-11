<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="../gral.js/jquery.js"></script>
    <script type="text/javascript" src="../gral.js/jquery-ui.js"></script>
    <script type="text/javascript" src="../gral.js/sic.js"></script>

    <link rel="stylesheet" href="../gral.css/_gral.css">
    <title>SIC</title>

</head>
<body>
    <div id="contenedor" class="flex-container">
      <!--?php if($_POST['cte']=='004281' || $_POST['cte']=='000259'){?-->
       <div id="header" style="width: 100%;" >
            <header class="flex-item-3 flex-container flex-fila flex-centrado-v" style="height: 30px;">
                <p class="flex-item-3 borde">Sistema de Informaci&oacute;n Controlada</p>
                <p class="flex-item-3 icon-users borde"> <?php echo "<span id='empresa'>".$_POST['empresa']."</span> (<span id='cliente'>".$_POST['cte']."</span>) "?></p>
                <p class="flex-item-3 icon-user borde"> <?php echo "<span id='usuario'>".$_POST['usr']."</span>"?></p>
            </header>           
       </div>

        <main class="flex-item-3 flex-container flex-fila " style="width: calc(100% - 0px); height: 100%; text-align: left">
            <div class="flex-item-3bis borde" style="height: calc(100% - 12px); width: 300px; overflow-y:auto;">
               <br><br>
                <!--p id="correos" class="icon-mail borde"> Envio </p-->
                <br>
                <p id="control" class="icon-cog-alt borde"> Control de Informaci&oacute;n</p>
                <div id="calendario" class="borde"></div>
                <br>
                <p id="descarga" class="icon-file-archive borde"> Archivos</p>
                    <!--?php if($_POST['cte']=='004281' || $_POST['cte']=='000259'){?-->
                    <div id="descargas" style=" display: none; text-align: center;"><br>
                        <p><i class="icon-file-pdf" style="font-size: 30px;display:none;"><span style="font-size: 20px;">pdf</span></i> 
                        <i class="icon-file-excel" style="font-size: 30px;display:none;"><span style="font-size: 20px;">excel</span></i></p>
                        <p><i class="icon-cargue" style="display:none;"><img src="../gral.img/cargue-icon.png" style="height: 40px; color: #fff;font-size: 30px;"> Cargue</i></p>
                        <!--p><br><i class="icon-download" style="font-size: 30px;display;">Descargar</i></p-->
                    </div>
                    <!--?php }?-->
                <br><br>
                
            </div>
            <div class="flex-item-3bis borde" style="height: calc(100% - 12px); overflow-y:auto;">
                <iframe id="visor"  name ='visor' frameborder="0" width="100%" height="99%" text-align="center"></iframe>
            </div>
            <div id="extra" class="flex-item-3bis borde <?php echo $_POST['cte']?>" style="height: calc(100% - 12px); width: 300px; overflow-y:auto; display: none;">
            </div>
        </main>
        <div id="footer" style="width: 100%; display: none;">
            <footer class="flex-item-3" >
                PIE <input type="checkbox">
            </footer>
        </div>
        <!--?php }?-->
    </div>
</body>
</html>
