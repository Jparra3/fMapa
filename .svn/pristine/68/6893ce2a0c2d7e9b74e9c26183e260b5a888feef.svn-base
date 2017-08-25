var rutaFacturaPdf = '../../archivos' + localStorage.modulo + 'facturas/';
var rutaReciboCajaPdf = '../../archivos' + localStorage.modulo + 'reciboCaja/';
var numeroFactura = null;
var fechaInicio = null;
var fechaFin = null;
var idTercero = null;
var idCliente = null;
var idCaja = null;
var saldo = null;
var idFormaPago = null;
var idTransaccionEstado = null;
var tipo = null;
var contadorFlecha = 0;
var contadorClientes = 0;

var data = null;
$(function () {

    window.onkeypress = function (e) {
        switch (e.keyCode) {

            /*NAVEGACIÓN CON FLECHAS*/
            case 38://FLECHA ARRIBA
                contadorFlecha--;
                if (contadorFlecha >= 0 && contadorFlecha <= contadorClientes) {
                    $('#linkCliente' + contadorFlecha).focus();
                } else {
                    contadorFlecha++;
                }
                return false;
                break;
            case 40://FLECHA ABAJO
                contadorFlecha++;
                if (contadorFlecha >= contadorClientes) {
                    contadorFlecha--;
                    return;
                }
                $('#linkCliente' + contadorFlecha).focus();
                return false;
                break;
        }
    };

    cargarTipo();
    cargarCajas();
    cargarSaldo();
    cargarEstados();
    cargarListadoFacturas();
    crearCalendarioDoble("txtFechaInicio", "txtFechaFin");
    validarNumeros("txtNit");
    autoCompletarTercero();

    $("#txtNit").change(function () {
        if ($("#txtNit").val() != "") {
            consultarTercero($("#txtNit").val());
        }
    });

    $("#txtNit").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNombre").val("");
                idTercero = null;
                idCliente = null;
                break;
        }
    });

    $("#txtNombre").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNit").val("");
                idTercero = null;
                idCliente = null;
                break;
        }
    });

    $("#selTipo").change(function () {
        var codigTipoNaturFormaPago = $("#selTipo").val();
        if (codigTipoNaturFormaPago != "") {
            codigTipoNaturFormaPago = codigTipoNaturFormaPago + "-FP";
            cargarFormasPago(codigTipoNaturFormaPago);
        }
    });

    $("#imgConsultar").click(function () {
        obtenerDatosEnvio();

        if($("#selTipo").val() == ""){
            alerta("Por favor indique el tipo para realizar la consulta.");
            return;
        }

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
    });

    $("#imgLimpiar").click(function () {
        limpiarVariables();
        limpiarControlesFormulario(document.fbeFormulario);
        cargarListadoFacturas();
    });
});
function cargarListadoFacturas() {
    $("#consultaTabla").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>Nro. Doc.</th>';
    tabla += '<th>Nit</th>';
    tabla += '<th>Tercero</th>';
    tabla += '<th>Tipo de documento</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Total impuesto</th>';
    tabla += '<th>Valor</th>';
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
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#consultaTabla").html(tabla);
}
function crearListadoFacturas(json) {
    $("#consultaTabla").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>Nro. Doc.</th>';
    tabla += '<th>Nit</th>';
    tabla += '<th>Tercero</th>';
    tabla += '<th>Tipo de documento</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Total impuesto</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Saldo</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th colspan="3">Acción</th>';
    tabla += '</tr>';

    $.each(json.data, function (contador, fila) {
        tabla += '<tr>';
        tabla += '<td align="right">' + fila.numeroFactura + '</td>';
        tabla += '<td align="right">' + fila.nit + '</td>';
        tabla += '<td>' + fila.cliente + '</td>';
        tabla += '<td>' + fila.tipoDocumento + '</td>';
        tabla += '<td align="center">' + fila.fecha + '</td>';
        tabla += '<td align="right">' + agregarSeparadorMil(String(parseInt(fila.totalImpuesto))) + '</td>';
        tabla += '<td align="right">' + agregarSeparadorMil(String(parseInt(fila.valor))) + '</td>';
        tabla += '<td align="right">' + agregarSeparadorMil(String(parseInt(fila.saldo))) + '</td>';



        tabla += '<td align="center">' + fila.estadoTransaccion + '</td>';
        tabla += '<td align="center"><span class="fa fa-files-o imagenesTabla" title="Ver recibos de caja" class="imagenesTabla" onclick="verRecibosCaja(' + fila.idTransaccionConcepto + ')"></span></td>';
        tabla += '<td align="center"><span class="fa fa-print imagenesTabla" title="Ver Factura" class="imagenesTabla" onclick="verFactura(' + fila.numeroFactura + "," + "'" + fila.codigoTipoNaturaleza + "'" + ')"></span></td>';

        if (fila.estadoTransaccion == 'ANULADO'){
            tabla += '<td align="center">&nbsp;</td>';
        }else{            
            if(fila.codigoTipoNaturaleza == "VE"){
                tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" title="Anular Factura" class="imagenesTabla" onclick="anularFactura(' + fila.idTransaccion + "," + "'" + fila.codigoTipoNaturaleza + "'" + ')"></span></td>';
            }else{
                tabla += '<td align="center">&nbsp;</td>';
            }
        }
        tabla += '</tr>';
    });

    tabla += '</table>';
    $("#consultaTabla").html(tabla);
}
function asignarDatosEnvio() {
    numeroFactura = $("#txtNoFactura").val();
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    idCaja = $("#selCaja").val();
    saldo = $("#selSaldo").val();
    idFormaPago = $("#selFormaPago").val();
    idTransaccionEstado = $("#selTransaccionEstado").val();
    tipo = $("#selTipo").val();
}
function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idTercero=' + idTercero + '&idCliente=' + idCliente + '&numeroFactura=' + numeroFactura + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin + '&idCaja=' + idCaja + '&saldo=' + saldo + '&idFormaPago=' + idFormaPago + '&idTransaccionEstado=' + idTransaccionEstado + '&tipo=' + tipo;
}
function cargarCajas() {
    $.ajax({
        url: localStorage.modulo + 'controlador/caja.consultar.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selCaja');
            control.empty();

            if (json.numeroRegistros > 1) {
                control.append('<option value="">--Seleccione--</option>');
            }

            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idCaja + '">' + fila.numeroCaja + " - " + fila.bodega + '</option>');
            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function cargarSaldo() {
    var control = $('#selSaldo');
    control.empty();
    control.append('<option value="">--Seleccione--</option>');
    control.append('<option value=" > 0 ">Con saldo mayor a 0</option>');
    control.append('<option value=" = 0 ">Con saldo igual a 0</option>');
}
function cargarTipo() {
    var codigosTipoNaturaleza = "'VE','CA'";
    $.ajax({
        url: localStorage.modulo + 'controlador/tipoNaturaleza.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {estado:true, codigo: codigosTipoNaturaleza},
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }
            
            var control = $('#selTipo');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.codigo + '">' + fila.tipoNaturaleza + '</option>');
            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function autoCompletarTercero() {
    $("#txtNombre").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function (event, ui) {
            idTercero = ui.item.idTercero;
            idCliente = ui.item.idCliente;
            $("#txtNit").val(ui.item.nit);
        }
    });
}
function consultarTercero(nit) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacion.consultarCliente.php',
        type: 'POST',
        dataType: "json",
        data: {nit: nit},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                $("#txtNombre").val("");
                idTercero = null;
                idCliente = null;
                return false;
            }

            if (json.numeroRegistros == 1) {
                $.each(json.data, function (contador, fila) {
                    idTercero = fila.idTercero;
                    idCliente = fila.idCliente;
                    $("#txtNombre").val(fila.cliente);
                });
            } else {
                var html = '';
                html += '<div style="min-height: 12.4286px;';
                html += 'padding: 0px;';
                html += 'border-bottom: 1px solid #E5E5E5;';
                html += 'background-image: linear-gradient(to bottom, #455A64 0%, #455A64 100%);';
                html += 'background-repeat: repeat-x;';
                html += 'font-weight: bold;';
                html += 'border-radius: 4px;';
                html += 'padding-top: 0.6%;';
                html += 'font-size: 1.6em;';
                html += 'color: #FFF;">';
                html += 'Por favor indique la sucursal</div>';

                html += '<ul style="list-style:none; margin-top:1%;">';
                contadorClientes = 0;
                $.each(json.data, function (contador2, fila) {
                    html += '<li><input style="width: 400px;" id="linkCliente' + contadorClientes + '" onfocus="focoCampoCliente($(this))" onkeypress="keypressCampoCliente($(this), event)" type="text" class="campo_cliente"  data-dismiss="modal" onclick="asignar(' + fila.idTercero + ',' + fila.idCliente + ',' + "'" + fila.cliente + "'" + ')" value="' + fila.cliente + '"></li>';
                    contadorClientes++;
                });
                html += '</ul>';

                bootbox.alert(html);
                $('.bootbox').on('shown.bs.modal', function () {
                    $('#linkCliente0').focus();
                });
                $('.bootbox').on('hidden.bs.modal', function () {
                    $('#txtFechaVencimiento').focus();
                });
                contadorFlecha = 0;
            }

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function focoCampoCliente(campo) {
    campo.select();
}
function keypressCampoCliente(campo, event) {
    if (event.keyCode == 13) {
        campo.click();
    }
}
function asignar(idTercer, idClient, terceroSucursal) {
    idTercero = idTercer;
    idCliente = idClient;
    $("#txtNombre").val(terceroSucursal);
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
function verRecibosCaja(idTransaccionConcepto) {

    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacion.consultarRecibosCaja.php',
        type: 'POST',
        dataType: "json",
        data: {idTransaccionConcepto: idTransaccionConcepto},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta("No se encontraron recibos de caja para esta factura");
                return false;
            }

            $("#divListadoRecibosCaja").html("");
            var tabla = '<table class="table table-bordered table-striped consultaTabla">';
            tabla += '<tr>';
            tabla += '<th>Nro. Documento</th>';
            tabla += '<th>Concepto</th>';
            tabla += '<th>Fecha</th>';
            tabla += '<th>Valor</th>';
            tabla += '</tr>';
            var total = 0;
            $.each(json.data, function (contador, fila) {
                tabla += '<tr>';
                tabla += '<td align="right">' + fila.numeroDocumento + '</td>';
                tabla += '<td>' + fila.tipoDocumento + '</td>';
                tabla += '<td align="center">' + fila.fecha + '</td>';
                tabla += '<td align="right">' + agregarSeparadorMil(String(parseInt(fila.valor))) + '</td>';
                tabla += '</tr>';
                total = total + parseInt(fila.valor);
            });

            tabla += '<tr>';
            tabla += '<td colspan="3" align="right"><b>Total</b></td>';
            tabla += '<td align="right">' + agregarSeparadorMil(String(parseInt(total))) + '</td>';
            tabla += '</tr>';

            tabla += '</table>';
            $("#divListadoRecibosCaja").html(tabla);

            $("#btnAbrirModal").click();

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function anularFactura(idTransaccion, tipo) {
    switch (tipo){
        case "VE": //TIPO VENTAS - FACTURACION
            bootbox.confirm("Está seguro(a) de anular la factura?", function (result) {
                if (result == true) {
                    $.ajax({
                        async: false,
                        url: localStorage.modulo + 'controlador/facturacion.anular.php',
                        data: {
                            idTransaccion: idTransaccion
                        },
                        dataType: "json",
                        type: 'POST',
                        success: function (json) {
                            var exito = json.exito;
                            var mensaje = json.mensaje;

                            if (exito == 0) {
                                alerta(mensaje);
                                return false;
                            }

                            alerta(json.mensaje);
                            $("#imgConsultar").click();

                        }, error: function (xhr, opciones, error) {
                            alerta(error);
                            return false;
                        }
                    });
                }
            });
        break;
        case "CA": //TIPO CAJA
            
        break;
    }
}
function cargarEstados() {
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarEstados.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selTransaccionEstado');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                if (fila.inicial == true) {
                    control.append('<option selected value="' + fila.idEstadoTransaccion + '">' + fila.estadoTransaccion + '</option>');
                } else {
                    control.append('<option value="' + fila.idEstadoTransaccion + '">' + fila.estadoTransaccion + '</option>');
                }
            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function limpiarVariables() {
    numeroFactura = null;
    fechaInicio = null;
    fechaFin = null;
    idTercero = null;
    idCliente = null;
    idCaja = null;
    saldo = null;
    idFormaPago = null;
    idTransaccionEstado = null;
    tipo = null;
}
function cargarFormasPago(codigTipoNaturFormaPago) {
    $.ajax({
        url: localStorage.modulo + 'controlador/facturacion.consultarFormaPago.php',
        type: 'POST',
        dataType: "json",
        data: {estado: true
            , codigoTipoNaturaleza: codigTipoNaturFormaPago},
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

            var control = $('#selFormaPago');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idFormaPago + '">' + fila.formaPago + '</option>');
            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}