var valorRecibido = variableUrl();
var idCaja = valorRecibido[1];
var origen = valorRecibido[2];
var arrFormatoImpresion = new Array();

$(function () {
    obtenerSelectEstadoSeleccione($("#selEstado"));
    consultarTipoDocumento();
    cargarBodegas();
    crearTablaFormato();
    crearCalendario("txtFechaExpedicionResolucion");
    cargarFormatosImpresion();
    validarCaja();
    onClick();
    onChangeTxtIp();
});

function onClick() {
    $("#imgGuardar").bind({
        "click": function () {
            if (validarVacios(document.frmInformacionCaja) == false)
                return false;
            if(arrFormatoImpresion.length == 0){
                alerta("Debe adicionar almenos un formato de impresión.");
                return;
            }
            var estadoFormatos = validarFormatoPrincipal();
            if(estadoFormatos != true){
                alerta("Debe seleccionar un formato de impresión como principal.");
                return;
            }
            obtenerDatosEnvio();
            if(idCaja != null && idCaja != "null" && idCaja != undefined){
                actualizarCaja();
            }else{
                adicionarCaja();
            }
        }
    });

    $("#imgNuevoFormato").bind({
        "click": function () {
            if (validarVacios(document.frmFormatoImpresion) == false)
                return false;
            var estadoFormatoArreglo = validarFormatoArreglo();
            if(estadoFormatoArreglo != true){
                adicionarFormato();   
            }
        }
    });
}

function validarCaja(){
    if(idCaja != null && idCaja != "null" && idCaja != "" && idCaja != undefined){
        consultarCaja(idCaja);
    }
}

function onChangeTxtIp(){
    $("#txtDireccionIp").change(function(){
        if($(this).val().toString() != ""){
           validarExistenciaIp($(this).val()); 
        }
    });
}

function validarExistenciaIp(direccionIp){
    $.ajax({
        url: localStorage.modulo + 'controlador/caja.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {direccionIp: direccionIp},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros > 0) {
                alerta("La dirección ip "+direccionIp+" ya está registrada.");
                $("#txtDireccionIp").val("");
                return false;
            }
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function consultarTipoDocumento() {
    $.ajax({
        url: localStorage.modulo + 'controlador/tipoDocumento.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {estado: true},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                alerta("No se encontraron registros con los parámetros indicados.");
                return false;
            }

            var control = $('#selTipoDocumento');
            control.empty();
            control.append('<option value=""> -- Seleccione -- </option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function cargarBodegas() {
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selBodega');
            control.empty();
            control.append('<option value=""> -- Seleccione -- </option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}

//Adicionar caja
function adicionarCaja() {
    $.ajax({
        url: localStorage.modulo + 'controlador/caja.adicionarGestionCaja.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            alerta(mensaje, true);
        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}

function actualizarCaja() {
    $.ajax({
        url: localStorage.modulo + 'controlador/caja.actualizarGestionCaja.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function (json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            alerta(mensaje, true);
        }, error: function (xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}

function obtenerDatosEnvio() {
    data = "";
    data += "prefijo=" + $("#txtPrefijo").val();
    data += "&numeroMaximo=" + $("#txtNumeroMaximo").val();
    data += "&numeroMinimo=" + $("#txtNumeroMinimo").val();
    data += "&ultimoNumeroUtilizado=" + $("#txtUltimoNumeroUtilizado").val();
    data += "&direccionIp=" + $("#txtDireccionIp").val();
    data += "&nombrePc=" + $("#txtNombrePc").val();
    data += "&numeroResolucion=" + $("#txtNumeroResolucion").val();
    data += "&fechaExpedicionResolucion=" + $("#txtFechaExpedicionResolucion").val();
    data += "&idTipoDocumento=" + $("#selTipoDocumento").val();
    data += "&idBodega=" + $("#selBodega").val();
    data += "&estado=" + $("#selEstado").val();
    data += "&serialPc=" + $("#txtSerialPc").val();
    data += "&macPc=" + $("#txtMacPc").val();
    data += "&idCaja=" + idCaja;
    data += "&arrFormatoImpresion=" + JSON.stringify(arrFormatoImpresion);
}

//Formatos de impresion
function crearTablaFormato() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Formato impresion </th>";
    html += "<th> Accion </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp </td>";
    html += "<td> &nbsp </td>";
    html += "<td> &nbsp </td>";
    html += "</tr>";

    $("#tblFormatoImpresion").html(html);
}

function adicionarFormato() {
    var objFormato = new Object();
    objFormato.idFormatoImpresion = $("#selFormatoImpresion").val();
    objFormato.formatoImpresion = $("#selFormatoImpresion option:selected").html();
    objFormato.idCajaFormatoImpresion = null;
    objFormato.principal = 'false';
    objFormato.idCaja = null;
    objFormato.estado = 'TRUE';
    $("#"+objFormato.idFormatoImpresion).hide();
    limpiarControlesFormulario(document.frmFormatoImpresion);

    arrFormatoImpresion.push(objFormato);
    visualizarFormatosImpresion();
}

function visualizarFormatosImpresion() {
    if(arrFormatoImpresion.length == 0){
        crearTablaFormato();
        return;
    }
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Formato impresion </th>";
    html += "<th> Principal </th>";
    html += "<th> Accion </th>";
    html += "</tr>";
    $.each(arrFormatoImpresion, function (contador, fila) {
        if(fila.estado != false && fila.estado != 'false'){
            html += "<tr>";
            html += "<td class='valorFijo'> "+parseInt(contador+1)+" </td>";
            html += "<td> "+fila.formatoImpresion+" </td>";
            var estadoPrincipal = "";
            if(fila.principal != "false" && fila.principal != false && fila.principal != null && fila.principal != "null"){
                estadoPrincipal = " checked='checked' ";
            }
            html += "<td class='valorFijo'> <input type='radio' name='radio' id='rd"+contador+"' "+estadoPrincipal+"> </td>";
            html += "<td class='valorFijo'><span onClick='anularFormatoImpresion(" + contador + ")' class='fa fa-trash imagenesTabla' title='Eliminar formato'></span></td>";
            html += "</tr>";
        }
    });
    $("#tblFormatoImpresion").html(html);
    $.each(arrFormatoImpresion, function (contador, fila) {
        if(fila.estado != false && fila.estado != 'false'){
            $("#rd"+contador).change(function(){
                cambiarPrincipal(contador);
            });
        }else{
            if(fila.principal != "false" && fila.principal != false){
                arrFormatoImpresion[contador].principal = 'false';
            }
        }
    });
}

function cambiarPrincipal(posicion){
    arrFormatoImpresion[posicion].principal = "true";
    $.each(arrFormatoImpresion, function (contador, fila) {
        if(contador != posicion){
            arrFormatoImpresion[contador].principal = "false";
        }
    });
}

function cargarFormatosImpresion(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/formatoImpresion.consultar.php',
        type: 'POST',
        dataType: "json",
        data: { 
              estado: "true"
        },
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                var control = $("#selFormatoImpresion");
                control.append("<option value=''> --Seleccione-- </option>");
                return false;
            }
            
            var control = $("#selFormatoImpresion");
            control.empty();
            control.append("<option value=''> --Seleccione-- </option>");
            var formatoPrincipal = null;
            $.each(json.data, function(contador, fila) {
                control.append("<option id='"+fila.idFormatoImpresion+"' value='"+fila.idFormatoImpresion+"' name='"+fila.archivo+"'> "+fila.formatoImpresion+" </option>");
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function anularFormatoImpresion(posicion){
    var objFormato = arrFormatoImpresion[posicion];
    $("#"+objFormato.idFormatoImpresion).show();
    if(objFormato.idCaja != null && objFormato.idCaja != "null"){
        arrFormatoImpresion[posicion].estado = "false";
    }else{
        arrFormatoImpresion.splice(posicion, 1);
    }
    visualizarFormatosImpresion();
}

function consultarCaja(idCaja){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/caja.consultaMasiva.php',
        type:'POST',
        dataType:"json",
        data:{idCaja:idCaja},
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
            
            asignarValorControles(json);
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function asignarValorControles(json){
    $.each(json.data, function(contador, fila){
        $("#txtPrefijo").val(fila.prefijo);
        $("#txtNumeroMaximo").val(fila.numeroMaximo);
        $("#txtNumeroMinimo").val(fila.numeroMinimo);
        $("#txtUltimoNumeroUtilizado").val(fila.ultimoNumeroUtilizado);
        $("#txtDireccionIp").val(fila.direccionIp);
        $("#txtNombrePc").val(fila.nombrePc);
        $("#txtSerialPc").val(fila.serialPc);
        $("#txtMacPc").val(fila.macPc);
        $("#txtNumeroResolucion").val(fila.numeroResolucion);
        $("#txtFechaExpedicionResolucion").val(fila.fechaExpedicionResolucion);
        $("#selTipoDocumento").val(fila.idTipoDocumento);
        $("#selBodega").val(fila.idBodega);
        $("#selEstado").val(fila.estado.toString());
        
        if(fila.arrFormatoImpresion != null){
            $.each(fila.arrFormatoImpresion, function(contadorFormato, filaFormato){
                if(filaFormato.estado.toString() != "false"){
                    $("#"+filaFormato.idFormatoImpresion).hide();
                }
                var objFormato = new Object();
                objFormato.idFormatoImpresion = filaFormato.idFormatoImpresion;
                objFormato.formatoImpresion = filaFormato.formatoImpresion;
                objFormato.idCajaFormatoImpresion = filaFormato.idCajaFormatoImpresion;
                objFormato.principal = filaFormato.principal.toString();
                objFormato.idCaja = filaFormato.idCaja.toString();
                objFormato.estado = filaFormato.estado.toString();


                arrFormatoImpresion.push(objFormato);
            });
            visualizarFormatosImpresion();
        }else{
            alerta("Esta caja no presenta formatos de impresion disponibles.");
        }
    });
}

function validarFormatoPrincipal(){
    var estadoFormatos = true;
    var contadorPrincipal = 0;
    $.each(arrFormatoImpresion, function (contador, fila) {
        if(fila.estado != false && fila.estado != 'false'){
            if($("#rd"+contador).is(":checked")){
                contadorPrincipal++;
            }
        }
    });   
    if(contadorPrincipal == 0){
        estadoFormatos = false;
    }
    return estadoFormatos;
}

function validarFormatoArreglo(){
    var estado = false;
    var idFormatoImpresion = $("#selFormatoImpresion").val();
    $.each(arrFormatoImpresion, function(contador, fila){
        if(idFormatoImpresion == fila.idFormatoImpresion){
            arrFormatoImpresion[contador].estado = 'true';
            $("#"+idFormatoImpresion).hide(); 
            estado = true;
            limpiarControlesFormulario(document.frmFormatoImpresion);
        }
    });
    visualizarFormatosImpresion();
    return estado;
}