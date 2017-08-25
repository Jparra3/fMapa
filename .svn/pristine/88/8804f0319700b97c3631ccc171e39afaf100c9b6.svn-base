var valorRecibido = variableUrl();
var idTipoDocumento = valorRecibido[1];
var tipoDocumento = null;
var idNaturaleza = null;
var codigo = null;
var codigoInicial = null;
var idOficina = null;
var estado = null;

var data = null;
$(function(){
    cargarNaturaleza();
    cargarOficinas();
    obtenerSelectEstado($("#selEstado"));
    
    if(idTipoDocumento != "" && idTipoDocumento != null && idTipoDocumento != "undefined"){
        consultarInformacionTipoDocumento();
    }else{
        $("#selEstado").attr("disabled", true);
    }
    
    $("#txtCodigo").change(function(){
       if($.trim($("#txtCodigo").val()) != ""){
           if(validarExistenciaCodigo() == true){//SI EL CODIGO YA EXISTE
               $("#txtCodigo").css("border-color", "#F44336");
               $("#spnImagen").html('<img src="../imagenes/mal.png">');
           }else{//SI EL CODIGO ESTA DISPONIBLE
               $("#txtCodigo").css("border-color", "#4CAF50");
               $("#spnImagen").html('<img src="../imagenes/bien.png">');
           }
       } 
    });
    
    $("#txtCodigo").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtCodigo").css("border-color", "#ccc");
                $("#spnImagen").html('');
            break;
        }
    });
    
    $("#imgGuardar").click(function(){
        obtenerDatosEnvio();

        if(validarVacios(document.frmTipoDocumento) == false)
            return false;


        if(idTipoDocumento != "" && idTipoDocumento != null && idTipoDocumento != "undefined"){
            
            if(codigoInicial != $("#txtCodigo").val()){
                if(validarExistenciaCodigo() == true){
                    alerta("El código del tipo de documento ingresado ya existe.");
                    return false;
                }
            }
            
            modificarTipoDocumento();
        }else{
            
            if(validarExistenciaCodigo() == true){
                alerta("El código del tipo de documento ingresado ya existe.");
                return false;
            }
            
            adicionarTipoDocumento();
        }
    });
});
function asignarValoresEnvio(){
    tipoDocumento = $("#txtTipoDocumento").val();
    idNaturaleza = $("#selNaturaleza").val();
    codigo = $("#txtCodigo").val();
    idOficina = $("#selOficina").val();
    estado = $("#selEstado").val();
}
function obtenerDatosEnvio(){
    asignarValoresEnvio();
    data = "idTipoDocumento=" + idTipoDocumento +"&tipoDocumento=" + tipoDocumento + "&idNaturaleza=" + idNaturaleza + '&codigo=' + codigo + "&idOficina=" + idOficina + "&estado=" + estado;
}
function consultarInformacionTipoDocumento(){
    $.ajax({
        url: localStorage.modulo + 'controlador/tipoDocumento.consultar.php',
        type:'POST',
        dataType:"json",
        data:{idTipoDocumento:idTipoDocumento},
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
            
            crearListado(json);
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function crearListado(json){
    $.each(json.data, function(contador, fila){
        $("#txtTipoDocumento").val(fila.tipoDocumento);
        $("#selNaturaleza").val(fila.idNaturaleza);
        $("#txtCodigo").val(fila.codigo);
        codigoInicial = fila.codigo;
        $("#selOficina").val(fila.idOficina);
        
        if(fila.estado == true)
            $("#selEstado").val("true");
        else
            $("#selEstado").val("false");
    });
}
function cargarNaturaleza(){
    $.ajax({
        async: false,
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
function cargarOficinas(){
    $.ajax({
        async: false,
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

function adicionarTipoDocumento(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/tipoDocumento.adicionar.php',
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
            
            alerta(mensaje, true);
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function modificarTipoDocumento(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/tipoDocumento.modificar.php',
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
            
            alerta(mensaje, true);
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
    return true;
}
function validarExistenciaCodigo(){
    var retorno;
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/tipoDocumento.validarExistenciaCodigo.php',
        type:'POST',
        dataType:"json",
        data:{codigo:$.trim($("#txtCodigo").val())},
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
            retorno = json.existe;
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
    return retorno;
}