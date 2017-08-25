var idProducto = null;
var codigo = null;
var producto = null;
var idUnidadMedida = null;
var idLineaProducto = null;
var idBodega = null;
var mostrarSaldosNegativos = null;
var maximoMinimo = null;

var data = null;
$(function(){
    cargarUnidadMedida();
    cargarTabla();
    cargarBodegas();
    autoCompletarProducto();

    $("#imgConsultar").bind({
        "click":function(){
            validaLogueo();
            numeroControlesVacios = validarVaciosConsultar(document.fbeFormulario);

            if(numeroControlesVacios == document.fbeFormulario.length - 1){
                alerta("Debe ingresar al menos un parámetro de búsqueda.");
                return false;
            }
            
            obtenerDatosEnvio();
            $.ajax({
                async:false,
                url: localStorage.modulo + "controlador/saldosMinimos.consultar.php",
                type:'POST',
                dataType:"json",
                data:data,
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;

                    if (exito == 0){
                            alerta(mensaje);
                            return false;
                    }
                    
                    if(json.numeroRegistros == 0){
                        alerta('No se encontraron registros con los parámetros indicados.');
                        cargarTabla();
                        return false;
                    }
                    
                    crearListado(json);  

                    localStorage.numeroRegistros = json.numeroRegistros;//Almaceno el número de registros para saber cuantas debo ocultar
                    obtenerPermisosFormulario(localStorage.formulario);//Habilitar las acciones a las que tenga permiso

                },error: function(xhr, opciones, error){
                         alerta(error);
                }
            });
        }
    });
	
    $("#imgLimpiar").bind({
        "click":function(){
            limpiarVariables();
            limpiarControlesFormulario(document.fbeFormulario);
            cargarTabla();
        }
    });
    
    $("#btnBuscarLineas").click(function(){
        cargarLinea();
    });
});

function limpiarVariables(){
    idProducto = null;
    codigo = null;
    producto = null;
    idUnidadMedida  = null;
    idLineaProducto = null;
    idBodega = null;
    mostrarSaldosNegativos = null;
    maximoMinimo = null;

    data = null;
}

function asignarDatosEnvio(){
    producto = $("#txtProducto").val();
    codigo = $("#txtCodigoProducto").val();
    idUnidadMedida  = $("#selUnidadMedida").val();
    idBodega = $("#selBodega").val();
    mostrarSaldosNegativos = $("#selSaldosNegativos").val();
    maximoMinimo = $("#selMaximoMinimo").val();
}

function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'idProducto=' + idProducto + '&codigoProducto=' + codigo + '&producto=' + producto + '&idUnidadMedida='+ idUnidadMedida + '&idLineaProducto=' + idLineaProducto + '&idBodega=' + idBodega + '&mostrarSaldosNegativos=' + mostrarSaldosNegativos + '&maximoMinimo=' + maximoMinimo;
}
function cargarTabla(){
    var tabla = '<tr>';
    tabla += '<th><p>#</p></th>';
    tabla += '<th><p>Producto</p></th>';
    tabla += '<th><p>Unidad Medida</p></th>';
    tabla += '<th><p>Linea Producto</p></th>';
    tabla += '<th><p>Bodega</p></th>';
    tabla += '<th><p>' + $("#selMaximoMinimo option:selected").html() + '</p></th>';
    tabla += '<th><p>Existencia actual</p></th>';
    tabla += '</tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    $('#consultaTabla').html(tabla);
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '<thead>';
    tabla += '<tr id="trEstatico">';
    tabla += '<th><p>#</p></th>';
    tabla += '<th><p>Producto</p></th>';
    tabla += '<th><p>Linea Producto</p></th>';
    tabla += '<th><p>Bodega</p></th>';
    tabla += '<th><p>' + $("#selMaximoMinimo option:selected").html() + '</p></th>';
    tabla += '<th><p>Existencia actual</p></th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';
   $.each(json.data,function(contador, fila){
        tabla += '<tr>';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.codigo + ' - ' + fila.producto + '</td>';
        tabla += '<td>' + fila.lineaProducto + '</td>';
        tabla += '<td>' + fila.bodega + '</td>';
        
        if($("#selMaximoMinimo").val() == "1"){//Maximo
            tabla += '<td align="right">' + fila.maximo + '</td>';
        }else if($("#selMaximoMinimo").val() == "0"){//Mínimo
            tabla += '<td align="right">' + fila.minimo + '</td>';
        }
        
        tabla += '<td align="right">' + fila.cantidad + '</td>';
        tabla += '</tr>';
    });
    tabla += '</tbody>';
    tabla += '</table>';
    $('#consultaTabla').html(tabla);
}
function cargarUnidadMedida(){
    $.ajax({
        async:false,
        url:localStorage.modulo  + "controlador/producto.cargarUnidadMedida.php"
        ,type:'POST'
        ,dataType:"json"
        ,data:null
        ,success: function(json){
            control = $('#selUnidadMedida');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data,function(contador,fila){
                control.append('<option value="' + fila.idUnidadMedida+'">' + fila.unidadMedida+ '</option>');
            });
        }
    });	
}
function cargarLinea(){
    $.ajax({
        async:false,
        url: localStorage.modulo  + "controlador/producto.cargarLinea.php",
        type:'POST',
        dataType:"html",
        data:null,
        success: function(html){
            bootbox.alert(html);
        }
    });
}
function asignar(lineaProducto, idLinea){
    idLineaProducto = idLinea;
    $("#txtLinea").val(lineaProducto);
}
function cargarBodegas(){   
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
        data:null,
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
                var control = $("#selBodega");
                control.empty();
                return false;
            }
            var control = $("#selBodega");
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
        },error: function(xhr, opciones, error){
            alerta(error);
        }
    });
}
function autoCompletarProducto() {
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarProducto.php',
        select: function (event, ui) {
            idProducto = ui.item.idProducto;
        }
    });
}