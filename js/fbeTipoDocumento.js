var tipoDocumento = null;
var idNaturaleza = null;
var codigo = null;
var estado = null;

var data = null;
$(function(){
    cargarNaturaleza();
    obtenerSelectEstadoSeleccione($("#selEstado"));
    cargarListado();
    
    $("#imgNuevo").click(function(){
        abrirVentana(localStorage.modulo + 'vista/frmTipoDocumento.html', '');	
    });
    
    $("#imgConsultar").click(function(){
        obtenerDatosEnvio();
        $.ajax({
            url:localStorage.modulo + 'controlador/tipoDocumento.consultar.php',
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
    
});
function cargarListado(){
	$("#consultaTabla").html("");
	var tabla = '';
	tabla += '<tr>';
        tabla += '<th>#</th>';
        tabla += '<th>Código</th>';
	tabla += '<th>Tipo Documento</th>';
        tabla += '<th>Naturaleza</th>';
        tabla += '<th>Oficina</th>';
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
	tabla += '</tr>';
	$("#consultaTabla").html(tabla);		
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<thead>';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Naturaleza</th>';
    tabla += '<th>Oficina</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';
    $.each(json.data, function(contador,fila){
        tabla += '<tr>';
        tabla += '<td align="center">'+(contador + 1)+'</td>';
        tabla += '<td align="center">'+fila.codigo+'</td>';
        tabla += '<td>'+fila.tipoDocumento+'</td>';
        tabla += '<td>'+fila.naturaleza+'</td>';
        tabla += '<td>'+fila.oficina+'</td>';
        
        if(fila.estado == true)
            tabla += '<td align="center">ACTIVO</td>';
        else
            tabla += '<td align="center">INACTIVO</td>';
        
        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgModificar' + contador + '" title="Editar" class="imagenesTabla" onclick="abrirVentana(' + "'" + localStorage.modulo + 'vista/frmTipoDocumento.html' + "'" + ',' + fila.idTipoDocumento +')"></span></td>';
        tabla += '<td align="center"><span class="fa fa-minus imagenesTabla" id="imgInactivar' + contador + '" title="Inactivar" class="imagenesTabla" onclick="inactivarTipoDocumento('+fila.idTipoDocumento +')"></span></td>';
        tabla += '</tr>';
    });
    tabla += '</tbody>';
    tabla += '</table>';
    $("#consultaTabla").html(tabla);
    $("#consultaTabla").tablesorter();
    
    $("#imgLimpiar").click(function(){
        cargarListado();
        limpiarControlesFormulario(document.fbeFormulario);
        limpiarVariables();
    });
}
function cargarNaturaleza(){
    $.ajax({
        url: localStorage.modulo + 'controlador/naturaleza.consultar.php',
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
            
            var control = $('#selNaturaleza');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idNaturaleza + '">' + fila.naturaleza + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function inactivarTipoDocumento(idTipoDocumento){
    bootbox.confirm("Está seguro(a) de inactivar el tipo de documento?", function(result) {
        if(result==true){
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/tipoDocumento.inactivar.php',
                data:{idTipoDocumento:idTipoDocumento},
                dataType:"json",
                type:'POST',
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    
                    if(exito == 0){
                        alerta(mensaje);
                        return false;
                    }
                    
                    alerta(json.mensaje);
                    $("#imgConsultar").click();
                    
                },error: function(xhr, opciones, error){
                    alerta(error);
                    return false;
                }
            });		
        }  
    }); 
}
function asignarDatosEnvio(){
    tipoDocumento = $("#txtTipoDocumento").val();
    idNaturaleza = $("#selNaturaleza").val();
    codigo = $("#txtCodigo").val();
    estado = $("#selEstado").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'tipoDocumento=' + tipoDocumento + '&idNaturaleza=' + idNaturaleza + '&codigo=' + codigo + '&estado=' + estado;
}
function limpiarVariables(){
    tipoDocumento = null;
    idNaturaleza = null;
    codigo = null;
    estado = null;

    data = null;
}