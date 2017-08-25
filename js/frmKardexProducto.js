//Javascript Document
var idProducto = null;
var fechaInicio = null;
var fechaFin = null;
var idBodega = null;

var data = null;

$(function(){
    cargarListado();
    
    cargarBodegas();
    autoCompletarProducto();
    
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");    
    
    $("input.form-control.multiselect-search").attr("accesskey", "V");
    
    $("#txtCodigoProducto").change(function(){
        if($("#txtCodigoProducto").val() != ""){
            consultarProducto($("#txtCodigoProducto").val());
        }
    });
    
    $("#txtCodigoProducto").keypress(function(e){
        switch(e.keyCode){
            case 13:
                if($("#txtCodigoProducto").val() != ""){
                    consultarProducto($("#txtCodigoProducto").val());
                }
            break;
            case 08 || 46:
                $("#txtProducto").val("");
                $("#spnUnidadMedida").html("");
                $("#txtCantidad").val("");
                idProducto = null;
            break;
        }
    });
    
    $("#txtProducto").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtCodigoProducto").val("");
                $("#spnUnidadMedida").html("");
                $("#txtCantidad").val("");
                idProducto = null;
            break;
        }
    });
    
    $("#imgLimpiar").click(function(){
        limpiarControlesFormulario(document.frmFormulario); 
        cargarListado();
        limpiarVariables();
        $("#divTotales").html("");
        $("#spnUnidadMedida").html("");
        
        $('#selBodega').val("");
        $('#selBodega').multiselect('destroy');
        $('#selBodega').multiselect({
            maxHeight: 600
            ,nonSelectedText: '--Seleccione--'	
            ,enableFiltering: true
            ,filterPlaceholder: 'Buscar'
            ,numberDisplayed: 1
            ,enableCaseInsensitiveFiltering: true
            ,buttonWidth: '220px'
        });
    });
    
    $("#imgConsultar").click(function(){
       if(validarVacios(document.frmFormulario) == false)
                return false; 
            
        obtenerDatosEnvio();
        
        $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/transaccionProducto.consultarKardexProducto.php',
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
                    alerta("No se encontraron registros con los parámetros establecidos");
                    cargarListado();
                    return false;
                }
                
                crearListado(json);
                
            },error: function(xhr, opciones, error){
                alerta(error);
            }
        });
    });
    
});

function cargarListado(){
    $("#consultaTabla").html("");
    var tabla = '';
    tabla += '<table id="tblKardexProducto" name="tblKardexProducto" class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th colspan="11"><b>Bodega</b></th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Nro. Docum</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Valor Unit Salid Impue</th>';
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
    tabla += '</tr>';
    tabla += '</table>';
    $("#consultaTabla").html(tabla);	
    $("#divTotales").html("");
}
function cargarBodegas(){   
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
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
                var control = $("#selBodega");
                control.empty();
                return false;
            }
            var control = $("#selBodega");
            control.empty();
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
            $('#selBodega').multiselect({
                maxHeight: 600
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
                ,buttonWidth: '220px'
            });
        },error: function(xhr, opciones, error){
            alerta(error);
        }
    });
}
function consultarProducto(codigoProducto){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/transaccion.consultarProductos.php',
        type:'POST',
        dataType:"json",
        data:{
                codigoProducto:codigoProducto
                , compuesto: false
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                $("#txtProducto").val("");
                $("#spnUnidadMedida").html("");
                idProducto = null;
                return false;
            }
            
            $.each(json.data, function(contador, fila){
                idProducto = fila.idProducto;
                $("#txtProducto").val(fila.producto);
                $("#spnUnidadMedida").html(fila.unidadMedida);
            });
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function autoCompletarProducto(){
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarProducto.php?idProductos=&compuesto=false',
        select:function(event, ui){
            idProducto = ui.item.idProducto;
            $("#txtCodigoProducto").val(ui.item.codigo);
            $("#spnUnidadMedida").html(ui.item.unidadMedida);
        }
    });
}
function asignarDatosEnvio(){
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    idBodega = $("#selBodega").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = "idProducto=" + idProducto + "&fechaInicio=" + fechaInicio + "&fechaFin=" + fechaFin + "&idBodega=" + idBodega;
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '';
    var idBodegaEvaluar = null;
    var bodegaEvaluar = null;
    var contador = 0;
    
    var cantidadTotalEntrada = 0;
    var cantidadTotalSalida = 0;
    
    var cantidadEntrada = 0;
    var cantidadSalida = 0;
    
    var arrTotalBodega = new Array();
    
    $.each(json.data,function(contador,fila){
        if(idBodegaEvaluar != fila.idBodega){
            
            if(idBodegaEvaluar != "null" && idBodegaEvaluar != "" && idBodegaEvaluar != null){
                
                var objTotal = new Object();
                objTotal.cantidadEntrada = cantidadEntrada;
                objTotal.cantidadSalida = cantidadSalida;
                objTotal.bodega = bodegaEvaluar;
                arrTotalBodega.push(objTotal);
                
                cantidadEntrada = 0;
                cantidadSalida = 0;
                
                tabla += '</table>'; 
            }
            tabla += '</br>'; 
            tabla += '<table class="table table-bordered table-striped consultaTabla">';
            tabla += '<tr>';
            tabla += '<th colspan="11"><b>Bodega : </b> ' + fila.bodega + '</th>';
            tabla += '</tr>';
            tabla += '<tr>';
            tabla += '<th>#</th>';
            tabla += '<th>Fecha</th>';
            tabla += '<th>Tipo Documento</th>';
            tabla += '<th>Nro. Docum</th>';
            tabla += '<th>Cantidad</th>';
            tabla += '<th>Serial</th>';
            tabla += '<th>Nota</th>';
            tabla += '<th>Valor Unit Salid Impue</th>';
            tabla += '</tr>';            
            idBodegaEvaluar = fila.idBodega;
            bodegaEvaluar = fila.bodega;
            contador = 1;
        }
        tabla += '<tr>';
        tabla += "<td style='text-align:center'>" + contador++; + "</td>";
        tabla += "<td style='text-align:center'>" + fila.fecha + "</td>";
        tabla += "<td>" + fila.tipoDocumento + "</td>";
        tabla += "<td style='text-align:right'>" + fila.numeroTipoDocumento + "</td>";
        tabla += "<td style='text-align:right'>" + fila.cantidad + "</td>";
        if(fila.serial != "" && fila.serial != null && fila.serial != "null"){
            tabla += "<td style='text-align:right'>" + fila.serial + "</td>";
        }else{
            tabla += "<td>&nbsp;</td>";
        }
        
        if(fila.nota != "" && fila.nota != null && fila.nota != "null"){
            tabla += "<td>" + fila.nota + "</td>";
        }else{
            tabla += "<td>&nbsp;</td>";
        }
        tabla += "<td style='text-align:right'>" + agregarSeparadorMil(parseInt(fila.valorUnitaSalidConImpue).toString()) + "</td>";
        tabla += '</tr>';
        
        if(fila.signo == 1 || fila.signo == "1"){
            cantidadEntrada +=  parseFloat(fila.cantidad);
            cantidadTotalEntrada +=  parseFloat(fila.cantidad);
        }else{
            if(fila.signo == -1 || fila.signo == "-1"){
                cantidadSalida +=  parseFloat(fila.cantidad);
                cantidadTotalSalida +=  parseFloat(fila.cantidad);
            }
        }
    });
    
    if(cantidadEntrada != 0 || cantidadSalida != 0){
        var objTotal = new Object();
        objTotal.cantidadEntrada = cantidadEntrada;
        objTotal.cantidadSalida = cantidadSalida;
        objTotal.bodega = bodegaEvaluar;
        arrTotalBodega.push(objTotal);
    }
    
    tabla += '</table>';
    tabla += '<br>';
    
    var tablaTotal = "";
    tablaTotal += '<table class="table table-bordered table-striped consultaTabla">';
    tablaTotal += '<tr>';
    tablaTotal += '<th colspan="3">Bodega</th>';
    tablaTotal += '<th colspan="4">Total Entradas</th>';
    tablaTotal += '<th colspan="4">Total Salida</th>';
    tablaTotal += '</tr>';
    
    $.each(arrTotalBodega,function(contador,fila){
        tablaTotal += '<tr>';
        tablaTotal += '<td colspan="3">' + fila.bodega  + '</td>';
        tablaTotal += '<td colspan="4" style="text-align:right">' + parseFloat(fila.cantidadEntrada).toFixed(2)  + '</td>';
        tablaTotal += '<td colspan="4" style="text-align:right">' + parseFloat(fila.cantidadSalida).toFixed(2)  + '</td>';
        tablaTotal += '</tr>';
    });
    
    tablaTotal += '<tr>';
    tablaTotal += '<td colspan="3"><b>Total</b></td>';
    tablaTotal += '<td colspan="4" style="text-align:right">' + parseFloat(cantidadTotalEntrada).toFixed(2)  + '</td>';
    tablaTotal += '<td colspan="4" style="text-align:right">' + parseFloat(cantidadTotalSalida).toFixed(2)  + '</td>';
    tablaTotal += '</tr>';
    tablaTotal += '</table>';
    
    $("#divTotales").html(tablaTotal);
    
    $("#consultaTabla").html(tabla);		
    $("#consultaTabla").css("border","0px");
}
function limpiarVariables(){
    idProducto = null;
    fechaInicio = null;
    fechaFin = null;
    idBodega = null;

    data = null;
}