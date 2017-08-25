var codigoTipoNaturaleza = "IN-CO";
var fechaInicio = null;
var fechaFin = null;
var fechaVencimientoInicio = null;
var fechaVencimientoFin = null;
var idConcepto = null;
var idTercero = null;
var idOficina = null;
var idTransaccionEstado = null;
var documentoExterno = null;

var data = null;
$(function(){
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    crearCalendario("txtFechaVencimientoInicio");
    crearCalendario("txtFechaVencimientoFin");
    cargarTipoDocumento();
    autoCompletarTercero();
    cargarOficinas();
    cargarEstados();
    cargarListado();
    
    $("#imgNuevo").click(function(){
        abrirVentana(localStorage.modulo + 'vista/frmTransaccion.html', '');
    });
    $("#imgConsultar").click(function(){
        obtenerDatosEnvio();
        $.ajax({
            url:localStorage.modulo + 'controlador/transaccion.consultar.php',
            type:'POST',
            dataType:"json",
            data:data,
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    alerta(mensaje);
                    return false;
                }

                if(json.numeroRegistros == 0){
                    alerta("No se encontraron registros con los parámetros indicados.");
                    cargarListado();
                    return false;	
                }

                crearListado(json);

                localStorage.numeroRegistros = json.numeroRegistros;//Almaceno el número de registros para saber cuantas debo ocultar
                obtenerPermisosFormulario(localStorage.formulario);//Habilitar las acciones a las que tenga permiso
                    
            },error: function(xhr, opciones, error){
                alerta(error);
                return false;
            }
        });
    });
    $("#imgLimpiar").click(function(){
        cargarListado();
        limpiarControlesFormulario(document.fbeFormulario);
        limpiarVariables(); 
    });
});
function asignarDatosEnvio(){
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    fechaVencimientoInicio = $("#txtFechaVencimientoInicio").val();
    fechaVencimientoFin = $("#txtFechaVencimientoFin").val();
    idConcepto = $("#selTipoDocumento").val();
    idOficina = $("#selOficina").val();
    idTransaccionEstado = $("#selTransaccionEstado").val();
    documentoExterno = $("#txtDocumentoExterno").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin + '&fechaVencimientoInicio=' + fechaVencimientoInicio + '&fechaVencimientoFin=' + fechaVencimientoFin
            + '&idConcepto=' + idConcepto + '&idOficina=' + idOficina + '&idTercero=' + idTercero + '&idTransaccionEstado=' + idTransaccionEstado
            + '&documentoExterno=' + documentoExterno + '&codigoTipoNaturaleza=' + codigoTipoNaturaleza;
}
function cargarTipoDocumento(){
    $.ajax({
        url: localStorage.modulo + 'controlador/concepto.consultar.php',
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
                control.append('<option value="' + fila.idConcepto + '">' + fila.tipoDocumento + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function cargarOficinas(){
    $.ajax({
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
function cargarListado(){
    $("#consultaTabla").html("");
    var tabla = '';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Tercero</th>';
    tabla += '<th>Oficina</th>';
    tabla += '<th># factura</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Fecha Vencimiento</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Saldo</th>';
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
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    $("#consultaTabla").html(tabla);		
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<thead>';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>#</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Tercero</th>';
    tabla += '<th>Oficina</th>';
    tabla += '<th># factura</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Fecha Vencimiento</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Saldo</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';
    $.each(json.data, function(contador,fila){
        tabla += '<tr>';
        tabla += '<td align="center">'+(contador + 1)+'</td>';
        tabla += '<td>'+fila.numeroTipoDocumento + " - " + fila.tipoDocumento+'</td>';
        tabla += '<td>'+fila.tercero+'</td>';
        tabla += '<td>'+fila.oficina+'</td>';
        if(fila.documentoExterno != "" && fila.documentoExterno != null && fila.documentoExterno != "null"){
            tabla += '<td>'+fila.documentoExterno+'</td>';
        }else{
            tabla += '<td></td>';
        }
        if(fila.nota != "" && fila.nota != null && fila.nota != "null")
            tabla += '<td>'+fila.nota+'</td>';
        else
            tabla += '<td></td>';
        
        tabla += '<td align="center">'+fila.fecha+'</td>';
        tabla += '<td align="center">'+fila.fechaVencimiento+'</td>';
        tabla += '<td align="right">'+ agregarSeparadorMil(fila.valor.toString()) +'</td>';
        tabla += '<td align="right">'+ agregarSeparadorMil(fila.saldo.toString())+ '</td>';
        tabla += '<td align="center">'+fila.transaccionEstado+'</td>';        
        tabla += '<td align="center"><span class="fa fa-eye imagenesTabla" id="imgVerProductos' + contador + '" title="Ver Productos" class="imagenesTabla" onclick="visualizarProductos('+fila.idTransaccion +')""></span></td>';
        if(fila.idTransaccionEstado == 1){
            tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgAnular' + contador + '" title="Anular Inventario" class="imagenesTabla" onclick="anularInventario('+fila.idTransaccion +')"></span></td>';
        }else{
            tabla += '<td></td>';
        }
        tabla += '</tr>';
    });
    tabla += '</tbody>';
    tabla += '</table>';
    $("#consultaTabla").html(tabla);
    $("#consultaTabla").tablesorter();
}
function autoCompletarTercero(){
    $("#txtTercero").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarTercero.php',
        select:function(event, ui){
            idTercero = ui.item.idTercero;
        }
    });
}
function limpiarVariables(){
    fechaInicio = null;
    fechaFin = null;
    fechaVencimientoInicio = null;
    fechaVencimientoFin = null;
    idConcepto = null;
    idTercero = null;
    idOficina = null;
    idTransaccionEstado = null;

    data = null;
}
function visualizarProductos(idTransaccion){
    var html = '<table class="table table-bordered table-striped consultaTabla" style="margin-top:10px;">';
    html += '<tr>';
    html += '<th colspan="10" style="text-align:center;"><b>LISTADO DE PRODUCTOS</b></th>';
    html += '</tr>';
    html += '<tr>';
    html += '<th>#</th>';
    html += '<th style="width:150px">Producto</th>'; 
    html += '<th style="width:70px">Unidad Medida</th>'; 
    html += '<th>Cantidad</th>';
    html += '<th>Serial</th>';
    html += '<th>Serial Interno</th>';
    html += '<th style="width:100px">Valor compra</th>';
    html += '<th style="width:100px">Valor venta</th>';
    html += '<th style="width:150px">Bodega</th>';
    html += '<th style="width:200px">Nota</th>';
    html += '</tr>';
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/transaccionProducto.consultar.php',
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
                html += '<td>' + fila.producto + '</td>';
                html += '<td>' + fila.unidadMedida + '</td>';
                html += '<td align="center">' + fila.cantidad + '</td>';
                
                if(fila.serial != null && fila.serial != "null" && fila.serial != ""){
                    html += '<td>' + fila.serial + '</td>';
                }else{
                    html += "<td>&nbsp;</td>";
                }
                
                if(fila.serialInterno != null && fila.serialInterno != "null" && fila.serialInterno != ""){
                    html += '<td>' + fila.serialInterno + '</td>';
                }else{
                    html += "<td>&nbsp;</td>";
                }
                
                
                html += '<td align="right">' + agregarSeparadorMil(fila.valorEntradaMostrar) + '</td>';
                html += '<td align="right">' + agregarSeparadorMil(fila.valorSalidaMostrar) + '</td>';
                html += '<td>' + fila.bodega + '</td>';
                
                if(fila.nota != "" && fila.nota != null && fila.nota != "null"){
                    html += '<td>' + fila.nota + '</td>';
                }else{
                    html += "<td>&nbsp;</td>";
                }
                
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
function anularInventario(idTransaccion){
    bootbox.confirm("Está seguro(a) de anular el inventario?", function(result) {
        if(result==true){
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/transaccion.inactivar.php',
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