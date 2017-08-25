var idProducto = null;
var fechaInicio = null;
var fechaFin = null;
var idTransaccionEstado = null;
var data = null;

$(function(){
    cargarTabla();
    cargarEstados();
    autoCompletarProducto();
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    
    $("#txtCodigoProductoCompuesto").change(function () {
        if ($("#txtCodigoProductoCompuesto").val() != "") {
            consultarProducto($("#txtCodigoProductoCompuesto").val());
        }
    });
    
    $("#txtCodigoProductoCompuesto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtProductoCompuesto").val("");
                idProducto = null;
                break;
        }
    });
    
    $("#txtProductoCompuesto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProductoCompuesto").val("");
                idProducto = null;
                break;
        }
    });
    
    $("#imgNuevo").bind({
        "click":function(){
            validaLogueo();
            abrirVentana(localStorage.modulo + 'vista/frmProductoCompuesto.html');
        }

    });

    $("#imgConsultar").bind({
        "click":function(){            
            obtenerDatosEnvio();
            
            $.ajax({
                async:false,
                url: localStorage.modulo + "controlador/productoCompuesto.consultar.php",
                type:'POST',
                dataType:"json",
                data:data,
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;

                    if (exito == 0){
                            alerta(mensaje);
                            return false;
                    }
                    
                    if(json.numeroRegistros == 0){
                        alerta('No se encontraron registros con los parámetros indicados.');
                        cargarTabla();
                        return false;
                    }
                    
                    crearListado(json);  

                    localStorage.numeroRegistros = json.numeroRegistros;//Almaceno el número de registros para saber cuantas debo ocultar
                    obtenerPermisosFormulario(localStorage.formulario);//Habilitar las acciones a las que tenga permiso

                },error: function(xhr, opciones, error){
                         alerta(error);
                }
            });
        }
    });
	
    $("#imgLimpiar").bind({
        "click":function(){
            limpiarVariables();
            limpiarControlesFormulario(document.fbeFormulario);
            cargarTabla();
        }
    });
});

function limpiarVariables(){
    idProducto = null;
    fechaInicio = null;
    fechaFin = null;
    idTransaccionEstado = null;
    data = null;

}

function asignarDatosEnvio(){
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    idTransaccionEstado = $("#selEstado").val();
}

function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'idProducto=' + idProducto + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin + '&idTransaccionEstado=' + idTransaccionEstado;
}
function cargarTabla(){
    var tabla = '<tr>';
    tabla += '<th><p>#</p></th>';
    tabla += '<th><p>Tipo documento</p></th>';
    tabla += '<th><p>Fecha</p></th>';
    tabla += '<th><p>Código</p></th>';
    tabla += '<th><p>Producto</p></th>';
    tabla += '<th><p>Unidad Medida</p></th>';
    tabla += '<th><p>Linea Producto</p></th>';
    tabla += '<th><p>Cantidad</p></th>';
    tabla += '<th><p>Saldo</p></th>';
    tabla += '<th><p>Estado</p></th>';
    tabla += '<th colspan="2"><p>Acción</p></th>';
    tabla += '</tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>'
    $('#consultaTabla').html(tabla);
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '<thead>';
    tabla += '<tr id="trEstatico">';
    tabla += '<th><p>#</p></th>';
    tabla += '<th><p>Tipo documento</p></th>';
    tabla += '<th><p>Fecha</p></th>';
    tabla += '<th><p>Código</p></th>';
    tabla += '<th><p>Producto</p></th>';
    tabla += '<th><p>Unidad Medida</p></th>';
    tabla += '<th><p>Linea Producto</p></th>';
    tabla += '<th><p>Cantidad</p></th>';
    tabla += '<th><p>Saldo</p></th>';
    tabla += '<th><p>Estado</p></th>';
    tabla += '<th colspan="2"><p>Acción</p></th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';
   $.each(json.data,function(contador, fila){
        tabla += '<tr>';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.tipoDocumento + '</td>';
        tabla += '<td>' + fila.fecha + '</td>';
        tabla += '<td>' + fila.codigo + '</td>';
        tabla += '<td>' + fila.producto + '</td>';
        tabla += '<td>' + fila.unidadMedida + '</td>';
        tabla += '<td>' + fila.lineaProducto + '</td>';
        tabla += '<td align="right">' + fila.cantidad + '</td>';
        tabla += '<td align="right">' + fila.saldoCantidadProducto + '</td>';
        tabla += '<td align="center">'+fila.transaccionEstado+'</td>';        
        tabla += '<td align="center"><span class="fa fa-eye imagenesTabla" id="imgVerProductos' + contador + '" title="Ver Productos" class="imagenesTabla" onclick="visualizarProductos('+fila.idTransaccion +')""></span></td>';
        if(fila.idTransaccionEstado == 1){
            tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgAnular' + contador + '" title="Anular Inventario" class="imagenesTabla" onclick="anular('+fila.idTransaccion +')"></span></td>';
        }else{
            tabla += '<td></td>';
        }
        tabla += '</tr>';
    });
    tabla += '</tbody>';
    tabla += '</table>';
    $('#consultaTabla').html(tabla);
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
            
            var control = $('#selEstado');
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
function consultarProducto(codigoProducto) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/productoCompuesto.consultarProducto.php',
        type: 'POST',
        dataType: "json",
        data: {codigoProducto: codigoProducto},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                $("#txtProductoCompuesto").val("");
                idProducto = null;
                return false;
            }

            $.each(json.data, function (contador, fila) {
                idProducto = fila.idProducto;
                $("#txtProductoCompuesto").val(fila.producto);
            });
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function autoCompletarProducto() {
    $("#txtProductoCompuesto").autocomplete({
        source: localStorage.modulo + 'ajax/productoCompuesto.autoCompletar.php',
        select: function (event, ui) {
            idProducto = ui.item.idProducto;
            $("#txtCodigoProductoCompuesto").val(ui.item.codigo);
        }
    });
}
function visualizarProductos(idTransaccion){
    var html = '<table class="table table-bordered table-striped consultaTabla" style="margin-top:10px;">';
    html += '<tr>';
    html += '<th colspan="5" style="text-align:center;"><b>LISTADO DE PRODUCTOS</b></th>';
    html += '</tr>';
    html += '<tr>';
    html += '<th style="width:50px">#</th>';
    html += '<th style="width:50px">Código</th>'; 
    html += '<th style="width:350px">Producto</th>'; 
    html += '<th style="width:150px">Unidad de medida</th>'; 
    html += '<th style="width:50px">Cantidad</th>';
    html += '</tr>';
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/productoCompuesto.consultarProductosInventariados.php',
        data:{idTransaccion:idTransaccion},
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
                return false;
            }

            $.each(json.data, function(contador, fila){
                html += '<tr>';
                html += '<td align="center">' + (contador + 1) + '</td>';
                html += '<td>' + fila.codigo + '</td>';
                html += '<td>' + fila.producto + '</td>';
                html += '<td>' + fila.unidadMedida + '</td>';
                html += '<td align="center">' + fila.cantidad + '</td>';
                html += '</tr>';
            });

        },error: function(xhr, opciones, error){
            alerta(error);
            return false;
        }
    });
    html += '</table>';
    bootbox.alert(html);
    $(".modal-dialog").css("width", "800px");
}
function anular(idTransaccion){
    bootbox.confirm("Está seguro(a) de anular el movimiento?", function(result) {
        if(result==true){
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/productoCompuesto.anular.php',
                data:{idTransaccion:idTransaccion},
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