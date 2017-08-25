var codigoTipoNaturaleza = "CO";
var idTipoDocumentoEntrada = '12';
var idTipoDocumentoSalida = '13';
var idBodega = null;
var idProducto = null;
var idLineaProducto = null;

var data = null;
var tabla = '';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>Código Producto</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Cantidad Total</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Línea Producto</th>';
    tabla += '<th>Bodega</th>';
    tabla += '<th>Cantidad a Trasladar</th>';
    tabla += '<th><img id="imgProductos" src="../imagenes/casilla_vacia.png" style="width: 30px;cursor:pointer" onclick="habilitarTodo(this);" alt="V"></th>';
    tabla += '</tr>';
    
$(function(){
    autoCompletarProducto();
    cargarTipoDocumento();
    cargarTabla();
    cargarBodegas();
    $('#imgTrasladar').hide();
    
    $("#txtCodigo").change(function(){
        if($("#txtCodigo").val() == '')
            return;
        consultarProducto($("#txtCodigo").val());
    }).keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtProducto").val("");
                idProducto = null;
            break;
        }
    });
    $("#txtProducto").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtCodigo").val("");
                idProducto = null;
            break;
        }
    });
    $('#imgConsultar').bind({
        'click':function(){
            
            if($('#selBodegaOrigen').val() == ''){
                alerta('Por favor elija la bodega origen.');
                return;
            }
            
            obtenerDatos();
            $.ajax({
                async:false,
                url:'../controlador/productoExistencia.consultarExistencia.php',
                type:'POST',
                dataType:'json',
                data:data,
                success:function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    
                    if(exito == 0){
                        alerta(mensaje);
                        return;
                    }
                    if(json.numeroRegistros == 0){
                        cargarTabla();
                        alerta('No se encontraron registros con los parámetros indicados');
                        return;
                    }
                    
                    crearListado(json);
                }
            });
        }
    });
    $('#imgTrasladar').bind({
        'click':function(){
            if($('#selBodegaOrigen').val() == ''){
                alerta('Por favor escoja la bodega origen.');
                return;
            }
            if($('#selBodegaDestino').val() == ''){
                alerta('Por favor escoja la bodega destino.');
                return;
            }
            var dataProductos = [];
            $.each($('input[name="cantidadesProductos"]'),function(contador,fila){
                var control = $(this);
                if(control.val() != ''){
                    var objeto = new Object
                    var id = control.attr('id');
                    var arreglo = $('#div'+id).html().split(',');
                    objeto.idProducto = arreglo[0];
                    objeto.serial = arreglo[1];
                    objeto.cantidad = control.val();

                    dataProductos.push(objeto);
                }
            });
            
            $.ajax({
                async:false,
                url:'../controlador/productoExistencia.trasladarProducto.php',
                type:'POST',
                dataType:'json',
                data:{
                        productosTrasladados:dataProductos,
                        bodegaOrigen:$('#selBodegaOrigen').val(),
                        bodegaDestino:$('#selBodegaDestino').val(),
                        idTipoDocumentoEntrada:idTipoDocumentoEntrada,
                        idTipoDocumentoSalida:idTipoDocumentoSalida
                     },
                success:function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    
                    alerta(mensaje);
                    if(exito == 0){
                        return;
                    }
                    $('#imgLimpiar').click();
                    abrirPopup(localStorage.modulo + 'vista/frmDetalleTraslado.html?idTransaccionEntrada='+json.idTransaccionEntrada+'&idTransaccionSalida='+json.idTransaccionSalida, 'Productos'); 
                }
            });
        }
    })
    $('#selBodegaOrigen').bind({
        'change':function(){
            $('#selBodegaDestino option').show();
            $('#selBodegaDestino option[value="'+$(this).val()+'"]').hide();
        }
    })
    $('#imgLimpiar').bind({
        'click':function(){
            limpiarVariables();
            cargarTabla();
            limpiarControlesFormulario(document.fbeFormulario);
        }
    });
    $("#btnBuscarLineas").click(function(){
        cargarLinea();
    });
});
function asignarValores(){
    idTipoDocumento = $('#selTipoDocumento').val();
    idBodega = $('#selBodegaOrigen').val();
}
function obtenerDatos(){
    asignarValores();
    data = 'idTipoDocumento='+idTipoDocumento+'&idLineaProducto='+idLineaProducto+'&idProducto='+idProducto+'&idBodega='+idBodega+'&bodega=true';
}
function limpiarVariables(){
    idProducto = null;
    idBodega = null;
    idTipoDocumento = null;
    idLineaProducto = null;
    data = null;
}
function crearListado(json){
    var table = '';
    
    $.each(json.data, function(contador, fila){
        table += '<tr>';
        table += '<td name="td'+contador+'" align="right">'+fila.codigo+'</td>';
        table += '<td name="td'+contador+'">'+fila.producto+'</td>';
        table += '<td name="td'+contador+'" align="right">'+fila.cantidad+'</td>';
        table += '<td name="td'+contador+'">'+fila.serial+'</td>';
        table += '<td name="td'+contador+'">'+fila.lineaProducto+'</td>';
        table += '<td name="td'+contador+'">'+fila.bodega+'</td>';
        table += '<td name="td'+contador+'"><div id="div'+contador+'" style="display:none">'+fila.idProducto+','+fila.serial+'</div><input id="'+contador+'" name="cantidadesProductos" class="form-control verySmall" onchange="validarCantidad(this);" accessKey="'+fila.cantidad+'" disabled="disabled"></td>';
        table += '<td name="td'+contador+'" align="center"><img id="imgProductos'+contador+'" name="imgProductos" src="../imagenes/casilla_vacia.png" style="width: 30px;cursor:pointer"  alt="V" onclick="habilitarCampo(this, '+contador+')"></td>';
        table += '</tr>';
    });
    $('#consultaTabla').html(tabla + table);
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
            
            var control = $('#selBodegaOrigen');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            
            $.each(json.data, function(contador, fila){                
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
            
            $('#selBodegaDestino').html($('#selBodegaOrigen').html());
            $('#selBodegaOrigen').change();
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function cargarTipoDocumento(){
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarTipoDocumento.php',
        type:'POST',
        dataType:"json",
        data:{estado:true
             , codigoTipoNaturaleza: codigoTipoNaturaleza},
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
            
            var control = $('#selTipoDocumento');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarProducto(codigoProducto){
    if(codigoProducto == ''){
        return false;
    }
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/transaccion.consultarProductos.php',
        type:'POST',
        dataType:"json",
        data:{
                codigoProducto:codigoProducto,
                compuesto:'true'
             },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                idProducto = '';
                return false;
            }
            
            if(json.numeroRegistros == 0){
                alerta('No hay productos asociados con este código.');
                idProducto = '';
                $('#txtProducto').val('');
                return false;
            }
            
            $.each(json.data, function(contador, fila){
                idProducto = fila.idProducto;
                $("#txtProducto").val(fila.producto);
            });
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function autoCompletarProducto(){
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarProducto.php',
        select:function(event, ui){
            idProducto = ui.item.idProducto;
            $("#txtCodigo").val(ui.item.codigo);
        }
    });
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
function habilitarCampo(control, id){
    if(control.alt == "V"){
        control.alt = "L";
        control.src = "../imagenes/casilla_marcada.png";
        $('td[name="td'+id+'"]').attr('style', 'background: #E6E6E6');
        $('#'+id).prop('disabled', false).focus();
    }else{
        control.alt = "V";
        control.src = "../imagenes/casilla_vacia.png";
        $('td[name="td'+id+'"]').attr('style', 'background: #FFFFFF');
        $('#'+id).prop('disabled', true).val('');
    }
    if($('img[name="imgProductos"][alt="L"]').length > 0){
        $('#selBodegaDestino').prop('disabled', false);
        $('#imgTrasladar').show();
    }else{
        $('#selBodegaDestino').prop('disabled', true);
        $('#imgTrasladar').hide();
    }
}
function habilitarTodo(){
    if($('#imgProductos').attr('alt') == "V"){
        $('img[name="imgProductos"]').attr('alt','V');
        $('#imgProductos').attr('src', "../imagenes/casilla_marcada.png");
        $('#imgProductos').attr('alt','L');
    }else{
        $('#imgProductos').attr('src', "../imagenes/casilla_vacia.png");
        $('#imgProductos').attr('alt','V');
    }
    $('img[name="imgProductos"]').click();
}
function validarCantidad(elemento){
    if(elemento.value == ''){
        elemento.value = "";
    }
    
    if($.isNumeric(elemento.value) == false || parseInt(elemento.value) > parseInt(elemento.accessKey) || parseInt(elemento.value) <= 0){
        alerta('Debe ingresar una cantidad válida');
        elemento.value = '';
        return false;
    }
}
