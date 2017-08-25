var idTecnico = null;
$(function () {
    cargarListado();
    autoCompletarTecnico();
    $("#imgNuevo").click(function () {
        abrirVentana(localStorage.modulo + 'vista/frmTecnico.html', '');
    });
    $("#imgConsultar").bind({
        "click": function () {
            var data = obtenerValorEnvio();
            consultarTecnico(data);
        }
    });
    $("#imgLimpiar").click(function () {
        limpiarControlesFormulario(document.fbeFormulario);
        limpiarVariables();
        cargarListado();
    });
});
function limpiarVariables() {
    idTecnico = null;
}
function consultarTecnico(data) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/tecnico.consultar.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            if (json.numeroRegistros == 0) {
                alerta("No se encontraron registros con los praámetros indicados.");
                limpiarTecnico();
                return false;
            }
            crearListado(json);
            localStorage.numeroRegistros = json.numeroRegistros;//Almaceno el número de registros para saber cuantas debo ocultar
            obtenerPermisosFormulario(localStorage.formulario);//Habilitar las acciones a las que tenga permiso
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function obtenerValorEnvio() {
    var data = "";
    data += "idTecnico=" + idTecnico;
    data += "&estado=" + $("#selEstado").val();
    return data;
}

function autoCompletarTecnico() {
    $("#txtNombre").autocomplete({
        source: localStorage.modulo + 'ajax/tecnico.autocompletar.php',
        select: function (event, ui) {
            idTecnico = ui.item.idTecnico;
        }
    });
}

function crearListado(json) {
    $("#consultaTabla").html("");
    var tabla = '<table>';
    tabla += '<tr>';
    tabla += '<th> # </th>';
    tabla += '<th> # Documento Identidad - Nombre</th>';
    tabla += '<th> Eps </th>';
    tabla += '<th> Arl </th>';
    tabla += '<th> Cargo </th>';
    tabla += '<th> Celular </th>';
    tabla += '<th> Correo </th>';
    tabla += '<th> Dirección </th>';
    tabla += '<th> Acción </th>';
    $.each(json.data, function (contador, fila) {
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td class="valorFijo"> ' + parseInt(contador + 1) + ' </td>';
        tabla += '<td class="valorTexto"> ' + fila.numeroIdentificacion + ' - ' + fila.tecnico + ' </td>';
        tabla += '<td class="valorTexto"> ' + fila.eps + ' </td>';
        tabla += '<td class="valorTexto"> ' + fila.arl + ' </td>';
        tabla += '<td class="valorTexto"> ' + fila.cargo + ' </td>';
        tabla += '<td class="valorNumerico"> ' + fila.celular + ' </td>';
        tabla += '<td class="valorTexto"> ' + fila.correo + ' </td>';
        tabla += '<td class="valorTexto"> ' + fila.direccion + ' </td>';
        tabla += '<td class="valorFijo"> <span class="fa fa-pencil imagenesTabla" id="imgModificar' + contador + '"  title="Editar" class="imagenesTabla" onclick="abrirVentana(' + "'" + localStorage.modulo + 'vista/frmTecnico.html' + "'" + ',' + fila.idTecnico + ')"> </td>';
        tabla += '</tr>';
    });
    tabla += '</table>';
    $("#consultaTabla").html(tabla);
}


function cargarListado() {
    $("#consultaTabla").html("");
    var tabla = '<table>';
    tabla += '<tr>';
    tabla += '<th> # </th>';
    tabla += '<th> # Documento Identidad - Nombre</th>';
    tabla += '<th> Eps </th>';
    tabla += '<th> Arl </th>';
    tabla += '<th> Celular </th>';
    tabla += '<th> Correo </th>';
    tabla += '<th> Dirección </th>';
    tabla += '<th> Acción </th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#consultaTabla").html(tabla);
}

function limpiarTecnico() {
    idTecnico = null;
    cargarListado();
    limpiarControlesFormulario(document.fbeFormulario);
}