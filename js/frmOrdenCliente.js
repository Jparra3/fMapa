var idZona = null;
var idPeriodicidad = null;
var data = null;
var arrOrdenCliente = new Array();

$(function () {
    cargarListado();
    cargarZonas();
    cargarPeriodicidadVisita();
    $("#imgGuardar").hide();

    $("#imgConsultar").click(function () {
        obtenerDatosEnvio();

        if (validarVacios(document.frmOrdenCliente) == false)
            return false;

        $.ajax({
            async: false,
            url: localStorage.modulo + "controlador/cliente.consultarOrdenVisita.php",
            type: 'POST',
            dataType: "json",
            data: data,
            success: function (json) {
                var exito = json.exito;
                var mensaje = json.mensaje;

                if (exito == 0) {
                    $("#imgGuardar").hide();
                    limpiarVariables();
                    cargarListado()
                    alerta(mensaje);
                    return false;
                }

                if (json.numeroRegistros == 0) {
                    $("#imgGuardar").hide();
                    limpiarVariables();
                    cargarListado();
                    alerta("No se encontraron registros con los parametros indicados.");
                    return false;
                }

                crearListado(json);
                $("#imgGuardar").show();

            }, error: function (xhr, opciones, error) {
                alerta(error);
            }
        });
    });

    $("#imgGuardar").click(function () {
        limpiarVariables();
        
        var orden = 1;
        $("#ulListadoClientes li").each(function () {
            
            var idCliente = $(this).attr("idCliente");
            var cliente = $(this).attr("value");
            var obj = new Object();
            
            obj.idCliente = idCliente;
            obj.cliente = cliente;
            obj.orden = orden;
            
            arrOrdenCliente.push(obj);
            
            orden++;
            
        });
        
        $.ajax({
            async: false,
            url: localStorage.modulo + "controlador/cliente.actualizarOrdenVisita.php",
            type: 'POST',
            dataType: "json",
            data: {arrOrdenCliente: arrOrdenCliente},
            success: function (json) {
                var exito = json.exito;
                var mensaje = json.mensaje;

                if (exito == 0) {
                    alerta(mensaje);
                    return false;
                }

                $("#imgGuardar").hide();
                limpiarVariables();
                limpiarControlesFormulario(document.frmOrdenCliente);
                cargarListado();
                alerta(mensaje);
                
            }, error: function (xhr, opciones, error) {
                alerta(error);
            }
        });
        
    });
});
function cargarZonas() {
    $.ajax({
        url: localStorage.modulo + 'controlador/zona.consultar.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function (json) {
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
            $.each(json.data, function (contador, fila) {
                control.append('<option idRegional="' + fila.idRegional + '" value="' + fila.idZona + '">' + fila.zonaRegional + '</option>');
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarPeriodicidadVisita() {
    $.ajax({
        url: localStorage.modulo + 'controlador/periodicidadVisita.cargar.php',
        dataType: 'json',
        type: 'POST',
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            var control = $('#selPeriodicidadVisita');
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idPeriodicidadVisita + '">' + fila.periodicidadVisita + '</option>');
            });
        }

    });
}
function asignarDatosEnvio() {
    idZona = $("#selZona").val();
    idPeriodicidadVisita = $("#selPeriodicidadVisita").val();
}
function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idZona=' + idZona + '&idPeriodicidadVisita=' + idPeriodicidadVisita;
}
function cargarListado() {
    $("#consultaTabla").html("");
    $("#divListadoClientes").html("");
}
function crearListado(json) {
    $("#divListadoClientes").html("");
    var html = '<ol id="ulListadoClientes" style="cursor: move;">';
    $.each(json.data, function (contador, fila) {
        html += '<li idCliente="' + fila.idCliente + '" value="' + fila.cliente + '">' + fila.cliente + '</li>';
    });
    html += '</ol>';
    $("#divListadoClientes").html(html);

    $("#ulListadoClientes").sortable({
        helper: function (event) {
            var value = event.target.attributes.value.value;
            return $("<div class='ui-widget-header'>" + value + "</div>");
        }
    });
}
function limpiarVariables(){
    arrOrdenCliente = new Array();
}