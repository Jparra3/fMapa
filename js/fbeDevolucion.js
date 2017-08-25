var numeroRecibo = null;
var idCaja = null;
var idOficina = null;
var fechaInicio = null;
var fechaFin = null;
var idCliente = null;
var idEstadoTransaccion = null;

var data=null;
var tabla = '';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Nro. Docum Devol</th>';
    tabla += '<th>Nro. Factu Afect</th>';
    tabla += '<th>Nro. Caja</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Oficina</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';

$(function(){
    cargarCajas();
    cargarOficinas();
    autoCompletarCliente();
    validarNumeros("txtNumeroRecibo");
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    cargarEstados();
    cargarTabla();
    
    $("#imgNuevo").click(function(){
        abrirVentana(localStorage.modulo + 'vista/frmDevolucion.html', '');	 
    });
    
    $("#imgLimpiar").click(function(){
        limpiarVariables();
        limpiarControlesFormulario(document.fbeFormulario);
        cargarTabla();
    });
    
    $("#imgConsultar").bind({
        'click':function(){
            obtenerDatosEnvio();
            $.ajax({
                url: localStorage.modulo + 'controlador/transaccion.consultarFactura.php',
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
                        alerta('No se encontraron registros con los parámetros indicados.');
                        cargarTabla();
                        return false;
                    }

                    crearListado(json);

                }, error: function(xhr, opciones, error){
                    alert(error);
                    return false;
                }
            });
        }
    });
});
function asignarDatosEnvio(){
    numeroRecibo = $("#txtNumeroRecibo").val();
    idCaja = $("#selCaja").val();
    idOficina = $("#selOficina").val();
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    idEstadoTransaccion = $("#selTransaccionEstado").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'numeroRecibo=' + numeroRecibo + '&idCaja=' + idCaja + '&idOficina=' + idOficina + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin + '&idCliente=' + idCliente + '&idEstadoTransaccion='+idEstadoTransaccion;
}
function cargarTabla(){
    var table = '';
    table += '<tr>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    
    table += '</tr>';
    $('#consultaTabla').html(tabla + table);
}
function crearListado(json){
    var table = '';
    $.each(json.data, function(contador, fila){
        table += '<tr>';
        table += '<td>'+fila.cliente+'</td>';
        table += '<td align="right">'+fila.numeroRecibo+'</td>';
        if(fila.numeroReciboAfecta == "" || fila.numeroReciboAfecta == "null" || fila.numeroReciboAfecta == null){
            fila.numeroReciboAfecta = "";
        }
        table += '<td align="right">'+fila.numeroReciboAfecta+'</td>';
        table += '<td align="right">'+fila.numeroCaja+'</td>';
        table += '<td>'+fila.fecha+'</td>';
        table += '<td>'+fila.oficina+'</td>';
        table += '<td align="center">'+fila.estadoTransaccion+'</td>';
        table += '<td align="center"><span class="fa fa-navicon imagenesTabla" id="imgVerProductos' + contador + '" title="Ver Productos" class="imagenesTabla" onclick="visualizarProductos('+fila.idTransaccion +')""></span></td>';
        table += '</tr>';
    });
    $('#consultaTabla').html(tabla + table);
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
function autoCompletarCliente(){
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursalNit.php',
        select:function(event, ui){
            idCliente = ui.item.idCliente;
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
function cargarEstados(){
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarEstados.php',
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
            
            var control = $('#selTransaccionEstado');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){                
                if(fila.inicial == true){
                    control.append('<option selected value="' + fila.idEstadoTransaccion + '">' + fila.estadoTransaccion + '</option>');
                }else{
                    control.append('<option value="' + fila.idEstadoTransaccion + '">' + fila.estadoTransaccion + '</option>');
                }
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function visualizarProductos(id){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/transaccionProducto.consultar.php',
        data:{idTransaccion:id},
        dataType:"json",
        type:'POST',
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            var total = 0;
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
                var totalProducto = (parseInt(fila.valorUnitaSalidConImpue) * parseInt(fila.cantidad));
                html += '<tr>';
                html += '<td align="center">'+(contador+1)+'</td>';
                html += '<td align="right"><span>'+fila.codigo+'</span></td>';
                html += '<td>'+fila.producto+'</td>';
                html += '<td>'+fila.nota+'</td>';
                html += '<td align="right">'+parseInt(fila.cantidad)+'</td>'; 
                html += '<td align="right">'+agregarSeparadorMil(parseInt(fila.valorUnitaSalidConImpue).toString())+'</td>';
                html += '<td align="right">'+agregarSeparadorMil(totalProducto.toString())+'</td>';
                html += '</tr>';
                
                total += totalProducto;
            });
            html += '<tr>';
            html += '<td colspan="3" align="center"><b>TOTAL</b></td>';
            html += '<td colspan="4" align="right">'+agregarSeparadorMil(total.toString())+'</td>';
            html += '</tr>';
            html += '</table>';
            
            bootbox.alert(html);

        },error: function(xhr, opciones, error){
            alerta(error);
            return false;
        }
    });
}
function limpiarVariables(){
    numeroRecibo = null;
    idCaja = null;
    idOficina = null;
    fechaInicio = null;
    fechaFin = null;
    idCliente = null;
    idEstadoTransaccion = null;
    data=null;
}