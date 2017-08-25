var idCliente = null;
var arrInformacionExportar = new Array();
$(function() {
    $("#imgExportar").hide();
    autoCompletarCliente();
    cargarServicio();
    cargarProductos();
    cargarBodegas();
    crearTablaResporte();

    //Controles
    $("#imgConsultar").bind({
        "click": function() {
            var data = obtenerValorEnvio();
            consultar(data);
        }
    });

    $("#imgLimpiar").bind({
        "click": function() {
            limpiarControles();
        }
    });
    $("#imgExportar").bind({
        "click":function (){
            exportarExcel();
        }
    });
});

function exportarExcel() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/inventario.exportarInventarioInstalado.php',
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

function consultar(data) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.consultarInventarioInstalado.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta("No se encontraron registros con los parámetros indicados.");
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
        html += "<th> Código </th>";
        html += "<th> Producto </th>";
        html += "<th> Servicio </th>";
        html += "<th> Cliente - Sucursal</th>";
        html += "<th> Zona </th>";
        html += "<th> Cantidad </th>";
        html += "<th> ($)Valor por unidad </th>";
        html += "<th> ($)Subtotal </th>";
        //html += "<th> idCliente </th>"; Para verificar los colores
        html += "</tr>";
        var clienteAnterior = null;
        var color = "";
        var estilo = "";
        $.each(json.data, function(contador, fila) {
            if (idCliente == null || idCliente == undefined) {
                if (clienteAnterior != fila.idCliente) {
                    clienteAnterior = fila.idCliente;
                    if (color != "") {
                        color = "";
                    } else {
                        color = "#E6E6E6";
                    }
                }
            }
            html += "<tr bgcolor='" + color + "'>";
            html += "<td class='valorFijo'> " + parseInt(contador + 1) + " </td>";
            html += "<td class='valorNumerico'> " + fila.codigo + " </td>";
            html += "<td class='valorTexto'> " + fila.producto + " </td>";
            html += "<td class='valorTexto'> " + fila.servicio + " </td>";
            html += "<td class='valorTexto'> " + fila.cliente + " - " + fila.sucursal + " </td>";
            html += "<td class='valorTexto'> " + fila.zona + " </td>";
            html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(fila.cantidad).toString()) + " </td>";
            html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(fila.valorUnidad).toString()) + " </td>";
            html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(fila.subtotal).toString()) + " </td>";
            //html += "<td class='valorNumerico'> "+fila.idCliente+" </td>";  Para verificar los colores
            html += "</tr>";
        });
        html += "<tr>";
        html += "<td colspan='8' class='valorNumerico'> ($)Total</td>";
        html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(json.data[0].total).toString()) + " </td>";
        html += "</tr>";
        $('#consultaTabla').html(html);
    }
}

function crearTablaResporte() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Código </th>";
    html += "<th> Producto </th>";
    html += "<th> Servicio </th>";
    html += "<th> Cliente - Sucursal </th>";
    html += "<th> Zona </th>";
    html += "<th> Cantidad </th>";
    html += "<th> ($)Valor por unidad </th>";
    html += "<th> ($)Subtotal </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
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

function autoCompletarCliente() {
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            idCliente = ui.item.idCliente;
        }
    });
}

function cargarServicio() {
    var dataProducto = "";
    dataProducto += "estado=TRUE";
    dataProducto += "&productoServicio=FALSE";
    crearSelectServicio(consultarProductoServicio(dataProducto));
}

function cargarProductos() {
    var dataProducto = "";
    dataProducto += "estado=TRUE";
    dataProducto += "&productoServicio=TRUE";
    crearSelectProducto(consultarProductoServicio(dataProducto));
}

function crearSelectServicio(json) {
    var control = $('#selServicio');
    control.empty();

    $.each(json.data, function(contador, fila) {
        control.append('<option value="' + fila.idProducto + '">' + fila.codigo + ' - ' + fila.producto + '</option>');
    });

    $('#selServicio').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $("button.multiselect").css("width", "300px");
}

function crearSelectProducto(json) {
    var control = $('#selProducto');
    control.empty();

    $.each(json.data, function(contador, fila) {
        control.append('<option value="' + fila.idProducto + '">' + fila.codigo + ' - ' + fila.producto + '</option>');
    });

    $('#selProducto').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $("button.multiselect").css("width", "300px");
}

function consultarProductoServicio(data) {
    var jsonServicioProducto = null;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/producto.consultar.php',
        type: 'POST',
        dataType: "json",
        data: data,
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

            jsonServicioProducto = json;
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
    return jsonServicioProducto;
}

function cargarBodegas() {
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function(json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selBodega');
            control.empty();

            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
            $('#selBodega').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });
            $("button.multiselect").css("width", "300px");
        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}

function limpiarControles() {
    limpiarControlesFormulario(document.fbeFormulario);
    $('#selServicio').val("");
    $('#selServicio').multiselect('destroy');
    $('#selServicio').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $('#selProducto').val("");
    $('#selProducto').multiselect('destroy');
    $('#selProducto').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $('#selBodega').val("");
    $('#selBodega').multiselect('destroy');
    $('#selBodega').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");

    idCliente = null;
    $("#imgExportar").hide();
    arrInformacionExportar = new Array();
    crearTablaResporte();
}

function obtenerValorEnvio() {
    var data = "";
    data += "idCliente=" + idCliente;
    data += "&idProducto=" + $("#selProducto").val();
    data += "&idServicio=" + $("#selServicio").val();
    data += "&idBodega=" + $("#selBodega").val();

    return data;
}