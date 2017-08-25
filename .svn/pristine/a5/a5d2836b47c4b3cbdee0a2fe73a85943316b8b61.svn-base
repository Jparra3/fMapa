var idUsuario = null;
var fecha = null;

var data = null;
$(function(){
    cargarCajeros();
    crearCalendario("txtFecha");
    cargarListado();
    
    $("#imgConsultar").click(function(){
        if(validarVacios(document.fbeFormulario) == false)
            return false;
        
        obtenerDatosEnvio();
        $.ajax({
            url: localStorage.modulo + 'controlador/estadoCaja.consultar.php',
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
        limpiarControlesFormulario(document.fbeFormulario);
        cargarListado();
        limpiarVariables();
    });
});
function cargarCajeros(){
    $.ajax({
        url: localStorage.modulo + 'controlador/estadoCaja.consultarCajeros.php',
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
            
            var control = $('#selCajero');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idUsuario + '">' + fila.persona + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function asignarDatosEnvio(){
    idUsuario = $("#selCajero").val();
    fecha = $("#txtFecha").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'idUsuario=' + idUsuario + '&fecha='+ fecha;
}
function cargarListado(){
    $("#consultaTabla").html("");
    var tabla = '';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Forma pago</th>';
    tabla += '<th>Valor</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    $("#consultaTabla").html(tabla);		
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Forma pago</th>';
    tabla += '<th>Valor</th>';
    tabla += '</tr>';
    $.each(json.data.detalle, function(contador, fila){
        tabla += '<tr>';
        tabla += "<td align='center'>" + (contador + 1) + "</td>";
        tabla += "<td>" + fila.formaPago + "</td>";
        tabla += "<td align='right'>" + agregarSeparadorMil(String(fila.valor)) + "</td>";		
        tabla += '</tr>'; 
    });
    tabla += '<tr>';
    tabla += "<td align='right' colspan='2'><b>Total</b></td>";
    tabla += "<td align='right'>" + agregarSeparadorMil(String(json.data.total)) + "</td>";
    tabla += '</tr>'; 
    $("#consultaTabla").html(tabla);		
}
function limpiarVariables(){
    idUsuario = null;
    fecha = null;

    data = null;
}