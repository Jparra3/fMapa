//VARIABLES TRANSACCION
var codigoTipoNaturaleza = "CO";
var codigoTipoNaturalezaConceptos = "IN-CO";
var codigParamFormaPagoDefec = "FPD-IN";
var valorRecibido = variableUrl();
var idTransaccion = valorRecibido[1];
var idConcepto = null;
var idTercero = null;
var idProveedor = null;
var idTransaccionEstado = null;
var nota = null;
var fecha = null;
var fechaVencimiento = null;
var documentoExterno = null;

//VARIABLES TRANSACCION PRODUCTO
var posicionTemp = null;
var dataProductos = new Array();
var arrIdEliminar = new Array();
var idProducto = null;
var productoSerial = null;
var idTipoImpuesto = null;
var valorImpuesto = null;
var impuesto = null;
var idProductosSeleccionados = new Array();
var totalCompra = null;

//VARIABLES FORMAS DE PAGO
var dataFormasPago = new Array();
var codigTipoNaturFormaPago = "CO-FP";
var totalPago = 0;

//BANDERAS - PARAMETROS
var requiereProveedor = null;
var requiereDocumentoExterno = null;
var requiereFormasPago = null;

var data = null;
$(function () {

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
        if (target == "#tabTransaccion") {
            $("#txtNit").focus();
        } else if (target == "#tabTransaccionProducto") {
            $("#txtCodigoProducto").focus();
        }
    });

    //CARGUE INICIAL TAB TRANSACCION
    $("#txtNit").focus();
    validarNumeros("txtNit");
    cargarEstados();
    autoCompletarTercero();
    crearCalendario("txtFecha");
    crearCalendario("txtFechaVencimiento");
    cargarTipoDocumento();

    $("#selTipoDocumento").change(function () {
        if ($(this).val() == "") {
            $("#txtNoDocumento").val("");
            $("#txtOficina").val("");
            $("#txtCodigoTipoDocumento").val("");
            return false;
        }
        var idTipoDocumento = $("#selTipoDocumento option:selected").attr("idTipoDocumento");
        requiereProveedor = $("#selTipoDocumento option:selected").attr("requiereProveedor");
        requiereDocumentoExterno = $("#selTipoDocumento option:selected").attr("requiereDocumentoExterno");
        requiereFormasPago = $("#selTipoDocumento option:selected").attr("requiereFormasPago");

        obtenerInfoTipoDocumento(idTipoDocumento);
        configurarCamposRequeridos();
    });
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
                idProveedor = null;
                break;
        }
    });

    $("#txtNombre").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNit").val("");
                idTercero = null;
                idProveedor = null;
                break;
        }
    });

    $("#txtCodigoTipoDocumento").change(function () {
        if ($("#txtCodigoTipoDocumento").val() != "") {
            consultarTipoDocumento($("#txtCodigoTipoDocumento").val());
        }
    });

    $("#txtCodigoTipoDocumento").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#selTipoDocumento").val("");
                $('#selTipoDocumento').multiselect('select', "", true);

                $("#txtNoDocumento").val("");
                $("#txtOficina").val("");
                break;
        }
    });

    $("#imgNuevoTercero").click(function () {
        abrirPopup('/Seguridad/vista/frmTercero.html?id=&origen=1', 'Tercero');
    });

    //CARGUE INICIAL TAB TRANSACCION PRODUCTO
    realizarFoco(document.frmTransaccionProducto, '$("#imgNuevoTransaccionProducto").click()');

    autoCompletarProducto();
    cargarBodegas();
    //validarNumeros("txtCantidad");
    formatearNumeroDecimal("txtvalorUnitarioEntrada");
    formatearNumeroDecimal("txtvalorUnitarioSalida");
    cargarListadoTransaccionProducto();
    obtenerValorEtiquetaConImpuesto();
    cargarListadoImpuestos();

    $("#txtCodigoProducto").change(function () {
        if ($("#txtCodigoProducto").val() != "") {
            consultarProducto($("#txtCodigoProducto").val());
        }
    });

    $("#txtCodigoProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 13:
                if ($("#txtCodigoProducto").val() != "") {
                    consultarProducto($("#txtCodigoProducto").val());
                }
                break;
            case 08 || 46:
                $("#txtProducto").val("");
                $("#txtUnidadMedida").val("");
                $("#txtCantidad").val("");
                idProducto = null;
                productoSerial = null;
                idTipoImpuesto = null;
                valorImpuesto = null;
                impuesto = null;
                $("#pnlSeriales").hide();
                $("#txtvalorUnitarioEntrada").val("");
                $("#txtvalorUnitarioSalida").val("");
                $(".spnImpuestoProducto").html("");
                break;
        }
    });

    $("#txtProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProducto").val("");
                $("#txtUnidadMedida").val("");
                $("#txtCantidad").val("");
                idProducto = null;
                productoSerial = null;
                idTipoImpuesto = null;
                valorImpuesto = null;
                impuesto = null;
                $("#pnlSeriales").hide();
                $("#txtvalorUnitarioEntrada").val("");
                $("#txtvalorUnitarioSalida").val("");
                $(".spnImpuestoProducto").html("");
                break;
        }
    });

    $("#imgNuevoProducto").click(function () {
        abrirPopup(localStorage.modulo + 'vista/frmProducto.html?id=&origen=1', 'Productos');
    });

    $("#txtCantidad").change(function () {
        if (productoSerial == true) {
            if (posicionTemp != null) {
                var obj = dataProductos[posicionTemp];
                crearCamposSeriales($("#txtCantidad").val(), obj.serial);
            } else {
                crearCamposSeriales($("#txtCantidad").val(), null);
            }

            $("#spnTituloSeriales").html("Seriales del producto: " + $("#txtProducto").val());
            $("#pnlSeriales").show();
        } else {
            $("#pnlSeriales").hide();
        }
    });


    //CARGUE INICIAL TAB TRANSACCION FORMA DE PAGO
    cargarFormasPago();
    formatearNumero("txtValorPagar");
    realizarFoco(document.frmTransaccionFormaPago, '$("#imgNuevaFormaPago").click()');
    cargarListadoFormasPago();

    $("#txtValorPagar").keypress(function (e) {
        switch (e.keyCode) {
            case 13:
                return false;
                break;
        }
    });

    $("#imgNuevaFormaPago").click(function () {
        if (validarVacios(document.frmTransaccionFormaPago) == false)
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


    $("#imgGuardar").click(function () {
        obtenerDatosEnvio();

        if (validarVacios(document.frmTransaccion) == false)
            return false;

        if (dataProductos.length == 0) {
            alerta("Debe adicionar mínimo un producto.");
            return false;
        }

        if (requiereFormasPago == "true") {
            if (dataFormasPago.length == 0) {
                alerta("Debe adicionar mínimo una forma de pago.");
                return false;
            }

            if (totalCompra > totalPago) {
                alerta("El valor pagado es menor al total de la compra.");
                return false;
            }
        }

        var numeroControlesVacios = validarVaciosConsultar(document.frmTransaccionProducto) - 1;
        var diferenciaControles = (document.frmTransaccionProducto.length - 3) - parseInt(numeroControlesVacios);//Saber diferencia de controles si es solo uno es por el select de bodega que siempre tiene valor

        if (diferenciaControles > 1) {
            alerta("Por favor adicione el producto ingresado, dando clic en el icono de adicionar (+)");
            return false;
        }

        try {
            $.ajax({
                async: false,
                url: localStorage.modulo + 'controlador/transaccion.adicionar.php',
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

                    alerta(mensaje, true);

                }, error: function (xhr, opciones, error) {
                    throw error;
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

        if (validarVacios(document.frmTransaccionProducto) == false)
            return false;

        if (idProducto == null || idProducto == "" || idProducto == "null") {
            alerta("Por favor indique el producto");
            return false;
        }		
        var obj = new Object();
        if (productoSerial == true) {
            var cantidad = $("#txtCantidad").val();
            var seriales = new Array();
            var serialesInterno = new Array();
            for (var i = 0; i < cantidad; i++) {

                if ($.trim($("#txtSerial" + i).val()) == "") {
                    $("#txtSerial" + i).focus();
                    return false;
                }
                
                if ($.trim($("#txtSerialInterno" + i).val()) == "") {
                    $("#txtSerialInterno" + i).focus();
                    return false;
                }

                seriales[i] = $("#txtSerial" + i).val();
                serialesInterno[i] = $("#txtSerialInterno" + i).val();
            }

            if (validarSerialRepetido(seriales) == false) {
                return false;
            }
            if (validarSerialInternoRepetido(serialesInterno) == false) {
                return false;
            }
            obj.serial = seriales;
            obj.serialInterno = serialesInterno;
        } else {
            obj.serial = null;
            obj.serialInterno = null;
        }
		console.log('Sii');
        obj.idTransaccionProducto = $("#hidIdTransaccionProducto").val();
        obj.idProducto = idProducto;
        obj.idTipoImpuesto = idTipoImpuesto;
        obj.valorImpuesto = valorImpuesto;
        obj.impuesto = impuesto;
        obj.codigo = $("#txtCodigoProducto").val();
        obj.producto = $("#txtProducto").val();
        obj.productoSerial = productoSerial;
        obj.unidadMedida = $("#txtUnidadMedida").val();
        obj.cantidad = $("#txtCantidad").val();
        //obj.valorUnitarioEntrada = quitarSeparadorMil($("#txtvalorUnitarioEntrada").val());
        //obj.valorUnitarioSalida = quitarSeparadorMil($("#txtvalorUnitarioSalida").val());
        obj.valorUnitarioEntrada = $("#txtvalorUnitarioEntrada").val();
        obj.valorUnitarioSalida = $("#txtvalorUnitarioSalida").val();
        obj.impuesto = $(".spnImpuestoProducto").html();
        obj.idBodega = $("#selBodega").val();
        obj.bodega = $("#selBodega option:selected").text();
        obj.nota = $("#txaNotaTransaccionProducto").val();

        if (posicionTemp != null) {
            dataProductos.splice(posicionTemp, 1);
            idProductosSeleccionados.splice(posicionTemp, 1);
            crearListadoTransaccionProducto();
        }

        dataProductos.push(obj);
        idProductosSeleccionados.push(idProducto);
        autoCompletarProducto();
        limpiarVariablesTransaccionProducto();
        crearListadoTransaccionProducto();
        posicionTemp = null;
		
        obtenerConsolidadoImpuestos();		
        actualizarTotalCompra();				
    });
});
function asignarDatosEnvio() {
    idConcepto = $("#selTipoDocumento").val();
    idTransaccionEstado = $("#selTransaccionEstado").val();
    nota = $("#txaNotaTransaccion").val();
    fecha = $("#txtFecha").val();
    fechaVencimiento = $("#txtFechaVencimiento").val();
    documentoExterno = $("#txtDocumentoExterno").val();
}
function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idTransaccion=' + idTransaccion + '&idConcepto=' + idConcepto + '&idTercero=' + idTercero + '&idProveedor=' + idProveedor
            + '&idTransaccionEstado=' + idTransaccionEstado + '&nota=' + nota + '&fecha=' + fecha + '&fechaVencimiento=' + fechaVencimiento
            + '&documentoExterno=' + documentoExterno + '&totalCompra=' + totalCompra + '&dataFormasPago=' + JSON.stringify(dataFormasPago)
            + '&dataProductos=' + JSON.stringify(dataProductos);
}
function cargarTipoDocumento() {
    $.ajax({
        url: localStorage.modulo + 'controlador/concepto.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {estado: true
            , codigoTipoNaturaleza: codigoTipoNaturalezaConceptos},
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
                control.append('<option requiereFormasPago="' + fila.requiereFormasPago + '" requiereDocumentoExterno="' + fila.requiereDocumentoExterno
                        + '" requiereProveedor="' + fila.requiereProveedor + '" idTipoDocumento="' + fila.idTipoDocumento
                        + '" value="' + fila.idConcepto + '">' + fila.tipoDocumento + '</option>');
            });

            $('#selTipoDocumento').multiselect({
                maxHeight: 300
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , enableCaseInsensitiveFiltering: true
            });
            $("button.multiselect").css("width", "170px");
            $("input.form-control.multiselect-search").attr("accesskey", "V");

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function obtenerInfoTipoDocumento(idTipoDocumento) {
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.obtenerInfoTipoDocumento.php',
        type: 'POST',
        dataType: "json",
        data: {idTipoDocumento: idTipoDocumento},
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            $.each(json.data, function (contador, fila) {
                $("#txtNoDocumento").val(fila.numeroTipoDocumento);
                $("#txtOficina").val(fila.oficina);
                $("#txtCodigoTipoDocumento").val(fila.codigo);
            });

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
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
function autoCompletarTercero() {
    $("#txtNombre").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autocompletarTerceroSucursal.php',
        select: function (event, ui) {
            idTercero = ui.item.idTercero;
            idProveedor = ui.item.idProveedor;
            $("#txtNit").val(ui.item.nit);
        }
    });
}
function consultarTercero(nit) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/transaccion.consultarTerceroSucursal.php',
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
                idProveedor = null;
                return false;
            }

            if (json.numeroRegistros == 1) {
                $.each(json.data, function (contador, fila) {
                    idTercero = fila.idTercero;
                    idProveedor = fila.idProveedor;
                    $("#txtNombre").val(fila.terceroSucursal);
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
                $.each(json.data, function (contador, fila) {
                    html += '<li><a data-dismiss="modal" style="cursor:pointer" onclick="asignar(' + fila.idTercero + ',' + fila.idProveedor + ',' + "'" + fila.terceroSucursal + "'" + ')">' + fila.terceroSucursal + '</li>';
                });
                html += '</ul>';

                bootbox.alert(html);
            }

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function asignar(idTercer, idProveedo, terceroSucursal) {
    idTercero = idTercer;
    idProveedor = idProveedo;
    $("#txtNombre").val(terceroSucursal);
}
function consultarTipoDocumento(codigo) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/tipoDocumento.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {codigo: codigo
            , validarPermisos: true
            , estado: true
            , codigoTipoNaturaleza: codigoTipoNaturalezaConceptos},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje, true);
                return false;
            }

            if (json.numeroRegistros == 0) {
                idConcepto = null;
                requiereProveedor = null;
                requiereDocumentoExterno = null;
                requiereFormasPago = null;
                $("#selTipoDocumento").val("");
                $('#selTipoDocumento').multiselect('select', "", true);

                $("#txtNoDocumento").val("");
                $("#txtOficina").val("");

                alerta("No se encontró ningún tipo de documento con el código ingresado.");

                return false;
            }

            $.each(json.data, function (contador, fila) {
                idConcepto = fila.concepto.idConcepto;
                requiereProveedor = fila.concepto.requiereProveedor.toString();
                requiereDocumentoExterno = fila.concepto.requiereDocumentoExterno.toString();
                requiereFormasPago = fila.concepto.requiereFormasPago.toString();

                $("#selTipoDocumento").val(idConcepto);
                $('#selTipoDocumento').multiselect('select', idConcepto, true);

                $("#txtNoDocumento").val(fila.proximoNumero);
                $("#txtOficina").val(fila.oficina);
            });

            configurarCamposRequeridos();

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function autoCompletarProducto() {
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarProducto.php?idProductos=' + idProductosSeleccionados.join() + '&compuesto=false',
        select: function (event, ui) {
            idProducto = ui.item.idProducto;
            idTipoImpuesto = ui.item.idTipoImpuesto;
            valorImpuesto = ui.item.valorImpuesto;
            impuesto = ui.item.impuesto;
            $("#txtCodigoProducto").val(ui.item.codigo);
            $("#txtUnidadMedida").val(ui.item.unidadMedida);
            productoSerial = ui.item.productoSerial;
            $("#txtvalorUnitarioEntrada").val(numberFormat(ui.item.valorEntradaMostrar));
            $("#txtvalorUnitarioSalida").val(numberFormat(ui.item.valorSalidaMostrar));
            $(".spnImpuestoProducto").html(ui.item.impuesto);
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
            , idProducto: idProductosSeleccionados.join()
            , compuesto: false},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                $("#txtProducto").val("");
                $("#txtUnidadMedida").val("");
                $("#txtCantidad").val("");
                idProducto = null;
                idTipoImpuesto = null;
                valorImpuesto = null;
                impuesto = null;
                productoSerial = null;
                $("#pnlSeriales").hide();
                $("#txtvalorUnitarioEntrada").val("");
                $("#txtvalorUnitarioSalida").val("");
                $(".spnImpuestoProducto").html("");
                return false;
            }

            $.each(json.data, function (contador, fila) {
                idProducto = fila.idProducto;
                idTipoImpuesto = fila.idTipoImpuesto;
                valorImpuesto = fila.valorImpuesto;
                impuesto = fila.impuesto;
                $("#txtProducto").val(fila.producto);
                $("#txtUnidadMedida").val(fila.unidadMedida);
                productoSerial = fila.productoSerial;
                $("#txtvalorUnitarioEntrada").val(numberFormat(fila.valorEntradaMostrar));
                $("#txtvalorUnitarioSalida").val(numberFormat(fila.valorSalidaMostrar));
                $(".spnImpuestoProducto").html(fila.impuesto);
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarBodegas() {
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
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

            var control = $('#selBodega');
            control.empty();

            if (json.numeroRegistros > 1) {
                control.append('<option value="">--Seleccione--</option>');
            }

            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });

        }, error: function (xhr, opciones, error) {
            alert(error);
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
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Valor compra</th>';
    tabla += '<th>Valor venta</th>';
    tabla += '<th>Impuesto</th>';
    tabla += '<th>Bodega</th>';
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
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Serial Interno</th>';
    tabla += '<th>Valor compra</th>';
    tabla += '<th>Valor venta</th>';
    tabla += '<th>Impuesto</th>';
    tabla += '<th>Bodega</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';

    if (dataProductos.length == 0) {
        cargarListadoTransaccionProducto();
        return false;
    }

    for (var i = 0; i < dataProductos.length; i++) {
        var obj = dataProductos[i];
        tabla += '<tr>';
        tabla += "<td align='center'>" + (i + 1) + "</td>";
        tabla += "<td>" + obj.codigo + "</td>";
        tabla += "<td>" + obj.producto + "</td>";
        tabla += "<td>" + obj.unidadMedida + "</td>";
        tabla += "<td align='center'>" + obj.cantidad + "</td>";

        if (obj.serial != null) {
            tabla += "<td>";
            for (var j = 0; j < obj.serial.length; j++) {
                tabla += (j + 1) + ". " + obj.serial[j] + "<br>";
            }
            tabla += "</td>";
        } else {
            tabla += "<td>&nbsp;</td>";
        }
        
        if (obj.serialInterno != null) {
            tabla += "<td>";
            for (var j = 0; j < obj.serialInterno.length; j++) {
                tabla += (j + 1) + ". " + obj.serialInterno[j] + "<br>";
            }
            tabla += "</td>";
        } else {
            tabla += "<td>&nbsp;</td>";
        }

        tabla += "<td align='right'>" + obj.valorUnitarioEntrada + "</td>";
        tabla += "<td align='right'>" + obj.valorUnitarioSalida + "</td>";
        tabla += "<td>" + obj.impuesto + "</td>";
        tabla += "<td>" + obj.bodega + "</td>";

        if (obj.nota != "" && obj.nota != null && obj.nota != "null") {
            tabla += "<td>" + obj.nota + "</td>";
        } else {
            tabla += "<td>&nbsp;</td>";
        }

        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgEditarTransaccionProducto' + i + '" title="Editar" class="imagenesTabla" onclick="editarTransaccionProducto(' + i + ')"></span></td>';
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarTransaccionProducto' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarTransaccionProducto(' + i + ')"></span></td>';
        tabla += '</tr>';
    }

    tabla += '</table>';
    $("#divListadoTransaccionProducto").html(tabla);
}
function editarTransaccionProducto(posicion) {
    var obj = dataProductos[posicion];
    idProducto = obj.idProducto;
    idTipoImpuesto = obj.idTipoImpuesto;
    valorImpuesto = obj.valorImpuesto;
    impuesto = obj.impuesto;
    $("#hidIdTransaccionProducto").val(obj.idTransaccionProducto);
    $("#txtCodigoProducto").val(obj.codigo);
    $("#txtProducto").val(obj.producto);
    $("#txtUnidadMedida").val(obj.unidadMedida);
    $("#txtCantidad").val(obj.cantidad);
    $("#txtvalorUnitarioEntrada").val(obj.valorUnitarioEntrada);
    $("#txtvalorUnitarioSalida").val(obj.valorUnitarioSalida);
    $("#txaNotaTransaccionProducto").val(obj.nota);
    $(".spnImpuestoProducto").html(obj.impuesto);

    productoSerial = obj.productoSerial;
    if (obj.serial != null) {
        crearCamposSeriales(obj.cantidad, obj.serial);
        $("#pnlSeriales").show();
    }
    $("#txtCantidad").focus();
    posicionTemp = parseInt(posicion);

    actualizarTotalCompra();
}
function eliminarTransaccionProducto(posicion) {
    var obj = dataProductos[posicion];
    idBorrar = obj.idTransaccionProducto;
    if (idBorrar != "" && idBorrar != null && idBorrar != "null") {
        arrIdEliminar.push(idBorrar);
    }
    dataProductos.splice(posicion, 1);
    idProductosSeleccionados.splice(posicion, 1);

    autoCompletarProducto();
    crearListadoTransaccionProducto();
    $("#txtCodigoProducto").focus();
    obtenerConsolidadoImpuestos();
    actualizarTotalCompra();
}
function limpiarVariablesTransaccionProducto() {
    idProducto = "";
    idTipoImpuesto = null;
    valorImpuesto = null;
    impuesto = null;
    productoSerial = null;
    documentoExterno = null;
    $("#hidIdTransaccionProducto").val("");
    $("#txtCodigoProducto").val("");
    $("#txtProducto").val("");
    $("#txtCantidad").val("");
    $("#txtUnidadMedida").val("");
    $("#txtvalorUnitarioEntrada").val("");
    $("#txtvalorUnitarioSalida").val("");
    $("#txaNotaTransaccionProducto").val("");
    $(".spnImpuestoProducto").html("");
    $("#divFrmSeriales").html("");
    $("#pnlSeriales").hide();
    $("#txtCodigoProducto").focus();
}
function crearCamposSeriales(cantidad, seriales) {
    var html = '<form id="frmSeriales" name="frmSeriales">';
    html += '<table>';
    for (var i = 0; i < cantidad; i++) {
        if (seriales != null) {
            if (seriales[i] != undefined) {
                html += '<tr><td><input type="text" id="txtSerial' + i + '" value="' + seriales[i] + '" class="form-control large" title="el serial ' + (i + 1) + '" placeholder="*Serial ' + (i + 1) + '"></td></tr>';
                html += '<tr><td><input type="text" id="txtSerialInterno' + i + '" value="' + seriales[i] + '" class="form-control large" title="el serial interno ' + (i + 1) + '" placeholder="*Serial Interno' + (i + 1) + '"></td></tr>';
            } else {
                html += '<tr><td><input type="text" id="txtSerial' + i + '" class="form-control large" title="el serial ' + (i + 1) + '" placeholder="*Serial ' + (i + 1) + '"></td></tr>';
                html += '<tr><td><input type="text" id="txtSerialInterno' + i + '" class="form-control large" title="el serial interno ' + (i + 1) + '" placeholder="*Serial Interno ' + (i + 1) + '"></td></tr>';
            }
        } else {
            html += '<tr><td><input type="text" id="txtSerial' + i + '" class="form-control large" title="el serial ' + (i + 1) + '" placeholder="*Serial ' + (i + 1) + '"></td><tr>';
            html += '<tr><td><input type="text" id="txtSerialInterno' + i + '" class="form-control large" title="el serial interno ' + (i + 1) + '" placeholder="*Serial Interno ' + (i + 1) + '"></td></tr>';
        }
        html += '<tr><td>&nbsp;</td></tr>';
    }
    html += '</table>';
    html += '</form>';
    $("#divFrmSeriales").html(html);
    realizarFoco(document.frmSeriales, '$("#imgNuevoTransaccionProducto").click()');
}
function asignarIdProducto() {
    idProducto = $("#hidIdProducto").val();
    consultarInfoProducto(idProducto);
}
function consultarInfoProducto(idProducto) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/producto.consultar.php',
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

            $.each(json.data, function (contador, fila) {
                $("#txtCodigoProducto").val(fila.codigo);
                $("#txtProducto").val(fila.producto);
                $("#txtUnidadMedida").val(fila.unidadMedida);
                productoSerial = fila.productoSerial;
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function validarSerialRepetido(seriales) {
    for (var i = 0; i < seriales.length; i++) {
        for (var j = (i + 1); j < seriales.length; j++) {
            if (seriales[i] == seriales[j]) {
                alert("El serial " + seriales[i] + " está repetido.");
                $("#txtSerial" + i).focus();
                return false;
            }
        }
    }
    return true;
}
function validarSerialInternoRepetido(serialesInterno) {
    for (var i = 0; i < serialesInterno.length; i++) {
        for (var j = (i + 1); j < serialesInterno.length; j++) {
            if (serialesInterno[i] == serialesInterno[j]) {
                alert("El serial interno " + serialesInterno[i] + " está repetido.");
                $("#txtSerialInterno" + i).focus();
                return false;
            }
        }
    }
    return true;
}
function obtenerValorEtiquetaConImpuesto() {
    $.ajax({
        async: false,
        url: "/Seguridad/controlador/parametroAplicacion.consultar.php"
        , type: 'POST'
        , dataType: "json"
        , data: {idParametroAplicacion: '19,20'}
        , success: function (json) {

            if (json.exito == 0) {
                alerta(json.mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            $.each(json.data, function (contador, fila) {
                if (fila.idParametroAplicacion == 19) {
                    if (fila.valor == "1")
                        $("#spnTieneImpuestoValorEntrada").html("Con impuesto incluido");
                    else
                        $("#spnTieneImpuestoValorEntrada").html("Sin impuesto incluido");
                }

                if (fila.idParametroAplicacion == 20) {
                    if (fila.valor == "1")
                        $("#spnTieneImpuestoValorSalida").html("Con impuesto incluido");
                    else
                        $("#spnTieneImpuestoValorSalida").html("Sin impuesto incluido");
                }
            });
        }
    });
}

//FORMAS DE PAGO
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
    for (var i = 0; i < dataFormasPago.length; i++) {
        var obj = dataFormasPago[i];
        tabla += '<tr>';
        tabla += "<td align='center'>" + (i + 1) + "</td>";
        tabla += "<td>" + obj.formaPago + "</td>";
        tabla += "<td align='right'>" + agregarSeparadorMil(String(obj.valor)) + "</td>";
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarFormaPago' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarFormaPago(' + i + ')"></span></td>';
        tabla += '</tr>';
    }
    tabla += '<tr>';
    tabla += "<td colspan='2' align='right'><b>Total</b></td>";
    tabla += "<td id='tdTotalPago' align='right'></td>";
    tabla += "<td>&nbsp;</td>";
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoFormasPago").html(tabla);
    actualizarTotalPago();
}
function eliminarFormaPago(posicion) {
    var obj = dataFormasPago[posicion];
    $('#selFormaPago option[value=' + obj.idFormaPago + ']').show();
    $('#selFormaPago option[value=' + obj.idFormaPago + ']').attr("disabled", false);
    dataFormasPago.splice(posicion, 1);
    crearListadoFormasPago();
    $("#selFormaPago").focus();
    actualizarTotalPago();
}
function obtenerConsolidadoImpuestos() {
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.calcularImpuestosTemporal.php',
        type: 'POST',
        dataType: "json",
        data: {dataProductos: JSON.stringify(dataProductos)},
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                cargarListadoImpuestos();
            }

            crearListadoImpuestos(json);

        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function cargarListadoImpuestos() {
    $("#divListadoImpuestos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Impuesto</th>';
    tabla += '<th>Base</th>';
    tabla += '<th>Valor</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoImpuestos").html(tabla);
}
function crearListadoImpuestos(json) {
    $("#divListadoImpuestos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Impuesto</th>';
    tabla += '<th>Base</th>';
    tabla += '<th>Valor</th>';
    tabla += '</tr>';

    if (json.numeroRegistros == 0) {
        cargarListadoImpuestos();
        return false;
    }

    $.each(json.data, function (contador, fila) {
        tabla += '<tr>';
        tabla += "<td align='center'>" + (contador + 1) + "</td>";
        tabla += "<td>" + fila.impuesto + "</td>";
        tabla += "<td align='right'>" + agregarSeparadorMil(String(fila.base)) + "</td>";
        tabla += "<td align='right'>" + agregarSeparadorMil(String(fila.valor)) + "</td>";
        tabla += '</tr>';
    });

    tabla += '</table>';
    $("#divListadoImpuestos").html(tabla);
}
function actualizarTotalCompra() {
    totalCompra = 0;

    for (var i = 0; i < dataProductos.length; i++) {

        var obj = dataProductos[i];

        var valorCompra = obj.valorUnitarioEntrada;
        valorCompra = valorCompra.replace(/\./g, "");	
        valorCompra = valorCompra.replace(",", ".");		
        var cantidad = obj.cantidad;
        totalCompra += (valorCompra * cantidad);
    }	
	console.log(valorCompra);
	console.log(totalCompra);
	console.log(Math.round(totalCompra));
	console.log(Math.round(totalCompra).toString());
    $("#spnTotalCompra").html("$" + agregarSeparadorMil(Math.round(totalCompra).toString()));	
}
function actualizarTotalPago() {
    totalPago = 0;

    for (var i = 0; i < dataFormasPago.length; i++) {
        var obj = dataFormasPago[i];
        totalPago += parseInt(obj.valor);
    }
    $("#tdTotalPago").html(agregarSeparadorMil(totalPago.toString()));
}
function configurarCamposRequeridos() {

    if (requiereProveedor == "true") {
        $("#trProveedor").show();
        $("#txtNit").removeAttr("accesskey");
        $("#txtNombre").removeAttr("accesskey");
    } else {
        $("#trProveedor").hide();
        $("#txtNit").attr("accesskey", "V");
        $("#txtNombre").attr("accesskey", "V");
        idTercero = 0;
    }

    if (requiereDocumentoExterno == "true") {
        $("#trDocumentoExterno").show();
    } else {
        $("#trDocumentoExterno").hide();
    }

    if (requiereFormasPago == "true") {
        $("#liFormasPago").show();
    } else {
        $("#liFormasPago").hide();
    }
}