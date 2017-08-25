var idCliente = null;
var idVendedor = null;
var idMunicipio = null;
var idTipoServicio = null;
var fechaInicial = null;
var fechaFinal = null;
var idProducto = null;
var data = null;
var arrInformacionExportar = new Array();

$(function() {
    consultarTipoOrdenTrabajo();
    cargarProductos();
    autoCompletarCliente();
    autoCompletarVendedor();
    cargarMunicipios();
    crearCalendario("txtFechaInicial");
    crearCalendario("txtFechaFinal");
    crearTablaResporte();
    $("#imgExportar").hide();
    $("#imgLimpiar").bind({
        "click": function() {
            limpiarControles();
        }
    });

    $("#imgConsultar").bind({
        "click": function() {
            asignarValorEnvio();
            consultar();
        }
    });
    $("#imgExportar").bind({
        "click":function (){
            exportarExcel();
        }
    });
});

function crearTablaResporte() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Servicio </th>";
    html += "<th> Cliente - Sucursal </th>";
    html += "<th> Empresa </th>";
    html += "<th> ($)Valor </th>";
    html += "<th> Tipo de servicio </th>";
    html += "<th> Fecha </th>";
    html += "<th> Municipio </th>";
    html += "<th> Vendedor </th>";
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

function cargarMunicipios() {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/tercero.cargarMunicipio.php',
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
                return false;
            }
            var control = $("#selMunicipio");
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idMunicipio + '">' + fila.municipio + '</option>');
            });

            $('#selMunicipio').multiselect({
                maxHeight: 400
                , nonSelectedText: 'Seleccione'
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

function consultarTipoOrdenTrabajo() {
    var control = $('#selTipoServicio');
    control.empty();
    control.append('<option value="1">Instalación</option>');
    control.append('<option value="2">Mantenimiento</option>');
    $('#selTipoServicio').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");
}

function autoCompletarCliente() {
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            idCliente = ui.item.idCliente;
        }
    });
}

function autoCompletarVendedor() {
    $("#txtVendedor").autocomplete({
        source: localStorage.modulo + 'ajax/vendedor.autocompletar.php',
        select: function(event, ui) {
            idVendedor = ui.item.idVendedor;
        }
    });
}

function cargarProductos() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/producto.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {
            estado: true
            , productoServicio: "FALSE"
        },
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

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function exportarExcel() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/servicio.exportarServicioInstalado.php',
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

function consultar() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicio.consultarServicioInstalados.php',
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
                limpiarControles();
                alerta("No se encontraron registros con los parámetros indicados.");
                return false;
            }
            arrInformacionExportar = json.data;
            $("#imgExportar").show();
            visualizarReporte(json);
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function visualizarReporte(json) {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Servicio </th>";
    html += "<th> Cliente - Sucursal </th>";
    html += "<th> Empresa </th>";
    html += "<th> Estado </th>";
    html += "<th> ($)Valor </th>";
    html += "<th> Tipo de servicio </th>";
    html += "<th> Fecha inicio </th>";
    html += "<th> Fecha fin </th>";
    html += "<th> Municipio </th>";
    html += "<th> Vendedor </th>";
    html += "<th> Técnicos </th>";
    html += "<th> Archivos </th>";
    html += "</tr>";
    $.each(json.data, function(contador, fila) {
        html += "<tr>";
        html += "<td class='valorFijo'> " + parseInt(contador + 1) + " </td>";
        html += "<td class='valorTexto'> " + fila.producto + " </td>";
        html += "<td class='valorTexto'> " + fila.cliente + " </td>";
        html += "<td class='valorTexto'> " + fila.empresa + " </td>";
        html += "<td class='valorTexto'> " + fila.estadoClienteServicio + " </td>";
        html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(fila.valor).toString()) + " </td>";
        html += "<td class='valorFijo'> " + fila.tipoServicio + " </td>";
        var fechaFinal = fila.fechaFinal;
        if (fechaFinal != null && fechaFinal != undefined) {
            html += "<td class='valorFijo'> " + fila.fechaInicial + " </td>";
            html += "<td class='valorFijo'> " + fila.fechaFinal + " </td>";
        } else {
            html += "<td class='valorFijo'> " + fila.fechaInicial + " </td>";
            html += "<td class='valorFijo'>  </td>";
        }
        html += "<td class='valorTexto'> " + fila.municipio + " </td>";
        html += "<td class='valorTexto'> " + fila.vendedor + " </td>";
        html += '<td class="valorFijo"> <span id="imgVerTecnicos0" class="fa fa-gear imagenesTabla" title="Ver Técnicos" onclick="consultarTecnico('+fila.idOrdenTrabajo+')"></span> </td>';
        html += '<td class="valorFijo"> <span class="fa fa-clipboard imagenesTabla" title="Ver archivos" style="color: rgb(0, 0, 0);"  onclick="consultarArchivoSubido('+fila.idOrdenTrabajoCliente+')"></span> </td>';
        html += "</tr>";
    });

    $('#consultaTabla').html(html);
}

function asignarValorEnvio() {
    data = "";
    data += "idCliente=" + idCliente;
    data += "&idVendedor=" + idVendedor;
    data += "&idServicio=" + $('#selServicio').val();
    data += "&idMunicipio=" + $('#selMunicipio').val();
    data += "&fechaInicial=" + $('#txtFechaInicial').val();
    data += "&fechaFinal=" + $('#txtFechaFinal').val();
    data += "&tipoOrden=" + $('#selTipoServicio').val();
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

    $('#selMunicipio').val("");
    $('#selMunicipio').multiselect('destroy');
    $('#selMunicipio').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $("button.multiselect").css("width", "300px");
    $('#selTipoServicio').val("");
    $('#selTipoServicio').multiselect('destroy');
    $('#selTipoServicio').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");
    idCliente = null;
    idVendedor = null;
    idMunicipio = null;
    idTipoServicio = null;
    fechaInicial = null;
    fechaFinal = null;
    idProducto = null;
    data = null;
    arrInformacionExportar = new Array();
    $("#imgExportar").hide();
    crearTablaResporte();
}

function consultarTecnico(idOrdenTrabajo) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoTecnico.consultar.php',
        data: {
            idOrdenTrabajo: idOrdenTrabajo
            , estado: true
        },
        dataType: "json",
        type: 'POST',
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            var html = '<table id="tblProductos" class="table table-bordered table-striped consultaTabla" style="margin-top:1%">';
            html += '<tr id="trEstatico">';
            html += '<th>#</th>';
            html += '<th>Técnico</th>';
            html += '<th>Principal</th>';
            html += '</tr>';

            $.each(json.data, function(contador, fila) {
                html += '<tr>';
                html += '<td align="center">' + (contador + 1) + '</td>';
                html += '<td><span>' + fila.tecnico + '</span></td>';

                if (fila.principal == true) {
                    html += '<td align="center">SI</td>';
                } else {
                    html += '<td align="center">NO</td>';
                }
                html += '</tr>';
            });

            html += '</table>';

            bootbox.alert(html);

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function consultarArchivoSubido(idOrdenTrabajoCliente){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarArchivo.php',
        data: {
            idOrdenTrabajoCliente: idOrdenTrabajoCliente
        },
        dataType: "json",
        type: 'POST',
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                alerta("No se encontraron regístros con los parámetros indicados.");
                return false;
            }

            var html = '<table id="tblProductos" class="table table-bordered table-striped consultaTabla" style="margin-top:1%">';
            html += '<tr id="trEstatico">';
            html += '<th> # </th>';
            html += '<th> Etiqueta </th>';
            html += '<th> Responsable </th>';
            html += '<th> Fecha de creacion </th>';
            html += '<th> Descargar </th>';
            html += '</tr>';

            $.each(json.data, function(contador, fila) {
                html += '<tr>';
                html += '<td class="valorFijo">' + (contador + 1) + '</td>';
                html += '<td class="valorTexto"><span>' + fila.etiqueta + '</span></td>';
                html += '<td class="valorTexto"><span>' + fila.persona + '</span></td>';
                html += '<td class="valorFijo"><span>' + fila.fechaCreacion + '</span></td>';
                html += '<td class="valorFijo"><span><span id="imgVerTecnicos0" class="fa fa-download imagenesTabla" title="Descargar este archivo" onclick="window.open(\''+fila.ruta+'\')"></span></span></td>';
                html += '</tr>';
            });

            html += '</table>';

            bootbox.alert(html);

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}