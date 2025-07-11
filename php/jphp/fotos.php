<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

Cuerda>
    <script src="js/jquery.js" type="text/javascript"></script>
<script>
    var refPrevio ='';
    function listaFotos(url){
        $("#archivos").load(url+" tbody", function(response, status, xhr){
            datos=$("#archivos tr").length-1;
            $("#archivos tr").each(function(i){
                if(i<3 || i==datos) 
                    this.remove();
                else{
                    $(this).find("td").each(function(i){
                        if(i!=1)
                            this.remove();
                        else{
                            old=$(this).find("a").attr("href");
                            $(this).find("a").attr("href",'javascript:muestraFotos("'+url+old+'")');
                        }
                    })  
                }
            });
        });
    }
    function muestraFotos(url){
        $("#mostrar").empty();
        var imagen=$('<img src="'+url+'" alt="" width="100%";>');
        $("#mostrar").append(imagen);
    }

    var datos;
    $(document).ready(function() {
        refPrevio='<?php echo $_POST["previo"]?>';
        p="e-previos/"+refPrevio;
        estado="";
        if (refPrevio != '' && refPrevio != null)
            $("#carpetas").load(p+" tbody", function(response, status, xhr){
                estado=status;
                if (status!='error'){
                    leeFotos(p);
                } else {
                    p=pathFotosNvo;
                    $("#carpetas").load(p+" tbody", function(response, status, xhr){leeFotos(p);});
                }   
            }); 
    });   

    function leeFotos(p){
        datos=$("#carpetas tr").length-1;
        $("#carpetas tr").each(function(i){
            if(i<3 || i==datos) 
                this.remove();
            else{
                $(this).find("td").each(function(i){
                    if(i>1)
                        this.remove();
                    if(i==1){
                        old=$(this).find("a").attr("href");
                        $(this).find("a").attr("href",'javascript:listaFotos("'+p+"/"+old+'")');
                    }
                })  
            }
        });
    }
    
    
</script>
<style>
    html,body{
        margin: 0;archivos
        padding: 0;
        height: 100%;
    } 
    a{
        font-size: 11px;
    }
    #principal{
        width: 30%;
        height: 100%;
        overflow: auto;
        float: left;
    }
    #carpetas{
        width: 100%;
        height: 70px;
        overflow: auto;
    }
    #archivos{
        width: 100%;
        height: calc(100% - 70px);
        overflow: auto;
    }
    #mostrar{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 70%;
        height: 100%;error system-config-
        overflow: auto;
        float: right;
    }
    
</style>
</head>
<body>
    <div id="principal">
        <div id="carpetas">
            
        </div>
         <div id="archivos">
              
        </div>       
    </div>

    <div id="mostrar">
        
    </div>

</body>
</html>