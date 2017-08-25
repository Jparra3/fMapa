var valorRecibido = variableUrl();
var idOrdenador = valorRecibido[1];
var idPersona = null;
var idEmpleado = null;

var iframe;
$(function(){
    
    cargarArl();
    cargarEps();
    cargarCargos();
    document.getElementById('ifrInformacionBasicaPersona').onload = function(){ // Cuando el iframe haya cargado en su totalidad
        
        iframe  = window.frames.ifrInformacionBasicaPersona; //Obtengo el objeto iframe
        validarExistenciaPersona();
        if(idOrdenador != "" && idOrdenador != null && idOrdenador != "null"){
            var data = "idOrdenador="+idOrdenador;
            consultarTecnico(data);
        }else{
            $('#accionesNuevas').html('<span id="imgLimpiar" class="fa fa-eraser imagenesTabla" title="Limpiar"></span>');
        }

        $("#imgGuardar").click(function(){
            if(validarVacios(document.frmOrdenador) == false)
                return false;
            
            if(idOrdenador != "" && idOrdenador != null && idOrdenador != "null"){
                iframe.modificarPersona();
                modificarTecnico();
            }else{
                if(idPersona != null && idPersona != "" && idPersona !="null"){
                    iframe.modificarPersona();
                }else{
                    idPersona = iframe.adicionarPersona(); //Llamo la funcion para adicionar la persona
                }
                if(idPersona != false)
                adicionarTecnico();
            }
        });
        
        $("#imgLimpiar").click(function (){
            iframe.limpiarPersona();
            limpiarVariables();
            limpiarControlesFormulario(document.frmOrdenador);
        });
        iframe.$("#txtNumeroDocumento").keypress(function(e){
            switch(e.keyCode){
                case 08 || 46:
                    var numeroIdentificacion = iframe.$("#txtNumeroDocumento").val();
                    iframe.limpiarPersona();
                    limpiarVariables();
                    limpiarControlesFormulario(document.frmOrdenador);
                    iframe.$("#txtNumeroDocumento").val(numeroIdentificacion);
                break;
            }
        });
    }
});

function adicionarTecnico(){
    var data = obtenerDatosEnvio();
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenador.adicionar.php',
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
            alerta(mensaje);
            limpiarOrdenador()
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function modificarTecnico(){
    var data = obtenerDatosEnvio();
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenador.modificar.php',
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
            alerta(mensaje);
            limpiarOrdenador();
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function consultarTecnico(data){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenador.consultar.php',
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
            
            if (json.numeroRegistros == 0) {
                if(idPersona != null && idPersona != undefined && idPersona != ""){
                    alerta("Esta persona no está registrada como técnico, parar registrarla termine de diligenciar los datos y precione guardar.");
                }
                return false;
            }
            
            crearListado(json);
            iframe.consultarPersona(idPersona); //Asigno los datos de persona
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function cargarArl(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/arl.consultar.php',
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
            if (json.numeroRegistros == 0) {
                return false;
            }
            var control = $('#selArl');
            control.empty();
            control.append("<option value=''> --Seleccione-- </option>");
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idArl + '">' + fila.arl + '</option>');
            });
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function cargarEps(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/eps.consultar.php',
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
            if (json.numeroRegistros == 0) {
                return false;
            }
            var control = $('#selEps');
            control.empty();
            control.append("<option value=''> --Seleccione-- </option>");
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEps + '">' + fila.eps + '</option>');
            });
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function cargarCargos(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/cargo.consultar.php',
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
            if (json.numeroRegistros == 0) {
                return false;
            }
            var control = $('#selCargo');
            control.empty();
            control.append("<option value=''> --Seleccione-- </option>");
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idCargo + '">' + fila.cargo + '</option>');
            });
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function crearListado(json){
    $.each(json.data,function(contador, fila){
        $("#selEps").val(fila.idEps);
        $("#selArl").val(fila.idArl);
        $("#selCargo").val(fila.idCargo);
        $("#selEstado").val(fila.estado.toString());
        idPersona = fila.idPersona;
        idEmpleado = fila.idEmpleado
    });
}
function obtenerDatosEnvio(){
    var data = "";
    data += "idOrdenador="+idOrdenador;
    data += "&idPersona="+idPersona;
    data += "&idEmpleado="+idEmpleado;
    data += "&idEps="+$("#selEps").val();
    data += "&idArl="+$("#selArl").val();
    data += "&idCargo="+$("#selCargo").val();
    data += "&estado="+$("#selEstado").val();
    return data;
}

function limpiarVariables(){
    idOrdenador = null;
    idPersona = null;
    idEmpleado = null;
}

function cargarZonas() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/zona.consultar.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }
            var control = $('#selZonaVendedor');
            control.empty();
            control.append("<option value=''> --Seleccione-- </option>");
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idZona + '">' + fila.zonaRegional + '</option>');
            });

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function validarExistenciaPersona(){
    iframe.$("#txtNumeroDocumento").change(function(){
        if(iframe.$("#txtNumeroDocumento").val().toString() != "" && iframe.$("#txtNumeroDocumento").val().toString() != null){
            idPersona = iframe.consultarNitPersona(iframe.$("#txtNumeroDocumento").val());
            if(idPersona != null && idPersona != undefined && idPersona != false){
                var data = "idPersona="+idPersona;
                consultarTecnico(data);
            } 
        }
    });
}

function limpiarOrdenador(){
    limpiarControlesFormulario(document.frmOrdenador);
    iframe.limpiarPersona();
}