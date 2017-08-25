var codigTipoNaturFormaPago = "VE-FP";
var codigParamFormaPagoDefec = "FPD-VE";
//VARIABLES TRANSACCION
var idTransaccion = null;
var idTercero = null;
var idCliente = null;
var nota = null;
var fechaVencimiento = null;
var contadorFlecha = 0;
var contadorClientes = 0;

//VARIABLES TRANSACCION PRODUCTO
var idBodega = null;
var idCaja = null;
var prefijo = null;
var posicionTemp = null;
var dataProductos = new Array();
var arrIdEliminar = new Array();
var idProducto = null;
var productoSerial = null;
var valorUnitarioEntrada = null;
var valorUnitarioSalida = null;
var valorEntraConImpue = null;
var validarVacioSerial = null;
var arrSerialesProductos = new Array();

//VARIABLES FORMAS DE PAGO
var dataFormasPago = new Array();

var valorTotalPagar = 0;
var valorTotalPagado = 0;

var idFormatoImpresion = 0;

//Variable de general
arrIdFrmPosicion = new Array();

var permiteModificarValorVenta = null;

var data = null;
$(function () {

    if (validarCajero() == false)
        return false;

    window.onkeypress = function (e) {
        switch (e.keyCode) {
            case 117://F6 -> PARA HACER FOCO A ENCABEZADO
                $("#txtNit").focus();
                return false;
                break;
            case 118://F7 -> PARA HACER FOCO A PRODUCTOS
                $("#txtCodigoProducto").focus();
                return false;
                break;
            case 119://F8 -> PARA HACER FOCO A FORMAS DE PAGO
                $("#selFormaPago").focus();
                return false;
                break;
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
            case 19://PAUSE/BREAK -> PARA GUARDAR LA FACTURA
                $("#imgGuardar").click();
                return false;
                break;
        }
    };

    $("#consultaTabla").html("");
    cargarInformacionCaja();
    cargarFormatosImpresion();
    //CARGUE INICIAL TAB TRANSACCION
    realizarFoco(document.frmTransaccion, '$("#txtCodigoProducto").focus()');
    validarNumeros("txtNit");
    autoCompletarTercero();
    crearCalendario("txtFechaVencimiento");
    $("#txtFechaVencimiento").val(obtenerFechaActual());

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

    $("#txtNombre").change(function () {
        if ($("#txtFechaVencimiento").val().toString() != "") {
            $("#txtCodigoProducto").focus();
        }
    });

    $("#txtFechaVencimiento").change(function () {
        if ($("#txtFechaVencimiento").val().toString() != "") {
            $("#txtCodigoProducto").focus();
        }
    });

    //CARGUE INICIAL TAB TRANSACCION PRODUCTO
    realizarFoco(document.frmTransaccionProducto, '$("#imgNuevoTransaccionProducto").click()');
    autoCompletarProducto();
    //validarNumeros("txtCantidad");
    cargarListadoTransaccionProducto();
    cargarPresentacionesProducto();

    $("#txtCodigoProducto").change(function () {
        if ($("#txtCodigoProducto").val() != "") {
            consultarProducto($("#txtCodigoProducto").val());
        }
    });

    $("#txtCodigoProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtProducto").val("");
                $("#txtUnidadMedida").html("");
                $("#txtValor").val("");
                $("#txtValorTotal").val("");
                $("#txtCantidad").val("");
                idProducto = null;
                productoSerial = null;
                valorEntraConImpue = null;
                valorUnitarioEntrada = null;
                valorUnitarioSalida = null;
                $("#pnlSeriales").hide();
                break;
        }
    });

    $("#txtProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProducto").val("");
                $("#txtUnidadMedida").html("");
                $("#txtValor").val("");
                $("#txtValorTotal").val("");
                $("#txtCantidad").val("");
                idProducto = null;
                productoSerial = null;
                valorEntraConImpue = null;
                valorUnitarioEntrada = null;
                valorUnitarioSalida = null;
                $("#pnlSeriales").hide();
                break;
        }
    });

    $("#txtCantidad").change(function () {
        var valor = eliminarPuntos($("#txtValor").val());
        var cantidad = eliminarPuntos($("#txtCantidad").val());        
        var valorTotal = valor * cantidad;
        valorTotal = Math.round(valorTotal);
        if(!isNaN(valorTotal)){
            $("#txtValorTotal").val(agregarSeparadorMil(String(valorTotal)));
        }
    });

    $("#txaNotaTransaccionProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 13:
                adicionarProducto();
                break;
        }
    });

    $("#txtCantidad").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtValorTotal").val("");
                break;
        }
    });

    $("#imgGuardar").click(function () {
        obtenerDatosEnvio();

        if (validarVacios(document.frmTransaccion) == false)
            return false;

        if (dataProductos.length == 0) {
            alerta("Debe adicionar mínimo un producto.");
            return false;
        }

        if (dataFormasPago.length == 0) {
            alerta("Debe adicionar mínimo una forma de pago.");
            return false;
        }

        if (valorTotalPagado < valorTotalPagar) {
            alerta("El valor del pago es menor al total a pagar.");
            return false;
        }

        if ($("#selFormatoImpresion").val() == '' || $("#selFormatoImpresion").val() == 'null' || $("#selFormatoImpresion").val() == null) {
            alerta("Debe seleccionar el formato de impresión.");
            return false;
        }

        try {
            $.ajax({
                async: false,
                url: localStorage.modulo + 'controlador/facturacion.adicionar.php',
                type: 'POST',
                dataType: "json",
                data: data,
                success: function (json) {
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    idTransaccion = json.idTransaccion;

                    if (exito == 0) {
                        throw mensaje;
                        return false;
                    }

                    generarFactura();
                    alerta(mensaje, 'limpiarFormulario()');

                }, error: function (xhr, opciones, error) {
                    alerta(error);
                    return false;
                }
            });
        } catch (e) {
            alerta(e);
            $("body").removeClass("loading");
            return false;
        }
    });

    $("#imgNuevoTransaccionProducto").click(function () {
        adicionarNuevaTransaccionProducto();
    });


    //CARGUE INICIAL DE FORMAS DE PAGO
    cargarFormasPago();
    formatearNumero("txtValorPagar");
    cargarListadoFormasPago();
    realizarFoco(document.frmFormaPago, '$("#imgNuevoFormaPago").click()');
    $("#txtNit").focus();

$("#txtValorPagar").keypress(function (e) {
        switch (e.keyCode) {
            case 13:
                return false;
                break;
        }
    });

    $("#imgNuevoFormaPago").click(function () {
        if (validarVacios(document.frmFormaPago) == false)
            return false;

        var obj = new Object();
        obj.idFormaPago = $("#selFormaPago").val();
        obj.formaPago = $("#selFormaPago option:selected").text();
        obj.valor = quitarSeparadorMil($("#txtValorPagar").val());

        dataFormasPago.push(obj);
        crearListadoFormasPago();
        $('#selFormaPago option[value=' + obj.idFormaPago + ']').hide();
        $('#selFormaPago option[value=' + obj.idFormaPago + ']').attr("disabled", true);
        $("#selFormaPago").val("");
        $("#txtValorPagar").val("");
        $("#selFormaPago").focus();
    });

    //Consultar pedidos desde movil
    $("#spnPedidoMovil").bind({
        "click": function () {
            abrirPopup('../vista/frmPedidoMovil.html');
        }
    });
});

function adicionarNuevaTransaccionProducto() {
    if (validarVacios(document.frmTransaccionProducto) == false)
        return false;

    if (idProducto == null || idProducto == "" || idProducto == "null") {
        alerta("Por favor indique el producto");
        return false;
    }

    if (posicionTemp != null) {
        var obj = dataProductos[posicionTemp];
        if (obj.idPedidoProducto) {
            if (obj.idPedidoProducto != null && obj.idPedidoProducto != 'null' && obj.idPedidoProducto != undefined) {
                if ($("#txtCantidad").val() > obj.cantidad) {
                    alerta(" La cantidad de este producto no puede ser mayor a la anterior. ");
                    return false;
                }
            }
        }
    }


    var obj = new Object();
    if (productoSerial == true) {
        var cantidad = $("#txtCantidad").val();
        var seriales = new Array();
        for (var i = 0; i < cantidad; i++) {

            if ($.trim($("#txtSerial" + i).val()) == "") {
                $("#txtSerial" + i).focus();
                return false;
            }

            seriales[i] = $("#txtSerial" + i).val();
        }

        if (validarSerialRepetido(seriales) == false) {
            return false;
        }

        obj.serial = seriales;
    } else {
        obj.serial = null;
    }

    obj.idTransaccionProducto = $("#hidIdTransaccionProducto").val();
    obj.idProducto = idProducto;
    obj.codigo = $("#txtCodigoProducto").val();
    obj.producto = $("#txtProducto").val();
    obj.productoSerial = productoSerial;
    obj.unidadMedida = $("#txtUnidadMedida").html();
    obj.valorEntraConImpue = valorEntraConImpue;
    obj.valorSalidConImpue = eliminarPuntos($("#txtValor").val());
    obj.valorUnitarioEntrada = valorUnitarioEntrada;
    obj.valorUnitarioSalida = valorUnitarioSalida;
    obj.cantidad = eliminarPuntos($("#txtCantidad").val());
    obj.valorTotal = obj.valorSalidConImpue * obj.cantidad;
    obj.valorTotal = Math.round(obj.valorTotal);
    
    obj.nota = $("#txaNotaTransaccionProducto").val();
    
    if($("#selPresentacion").val() != ""){
        obj.idUnidadMedidaPresentacion = $("#selPresentacion").val();
        obj.unidadMedidaPresentacion = $("#selPresentacion option:selected").text();
    }else{
        obj.idUnidadMedidaPresentacion = null;
        obj.unidadMedidaPresentacion = '';
    }

    if (posicionTemp != null) {
        if (dataProductos[posicionTemp].idPedidoProducto) {
            if (dataProductos[posicionTemp].idPedidoProducto != null && dataProductos[posicionTemp].idPedidoProducto != 'null' && dataProductos[posicionTemp].idPedidoProducto != undefined) {
                var objDataProducto = dataProductos[posicionTemp];
                obj.idPedidoProducto = objDataProducto.idPedidoProducto;
            }
        }
        dataProductos.splice(posicionTemp, 1);
        crearListadoTransaccionProducto();
    }

    dataProductos.push(obj);
    autoCompletarProducto();
    limpiarVariablesTransaccionProducto();
    crearListadoTransaccionProducto();
    posicionTemp = null;
}

function adicionarProducto() {
    if (productoSerial == true) {
        if (posicionTemp != null) {
            var obj = dataProductos[posicionTemp];
            if (obj.idPedidoProducto) {
                if (obj.idPedidoProducto != null && obj.idPedidoProducto != 'null' && obj.idPedidoProducto != undefined) {
                    if ($("#txtCantidad").val() > obj.cantidad) {
                        alerta(" La cantidad de este producto no puede ser mayor a la anterior. ");
                        return false;
                    }
                }
            }
            crearCamposSeriales($("#txtCantidad").val(), obj.serial);
        } else {
            crearCamposSeriales($("#txtCantidad").val(), null);
        }

        $("#spnTituloSeriales").html("Seriales del producto: " + $("#txtProducto").val());
        $("#pnlSeriales").show();

        consultarSerialesProducto();
    } else {
        $("#pnlSeriales").hide();
    }
}

function cargarFormatosImpresion() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cajaFormatoImpresion.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {estado: "true", idCaja: idCaja, codigoTipoNaturaleza: 'VE'},
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

            var control = $("#selFormatoImpresion");
            control.empty();
            control.append('<option value=""> --Seleccione-- </option>');
            var selected;
            $.each(json.data, function (contador, fila) {
                selected = '';
                if (fila.principal == true)
                    selected = 'selected="selected"';

                control.append("<option " + selected + " value='" + fila.idCajaFormatoImpresion + "' name='" + fila.archivo + "'> " + fila.formatoImpresion + " </option>");
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarInformacionCaja() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacion.consultarCaja.php',
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

            if (json.tienePermisoTipoDocumento == false) {
                alertaCallback(mensaje, 'recargarPagina()');
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            $.each(json.data, function (contador, fila) {
                idCaja = fila.idCaja;
                idBodega = fila.idBodega;
                prefijo = fila.prefijo;
                $("#spnInfoCaja").html("Tipo Documento: " + fila.tipoDocumento + " - Número: " + fila.numeroTipoDocumento);
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function asignarDatosEnvio() {
    nota = $("#txaNotaTransaccion").val();
    fechaVencimiento = $("#txtFechaVencimiento").val();
    idFormatoImpresion = $("#selFormatoImpresion").val();
}
function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idTercero=' + idTercero + '&idCliente=' + idCliente + '&nota=' + nota + '&fechaVencimiento=' + fechaVencimiento + '&idCaja=' + idCaja 
            + '&prefijo=' + prefijo + '&valorTotalPagar=' + valorTotalPagar + '&idFormatoImpresion=' + idFormatoImpresion 
            + '&dataFormasPago=' + JSON.stringify(dataFormasPago) + '&dataProductos=' + JSON.stringify(dataProductos) + '&idBodega=' + idBodega;
}
function autoCompletarTercero() {
    $("#txtNombre").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php?tipo=C',
        select: function (event, ui) {
            idTercero = ui.item.idTercero;
            idCliente = ui.item.idClienteProveedor;
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
        data: {nit: nit, tipo:'C'},
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
                    idCliente = fila.idClienteProveedor;
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
                    html += '<li><input id="linkCliente' + contadorClientes + '" onfocus="focoCampoCliente($(this))" onkeypress="keypressCampoCliente($(this), event)" type="text" class="campo_cliente"  data-dismiss="modal" onclick="asignar(' + fila.idTercero + ',' + fila.idCliente + ',' + "'" + fila.cliente + "'" + ')" value="' + fila.cliente + '"></li>';
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
function autoCompletarProducto() {
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarProducto.php',
        select: function (event, ui) {
            idProducto = ui.item.idProducto;
            $("#txtCodigoProducto").val(ui.item.codigo);
            $("#txtUnidadMedida").html(ui.item.unidadMedida);
            valorEntraConImpue = parseInt(Math.round(ui.item.valorEntraConImpue));
            $("#txtValor").val(numberFormat(ui.item.valorSalidConImpue));
            valorUnitarioEntrada = ui.item.valorEntrada;
            valorUnitarioSalida = ui.item.valorSalida;
            productoSerial = ui.item.productoSerial;

            if (permiteModificarValorVenta == true) {
                formatearNumeroDecimal("txtValor");
                $("#txtValor").removeAttr("readonly");
            } else {
                $("#txtValor").attr("readonly", "readonly");
            }
        }
    });
}
function consultarProducto(codigoProducto) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/transaccion.consultarProductos.php',
        type: 'POST',
        dataType: "json",
        data: {codigoProducto: codigoProducto
            , estado: true},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                $("#txtProducto").val("");
                $("#txtUnidadMedida").html("");
                $("#txtValor").val("");
                $("#txtValorTotal").val("");
                $("#txtCantidad").val("");
                idProducto = null;
                productoSerial = null;
                valorEntraConImpue = null;
                valorUnitarioEntrada = null;
                valorUnitarioSalida = null;
                $("#pnlSeriales").hide();
                return false;
            }

            $.each(json.data, function (contador, fila) {
                idProducto = fila.idProducto;
                $("#txtProducto").val(fila.producto);
                $("#txtUnidadMedida").html(fila.unidadMedida);
                $("#txtValor").val(numberFormat(fila.valorSalidConImpue));
                productoSerial = fila.productoSerial;
                valorEntraConImpue = parseInt(Math.round(fila.valorEntraConImpue));
                valorUnitarioEntrada = fila.valorEntrada;
                valorUnitarioSalida = fila.valorSalida;

                if (permiteModificarValorVenta == true) {
                    formatearNumeroDecimal("txtValor");
                    $("#txtValor").removeAttr("readonly");
                } else {
                    $("#txtValor").attr("readonly", "readonly");
                }

            });
            
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarListadoTransaccionProducto() {
    $("#divListadoTransaccionProducto").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Unidad Medida</th>';
    tabla += '<th>Presentación</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoTransaccionProducto").html(tabla);
}
function crearListadoTransaccionProducto() {
    $("#divListadoTransaccionProducto").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Unidad Medida</th>';
    tabla += '<th>Presentación</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';

    if (dataProductos.length == 0) {
        cargarListadoTransaccionProducto();
        $("#spnValorTotalPagar").html("");
        return false;
    }

    valorTotalPagar = 0;
    for (var i = 0; i < dataProductos.length; i++) {
        var obj = dataProductos[i];
        tabla += '<tr>';
        tabla += "<td align='center'>" + (i + 1) + "</td>";
        tabla += "<td>" + obj.codigo + "</td>";
        tabla += "<td>" + obj.producto + "</td>";
        tabla += "<td>" + obj.unidadMedida + "</td>";
        tabla += "<td>" + obj.unidadMedidaPresentacion + "</td>";
        tabla += "<td align='right'>" + numberFormat(obj.valorSalidConImpue) + "</td>";
        tabla += "<td align='center'>" + reemplazarPuntoPorComa(obj.cantidad) + "</td>";
        tabla += "<td align='right'>" + agregarSeparadorMil(String(obj.valorTotal)) + "</td>";

        if (obj.serial != null) {
            tabla += "<td>";
            for (var j = 0; j < obj.serial.length; j++) {
                tabla += (j + 1) + ". " + obj.serial[j] + "<br>";
            }
            tabla += "</td>";
        } else {
            tabla += "<td>&nbsp;</td>";
        }

        if (obj.nota != "" && obj.nota != null && obj.nota != "null") {
            tabla += "<td>" + obj.nota + "</td>";
        } else {
            tabla += "<td>&nbsp;</td>";
        }

        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgEditarTransaccionProducto' + i + '" title="Editar" class="imagenesTabla" onclick="editarTransaccionProducto(' + i + ')"></span></td>';
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarTransaccionProducto' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarTransaccionProducto(' + i + ')"></span></td>';
        tabla += '</tr>';
        valorTotalPagar += parseFloat(obj.valorTotal);
    }

    tabla += '</table>';
    $("#divListadoTransaccionProducto").html(tabla);
    $("#spnValorTotalPagar").html(numberFormat(valorTotalPagar));
    $("#txtValorPagar").val(agregarSeparadorMil(String(Math.round(valorTotalPagar))));
}
function editarTransaccionProducto(posicion) {
    posicionTemp = parseInt(posicion);
    var obj = dataProductos[posicion];
    idProducto = obj.idProducto;
    $("#hidIdTransaccionProducto").val(obj.idTransaccionProducto);
    $("#txtCodigoProducto").val(obj.codigo);
    $("#txtProducto").val(obj.producto);
    $("#txtUnidadMedida").html(obj.unidadMedida);
    $("#txtValor").val(numberFormat(obj.valorSalidConImpue));
    $("#txtValorTotal").val(agregarSeparadorMil(String(obj.valorTotal)));
    $("#selPresentacion").val(obj.idUnidadMedidaPresentacion);
    $("#txtCantidad").val(reemplazarPuntoPorComa(obj.cantidad));
    $("#txaNotaTransaccionProducto").val(obj.nota);
    valorEntraConImpue = obj.valorEntraConImpue;
    valorUnitarioEntrada = obj.valorUnitarioEntrada;
    valorUnitarioSalida = obj.valorUnitarioSalida;
    productoSerial = obj.productoSerial;
    if (obj.serial != null) {
        crearCamposSeriales(obj.cantidad, obj.serial);
        $("#pnlSeriales").show();
    }
    $("#txtCantidad").focus();
}
function eliminarTransaccionProducto(posicion) {
    var obj = dataProductos[posicion];
    idBorrar = obj.idTransaccionProducto;
    if (idBorrar != "" && idBorrar != null && idBorrar != "null") {
        arrIdEliminar.push(idBorrar);
    }
    dataProductos.splice(posicion, 1);

    autoCompletarProducto();
    crearListadoTransaccionProducto();
    $("#txtCodigoProducto").focus();
}
function limpiarVariablesTransaccionProducto() {
    idProducto = "";
    productoSerial = null;
    valorEntraConImpue = null;
    valorUnitarioEntrada = null;
    valorUnitarioSalida = null;
    validarVacioSerial = null;
    $("#hidIdTransaccionProducto").val("");
    $("#txtCodigoProducto").val("");
    $("#txtProducto").val("");
    $("#txtCantidad").val("");
    $("#txtValor").val("");
    $("#txtValorTotal").val("");
    $("#txtUnidadMedida").html("");
    $("#txaNotaTransaccionProducto").val("");
    $("#divFrmSeriales").html("");
    $("#pnlSeriales").hide();
    $("#txtCodigoProducto").focus();
    $("#selPresentacion").val("");
}
function crearCamposSeriales(cantidad, seriales) {
    var html = '<form id="frmSeriales" name="frmSeriales">';
    html += '<table>';
    for (var i = 0; i < cantidad; i++) {
        html += '<tr>';
        html += '<td>*Serial ' + (i + 1) + '</td>';

        if (seriales != null) {
            if (seriales[i] != undefined) {
                html += '<td><input type="text" id="txtSerial' + i + '" value="' + seriales[i] + '" class="form-control large" title="el serial ' + (i + 1) + '"></td>';
            } else {
                html += '<td><input type="text" id="txtSerial' + i + '" class="form-control large" title="el serial ' + (i + 1) + '"></td>';
            }
        } else {
            html += '<td><input type="text" id="txtSerial' + i + '" class="form-control large" title="el serial ' + (i + 1) + '"></td>';
        }

        html += '</tr>';
    }
    html += '</table>';
    html += '</form>';

    $("#divFrmSeriales").html(html);

    if (posicionTemp == null) {
        modal();
    } else {
        $("#pnlSeriales").addClass("contenido");
        $("#imgCerrarModal").hide();
    }

    realizarFoco(document.frmSeriales, 'adicionarTransaccionProducto()');
}
function consultarSerialesProducto() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacionProducto.consultarSerial.php',
        type: 'POST',
        dataType: "json",
        data: {idProducto: idProducto},
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

            $.each(json.data, function (contador, fila) {
                arrSerialesProductos.push(fila.serial);
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function validarExistenciaSerial(serial) {
    var retorno = false;
    for (var i = 0; i < arrSerialesProductos.length; i++) {
        if (serial == arrSerialesProductos[i]) {
            retorno = true;
        }
    }
    return retorno;
}
function validarSerialRepetido(seriales) {
    for (var i = 0; i < seriales.length; i++) {

        if (validarExistenciaSerial(seriales[i]) == false) {
            alert("El serial " + seriales[i] + " no se encuentra inventariado.");

            if (posicionTemp == null) {
                modal();
            }
            validarVacioSerial = true;
            $("#pnlSeriales").show();
            $("#txtSerial" + i).focus();
            return false;
        }

        for (var j = (i + 1); j < seriales.length; j++) {
            if (seriales[i] == seriales[j]) {
                alert("El serial " + seriales[i] + " está repetido.");

                if (posicionTemp == null) {
                    modal();
                }
                validarVacioSerial = true;
                $("#pnlSeriales").show();
                $("#txtSerial" + i).focus();
                return false;
            }
        }
    }
    return true;
}
function modal() {
    $("#modal").addClass("modal_personalizado");
    $("#pnlSeriales").removeClass("contenido");
    $("#pnlSeriales").addClass("contenido_modal");
    $("#imgCerrarModal").show();
    $("#modal").show(function () {
        if (validarVacioSerial != true) {
            $("#txtSerial0").focus();
        }
    });

    $("#imgCerrarModal").click(function () {
        cerrarModal();
    });
}
function cerrarModal() {
    $("#pnlSeriales").hide();
    $("#modal").hide();
    $("#modal").removeClass("modal_personalizado");
    $("#pnlSeriales").removeClass("contenido_modal");
}
function adicionarTransaccionProducto() {
    var estadoTransaccion = adicionarNuevaTransaccionProducto();
    if (estadoTransaccion != false) {
        cerrarModal();
    } else {
        if ($("#frmSeriales").length > 0) {
            var contador = 0;
            $("#frmSeriales input").each(function () {
                $("#txtSerial" + contador).unbind("keypress");
                $("#txtSerial" + contador).finish();
                contador++;
            });
        }
    }
}

//----------------------------FORMAS DE PAGO--------------------------------------
function obtenerFormaPagoDefecto() {
    var idFormaPago;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacion.obtenerFormaPagoDefecto.php',
        type: 'POST',
        dataType: "json",
        data: {codigo: codigParamFormaPagoDefec},
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje, true);
                return false;
            }

            idFormaPago = json.idTipoDocumento;

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
    return idFormaPago;
}
function cargarFormasPago() {
    var idFormaPagoDefecto = obtenerFormaPagoDefecto();
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
                if (idFormaPagoDefecto == fila.idFormaPago) {
                    control.append('<option selected value="' + fila.idFormaPago + '">' + fila.formaPago + '</option>');
                } else {
                    control.append('<option value="' + fila.idFormaPago + '">' + fila.formaPago + '</option>');
                }

            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function cargarListadoFormasPago() {
    $("#divListadoFormasPago").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Forma Pago</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoFormasPago").html(tabla);
}
function crearListadoFormasPago() {
    $("#divListadoFormasPago").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Forma Pago</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';

    if (dataFormasPago.length == 0) {
        cargarListadoFormasPago();
        return false;
    }
    valorTotalPagado = 0;
    for (var i = 0; i < dataFormasPago.length; i++) {
        var obj = dataFormasPago[i];
        tabla += '<tr>';
        tabla += "<td align='center'>" + (i + 1) + "</td>";
        tabla += "<td>" + obj.formaPago + "</td>";
        tabla += "<td align='right'>" + agregarSeparadorMil(String(obj.valor)) + "</td>";
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarFormaPago' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarFormaPago(' + i + ')"></span></td>';
        tabla += '</tr>';

        valorTotalPagado += parseFloat(obj.valor);
    }

    tabla += '</table>';
    $("#divListadoFormasPago").html(tabla);
}
function eliminarFormaPago(posicion) {
    var obj = dataFormasPago[posicion];
    $('#selFormaPago option[value=' + obj.idFormaPago + ']').show();
    $('#selFormaPago option[value=' + obj.idFormaPago + ']').attr("disabled", false);
    dataFormasPago.splice(posicion, 1);
    crearListadoFormasPago();
    $("#selFormaPago").focus();
}
function limpiarFormulario() {
    limpiarVariables();
    limpiarControlesFormulario(document.frmTransaccion);
    limpiarControlesFormulario(document.frmTransaccionProducto);
    limpiarControlesFormulario(document.frmFormaPago);
    cargarListadoTransaccionProducto();
    cargarListadoFormasPago();
    cargarFormasPago();
    cargarInformacionCaja();
    autoCompletarProducto();
    $("#txtFechaVencimiento").val(obtenerFechaActual());
}
function limpiarVariables() {
    //VARIABLES TRANSACCION
    idTransaccion = null;
    idTercero = null;
    idCliente = null;
    nota = null;
    fechaVencimiento = null;
    contadorFlecha = 0;
    contadorClientes = 0;

    //VARIABLES TRANSACCION PRODUCTO
    posicionTemp = null;
    dataProductos = new Array();
    arrIdEliminar = new Array();
    idProducto = null;
    productoSerial = null;
    valorEntraConImpue = null;
    validarVacioSerial = null;
    arrSerialesProductos = new Array();

    //VARIABLES FORMAS DE PAGO
    dataFormasPago = new Array();

    valorTotalPagar = 0;
    valorTotalPagado = 0;
    idFormatoImpresion = null;

    data = null;

    $("#spnValorTotalPagar").html("");
}
function alerta(mensaje, funcion) {
    if (funcion != null && funcion != "") {
        bootbox.dialog({
            message: mensaje,
            title: "Mensaje",
            buttons: {
                main: {
                    id: "btnCerrarModal",
                    label: "Aceptar",
                    className: "btn-primary",
                    "callback": function (e) {
                        eval(funcion);
                    }
                }
            }
        });
    } else {
        bootbox.dialog({
            message: mensaje,
            title: "Información",
            buttons: {
                main: {
                    id: "btnCerrarModal",
                    label: "Aceptar",
                    className: "btn-primary"
                }
            }
        });
    }
}
function validarCajero() {
    var retorno = true;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacion.validarCajero.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            permiteModificarValorVenta = json.modificaValorVenta;

            if (exito == 0) {
                retorno = false;
                alertaCallback(mensaje, 'recargarPagina()');
                return false;
            }

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
    return retorno;
}

function generarFactura() {
    var archivo = $("#selFormatoImpresion option:selected").attr("name");
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/' + archivo,
        type: 'POST',
        dataType: "json",
        data: {
            idTransaccion: idTransaccion
            , valorTotalPagar: valorTotalPagar
            , valorTotalPagado: valorTotalPagado
            , localStorage: localStorage.modulo
        },
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                throw mensaje;
                return false;
            }

            window.open(json.ruta, '_blank');
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

//Aqui obtendo el arreglo enviado desde el formulario de  pedidoMovil
function obtenerArregloPedidoMovil(arrRetorno) {
    if (arrRetorno.length > 0) {
        if (dataProductos.length > 0) {
            for (var i = 0; i < arrRetorno.length; i++) {
                var objArrRetorno = arrRetorno[i];
                dataProductos.push(objArrRetorno);
                if (objArrRetorno.productoSerial == true) {
                    var arrSerial = new Array();
                    arrSerial = objArrRetorno.serial;
                    for (var j = 0; j < arrSerial.length; j++) {
                        arrSerialesProductos.push(objArrRetorno.serial[j]);
                    }
                }
            }
        } else {
            dataProductos = arrRetorno;
            for (var i = 0; i < arrRetorno.length; i++) {
                var objArrRetorno = arrRetorno[i];
                if (objArrRetorno.productoSerial == true) {
                    var arrSerial = new Array();
                    arrSerial = objArrRetorno.serial;
                    for (var j = 0; j < arrSerial.length; j++) {
                        arrSerialesProductos.push(objArrRetorno.serial[j]);
                    }
                }
            }
        }
        crearListadoTransaccionProducto();
    }
}
function cargarPresentacionesProducto() {
    $.ajax({
        url: localStorage.modulo + 'controlador/producto.cargarUnidadMedida.php',
        type: 'POST',
        dataType: "json",
        data: null,
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

            var control = $('#selPresentacion');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                if (fila.principal == true) {
                    control.append('<option selected value="' + fila.idUnidadMedida + '">' + fila.unidadMedida + '</option>');
                } else {
                    control.append('<option value="' + fila.idUnidadMedida + '">' + fila.unidadMedida + '</option>');
                }

            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}