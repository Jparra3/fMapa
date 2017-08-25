var idPedido=null;
var idCliente=null;
var idVendedor=null;
var idZona=null;
var tipoPedido=null;
var fechaInicio=null;
var fechaFin=null;
var idEstadoPedido=null;

var data = null;
var tabla = '';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>#</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Zona</th>';
    tabla += '<th>Vendedor</th>';
    tabla += '<th>Tipo Pedido</th>';
    tabla += '<th>Estado Pedido</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '<th>Fecha Creación</th>';
    tabla += '<th>Usuario Creación</th>';
    tabla += '<th colspan="3">Acción</th>';
    tabla += '</tr>';

$(function(){
    crearCalendario("txtFechaPedidoInicio");
    crearCalendario("txtFechaPedidoFin");
    cargarZonas();
    cargarEmpresas();
    autoCompletarVendedor();
    autoCompletarCliente();
    cargarListado();
    cargarEstadoPedido();
    validarNumeros('txtNit');
    validarNumeros('txtNitVendedor');
    $('#selTipoPedido').multiselect({
        maxHeight: 600
        ,nonSelectedText: '--Seleccione--'	
        ,enableFiltering: true
        ,filterPlaceholder: 'Buscar'
        ,numberDisplayed: 1
        ,enableCaseInsensitiveFiltering: true
    });
    
   $("#imgNuevo").click(function(){
       abrirVentana(localStorage.modulo + 'vista/frmPedido.html', '');	 
   });
   $("#imgConsultar").click(function(){
       obtenerDatosEnvio();
       
       $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/pedido.consultar.php',
            data:data,
            dataType:"json",
            type:'POST',
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    alerta(mensaje);
                    return false;
                }

                if(json.numeroRegistros == 0){
                    cargarListado();
                    return false;
                }
                crearListado(json);
                
            },error: function(xhr, opciones, error){
                alerta(error);
            }
        });
   });
    $("#txtNit").change(function(){
        consultarTercero( $("#txtNit").val());
    }).keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtCliente").val("");
                $("#txtCliente").keypress();
                idCliente = null;
            break;
        }
    });
    $("#txtCliente").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtNit").val("");
                autoCompletarCliente();
                idCliente = null;
            break;
        }
    });
    $("#txtNitVendedor").change(function(){
        consultarVendedor($("#txtNitVendedor").val());
    }).keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtVendedor").val("");
                idProducto = null;
            break;
        }
    });
    $("#txtVendedor").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtNitVendedor").val("");
                idProducto = null;
            break;
        }
    });

   $("#imgLimpiar").click(function(){
        cargarListado();
        limpiarControlesFormulario(document.fbeFormulario);
        limpiarVariables(); 
    });
});
function asignarValores(){
    fechaInicio = $('#txtFechaPedidoInicio').val();
    fechaFin = $('#txtFechaPedidoFin').val();
    idEstadoPedido = $('#selEstadoPedido').val();
    tipoPedido = $('#selTipoPedido').val();
    idZona = $('#selZona').val();
    idEmpresa = $('#selEmpresa').val();
}
function obtenerDatosEnvio(){
    asignarValores();
    data = 'idPedido='+idPedido+'&idCliente='+idCliente+'&idVendedor='+idVendedor+'&idZona='+idZona+'&fechaInicio='+fechaInicio+'&fechaFin='+fechaFin+'&idEstadoPedido='+idEstadoPedido+'&tipoPedido='+tipoPedido +'&idEmpresa='+idEmpresa;
}

function cargarListado(){
    var table = '';
    table += '<tr>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '</tr>';
    
    $('#consultaTabla').html(tabla + table);
}

function crearListado(json){
    var table = '';
    $.each(json.data, function(contador, fila){
        table += '<tr>';
        table += '<td align="center">'+(contador+1)+'</td>';
        table += '<td>'+fila.fecha+'</td>';
        table += '<td>'+fila.tercero +' - '+fila.sucursal+'</td>';
        table += '<td>'+fila.zona+'</td>';
        table += '<td>'+fila.nombreVendedor+'</td>';
        table += '<td align="center">'+fila.tipo+'</td>';
        table += '<td align="center">'+fila.pedidoEstado+'</td>';
        table += '<td align="right">'+agregarSeparadorMil(parseInt(fila.valorTotal).toString())+'</td>';
        table += '<td>'+fila.fechaCreacion+'</td>';
        table += '<td>'+fila.usuarioCreacion+'</td>';
        table += '<td align="center"><span class="fa fa-navicon imagenesTabla" id="imgVerProductos' + contador + '" title="Ver Productos" class="imagenesTabla" onclick="visualizarProductos('+fila.idPedido +')""></span></td>';
        table += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgEditar' + contador + '" title="Editar Pedido" class="imagenesTabla" onclick="editarPedido('+fila.idPedido +')"></span></td>';
        table += '<td align="center"><span class="fa fa-minus imagenesTabla" id="imgAnular' + contador + '" title="Cancelar Pedido" class="imagenesTabla" onclick="anularPedido('+fila.idPedido +')"></span></td>';
        table += '</tr>';
    });
    
    $('#consultaTabla').html(tabla + table);
}

function cargarEstadoPedido(){   
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/estadoPedido.consultar.php',
        data:null,
        dataType:"json",
        type:'POST',
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                var control = $("#selEstadoPedido");
                control.empty();
                return false;
            }
            var control = $("#selEstadoPedido");
            control.empty();
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idEstadoPedido + '">' + fila.estadoPedido + '</option>');
            });
            $('#selEstadoPedido').multiselect({
                maxHeight: 600
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
        },error: function(xhr, opciones, error){
            alerta(error);
        }
    });
}
function limpiarVariables(){
    idPedido=null;
    idCliente=null;
    idVendedor=null;
    idZona=null;
    fechaInicio=null;
    fechaFin=null;
    idEstadoPedido=null;

    data = null;
}
function editarPedido(idPedido){
    abrirVentana(localStorage.modulo + 'vista/frmPedido.html', idPedido);
}
function anularPedido(id){
    bootbox.confirm("¿Está seguro(a) de anular el pedido?", function(result) {
        if(result==true){
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/pedido.inactivar.php',
                data:{idPedido:id},
                dataType:"json",
                type:'POST',
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    
                    if(exito == 0){
                        alerta(mensaje);
                        return false;
                    }
                    
                    alerta(mensaje);
                    $("#imgConsultar").click();
                    
                },error: function(xhr, opciones, error){
                    alerta(error);
                    return false;
                }
            });		
        }  
    }); 
}
function cargarZonas(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/zona.consultar.php',
        type:'POST',
        dataType:"json",
        data:null,
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                return false;
            }
            var control = $('#selZona');
            control.empty();
            
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idZona + '">' + fila.zonaRegional + '</option>');
            });
            
            $('#selZona').multiselect({
                maxHeight: 600
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
            
            $("button.multiselect").css("width","300px");
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function autoCompletarVendedor(){
    $("#txtVendedor").autocomplete({
        source: localStorage.modulo + 'ajax/vendedor.autocompletar.php',
        select:function(event, ui){
            idVendedor = ui.item.idVendedor;
            $('#txtNitVendedor').val(ui.item.numeroIdentificacion);
        }
    });
}
function autoCompletarCliente(){
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select:function(event, ui){
            $("#txtNit").val(ui.item.nit);
            idCliente = ui.item.idCliente;
        }
    });
}
function visualizarProductos(id){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/pedidoProducto.consultar.php',
        data:{idPedido:id},
        dataType:"json",
        type:'POST',
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
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
            
            $.each(json.data, function(contador, fila){
                html += '<tr>';
                html += '<td align="center">'+(contador+1)+'</td>';
                html += '<td align="right"><span>'+fila.codigo+'</span></td>';
                html += '<td>'+fila.producto+'</td>';
                html += '<td>'+fila.notaPedidoProducto+'</td>';
                html += '<td align="right">'+parseInt(fila.cantidad)+'</td>';
                html += '<td align="right">'+agregarSeparadorMil(parseInt(fila.valorUnitario).toString())+'</td>';
                html += '<td align="right">'+agregarSeparadorMil(parseInt(fila.valorTotal).toString())+'</td>';
                html += '</tr>';
            });
            
            html += '</table>';
            
            bootbox.alert(html);

        },error: function(xhr, opciones, error){
            alerta(error);
            return false;
        }
    });
}
function cargarEmpresas(){
    $.ajax({
        async: false,
        url: '/Seguridad/ajax/tercero.cargarEmpresas.php',
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
                return false;
            }
            var control = $("#selEmpresa");
            control.empty();
            control.append('<option value="">--SELECCIONE--</option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEmpresa + '">' + fila.empresa + '</option>');
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}