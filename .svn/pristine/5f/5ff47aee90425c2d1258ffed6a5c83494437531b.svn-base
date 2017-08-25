var valorRecibido = variableUrl();
var idPedido = valorRecibido[1];
var fecha = null;
var nota = null;
var tipoPedido = null;
var idCliente = null;
var idVendedor = null;
var idZona = null;
var productos = [];
var idProductos = [];
var productosEliminados = [];
var contadorProductos = 0;
var idClienteServicioSeleccionado = null;

var data = null;
var tabla = '';
tabla += '<tr id="trEstatico">';
//tabla += '<th>#</th>';
tabla += '<th>Código</th>';
tabla += '<th>Producto</th>';
tabla += '<th>Nota</th>';
tabla += '<th style="width:15%">Cantidad</th>';
tabla += '<th>Valor Unitario</th>';
tabla += '<th>Valor Total</th>';
tabla += '<th>Eliminar</th>';
tabla += '</tr>';
$(function() {
    realizarFoco(document.frmPedidoProducto, '$("#imgNuevoProducto").click()');
    realizarFoco(document.frmPedido, '$("#tabProductos").click()');
    autoCompletarProducto();
    autoCompletarCliente();
    crearTablaProducto();
    validarNumeros('txtNit');
    validarNumeros('txtCodigoProducto');
    validarNumeros('txtCantidad');
    crearCalendario("txtFecha");
    formatearNumero("txtCantidad");
    $("#tabServicioInstalado").hide();
    var f = new Date();
    
    $("#selTipoPedido").change(function(){
        
        if(idCliente == "" || idCliente == null || idCliente == "null"){
            alerta("Debe seleccionar el cliente vinculado al pedido");
            return false;
        }
        
       if($("#selTipoPedido").val() == 1){//Instalación
          $("#selVendedor").attr("accesskey","L"); 
          $(".trVendedor").show();
          $("#tabServicioInstalado").hide();
       }else{//Mantenimiento o Desinstalación
          $("#selVendedor").attr("accesskey","V");
          $(".trVendedor").hide();
          $("#tabServicioInstalado").show();
          var retorno = consultarServiciosClientes(idCliente,"#divInformacionServiciosClientes",true);
          if(retorno == false){
              $("#selTipoPedido").val("");
              $(".trVendedor").hide();
              $("#tabServicioInstalado").hide();
          }
       }
    });

    $('#txtFecha').val(f.getFullYear() + '-' + (f.getMonth() + 1) + '-' + f.getDate());
    $("#txtNit").focus();
    $('#selVendedor').multiselect({
        maxHeight: 300
        , nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");
    $("input.form-control.multiselect-search").attr("accesskey", "V");

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("href");
        if (target == "#tabPedido") {
            $("#txtNit").focus();
        } else if (target == "#tabPedidoProducto") {
            $("#txtCodigoProducto").focus();
        }
    });

    if (idPedido != '' && idPedido != 'null' && idPedido != null) {
        consultarPedido();
    }

    /*-----------------VALIDACIONES--------------------------*/
    $("#imgGuardarProducto").click(function() {
        abrirPopup(localStorage.modulo + 'vista/frmProducto.html?id=&origen=1', 'Productos');
    });
    $("#txtCodigoProducto").change(function() {
        if ($("#txtCodigoProducto").val() == '')
            return;
        consultarProducto($("#txtCodigoProducto").val());
    }).keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtProducto, #txtCantidad").val("");
                $("#txtValorUnitario").val('');
                idProducto = null;
                break;
        }
    });
    $("#txtProducto").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProducto, #txtCantidad").val("");
                $("#txtValorUnitario").val('');
                idProducto = null;
                break;
        }
    });
    $("#txtCantidad").change(function() {
        validarCantidad(this);
    });
    $("#txtNit").change(function() {
        if ($("#txtNit").val() == '')
            return;
        consultarTercero($("#txtNit").val());
    }).keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCliente").val("");
                $("#txtCliente").keypress();
                $("#selTipoPedido").val("");
                $(".trVendedor").hide();
                $("#tabServicioInstalado").hide();
                idCliente = null;
                break;
        }
    });
    $("#txtCliente").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNit").val("");
                $("#selTipoPedido").val("");
                $(".trVendedor").hide();
                $("#tabServicioInstalado").hide();
                autoCompletarCliente();
                idCliente = null;
                break;
        }
    });
    $('#imgNuevoProducto').bind({
        'click': function() {
            if (validarVacios(document.frmPedidoProducto) == false)
                return false;
            agregarArreglo();
        }
    });

    /*-----------------GUARDAR--------------------------*/
    $('#imgGuardar').bind({
        'click': function() {

            if($("#selTipoPedido").val() != 1){//Mantenimiento o Desinstalación
                idClienteServicioSeleccionado = $('input:radio[name=rdoClienteServicio]:checked').val();
                if(idClienteServicioSeleccionado == "" || idClienteServicioSeleccionado == null || idClienteServicioSeleccionado == "null"){
                    alerta("Debe seleccionar el servicio al cual desea realizar el mantenimiento o la desinstalación");
                    return false;
                }
            }
            
            if(idClienteServicioSeleccionado == "" || idClienteServicioSeleccionado == null || idClienteServicioSeleccionado == "null" || idClienteServicioSeleccionado == undefined){
                idClienteServicioSeleccionado = null;
            }
            
            if($("#selTipoPedido").val() == 1){
                idVendedor = $("#selVendedor").val();
                if (idVendedor == '' || idVendedor == 'null' || idVendedor == null) {
                    alerta('Por favor indique el vendedor');
                    return false;
                }
            }else{
                idVendedor = -1;//Sin Vendedor
            }
            

            obtenerValores();
            if (productos.length == 0) {
                alerta('Por favor agregue un producto');
                return;
            }

            if (validarVacios(document.frmPedido) == false)
                return false;

            if (idCliente == '' || idCliente == 'null' || idCliente == null) {
                alerta('Por favor indique el cliente');
                return false;
            }
            
            if (idZona == '' || idZona == 'null' || idZona == null) {
                alerta('Por favor indique la zona');
                return false;
            }
            if (tipoPedido == '' || tipoPedido == 'null' || tipoPedido == null) {
                alerta('Por favor indique el tipo de pedido');
                return false;
            }
            

            if (idPedido != '' && idPedido != 'null' && idPedido != null) {
                var urlPedido = localStorage.modulo + 'controlador/pedido.modificar.php';
                var urlPedidoProducto = localStorage.modulo + 'controlador/pedidoProducto.modificar.php';
            } else {
                var urlPedido = localStorage.modulo + 'controlador/pedido.adicionar.php';
                var urlPedidoProducto = localStorage.modulo + 'controlador/pedidoProducto.adicionar.php';
            }

            /**AJAX PEDIDO**/
            $.ajax({
                async: false,
                url: urlPedido,
                type: 'POST',
                dataType: "json",
                data: data,
                success: function(json) {
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    idPedido = json.idPedido;

                    if (exito == 0) {
                        alerta(mensaje);
                        idProducto = '';
                        return false;
                    }
                    ajaxPedidoProducto(urlPedidoProducto);
                }, error: function(xhr, opciones, error) {
                    alerta(error);
                    return false;
                }
            });
        }
    });

    $("#imgNuevoCliente").bind({
        "click": function() {
            abrirFormularioCliente();
        }
    });
});

function abrirFormularioCliente() {
    var formulario = "vista/frmCliente.html";
    var parametros = "?idCliente=null&origen=2";
    abrirPopup(localStorage.modulo + formulario + parametros, 'Cliente');
}

function asignarCliente(clienteId) {
    if (clienteId != null && clienteId != undefined) {
        idCliente = clienteId;
        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/cliente.consultar.php',
            type: 'POST',
            dataType: "json",
            data: {idCliente: idCliente},
            success: function(json) {
                $.each(json.data, function(contador, fila) {
                    cargarZonas(fila.idZona);
                    $("#txtNit").val(fila.nit);
                    $("#txtCliente").val(fila.terceroSucursal);
                });
            }
        });
    }
}

function asignarValores() {
    fecha = $('#txtFecha').val();
    nota = $('#txaNota').val();
    
    if($("#selTipoPedido").val() == 1){
        idVendedor = $('#selVendedor').val();
    }else{
        idVendedor = -1;
    }
    
    tipoPedido = $('#selTipoPedido').val();
}
function obtenerValores() {
    asignarValores();
    data = 'idPedido=' + idPedido + '&idCliente=' + idCliente + '&idVendedor=' + idVendedor + '&idZona=' + idZona + '&fecha=' + fecha + '&nota=' + nota + '&tipoPedido=' + tipoPedido + '&idClienteServicio=' + idClienteServicioSeleccionado;
}
function crearTablaProducto() {
    $('#tblProductos').html(tabla + '');
}
function agregarArreglo() {
    var objeto = new Object();
    objeto.idProducto = idProducto;
    objeto.producto = $('#txtProducto').val();
    objeto.codigoProducto = $('#txtCodigoProducto').val();
    objeto.valorUnitario = $("#txtValorUnitario").val().replace('.', '');
    objeto.cantidad = $('#txtCantidad').val();
    objeto.nota = $('#txaNotaProducto').val();
    productos.push(objeto);

    montarTablaProducto();
    idProducto = '';
    $("#txtProducto, #txtCodigoProducto, #txtCantidad, #txtValorUnitario, #txaNotaProducto").val('');

}
function montarTablaProducto() {
    var table = '';
    idProductos = [];
    $.each(productos, function(contador, fila) {
        var idProducto = fila.idProducto;
        var codigoProducto = fila.codigoProducto;
        var producto = fila.producto;
        var nota = fila.nota;
        var valorUnitario = parseInt(fila.valorUnitario);
        var cantidad = fila.cantidad;
        var valorTotal = parseInt(cantidad) * valorUnitario;

        contadorProductos++;

        table += '<tr id="tr' + idProducto + '">';
        //table += '<td align="center">'+contadorProductos+'</td>';
        table += '<td align="right"><span>' + codigoProducto + '</span></td>';
        table += '<td>' + producto + '</td>';
        table += '<td>' + nota + '</td>';
        table += '<td>';
        table += '<div class="input-group">';
        table += '<span class="input-group-btn">';
        table += '<button class="btn btn-default" type="button" onclick="restarCantidad(' + idProducto + ')"><span class="fa fa-minus"></span></button>';
        table += '</span>';
        table += '<input type="text" id="' + idProducto + '" name="productos" class="form-control small" value="' + parseInt(cantidad) + '" style="text-align:center" onchange="validarCantidad(this)" onkeypress="validarNumeros(' + idProducto + ')" maxlength="10" accessKey="V">';
        table += '<span class="input-group-btn">';
        table += '<button class="btn btn-default" type="button" onclick="sumarCantidad(' + idProducto + ')"><span class="fa fa-plus"></span></button>';
        table += '</span>';
        table += '</div>';
        table += '</td>';
        table += '<td align="right" id="valorUnitario' + idProducto + '">' + agregarSeparadorMil(valorUnitario.toString()) + '</td>';
        table += '<td align="right" id="total' + idProducto + '">' + agregarSeparadorMil(valorTotal.toString()) + '</td>';
        table += '<td align="center"><span class="fa fa-trash" style="cursor: pointer" onclick="eliminar(' + idProducto + ')"></span></td>';
        table += '</tr>';

        idProductos.push(idProducto);
        var posicion = productosEliminados.indexOf(idProducto);
        productosEliminados[posicion] = undefined;
    });

    productosEliminados = productosEliminados.filter(function(n) {
        return n != undefined
    });//SE ELIMINAN LAS POSICIONES NULAS O VACÍAS 

    $('#tblProductos').html(tabla + table);
    autoCompletarProducto();
}
function crearListado(json) {
    var table = '';
    productos = [];
    $.each(json.data, function(contador, fila) {
        idCliente = fila.idCliente;
        idZona = fila.idZona;
        cargarZonas(fila.idZona);
        idVendedor = fila.idVendedor;
        tipoPedido = fila.tipoPedido;

        $("#selTipoPedido").val(fila.tipoPedido);
        $("#spnZona").val(fila.zona);
        $('#selVendedor').multiselect('select', fila.idVendedor, true);
        $("#txtNitVendedor").val(fila.numeroIdentificacionVendedor);
        $("#txtVendedor").val(fila.nombreVendedor);
        $("#txtNit").val(fila.nit);
        $("#txtCliente").val(fila.tercero + ' - ' + fila.sucursal);
        $("#txaNota").val(fila.notaPedido);
        $("#txtFecha").datepicker("update", fila.fecha);
        $("#txtFecha").val(fila.fecha);

        fecha = fila.fecha;
        nota = fila.notaPedido;

        consultarPedidoProducto();
    });

    $('#consultaTabla').html(tabla + table);
}
function consultarPedido() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/pedido.consultar.php',
        data: {idPedido: idPedido},
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
                cargarListado();
                return false;
            }
            crearListado(json);

        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}
function consultarPedidoProducto() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/pedidoProducto.consultar.php',
        data: {idPedido: idPedido},
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
                cargarListado();
                return false;
            }
            $.each(json.data, function(contador, fila) {
                var objeto = new Object();
                objeto.idProducto = fila.idProducto;
                objeto.producto = fila.producto;
                objeto.codigoProducto = fila.codigo;
                objeto.valorUnitario = fila.valorUnitario;
                objeto.cantidad = fila.cantidad;
                objeto.nota = fila.notaPedidoProducto;

                productos.push(objeto);
            });
            montarTablaProducto();

        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}
function cargarZonas(id) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/zona.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idZona: id},
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta('El cliente no tiene una zona asociada.');
                $('#spnZona').html('');
                idZona = null;
                return false;
            }

            $.each(json.data, function(contador, fila) {
                $('#spnZona').html(fila.zona);
                idZona = fila.idZona;
            });

            vendedorConsultar();

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function vendedorConsultar() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/vendedor.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idZona: idZona},
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            var control = $('#selVendedor');
            control.val();
            control.multiselect('destroy');
            control.empty();

            if (json.numeroRegistros == 0) {
                $('#selVendedor').multiselect({
                    maxHeight: 300
                    , nonSelectedText: '--Seleccione--'
                    , enableFiltering: true
                    , filterPlaceholder: 'Buscar'
                    , enableCaseInsensitiveFiltering: true
                });
                $("button.multiselect").css("width", "300px");
                $("input.form-control.multiselect-search").attr("accesskey", "V");

                alerta('No hay vendedores asociados a esta zona.');
                return false;
            }

            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idVendedor + '">' + fila.nombreCompleto + '</option>');
            });

            $('#selVendedor').multiselect({
                maxHeight: 300
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , enableCaseInsensitiveFiltering: true
            });
            $("button.multiselect").css("width", "300px");
            $("input.form-control.multiselect-search").attr("accesskey", "V");

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function autoCompletarCliente() {
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            $("#txtNit").val(ui.item.nit);
            idCliente = ui.item.idCliente;
            autoCompletarProducto();
            cargarZonas(ui.item.idZona);
        }
    });
}
function autoCompletarProducto() {
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarProductoCliente.php?idProductos=' + idProductos.join() + '&compuesto=true&idCliente='+idCliente,
        select: function(event, ui) {
            if(idCliente == "" || idCliente == null || idCliente == "null"){
                alerta("Debe seleccionar el cliente para seleccionar el producto");
                return false;
            }
            idProducto = ui.item.idProducto;
            $("#txtCodigoProducto").val(ui.item.codigo);
            $("#txtValorUnitario").val(agregarSeparadorMil(parseInt(ui.item.valorSalidaMostrar).toString()));
            validarCantidad(document.getElementById('txtCantidad'));
        }
    });
}
function consultarProducto(codigoProducto) {
    if (codigoProducto == '') {
        return false;
    }
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/transaccion.consultarProductos.php',
        type: 'POST',
        dataType: "json",
        data: {
            codigoProducto: codigoProducto,
            idProducto: idProductos.join(),
            compuesto: 'true'
        },
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                idProducto = '';
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta('No hay productos asociados con este código.');
                idProducto = '';
                $('#txtProducto').val('');
                return false;
            }

            $.each(json.data, function(contador, fila) {
                idProducto = fila.idProducto;
                $("#txtProducto").val(fila.producto);
                $("#txtValorUnitario").val(agregarSeparadorMil(parseInt(fila.valorSalida).toString()));
                validarCantidad(document.getElementById('txtCantidad'));
            });

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function consultarTercero(nit) {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/tercero.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {nit: nit},
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta('No hay terceros con este nit.');
                idCliente = '';
                $('#txtNit').val('');
                return false;
            }

            $.each(json.data, function(contador, fila) {
                consultarSucursal(fila.idTercero, fila.tercero);
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function consultarSucursal(idTercero, tercero) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idTercero: idTercero},
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

            if (json.numeroRegistros == 1) {
                $.each(json.data, function(contador, fila) {
                    idCliente = fila.idCliente;
                    $("#txtCliente").val(tercero + ' - ' + fila.sucursal);
                    cargarZonas(fila.idZona);
                });
            } else {
                var html = '';
                html += '<ul style="list-style:none; margin-top:1%;">';
                $.each(json.data, function(contador, fila) {
                    html += '<li><a data-dismiss="modal" style="cursor:pointer" onclick="asignar(\'' + fila.terceroSucursal + '\',' + fila.idCliente + ', ' + fila.idZona + ' )">' + fila.sucursal + '</li>';
                });
                html += '</ul>';

                bootbox.alert({
                    size: 'large',
                    title: 'Por favor escoja una sucursal de ' + tercero,
                    message: html
                });
            }

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function consultarVendedor(numeroIdentificacion) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/vendedor.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {numeroIdentificacion: numeroIdentificacion},
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
                $('#txtVendedor').val(fila.nombreCompleto);
                idVendedor = fila.idVendedor;
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function ajaxPedidoProducto(urlPedidoProducto) {
    $.ajax({
        async: false,
        url: urlPedidoProducto,
        type: 'POST',
        dataType: "json",
        data: {
            idPedido: idPedido,
            productos: productos,
            productosEliminados: productosEliminados
        },
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            alerta('La información del pedido se guardó correctamente.', true);
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function sumarCantidad(id) {
    $.each(productos, function(contador, fila) {
        if (fila.idProducto == id) {
            var elemento = $('input[id="' + id + '"]');
            var cantidad = parseInt(elemento.val());
            cantidad += 1;
            elemento.val(cantidad);
            fila.cantidad = cantidad;

            obtenerTotal(id, cantidad);
            return;
        }
    });

}
function restarCantidad(id) {
    $.each(productos, function(contador, fila) {
        if (fila.idProducto == id) {
            var elemento = $('input[id="' + id + '"]');
            var cantidad = parseInt(elemento.val());
            if (cantidad <= 1) {
                return;
            }
            cantidad -= 1;
            elemento.val(cantidad);

            obtenerTotal(id, cantidad);
            return;
        }
    });

}
function asignar(sucursal, idClient, idZon) {
    idCliente = idClient;
    $('#txtCliente').val(sucursal);
    cargarZonas(idZon);
}
function validarCantidad(elemento) {
    if (elemento.value == '' || parseInt(elemento.value) <= 0) {
        elemento.value = 1;
    }
}
function obtenerTotal(id, cantidad) {
    var valorUnitario = parseInt($('#valorUnitario' + id).html().replace('.', ''));
    var valorTotal = valorUnitario * cantidad;
    $('#total' + id).html(agregarSeparadorMil(valorTotal.toString()));
}
function eliminar(id) {
    var cont = null;
    $.each(productos, function(contador, fila) {
        if (fila.idProducto == id) {
            cont = contador;
            return;
        }
    });

    productos[cont] = undefined;
    productos = productos.filter(function(n) {
        return n != undefined
    });//SE ELIMINA LAS POSICIONES NULAS O VACÍAS 

    var posicion = idProductos.indexOf(id);
    idProductos[posicion] = undefined;
    idProductos = idProductos.filter(function(n) {
        return n != undefined
    });//SE ELIMINA LAS POSICIONES NULAS O VACÍAS 
    productosEliminados.push(id);

    autoCompletarProducto();
    montarTablaProducto();
}