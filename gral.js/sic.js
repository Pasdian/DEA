var config;
$(function() {

    $("#calendario").datepicker({
        minDate: '-1Y',
        maxDate: '-1D',
        onSelect: function(dataText) {
            actualizaFecha(dataText.replace("/", "").replace("/", ""));
        }
    });

    $.ajax({
        data: { "archivo": "../gral.js/conf_sic.json" },
        type: "POST",
        dataType: "json",
        url: '../gral.php/transJSON.php',
        success: function(respuesta) {

            config = respuesta[$("#cliente").text()];
            if (typeof config != "undefined") {
                /*Preparar la GUI*/
                config["cliente"]["numero"] = $("#cliente").text();
                config["cliente"]["nombre"] = $("#empresa").text();
                config["usuario"] = $("#usuario").text();
                config["fecha"] = $("#calendario").val().replace("/", "").replace("/", "");

                $("#contenedor").css("background-image", "url(" + config["backgraund"] + ")");
                if (typeof config["path"]["excel"] != "undefined")
                    $(".icon-file-excel").css("display", "");
                if (typeof config["path"]["pdf"] != "undefined")
                    $(".icon-file-pdf").css("display", "");
                if (typeof config["path"]["cargue"] != "undefined")
                    $(".icon-cargue").css("display", "");
            } else {
                $("main").html("");
            }

        },
        error: function() {
            console.log("No se ha podido obtener la información");
        }
    });

    $("#correos").on("click", function() {

    });

    $("#control").on("click", function() {

    });

    $("#descarga").on("click", function() {
        mostrarExtra("#extra");
        mostrarExtra("#descargas");
    });

    $(".icon-file-pdf").on("click", function() {
        limpiaCont();
        $(".icon-file-excel").css('font-size', '30px');
        $(".icon-file-pdf").css('font-size', '60px');
        $("#extra").load("../gral.php/listArchSIC.php?fecha=" + config["fecha"] + "&dir=" + config["path"]["pdf"]);
        config["seleccion"] = "pdf";
    });

    $(".icon-file-excel").on("click", function() {
        limpiaCont();
        $(".icon-file-excel").css('font-size', '60px');
        $(".icon-file-pdf").css('font-size', '30px');
        $("#extra").load("../gral.php/listArchSIC.php?fecha=" + config["fecha"] + "&dir=" + config["path"]["excel"]);
        config["seleccion"] = "excel";
    });

    $(".icon-cargue").on("click", function() {
        limpiaCont();
        $("#extra").load("../gral.php/listArchSIC2.php?fecha=" + config["fecha"] + "&dir=" + config["path"]["cargue"] + config["fecha"]);
        config["seleccion"] = "cargue";
    });

    $(".icon-download").on("click", function() {
        Archivos = [];
        $("#extra a").each(function() {
            Archivos = Archivos.concat([$(this).text()]);
        });
        $("#extra").load("../gral.php/zipCargue.php?ruta=" + config["path"]["cargue"] + "&fecha=" + config["fecha"]);
    });

});

function limpiaCont() {
    $(".icon-file-excel").css('font-size', '30px');
    $(".icon-file-pdf").css('font-size', '30px');
    $("#visor").attr("src", "");
    $("#extra").html("");
    config["seleccion"] = "";
}

function actualizaFecha(v) {
    config["fecha"] = v;
    if ($("#descargas").css("display") !== "none") {
        if (config["seleccion"] === 'excel') {
            $(".icon-file-excel").click();
        }
        if (config["seleccion"] === 'pdf') {
            $(".icon-file-pdf").click();
        }
        if (config["seleccion"] === 'cargue') {
            $(".icon-cargue").click();
        }
    }
}

$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'yy/mm/dd',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};

$.datepicker.setDefaults($.datepicker.regional['es']);

function mostrarExtra(capa) {
    limpiaCont();
    if ($(capa).css("display") === "none")
        $(capa).css("display", "");
    else
        $(capa).css("display", "none");

    if ($(capa).hasClass("000259")) {
        //$("#extra").load("../gral.php/listArchSIC2.php?dir=../CorreosAutomaticos/Documentos/TRANSBEL/");
    }
}

function mostrarVisor(url) {
    $("#visor").attr("src", url);
}