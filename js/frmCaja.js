var codigTipoNaturFormaPago = "CA-FP";
var codigoTipoNaturalezaConceptos = "'CA-CO', 'CA-VE'";
var codigParamFormaPagoDefec = "FPD-CA";
//VARIABLES TRANSACCION
var idTercero = null;
var idClienteProveedor = null;
var tipoClienteProveedor = null;
var notaTransaccion = null;
var fechaVencimiento = null;
var contadorFlecha = 0;
var contadorClientes = 0;
var archivoPhp = "";
var arrCreditosAdicionado = new Array();

//VARIABLES TRANSACCION PRODUCTO
var idBodega = null;
var idCaja = null;
var prefijo = null;
var posicionTemp = null;
var dataProductos = new Array();
var arrIdEliminar = new Array();
var idProducto = null;
var idConcepto = null;
var tipoDocumento = null;
var productoSerial = null;
var valorUnitarioEntrada = null;
var valorUnitarioSalida = null;
var valorEntraConImpue = null;
var validarVacioSerial = null;
var arrSerialesProductos = new Array();
var arrConceptoPago = new Array();
var formulario = null;
var idTipoDocumento = null;
var permiteMultipleConceptos = null;

//VARIABLES FORMAS DE PAGO
var dataFormasPago = new Array();

var valorTotalPagar = 0;
var valorTotalPagado = 0;

var abreFrmAparte = false;

var data = null;
$(function () {

    if (validarCajero() == false)
        return false;
    
    //Se valida si permite cruce 
    permiteMultipleConceptos = obtenerPermiteMultipleConceptos();

    formatearNumero("txtValor");

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
                idClienteProveedor = null;
                tipoClienteProveedor = null;
                break;
        }
    });

    $("#txtNombre").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNit").val("");
                idTercero = null;
                idClienteProveedor = null;
                tipoClienteProveedor = null;
                break;
        }
    });

    //CARGUE INICIAL TAB TRANSACCION PRODUCTO
    realizarFoco(document.frmTransaccionProducto, '$("#imgNuevoTransaccionProducto").click()');
    autoCompletarProducto();
    cargarListadoTransaccionProducto();

    $("#txtCodigoProducto").change(function () {
        if ($("#txtCodigoProducto").val() != "" && idClienteProveedor != null && idClienteProveedor != "") {
            consultarProducto($("#txtCodigoProducto").val());
        } else {
            if (idClienteProveedor == null && $("#txtCodigoProducto").val() != "") {
                alerta("Debe seleccionr un cliente valido.");
            }
        }
    });

    $("#txtCodigoProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtProducto").val("");
                $("#txtValor").val("");
                idConcepto = null;
                productoSerial = null;
                valorEntraConImpue = null;
                valorUnitarioEntrada = null;
                valorUnitarioSalida = null;
                formulario = null;
                idTipoDocumento = null;
                $("#pnlSeriales").hide();

                break;
        }
    });

    $("#txtProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProducto").val("");
                $("#txtValor").val("");
                idConcepto = null;
                productoSerial = null;
                valorEntraConImpue = null;
                valorUnitarioEntrada = null;
                valorUnitarioSalida = null;
                formulario = null;
                idTipoDocumento = null;
                $("#pnlSeriales").hide();

                break;
        }
    });

    $("#imgGuardar").click(function () {
        obtenerDatosEnvio();

        if (validarVacios(document.frmTransaccion) == false)
            return false;

        if (dataProductos.length == 0) {
            alerta("Debe adicionar mínimo un concepto.");
            return false;
        }

        if(permiteMultipleConceptos != "TRUE" && permiteMultipleConceptos != true){
            if (dataProductos.length != 1) {
                alerta("No es posible adicionar más de un concepto.");
                return false;
            }
        }

        if (dataFormasPago.length == 0) {
            alerta("Debe adicionar mínimo una forma de pago.");
            return false;
        }

        if (valorTotalPagado < valorTotalPagar) {
            alerta("El valor del pago es menor al total a pagar.");
            return false;
        }
        if (valorTotalPagado > valorTotalPagar) {
            alerta("El valor del pago es mayor al total a pagar.");
            return false;
        }

        if ($("#selFormatoImpresion").val() == '' || $("#selFormatoImpresion").val() == 'null' || $("#selFormatoImpresion").val() == null) {
            alerta("Debe seleccionar el formato de impresión.");
            return false;
        }

        adicionarPagos();
    });

    $("#imgNuevoTransaccionProducto").click(function () {
        if(permiteMultipleConceptos != "TRUE" && permiteMultipleConceptos != true){
            if (dataProductos.length == 1) {
                alerta("No es posible agregar más de un concepto.");
                return false;
            }
        }
        
        if(abreFrmAparte == true){
            if($("#txtJsonConcepto").val() == ""){
                alerta("Debe diligenciar el formulario");
                return false;
            }
        }
        
        adicionarNuevoConcepto();
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
        if (dataProductos.length == 0) {
            alerta("Debe agregar almenos un concepto.");
            return false;
        }
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
});
function adicionarNuevoConcepto(estado) {
    if (validarVacios(document.frmTransaccionProducto) == false)
        return false;

    //Pendiente 
    /*if(idConcepto == null || idConcepto == "" || idConcepto == "null"){
     alerta("Por favor indique el concepto");
     return false;
     }*/

    var obj = new Object();
    if (productoSerial == true) {
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
    obj.idConcepto = idConcepto;
    obj.codigo = $("#txtCodigoProducto").val();
    obj.tipoDocumento = $("#txtProducto").val();
    obj.nota = $("#txaNota").val();
    obj.productoSerial = productoSerial;
    obj.valorEntraConImpue = valorEntraConImpue;
    obj.valorSalidConImpue = quitarSeparadorMil($("#txtValor").val());
    obj.valorUnitarioEntrada = valorUnitarioEntrada;
    obj.valorUnitarioSalida = valorUnitarioSalida;

    var objConceptoPago = new Object();
    objConceptoPago.nota = $("#txaNota").val();
    objConceptoPago.idConcepto = idConcepto;
    objConceptoPago.idClienteProveedor = idClienteProveedor;
    objConceptoPago.tipoClienteProveedor = tipoClienteProveedor;
    objConceptoPago.valorPago = quitarSeparadorMil($("#txtValor").val());
    objConceptoPago.archivo = archivoPhp;
    objConceptoPago.parametros = $("#txtJsonConcepto").val();
    if (posicionTemp != null) {
        dataProductos.splice(posicionTemp, 1);
        arrCodigoTipoPago.splice(posicionTemp, 1);
    }

    dataProductos.push(obj);
    arrConceptoPago.push(objConceptoPago);
    autoCompletarProducto();
    limpiarVariablesTransaccionProducto();
    crearListadoTransaccionProducto();
    posicionTemp = null;    
}
function adicionarPagos() {
    var jsonData = data;
    try {
        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/caja.adicionar.php',
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
                //alerta("La informacion de registró correctamente.");
                jsonData += "&idTransaccion=" + idTransaccion;
                jsonData += "&localStorage=" + localStorage.modulo;
                generarFacturaPdf(jsonData);
                limpiarVariablesTransaccionProducto();
                limpiarFormulario();
                limpiarVariables();
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
}
function cargarInformacionCaja() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/caja.consultarCaja.php',
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
                prefijo = fila.prefijo;
                idBodega = fila.idBodega;
                $("#spnInfoCaja").html("Tipo Documento: " + fila.tipoDocumento + " - Número: " + fila.numeroTipoDocumento);
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function asignarDatosEnvio() {
    notaTransaccion = $("#txtNotaTransaccion").val();
    fechaVencimiento = $("#txtFechaVencimiento").val();
}
function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idTercero=' + idTercero + '&idClienteProveedor=' + idClienteProveedor + '&tipoClienteProveedor=' + tipoClienteProveedor + '&nota=' + notaTransaccion + '&fechaVencimiento=' + fechaVencimiento + '&idCaja=' + idCaja + '&prefijo=' + prefijo + '&valorTotalPagar=' + valorTotalPagar + '&dataFormasPago=' + JSON.stringify(dataFormasPago);
    data += "&arrConceptoPago=" + JSON.stringify(arrConceptoPago)
    data += "&permiteMultipleConceptos=" + permiteMultipleConceptos;
}
function autoCompletarTercero() {
    $("#txtNombre").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function (event, ui) {
            idTercero = ui.item.idTercero;
            idClienteProveedor = ui.item.idClienteProveedor;
            tipoClienteProveedor = ui.item.tipo;
            $("#txtNit").val(ui.item.nit);
        }
    });
}
function consultarTercero(nit) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/caja.consultarCliente.php',
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
                idClienteProveedor = null;
                tipoClienteProveedor = null;
                return false;
            }

            if (json.numeroRegistros == 1) {
                $.each(json.data, function (contador, fila) {
                    idTercero = fila.idTercero;
                    idClienteProveedor = fila.idClienteProveedor;
                    tipoClienteProveedor = fila.tipo;
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
                contadorClientes = 0;
                $.each(json.data, function (contador2, fila) {
                    html += '<li><input id="linkCliente' + contadorClientes + '" onfocus="focoCampoCliente($(this))" onkeypress="keypressCampoCliente($(this), event)" type="text" class="campo_cliente"  data-dismiss="modal" onclick="asignar(' + fila.idTercero + ',' + fila.idClienteProveedor + ',' + "'" + fila.terceroSucursal + "'" + ',' + "'" + fila.tipo + "'" + ')" value="' + fila.terceroSucursal + '"></li>';
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
function asignar(idTercer, idClient, terceroSucursal, tipo) {
    idTercero = idTercer;
    idClienteProveedor = idClient;
    tipoClienteProveedor = tipo;
    $("#txtNombre").val(terceroSucursal);
}
function autoCompletarProducto() {
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarConcepto.php?codigoTipoNaturaleza=' + codigoTipoNaturalezaConceptos,
        select: function (event, ui) {
            if (idClienteProveedor != null && idClienteProveedor != "") {
                cargarVentanaConcepto(ui);
            } else {
                alerta("Debe seleccionar un asociado válido.");
            }
        }
    });
}
function asignarValorConcepto(arrJsonConceptoPago) {
    if (arrJsonConceptoPago != null && arrJsonConceptoPago != "") {
        var objJsonConceptoPago = arrJsonConceptoPago[0];
        //Obtenemos el id del crédito adionado
        /*var posicion = parseInt(arrJsonConceptoPago.length)-1;
         var contadorRepetidos = 0;
         if(posicion!=null){
         var objJsonConceptoPago = arrJsonConceptoPago[posicion];
         for(i = 0; i<arrCreditosAdicionado.length; i++){
         var  objArrCreditoAdicionado = arrCreditosAdicionado[i];
         if(objArrCreditoAdicionado.idCredito == objJsonConceptoPago.idCredito){
         contadorRepetidos++;
         }
         }
         if(contadorRepetidos==0){*/
        var objArrConceptoPago = arrJsonConceptoPago[0];
        $("#txtValor").val(objArrConceptoPago.valorConcepto);
        $("#txtJsonConcepto").val(JSON.stringify(arrJsonConceptoPago));

        var objCreditoAdicionado = new Object();
        objCreditoAdicionado.idCredito = objJsonConceptoPago.idCredito;
        //Adicionamos el objeto al arreglo
        arrCreditosAdicionado.push(objCreditoAdicionado);
    }/*else{
     alerta("El crédito al cual le adicionó está repetido");
     }
     }
     }*/
}
function cargarVentanaConcepto(ui) {
    if (idClienteProveedor != null && idClienteProveedor != "") {
        if (ui != null) {
            idConcepto = ui.item.idConcepto;
            $("#txtCodigoProducto").val(ui.item.codigo);
            tipoDocumento = ui.item.tipoDocumento;
            //if(ui.item.archivo!=null && ui.item.archivo!="" && ui.item.archivo!=" "){
            archivoPhp = ui.item.archivo;
            formulario = ui.item.formulario;
            idTipoDocumento = ui.item.idTipoDocumento;
            //}
        }
        abrirPopPup(idClienteProveedor, tipoClienteProveedor);
    } else {
        alerta("Debe seleccionar el cliente y el concepto.");
        autoCompletarProducto();
    }
}
function abrirPopPup(cliente, tipoClienteProveedor) {
    if (formulario != null) {
        var numeroDocumentoIdentidad = $("#txtNit").val();
        abrirPopup(localStorage.modulo + formulario + '?idClienteProveedor=' + cliente + '&origen=1&nit=' + numeroDocumentoIdentidad + '&idTipoDocumento=' + idTipoDocumento + '&tipoClienteProveedor=' + tipoClienteProveedor + '&idTercero=' + idTercero, 'PagoCredito');
        abreFrmAparte = true;
        formulario = null;
    }
}
function consultarProducto(codigoProducto) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/transaccion.consultarConcepto.php',
        type: 'POST',
        dataType: "json",
        data: {idConcepto: codigoProducto, codigoTipoNaturaleza:codigoTipoNaturalezaConceptos},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                $("#txtProducto").val("");
                $("#txtValor").val("");
                idConcepto = null;
                $("#pnlSeriales").hide();
                return false;
            }

            $.each(json.data, function (contador, fila) {
                idConcepto = fila.idConcepto;
                archivoPhp = fila.archivo;
                formulario = fila.formulario;
                idTipoDocumento = fila.idTipoDocumento;
                $("#txtProducto").val(fila.tipoDocumento);
            });
            cargarVentanaConcepto(null);
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarListadoTransaccionProducto() {
    $("#divListadoTransaccionProducto").html("");
    var tabla = '<center><table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th style="width: 30%">Concepto</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";
    tabla += '</tr>';
    tabla += '</table></center>';
    $("#divListadoTransaccionProducto").html(tabla);
}
function crearListadoTransaccionProducto() {
    $("#divListadoTransaccionProducto").html("");
    var tabla = '<center><table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Concepto</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Acción</th>';
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
        tabla += "<td align='right'>" + obj.codigo + "</td>";
        tabla += "<td align='center'>" + obj.tipoDocumento + "</td>";
        tabla += "<td>" + obj.nota + "</td>";
        tabla += "<td align='right'>$" + agregarSeparadorMil(String(obj.valorSalidConImpue)) + "</td>";
        //tabla += '<td align="center" style="width: 5%"><span class="fa fa-pencil imagenesTabla" id="imgEditarTransaccionProducto' + i + '" title="Editar" class="imagenesTabla" onclick="editarTransaccionProducto('+i+')"></span></td>';	
        tabla += '<td align="center" style="width: 5%"><span class="fa fa-trash imagenesTabla" id="imgBorrarTransaccionProducto' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarTransaccionProducto(' + i + ')"></span></td>';
        tabla += '</tr>';
        valorTotalPagar = parseInt(valorTotalPagar) + parseInt(obj.valorSalidConImpue);
    }

    tabla += '</table></center>';
    $("#divListadoTransaccionProducto").html(tabla);
    $("#spnValorTotalPagar").html(agregarSeparadorMil(String(valorTotalPagar)));
    $("#txtValorPagar").val(agregarSeparadorMil(String(valorTotalPagar)));
}
function editarTransaccionProducto(posicion) {
    posicionTemp = parseInt(posicion);
    var obj = dataProductos[posicion];
    idConcepto = obj.idConcepto;
    $("#hidIdTransaccionProducto").val(obj.idTransaccionProducto);
    $("#txtCodigoProducto").val(obj.codigo);
    $("#txtProducto").val(obj.tipoDocumento);
    $("#txtValor").val(agregarSeparadorMil(String(obj.valorSalidConImpue)));
    $("#txaNota").val(obj.nota);
    valorEntraConImpue = obj.valorEntraConImpue;
    valorUnitarioEntrada = obj.valorUnitarioEntrada;
    valorUnitarioSalida = obj.valorUnitarioSalida;
    productoSerial = obj.productoSerial;
}
function eliminarTransaccionProducto(posicion) {
    var obj = dataProductos[posicion];
    idBorrar = obj.idTransaccionProducto;
    if (idBorrar != "" && idBorrar != null && idBorrar != "null") {
        arrIdEliminar.push(idBorrar);
    }
    //Obtengo el idCredito para buscarlo y borrarlo
    var objCreditoBorrar = arrConceptoPago[posicion];
    var creditoId = objCreditoBorrar.idCredito;
    dataProductos.splice(posicion, 1);
    arrConceptoPago.splice(posicion, 1);
    for (i = 0; i < arrCreditosAdicionado.length; i++) {
        if (creditoId == arrCreditosAdicionado) {
            arrCreditosAdicionado.splice(i, 1);
        }
    }
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
    idTipoDocumento = null;
    formulario = null;
    $("#hidIdTransaccionProducto").val("");
    $("#txtCodigoProducto").val("");
    $("#txtProducto").val("");
    $("#txtValor").val("");
    $("#txaNota").val("");
    $("#divFrmSeriales").html("");
    $("#spnValorTotalPagar").html("");
    $("#pnlSeriales").hide();
    $("#txtCodigoProducto").focus();
    $("#txtJsonConcepto").val("");
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
    cerrarModal();
    $("#imgNuevoTransaccionProducto").click();
}

//----------------------------FORMAS DE PAGO------------------------------------
function obtenerFormaPagoDefecto() {
    var idFormaPago;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/caja.obtenerFormaPagoDefecto.php',
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
    var tabla = '<center><table class="table table-bordered table-striped consultaTabla">';
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
    tabla += '</table></center>';
    $("#divListadoFormasPago").html(tabla);
}
function crearListadoFormasPago() {
    $("#divListadoFormasPago").html("");
    var tabla = '<center><table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th style="width: 35%">Forma Pago</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th style="width: 5%">Acción</th>';
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
        tabla += "<td align='center'>" + obj.formaPago + "</td>";
        tabla += "<td align='right'>$" + agregarSeparadorMil(String(obj.valor)) + "</td>";
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarFormaPago' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarFormaPago(' + i + ')"></span></td>';
        tabla += '</tr>';

        valorTotalPagado += parseInt(obj.valor);
    }

    tabla += '</table></center>';
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
    idTercero = null;
    idClienteProveedor = null;
    tipoClienteProveedor = null;
    nota = null;
    fechaVencimiento = null;
    contadorFlecha = 0;
    contadorClientes = 0;
    idTipoDocumento = null;

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
    arrConceptoPago = new Array();
    formulario = null;

    valorTotalPagar = 0;
    valorTotalPagado = 0;

    arrCreditosAdicionado = new Array();

    data = null;
    cargarListadoTransaccionProducto();
    cargarListadoFormasPago();
    idConcepto = null;
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
        url: localStorage.modulo + 'controlador/caja.validarCajero.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

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
function cargarFormatosImpresion() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cajaFormatoImpresion.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {
            estado: "true"
            , idCaja: idCaja
            , codigoTipoNaturaleza: 'CA'
        },
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

function generarFacturaPdf(jsonData) {
    var archivo = $("#selFormatoImpresion option:selected").attr("name");
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/' + archivo,
        type: 'POST',
        dataType: "json",
        data: jsonData,
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
function obtenerPermiteMultipleConceptos(){
    var permitecruce = false;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/parametroAplicacion.consultar.php',
        type: 'POST',
        dataType: "json",
        data:{
                parametroAplicacion:"PERMITE MULTIPLES CONCEPTOS"
            },
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                throw mensaje;
                return false;
            }
            
            if(json.valor != "" && json.valor != null && json.valor != "null"){
                permitecruce = json.valor;
            }
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
    return permitecruce;
}
