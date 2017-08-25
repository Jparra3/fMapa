var valorRecibido = variableUrl();
var idVendedor = valorRecibido[1];
var idPersona = null;

var iframe;
$(function() {
    cargarZonas();
    document.getElementById('ifrInformacionBasicaPersona').onload = function() { // Cuando el iframe haya cargado en su totalidad

        iframe = window.frames.ifrInformacionBasicaPersona; //Obtengo el objeto iframe
        validarExistenciaPersona();
        if (idVendedor != "" && idVendedor != null && idVendedor != "null") {
            var data = "idVendedor=" + idVendedor;
            consultarVendedor(data);
        } else {
            $('#accionesNuevas').html('<span id="imgLimpiar" class="fa fa-eraser imagenesTabla" title="Limpiar"></span>');
        }

        $("#imgGuardar").click(function() {
            if (validarVacios(document.frmPegador) == false)
                return false;

            if (idVendedor != "" && idVendedor != null && idVendedor != "null") {
                iframe.modificarPersona();
                modificarVendedor();
            } else {
                if(idPersona != null && idPersona != undefined && idPersona != ""){
                    iframe.modificarPersona();
                }else{
                    idPersona = iframe.adicionarPersona(); //Llamo la funcion para adicionar la persona
                }
                if (idPersona != false)
                    adicionarVendedor();
            }
        });

        $("#imgLimpiar").click(function() {
            iframe.limpiarPersona();
            limpiarVariables();
            limpiarControlesFormulario(document.frmPegador);
        });
        iframe.$("#txtNumeroDocumento").keypress(function(e) {
            switch (e.keyCode) {
                case 08 || 46:
                    var numeroIdentificacion = iframe.$("#txtNumeroDocumento").val();
                    iframe.limpiarPersona();
                    limpiarVariables();
                    limpiarControlesFormulario(document.frmPegador);
                    iframe.$("#txtNumeroDocumento").val(numeroIdentificacion);
                    break;
            }
        });
    }
});

function adicionarVendedor() {
    var data = obtenerDatosEnvio();
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/vendedor.adicionar.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            alerta(mensaje);
            limpiarVendedor()
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function modificarVendedor() {
    var data = obtenerDatosEnvio();
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/vendedor.modificar.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            alerta(mensaje);
            limpiarVendedor();
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function consultarVendedor(data) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/vendedor.consultar.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                if(idPersona != null && idPersona != undefined && idPersona != ""){
                    alerta("Esta persona no está registrada como vendedor, parar registrarla termine de diligenciar los datos y precione guardar.");
                }
                return false;
            }

            crearListado(json);
            iframe.consultarPersona(idPersona); //Asigno los datos de persona
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

//function adicionarPegador(){
//    obtenerDatosEnvio();
//    $.ajax({
//        async:false,
//        url: localStorage.modulo + 'controlador/vendedor.adicionar.php',
//        type:'POST',
//        dataType:"json",
//        data:data,
//        success: function(json){
//                var exito = json.exito;
//                var mensaje = json.mensaje;
//                
//                if(exito == 0){
//                    alerta(mensaje);
//                    return false;
//                }
//                alerta("La información del vendedor se guardó correctamente.", true);
//        },error: function(xhr, opciones, error){
//            alerta (error);
//        }
//    });
//}
//function modificarPegador(){
//    obtenerDatosEnvio();
//    $.ajax({
//        async:false,
//        url: localStorage.modulo + 'controlador/vendedor.modificar.php',
//        type:'POST',
//        dataType:"json",
//        data:data,
//        success: function(json){
//                var exito = json.exito;
//                var mensaje = json.mensaje;
//                
//                if(exito == 0){
//                    alerta(mensaje);
//                    return false;
//                }
//                alerta("La información del vendedor se guardó correctamente.", true);
//        },error: function(xhr, opciones, error){
//            alerta (error);
//        }
//    });
//}
function crearListado(json) {
    $.each(json.data, function(contador, fila) {
        $("#selZonaVendedor").val(fila.idZona);
        $("#selEstado").val(fila.estado.toString());
        idVendedor = fila.idVendedor;
        idPersona = fila.idPersona;
    });
}
function obtenerDatosEnvio() {
    var data = "";
    data += "idVendedor=" + idVendedor;
    data += "&idPersona=" + idPersona;
    data += "&idZona=" + $("#selZonaVendedor").val();
    data += "&estado=" + $("#selEstado").val();
    return data;
}

function limpiarVariables() {
    idVendedor = null;
    idPersona = null;
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

function validarExistenciaPersona() {
    iframe.$("#txtNumeroDocumento").change(function() {
        if (iframe.$("#txtNumeroDocumento").val().toString() != "" && iframe.$("#txtNumeroDocumento").val().toString() != null) {
            idPersona = iframe.consultarNitPersona(iframe.$("#txtNumeroDocumento").val());
            if (idPersona != null && idPersona != undefined) {
                var data = "idPersona=" + idPersona;
                consultarVendedor(data);
            }
        }
    });
}

function limpiarVendedor() {
    limpiarControlesFormulario(document.frmPegador);
    iframe.limpiarPersona();
}