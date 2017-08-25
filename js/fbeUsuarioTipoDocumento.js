var idUsuario;

var data;
$(function(){
    cargarListado();
    cargarUsuarios();
    $("#imgNuevo").click(function(){
        abrirVentana(localStorage.modulo + 'vista/frmUsuarioTipoDocumento.html', '');
    });
    
    $("#imgConsultar").click(function(){
        obtenerDatosEnvio();
        
        if(validarVacios(document.fbeFormulario) == false)
            return false;
        
        $.ajax({
            url:localStorage.modulo + 'controlador/usuarioTipoDocumento.consultar.php',
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
                    alerta("No se encontraron registros para el usuario indicado.");
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
function asignarDatosEnvio(){
    idUsuario = $("#selUsuario").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'idUsuario=' + idUsuario;
}
function cargarListado(){
    $("#consultaTabla").html("");
    var tabla = '';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Usuario</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Naturaleza</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
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
    var tabla = '';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Usuario</th>';
    tabla += '<th>Tipo Documento</th>';
    tabla += '<th>Naturaleza</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    $.each(json.data, function(contador, fila){
        tabla += '<tr>';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.usuario + '</td>';
        tabla += '<td>' + fila.tipoDocumento + '</td>';
        tabla += '<td>' + fila.naturaleza + '</td>';
        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgModificar' + contador + '" title="Editar" class="imagenesTabla" onclick="abrirVentana(' + "'" + localStorage.modulo + 'vista/frmUsuarioTipoDocumento.html' + "'" + ',' + fila.idUsuario +')"></span></td>';
        tabla += '</tr>';
    });
    tabla += '</tr>';
    $("#consultaTabla").html(tabla);		
}
function cargarUsuarios(){
    $.ajax({
        url: '/Seguridad/controlador/usuario.consultarUsuarioEmpresa.php',
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