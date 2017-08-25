var valorRecibido = variableUrl();

var idClienteServicio = valorRecibido[0];
var idOrdenTrabajoCliente = valorRecibido[1];
var idOrdenTrabajo = null;
var idCliente = null;
var idProductoComposicion = null;
var idTransaccion = null;
var valorClienteServicio = 0;
var tipo = valorRecibido[2];
var manejaArchivos = valorRecibido[3];
var productosServicio = [];
var productosDevolver = [];
var arrInformacionArchivos = [];
var arrIdElimArchivos = [];

var arrProducServ = [];

var data = null;

$(function(){
    cargarListado();
    cargarListadoArchivos();
    deshabilitarCampos();
    
    if(manejaArchivos == 1){//Manejo de archivos
        $("#linkProductos").hide();//Tab para ingresar la información de los productos
        $("#spnTituloFormulario").html("Archivos Servicio");
        consultarInformacionArchivosServicio();
    }else{
        $("#linkArchivos").hide();//Tab para adicionar archivos
        $("#spnTituloFormulario").html("Servicios de clientes");
    }
    
    if(idClienteServicio != '' && idClienteServicio != 'null' && idClienteServicio != null && idClienteServicio != 0){
        $.ajax({
            async:false,
            url: localStorage.modulo + 'controlador/clienteServicio.consultar.php',
            type:'POST',
            dataType:"json",
            data:{idClienteServicio:idClienteServicio},
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
                
                $('#divInformacionServicio').html("");
                var tablaInformacion = "";
                tablaInformacion += '<table class="table table-bordered table-striped consultaTabla">';
                $.each(json.data, function(contador, fila){
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<th colspan="2">Información Servicio</th>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Cliente</b></td>';
                    tablaInformacion += '<td>'+fila.cliente+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Tipo Documento</b></td>';
                    tablaInformacion += '<td>'+fila.tipoDocumento+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Nro Documento</b></td>';
                    tablaInformacion += '<td>'+fila.numero+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Fecha Orden Trabajo</b></td>';
                    tablaInformacion += '<td>'+fila.fecha+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Fecha Inicio Servicio</b></td>';
                    tablaInformacion += '<td>'+fila.fechaInicial+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Fecha Final Servicio</b></td>';
                    
                    if(fila.fechaFinal == "" || fila.fechaFinal == null){
                        tablaInformacion += '<td></td>';
                    }else{
                        tablaInformacion += '<td>'+fila.fechaFinal+'</td>';
                    }
                    
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Ordenador</b></td>';
                    tablaInformacion += '<td>'+fila.ordenador+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Municipio</b></td>';
                    tablaInformacion += '<td>'+fila.municipio+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Estado Orden Trabajo</b></td>';
                    tablaInformacion += '<td>'+fila.estadoOrdenTrabajo+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Tipo</b></td>';
                    tablaInformacion += '<td>'+fila.tipo+'</td>';
                    tablaInformacion += '</tr>';
                    
                    idCliente = fila.idCliente;
                    idProductoComposicion = fila.idProductoComposicion;
                    idOrdenTrabajo = fila.idOrdenTrabajo;
                    
                    
                    consultarOrdenTrabajoTecnico(fila.idOrdenTrabajo);
                    consultarClienteServicioProducto();
                    
                    //Consultar los productos de mantenimiento
                    consultarOrdenTrabajoProducto(idOrdenTrabajoCliente,"Productos de mantenimiento");
                    
                    $("#txtFechaInicio").val(fila.fechaInicial);
                    
                    
                });
                tablaInformacion += '</table>';
                $('#divInformacionServicio').html(tablaInformacion);
                
            },error: function(xhr, opciones, error){
                alerta (error);
                return false;
            }
        });
    }else{
        $.ajax({
            async:false,
            url: localStorage.modulo +'controlador/ordenTrabajoCliente.consultar.php',
            type:'POST',
            dataType:"json",
            data:{
                    idOrdenTrabajoCliente:idOrdenTrabajoCliente
                },
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
                
                $('#divInformacionServicio').html("");
                var tablaInformacion = "";
                tablaInformacion += '<table class="table table-bordered table-striped consultaTabla">';
                $.each(json.data, function(contador, fila){
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<th colspan="2">Información Orden Trabajo</th>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Cliente</b></td>';
                    tablaInformacion += '<td>'+fila.cliente+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Tipo Documento</b></td>';
                    tablaInformacion += '<td>'+fila.tipoDocumento+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Nro Documento</b></td>';
                    tablaInformacion += '<td>'+fila.numero+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Fecha Orden Trabajo</b></td>';
                    tablaInformacion += '<td>'+fila.fecha+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Fecha Inicio Orden Trabajo</b></td>';
                    tablaInformacion += '<td>'+fila.fechaInicio+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Fecha Fin Orden Trabajo</b></td>';
                    tablaInformacion += '<td>'+fila.fechaFin+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Ordenador</b></td>';
                    tablaInformacion += '<td>'+fila.ordenador+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Municipio</b></td>';
                    tablaInformacion += '<td>'+fila.municipio+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Estado Orden Trabajo</b></td>';
                    tablaInformacion += '<td>'+fila.estadoOrdenTrabajo+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Tipo</b></td>';
                    tablaInformacion += '<td>'+fila.tipo+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Servicio</b></td>';
                    tablaInformacion += '<td>'+fila.productoComposicion+'</td>';
                    tablaInformacion += '</tr>';
                    tablaInformacion += '<tr>';
                    tablaInformacion += '<td><b>Valor Servicio</b></td>';
                    tablaInformacion += '<td>'+agregarSeparadorMil(parseInt(fila.valorSalidConImpue).toString())+'</td>';
                    tablaInformacion += '</tr>';
                    
                    idCliente = fila.idCliente;
                    idProductoComposicion = fila.idProductoComposicion;
                    idOrdenTrabajo = fila.idOrdenTrabajo;
                    valorClienteServicio =  fila.valorSalidConImpue;
                    
                    
                    consultarOrdenTrabajoTecnico(fila.idOrdenTrabajo);
                    consultarOrdenTrabajoProducto(idOrdenTrabajoCliente, "Productos a instalar");
                    
                });
                tablaInformacion += '</table>';
                $('#divInformacionServicio').html(tablaInformacion);
                $("#txtFechaInicio").val(obtenerFechaActual());
                
            },error: function(xhr, opciones, error){
                alerta (error);
                return false;
            }
        });
    }
    
    
    $("#imgAdicionarArchivo").click(function(){
        if(validarVacios(document.frmInformacionArchivos) == false)
            return false; 
            var rutaArchivo = subirArchivoTemporal();
            
            var objInformacionArchivo = new Object();
            objInformacionArchivo.idClienteServicioArchivo = null;
            objInformacionArchivo.etiqueta = $("#txtEtiqueta").val();
            objInformacionArchivo.observacion = $("#txaObservacion").val();
            objInformacionArchivo.rutaArchivo = rutaArchivo;
            
            arrInformacionArchivos.push(objInformacionArchivo);
            
            crearListadoArchivos();
            
            limpiarControlesFormulario(document.frmInformacionArchivos);
            
    });
    
    $('#imgGuardar').bind({
        'click':function(){
            
            
            if(manejaArchivos == 1){//Manejo de archivos   
                
                if(arrInformacionArchivos.length == 0){
                    alerta("Debe adicionar mínimo un archivo");
                    return false;
                }
                
                eliminarArchivosServicio();
                adicionarArchivosServicio();
                
                alerta("Los archivos vinculados a la orden del cliente se adicionaron correctamente ",true);
            }else{
                productosServicio = [];
                productosDevolver = [];
                productosServicioProducto = [];


                $.each($('input[name="txtCantidadInstalada"]'),function(contador, fila){

                    //Se valida si instalo algún producto
                    if(fila.value > 0){
                        var objetoInstalar = new Object();
                        objetoInstalar.cantidad = fila.value;
                        objetoInstalar.idOrdenTrabajoProducto = fila.id;
                        objetoInstalar.nota = $("#txaNota" + fila.id).val();
                        objetoInstalar.tipo = "Producto";
                        productosServicio.push(objetoInstalar);
                    }

                    //Se valida si va a devolver productos
                    if(parseInt(fila.value) < parseInt(fila.accessKey)){
                        var cantidadDevolver = parseInt(fila.accessKey) - parseInt(fila.value);
                        var objetoDevolver = new Object();
                        objetoDevolver.cantidad = cantidadDevolver;
                        objetoDevolver.idOrdenTrabajoProducto = fila.id;
                        objetoDevolver.nota = $("#txaNota" + fila.id).val();
                        objetoDevolver.tipo = "Producto";
                        productosDevolver.push(objetoDevolver);
                    }
                });


                //Se evaluan los productos a desinstalar
                $.each($('input[name="txtCantidadDesintalar"]'),function(contador, fila){

                    var cantidadDevolver = 0;
                    var cantidadDesinstalar = 0;
                    if($("#txtCantidadDevolver" + fila.id).val() != "" && $("#txtCantidadDevolver" + fila.id).val() != null){
                        cantidadDevolver = parseInt($("#txtCantidadDevolver" + fila.id).val()); 
                        cantidadDesinstalar = fila.value;
                    }

                    var objetoServicioProducto = new Object();
                    objetoServicioProducto.cantidad = (fila.accessKey - cantidadDesinstalar);
                    objetoServicioProducto.idClienteServicioProducto = fila.id;
                    objetoServicioProducto.tipo = "Servicio";
                    objetoServicioProducto.nota = $("#txaNota" + fila.id).val();


                    productosServicioProducto.push(objetoServicioProducto);

                    //Se valida si se devuelve algún producto
                    if(cantidadDesinstalar > 0){
                        var objetoDevolver = new Object();
                        objetoDevolver.cantidad = cantidadDesinstalar;
                        objetoDevolver.idClienteServicioProducto = fila.id;
                        objetoDevolver.nota = $("#txaNota" + fila.id).val();
                        objetoDevolver.tipo = "Servicio";
                        productosDevolver.push(objetoDevolver);
                    }
                });



                if(idClienteServicio != '' && idClienteServicio != 'null' && idClienteServicio != null && idClienteServicio != 0){
                    modifCantiProducServic();

                    if(productosDevolver.length > 0){
                        adicionarTransaccion();
                        adicionarProductosTransaccion("Servicio");
                    }

                    if(productosServicio.length > 0){
                        adicionarProductoServicio();
                    }

                    if(tipo == 3){//Desinstalación
                        actualizarEstadoClienteServicio();
                    }
                    
                    actualizarEstadoOrdenTrabajo();

                    alerta("El servicio se ha modificado correctamente.",true);
                }else{

                    try {
                        if(productosDevolver.length > 0){
                            adicionarTransaccion();
                            adicionarProductosTransaccion("Producto");
                        }
                        adicionarServicio();
                        adicionarProductoServicio();
                        
                        alerta("El servicio se ha adicionado correctamente.",true);
                    }
                    catch(error) {
                        alerta("Error al adicionar la información => " + error);
                    }
                }
                
            }
        }
    });
});

function deshabilitarCampos(){
    $('#txtCliente, #txtFechaOrden, #selTipoDocumento, #txtNoOrdenTrabajo, #selOrdenador, #txtMunicipio, #txtFechaInicio, #txtFechaFin').prop('disabled', true);
}
function cargarListado(){
    $('#tblProductos').html("");
    var table = '';
    table += '<tr id="trEstatico">';
    table += '<th rowspan="2">Código Producto</th>';
    table += '<th rowspan="2">Producto</th>';
    table += '<th colspan="6">Productos que Componen</th>';
    table += '</tr>';
    table += '<tr id="trEstatico">';
    table += '<th>Código Producto</th>';
    table += '<th>Producto</th>';
    table += '<th>Cantidad</th>';
    table += '<th>Unidad Medida</th>';
    table += '<th style="width: 80px;">Cantidad a Devolver</th>';
    table += '<th>SI / NO</th>';
    table += '</tr>';
    table += '<tr>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '</tr>';
    $('#tblProductos').html(table);
}
function consultarOrdenTrabajoTecnico(id){
    $.ajax({
        async:false,
        url:'../controlador/ordenTrabajoTecnico.consultar.php',
        type:'POST',
        dataType:"json",
        data:{idOrdenTrabajo:id},
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
            
            $('#divInformacionTecnico').html("");
            var tablaInformacion = "";
            tablaInformacion += '<table class="table table-bordered table-striped consultaTabla" style="margin-top: -4%;">';
            $.each(json.data, function(contador, fila){
                tablaInformacion += '<tr>';
                tablaInformacion += '<th colspan="4">Información Técnico</th>';
                tablaInformacion += '</tr>';
                
                var principal = "";
                if(fila.principal == true){
                    principal = "SI";
                }else{
                    principal = "NO";
                }
                tablaInformacion += '<tr>';
                tablaInformacion += '<td><b>Técnico</b></td>';
                tablaInformacion += '<td>'+fila.tecnico+'</td>';
                tablaInformacion += '<td><b>Principal</b></td>';
                tablaInformacion += '<td>'+principal+'</td>';
                tablaInformacion += '</tr>';
            });
            tablaInformacion += '</table>';
            $('#divInformacionTecnico').html(tablaInformacion);
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function consultarOrdenTrabajoProducto(id,titulo){
    $.ajax({
        async:false,
        url:'../controlador/ordenTrabajoProducto.consultar.php',
        type:'POST',
        dataType:"json",
        data:{
                idOrdenTrabajoCliente:id
                ,estado:true
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }

            if(json.numeroRegistros == 0){
                $('#tblProductos').html("");
                return false;
            }
            var table = '';
            var idProductoComposicion = 0;
            
            
            $('#spnTituloProductos').html("<h3 style='text-align: center;'>" +  titulo + "</h3>");
            $('#tblProductos').html("");
            var table = '';
            table += '<tr id="trEstatico">';
            table += '<th rowspan="2">Código Producto</th>';
            table += '<th rowspan="2">Producto</th>';
            table += '<th colspan="8">Productos que Componen</th>';
            table += '</tr>';
            table += '<tr id="trEstatico">';
            table += '<th>Código Producto</th>';
            table += '<th>Producto</th>';
            table += '<th>Cantidad</th>';
            table += '<th>Unidad Medida</th>';
            table += '<th>Serial</th>';
            table += '<th>Nota</th>';
            table += '<th style="width: 80px;">Cantidad Instalada</th>';
            table += '<th style="width: 80px;">Cantidad Devolver</th>';
            table += '</tr>';
            
            $.each(json.data, function(contador, fila){
                table += '<tr>';
                
                if(idProductoComposicion != fila.idProductoComposicion){
                    idProductoComposicion = fila.idProductoComposicion;
                    table += '<td rowspan="'+fila.cantidadProductoCompone+'" align="right">'+fila.codigoProductoComposicion+'</td>';
                    table += '<td rowspan="'+fila.cantidadProductoCompone+'">'+fila.productoComposicion+'</td>';
                }
                
                table += '<td name="td'+fila.idOrdenTrabajoProducto+'">'+fila.codigoProductoCompone+'</td>';
                table += '<td name="td'+fila.idOrdenTrabajoProducto+'">'+fila.productoCompone+'</td>';
                table += '<td name="td'+fila.idOrdenTrabajoProducto+'" align="right">'+parseInt(fila.cantidad)+'</td>';
                table += '<td name="td'+fila.idOrdenTrabajoProducto+'">'+fila.unidadMedida+'</td>';
                
                if(fila.serial != "" &&  fila.serial != null &&  fila.serial != "null"){
                    table += '<td name="td'+fila.idOrdenTrabajoProducto+'">'+fila.serial+'</td>';
                }else{
                    table += '<td name="td'+fila.idOrdenTrabajoProducto+'"></td>';
                }
                
                var nota = fila.nota;
                if(nota == "" || nota == null ||  nota == "null"){
                    nota = "";
                }
 
                table += "<td><textarea id='txaNota" + fila.idOrdenTrabajoProducto + "' class='form-control' accesskey='V' placeholder='Nota' style='width: 150px; height: 33px;'>" + nota + "</textarea></td>";
                
                table += '<td name="td'+fila.idOrdenTrabajoProducto+'" align="center"><input type="text" id="'+fila.idOrdenTrabajoProducto+'" name="txtCantidadInstalada" class="form-control verySmall" onchange="validarCantidad(this);" accessKey="' + fila.cantidad + '" value="' + parseInt(fila.cantidad) + '"></td>';
                table += '<td name="td'+fila.idOrdenTrabajoProducto+'" align="center"><input type="text" id="spn'+fila.idOrdenTrabajoProducto+'" name="spnCantidadInstalada" disabled class="form-control verySmall"></span></td>';    
                table += '</tr>';
            });
            $('#tblProductos').html(table);
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function consultarTecnico(){
    $.ajax({
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarTecnico.php',
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
            
            var control = $('#selTecnico');
            var tabla = $('#tblPrincipalTecnico');
            control.empty();
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idTecnico + '">' + fila.tecnico + '</option>');
                tabla.append('<tr><td><input type="radio" id="rdoTecnico' + fila.idTecnico + '" name="rdoTecnico" value="' + fila.idTecnico + '" style="margin-bottom:2px"></tr></td>');
            });
            $("#selTecnico").attr("size",$("#selTecnico option").length);
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function validarCantidad(elemento){
    if(elemento.value == ''){
        elemento.value = "";
    }
    
    if($.isNumeric(elemento.value) == false || parseInt(elemento.value) > parseInt(elemento.accessKey)){
        alerta('Debe ingresar una cantidad válida');
        elemento.value = parseInt(elemento.accessKey);
        $("#spn" + elemento.id).val("");
        return false;
    }
    
    var cantidadTemporal = parseInt(elemento.accessKey) - parseInt(elemento.value);
    
    if(cantidadTemporal == 0){
        $("#spn" + elemento.id).val("");
    }else{
        $("#spn" + elemento.id).val(cantidadTemporal);
    }
}


function validarCantidadDesinstalar(elemento){
    if(elemento.value == ''){
        elemento.value = "";
    }
    
    if($.isNumeric(elemento.value) == false || parseInt(elemento.value) > parseInt(elemento.accessKey)){
        alerta('Debe ingresar una cantidad válida');
        elemento.value = parseInt(elemento.accessKey);
        $("#txtCantidadDevolver" + elemento.id).val("");
        return false;
    }
    
    var cantidadTemporal = parseInt(elemento.accessKey) - parseInt(elemento.value);
    
    if(cantidadTemporal == 0){
        $("#txtCantidadDevolver" + elemento.id).val(0);
    }else{
        $("#txtCantidadDevolver" + elemento.id).val(cantidadTemporal);
    }
}
function habilitarCampo(control, id){
    if(control.name == "V"){
        control.name = "L";
        control.src = "../imagenes/casilla_marcada.png";
        $('td[name="td'+id+'"]').attr('style', 'background: #E6E6E6');
        $('#'+id).prop('disabled', false).focus();
    }else{
        control.name = "V";
        control.src = "../imagenes/casilla_vacia.png";
        $('td[name="td'+id+'"]').attr('style', 'background: #FFFFFF');
        $('#'+id).prop('disabled', true).val('');
    }
}

/*
function habilitarCampo(control, id){
    var control = $(control);
    
    if(control.prop('checked') == true){
        $('td[name="td'+id+'"]').attr('style', 'background: #E6E6E6');
        $('#'+id).prop('disabled', false).focus();
    }else{
        $('td[name="td'+id+'"]').attr('style', 'background: #FFFFFF');
        $('#'+id).prop('disabled', true).val('');
    }
}
*/
function variableUrl(){
	var src = '';
	var cadenaPrueba = '';
	if(String( window.location.href ).split('?')[1]){
		src = String( window.location.href ).split('?')[1];
		src = src.replace("%C2%A0%C2%A0%C2%A0","");
		src = decodeURI(src);
		var srcDos = src.split('=');
		
		src = src.split('&');
		for(i=0; i < src.length; i++){
			src[i] = src[i].substring(src[i].indexOf('=')+1);
		}
		if(src[3] != '1'){
			cadena = src[0].indexOf(' ');	
			src[0] = src[0].substring(cadena+1);
		}
	}
	return src;
}
function adicionarServicio(){
    $.ajax({
                    async:false,
                    url:'../controlador/clienteServicio.adicionar.php',
                    type:'POST',
                    dataType:"json",
                    data:{
                            idCliente:idCliente
                            ,valor:valorClienteServicio
                            ,idProductoComposicion:idProductoComposicion
                            ,idOrdenTrabajoCliente:idOrdenTrabajoCliente
                            ,idTransaccion:idTransaccion
                            ,fechaInicial:$("#txtFechaInicio").val()
                        },
                    success: function(json){
                        var exito = json.exito;
                        var mensaje = json.mensaje;

                        if(exito == 0){
                            throw mensaje;
                        }
                        
                        idClienteServicio = json.idClienteServicio;
                    },error: function(xhr, opciones, error){
                        throw error;
                    }
                });
}
function adicionarProductoServicio(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/clienteServicio.adicionarProductos.php',
        type:'POST',
        dataType:"json",
        data:{
                idClienteServicio:idClienteServicio
                ,idOrdenTrabajoCliente:idOrdenTrabajoCliente
                ,producto:productosServicio
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                throw mensaje;
            }
        },error: function(xhr, opciones, error){
            throw error;
        }
    });
}
function modifCantiProducServic(){
    $.ajax({
        async:false,
        url:'../controlador/clienteServicio.modificarCantidadProducto.php',
        type:'POST',
        dataType:"json",
        data:{
                idClienteServicio:idClienteServicio
                ,producto:productosServicioProducto
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                throw mensaje;
            }
        },error: function(xhr, opciones, error){
            throw error;
        }
    });
}

function adicionarTransaccion(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicio.adicionarTransaccion.php',
        type:'POST',
        dataType:"json",
        data:{
                idCliente:idCliente
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }
            
            idTransaccion = json.idTransaccion;
                    
        }, error: function(xhr, opciones, error){
            throw error;
        }
    });
}
function adicionarProductosTransaccion(tipo){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicio.adicionarTransaccionProducto.php',
        type:'POST',
        dataType:"json",
        data:{
                idTransaccion:idTransaccion
                ,productos:productosDevolver
                ,tipo:tipo
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }        
        }, error: function(xhr, opciones, error){
            throw error;
        }
    });
}
function consultarClienteServicioProducto(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicioProducto.consultar.php',
        type:'POST',
        dataType:"json",
        data:{
                idClienteServicio:idClienteServicio
                ,estado:true
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }        
            
            $('#spnTituloDesinstalacion').html("<h3 style='text-align: center;'>Productos a desinstalar</h3>");
            $('#tblProductosDesinstalar').html("");
            var table = '';
            table += '<tr id="trEstatico">';
            table += '<th rowspan="2">Código Producto</th>';
            table += '<th rowspan="2">Producto</th>';
            table += '<th colspan="9">Productos que Componen</th>';
            table += '</tr>';
            table += '<tr id="trEstatico">';
            table += '<th>Código Producto</th>';
            table += '<th>Producto</th>';
            table += '<th>Cantidad</th>';
            table += '<th>Unidad Medida</th>';
            table += '<th>Serial</th>';
            table += '<th>Nota</th>';
            table += '<th style="width: 80px;">Cantidad Desinstalar</th>';
            table += '<th style="width: 80px;">Cantidad Sobrante</th>';
            table += '<th style="width: 50px;">Desinstalar</th>';
            table += '</tr>';
            
            idProductoComposicion = null;
            
            $.each(json.data, function(contador, fila){
                
                idClienteServicio = fila.idClienteServicio;
                
                table += '<tr>';
                if(idProductoComposicion != fila.idProductoComposicion){
                    idProductoComposicion = fila.idProductoComposicion;
                    table += '<td rowspan="'+fila.cantidadProductoCompone+'" align="right">'+fila.codigoProductoComposicion+'</td>';
                    table += '<td rowspan="'+fila.cantidadProductoCompone+'">'+fila.productoComposicion+'</td>';
                }
                table += '<td name="td'+fila.idClienteServicioProducto+'">'+fila.codigoProductoCompone+'</td>';
                table += '<td name="td'+fila.idClienteServicioProducto+'">'+fila.productoCompone+'</td>';
                table += '<td name="td'+fila.idClienteServicioProducto+'" align="right">'+parseInt(fila.cantidad)+'</td>';
                table += '<td name="td'+fila.idClienteServicioProducto+'">'+fila.unidadMedida+'</td>';
                
                if(fila.serial != "" &&  fila.serial != null &&  fila.serial != "null"){
                    table += '<td name="td'+fila.idClienteServicioProducto+'">'+fila.serial+'</td>';
                }else{
                    table += '<td name="td'+fila.idClienteServicioProducto+'"></td>';
                }
                
                var nota = fila.nota;
                if(nota == "" || nota == null ||  nota == "null"){
                    nota = "";
                }
                
                table += "<td><textarea id='txaNota" + fila.idClienteServicioProducto + "' class='form-control' accesskey='V' placeholder='Nota' style='width: 150px; height: 33px;'>" + nota + "</textarea></td>";
                
                table += '<td name="td'+fila.idClienteServicioProducto+'" align="center"><input type="text" id="'+fila.idClienteServicioProducto+'" name="txtCantidadDesintalar" class="form-control verySmall" onchange="validarCantidadDesinstalar(this);" accessKey="' + fila.cantidad + '" disabled></td>';
                table += '<td name="td'+fila.idClienteServicioProducto+'" align="center"><input type="text" id="txtCantidadDevolver'+fila.idClienteServicioProducto+'" name="txtCantidadDevolver" class="form-control verySmall"  accessKey="' + fila.cantidad + '" disabled></td>';
                table += '<td name="td'+fila.idClienteServicioProducto+'" align="center"><img id="img'+fila.idClienteServicioProducto+'" src="../imagenes/casilla_vacia.png" style="width: 30px;cursor:pointer" onclick="habilitarCampo(this,\''+fila.idClienteServicioProducto+'\');" name="V"></td>';    
                table += '</tr>';
            });
            $('#tblProductosDesinstalar').html(table);
            
        }, error: function(xhr, opciones, error){
            throw error;
        }
    });
}
function actualizarEstadoClienteServicio(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicio.actualizarEstado.php',
        type:'POST',
        dataType:"json",
        data:{
                idClienteServicio:idClienteServicio
                ,idEstadoClienteServicio:2//Desinstalación
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }        
        }, error: function(xhr, opciones, error){
            throw error;
        }
    });
}
function obtenerFechaActual(){	
    var f = new Date();
    var dia = f.getDate();
    var mes = f.getMonth() +1;
    var anio = f.getFullYear();

    if(dia.toString().length<2){
            dia = "0" + dia.toString();
    }
    if(mes.toString().length <2){
            mes = "0" + mes.toString();
    }

    var fecha =  anio + "-" + mes + "-" + dia;
    //return new Date().toJSON().slice(0,10)
    return fecha;
}
function cargarListadoArchivos(){
    $('#divInformacionArchivos').html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>Etiqueta</th>';
    tabla += '<th>Observación</th>';
    tabla += '<th>Archivo</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $('#divInformacionArchivos').html(tabla);
}
function subirArchivoTemporal(){
    var rutaArchivo = null;
    if($("#fleArchivo").val() == ""){
        alerta("Por favor escoja un documento.");
        return false;
    }

    var archivos = document.getElementById("fleArchivo");
    var archivo = archivos.files; 
    var data = new FormData();
    for(i=0; i < archivo.length; i++){
        data.append('archivo'+i,archivo[i]);
    }
    
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicio.subirArchivoTemporal.php', 
        type:'POST', 
        dataType:"json",
        contentType:false, 
        data:data,
        processData:false, 
        cache:false,
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            rutaArchivo = json.rutaArchivo;
        } 
    });
    return rutaArchivo;
}
function crearListadoArchivos(){
    $('#divInformacionArchivos').html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>Etiqueta</th>';
    tabla += '<th style="width:60%;">Observación</th>';
    tabla += '<th style="width:5%;">Archivo</th>';
    tabla += '<th style="width:5%;">Acción</th>';
    tabla += '</tr>';
    
    if(arrInformacionArchivos.length == 0){
        tabla += '<tr>';
        tabla += '<td>&nbsp;</td>';
        tabla += '<td>&nbsp;</td>';
        tabla += '<td>&nbsp;</td>';
        tabla += '<td>&nbsp;</td>';
        tabla += '</tr>';
    }else{
        $.each(arrInformacionArchivos, function(indice, fila){
            tabla += '<tr>';
            tabla += '<td>' + fila.etiqueta + '</td>';
            tabla += '<td>' + fila.observacion + '</td>';
            tabla += '<td style="text-align:center"><a href="' + fila.rutaArchivo + '" target="_blank"><span class="fa fa-file"></span></a></td>';
            tabla += '<td style="text-align:center"><span class="fa fa-trash imagenesTabla" onclick="eliminarArchivo(' + indice + ')"></span></td>';
            tabla += '</tr>';
        });
    }
    tabla += '</table>';
    $('#divInformacionArchivos').html(tabla);
}
function eliminarArchivo(indice){
    var objEliminar = arrInformacionArchivos[indice];
    if(objEliminar.idClienteServicioArchivo != "" && objEliminar.idClienteServicioArchivo != null && objEliminar.idClienteServicioArchivo != "null"){
        arrIdElimArchivos.push(objEliminar.idClienteServicioArchivo)
    }
    arrInformacionArchivos.splice(indice,1);
    arrInformacionArchivos.filter(Boolean);
    crearListadoArchivos();
}
function adicionarArchivosServicio(){
    
    if(idClienteServicio == "" || idClienteServicio == null || idClienteServicio == "null" || idClienteServicio == 0){
        idClienteServicio = "";
    }
    
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicioArchivo.adicionar.php', 
        type:'POST', 
        dataType:"json",
        data:{
                idClienteServicio:idClienteServicio
                ,idOrdenTrabajo:idOrdenTrabajo
                ,idOrdenTrabajoCliente:idOrdenTrabajoCliente
                ,informacionArchivos:arrInformacionArchivos
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
        } 
    });
}
function consultarInformacionArchivosServicio(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicioArchivo.consultar.php', 
        type:'POST', 
        dataType:"json",
        data:{
                idClienteServicio:idClienteServicio
                ,idOrdenTrabajoCliente:idOrdenTrabajoCliente
                ,estado:true
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                cargarListado();
                return false
            }
            
            $.each(json.data,function(contador,fila){
                var objInformacionArchivo = new Object();
                objInformacionArchivo.idClienteServicioArchivo = fila.idClienteServicioArchivo;
                objInformacionArchivo.etiqueta = fila.etiqueta;
                objInformacionArchivo.observacion = fila.observacion;
                objInformacionArchivo.rutaArchivo = fila.ruta;
                
                arrInformacionArchivos.push(objInformacionArchivo);
            });
            crearListadoArchivos();
            
        } 
    });
}
function eliminarArchivosServicio(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/clienteServicioArchivo.eliminar.php', 
        type:'POST', 
        dataType:"json",
        data:{
                informacionEliminarArchivos:arrIdElimArchivos
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
        } 
    });
}
function actualizarEstadoOrdenTrabajo(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.actualizarEstadoServicio.php',
        type:'POST',
        dataType:"json",
        data:{
                idClienteServicio:idClienteServicio
                ,idEstadoOrdenTrabajo:3//Instalado
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }        
        }, error: function(xhr, opciones, error){
            throw error;
        }
    });
}