<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Visualizador de Imágenes</title>
    
    <script src="js/jquery.js" type="text/javascript"></script>
    <script>
        // Variables globales
        var refPrevio = '';
        var isLoading = false;
        var currentImage = null;
        var currentScale = 1;
        var currentRotation = 0;
        var startX, startY, offsetX = 0, offsetY = 0;
        var isDragging = false;
        
        /**
         * Sanitiza una URL para prevenir XSS
         */
        function sanitizeUrl(url) {
            if (!url) return '';
            return url.replace(/["'<>]/g, '');
        }
        
        /**
         * Muestra u oculta el indicador de carga
         */
        function toggleLoading(show) {
            if (show) {
                $('#mostrar').html('<div class="loading">Cargando...</div>');
                isLoading = true;
            } else {
                isLoading = false;
            }
        }
        
        /**
         * Carga la lista de fotos desde una URL
         */
        function listaFotos(url) {
            if (isLoading) return;
            
            var safeUrl = sanitizeUrl(url);
            toggleLoading(true);
            
            $("#archivos").load(safeUrl + " tbody", function(response, status, xhr) {
                toggleLoading(false);
                
                if (status === "error") {
                    $("#archivos").html('<div class="error">Error al cargar las fotos</div>');
                    return;
                }
                
                var $rows = $("#archivos tr");
                datos = $rows.length - 1;
                
                $rows.each(function(i) {
                    if (i < 3 || i == datos) {
                        $(this).remove();
                    } else {
                        $(this).find("td").each(function(j) {
                            if (j != 1) {
                                $(this).remove();
                            } else {
                                var $link = $(this).find("a");
                                var oldHref = $link.attr("href");
                                if (oldHref) {
                                    $link.attr("href", 'javascript:muestraFotos("' + safeUrl + sanitizeUrl(oldHref) + '")');
                                }
                            }
                        });
                    }
                });
            }).fail(function() {
                toggleLoading(false);
                $("#archivos").html('<div class="error">Error al cargar el directorio</div>');
            });
        }
        
        /**
         * Crea los controles para la imagen
         */
        function createImageControls() {
            const controls = `
                <div class="image-controls">
                    <button class="control-btn zoom-in" title="Zoom In">+</button>
                    <button class="control-btn zoom-out" title="Zoom Out">-</button>
                    <button class="control-btn rotate-left" title="Rotar izquierda">?</button>
                    <button class="control-btn rotate-right" title="Rotar derecha">?</button>
                    <button class="control-btn reset" title="Restablecer">?</button>
                    <button class="control-btn move" title="Mover">?</button>
                </div>
            `;
            return $(controls);
        }
        
        /**
         * Aplica las transformaciones a la imagen
         */
        function applyTransforms() {
            if (currentImage) {
                currentImage.css({
                    'transform': `translate(${offsetX}px, ${offsetY}px) scale(${currentScale}) rotate(${currentRotation}deg)`,
                    'transition': 'transform 0.2s ease'
                });
            }
        }
        
        /**
         * Muestra una foto individual
         */
        function muestraFotos(url) {
            if (isLoading) return;
            
            var safeUrl = sanitizeUrl(url);
            toggleLoading(true);
            
            $("#mostrar").empty();
            currentImage = $('<img>').attr({
                'src': safeUrl,
                'alt': 'Imagen cargada',
                'class': 'imagen-cargada'
            }).on('error', function() {
                $(this).attr('src', 'placeholder.jpg');
                $(this).after('<div class="error">No se pudo cargar la imagen</div>');
            }).on('load', function() {
                toggleLoading(false);
                currentScale = 1;
                currentRotation = 0;
                offsetX = 0;
                offsetY = 0;
                
                const controls = createImageControls();
                $("#mostrar").append(controls);
                $("#mostrar").append(currentImage);
                
                // Eventos para los controles
                $('.zoom-in').click(function() {
                    currentScale += 0.2;
                    applyTransforms();
                });
                
                $('.zoom-out').click(function() {
                    if (currentScale > 0.3) {
                        currentScale -= 0.2;
                        applyTransforms();
                    }
                });
                
                $('.rotate-left').click(function() {
                    currentRotation -= 90;
                    applyTransforms();
                });
                
                $('.rotate-right').click(function() {
                    currentRotation += 90;
                    applyTransforms();
                });
                
                $('.reset').click(function() {
                    currentScale = 1;
                    currentRotation = 0;
                    offsetX = 0;
                    offsetY = 0;
                    applyTransforms();
                });
                
                $('.move').click(function() {
                    $(this).toggleClass('active');
                    if ($(this).hasClass('active')) {
                        currentImage.css('cursor', 'move');
                        currentImage.on('mousedown', startDrag);
                    } else {
                        currentImage.css('cursor', 'default');
                        currentImage.off('mousedown');
                    }
                });
                
                applyTransforms();
            });
            
            currentImage.on('mousedown', startDrag);
        }
        
        /**
         * Inicia el arrastre de la imagen
         */
        function startDrag(e) {
            if (!$(e.target).hasClass('imagen-cargada')) return;
            
            isDragging = true;
            startX = e.clientX - offsetX;
            startY = e.clientY - offsetY;
            
            $(document).on('mousemove', dragImage);
            $(document).on('mouseup', stopDrag);
            
            e.preventDefault();
        }
        
        /**
         * Arrastra la imagen
         */
        function dragImage(e) {
            if (!isDragging) return;
            
            offsetX = e.clientX - startX;
            offsetY = e.clientY - startY;
            
            applyTransforms();
        }
        
        /**
         * Detiene el arrastre de la imagen
         */
        function stopDrag() {
            isDragging = false;
            $(document).off('mousemove', dragImage);
            $(document).off('mouseup', stopDrag);
        }
        
        /**
         * Lee la estructura de carpetas
         */
        function leeFotos(p) {
            var $rows = $("#carpetas tr");
            datos = $rows.length - 1;
            
            $rows.each(function(i) {
                if (i < 3 || i == datos) {
                    $(this).remove();
                } else {
                    $(this).find("td").each(function(j) {
                        if (j > 1) {
                            $(this).remove();
                        }
                        if (j == 1) {
                            var $link = $(this).find("a");
                            var oldHref = $link.attr("href");
                            if (oldHref) {
                                $link.attr("href", 'javascript:listaFotos("' + p + "/" + sanitizeUrl(oldHref) + '")');
                            }
                        }
                    });
                }
            });
        }
        
        // Document ready
        $(document).ready(function() {
            // Sanitizar entrada PHP
            refPrevio = '<?php echo isset($_POST["previo"]) ? htmlspecialchars($_POST["previo"], ENT_QUOTES) : "" ?>';
            
            var p = "e-previos/" + refPrevio;
            var estado = "";
            
            if (refPrevio) {
                $("#carpetas").load(p + " tbody", function(response, status, xhr) {
                    estado = status;
                    if (status != 'error') {
                        leeFotos(p);
                    } else {
                        p = pathFotosNvo;
                        $("#carpetas").load(p + " tbody", function(response, status, xhr) {
                            leeFotos(p);
                        }).fail(function() {
                            $("#carpetas").html('<div class="error">No se pudo cargar el directorio</div>');
                        });
                    }
                }).fail(function() {
                    $("#carpetas").html('<div class="error">Error al cargar el directorio principal</div>');
                });
            }
            
            // Delegación de eventos para mejor rendimiento
            $(document).on('click', '#archivos a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href').replace('javascript:muestraFotos("', '').replace('")', '');
                muestraFotos(url);
            });
        });
    </script>
    
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        } 
        
        a {
            font-size: 11px;
            color: #0066cc;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        #principal {
            width: 30%;
            height: 100%;
            overflow: auto;
            float: left;
            background: #f5f5f5;
            border-right: 1px solid #ddd;
        }
        
        #carpetas {
            width: 100%;
            height: 70px;
            overflow: auto;
            padding: 5px;
            box-sizing: border-box;
        }
        
        #archivos {
            width: 100%;
            height: calc(100% - 70px);
            overflow: auto;
            padding: 5px;
            box-sizing: border-box;
        }
        
        #mostrar {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 70%;
            height: 100%;
            overflow: auto;
            float: right;
            background: #eee;
        }
        
        .imagen-cargada {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transform-origin: center center;
            cursor: default;
        }
        
        .loading, .error {
            padding: 10px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .error {
            color: #d9534f;
        }
        
        tr:hover {
            background-color: #e9e9e9;
        }
        
        .image-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            background: rgba(255,255,255,0.8);
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .control-btn {
            width: 30px;
            height: 30px;
            margin: 2px;
            border: none;
            border-radius: 3px;
            background: #f0f0f0;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .control-btn:hover {
            background: #ddd;
        }
        
        .control-btn.active {
            background: #0066cc;
            color: white;
        }
        
        .zoom-in, .zoom-out {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="principal">
        <div id="carpetas"></div>
        <div id="archivos"></div>       
    </div>
    <div id="mostrar"></div>
</body>
</html>
