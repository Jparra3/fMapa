var valorRecibido = variableUrl();
var idUsuario = valorRecibido[1];
var dataTipoDocumento = new Array();
var idTipoDocumentoSeleccionados = new Array();
$(function(){
    cargarListado();
    
    
    if(idUsuario != null && idUsuario != "null" && idUsuario != ""){
        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/usuarioTipoDocumento.consultar.php',
            type:'POST',
            dataType:"json",
            data:{idUsuario:idUsuario},
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
                
                var idUsuario;
                var usuario;
                $.each(json.data, function(contador, fila){
                    var obj = new Object();
                    idUsuario = fila.idUsuario;
                    usuario = fila.usuario;
                    obj.idUsuario = fila.idUsuario;
                    obj.usuario = fila.usuario;
                    obj.idTipoDocumento = fila.idTipoDocumento;
                    obj.tipoDocumento = fila.tipoDocumento;
                    dataTipoDocumento.push(obj);
                    idTipoDocumentoSeleccionados.push(fila.idTipoDocumento);
                });
                
                crearListado();
                $('#selUsuario').append('<option value="' + idUsuario + '">' + usuario + '</option>');
                $("#selUsuario").attr("disabled", true);
                
            }, error: function(xhr, opciones, error){
                alert(error);
                return false;
            }
        });
    }else{
        cargarUsuarios();
    }
    
    cargarTipoDocumento();
    
    $("#selUsuario").change(function(){
        cargarListado();
        dataTipoDocumento = new Array();
        $("#selTipoDocumento").val("");
        idTipoDocumentoSeleccionados = new Array();
        cargarTipoDocumento();
    });
    
    $("#imgGuardar").click(function(){
        $.ajax({
            url: localStorage.modulo + 'controlador/usuarioTipoDocumento.adicionar.php',
            type:'POST',
            dataType:"json",
            data:{
                idUsuario: $("#selUsuario").val()
                , dataTipoDocumento: dataTipoDocumento
            },
            success: function(json){
                var mensaje = json.mensaje;
                var exito = json.exito;

                if(exito == 0){
                    alerta (mensaje);
                    return false;
                }
                
                alerta(mensaje, true);
                
            }, error: function(xhr, opciones, error){
                alert(error);
                return false;
            }
        });
    });
    
    $("#imgNuevo").click(function(){
        
        if(validarVacios(document.frmUsuarioTipoDocumento) == false)
            return false;
        
        var obj = new Object();
        obj.idUsuario = $("#selUsuario").val();
        obj.usuario = $("#selUsuario option:selected").text();
        obj.idTipoDocumento = $("#selTipoDocumento").val();
        obj.tipoDocumento = $("#selTipoDocumento option:selected").text();
        dataTipoDocumento.push(obj);
        idTipoDocumentoSeleccionados.push(obj.idTipoDocumento);
        
        cargarTipoDocumento();
        limpiarVariables();
        crearListado();
    });
    
});
function cargarUsuarios(){
    $.ajax({
        url: localStorage.modulo + 'controlador/usuarioTipoDocumento.consultarUsuarios.php',
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
            
            var control = $('#selUsuario');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idUsuario + '">' + fila.usuario + " - " + fila.nombreCompleto + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function cargarTipoDocumento(){
    $.ajax({
        url: localStorage.modulo + 'controlador/usuarioTipoDocumento.consultarTipoDocumento.php',
        type:'POST',
        dataType:"json",
        data:{idTipoDocumento:idTipoDocumentoSeleccionados.join()},
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
            
            var control = $('#selTipoDocumento');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            
            if(json.numeroRegistros == 0){
                return false;
            }
            
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function cargarListado(){
    $("#divListado").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">'; 
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Usuario</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    $("#divListado").html(tabla);		
}
function limpiarVariables(){
    $("#selTipoDocumento").val("");
    $("#selTipoDocumento").focus();
}
function crearListado(){
    $("#divListado").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">'; 
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Usuario</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    
    if(dataTipoDocumento.length == 0){
        cargarListado();
        return false;
    }
    
    for(var i = 0; i < dataTipoDocumento.length; i++){
        var obj = dataTipoDocumento[i];
        tabla += '<tr>';
        tabla += "<td align='center'>" + (i + 1) + "</td>";
        tabla += "<td>" + obj.usuario + "</td>";
        tabla += "<td>" + obj.tipoDocumento + "</td>";
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrar' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarTipoDocumento('+i+')"></span></td>';
        tabla += '</tr>';
    }
    
    tabla += '</table>';
    $("#divListado").html(tabla);	
}
function eliminarTipoDocumento(posicion){
    dataTipoDocumento.splice(posicion, 1);
    idTipoDocumentoSeleccionados.splice(posicion, 1);
    cargarTipoDocumento();
    crearListado();
}