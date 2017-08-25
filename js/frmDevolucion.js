var idTercero=null;
var idCliente=null;
var idTransaccion=null;
var numeroRecibo=null;
var idCaja=null;
var valoresProductos=[];

var data=null;
var tabla = '';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>Producto</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Cantidad a Devolver</th>';
    tabla += '</tr>';

$(function(){
    validarNumeros('txtNumeroRecibo');
    cargarTabla();
    cargarCajas();
    
    $("#imgConsultar").bind({
        'click':function(){
            limpiarVariables();
            if($("#txtNumeroRecibo").val() == ''){
                alerta('Por favor digite el número de la factura');
                return;
            }
            if($("#selCaja").val() == ''){
                alerta('Por favor seleccione la caja');
            }
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
    $('#imgGuardar').bind({
        'click':function(){
            if($("#txtNumeroRecibo").val() == ''){
                alerta('Por favor digite el número del recibo');
                return;
            }
            if($("#selCaja").val() == ''){
                alerta('Por favor seleccione la caja');
            }
            var bandera = false;
            
            $.each($('input[name="txtProductos"]'), function(contador, fila){
                if($(fila).val() != ''){
                    bandera = true;
                    return;
                }
            });
            if(bandera == false){
                alerta('Debe devolver almenos un producto');
                return;
            }
            
            var dataProductos = [];
            $.each($('input[name="txtProductos"]'),function(contador,fila){
                var objeto = new Object();
                if($(fila).val() != ''){
                    var objetoProducto = valoresProductos[$(fila).attr('id')];
                    objeto.idProducto = $(fila).attr('id');
                    objeto.cantidadDevolver = $(fila).val();
                    objeto.valorUnitario = objetoProducto.valorUnitario;
                    objeto.serial = objetoProducto.serial;
                    objeto.idBodega = objetoProducto.idBodega;
                    objeto.idTransaccionProducto = objetoProducto.idTransaccionProducto;
                    
                    dataProductos.push(objeto);
                }
            });
            
            $.ajax({
                url: localStorage.modulo + 'controlador/transaccion.devolverProductosFactura.php',
                type:'POST',
                dataType:"json",
                data:{
                        dataProductos:dataProductos,
                        idTransaccion:idTransaccion,
                        idCliente:idCliente,
                        idTercero:idTercero,
                        idCaja:idCaja
                     },
                success: function(json){
                    var mensaje = json.mensaje;
                    var exito = json.exito;

                    if(exito == 0){
                        alerta (mensaje);
                        return false;
                    }
                    
                    alerta(mensaje, true);

                }, error: function(xhr, opciones, error){
                    alert(error);
                    return false;
                }
            });
        }
    })
});
function asignarDatosEnvio(){
    numeroRecibo = $("#txtNumeroRecibo").val();
    idCaja = $("#selCaja").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'numeroRecibo=' + numeroRecibo + '&idCaja=' + idCaja;
}
function crearListado(json){
    var table = '';
    valoresProductos =[];
    $.each(json.data.detalle, function(contador, fila){
        idTransaccion = fila.idTransaccion;
        idTercero = fila.idTercero;
        idCliente = fila.idCliente;
        $('#divNumeroRecibo').html(fila.numeroRecibo);
        $('#divCliente').html(fila.cliente);
        $('#divCaja').html(fila.numeroCaja);
        $('#divFecha').html(fila.fecha);
        $('#divOficina').html(fila.oficina);
        
        table += '<tr>';
        table += '<td>'+fila.producto+'</td>';
        table += '<td align="center">'+fila.saldoCantidadProducto+'</td>';
        
        var disabled = "";
        if(fila.saldoCantidadProducto == 0){
            disabled = "disabled";
        }

        table += '<td align="center"><input ' + disabled + ' type="text" id="'+fila.idProducto+'" name="txtProductos" class="form-control medium" placeholder="Cantidad" accessKey="'+fila.saldoCantidadProducto+'" onchange="validarCantidad(this)"></td>';
        table += '</tr>';
        var objeto = new Object();
        objeto.valorUnitario = fila.valorUnitaSalidConImpue;
        objeto.serial = fila.serial;
        objeto.idBodega = fila.idBodega;
        objeto.idTransaccionProducto = fila.idTransaccionProducto;
        valoresProductos[fila.idProducto] = objeto;
    });
    $('#tblProductos').html(tabla + table);
}
function limpiarVariables(){
    idTercero=null;
    idCliente=null;
    idTransaccion=null;
    numeroRecibo=null;
    idCaja=null;
    valoresProductos=[];
}
function cargarTabla(){
    var table = '';
    table += '<tr>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '</tr>';
    $('#tblProductos').html(tabla + table);
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
function validarCantidad(elemento){ 
    
    if(parseInt(elemento.value) <= 0){
        elemento.value = "";
    }
    
    if(parseInt(elemento.value) > parseInt(elemento.accessKey)){
        alerta("El valor ingresado debe ser menor o igual al saldo.");
        elemento.value = "";
    }
}