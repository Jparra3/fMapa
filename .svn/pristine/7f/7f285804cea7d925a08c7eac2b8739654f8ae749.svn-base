var idZona = null;
var idRegional = null;
var idPeriodicidadVisita = null;
var fechaFin = null;
var data = null;

$(function(){
    cargarZonas();
    cargarPeriodicidadVisita();
    crearCalendario("txtFechaFin");
    cargarListado();
    cargarListadoInfoVendedor();
    $("#imgDescargar").hide();
    
    $("#selZona").change(function(){
        if($(this).val() != ""){
            cargarInfoVendedor($(this).val());
        }else{
            cargarListadoInfoVendedor();
        }
    });
    
    $("#imgBuscar").click(function(){
        
        obtenerDatosEnvio();
        
        if (validarVacios(document.frmRuta) == false)
            return false;
        
        $.ajax({
            async: false,
            url: localStorage.modulo + "controlador/ruta.consultar.php",
            type: 'POST',
            dataType: "json",
            data: data,
            success: function (json) {
                var exito = json.exito;
                var mensaje = json.mensaje;

                if (exito == 0) {
                    alerta(mensaje);
                    return false;
                }

                if(json.numeroRegistros == 0){
                    cargarListado();
                    $("#imgDescargar").hide();
                    alerta("No se encontraron registros con los parametros indicados.");
                    return false;
                }
                
                crearListado(json);
                
                $("#imgDescargar").show();
                
            }, error: function (xhr, opciones, error) {
                alerta(error);
            }
        });
    });    
    
    $("#imgDescargar").click(function(){
        $.ajax({
            async: false,
            url: localStorage.modulo + "controlador/ruta.descargar.php",
            type: 'POST',
            dataType: "json",
            data: null,
            success: function (json) {
                var exito = json.exito;
                var mensaje = json.mensaje;
                var ruta = json.ruta;

                if (exito == 0) {
                    alerta(mensaje);
                    return false;
                }
                
                window.open(ruta);
                alerta(mensaje, true);
                
            }, error: function (xhr, opciones, error) {
                alerta(error);
            }
        });
    });    
});
function cargarListado() {
    $("#divListadoRuta").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Nit</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Fecha inicial</th>';
    tabla += '<th>Hora visita</th>';
    tabla += '<th>Tiempo visita</th>';
    tabla += '<th>Visita festivos</th>';
    tabla += '<th>Fecha visita</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoRuta").html(tabla);
}
function crearListado(json) {
    $("#divListadoRuta").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Nit</th>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Fecha inicial</th>';
    tabla += '<th>Hora visita</th>';
    tabla += '<th>Tiempo visita</th>';
    tabla += '<th>Visita festivos</th>';
    tabla += '<th>Fecha visita</th>';
    tabla += '</tr>';
    
    $.each(json.data, function(contador, fila){
        tabla += '<tr>';
        tabla += '<td style="text-align:center;">' + (contador + 1) + '</td>';
        tabla += '<td style="text-align:right;">' + fila.nit + '</td>';
        tabla += '<td style="text-align:left;">' + fila.tercero + '</td>';
        tabla += '<td style="text-align:center;">' + fila.fechaInicialVisita + '</td>';
        tabla += '<td style="text-align:center;">' + fila.horaVisita + '</td>';
        tabla += '<td style="text-align:center;">' + fila.tiempoVisita + ' min.</td>';
        tabla += '<td style="text-align:center;">' + fila.visitaFestivos + '</td>';
        tabla += '<td style="text-align:center;">' + fila.fechaVisita + '</td>';
        tabla += '</tr>';
    });
    
    tabla += '</table>';
    $("#divListadoRuta").html(tabla);
}
function cargarZonas() {
    $.ajax({
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
            var control = $('#selZona');
            control.empty();
            control.append("<option value=''> --Seleccione-- </option>");
            $.each(json.data, function(contador, fila) {
                control.append('<option idRegional="' + fila.idRegional + '" value="' + fila.idZona + '">' + fila.zonaRegional + '</option>');
            });

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarPeriodicidadVisita(){
    $.ajax({
        url: localStorage.modulo + 'controlador/periodicidadVisita.cargar.php',
        dataType: 'json',
        type:'POST',
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){   
                alerta(mensaje);
                return false;
            }
            
            var control = $('#selPeriodicidadVisita');
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador,fila){
                control.append('<option value="'+ fila.idPeriodicidadVisita +'">'+ fila.periodicidadVisita +'</option>');
            });
        }
    
    });
}
function asignarDatosEnvio(){
    idZona = $("#selZona").val();
    idRegional = $("#selZona option:selected").attr("idRegional");
    idPeriodicidadVisita = $("#selPeriodicidadVisita").val();
    fechaFin = $("#txtFechaFin").val();
}
function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'idZona=' + idZona + '&idRegional=' + idRegional + '&modulo=' + localStorage.modulo + '&idPeriodicidadVisita=' + idPeriodicidadVisita + '&fechaFin=' + fechaFin;
}
function cargarInfoVendedor(idZona){
    $.ajax({
        url: localStorage.modulo + 'controlador/vendedor.consultar.php',
        dataType: 'json',
        type:'POST',
        data:{idZona:idZona},
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){  
                alerta(mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                cargarListadoInfoVendedor();
                return false;
            }
            
            crearListadoInfoVendedor(json);
        }
    
    });
}
function cargarListadoInfoVendedor(){
    $("#divInfoVendedor").html("");
    var html = '<table class="table table-bordered table-striped consultaTabla">';
    html += '<tr>';
    html += '<th colspan="5">INFORMACIÓN DEL VENDEDOR</th>';
    html += '</tr>';
    html += '<tr>';
    html += '<th>Nro. Identificación</th>';
    html += '<th>Nombre</th>';
    html += '<th>Dirección</th>';
    html += '<th>Celular</th>';
    html += '<th>Correo</th>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>&nbsp;</td>';
    html += '<td>&nbsp;</td>';
    html += '<td>&nbsp;</td>';
    html += '<td>&nbsp;</td>';
    html += '<td>&nbsp;</td>';
    html += '</tr>';
    html += '</table>';
    $("#divInfoVendedor").html(html);
}
function crearListadoInfoVendedor(json){
    $("#divInfoVendedor").html("");
    var html = '<table class="table table-bordered table-striped consultaTabla">';
    html += '<tr>';
    html += '<th colspan="5">INFORMACIÓN DEL VENDEDOR</th>';
    html += '</tr>';
    html += '<tr>';
    html += '<th>Nro. Identificación</th>';
    html += '<th>Nombre</th>';
    html += '<th>Dirección</th>';
    html += '<th>Celular</th>';
    html += '<th>Correo</th>';
    html += '</tr>';
    $.each(json.data, function(contador, fila){
        html += '<tr>';
        html += '<td>' + fila.documentoIdentidad + '</td>';
        html += '<td>' + fila.nombreCompleto + '</td>';
        html += '<td>' + fila.direccion + '</td>';
        html += '<td>' + fila.celular + '</td>';
        html += '<td>' + fila.correoElectronico + '</td>';
        html += '</tr>';
    });
    html += '</table>';
    $("#divInfoVendedor").html(html);
}