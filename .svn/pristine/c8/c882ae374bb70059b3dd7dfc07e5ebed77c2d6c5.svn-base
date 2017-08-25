var dataProductos = new Array();
var arrPedidos = new Array();
var arrSerialesProductos = new Array();
var idCliente = null;
var idProducto = null;
$(function() {
    crearTabla();
    autoCompletarCliente();
    autoCompletarProducto();
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    $("#imgConsultar").bind({
        "click": function() {
            var data = obtenerDatosEnvio();
            if (data != null && data != '' && data != 'null') {
                consultarPedidoMovil(data);
            }
        }
    });
    $("#imgGuardar").bind({
        "click": function() {
            var estado = validarInformacionArreglo();
            if (estado != false) {
                if (aliminarPosicionesInacivadas() == true) {
                    retornarArreglo();
                } else {
                    alerta("Debe seleccionar almenos un producto.");
                }
            } else {
                alerta("Debe ingresar los seriales restantes.");
            }
        }
    });
    $("#imgCancelar").bind({
        "click": function() {
            window.close();
        }
    });
    $("#imgLimpiar").bind({
        "click": function() {
            limpiarVariables();
            $("#txtCliente").val("");
            $("#txtFechaInicio").val("");
            $("#txtFechaFin").val("");
        }
    });
    $("#txtCliente").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                limpiarVariables();
                break;
        }
    });
});

function autoCompletarCliente() {
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/clienteMovil.autoCompletar.php',
        select: function(event, ui) {
            idCliente = ui.item.idCliente;
        }
    });
}

function autoCompletarProducto() {
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/producto.autoCompletarProducto.php',
        select: function(event, ui) {
            idProducto = ui.item.idProducto;
        }
    });
}

function consultarPedidoMovil(data) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/pedido.consultarPedidoMovil.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                throw mensaje;
                return false;
            }
            if (json.numeroRegistros == 0) {
                alerta("No hay registros con los parametros indicados.");
                return false;
            }
            cargarProductos(json);
            arrPedidos = json.data;
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function cargarProductos(json) {//Visualizar pedidos hechos desde movil

//    $.each(json.data, function(contador, fila) {
//        var obj = new Object();
//
//        obj.serial = null;
//        obj.idTransaccionProducto = null;
//        obj.idProducto = fila.idProducto;
//        obj.codigo = fila.codigo;
//        obj.producto = fila.producto;
//        obj.productoSerial = fila.productoSerial;
//        obj.unidadMedida = fila.unidadMedida;
//        obj.valorEntraConImpue = parseFloat(fila.valorEntraConImpue);
//        obj.valorSalidConImpue = parseFloat(fila.valorSalidConImpue);
//        obj.valorUnitarioEntrada = parseFloat(fila.valorEntrada);
//        obj.valorUnitarioSalida = parseFloat(fila.valorSalida);
//        obj.cantidad = fila.cantidad;
//        obj.valorTotal = obj.valorSalidConImpue * obj.cantidad;
//        ;//fila.valorTotal;
//        obj.nota = fila.nota;
//        obj.idPedidoProducto = fila.idPedidoProducto;
//
//        dataProductos.push(obj);
//    });
    crearListadoTransaccionProducto(json);
}

function crearTabla() {
    var tabla = "";
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Unidad Medida</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th colspan="2">Acción</th>';
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
    tabla += '<td colspan="2">&nbsp;</td>';
    tabla += '</tr>';
    $("#tblPedido").html(tabla);
}

function crearListadoTransaccionProducto(json) {
    $("#divListadoTransaccionProducto").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th> Información pedido </th>';
    tabla += '<th> Productos </th>';
    tabla += '<th> <input type="checkbox" id="chkSeleccionarTodo" title="Seleccionar todos los productos"> </th>';
    tabla += '</tr>';
    var contadorPedido = 0;
    $.each(json.data, function(contador, filaInfoPedido) {
        contadorPedido++;
        tabla += "<tr>";
        tabla += "<td align='center'>" + contadorPedido + "</td>";
        tabla += "<td class='valorTexto'>Cliente: " + filaInfoPedido.cliente + "<br>Fecha: " + filaInfoPedido.fecha + " </td>";
        tabla += "<td class='valorTexto'>";
        if (filaInfoPedido.productos.length > 0) {
            tabla += '<table class="table table-bordered table-striped consultaTabla">';
            tabla += "<tr>";
            tabla += '<th>#</th>';
            tabla += '<th>Código</th>';
            tabla += '<th>Producto</th>';
            tabla += '<th>Unidad Medida</th>';
            tabla += '<th>Valor</th>';
            tabla += '<th>Cantidad</th>';
            tabla += '<th>Valor Total</th>';
            tabla += '<th>Serial</th>';
            tabla += '<th>Nota</th>';

            tabla += '<th colspan="2"> <input id="chkPedido' + filaInfoPedido.idPedido + '" type="checkbox" title="Seleccionar todos los productos de este pedido"> </th>';
            tabla += "</tr>";
            $.each(filaInfoPedido.productos, function(contadorProductos, filaProductos) {

                //var obj = dataProductos[i];
                tabla += '<tr>';
                tabla += "<td align='center'>" + (contadorProductos + 1) + "</td>";
                tabla += "<td>" + filaProductos.codigo + "</td>";
                tabla += "<td>" + filaProductos.producto + "</td>";
                tabla += "<td>" + filaProductos.unidadMedida + "</td>";
                tabla += "<td align='right'>" + agregarSeparadorMil(String(filaProductos.valorUnitaConImpue)) + "</td>";
                tabla += "<td align='center'>" + filaProductos.cantidad + "</td>";
                tabla += "<td align='right'>" + agregarSeparadorMil(String(filaProductos.total)) + "</td>";
                if (filaProductos.serial != null) {
                    tabla += "<td id='tdSerial" + filaProductos.idPedidoProducto + "'>";
                    for (var j = 0; j < filaProductos.serial.length; j++) {
                        tabla += (j + 1) + ". " + filaProductos.serial[j] + "<br>";
                    }
                    tabla += "</td>";
                } else {
                    tabla += "<td  id='tdSerial" + filaProductos.idPedidoProducto + "'>&nbsp;</td>";
                }

                if (filaProductos.nota != "" && filaProductos.nota != null && filaProductos.nota != "null") {
                    tabla += "<td>" + filaProductos.nota + "</td>";
                } else {
                    tabla += "<td>&nbsp;</td>";
                }
                if (filaProductos.productoSerial != null && filaProductos.productoSerial != 'null' && filaProductos.productoSerial != "" && filaProductos.productoSerial != undefined) {
                    tabla += '<td align="center"><span class="fa fa-qrcode imagenesTabla" title=" Adicionar serial " id="imgEditarTransaccionProducto' + contadorProductos + '" title=" Asignar seriales" class="imagenesTabla" onclick="consultarSerialesProducto(' + filaProductos.cantidad + ', ' + filaProductos.idProducto + ', \'' + filaProductos.producto + '\', ' + dataProductos.length + ', ' + filaProductos.idPedidoProducto + ')"></span></td>';
                    tabla += '<td align="center"> <input type="checkbox" id="chk' + filaProductos.idPedidoProducto + '" title="Facturar el producto ' + filaProductos.producto + '"></td>';
                } else {
                    tabla += '<td align="center" colspan="2"> <input type="checkbox" id="chk' + filaProductos.idPedidoProducto + '" title="Facturar el producto ' + filaProductos.producto + '"></td>';
                }
                tabla += '</tr>';
            });
            tabla += '</table>';
        }
        tabla += "</td>";
        tabla += "<td> &nbsp; </td>";
        tabla += "</tr>";
        adicionarProductoArreglo(filaInfoPedido);
    });
    tabla += '</table>';
    $("#tblPedido").html(tabla);

    //Activar onChange checkbox
    $("#chkSeleccionarTodo").change(function() {
        if ($("#chkSeleccionarTodo").is(":checked")) {
            $("#chkSeleccionarTodo").attr("title", "Deschequear todos los producos");
            chequearTodoProducto();
        } else {
            $("#chkSeleccionarTodo").attr("title", "Seleccionar todos los productos");
            deschequearTodoProducto();
        }
    });

    $.each(json.data, function(contador, filaInfoPedido) {
        if (filaInfoPedido.productos.length > 0) {

            $("#chkPedido" + filaInfoPedido.idPedido).change(function() {
                chequearProductoPedido(filaInfoPedido);
            });

            $.each(filaInfoPedido.productos, function(contadorProductos, filaProductos) {
                $("#chk" + filaProductos.idPedidoProducto).change(function() {
                    if ($("#chk" + filaProductos.idPedidoProducto).is(":checked")) {
                        cambiarEstadoCheck(filaProductos.idPedidoProducto, 'true');
                    } else {
                        cambiarEstadoCheck(filaProductos.idPedidoProducto, 'false');
                    }
                });
            });
        }
    });
}

function adicionarProductoArreglo(objJson) {
    $.each(objJson.productos, function(contadorProductos, filaProductos) {
        var obj = new Object();

        obj.serial = null;
        obj.idTransaccionProducto = null;
        obj.idProducto = filaProductos.idProducto;
        obj.codigo = filaProductos.codigo;
        obj.producto = filaProductos.producto;
        obj.productoSerial = filaProductos.productoSerial;
        obj.unidadMedida = filaProductos.unidadMedida;
        obj.valorEntraConImpue = parseInt(filaProductos.valorEntraConImpue);
        obj.valorSalidConImpue = parseInt(filaProductos.valorSalidConImpue);
        obj.valorUnitarioEntrada = parseInt(filaProductos.valorEntrada);
        obj.valorUnitarioSalida = parseInt(filaProductos.valorSalida);
        obj.cantidad = parseInt(filaProductos.cantidad);
        obj.valorTotal = parseInt(obj.valorSalidConImpue * obj.cantidad);
        //fila.valorTotal;
        obj.nota = filaProductos.nota;
        obj.idPedidoProducto = filaProductos.idPedidoProducto;
        obj.estadoCheck = 'false';

        dataProductos.push(obj);
    });
}

function chequearProductoPedido(objPedidoProducto) {
    if ($("#chkPedido" + objPedidoProducto.idPedido).is(":checked")) {
        $.each(objPedidoProducto.productos, function(contador, fila) {
            $("#chk" + fila.idPedidoProducto).prop('checked', true);
            cambiarEstadoCheck(fila.idPedidoProducto, 'true');
        });
    }else{
        $.each(objPedidoProducto.productos, function(contador, fila) {
            $("#chk" + fila.idPedidoProducto).prop('checked', false);
            cambiarEstadoCheck(fila.idPedidoProducto, 'false');
        });
    }
}

function cambiarEstadoCheck(idPedidoProducto, estado) {
    for (var i = 0; i < dataProductos.length; i++) {
        var objDataProductos = dataProductos[i];
        if (idPedidoProducto == objDataProductos.idPedidoProducto) {
            dataProductos[i].estadoCheck = estado;
        }
    }
}

function obtenerDatosEnvio() {
    if (idCliente == null) {
        if ($("#txtCliente").val().toString() != "") {
            alerta("Debe ingresar un cliente válido");
            return;
        } else {
            if (idProducto == null) {
                if ($("#txtProducto").val().toString() != "") {
                    alerta("Debe ingresar un producto válido");
                    return;
                } else {
                    if ($("#txtFechaInicio").val().toString() == "") {
                        if ($("#txtFechaFin").val().toString() == "") {
                            alerta("Debe ingresar almenos un parámetro de búsqueda.");
                            return;
                        }
                    }
                }
            }
        }
    }
    var data = "";
    data += "idCliente=" + idCliente;
    data += "&idProducto=" + idProducto;
    data += "&fechaInicio=" + $("#txtFechaInicio").val();
    data += "&fechaFin=" + $("#txtFechaFin").val();
    return data;
}

function consultarSerialesProducto(cantidad, idProducto, producto, posicionArreglo, idTd) {
    crearCamposSeriales(cantidad, posicionArreglo, idTd);

    $("#spnTituloSeriales").html("Seriales del producto: " + producto);
    $("#pnlSeriales").show();
    modal();
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/facturacionProducto.consultarSerial.php',
        type: 'POST',
        dataType: "json",
        data: {idProducto: idProducto},
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

            $.each(json.data, function(contador, fila) {
                var contadorRepetidos = 0;
                for (var i = 0; i < arrSerialesProductos.length; i++) {
                    if (fila.serial == arrSerialesProductos[i]) {
                        contadorRepetidos++;
                    }
                }
                if (contadorRepetidos == 0) {
                    arrSerialesProductos.push(fila.serial);
                }
            });

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function crearCamposSeriales(cantidad, posicionArreglo, idTd) {
    var html = '<form id="frmSerialePedido" name="frmSerialePedido">';
    html += '<table>';
    for (var i = 0; i < cantidad; i++) {
        if (dataProductos[posicionArreglo].serial != null && dataProductos[posicionArreglo].serial != undefined) {
            html += '<tr>';
            html += '<td>*Serial ' + (i + 1) + '</td>';
            html += '<td><input type="text" id="txtSerialPedido' + i + '" value="' + dataProductos[posicionArreglo].serial[i] + '"></td>';
            html += '<td><input type="text" id="txtPermiteTrabajar" style="visibility: hidden"></td>';
            html += '</tr>';
        } else {
            html += '<tr>';
            html += '<td>*Serial ' + (i + 1) + '</td>';
            html += '<td><input type="text" id="txtSerialPedido' + i + '"></td>';
            html += '<td><input type="text" id="txtPermiteTrabajar" style="visibility: hidden"></td>';
            html += '</tr>';
        }
    }
    html += '</table>';
    html += '</form>';

    $("#divSeriales").html(html);
    $('#frmSerialePedido input[type=text]').each(function() {
        if ($(this).attr("id") != "txtPermiteTrabajar") {
            $("#" + $(this).attr("id")).change(function() {
                adicionarSerial(cantidad, posicionArreglo, idTd);
            });
        }
    });
    $('#frmSerialePedido input[type=text]').each(function() {
        if ($(this).attr("id") != "txtPermiteTrabajar") {
            $("#" + $(this).attr("id")).keypress(function(e) {
                switch (e.keyCode) {
                    case 08 || 46:
                        adicionarNuevamenteSerial(dataProductos[posicionArreglo].serial);
                        break;
                }
            });
        }
    });
}

function modal() {
    $("#modal").addClass("modal_personalizado");
    $("#pnlSeriales").removeClass("contenido");
    $("#pnlSeriales").addClass("contenido_modal");
    $("#imgCerrarModal").show();
    $("#modal").show(function() {
        $("#txtSerialPedido").focus();
    });

    $("#imgCerrarModal").click(function() {
        cerrarModal();
    });
}

function cerrarModal() {
    $("#pnlSeriales").hide();
    $("#modal").hide();
    $("#modal").removeClass("modal_personalizado");
    $("#pnlSeriales").removeClass("contenido_modal");
}
function adicionarSerial(cantidad, posicionArreglo, idTd) {
    var seriales = new Array();
    for (var i = 0; i < cantidad; i++) {

        if ($.trim($("#txtSerialPedido" + i).val()) == "") {
            $("#txtSerialPedido" + i).focus();
            return false;
        }
        seriales.push($("#txtSerialPedido" + i).val());
    }
    if (validarSerialRepetido(seriales) == false) {
        return false;
    }
    dataProductos[posicionArreglo].serial = seriales;
    eliminarSerialUtilizado(seriales);
    cerrarModal();
    //crearListadoTransaccionProducto();
    var tdSerial = "";
    for (var i = 0; i < seriales.length; i++) {
        tdSerial += parseInt(i + 1) + ". " + seriales[i] + "<br>";
    }
    $("#tdSerial" + idTd).html(tdSerial);
}

function validarSerialRepetido(seriales) {
    for (var i = 0; i < seriales.length; i++) {

        if (validarExistenciaSerial(seriales[i]) == false) {
            alert("El serial " + seriales[i] + " no se encuentra inventariado.");
            return false;
        }

        for (var j = (i + 1); j < seriales.length; j++) {
            if (seriales[i] == seriales[j]) {
                alert("El serial " + seriales[i] + " está repetido.");
                return false;
            }
        }
    }
    return true;
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

function eliminarSerialUtilizado(seriales) {
    var retorno = false;
    for (var i = 0; i < seriales.length; i++) {
        for (var j = 0; j < arrSerialesProductos.length; j++) {
            if (seriales[i] == arrSerialesProductos[j]) {
                arrSerialesProductos.splice(j, 1);
            }
        }
    }
    return retorno;
}

function adicionarNuevamenteSerial(seriales) {
    var contador = 0;
    if (seriales != null && seriales != undefined) {
        for (var i = 0; i < seriales.length; i++) {
            for (var j = 0; j < arrSerialesProductos.length; j++) {
                if (seriales[i] == arrSerialesProductos[j]) {
                    contador++;
                }
            }
            if (contador == 0) {
                arrSerialesProductos.push(seriales[i]);
            }
        }
    }
}

function validarInformacionArreglo() {
    var estado = true;
    //Primero validamos si no ha digitado todos los seriales
    for (var i = 0; i < dataProductos.length; i++) {
        var objDataProducto = dataProductos[i];
        if (objDataProducto.productoSerial != false && objDataProducto.productoSerial != 'false' && objDataProducto.productoSerial != '' && $("#chk" + objDataProducto.idPedidoProducto).is(":checked")) {
            if (objDataProducto.serial == null) {
                estado = false;
            }
        }
    }
    return estado;
}

function aliminarPosicionesInacivadas() {
    //Primero validamos que haya almenos un checkbox chequeado
    var contadorControlesChequeados = 0;
    var estadoArreglo = true;
    for (var i = 0; i < dataProductos.length; i++) {
        var objDataProductos = dataProductos[i];
        if ($("#chk" + objDataProductos.idPedidoProducto).is(":checked")) {
            contadorControlesChequeados++;
        }
    }
    var arrDataProductosTemporal = new Array();
    arrDataProductosTemporal = dataProductos;
    dataProductos = new Array();
    if (contadorControlesChequeados > 0) {
        for (var i = 0; i < arrDataProductosTemporal.length; i++) {
            var objDataProductos = arrDataProductosTemporal[i];
            if (objDataProductos.estadoCheck != false && objDataProductos.estadoCheck != 'false') {
                dataProductos.push(objDataProductos);
            }
        }
    } else {
        estadoArreglo = false;
    }
    return estadoArreglo;
}

function retornarArreglo() {
    window.opener.obtenerArregloPedidoMovil(dataProductos);
    window.close();
}
function limpiarVariables() {
    crearTabla();
    idCliente = null;
    dataProductos = new Array();
    arrSerialesProductos = new Array();
}
function chequearTodoProducto() {
    $.each(arrPedidos, function(contador, filaInfoPedido) {
        $("#chkPedido"+filaInfoPedido.idPedido).prop('checked', true);
        if (filaInfoPedido.productos.length > 0) {
            $.each(filaInfoPedido.productos, function(contadorProductos, filaProductos) {
                $("#chk" + filaProductos.idPedidoProducto).prop('checked', true);
            });
        }
    });
    for (var i = 0; i < dataProductos.length; i++) {
        dataProductos[i].estadoCheck = 'true';
    }
}
function deschequearTodoProducto() {
    $.each(arrPedidos, function(contador, filaInfoPedido) {
        $("#chkPedido"+filaInfoPedido.idPedido).prop('checked', false);
        if (filaInfoPedido.productos.length > 0) {
            $.each(filaInfoPedido.productos, function(contadorProductos, filaProductos) {
                $("#chk" + filaProductos.idPedidoProducto).prop('checked', false);
            });
        }
    });
    for (var i = 0; i < dataProductos.length; i++) {
        dataProductos[i].estadoCheck = 'false';
    }
}