var idVendedor = null;
var idZona = null;
var numeroDocumento = null;

var data = null;
$(function(){
	autocompleteNombre();
        validarNumeros("txtNumeroDocumento");
	cargarListado();
        cargarZonas();
	$("#imgNuevo").click(function(){
            abrirVentana(localStorage.modulo + 'vista/frmVendedor.html','');
        });
	$("#imgConsultar").click(function(){
		obtenerDatosEnvio();
		
		numeroControlesVacios = validarVaciosConsultar(document.fbeFormulario);
			
		if(numeroControlesVacios == document.fbeFormulario.length){
                    alerta("Debe ingresar al menos un parámetro de búsqueda.");
                    return false;
		}
		$.ajax({
                    url: localStorage.modulo + 'controlador/vendedor.consultar.php',
                    type:'POST',
                    dataType:"json",
                    data:data,
                    success: function(json){
                            var exito = json.exito;
                            var mensaje = json.mensaje;
                            var numeroRegistros = json.numeroRegistros;

                            if(exito == 0){
                                alerta(mensaje);
                                return false;
                            }
                            if(json.numeroRegistros == 0){
                                alerta("No se encontraron registros con los parámetros indicados.");
                                cargarListado();
                                return false;
                            }
                            crearListado(json);

                            localStorage.numeroRegistros = json.numeroRegistros;//Almaceno el número de registros para saber cuantas debo ocultar
                            obtenerPermisosFormulario(localStorage.formulario);//Habilitar las acciones a las que tenga permiso
                    }
		});
	});
	$("#imgLimpiar").click(function(){
		limpiarControlesFormulario(document.fbeFormulario);
		limpiarVariables();
		cargarListado();
	});
});
function asignarValoresEnvio(){
    idZona = $("#selZona").val();
    numeroDocumento = $("#txtNumeroDocumento").val();
}
function obtenerDatosEnvio(){
    asignarValoresEnvio();
    data = 'idVendedor=' + idVendedor + '&idZona=' + idZona + '&numeroDocumento=' + numeroDocumento;
}
function autocompleteNombre(){
    $("#txtNombre").autocomplete({
        source: localStorage.modulo + 'ajax/vendedor.autocompletar.php',
        select:function(event, ui){
            idVendedor = ui.item.idVendedor;
        }
    });
}

function cargarListado(){
	$("#consultaTabla").html("");
	var tabla = '<table>';
	tabla += '<tr>';
        tabla += '<th>Número Documento</th>';
        tabla += '<th>Nombre</th>';
        tabla += '<th>Zona</th>';
	tabla += '<th>Celular</th>';
	tabla += '<th>Correo</th>';
	tabla += '<th>Dirección</th>';
	tabla += '<th>Acción</th>';
	tabla += '</tr>';
	tabla += '<tr>';
	tabla += '<td>&nbsp;</td>';
        tabla += '<td>&nbsp;</td>';
        tabla += '<td>&nbsp;</td>';
	tabla += '<td>&nbsp;</td>';
	tabla += '<td>&nbsp;</td>';
	tabla += '<td>&nbsp;</td>';
	tabla += '<td>&nbsp;</td>';
	tabla += '</tr>';
	tabla += '</table>';
	$("#consultaTabla").html(tabla);
}
function crearListado(json){
    $("#consultaTabla").html("");
    var tabla = '<table class="consultaTabla">';
    tabla += '<thead>';
    tabla += '<tr id="trEstatico">';
    tabla += '<th> # </th>';
    tabla += '<th># identificación - Nombre completo</th>';
    tabla += '<th>Zona</th>';
    tabla += '<th>Celular</th>';
    tabla += '<th>Correo</th>';
    tabla += '<th>Dirección</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';
    $.each(json.data, function(contador,fila){
            tabla += '<tr>';
            tabla += '<td class="valorFijo">'+parseInt(contador+1)+'</td>';
            tabla += '<td>'+fila.documentoIdentidad+' - '+fila.nombreCompleto+'</td>';
            tabla += '<td>'+fila.zona+'</td>';
            tabla += '<td class="valorNumerico">'+fila.celular+'</td>';
            tabla += '<td>'+fila.correoElectronico+'</td>';
            tabla += '<td>'+fila.direccion+'</td>';
            tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgModificar' + contador + '" title="Editar" class="imagenesTabla" onclick="abrirVentana(' + "'" + localStorage.modulo + 'vista/frmVendedor.html' + "'" + ',' + fila.idVendedor +')"></span></td>';
            tabla += '</tr>';
    });
    tabla += '</tbody>';
   tabla += '</table>';
   $("#consultaTabla").html(tabla);
   paginacion("consultaTabla");
   $("#consultaTabla").tablesorter();
}
function limpiarVariables(){
    idVendedor = '';
    codigoVendedor = '';
    numeroDocumento = '';

    data = '';
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
            var control = $('#selZona');
            control.empty();
            control.append("<option value=''> --Seleccione-- </option>");
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idZona + '">' + fila.zonaRegional + '</option>');
            });

            $('#selZona').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });
            $("button.multiselect").css("width", "300");
            $("button.multiselect").attr("accesskey", "V");
            $("input.form-control.multiselect-search").attr("accesskey", "V");

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}