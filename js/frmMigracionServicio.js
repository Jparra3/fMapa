var arrInformacion = new Array();

$(function() {
    $("#imgVistaPrevia").hide();
    $("#imgGuardar").hide();
    visualizartabla();
    $("#filArchivo").change(
            function() {
                $("#imgVistaPrevia").show();
                $("#imgGuardar").hide();
                visualizartabla();
            }
    );
    $("#imgVistaPrevia").bind({
        "click": function() {
            guardarTxtMigracion();
        }
    });
    $("#imgLimpiar").bind({
        "click":function(){
            limpiarFormulario();
        }
    });
    $("#imgGuardar").bind({
        "click": function() {
            if (validarVacios(document.fbeFormulario) == false)
                return false;
            alertaMigrarInformacion();
        }
    });
});
function guardarTxtMigracion() {
    var retorno = true;
    var archivos = document.getElementById("filArchivo");
    var archivo = archivos.files;
    var data = new FormData();
    for (i = 0; i < archivo.length; i++) {
        data.append('archivo' + i, archivo[i]);
    }
    $.ajax({
        async: false,
        url: localStorage.modulo + 'ajax/migracion.subirArchivoMigracion.php',
        type: 'POST',
        dataType: "json",
        contentType: false,
        data: data,
        processData: false,
        cache: false,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;
            var ruta = json.ruta;

            if (exito == 0) {
                alerta(mensaje);
                retorno = false;
            }
            cargarVistaPrevia(ruta);
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
    return retorno;
}
function cargarVistaPrevia(ruta) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'ajax/migracion.leerArchivoMigracionServicio.php',
        type: 'POST',
        data: {ruta: ruta},
        dataType: "json",
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
            }
            if(json.data != null){
                mostrarVistaPrevia(json);
            }
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function mostrarVistaPrevia(json){
    $("#consultaTabla").html("");
    var tabla = "";
    tabla += "<tr>";
    tabla += "<th> # </th>";
    for(var i = 0; i < json.tTable.length; i++){
        tabla += "<th> "+json.tTable[i]+" </th>";
    }
    tabla += "</tr>";
    $.each(json.data, function(contador, fila){
        tabla += "<td class='valorNumerico'> "+parseInt(contador+1)+" </td>";
        tabla += "<td class='valorTexto'> "+fila.nombreServicio+" </td>";
        tabla += "<td class='valorTexto'> "+fila.codigoServicio+" </td>";
        tabla += "<td class='valorNumerico'> "+agregarSeparadorMil(parseInt(fila.valorServicio).toString())+" </td>";
        tabla += "</tr>";
    });
    $("#consultaTabla").html(tabla);
    $("#imgVistaPrevia").hide();
    $("#imgGuardar").show();
    arrInformacion = JSON.stringify(json.data);
}
function visualizartabla(){
    var tabla = "";
    tabla += "<tr>";
    tabla += "<th> # </th>";
    tabla += "<th> Servicio </th>";
    tabla += "<th> Código </th>";
    tabla += "<th> Valor </th>";
    tabla += "</tr>";
    tabla += "<tr>";
    tabla += "<td> &nbsp; </td>";
    tabla += "<td> &nbsp; </td>";
    tabla += "<td> &nbsp; </td>";
    tabla += "<td> &nbsp; </td>";
    tabla += "</tr>";
    $("#consultaTabla").html(tabla);
    paginacion("consultaTabla");
}

function registrarInformacionMigracion(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/migracionServicio.adicionarInformacion.php',
        type: 'POST',
        data: {arrInformacion:arrInformacion},
        dataType: "json",
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
            }
            alerta(mensaje);
            limpiarFormulario();
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function limpiarFormulario(){
    $("#filArchivo").val("");
    visualizartabla();
    $("#imgVistaPrevia").hide();
    $("#imgGuardar").hide();
    arrInformacion = new Array();
}

function alertaMigrarInformacion(){
    bootbox.dialog({
    message: "Esto puede tardar unos minutos.",
    title: "Información",
    buttons: {
      main: {
        label: "Ok",
        className: "btn-primary",
        callback: function() {
            registrarInformacionMigracion();
        }
      }
    }
  });
  
}