$("#secPrincipal").css("border", "0px");
$("#consultaTabla").html("");


/*Variables Pedidos*/
var idPedido = null;
var idClientePedido = null;
var idVendedor = null;
var idZona = null;
var fechaInicio = null;
var fechaFin = null;
var idEstadoPedido = null;
var tipoPedido = null;
var arrIdPedidos = new Array();

/*Variables Ordeb Trabajo*/
var idTipoDocumento = null;
var numeroOrden = null;
var fechaInicioOrden = null;
var fechaFinOrden = null;
var idClienteOrden = null;
var idOrdenador = null;
var idMunicipio = null;
var idTecnico = null;
var idEstadoOrdenTrabajo = null;
var tipoOrdenTrabajo = null;

/*Variables Servicio*/
var idProductoServicio = null;
var idTecnicoServicio = null;
var nitClienteServicio = null;
var idClienteServicio = null;
var numeroClienteServicio = null;
var fechaInicioServicio = null;
var fechaFinServicio = null;
var idMunicipioServicio = null;
var idEstadoClienteServicio = null;
var tipoOrdenProcesar = null

var seccionCliente = null;

$(function() {

    


    cargarListadoPedidos();
    cargarListadoOrdenTrabajo();
    cargarListadoServicio();

    cargarTipoDocumento();
    consultarOrdenador();
    consultarTecnico();
    consultarEstadoOrdenTrabajo();
    consultarTipoOrdenTrabajo();
    consultarTipoPedido();
    consultarTecnicoServicio();
    consultarEstadoServicio();

    cargarZonas();
    //cargarEstadoPedido();
    autoCompletarVendedor();
    autoCompletarServicio();
    autoCompletarClienteOrden();
    autoCompletarClientePedido();
    autoCompletarClienteServicio();
    autoCompletarMunicipio();
    autoCompletarMunicipioServicio();


    crearCalendario("txtFechaInicioPedido");
    crearCalendario("txtFechaFinPedido");

    crearCalendario("txtFechaInicioOrden");
    crearCalendario("txtFechaFinOrden");

    crearCalendario("txtFechaInicioServicio");
    crearCalendario("txtFechaFinServicio");

    $("#txtNitClienteOrden").change(function() {
        if ($("#txtNitClienteOrden").val() != "") {
            consultarTerceroOrden($("#txtNitClienteOrden").val());
        }
    }).keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtClienteOrden").val("");
                $("#txtClienteOrden").keypress();
                idClienteOrden = null;
                break;
        }
    });
    $("#txtClienteOrden").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNitClienteOrden").val("");
                autoCompletarClienteOrden();
                idClienteOrden = null;
                break;
        }
    });

    $("#txtNitPedido").change(function() {
		if ($("#txtNitPedido").val() != "") {
            consultarTerceroPedido($("#txtNitPedido").val());
        }        
    }).keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtClientePedido").val("");
				//autoCompletarClientePedido
                $("#txtClientePedido").keypress();
                idClientePedido = null;
                break;
        }
    });
    $("#txtClientePedido").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNitPedido").val("");
                autoCompletarClientePedido();
                idClientePedido = null;
                break;
        }
    });


    $("#txtNitClienteServicio").change(function() {
        if ($("#txtNitClienteServicio").val() != "") {
            consultarTerceroServicio($("#txtNitClienteServicio").val());
        }
    }).keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtClienteServicio").val("");
                $("#txtClienteServicio").keypress();
                idClienteServicio = null;
                break;
        }
    });
    $("#txtClienteServicio").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNitClienteServicio").val("");
                autoCompletarClienteServicio();
                idClienteServicio = null;
                break;
        }
    });




    $("#txtNitVendedor").change(function() {
        consultarVendedor($("#txtNitVendedor").val());
    }).keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtVendedor").val("");
                idProducto = null;
                break;
        }
    });
    $("#txtVendedor").keypress(function(e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtNitVendedor").val("");
                idProducto = null;
                break;
        }
    });


    $("#imgLimpiarPedido").click(function() {
        limpiarControlesFormulario(document.fbeFormularioPedido);
        cargarListadoPedidos();
        limpiarVariablesPedido();
    });

    $("#imgLimpiarOrden").click(function() {
        limpiarControlesFormulario(document.fbeFormularioOrden);
        cargarListadoOrdenTrabajo();
        limpiarVariablesOrden();
    });

    $("#imgLimpiarServicio").click(function() {
        limpiarControlesFormulario(document.fbeFormularioServicio);
        cargarListadoServicio();
        limpiarVariablesServicio();
    });

    $("#imgOrdenTrabajo").click(function() {
        if(tipoOrdenProcesar == "" || tipoOrdenProcesar == null || tipoOrdenProcesar == "null"){
            tipoOrdenProcesar = "Instalacion";
        }

        var arrParametros = new Array();
        var objParametro = new Object();
        objParametro.id = "idOrdenTrabajo";
        objParametro.value = "";
        arrParametros.push(objParametro);
        var objParametro = new Object();
        objParametro.id = "tipo";
        objParametro.value = tipoOrdenProcesar;
        arrParametros.push(objParametro);
        var objParametro = new Object();
        objParametro.id = "idPedidos";
        objParametro.value = arrIdPedidos.toString();
        arrParametros.push(objParametro);
        abrirVentanaParametros(localStorage.modulo + 'vista/frmOrdenTrabajo.html', arrParametros);
    });

    $("#imgConsultarPedido").click(function() {
        arrIdPedidos = new Array()
        obtenerDatosEnvioPedido();

        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/ordenTrabajo.consultarPedidos.php',
            data: data,
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
                    alerta("No se encontraron registros con los parámetros establecidos");
                    cargarListadoPedidos();
                    return false;
                }
                crearListadoPedido(json);

            }, error: function(xhr, opciones, error) {
                alerta(error);
            }
        });
    });


    $("#imgConsultarOrdenTrabajo").click(function() {
        obtenerDatosEnvioOrdenTrabajo();

        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/ordenTrabajo.consultar.php',
            data: data,
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
                    alerta("No se encontraron registros con los parámetros establecidos");
                    cargarListadoOrdenTrabajo();
                    return false;
                }
                crearListadoOrdenTrabajo(json);

            }, error: function(xhr, opciones, error) {
                alerta(error);
            }
        });
    });


    $("#imgConsultarServicio").click(function() {
        obtenerDatosEnvioServicio();

        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/ordenTrabajo.consultarClienteServicio.php',
            data: data,
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
                    alerta("No se encontraron registros con los parámetros establecidos");
                    cargarListadoServicio();
                    return false;
                }
                crearListadoServicio(json);

            }, error: function(xhr, opciones, error) {
                alerta(error);
            }
        });
    });
    onClickNuevoCliente();
});

function onClickNuevoCliente() {
    $("#imgNuevoClientePedido").bind({
        "click": function() {
            seccionCliente = 0;
            abrirFormularioCliente();
        }
    });

    $("#imgNuevoClienteOrdenTrabajo").bind({
        "click": function() {
            seccionCliente = 1;
            abrirFormularioCliente();
        }
    });

    $("#imgNuevoClienteServicio").bind({
        "click": function() {
            seccionCliente = 2;
            abrirFormularioCliente();
        }
    });
}

function abrirFormularioCliente() {
    var formulario = "vista/frmCliente.html";
    var parametros = "?idCliente=null&origen=2";
    abrirPopup(localStorage.modulo + formulario + parametros, 'Cliente');
}

function asignarCliente(clienteId) {
    if (clienteId != null && clienteId != undefined) {
        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/cliente.consultar.php',
            type: 'POST',
            dataType: "json",
            data: {idCliente: clienteId},
            success: function(json) {
                $.each(json.data, function(contador, fila) {

                    switch (seccionCliente) {
                        case 0:
                            $("#txtNitPedido").val(fila.nit);
                            $("#txtClientePedido").val(fila.terceroSucursal);
                            idClientePedido = clienteId;
                            break;
                        case 1:
                            $("#txtNitClienteOrden").val(fila.nit);
                            $("#txtClienteOrden").val(fila.terceroSucursal);
                            idClienteOrden = clienteId;
                            break;
                        case 2:
                            $("#txtNitClienteServicio").val(fila.nit);
                            $("#txtClienteServicio").val(fila.terceroSucursal);
                            idClienteServicio = clienteId;
                            break;
                    }
                });
            }
        });
    }
    seccionCliente = null;
}

/*Funciones Tab Pedido*/
function cargarListadoPedidos() {
    $("#divConsultaPedido").html("");
    var tabla = '';
    tabla += '<table id="tblConsultaPedido" name="tblConsultaPedido" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>#</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Zona</th>';
    tabla += '<th>Empresa</th>';
    tabla += '<th>Vendedor</th>';
    tabla += '<th>Estado Pedido</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '<th>Fecha Creación</th>';
    tabla += '<th>Usuario Creación</th>';
    tabla += '<th>Seleccionar</th>';
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
    $("#divConsultaPedido").html(tabla);
}
/*Funciones Tab Orden de Trabajo*/
function cargarListadoOrdenTrabajo() {
    $("#divConsultaOrdenTrabajo").html("");
    var tabla = '';
    tabla += '<table id="tblConsultaOrden" name="tblConsultaOrden" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Número Orden</th>';
    tabla += '<th>Ordenador</th>';
    tabla += '<th>Municipio</th>';
    tabla += '<th>Fecha Inicio</th>';
    tabla += '<th>Fecha Fin</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Técnicos</th>';
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
    $("#divConsultaOrdenTrabajo").html(tabla);
}
function asignarValoresPedido() {
    fechaInicio = $('#txtFechaInicioPedido').val();
    fechaFin = $('#txtFechaFinPedido').val();
    //idEstadoPedido = $('#selEstadoPedido').val();
    idEstadoPedido = 1;//Activo
    idZona = $('#selZona').val();
    tipoPedido = $('#selTipoPedido').val();
}
function obtenerDatosEnvioPedido() {
    asignarValoresPedido();
    data = 'idPedido=' + idPedido + '&idCliente=' + idClientePedido + '&idVendedor=' + idVendedor + '&idZona=' + idZona + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin + '&idEstadoPedido=' + idEstadoPedido + '&tipoPedido=' + tipoPedido;
}
function crearListadoPedido(json) {
    $('#divConsultaPedido').html("");
    var tabla = '';
    tabla += '<table id="tblConsultaOrden" name="tblConsultaOrden" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Zona</th>';
    tabla += '<th>Empresa</th>';
    tabla += '<th>Vendedor</th>';
    tabla += '<th>Tipo Pedido</th>';
    tabla += '<th>Estado Pedido</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '<th>Fecha Creación</th>';
    tabla += '<th>Usuario Creación</th>';
    tabla += '<th>Ver Productos</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    $.each(json.data, function(contador, fila) {
        tabla += '<tr>';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.fecha + '</td>';
        tabla += '<td>'+  fila.nit + ' - ' + fila.tercero + ' - ' + fila.sucursal + '</td>';
        tabla += '<td>' + fila.zonaRegional + '</td>';
        tabla += '<td>' + fila.empresa + '</td>';
        tabla += '<td>' + fila.nombreVendedor + '</td>';
        tabla += '<td>' + fila.tipo + '</td>';
        tabla += '<td align="center">' + fila.pedidoEstado + '</td>';
        tabla += '<td align="right">' + agregarSeparadorMil(parseInt(fila.valorTotal).toString()) + '</td>';
        tabla += '<td>' + fila.fechaCreacion + '</td>';
        tabla += '<td>' + fila.usuarioCreacion + '</td>';
        tabla += '<td align="center"><span class="fa fa-navicon imagenesTabla" id="imgVerProductos' + contador + '" title="Ver Productos" class="imagenesTabla" onclick="visualizarProductos(' + fila.idPedido + ')""></span></td>';
        //tabla += '<td align="center"><input type="checkbox" id="chkPedido' + fila.idPedido + ' name="chkPedido" onclick="validarSeleccionPedido(this,' + fila.idPedido +')"></td>';
        tabla += '<td align="center"><img id="imgSeleccionarPedido' + contador + '" src="../imagenes/casilla_vacia.png" style="width: 30px;cursor:pointer" onclick="validarSeleccionPedido(this,' + fila.idPedido + ',\'' + fila.tipo + '\')" name="V"></td>';
        tabla += '</tr>';
    });
    tabla += '</table>';
    $('#divConsultaPedido').html(tabla);
}
function autoCompletarVendedor() {
    $("#txtVendedor").autocomplete({
        source: localStorage.modulo + 'ajax/vendedor.autocompletar.php',
        select: function(event, ui) {
            idVendedor = ui.item.idVendedor;
            $('#txtNitVendedor').val(ui.item.numeroIdentificacion);
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
function consultarTerceroOrden(nit) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarCliente.php',
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
                return false;
            }

            if (json.numeroRegistros == 1) {
                $.each(json.data, function(contador, fila) {
                    idClienteOrden = fila.idCliente;
                    $("#txtClienteOrden").val(fila.cliente);
                });
            } else {
                var html = '';
                html += '<div style="min-height: 12.4286px;'
                html += 'padding: 0px;'
                html += 'border-bottom: 1px solid #E5E5E5;';
                html += 'background-image: linear-gradient(to bottom, #455A64 0%, #455A64 100%);';
                html += 'background-repeat: repeat-x;';
                html += 'font-weight: bold;';
                html += 'border-radius: 4px;';
                html += 'padding-top: 0.6%;';
                html += 'font-size: 1.6em;';
                html += 'color: #FFF;">';
                html += 'Por favor escoja una sucursal</div>';

                html += '<ul style="list-style:none; margin-top:1%;">';
                $.each(json.data, function(contador, fila) {
                    html += '<li><a data-dismiss="modal" style="cursor:pointer" onclick="asignarClienteOrden(\'' + fila.cliente + '\',' + fila.idCliente + ')">' + fila.cliente + '</li>';
                });
                html += '</ul>';

                bootbox.alert(html);
            }

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function consultarTerceroPedido(nit) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarCliente.php',
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
                return false;
            }

            if (json.numeroRegistros == 1) {
                $.each(json.data, function(contador, fila) {
                    idClientePedido = fila.idCliente;
                    $("#txtClientePedido").val(fila.cliente);
                });
            } else {
                var html = '';
                html += '<div style="min-height: 12.4286px;'
                html += 'padding: 0px;'
                html += 'border-bottom: 1px solid #E5E5E5;';
                html += 'background-image: linear-gradient(to bottom, #455A64 0%, #455A64 100%);';
                html += 'background-repeat: repeat-x;';
                html += 'font-weight: bold;';
                html += 'border-radius: 4px;';
                html += 'padding-top: 0.6%;';
                html += 'font-size: 1.6em;';
                html += 'color: #FFF;">';
                html += 'Por favor escoja una sucursal</div>';

                html += '<ul style="list-style:none; margin-top:1%;">';
                $.each(json.data, function(contador, fila) {
                    html += '<li><a data-dismiss="modal" style="cursor:pointer" onclick="asignarClientePedido(\'' + fila.cliente + '\',' + fila.idCliente + ')">' + fila.cliente + '</li>';
                });
                html += '</ul>';

                bootbox.alert(html);
            }

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
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
                , buttonWidth: '200px'
            });



        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarEstadoPedido() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/estadoPedido.consultar.php',
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
                var control = $("#selEstadoPedido");
                control.empty();
                return false;
            }
            var control = $("#selEstadoPedido");
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEstadoPedido + '">' + fila.estadoPedido + '</option>');
            });
            $('#selEstadoPedido').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
                , buttonWidth: '220px'
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}
function visualizarProductos(id) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/pedidoProducto.consultar.php',
        data: {idPedido: id},
        dataType: "json",
        type: 'POST',
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            var html = '<table id="tblProductos" class="table table-bordered table-striped consultaTabla" style="margin-top:1%">';
            html += '<tr id="trEstatico">';
            html += '<th>#</th>';
            html += '<th>Código</th>';
            html += '<th>Producto</th>';
            html += '<th>Nota</th>';
            html += '<th style="width:15%">Cantidad</th>';
            html += '<th>Valor Unitario</th>';
            html += '<th>Valor Total</th>';
            html += '</tr>';

            $.each(json.data, function(contador, fila) {
                html += '<tr>';
                html += '<td align="center">' + (contador + 1) + '</td>';
                html += '<td align="right"><span>' + fila.codigo + '</span></td>';
                html += '<td>' + fila.producto + '</td>';
                html += '<td>' + fila.notaPedidoProducto + '</td>';
                html += '<td align="right">' + parseInt(fila.cantidad) + '</td>';
                html += '<td align="right">' + agregarSeparadorMil(parseInt(fila.valorUnitario).toString()) + '</td>';
                html += '<td align="right">' + agregarSeparadorMil(parseInt(fila.valorTotal).toString()) + '</td>';
                html += '</tr>';
            });

            html += '</table>';

            bootbox.alert(html);

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
/*function validarSeleccionPedido(control,idPedido){
 if(control.checked == true){
 arrIdPedidos.push(idPedido);
 }else{
 var posicionBorrar = arrIdPedidos.indexOf(idPedido);
 arrIdPedidos.splice(posicionBorrar,1);
 arrIdPedidos.filter(Boolean);
 }
 }*/
function validarSeleccionPedido(control, idPedido,tipo) {
    tipoOrdenProcesar = tipo;
    if (control.name == "V") {
        control.name = "L";
        control.src = "../imagenes/casilla_marcada.png";
        arrIdPedidos.push(idPedido);
    } else {
        control.name = "V";
        control.src = "../imagenes/casilla_vacia.png";
        var posicionBorrar = arrIdPedidos.indexOf(idPedido);
        arrIdPedidos.splice(posicionBorrar, 1);
        arrIdPedidos.filter(Boolean);
    }
}
/*Funciones Tab Orden Trabajo*/
function cargarTipoDocumento() {
    $.ajax({
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarTipoDocumento.php',
        type: 'POST',
        dataType: "json",
        data: {estado: true},
        success: function(json) {
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

            if (json.numeroRegistros == 1) {
                $.each(json.data, function(contador, fila) {
                    control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
                });
                $('#selTipoDocumento').attr("disabled", true);
            } else {
                control.append('<option value="">--Seleccione--</option>');
                $.each(json.data, function(contador, fila) {
                    control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
                });
            }

        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function consultarOrdenador() {
    $.ajax({
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarOrdenador.php',
        type: 'POST',
        dataType: "json",
        data: {estado: true},
        success: function(json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selOrdenador');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idOrdenador + '">' + fila.ordenador + '</option>');
            });

        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function consultarTecnico() {
    $.ajax({
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarTecnico.php',
        type: 'POST',
        dataType: "json",
        data: {estado: true},
        success: function(json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selTecnico');
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idTecnico + '">' + fila.tecnico + '</option>');
            });

            $('#selTecnico').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
                , buttonWidth: '300px'
            });



        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function autoCompletarClienteOrden() {
    $("#txtClienteOrden").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            $("#txtNitClienteOrden").val(ui.item.nit);
            idClienteOrden = ui.item.idCliente;
        }
    });
}
function autoCompletarClientePedido() {
    $("#txtClientePedido").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            $("#txtNitPedido").val(ui.item.nit);
            idClientePedido = ui.item.idCliente;
        }
    });
}
function asignarDatosEnvioOrdenTrabajo() {
    idTipoDocumento = $("#selTipoDocumento").val();
    numeroOrden = $("#txtNumeroOrden").val();
    fechaInicioOrden = $("#txtFechaInicioOrden").val();
    fechaFinOrden = $("#txtFechaFinOrden").val();
    idOrdenador = $("#selOrdenador").val();
    idTecnico = $("#selTecnico").val();
    idEstadoOrdenTrabajo = $("#selEstadoOrdenTrabajo").val();
    tipoOrdenTrabajo = $("#selTipoOrdenTrabajo").val();
}
function obtenerDatosEnvioOrdenTrabajo() {
    asignarDatosEnvioOrdenTrabajo();
    data = "idTipoDocumento=" + idTipoDocumento + "&numeroOrden=" + numeroOrden + "&fechaInicio=" + fechaInicioOrden + "&fechaFin=" + fechaFinOrden + "&idOrdenador=" + idOrdenador + "&idTecnico=" + idTecnico + "&idCliente=" + idClienteOrden + "&idMunicipio=" + idMunicipio + "&idEstadoOrdenTrabajo=" + idEstadoOrdenTrabajo + "&tipoOrdenTrabajo=" + tipoOrdenTrabajo;
}
function autoCompletarMunicipio() {
    $("#txtMunicipio").autocomplete({
        source: localStorage.modulo + 'ajax/ordenTrabajo.autocompletarMunicipio.php',
        select: function(event, ui) {
            idMunicipio = ui.item.idMunicipio;
        }
    });
}
function crearListadoOrdenTrabajo(json) {
    $('#divConsultaOrdenTrabajo').html("");
    var tabla = '';
    tabla += '<table id="tblConsultaOrden" name="tblConsultaOrden" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Número Orden</th>';
    tabla += '<th>Ordenador</th>';
    tabla += '<th>Tipo Orden</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th>Municipio</th>';
    tabla += '<th>Fecha Inicio</th>';
    tabla += '<th>Fecha Fin</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Técnicos</th>';
    tabla += '<th colspan="3">Acción</th>';
    tabla += '</tr>';
    $.each(json.data, function(contador, fila) {
        tabla += '<tr>';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td align="center">' + fila.fecha + '</td>';
        tabla += '<td>' + fila.tipoDocumento + '</td>';
        tabla += '<td>' + fila.numero + '</td>';
        tabla += '<td>' + fila.nombreOrdenador + '</td>';
        tabla += '<td>' + fila.tipo + '</td>';
        tabla += '<td>' + fila.estadoOrdenTrabajo + '</td>';
        tabla += '<td>' + fila.municipio + '</td>';
        tabla += '<td align="center">' + fila.fechaInicio + '</td>';

        if (fila.fechaFin != "" && fila.fechaFin != null && fila.fechaFin != "null") {
            tabla += '<td align="center">' + fila.fechaFin + '</td>';
        } else {
            tabla += '<td align="center"></td>';
        }

        //tabla += '<td align="center"><span class="fa fa-user imagenesTabla" id="imgVerClientes' + contador + '" title="Ver Clientes" class="imagenesTabla" onclick="visualizarClientes(' + fila.idOrdenTrabajo + ')""></span></td>';
        tabla += '<td>' + fila.cliente + '</td>';
        tabla += '<td align="center"><span class="fa fa-gear imagenesTabla" id="imgVerTecnicos' + contador + '" title="Ver Técnicos" class="imagenesTabla" onclick="visualizarTecnicos(' + fila.idOrdenTrabajo + ')""></span></td>';
        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgEditar' + contador + '" title="Editar Pedido" class="imagenesTabla" onclick="editarOrdenTrabajo(' + fila.idOrdenTrabajo + ')"></span></td>';
        
        if (fila.tipoOrdenTrabajo == 1) {//Instalación
            tabla += '<td align="center"><a onclick="abrirOrden(\'../controlador/ordenTrabajo.generarOrdenTrabaOMantePdf.php\',\''+fila.idOrdenTrabajo+'\',\''+fila.numero+'\',\'TRABAJO\');"><span class="fa fa-file imagenesTabla" id="imgExportarOrden' + contador + '" title="Orden Trabajo" style="color: rgb(0, 0, 0);"></span></a></td>';
        }else{
            tabla += '<td align="center"><a onclick="abrirOrden(\'../controlador/ordenTrabajo.generarOrdenTrabaOMantePdf.php\',\''+fila.idOrdenTrabajo+'\',\''+fila.numero+'\',\'MANTENIMIENTO\');"><span class="fa fa-file imagenesTabla" id="imgExportarOrden' + contador + '" title="Orden Trabajo" style="color: rgb(0, 0, 0);"></span></a></td>';
        }

        if (fila.tipoOrdenTrabajo == 1) {//Instalación
            tabla += '<td align="center"><a onclick="abrirOrden(\'../controlador/ordenTrabajo.generarOrdenServicioPdf.php\',\''+fila.idOrdenTrabajo+'\',\''+fila.numero+'\',\'\');"><span class="fa fa-clipboard imagenesTabla" id="imgExportarOrden' + contador + '" title="Orden de Servicio" style="color: rgb(0, 0, 0);"></span></a></td>';
        } else {//Mantenimiento
            tabla += '<td align="center"><a onclick="abrirOrden(\'../controlador/ordenTrabajo.generarOrdenServicioPdf.php\',\''+fila.idOrdenTrabajo+'\',\''+fila.numero+'\',\'\');"><span class="fa fa-clipboard imagenesTabla" id="imgExportarOrden' + contador + '" title="Orden Servicio - Mantenimiento" style="color: rgb(0, 0, 0);"></span></a></td>';
        }


        tabla += '</tr>';
    });
    tabla += '</table>';
    $('#divConsultaOrdenTrabajo').html(tabla);
}
function abrirOrden(link,idOrdenTrabajo,numero,tipoOrden){   
   window.open(''+link+'?idOrdenTrabajo=' + idOrdenTrabajo + '&numeroOrden=' + numero+'&tipoOrden=' + tipoOrden);
}
function editarOrdenTrabajo(idOrdenTrabajo) {
    abrirVentana(localStorage.modulo + 'vista/frmOrdenTrabajo.html', idOrdenTrabajo);
}
function limpiarVariablesPedido() {
    idPedido = null;
    idClientePedido = null;
    idVendedor = null;
    idZona = null;
    fechaInicio = null;
    fechaFin = null;
    idEstadoPedido = null;

    arrIdPedidos = new Array();
}
function limpiarVariablesOrden() {
    idTipoDocumento = null;
    numeroOrden = null;
    fechaInicioOrden = null;
    fechaFinOrden = null;
    idClienteOrden = null;
    idOrdenador = null;
    idMunicipio = null;
    idTecnico = null;
}
function visualizarTecnicos(idOrdenTrabajo) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoTecnico.consultar.php',
        data: {
            idOrdenTrabajo: idOrdenTrabajo
            , estado: true
        },
        dataType: "json",
        type: 'POST',
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            var html = '<table id="tblProductos" class="table table-bordered table-striped consultaTabla" style="margin-top:1%">';
            html += '<tr id="trEstatico">';
            html += '<th>#</th>';
            html += '<th>Técnico</th>';
            html += '<th>Principal</th>';
            html += '</tr>';

            $.each(json.data, function(contador, fila) {
                html += '<tr>';
                html += '<td align="center">' + (contador + 1) + '</td>';
                html += '<td><span>' + fila.tecnico + '</span></td>';

                if (fila.principal == true) {
                    html += '<td align="center">SI</td>';
                } else {
                    html += '<td align="center">NO</td>';
                }
                html += '</tr>';
            });

            html += '</table>';

            bootbox.alert(html);

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function visualizarClientes(idOrdenTrabajo) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoCliente.consultar.php',
        data: {
            idOrdenTrabajo: idOrdenTrabajo
            , estado: true
        },
        dataType: "json",
        type: 'POST',
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            var html = '<table id="tblProductos" class="table table-bordered table-striped consultaTabla" style="margin-top:1%">';
            html += '<tr id="trEstatico">';
            html += '<th>#</th>';
            html += '<th>Nit</th>';
            html += '<th>Cliente</th>';
            html += '<th>Empresa</th>';
            html += '</tr>';

            $.each(json.data, function(contador, fila) {
                html += '<tr>';
                html += '<td align="center">' + (contador + 1) + '</td>';
                html += '<td align="right"><span>' + fila.nit + '</span></td>';
                html += '<td><span>' + fila.cliente + '</span></td>';
                html += '<td><span>' + fila.empresa + '</span></td>';
                html += '</tr>';
            });

            html += '</table>';

            bootbox.alert(html);

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function consultarEstadoOrdenTrabajo() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarEstados.php',
        type: 'POST',
        dataType: "json",
        data: {
            estado: true
        },
        success: function(json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selEstadoOrdenTrabajo');
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEstadoOrdenTrabajo + '">' + fila.estadoOrdenTrabajo + '</option>');
            });

            $('#selEstadoOrdenTrabajo').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
                , buttonWidth: '220px'
            });

        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function consultarTipoOrdenTrabajo() {
    var control = $('#selTipoOrdenTrabajo');
    control.empty();
    control.append('<option value="1">Instalación</option>');
    control.append('<option value="2">Mantenimiento</option>');
    control.append('<option value="3">Desinstalación</option>');

    $('#selTipoOrdenTrabajo').multiselect({
        maxHeight: 600
        , nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
        , buttonWidth: '200px'
    });
}
function consultarTipoPedido() {
    var control = $('#selTipoPedido');
    control.empty();
    control.append('<option value="1">Instalación</option>');
    control.append('<option value="2">Mantenimiento</option>');
    control.append('<option value="3">Desinstalación</option>');

    $('#selTipoPedido').multiselect({
        maxHeight: 600
        , nonSelectedText: '--Seleccione--'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
        , buttonWidth: '200px'
    });
}
function asignarClienteOrden(sucursal, idClient) {
    idClienteOrden = idClient;
    $('#txtClienteOrden').val(sucursal);
}
function asignarClientePedido(sucursal, idClient) {
    idClientePedido = idClient;
    $('#txtClientePedido').val(sucursal);
}
/*Funciones Tab Servicio*/
function cargarListadoServicio() {
    $("#divConsultaServicio").html("");
    var tabla = '';
    tabla += '<table id="tblConsultaServicio" name="tblConsultaServicio" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Servicio</th>';
    tabla += '<th>Fecha Inicial</th>';
    tabla += '<th>Fecha Final</th>';
    tabla += '<th>Ordenador</th>';
    tabla += '<th>Técnico</th>';
    tabla += '<th>Municipio</th>';
    tabla += '<th>Estado</th>';
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
    tabla += '</tr>';
    tabla += '</table>';
    $("#divConsultaServicio").html(tabla);
}
function consultarTecnicoServicio() {
    $.ajax({
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarTecnico.php',
        type: 'POST',
        dataType: "json",
        data: {estado: true},
        success: function(json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selTecnicoServicio');
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idTecnico + '">' + fila.tecnico + '</option>');
            });

            $('#selTecnicoServicio').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
                , buttonWidth: '300px'
            });

        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function autoCompletarServicio() {
    $("#txtServicio").autocomplete({
        source: localStorage.modulo + 'ajax/ordenTrabajo.autoCompletarProducto.php?idProductos=&compuesto=true&servicio=false',
        select: function(event, ui) {
            idProductoServicio = ui.item.idProducto;
        }
    });
}
function autoCompletarClienteServicio() {
    $("#txtClienteServicio").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            $("#txtNitClienteServicio").val(ui.item.nit);
            idClienteServicio = ui.item.idCliente;
        }
    });
}
function consultarTerceroServicio(nit) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarCliente.php',
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
                return false;
            }

            if (json.numeroRegistros == 1) {
                $.each(json.data, function(contador, fila) {
                    idClienteServicio = fila.idCliente;
                    $("#txtClienteServicio").val(fila.cliente);
                });
            } else {
                var html = '';
                html += '<div style="min-height: 12.4286px;'
                html += 'padding: 0px;'
                html += 'border-bottom: 1px solid #E5E5E5;';
                html += 'background-image: linear-gradient(to bottom, #455A64 0%, #455A64 100%);';
                html += 'background-repeat: repeat-x;';
                html += 'font-weight: bold;';
                html += 'border-radius: 4px;';
                html += 'padding-top: 0.6%;';
                html += 'font-size: 1.6em;';
                html += 'color: #FFF;">';
                html += 'Por favor escoja una sucursal</div>';

                html += '<ul style="list-style:none; margin-top:1%;">';
                $.each(json.data, function(contador, fila) {
                    html += '<li><a data-dismiss="modal" style="cursor:pointer" onclick="asignarClienteServicio(\'' + fila.cliente + '\',' + fila.idCliente + ')">' + fila.cliente + '</li>';
                });
                html += '</ul>';

                bootbox.alert(html);
            }

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function asignarClienteServicio(sucursal, idClient) {
    idClienteServicio = idClient;
    $('#txtClienteServicio').val(sucursal);
}
function autoCompletarMunicipioServicio() {
    $("#txtMunicipioServicio").autocomplete({
        source: localStorage.modulo + 'ajax/ordenTrabajo.autocompletarMunicipio.php',
        select: function(event, ui) {
            idMunicipioServicio = ui.item.idMunicipio;
        }
    });
}
function consultarEstadoServicio() {
    $.ajax({
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarEstadoServicio.php',
        type: 'POST',
        dataType: "json",
        data: {
            estado: true
        },
        success: function(json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selEstadoClienteServicio');
            control.empty();
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEstadoClienteServicio + '">' + fila.estadoClienteServicio + '</option>');
            });

            $('#selEstadoClienteServicio').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
                , buttonWidth: '200px'
            });

            $('#selEstadoClienteServicio').val(1);//Instalado

        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}
function obtenerDatosEnvioServicio() {
    asignarDatosEnvioServicio();
    data = "idProducto=" + idProductoServicio + "&idTecnico=" + idTecnicoServicio + "&nit=" + nitClienteServicio + "&idCliente=" + idClienteServicio + "&numero=" + numeroClienteServicio + "&fechaInicio=" + fechaInicioServicio + "&fechaFin=" + fechaFinServicio + "&idMunicipio=" + idMunicipioServicio + "&idEstadoClienteServicio=" + 1;
}
function asignarDatosEnvioServicio() {
    idTecnicoServicio = $("#selTecnicoServicio").val();
    nitClienteServicio = $("#txtNitClienteServicio").val();
    numeroClienteServicio = $("#txtNumeroServicio").val();
    fechaInicioServicio = $("#txtFechaInicioServicio").val();
    fechaFinServicio = $("#txtFechaFinServicio").val();
    idEstadoClienteServicio = $("#selEstadoClienteServicio").val();
}
function crearListadoServicio(json) {
    $('#divConsultaServicio').html("");
    var tabla = '';
    tabla += '<table id="tblConsultaOrden" name="tblConsultaOrden" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Nro Servicio</th>';
    tabla += '<th>Servicio</th>';
    tabla += '<th>Fecha Inicial</th>';
    tabla += '<th>Fecha Final</th>';
    tabla += '<th>Ordenador</th>';
    tabla += '<th>Municipio</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th>Técnico</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';
    $.each(json.data, function(contador, fila) {
        tabla += '<tr>';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.cliente + '</td>';
        tabla += '<td>' + fila.numero + '</td>';
        tabla += '<td>' + fila.servicio + '</td>';
        tabla += '<td align="center">' + fila.fechaInicial + '</td>';

        if (fila.fechaFinal != "" && fila.fechaFinal != null && fila.fechaFinal != "null") {
            tabla += '<td align="center">' + fila.fechaFinal + '</td>';
        } else {
            tabla += '<td align="center"></td>';
        }


        tabla += '<td>' + fila.ordenador + '</td>';
        tabla += '<td>' + fila.municipio + '</td>';
        tabla += '<td>' + fila.estadoClienteServicio + '</td>';
        tabla += '<td align="center"><span class="fa fa-gear imagenesTabla" id="imgVerTecnicos' + contador + '" title="Ver Técnicos" class="imagenesTabla" onclick="visualizarTecnicos(' + fila.idOrdenTrabajo + ')""></span></td>';
        //tabla += '<td align="center"><span class="fa fa-gears imagenesTabla" id="imgGenerarMantenimiento' + contador + '" title="Generar Mantenimiento" class="imagenesTabla" onclick="generarMantenimiento('+fila.idClienteServicio +')"></span></td>';
        tabla += '<td align="center"><img src="../imagenes/mantenimiento.png" id="imgGenerarMantenimiento' + contador + '" title="Generar Mantenimiento" class="imagenesTabla" onclick="generarMantenimiento(' + fila.idClienteServicio + ')"></td>';
        tabla += '<td align="center"><span class="fa fa-times-circle imagenesTabla" id="imgGenerarDesinstalacion' + contador + '" title="Generar Desinstalación" class="imagenesTabla" onclick="generarDesinstalacion(' + fila.idClienteServicio + ')"></span></td>';
        tabla += '</tr>';
    });
    tabla += '</table>';
    $('#divConsultaServicio').html(tabla);
}
function generarMantenimiento(idClienteServicio) {
    var arrParametros = new Array();
    var objParametro = new Object();
    objParametro.id = "idOrdenTrabajo";
    objParametro.value = "";
    arrParametros.push(objParametro);
    var objParametro = new Object();
    objParametro.id = "tipo";
    objParametro.value = "Mantenimiento";
    arrParametros.push(objParametro);
    var objParametro = new Object();
    objParametro.id = "idClienteServicio";
    objParametro.value = idClienteServicio;
    arrParametros.push(objParametro);
    abrirVentanaParametros(localStorage.modulo + 'vista/frmOrdenTrabajo.html', arrParametros);
}
function generarDesinstalacion(idClienteServicio) {
    var arrParametros = new Array();
    var objParametro = new Object();
    objParametro.id = "idOrdenTrabajo";
    objParametro.value = "";
    arrParametros.push(objParametro);
    var objParametro = new Object();
    objParametro.id = "tipo";
    objParametro.value = "Desintalación";
    arrParametros.push(objParametro);
    var objParametro = new Object();
    objParametro.id = "idClienteServicio";
    objParametro.value = idClienteServicio;
    arrParametros.push(objParametro);
    abrirVentanaParametros(localStorage.modulo + 'vista/frmOrdenTrabajo.html', arrParametros);
}
function limpiarVariablesServicio() {
    idProductoServicio = null;
    idTecnicoServicio = null;
    nitClienteServicio = null;
    idClienteServicio = null;
    numeroClienteServicio = null;
    fechaInicioServicio = null;
    fechaFinServicio = null;
    idMunicipioServicio = null;
}