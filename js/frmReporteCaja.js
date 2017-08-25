var fechaInicio = null;
var fechaFin = null;
var idConcepto = null;
var idTercero = null;
var data = null;
var jsDataPagosCaja = null;
var idEmpresa = null;

$(function(){
    $("#imgDescargar").hide();
    crearCalendario("txtFechaInicial");
    crearCalendario("txtFechaFinal");
    cargarConcepto();
    autoCompletarCliente();
    crearTabla();
    $("#imgLimpiar").bind({
        "click":function(){
            limpiarVariable();
        }
    });
    $("#imgConsultar").bind({
        "click":function(){
            obtenerDatosEnvio();
            consultarPagoAsociado();
        }
    });
    $("#imgDescargar").bind({
        "click":function(){
            generarReporte();
        }
    });
});

function crearTabla(){
    var html = "";
    html += "<table>";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Asociado </th>";
    html += "<th> Fecha </th>";
    html += "<th> Concepto </th>";
    html += "<th> Valor </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "</tr>";
    html += "</table>";
    $("#consultaTabla").html(html);
}
function limpiarVariable(){
    $('#selConcepto').multiselect('destroy');
    $('#selConcepto').val("");
    $('#selConcepto').multiselect({
        maxHeight: 600
        ,nonSelectedText: '--Seleccione--'	
        ,enableFiltering: true
        ,filterPlaceholder: 'Buscar'
        ,enableCaseInsensitiveFiltering: true
    });
    $("#imgDescargar").hide();
    fechaInicio = null;
    fechaFin = null;
    idConcepto = null;
    idTercero = null;
    idEmpresa = null;
    crearTabla();
    $("#txtFechaInicial").val("");
    $("#txtFechaFinal").val("");
    $("#txtCliente").val("");
    $("#txtEmpresa").val("");
}
function cargarConcepto(){
    $.ajax({
        async: false
        , url: localStorage.modulo + 'controlador/concepto.consultar.php'
        , type: "POST"
        , dataType: "json"
        , data: {estado: true}
        , success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;
            if (json.numeroRegistros == 0) {
                alerta(mensaje);
                return;
            }

            var control = $("#selConcepto");
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append("<option value='" + fila.idConcepto + "'> " + fila.tipoDocumento + " </option>");
            });

            //Montamos el multiSelect
            $('#selConcepto').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });
        }
        , error: function(jqXHR, textStatus, errorThrown) {
            alerta(errorThrown);
        }
    });
}
function autoCompletarCliente(){
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autoCompletar.php',
        select:function(event, ui){
            idTercero = ui.item.idTercero;
            var tercero = ui.item.tercero;
            $("#txtCliente").val(ui.item.tercero);
        }
    });
}
function consultarPagoAsociado(){
    $.ajax({
          async: false
        , url: localStorage.modulo + 'controlador/transaccion.consuPagoAsoci.php'
        , type:"POST"
        , dataType: "json"
        , data:data
        , success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;
            if (json.numeroRegistros == 0) {
                limpiarVariable();
                alerta("No se obtuvieron registros con los parametros indicados");
                return;
            }
            jsDataPagosCaja = json;
            var html = "";
            html += "<table>";
            html += "<tr>";
            html += "<th> # </th>";
            html += "<th> Asociado </th>";
            html += "<th> Fecha </th>";
            html += "<th> Concepto </th>";
            html += "<th> Valor </th>";
            html += "</tr>";
            $.each(json.data, function(contador, fila) {
                html += "<tr>";
                html += "<td style='text-align:center;'> " + parseInt(contador + 1) + " </td>";
                html += "<td style='text-align:right;'> " + fila.nit + " - " + fila.tercero + " </td>";
                html += "<td style='text-align:center;'> " + fila.fecha + " </td>";
                html += "<td style='text-align:left;'> " + fila.tipoDocumento + " </td>";
                html += "<td style='text-align:right;'> $" + agregarSeparadorMil(parseInt(fila.valor).toString()) + " </td>";
                html += "</tr>";
            });
            html += "</table>";
            $("#consultaTabla").html(html);
            $("#imgDescargar").show();
        }
        , error: function(jqXHR, textStatus, errorThrown) {
            alerta(errorThrown);
        }
    });
}
function generarReporte(){
    $.ajax({
          async: false
        , url: localStorage.modulo + 'controlador/caja.generarReporte.php'
        , type:"POST"
        , dataType: "json"
        , data:{
            data:jsDataPagosCaja
            , fechaInicio:$("#txtFechaInicial").val()
            , fechaFin:$("#txtFechaFinal").val()
            , localStorage:localStorage.modulo
        }
        , success: function(json) {
                var exito = json.exito;
                var mensaje = json.mensaje;
                if(json.numeroRegistros == 0){
                    alerta(mensaje);
                    return;
                }
                window.open(json.ruta);   
                alerta(mensaje);
          }
        ,error: function(jqXHR, textStatus, errorThrown) {
            alerta(errorThrown);
        }
    });
}
function obtenerDatosEnvio(){
    $("#imgDescargar").hide();
    jsDataPagosCaja = null;
    data = "";
    data += "idTercero="+idTercero;
    data += "&fechaInicial="+$("#txtFechaInicial").val();
    data += "&fechaFinal="+$("#txtFechaFinal").val();
    data += "&idConcepto="+$("#selConcepto").val();
    
}

function autoCompletarEmpresa(){
    $("#txtEmpresa").autocomplete({
        source: localStorage.modulo + 'ajax/empresa.autoCompletar.php',
        select:function(event, ui){
            idEmpresa = ui.item.idEmpresa;
            var tercero = ui.item.tercero;
            $("#txtEmpresa").val(ui.item.tercero);
        }
    });
}
