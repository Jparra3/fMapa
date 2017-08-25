var idEmpresa = null;
var idCliente = null;
var idZona = null;
var estado = null;
var data = null;

$(function() {
    //Cargar parametros
    autoCompletarCliente();
    cargarZonas();
    cargarEmpresas();
    cargarListado();

    //Acciones formulario
    $("#imgConsultar").bind({
        "click": function() {
            obtenerValorEnvio();
            consultar();
        }
    });
    $("#imgNuevo").bind({
        "click": function() {
            abrirFormularioCliente(null);
        }
    });
    $("#imgLimpiar").bind({
        "click": function() {
            limpiarControles();
        }
    });
});

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
            control.append("<option value=''> --Seleccione-- </option>");
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

            $("button.multiselect").attr("accesskey", "V");
            $("input.form-control.multiselect-search").attr("accesskey", "V");

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarListado() {
    var table = '';
    table += '<tr>';
    table += '<th> # </th>';
    table += '<th> Cliente </th>';
    table += '<th> Celular </th>';
    table += '<th> Sucusal </th>';
    table += '<th> Empresa </th>';
    table += '<th> Acción </th>';
    table += '</tr>';
    table += '<tr>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '</tr>';

    $('#consultaTabla').html(table);
}

function consultar() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.consultar.php',
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
            visualizarCliente(json);
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function asignarValorEnvio() {
    idEmpresa = $("#selEmpresa").val();
    idZona = $("#selZona").val();
    estado = $("#selEstado").val();
}

function obtenerValorEnvio() {
    asignarValorEnvio();
    data = "";
    data += "idCliente=" + idCliente;
    data += "&idZona=" + idZona;
    data += "&idEmpresa=" + idEmpresa;
    data += "&estado=" + estado;
}

function visualizarCliente(json) {
    var table = '';
    table += '<tr>';
    table += '<th> # </th>';
    table += '<th> Cliente </th>';
    table += '<th> Celular/Teléfono </th>';
    table += '<th> Sucusal </th>';
    table += '<th> Empresa </th>';
    table += '<th> Estado </th>';
    table += '<th colspan="2"> Acción </th>';
    table += '</tr>';
    $.each(json.data, function(contador, fila) {
        table += '<tr>';
        table += '<td class="valorFijo"> ' + parseInt(contador + 1) + ' </td>';
        table += '<td class="valorTexto"> ' + fila.tercero + ' </td>';
        table += '<td class="valorNumerico"> ' + fila.terceroTelefono + ' </td>';
        table += '<td class="valorTexto">' + fila.sucursal + '</td>';
        table += '<td class="valorTexto">' + fila.empresa + '</td>';
        if(fila.estado != 'true' && fila.estado != true && fila.estado != "TRUE"){
            table += '<td class="valorTexto"> Inactivo </td>';
        }else{
            table += '<td class="valorTexto"> Activo </td>';
        }
        if(fila.estado != 'true' && fila.estado != true && fila.estado != "TRUE"){
            table += '<td colspan="2" class="valorFijo"> <span id="imgModificarCuenta" class="fa fa-pencil imagenesTabla" onClick="abrirFormularioCliente(' + fila.idCliente + ')" title="Editar el cliente ' + fila.tercero + '"></span> </td>';
            //table += '<td class="valorFijo"> <span class="fa fa-plus imagenesTabla" title="Activar esta sucursal del cliente ' + fila.tercero + '" onclick="alertaActivar(' + "'" + fila.tercero + "'" + ',' + fila.idSucursal + ')"></span> </td>';
        }else{
            table += '<td class="valorFijo"> <span id="imgModificarCuenta" class="fa fa-pencil imagenesTabla" onClick="abrirFormularioCliente(' + fila.idCliente + ')" title="Editar el cliente ' + fila.tercero + '"></span> </td>';
            table += '<td class="valorFijo"> <span id="imgAnular0" class="fa fa-minus imagenesTabla" title="Inactivar esta sucursal del cliente ' + fila.tercero + '" onclick="alertaInactivar(' + "'" + fila.tercero + "'" + ',' + fila.idSucursal + ')"></span> </td>';
        }
        table += '</tr>';
    });

    $('#consultaTabla').html(table);
}

function abrirFormularioCliente(idCliente) {
    var formulario = "vista/frmCliente.html";
    var parametros = "";
    if (idCliente != null && idCliente != "null" && idCliente != undefined) {
        parametros += "?idCliente=" + idCliente;
        parametros += "&origen=1";
    } else {
        parametros += "?idCliente=null&origen=1";
    }

    abrirPopup(localStorage.modulo + formulario + parametros, 'Cliente');
}
function alertaInactivar(cliente, idSucursal) {
    bootbox.confirm("Está seguro(a) de inactivar el cliente " + cliente + "?", function(result) {
        if (result == true) {
            inactivarCliente(idSucursal, "false");
        }
    });
}
function alertaActivar(cliente, idSucursal) {
    bootbox.confirm("Está seguro(a) de activar el cliente " + cliente + "?", function(result) {
        if (result == true) {
            inactivarCliente(idSucursal, "true");
        }
    });
}
function inactivarCliente(idSucursal, estado) {
    $.ajax({
        url: localStorage.modulo + 'controlador/cliente.inactivar.php',
        type: 'POST',
        dataType: "json",
        data: {
              idSucursal: idSucursal
            , estado:estado  
        },
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                return;
            }
            if (json.numeroRegistros == 0) {
                return false;
            }
            obtenerValorEnvio();
            consultar();
            if(estado != "true"){
                alerta(mensaje);
            }else{
                alerta("Se activó el cliente correctamente.");
            }
        }, error: function(xhr, opciones, error) {
            throw error;
            return false;
        }
    });
}

function limpiarControles() {
    limpiarControlesFormulario(document.fbeFormulario);
    $("#selZona").multiselect('destroy');
    $('#selZona').val("");
    $('#selZona').multiselect({
        maxHeight: 600
        , nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    //Limpiamos variables
    idCliente = null;
    idZona = null;
    estado = null;
    data = null;
    cargarListado();
}
function cargarEmpresas() {
    $.ajax({
        async: false,
        url: '/Seguridad/ajax/tercero.cargarEmpresas.php',
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
            var control = $("#selEmpresa");
            control.empty();
            control.append('<option value="">--SELECCIONE--</option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEmpresa + '">' + fila.empresa + '</option>');
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}