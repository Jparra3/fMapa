var rutaFacturaPdf = '../../archivos' + localStorage.modulo + 'facturas/';
var rutaReciboCajaPdf = '../../archivos' + localStorage.modulo + 'reciboCaja/';
var valorRecibido = variableUrl();
var idCliente = valorRecibido[0];
var idTipoDocumentoOmitir = valorRecibido[3]; //Se almacena el tipo documento de "PAGO FACTURA" para que al momento de cargar el combo de tipos de documento no sea tenido en cuenta.
var tipoClienteProveedor = valorRecibido[4]; //Se obtiene el tipo 'C' -> cliente , 'P' -> Proveedor
var idTercero = valorRecibido[5];
var saldo = " > 0 ";
var idTransaccionEstado = 1; //ACTIVO
var numeroFactura = null;
var idTipoDocumento = null;

var registroActivo = false;
var idTransaccion = null;
var numeroFactura = null;
var valorPago = null;

var arrPagoFactura = new Array();
var data = null;
$(function () {
    validarNumeros("txtNoFactura");
    cargarListadoFacturas();
    obtenerDatosEnvio();
    consultar();
    cargarTiposDocumento();

    $("#imgConsultar").click(function () {
        obtenerDatosEnvio();
        consultar();
    });

    $("#imgCancelar").click(function () {
        cerrarVentana();
    });

    $("#imgGuardar").click(function () {

        if (registroActivo == false) {
            alerta("Debe seleccionar la factura.");
            return false;
        }

        valorPago = $("#txtValorPagar" + numeroFactura).val();

        if ($.trim(valorPago) == null || $.trim(valorPago) == 0 || $.trim(valorPago) == "0" || $.trim(valorPago) == "") {
            alerta("Por favor indique el valor a pagar.");
            return false;
        }

        adicionarPago();
    });
});
function consultar() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacion.consultar.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta("No se encontraron registros con los parámetros indicados.");
                cargarListadoFacturas();
                return false;
            }

            crearListadoFacturas(json);

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function cargarListadoFacturas() {
    $("#divListadoFacturas").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>Nro. Factura</th>';
    tabla += '<th>Tipo de documento</th>';
    tabla += '<th>Nit</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Saldo</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoFacturas").html(tabla);
}
function crearListadoFacturas(json) {
    $("#divListadoFacturas").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>&nbsp;</th>';
    tabla += '<th>Nro. Factura</th>';
    tabla += '<th>Tipo de documento</th>';
    tabla += '<th>Nit</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Saldo</th>';
    tabla += '<th>Valor a pagar</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';

    var arrIdTxtValorPagar = new Array();
    $.each(json.data, function (contador, fila) {
        numeroFactura = fila.numeroFactura;
        idTransaccion = fila.idTransaccion;
        tabla += '<tr>';
        tabla += '<td align="center"><input onclick="activarRegistro(this, ' + fila.idTransaccion + ',' + fila.numeroFactura + ')" name="rdoFacturas" type="radio"></td>';
        tabla += '<td align="right">' + fila.numeroFactura + '</td>';
        tabla += '<td>' + fila.tipoDocumento + '</td>';
        tabla += '<td align="right">' + fila.nit + '</td>';
        tabla += '<td>' + fila.cliente + '</td>';
        tabla += '<td align="center">' + fila.fecha + '</td>';
        tabla += '<td align="center">' + agregarSeparadorMil(String(parseInt(Math.round(fila.saldo)))) + '</td>';
        var id = 'txtValorPagar' + fila.numeroFactura;
        tabla += '<td align="center"><input name="txtValorPagar" id="' + id + '" type="text" class="form-control medium saldos" value="' + agregarSeparadorMil(String(parseInt(Math.round(fila.saldo)))) + '"></td>';
        tabla += '<td align="center"><span class="fa fa-print imagenesTabla" title="Ver Factura" class="imagenesTabla" onclick="verFactura(' + fila.numeroFactura + "," + "'" + fila.codigoTipoNaturaleza + "'" + ')"></span></td>';
        tabla += '</tr>';

        arrIdTxtValorPagar.push(id);
    });

    tabla += '</table>';
    $("#divListadoFacturas").html(tabla);

    //Deshabilito todos los campos de texto, para que luego pueda activarlos mediante el radio button
    $(".saldos").attr('disabled', 'disabled');

    if (json.numeroRegistros == 1) {
        registroActivo = true;
        $('input[name=rdoFacturas]').prop("checked", true);
        $(".saldos").removeAttr("disabled");
        $(".saldos").focus();
    }

    for (var i = 0; i < arrIdTxtValorPagar.length; i++) {//Formateo el campo con separadores de mil
        formatearNumero(arrIdTxtValorPagar[i]);
    }
}
function asignarDatosEnvio() {
    numeroFactura = $("#txtNoFactura").val();
    idTipoDocumento = $("#selTipoDocumento").val();
}
function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idCliente=' + idCliente + '&idTercero=' + idTercero + '&tipoClienteProveedor=' + tipoClienteProveedor + '&saldo=' + saldo + '&idTransaccionEstado=' + idTransaccionEstado + '&numeroFactura=' + numeroFactura + '&idTipoDocumento=' + idTipoDocumento + '&idTipoDocumentoOmitir=' + idTipoDocumentoOmitir;
}
function verFactura(numeroFactura, tipo) {
    var ruta = "";
    
    switch (tipo){
        case "VE": //TIPO VENTAS - FACTURACION
            ruta = rutaFacturaPdf + 'factura_' + numeroFactura + '.pdf';
        break;
        case "CA": //TIPO CAJA
            ruta = rutaReciboCajaPdf + 'reciboCaja_' + numeroFactura + '.pdf';
        break;
    }
    
    if(ruta == ""){
        alerta("No se encontró ningún documento para esta factura.");
        return;
    }
    
    window.open(ruta, '_blank');
}
function activarRegistro(control, _idTransaccion, _numeroFactura) {
    registroActivo = true;
    idTransaccion = _idTransaccion;
    numeroFactura = _numeroFactura;

    $(".saldos").attr('disabled', 'disabled');
    if (control.checked == true) {
        $("#txtValorPagar" + numeroFactura).removeAttr("disabled");
        $("#txtValorPagar" + numeroFactura).focus();
    }
}

function adicionarPago() {
    var objPagoFactura = new Object();
    objPagoFactura.idCliente = idCliente;
    objPagoFactura.tipoClienteProveedor = tipoClienteProveedor;
    objPagoFactura.idTransaccion = idTransaccion;
    objPagoFactura.numeroFactura = numeroFactura;
    objPagoFactura.valorConcepto = valorPago;

    arrPagoFactura.push(objPagoFactura);
    cerrarVentana();
}
function cerrarVentana() {
    window.opener.asignarValorConcepto(arrPagoFactura);
    window.close();
}
function cargarTiposDocumento() {
    $.ajax({
        url: localStorage.modulo + 'controlador/facturacion.consultarTiposDocumento.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje, true);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selTipoDocumento');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}