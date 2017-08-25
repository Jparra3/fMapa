var fechaInicio = null;
var fechaFin = null;
var numeroTipoDocumento = null;
var estado = null;
var idTiposDocumentos = null;

var data = null;
$(function(){
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    obtenerSelectEstadoSeleccione($("#selEstado"));
    cargarListado();
    cargarTipoDocumento();
    
    $("#imgNuevo").click(function(){
        abrirVentana(localStorage.modulo + 'vista/frmMovimientoContable.html', '');	
    });
    
    $("#imgConsultar").click(function(){
        
        var numeroControlesVacios = validarVaciosConsultar(document.fbeFormulario);
			
        if(numeroControlesVacios == document.fbeFormulario.length){
            alerta("Debe ingresar al menos un parámetro de búsqueda.");
            return false;
        }
        
        obtenerDatosEnvio();
        $.ajax({
            url:localStorage.modulo + 'controlador/movimientoContable.consultar.php',
            type:'POST',
            dataType:"json",
            data:data,
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    alerta(error);
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
        
        $('#selTipoDocumento').val("");
        $('#selTipoDocumento').multiselect('destroy');
        $('#selTipoDocumento').multiselect({
            maxHeight: 600
            ,nonSelectedText: '--Seleccione--'	
            ,enableFiltering: true
            ,filterPlaceholder: 'Buscar'
            ,enableCaseInsensitiveFiltering: true
        });
    });	
});
function asignarValoresEnvio(){
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    numeroTipoDocumento = $("#txtNoTipoDocumento").val();
    estado = $("#selEstado").val();
    idTiposDocumentos = $("#selTipoDocumento").val();
}
function obtenerDatosEnvio(){
    asignarValoresEnvio();
    data = 'fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin + '&numeroTipoDocumento=' + numeroTipoDocumento + '&estado=' + estado + '&idTipoDocumento=' + idTiposDocumentos;
}
function cargarListado(){
	$("#consultaTabla").html("");
	var tabla = '';
	tabla += '<tr>';
	tabla += '<th>#</th>';
        tabla += '<th>Tipo Documento</th>';
	tabla += '<th>No. Tipo Documento</th>';
	tabla += '<th>Fecha</th>';
	tabla += '<th>Nota</th>';
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
    var tabla = '<table class="table table-bordered consultaTabla" >';
    tabla += '<thead>';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>#</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>No. Tipo Documento</th>';
    tabla += '<th>Fecha</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';
    $.each(json.data, function(contador,fila){
        tabla += '<tr>';
        tabla += '<td align="center">'+(contador + 1)+'</td>';
        tabla += '<td>'+fila.tipoDocumento+'</td>';
        tabla += '<td align="right">'+fila.numeroTipoDocumento+'</td>';
        tabla += '<td align="center">'+fila.fecha+'</td>';
        
        if(fila.nota != "" && fila.nota != "null" && fila.nota != null){
            tabla += '<td>'+fila.nota+'</td>';
        }else{
            tabla += '<td>&nbsp;</td>';
        }
        
        if(fila.estado == true)
            tabla += '<td>ACTIVO</td>';
        else
            tabla += '<td>INACTIVO</td>';
        
        
        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgModificar' + contador + '" title="Editar" class="imagenesTabla" onclick="abrirVentana(' + "'" + localStorage.modulo + 'vista/frmMovimientoContable.html' + "'" + ',' + fila.idMovimientoContable +')"></span></td>';
        tabla += '<td align="center"><span class="fa fa-minus imagenesTabla" id="imgInactivar' + contador + '" title="Inactivar" class="imagenesTabla" onclick="inactivarMovimientoContable('+ fila.idMovimientoContable +')"></span></td>';
        tabla += '</tr>';
    });
    tabla += '</tbody>';
    tabla += '</table>';
    $("#consultaTabla").html(tabla);
    $("#consultaTabla").tablesorter();
}

function limpiarVariables(){
    fechaInicio = null;
    fechaFin = null;
    numeroTipoDocumento = null;
    estado = null;
    idTiposDocumentos = null;

    data = null;
}

function inactivarMovimientoContable(idMovimientoContable){
    bootbox.confirm("Está seguro(a) de inactivar el movimiento contable?", function(result) {
        if(result==true){
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/movimientoContable.inactivar.php',
                data:{idMovimientoContable:idMovimientoContable},
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
function cargarTipoDocumento(){   
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/tipoDocumento.consultar.php',
        data:{estado:true},
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
                var control = $("#selTipoDocumento");
                control.empty();
                return false;
            }
            var control = $("#selTipoDocumento");
            control.empty();
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
            });
            $('#selTipoDocumento').multiselect({
                maxHeight: 600
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
        },error: function(xhr, opciones, error){
            alerta(error);
        }
    });		
}