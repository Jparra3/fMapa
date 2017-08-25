var idCaja = null;
var idUsuario = null;

$(function(){
    autoCompletarPrefijo();
    //autoCompletarUsuario();
    //consultarFormatoImpresion();
    crearTabla();
    onClick();
});

function onClick(){
    $("#imgConsultar").bind({
       "click":function(){
           var data = obtenerDatosEnvio();
           consultar(data);
       } 
    });
    
    $("#imgLimpiar").bind({
       "click":function(){
           limpiarFormulario();
       } 
    });
    
    $("#imgNuevo").bind({
        "click":function (){
            abrirVentanaCaja(null);
        }
    });
}

function autoCompletarPrefijo(){
    $("#txtPrefijo").autocomplete({
        source: localStorage.modulo + 'ajax/caja.autoCompletarPrefijo.php',
        select:function(event, ui){
            idCaja = ui.item.idCaja;
        }
    });
}

function autoCompletarUsuario(){
    $("#txtUsuario").autocomplete({
        source: localStorage.modulo + 'ajax/usuario.autoCompletarUsuario.php',
        select:function(event, ui){
            idUsuario = ui.item.idUsuario;
        }
    });
}

function crearTabla(){
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Prefijo </th>";
    html += "<th> Dirección ip </th>";
    html += "<th> Usuarios que tienen permiso </th>";
    html += "<th> Formatos de impresión </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp </td>";
    html += "<td> &nbsp </td>";
    html += "<td> &nbsp </td>";
    html += "<td> &nbsp </td>";
    html += "<td> &nbsp </td>";
    html += "<td> &nbsp </td>";
    html += "</tr>";
    
    $("#consultaTabla").html(html);
}

function consultarFormatoImpresion(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/formatoImpresion.consultar.php',
        type:'POST',
        dataType:"json",
        data:{estado:true},
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
            
            var control = $('#selFormatoImpresion');
            control.empty();
            
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idFormatoImpresion + '">' + fila.formatoImpresion + '</option>');
            });
            
            $('#selFormatoImpresion').multiselect({
                maxHeight: 400
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
            
            $("button.multiselect").css("width","300px");
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function consultar(data){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/caja.consultaMasiva.php',
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
                limpiarFormulario();
                return false;
            }
            
            visualizarInformacionCaja(json);
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function limpiarFormulario(){
    limpiarControlesFormulario(document.fbeFormulario);
    crearTabla();
    //
//    $('#selFormatoImpresion').val('').multiselect('destroy');
//    $('#selFormatoImpresion').multiselect({
//        maxHeight: 600
//        ,nonSelectedText: '--Seleccione--'	
//        ,enableFiltering: true
//        ,filterPlaceholder: 'Buscar'
//        ,numberDisplayed: 1
//        ,enableCaseInsensitiveFiltering: true
//    });
//    $("button.multiselect").css("width","300px");
    
    //
    idCaja = null;
    idUsuario = null;
}

function visualizarInformacionCaja(json){
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Prefijo </th>";
    html += "<th> Dirección ip </th>";
    html += "<th> Usuarios que tienen permiso </th>";
    html += "<th> Formatos de impresión </th>";
    html += "<th> Estado </th>";
    html += "<th colspan='2'> Acción </th>";
    html += "</tr>";
    var rowspan = null;
    $.each(json.data, function(contador, fila){
        html += "<tr>";
        html += "<td class='valorFijo'> "+parseInt(contador+1)+" </td>";
        html += "<td class='valorTexto'> "+fila.prefijo+" </td>";
        html += "<td class='valorTexto'> "+fila.direccionIp+" </td>";
        if(fila.arrUsuario != null){
            var infoUsuario = "";
            $.each(fila.arrUsuario, function(contadorUsuario, filaUsuario){
                infoUsuario += filaUsuario.usuario+"-" + filaUsuario.persona + "<br>";
            });
            html += "<td class='valorTexto'> " + infoUsuario + " </td>";
        }else{
            html += "<td class='valorTexto'>  </td>";
        }
        
        if(fila.arrFormatoImpresion != null){
            var infoFormato = "";
            $.each(fila.arrFormatoImpresion, function(contadorFormato, filaFormato){
                infoFormato += filaFormato.formatoImpresion+ "<br>";
            });
            html += "<td class='valorTexto'> "+infoFormato+ " </td>";
        }else{
            html += "<td class='valorTexto'>  </td>";
        }
        
        if(fila.estado != false && fila.estado != "FALSE" && fila.estado != "false"){
            html += "<td class='valorTexto'> Activo </td>";
        }else{
            html += "<td class='valorTexto'> Inactivo </td>";
        }
        html += "<td class='valorFijo'> <span onClick='abrirVentanaCaja(" + fila.idCaja + ")' class='fa fa-pencil imagenesTabla' title='Editar caja'></span> </td>";
        if(fila.estado != false && fila.estado != "FALSE" && fila.estado != "false"){
            html += "<td class='valorFijo'> <span onClick='estadoCaja(" + fila.idCaja + "," + '"'+fila.prefijo+ '"' + ", "+'"'+false+'"'+")' class='fa fa-minus imagenesTabla' title='Inactivar caja'></span> </td>";
        }else{
            html += "<td class='valorFijo'> <span onClick='estadoCaja(" + fila.idCaja + "," + '"'+fila.prefijo+ '"' + ", "+"true"+")' class='fa fa-plus imagenesTabla' title='Activar caja'></span> </td>";
        }
        html += "</tr>";
    });
    
    $("#consultaTabla").html(html);
}

function obtenerDatosEnvio(){
    var data = "";
    data += "idCaja=" + idCaja;
    data += "&idUsuario=" + idUsuario;
    data += "&estado=" + $("#selEstado").val();
    data += "&idFormatoImpresion=" + $("#selFormatoImpresion").val();
    data += "&direccionIp=" + $("#txtDireccionIp").val();
    
    return data;
}

function abrirVentanaCaja(idCaja){
    abrirVentana(localStorage.modulo + 'vista/frmGestionCaja.html?id='+idCaja, 'Caja'); 
}

function estadoCaja(idCaja, prefijo, estado){
    var mensaje = "";
    if(estado != "false" && estado != false){
        mensaje = "Está seguro(a) de activar la caja " + prefijo + "?";
    }else{
        mensaje = "Está seguro(a) de inactivar la caja " + prefijo + "?";
    }
    bootbox.confirm(mensaje, function(result) {
        if (result == true) {
            actualizarEstadoCaja(idCaja, estado);
        }
    });
}

function actualizarEstadoCaja(idCaja, estado){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/caja.actualizarEstadoGestianCaja.php',
        type:'POST',
        dataType:"json",
        data:{idCaja:idCaja, estado:estado},
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
            
            alerta("Se modificó la información correctamente.");
            $("#imgConsultar").click();
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}