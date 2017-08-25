var numeroRecibo = null;
var idCaja = null;
var idOficina = null;
var fechaInicio = null;
var fechaFin = null;
var idCliente = null;
var idLineaProducto = null;
var idProducto = null;
var idBodega = null;

var data = null;
$(function(){
    autoCompletarCliente();
    cargarBodegas();
    cargarCajas();
    cargarOficinas();
    validarNumeros("txtNoRecibo");
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    cargarProductos();
    cargarListado();
    $("#btnBuscarLineas").click(function(){
        cargarLinea();
    });
    
    $("#txtCliente").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                idCliente = null;
            break;
        }
    });
    
    $("#txtLinea").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                idLineaProducto = null;
            break;
        }
    });
    
    $("#imgConsultar").click(function(){
        obtenerDatosEnvio();
        $.ajax({
            url: localStorage.modulo + 'controlador/reporteVenta.consultar.php',
            type:'POST',
            dataType:"json",
            data:data,
            success: function(json){
                var mensaje = json.mensaje;
                var exito = json.exito;

                if(exito == 0){
                    alerta (mensaje);
                    return false;
                }

                if(json.numeroRegistros == 0){
                    alerta("No se encontraron registros con los parámetros indicados.");
                    cargarListado();
                    return false;
                }

                crearListado(json);

            }, error: function(xhr, opciones, error){
                alert(error);
                return false;
            }
        });
    });
    
    $("#imgLimpiar").click(function(){
        cargarListado();
        limpiarControlesFormulario(document.fbeFormulario);
        limpiarVariables(); 
        
        $('#selProducto').val("");
        $('#selProducto').multiselect('destroy');
        $('#selProducto').multiselect({
            maxHeight: 400
            ,nonSelectedText: '--Seleccione--'	
            ,enableFiltering: true
            ,filterPlaceholder: 'Buscar'
            ,numberDisplayed: 1
            ,enableCaseInsensitiveFiltering: true
        });
        $("button.multiselect").css("width","300px");
    });
});
function asignarDatosEnvio(){
    numeroRecibo = $("#txtNoRecibo").val();
    idCaja = $("#selCaja").val();
    idOficina = $("#selOficina").val();
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    idProducto = $("#selProducto").val();
    idBodega = $("#selBodega").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'numeroRecibo=' + numeroRecibo + '&idCaja=' + idCaja + '&idOficina=' + idOficina + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin + '&idCliente=' + idCliente + '&idLineaProducto=' + idLineaProducto + '&idProducto=' + idProducto + '&idBodega=' + idBodega;
}
function cargarLinea(){
    $.ajax({
        async:false,
        url: localStorage.modulo  + "controlador/producto.cargarLinea.php",
        type:'POST',
        dataType:"html",
        data:null,
        success: function(html){
            bootbox.alert(html);
        }
    });
}
function asignar(lineaProducto, idLinea){
    idLineaProducto = idLinea;
    $("#txtLinea").val(lineaProducto);
}
function cargarCajas(){
    $.ajax({
        url: localStorage.modulo + 'controlador/caja.consultar.php',
        type:'POST',
        dataType:"json",
        data:null,
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                return false;
            }
            
            var control = $('#selCaja');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){                
                control.append('<option value="' + fila.idCaja + '">' + fila.numeroCaja + " - " + fila.bodega + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function cargarBodegas(){
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
        type:'POST',
        dataType:"json",
        data:null,
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                return false;
            }
            
            var control = $('#selBodega');
            control.empty();
            
            if(json.numeroRegistros > 1){
                control.append('<option value="">--Seleccione--</option>');
            }
            
            $.each(json.data, function(contador, fila){                
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function autoCompletarCliente(){
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursalNit.php',
        select:function(event, ui){
            idCliente = ui.item.idCliente;
        }
    });
}
function cargarProductos(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/producto.consultar.php',
        type:'POST',
        dataType:"json",
        data:{estado: true},
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
            var control = $('#selProducto');
            control.empty();
            
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idProducto + '">' + fila.codigo + ' - ' + fila.producto + '</option>');
            });
            
            $('#selProducto').multiselect({
                maxHeight: 400
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
function cargarOficinas(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/transaccion.consultarOficinas.php',
        type:'POST',
        dataType:"json",
        data:{estado:true},
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                return false;
            }
            
            var control = $('#selOficina');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idOficina + '">' + fila.oficina + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}

function cargarListado(){
	$("#consultaTabla").html("");
	var tabla = '';
	tabla += '<tr>';
        tabla += '<th>#</th>';
        tabla += '<th>Cliente</th>';
        tabla += '<th>Fecha</th>';
        tabla += '<th>No. Recibo</th>';
        tabla += '<th>No. Caja</th>';
        tabla += '<th>Bodega</th>';
        tabla += '<th>Oficina</th>';
        tabla += '<th>Cod. Producto</th>';
        tabla += '<th>Linea Producto</th>';
	tabla += '<th>Producto</th>';
        tabla += '<th>Sald Cant</th>';
        tabla += '<th>Cant.</th>';
        tabla += '<th>Valor</th>';
        tabla += '<th>Valor Total</th>';
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
        tabla += "<td>&nbsp;</td>";	
	tabla += "<td>&nbsp;</td>";
	tabla += '</tr>';
	$("#consultaTabla").html(tabla);		
}
function crearListado(json){
    
    var numeroReciboActual = null;
    var color = "#DDDDDD'";
    
    $("#consultaTabla").html("");
    var tabla = '';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>No. Factura</th>';
    tabla += '<th>No. Caja</th>';
    tabla += '<th>Bodega</th>';
    tabla += '<th>Oficina</th>';
    tabla += '<th>Cod. Producto</th>';
    tabla += '<th>Linea Producto</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Sald Cant.</th>';
    tabla += '<th>Cant.</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Valor Total</th>';
    tabla += '</tr>';
    $.each(json.data.detalle, function(contador, fila){
        
        if(fila.numeroRecibo != numeroReciboActual){
            if(color == "#F2F2F2"){
                color = "#FFFFFF";
            }else{
                color = "#F2F2F2";
            }
            numeroReciboActual = fila.numeroRecibo;
        }
            
        tabla += '<tr style="background-color:' + color + '">';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.cliente + '</td>';
        tabla += '<td align="center">' + fila.fecha + '</td>';
        tabla += '<td align="center">' + fila.numeroRecibo + '</td>';
        tabla += '<td align="center">' + fila.numeroCaja + '</td>';
        tabla += '<td>' + fila.bodega + '</td>';
        tabla += '<td>' + fila.oficina + '</td>';
        tabla += '<td align="right">' + fila.codigo + '</td>';
        tabla += '<td>' + fila.lineaProducto + '</td>';
        tabla += '<td>' + fila.producto + '</td>';
        tabla += '<td align="right">' + fila.saldoCantidadProducto + '</td>';
        tabla += '<td align="right">' + fila.cantidad + '</td>';
        tabla += '<td align="right">' + agregarSeparadorMil(String(fila.valorUnitaSalidConImpue)) + '</td>';
        tabla += '<td align="right">' + agregarSeparadorMil(String(fila.valorTotal)) + '</td>';
        tabla += '</tr>';
    });
    tabla += '<tr>';
    tabla += '<td align="right" colspan="13"><b>Total</b></td>';
    tabla += '<td align="right">' + agregarSeparadorMil(String(json.data.total)) + '</td>';
    tabla += '</tr>';
    $("#consultaTabla").html(tabla);		
}
function limpiarVariables(){
    numeroRecibo = null;
    idCaja = null;
    idOficina = null;
    fechaInicio = null;
    fechaFin = null;
    idCliente = null;
    idLineaProducto = null;
    idProducto = null;
    idBodega = null;

    data = null;

}