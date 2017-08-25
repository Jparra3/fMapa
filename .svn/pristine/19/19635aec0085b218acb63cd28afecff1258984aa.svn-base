var idProducto = null;
var codigo = null;
var producto = null;
var idUnidadMedida = null;
var idLineaProducto = null;
var productoComposicion = null;
var estado = null;

var data = null;
$(function(){
    cargarUnidadMedida();
    cargarSelectSiNo($("#selProductoCompuesto"));
    obtenerSelectEstadoSeleccione($("#selEstado"));
    cargarTabla();
    
    $("#imgNuevo").bind({
        "click":function(){
            validaLogueo();
            abrirVentana(localStorage.modulo + 'vista/frmProducto.html');
        }

    });

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
                url: localStorage.modulo + "controlador/producto.consultar.php",
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
    productoComposicion = null;
    idLineaProducto = null;
    estado = null;

    data = null;
}

function asignarDatosEnvio(){
    producto = $("#txtProducto").val();
    codigo = $("#txtCodigoProducto").val();
    idUnidadMedida  = $("#selUnidadMedida").val();
    productoComposicion = $("#selProductoCompuesto").val();
    estado = $("#selEstado").val();
}

function obtenerDatosEnvio(){
    asignarDatosEnvio();
    data = 'codigoProducto=' + codigo + '&producto=' + producto + '&idUnidadMedida='+ idUnidadMedida + '&idLineaProducto=' + idLineaProducto + '&productoComposicion=' + productoComposicion + '&estado=' + estado;
}
function cargarTabla(){
    var tabla = '<tr>';
    tabla += '<th><p>#</p></th>';
    tabla += '<th><p>Código</p></th>';
    tabla += '<th><p>Producto</p></th>';
    tabla += '<th><p>Unidad Medida</p></th>';
    tabla += '<th><p>Linea Producto</p></th>';
    tabla += '<th><p>Valor Entrada</p></th>';
    tabla += '<th><p>Valor Salida</p></th>';
    tabla += '<th><p>Compuesto</p></th>';
    tabla += '<th><p>Estado</p></th>';
    tabla += '<th colspan="2"><p>Acción</p></th>';
    tabla += '</tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>'
    $('#consultaTabla').html(tabla);
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '<thead>';
    tabla += '<tr id="trEstatico">';
    tabla += '<th><p>#</p></th>';
    tabla += '<th><p>Código</p></th>';
    tabla += '<th><p>Producto</p></th>';
    tabla += '<th><p>Unidad Medida</p></th>';
    tabla += '<th><p>Linea Producto</p></th>';
    tabla += '<th><p>Valor Entrada</p></th>';
    tabla += '<th><p>Valor Salida</p></th>';
    tabla += '<th><p>Compuesto</p></th>';
    tabla += '<th><p>Estado</p></th>';
    tabla += '<th colspan="2"><p>Acción</p></th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';
   $.each(json.data,function(contador, fila){
        tabla += '<tr>';
        tabla += '<td align="center">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.codigo + '</td>';
        tabla += '<td>' + fila.producto + '</td>';
        tabla += '<td>' + fila.unidadMedida + '</td>';
        tabla += '<td>' + fila.lineaProducto + '</td>';
        
        if(fila.valorEntrada != null && fila.valorEntrada != "null" && fila.valorEntrada != ""){
            tabla += '<td align="right">' + numberFormat(fila.valorEntradaMostrar) +'</td>';
        }else{
            tabla += '<td>&nbsp;</td>';
        }
        
        if(fila.valorSalida != null && fila.valorSalida != "null" && fila.valorSalida != ""){
            tabla += '<td align="right">' + numberFormat(fila.valorSalidaMostrar) +'</td>';
        }else{
            tabla += '<td>&nbsp;</td>';
        }
        
        if(fila.productoComposicion == true){
            tabla += '<td align="center">SI</td>';
        }else{
            tabla += '<td align="center">NO</td>';
        }
        
        if(fila.estado == true){
            tabla += '<td align="center">ACTIVO</td>';
        }else{
            tabla += '<td align="center">INACTIVO</td>';
        }
        
        tabla += '<td align="center" class="accionesTabla"><span id="imgModificar' + contador + '" class="fa fa-pencil imagenesTabla" title="Modificar" onclick="realizarBusqueda('+fila.idProducto+')"></td></span>';
        tabla += '<td align="center" class="accionesTabla"><span id="imgInactivar' + contador + '" class="fa fa-minus imagenesTabla" title="Inactivar" onclick="inactivar('+fila.idProducto+')"></td></span>';
        tabla += '</tr>';
    });
    tabla += '</tbody>';
    tabla += '</table>';
    $('#consultaTabla').html(tabla);
    paginacion("consultaTabla");
    $("#consultaTabla").tablesorter();
}
function realizarBusqueda(id){
    abrirVentana(localStorage.modulo + 'vista/frmProducto.html', id);
}
function inactivar(id){
    bootbox.confirm("¿Está seguro de inactivar el producto?", function(result) {
        if(result == true){
            data = 'idProducto='+id;
            $.ajax({
                async:false,
                url: localStorage.modulo +  "controlador/producto.inactivar.php",
                type:'POST',
                dataType:"json",
                data:data,
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;

                    if (exito == 0){
                        return false; 
                    }

                    alerta(mensaje);
                    $("#imgConsultar").click();
                },error: function(xhr, opciones, error){
                    alerta(error);
                }
            });	
        }
    }); 
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
function cargarSelectSiNo(control){
    control.empty();
    control.append('<option value="">---</option>');
    control.append('<option value="true">SI</option>');
    control.append('<option value="false">NO</option>');
}