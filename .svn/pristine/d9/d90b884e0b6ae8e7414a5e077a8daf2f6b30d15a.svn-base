var idCliente = null;
var idVendedor = null;
var arrInformacionExportar = new Array();

$(function() {
    cargarZonas();
    autoCompletarVendedor();
    autoCompletarCliente();
    cargarTipoServicio();
    cargarEstadoPedido();
    borrarIdCliente();
    borrarIdVendedor();
    onClickLimpiar();
    crearListado();
    onClickConsultar();
    crearMultiselectOrden();
    onClickExportar();
});

function onClickExportar(){
    $("#imgExportar").hide();
    $("#imgExportar").bind({
        "click":function (){
            exportarExcel();
        }
    });
}

function onClickConsultar() {
    $("#imgConsultar").bind({
        "click": function() {
            var data = obtenerValorEnvio();
            consultar(data);
        }
    });
}

function exportarExcel() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/pedido.exportarListadoPedido.php',
        type: 'POST',
        dataType: "json",
        data: {arrInformacionExportar:JSON.stringify(arrInformacionExportar)
                , localStorage:localStorage.modulo},
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                limpiarControles();
                alerta("No se encontraron registros con los parámetros indicados.");
                return false;
            }
            window.open(json.ruta);
            limpiarControles();
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function autoCompletarVendedor() {
    $("#txtVendedor").autocomplete({
        source: localStorage.modulo + 'ajax/vendedor.autocompletar.php',
        select: function(event, ui) {
            idVendedor = ui.item.idVendedor;
            $('#txtNitVendedor').val(ui.item.numeroIdentificacion);
        }
    });
}
function autoCompletarCliente() {
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            $("#txtNit").val(ui.item.nit);
            idCliente = ui.item.idCliente;
        }
    });
}

function cargarZonas() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/zona.consultar.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }
            var control = $('#selZona');
            control.empty();

            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idZona + '">' + fila.zonaRegional + '</option>');
            });

            $('#selZona').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });

            $("button.multiselect").css("width", "300px");

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function borrarIdVendedor() {
    $("#txtVendedor").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                idVendedor = null;
                break;
        }
    });
}

function borrarIdCliente() {
    $("#txtCliente").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                idCliente = null;
                break;
        }
    });
}

function onClickLimpiar() {
    $("#imgLimpiar").click(function() {
        limpiarControles();
    });
}

function crearListado() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Cliente - Sucursal </th>";
    html += "<th> Vendedor </th>";
    html += "<th> Zona </th>";
    html += "<th> Orden de trabajo </th>";
    html += "<th> Tipo servicio </th>";
    html += "<th> Estado servicio </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "</tr>";

    $('#consultaTabla').html(html);
}

function cargarTipoServicio() {
    $('#selTipoPedido').multiselect({
        maxHeight: 600
        , nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");
}
function cargarEstadoPedido() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/estadoPedido.consultar.php',
        data: null,
        dataType: "json",
        type: 'POST',
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                var control = $("#selEstadoPedido");
                control.empty();
                return false;
            }
            var control = $("#selEstadoPedido");
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEstadoPedido + '">' + fila.estadoPedido + '</option>');
            });
            $('#selEstadoPedido').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });
            $("button.multiselect").css("width", "300px");
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}

function obtenerValorEnvio() {
    var data = "";
    data += "idCliente=" + idCliente;
    data += "&idZona=" + $("#selZona").val();
    data += "&idVendedor=" + idVendedor;
    data += "&tipoPedido=" + $("#selTipoPedido").val();
    data += "&idEstadoPedido=" + $("#selEstadoPedido").val();
    data += "&tieneOrdenTrabajo=" + $("#selTieneOrden").val();
    return data;
}

function consultar(data) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/pedido.reporteClientePedido.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                crearListado();
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta("No se encontraron registros con los parámetros indicados");
                limpiarControles();
                return false;
            }
            arrInformacionExportar = json.data;
            visualizarReporte(json);
            $("#imgExportar").show();
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function visualizarReporte(json) {
    if (json.data != null) {
        var html = "";
        html += "<tr>";
        html += "<th> # </th>";
        html += "<th> Cliente - Sucursal </th>";
        html += "<th> Vendedor </th>";
        html += "<th> Servicio </th>";
        html += "<th> Zona </th>";
        html += "<th> Orden de trabajo </th>";
        html += "<th> Tipo servicio </th>";
        html += "<th> Estado servicio </th>";
        html += "</tr>";
        $.each(json.data, function(contador, fila) {
            html += "<tr>";
            html += "<td class='valorFijo'> " + parseInt(contador + 1) + " </td>";
            html += "<td class='valorTexto'> " + fila.tercero + " - " + fila.sucursal + " </td>";
            html += "<td class='valorTexto'> " + fila.nombreVendedor + " </td>";
            html += "<td class='valorTexto'> " + fila.servicio + " </td>";
            html += "<td class='valorTexto'> " + fila.zona + " </td>";
            if (fila.idOrdenTrabajo != "" && fila.idOrdenTrabajo != null && fila.idOrdenTrabajo != "null") {
                html += "<td class='valorTexto'> Si </td>";
            } else {
                html += "<td class='valorTexto'> No </td>";
            }
            html += "<td class='valorTexto'> " + fila.tipo + " </td>";
            html += "<td class='valorTexto'> " + fila.pedidoEstado + " </td>";
            html += "</tr>";
        });
        $('#consultaTabla').html(html);
    }
}

function limpiarControles() {
    $('#selZona').val("");
    $('#selZona').multiselect('destroy');
    $('#selZona').multiselect({
        nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $('#selTipoPedido').val("");
    $('#selTipoPedido').multiselect('destroy');
    $('#selTipoPedido').multiselect({
        nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $('#selEstadoPedido').val("");
    $('#selEstadoPedido').multiselect('destroy');
    $('#selEstadoPedido').multiselect({
        nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    
    $('#selTieneOrden').val("");
    $('#selTieneOrden').multiselect('destroy');
    $('#selTieneOrden').multiselect({
        nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");
    
    $("#imgExportar").hide();
    arrInformacionExportar = new Array();
    
    crearListado();
    limpiarControlesFormulario(document.fbeFormulario);
    idCliente = null;
    idVendedor = null;
}

function crearMultiselectOrden(){
    $('#selTieneOrden').val("");
    $('#selTieneOrden').multiselect('destroy');
    $('#selTieneOrden').multiselect({
        nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");
}