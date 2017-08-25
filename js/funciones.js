function consultarServiciosClientes(idCliente,contenedor,requiereSeleccionar){
    $(contenedor).html("");
    var retorno = false;
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/cliente.consultarInformacionServicio.php',
        type:'POST',
        dataType:"json",
        data:{
                idCliente:idCliente
             },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta("El cliente no tiene vinculado servicios");
                retorno = false
                return false;
            }
            
            if(json.numeroRegistros == 0){
                alerta("El cliente no tiene vinculado servicios");
                retorno = false
                return false;
            }
            retorno = true;
            crearListaOrdeTrabClie(json,contenedor,requiereSeleccionar);
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
    return retorno;
}
function crearListaOrdeTrabClie(json,contenedor,requiereSeleccionar){
    var objInforClien  = null;
    var ij = 0;
    var kl = 0;
    var html = '';
    html += '<table class="table table-bordered table-striped consultatabla tree">';
    html += '<tr>';
    html += '<th colspan="8">Servicios Instalados</th>';
    html += '</tr>';
    
    for(var i = 0;i < json.data.length ;i++){
        
        objInforClien = json.data[i];
        
        ij++;
        html += '<tr class="treegrid-' + (ij) + '" style="font-weight: bold;">';
        html += '<td colspan="8">';
        
         
        if(requiereSeleccionar == true){
            if(json.data.length  == 1){
                html += '<input type="radio" id="rdoClienteServicio' + objInforClien.idClienteServicio + '" name="rdoClienteServicio" value="' + objInforClien.idClienteServicio + '" style="margin-left: 10px;margin-right: 10px;" checked>';
            }else{
                html += '<input type="radio" id="rdoClienteServicio' + objInforClien.idClienteServicio + '" name="rdoClienteServicio" value="' + objInforClien.idClienteServicio + '" style="margin-left: 10px;margin-right: 10px;">';
            }
        }
        html += '<span class="fa fa-user" style="margin-left: 6px;margin-right: 6px;"></span>' + objInforClien.numero + ' - ' + objInforClien.cliente + ' - ' + objInforClien.productoComposicion;
        html += '</td>';
        
        html += '</tr>';
        kl = ij;
        kl++;
        html += '<tr class="treegrid-' + (kl) + ' treegrid-parent-' + (ij) + ' " style="font-weight: bold;" >';
        html += '<td>Código</th>';
        html += '<td>Producto</td>';
        html += '<td>U. Medid</td>';
        html += '<td>Valor</td>';
        html += '<td>Cantidad</td>';
        html += '<td>Bodega</td>';
        html += '<td>Serial</td>';
        html += '<td>Nota</td>';
        html += '</tr>';
        kl++;
        
        if(objInforClien.productos.length == 0){
            html += '<tr class="treegrid-' + (kl) + ' treegrid-parent-' + (ij) + '" >';
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += '</tr>';
        }
        
        for(var j = 0;j < objInforClien.productos.length ;j++){
            
            //Se valida si es una posicion vacia
            if(objInforClien.productos[j] == null || objInforClien.productos[j] == ""){
                continue;
            }
            var objProducto = objInforClien.productos[j];
            html += '<tr class="treegrid-' + (kl) + ' treegrid-parent-' + (ij) + '" >';
            kl++;
            html += "<td align='right'>" + objProducto.codigoProductoCompone + "</td>";		
            html += "<td>" + objProducto.productoCompone + "</td>";
            html += "<td>" + objProducto.unidadMedida + "</td>";
            html += "<td align='right'>" + agregarSeparadorMil(parseInt(objProducto.valorEntraConImpue).toString()) + "</td>";
            html += "<td align='right'>" + parseInt(objProducto.cantidad) + "</td>";
            html += "<td>" + objProducto.bodega + "</td>";
            if(objProducto.serial != "" && objProducto.serial != null && objProducto.serial != "NULL"){
                html += "<td><b>" + objProducto.serial + "</b></td>";
            }else{
                html += "<td></td>";
            }
            if(objProducto.nota != "" && objProducto.nota != null && objProducto.nota != "NULL" && objProducto.nota != "null"){
                html += "<td><b>" + objProducto.nota + "</b></td>";
            }else{
                html += "<td></td>";
            }
            html += '</tr>';
        }
        ij = kl;
    }
    html += '</table>';
    html += '</div';
    html += '</div';
    
    $(contenedor).html(html);
    
    $('.tree').treegrid({
        'initialState': 'expanded',
    });
}