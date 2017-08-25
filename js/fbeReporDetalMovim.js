var fechaInicio = null;
var fechaFin = null;

var data = null;
$(function(){
    $("#imgExportar").hide();
    cargarListado();
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    
    $("#imgConsultar").click(function(){
        
        obtenerDatosEnvio();
        
        if($("#txtFechaInicio").val() == ""){
           alerta("Por favor indique la fecha inicial.");
           return false;
        }
       
        $.ajax({
            url: localStorage.modulo + 'controlador/reporDetalMovim.consultar.php',
            type:'POST',
            dataType:"json",
            data:data,
            success: function(json){
                var mensaje = json.mensaje;
                var exito = json.exito;

                if(exito == 0){
                    $("#imgExportar").hide();
                    alerta (mensaje);
                    return false;
                }

                if(json.numeroRegistros == 0){
                    alerta("No se encontraron registros.");
                    cargarListado();
                    return false;
                }

                crearListado(json);
                $("#imgExportar").show();

            }, error: function(xhr, opciones, error){
                alert(error);
                return false;
            }
        });
    });
    
    $("#imgLimpiar").click(function(){
        $("#imgExportar").hide();
       limpiarVariables();
       cargarListado();
       limpiarControlesFormulario(document.fbeFormulario);
    });
});
function asignarDatosEnvio(){
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin;
}
function cargarListado(){
    $("#consultaTabla").html("");
    var tabla = '<table class="table table-bordered consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Tercero</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Naturaleza</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Valor</th>';
    tabla += '</tr>';
    tabla += '<tr>';
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
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '<table class="table table-bordered consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Tercero</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Naturaleza</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Valor</th>';
    tabla += '</tr>';
    
    $.each(json.data.detalle, function(contador, fila){
        tabla += '<tr>';
        tabla += "<td align='center'>" + (contador + 1) + "</td>";	
        
        if(fila.tercero != null && fila.tercero != "null" && fila.tercero != ""){
            tabla += "<td>" + fila.tercero + "</td>";
        }else{
            tabla += "<td>&nbsp;</td>";
        }
        
        	
        tabla += "<td>" + fila.tipoDocumento + "</td>";	
        tabla += "<td>" + fila.naturaleza + "</td>";
        
        if(fila.fecha != "" && fila.fecha != null && fila.fecha != "null"){
            tabla += "<td align='center'>" + fila.fecha + "</td>";
        }else{
            tabla += "<td>&nbsp;</td>";	
        }
        
        if(fila.nota != "" && fila.nota != null && fila.nota != "null"){
            tabla += "<td>" + fila.nota + "</td>";
        }else{
            tabla += "<td>&nbsp;</td>";	
        }
        
        tabla += "<td align='right'>" + agregarSeparadorMil(String(fila.valor)) + "</td>";
        tabla += '</tr>';
    });
    
    tabla += '<tr>';
    tabla += '<td colspan="6" align="right"><b>Total</b></td>';
    tabla += '<td align="right">' + agregarSeparadorMil(String(json.data.total)) + '</td>';
    tabla += '</tr>';
    
    tabla += '</table>'; 
    $("#consultaTabla").html(tabla);		
}
function limpiarVariables(){
    fechaInicio = null;
    fechaFin = null;
    data = null;
}