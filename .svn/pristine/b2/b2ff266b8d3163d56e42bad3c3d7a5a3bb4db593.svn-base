var idPedidoMaximo = null;
$(function(){
    $body = $("body");
    $(document).on({
        ajaxStart: function() {$body.removeClass("loading");},
        ajaxStop: function() {$body.removeClass("loading");},    
        ajaxError: function() {$body.removeClass("loading");}
    });
    
    validaLogueo();
    cargarListado();
    consultarPedidos();    
    setInterval(function(){
        consultarPedidos();
    },10000);
    
    cargarListadoDespachados();
    consultarPedidosDespachados();    
});
function consultarPedidos(){
    $.ajax({
        url: '../controlador/pedido.consultar.php',
        type:'POST',
        dataType:"json",
        data:{idPedido:idPedidoMaximo
            , idPedidoEstado: 1},
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
            
            if(idPedidoMaximo == null){
                crearListado(json);
            }else{
                concatenarListado(json);
            }
            
            idPedidoMaximo = json.idPedidoMaximo;
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function cargarListado(){
    $("#divListado").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th><b>Cod. Pedido</b></th>';
    tabla += '<th><b>Info. Pedido</b></th>';
    tabla += '<th><b>Productos</b></th>';
    tabla += '<th><b>Acción</b></th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListado").html(tabla);
}
function crearListado(json){
    $("#divListado").html("");
    var tabla = '<table id="tblListadoProductos" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th><b>Cod. Pedido</b></th>';
    tabla += '<th><b>Info. Pedido</b></th>';
    tabla += '<th><b>Productos</b></th>';
    tabla += '<th><b>Acción</b></th>';
    tabla += '</tr>';
    $.each(json.data, function(contador, fila){
        tabla += '<tr id="' + fila.idPedido + '">';
        tabla += '<td style="vertical-align: middle; text-align: center;">' + fila.idPedido + '</td>';
        tabla += '<td style="vertical-align: middle;">';
        tabla += '<p><b>Cliente: </b>' + fila.cliente + '</p>';
        tabla += '<p><b>Fecha: </b>' + fila.fecha + '</p>';
        tabla += '<p><b>Forma de Pago: </b>' + fila.formaPago + '</p>';
        tabla += '<p><b>Teléfono: </b>' + fila.telefono + '</p>';
        tabla += '<p><b>Barrio: </b>' + fila.barrio + '</p>';
        tabla += '<p><b>Dirección: </b>' + fila.direccion + '</p>';
        tabla += '</td>';
        tabla += '<td>';
            tabla += '<table class="table table-bordered">';
            tabla += '<tr>';
            tabla += '<th><b>Producto</b></th>';
            tabla += '<th><b>Cantidad</b></th>';
            tabla += '<th><b>Precio</b></th>';
            tabla += '<th><b>Total</b></th>';
            tabla += '<th><b>Nota</b></th>';
            tabla += '<th><b>Acción</b></th>';
            tabla += '</tr>';
            var totalPedido = 0;
            $.each(fila.productos, function(contador2, fila2){
                tabla += '<tr id="' + fila2.idPedidoProducto + '">';
                tabla += '<td>' + fila2.producto + '</td>';
                tabla += '<td align="center">' + fila2.cantidad + '</td>';
                tabla += '<td align="right">' + agregarSeparadorMil(fila2.valorUnitaConImpue) + '</td>';
                tabla += '<td align="right">' + agregarSeparadorMil(fila2.total) + '</td>';
                
                if(fila2.nota != null && fila2.nota != "null")
                    tabla += '<td>' + fila2.nota + '</td>';
                else
                    tabla += "<td>&nbsp;</td>";	
                
                tabla += '<td style="vertical-align: middle; text-align: center;"><span onClick="anularProducto(' + fila.idPedido + ',' + fila2.idPedidoProducto + ',' + fila2.total + ')" class="fa fa-trash imagenesTabla" title="Eliminar Producto"></span></td>';
                tabla += '</tr>';
                
                totalPedido = totalPedido + parseFloat(fila2.total);
            });
            totalPedido = totalPedido + parseFloat(fila.valorDomicilio);
            tabla += '<tr>';
            tabla += '<td colspan="3">Domicilio</td>';
            tabla += '<td align="right">' + agregarSeparadorMil(fila.valorDomicilio) + '</td>';
            tabla += "<td colspan='2'>&nbsp;</td>";	
            tabla += '</tr>';
            tabla += '<tr>';
            tabla += '<td colspan="3" align="right"><b>Total</b></td>';
            tabla += '<td id="totalPedido' + fila.idPedido + '" align="right">' + agregarSeparadorMil(String(totalPedido)) + '</td>';
            tabla += "<td colspan='2'>&nbsp;</td>";
            tabla += '</tr>';
            tabla += '</table>';
        tabla += '</td>';
        tabla += '<td style="vertical-align: middle; text-align: center;">';
        tabla += '<span onClick="despacharPedido(' + fila.idPedido + ')" class="fa fa-check-circle fa-2x imagenesTabla" title="Despachar"></span><br><br>';
        tabla += '<span onClick="imprimir(' + fila.idPedido + ')" class="fa fa-print fa-2x imagenesTabla" title="Imprimir"></span><br><br>';
        tabla += '<span onClick="anularPedido(' + fila.idPedido + ')" class="fa fa-trash fa-2x imagenesTabla" title="Eliminar Pedido"></span>';
        tabla += '</td>';
        tabla += '</tr>';
    });
    tabla += '</table>';
    $("#divListado").html(tabla);
}
function concatenarListado(json){
    var pedidosNuevos = 0;
    var html = "";
    $.each(json.data, function(contador, fila){
        html += '<tr id="' + fila.idPedido + '">';
        html += '<td style="vertical-align: middle; text-align: center;">' + fila.idPedido + '</td>';
        html += '<td style="vertical-align: middle;">';
        html += '<p><b>Cliente: </b>' + fila.cliente + '</p>';
        html += '<p><b>Fecha: </b>' + fila.fecha + '</p>';
        html += '<p><b>Forma de Pago: </b>' + fila.formaPago + '</p>';
        html += '<p><b>Teléfono: </b>' + fila.telefono + '</p>';
        html += '<p><b>Barrio: </b>' + fila.barrio + '</p>';
        html += '<p><b>Dirección: </b>' + fila.direccion + '</p>';
        html += '</td>';
        html += '</td>';
        html += '<td>';
            html += '<table class="table table-bordered">';
            html += '<tr>';
            html += '<th><b>Producto</b></th>';
            html += '<th><b>Cantidad</b></th>';
            html += '<th><b>Precio</b></th>';
            html += '<th><b>Total</b></th>';
            html += '<th><b>Nota</b></th>';
            html += '<th><b>Acción</b></th>';
            html += '</tr>';
            var totalPedido = 0;
            $.each(fila.productos, function(contador2, fila2){
                html += '<tr id="' + fila2.idPedidoProducto + '">';
                html += '<td>' + fila2.producto + '</td>';
                html += '<td align="center">' + fila2.cantidad + '</td>';
                html += '<td align="right">' + agregarSeparadorMil(fila2.valorUnitaConImpue) + '</td>';
                html += '<td align="right">' + agregarSeparadorMil(fila2.total) + '</td>';
                
                if(fila2.nota != null && fila2.nota != "null")
                    html += '<td>' + fila2.nota + '</td>';
                else
                    html += "<td>&nbsp;</td>";	
                
                html += '<td style="vertical-align: middle; text-align: center;"><span onClick="anularProducto(' + fila.idPedido + ',' + fila2.idPedidoProducto + ',' + fila2.total + ')" class="fa fa-trash imagenesTabla" title="Eliminar Producto"></span></td>';
                html += '</tr>';
                
                totalPedido = totalPedido + parseFloat(fila2.total);
            });
            totalPedido = totalPedido + parseFloat(fila.valorDomicilio);
            html += '<tr>';
            html += '<td colspan="3">Domicilio</td>';
            html += '<td align="right">' + agregarSeparadorMil(fila.valorDomicilio) + '</td>';
            html += "<td colspan='2'>&nbsp;</td>";	
            html += '</tr>';
            html += '<tr>';
            html += '<td colspan="3" align="right"><b>Total</b></td>';
            html += '<td id="totalPedido' + fila.idPedido + '" align="right">' + agregarSeparadorMil(String(totalPedido)) + '</td>';
            html += "<td colspan='2'>&nbsp;</td>";	
            html += '</tr>';
            html += '</table>';
        html += '</td>';
        html += '<td style="vertical-align: middle; text-align: center;">';
        html += '<span onClick="despacharPedido(' + fila.idPedido + ')" class="fa fa-check-circle fa-2x imagenesTabla" title="Despachar"></span><br><br>';
        html += '<span onClick="imprimir(' + fila.idPedido + ')" class="fa fa-print fa-2x imagenesTabla" title="Imprimir"></span><br><br>';
        html += '<span onClick="anularPedido(' + fila.idPedido + ')" class="fa fa-trash fa-2x imagenesTabla" title="Eliminar Pedido"></span>';
        html += '</td>';
        html += '</tr>';
        
        pedidosNuevos++;
    });
    $("#tblListadoProductos").append(html);
    
    var mensaje = "";
    if(pedidosNuevos == 1){
        mensaje = '<p style="font-size:17px;">Hay ' + pedidosNuevos + ' pedido nuevo.</p>';
    }else{
        mensaje = '<p style="font-size:17px;">Hay ' + pedidosNuevos + ' pedidos nuevos.</p>';
    }
    
    var audio = document.getElementById("audNotificacion");
	audio.load();
    audio.autoplay = true;
    notificacion(mensaje, true);
}

function consultarPedidosDespachados(){
    $.ajax({
        url: '../controlador/pedido.consultar.php',
        type:'POST',
        dataType:"json",
        data:{idPedidoEstado: 2},
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
            
            crearListadoDespachados(json);
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function cargarListadoDespachados(){
    $("#divListadoDespachados").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th><b>Cod. Pedido</b></th>';
    tabla += '<th><b>Info. Pedido</b></th>';
    tabla += '<th><b>Productos</b></th>';
    tabla += '<th><b>Acción</b></th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoDespachados").html(tabla);
}
function crearListadoDespachados(json){
    $("#divListadoDespachados").html("");
    var tabla = '<table id="tblListadoProductosDespachados" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th><b>Cod. Pedido</b></th>';
    tabla += '<th><b>Info. Pedido</b></th>';
    tabla += '<th><b>Productos</b></th>';
    tabla += '<th><b>Acción</b></th>';
    tabla += '</tr>';
    $.each(json.data, function(contador, fila){
        tabla += '<tr id="' + fila.idPedido + '">';
        tabla += '<td style="vertical-align: middle; text-align: center;">' + fila.idPedido + '</td>';
        tabla += '<td style="vertical-align: middle;">';
        tabla += '<p><b>Cliente: </b>' + fila.cliente + '</p>';
        tabla += '<p><b>Fecha: </b>' + fila.fecha + '</p>';
        tabla += '<p><b>Forma de Pago: </b>' + fila.formaPago + '</p>';
        tabla += '<p><b>Teléfono: </b>' + fila.telefono + '</p>';
        tabla += '<p><b>Barrio: </b>' + fila.barrio + '</p>';
        tabla += '<p><b>Dirección: </b>' + fila.direccion + '</p>';
        tabla += '</td>';
        tabla += '<td>';
            tabla += '<table class="table table-bordered">';
            tabla += '<tr>';
            tabla += '<th><b>Producto</b></th>';
            tabla += '<th><b>Cantidad</b></th>';
            tabla += '<th><b>Precio</b></th>';
            tabla += '<th><b>Total</b></th>';
            tabla += '<th><b>Nota</b></th>';
            tabla += '</tr>';
            var totalPedido = 0;
            $.each(fila.productos, function(contador2, fila2){
                tabla += '<tr>';
                tabla += '<td>' + fila2.producto + '</td>';
                tabla += '<td align="center">' + fila2.cantidad + '</td>';
                tabla += '<td align="right">' + agregarSeparadorMil(fila2.valorUnitaConImpue) + '</td>';
                tabla += '<td align="right">' + agregarSeparadorMil(fila2.total) + '</td>';
                
                if(fila2.nota != null && fila2.nota != "null")
                    tabla += '<td>' + fila2.nota + '</td>';
                else
                    tabla += "<td>&nbsp;</td>";	
                
                totalPedido = totalPedido + parseFloat(fila2.total);
            });
            totalPedido = totalPedido + parseFloat(fila.valorDomicilio);
            tabla += '<tr>';
            tabla += '<td colspan="3">Domicilio</td>';
            tabla += '<td align="right">' + agregarSeparadorMil(fila.valorDomicilio) + '</td>';
            tabla += "<td>&nbsp;</td>";	
            tabla += '</tr>';
            tabla += '<tr>';
            tabla += '<td colspan="3" align="right"><b>Total</b></td>';
            tabla += '<td align="right">' + agregarSeparadorMil(String(totalPedido)) + '</td>';
            tabla += "<td>&nbsp;</td>";	
            tabla += '</tr>';
            tabla += '</table>';
        tabla += '</td>';
        
        tabla += '<td style="vertical-align: middle; text-align: center;"><span onClick="imprimir(' + fila.idPedido + ')" class="fa fa-print fa-2x imagenesTabla" title="Imprimir"></span></td>';
        
        tabla += '</tr>';
    });
    tabla += '</table>';
    $("#divListadoDespachados").html(tabla);
}

function anularProducto(idPedido, idPedidoProducto, total){
    idPedidoProductoEstado = 3;//Estado Cancelado
    bootbox.confirm("¿Está seguro(a) de cancelar este item del pedido?", function(result) {
        if(result == true){
            cambiarEstadoPedidoProducto(idPedidoProducto, idPedidoProductoEstado);
            $("#" + idPedidoProducto).remove();
            var totalPedido = parseFloat(quitarSeparadorMil($("#totalPedido" + idPedido).html()));
            totalPedido = totalPedido - parseFloat(total);
            $("#totalPedido" + idPedido).html(agregarSeparadorMil(String(totalPedido)));
        }
    });
}
function cambiarEstadoPedidoProducto(idPedidoProducto, idPedidoProductoEstado){
    $.ajax({
        async: false,
        url: '../controlador/pedidoProducto.cambiarEstado.php',
        type:'POST',
        dataType:"json",
        data:{idPedidoProducto:idPedidoProducto
            , idPedidoProductoEstado: idPedidoProductoEstado},
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function despacharPedido(idPedido){
    idPedidoEstado = 2;//Estado Despachado
    cambiarEstado(idPedido, idPedidoEstado);
    $("#" + idPedido).remove();
    consultarPedidosDespachados();
}
function anularPedido(idPedido){
    idPedidoEstado = 3;//Estado Cancelado
    
    bootbox.confirm("¿Está seguro(a) de cancelar el pedido?", function(result) {
        if(result == true){
            cambiarEstado(idPedido, idPedidoEstado);
            $("#" + idPedido).remove();
        }
    });
}
function cambiarEstado(idPedido, idPedidoEstado){
    $.ajax({
        async: false,
        url: '../controlador/pedido.cambiarEstado.php',
        type:'POST',
        dataType:"json",
        data:{idPedido:idPedido
            , idPedidoEstado: idPedidoEstado},
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function imprimir(idPedido){
    $.ajax({
        url: localStorage.modulo + 'controlador/pedido.imprimir.php',
        type:'POST',
        dataType:"json",
        data:{
            idPedido: idPedido
        },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            var ruta = json.ruta;
            
            if(exito == 0){
                alert(mensaje);
                return false;
            }
            
            window.open(ruta, '_blank');
        }
    });
}