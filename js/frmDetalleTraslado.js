var valorRecibido = variableUrl();
var idTransaccionEntrada = valorRecibido[0];
var idTransaccionSalida = valorRecibido[1];
 
$(function(){ 
    $.ajax({
        async:false,
        url:'../controlador/transaccionProducto.consultarDetalleTraslado.php',
        type:'POST',
        dataType:'json',
        data:{
                idTransaccionEntrada:idTransaccionEntrada,
                idTransaccionSalida:idTransaccionSalida
            },
        success:function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){
                alerta(mensaje);
                return;
            }
            var tabla;
            $.each(json.dataEntrada, function(contador, fila){
                tabla = '';
                tabla += '<tr>';
                tabla += '<th>Tercero</th>';
                tabla += '<td>'+fila.tercero+'</td>';
                tabla += '<th>Oficina</th>';
                tabla += '<td colspan="2">'+fila.oficina+'</td>';
                tabla += '</tr>';
				tabla += '</tr>';
                tabla += '<th>Tipo Documento</th>';
                tabla += '<td>'+fila.tipoDocumento+'</td>';
                tabla += '<th>Número Documento</th>';
                tabla += '<td colspan="2" align="right">'+fila.numeroTipoDocumento+'</td>';
                tabla += '</tr>';
                tabla += '<tr>';
                tabla += '<th>Fecha</th>';
                tabla += '<td>'+fila.fecha+'</td>';
                tabla += '<th>Estado</th>';
                tabla += '<td colspan="2">'+fila.transaccionEstado+'</td>';
                tabla += '</tr>';
				tabla += '<tr>';
				tabla += '<td colspan="6" style="height:10px"></td>';
                tabla += '</tr>';
                tabla += '<tr>';
                tabla += '<th>Código</th>';
                tabla += '<th>Producto</th>';
                tabla += '<th>Serial</th>';
                tabla += '<th>Cantidad</th>';
                tabla += '<th>Bodega</th>';
                tabla += '</tr>';
            });
            
            $.each(json.dataDetalleEntrada, function(contador, fila){
                tabla += '<tr>';
                tabla += '<td align="right">'+fila.codigo+'</td>';
                tabla += '<td>'+fila.producto+'</td>';
                tabla += '<td>'+fila.serial+'</td>';
                tabla += '<td align="right">'+fila.cantidad+'</td>';
                tabla += '<td>'+fila.bodega+'</td>';
                tabla += '</tr>';
            });
            
            $('#tblTrasladoEntrada').html(tabla);
            
            $.each(json.dataSalida, function(contador, fila){
                tabla = '';
                tabla += '<tr>';
                tabla += '<th>Tercero</th>';
                tabla += '<td>'+fila.tercero+'</td>';
                tabla += '<th>Oficina</th>';
                tabla += '<td colspan="2">'+fila.oficina+'</td>';
                tabla += '</tr>';
				tabla += '</tr>';
                tabla += '<th>Tipo Documento</th>';
                tabla += '<td>'+fila.tipoDocumento+'</td>';
                tabla += '<th>Número Documento</th>';
                tabla += '<td colspan="2" align="right">'+fila.numeroTipoDocumento+'</td>';
                tabla += '</tr>';
                tabla += '<tr>';
                tabla += '<th>Fecha</th>';
                tabla += '<td>'+fila.fecha+'</td>';
                tabla += '<th>Estado</th>';
                tabla += '<td colspan="2">'+fila.transaccionEstado+'</td>';
                tabla += '</tr>';
				tabla += '<tr>';
				tabla += '<td colspan="6" style="height:10px"></td>';
                tabla += '</tr>';
                tabla += '<tr>';
                tabla += '<th>Código</th>';
                tabla += '<th>Producto</th>';
                tabla += '<th>Serial</th>';
                tabla += '<th>Cantidad</th>';
                tabla += '<th>Bodega</th>';
                tabla += '</tr>';
            });
            
            $.each(json.dataDetalleSalida, function(contador, fila){
                tabla += '<tr>';
                tabla += '<td align="right">'+fila.codigo+'</td>';
                tabla += '<td>'+fila.producto+'</td>';
                tabla += '<td>'+fila.serial+'</td>';
                tabla += '<td align="right">'+fila.cantidad+'</td>';
                tabla += '<td>'+fila.bodega+'</td>';
                tabla += '</tr>';
            });
            
            $('#tblTrasladoSalida').html(tabla);
        },error:function(xhr,opciones,error){
            
        }
    })
});

function variableUrl(){
	var src = '';
	var cadenaPrueba = '';
	if(String( window.location.href ).split('?')[1]){
		src = String( window.location.href ).split('?')[1];
		src = src.replace("%C2%A0%C2%A0%C2%A0","");
		src = decodeURI(src);
		var srcDos = src.split('=');
		
		src = src.split('&');
		for(i=0; i < src.length; i++){
			src[i] = src[i].substring(src[i].indexOf('=')+1);
		}
		if(src[3] != '1'){
			cadena = src[0].indexOf(' ');	
			src[0] = src[0].substring(cadena+1);
		}
	}
	return src;
}
