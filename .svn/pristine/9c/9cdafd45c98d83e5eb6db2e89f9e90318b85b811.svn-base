/*Variables Orden Trabajo*/
var valorRecibido = variableUrl();
var idOrdenTrabajo = valorRecibido[0];
var tipo = valorRecibido[1];
var idPedidos = null;
var idClienteServicio = null;
var envioInformacion = false;



var fecha = null;
var idEstadoOrdenTrabajo = null;
var idTipoDocumento = null;
var numeroOrden = null;
var idOrdenador = null;
var idMunicipio = null;
var fechaInicio = null;
var fechaFin = null;
var idTecnico = null;
var tipoOrdenTrabajo = null;
var mensajeServicio = "";
var observacion = null;

var data = null;

/*Variables Orden Trabajo Vehiculo*/
var arrOrdenTrabajVehic =  new Array();

/*Variables Orden Trabajo Producto*/
var idProducto = null;
var idProductoCompuesto = null;
var idProductoComposicion = null;
var secuencialProducto = 0;
var arrObjOrdenProducto =  new Array();
var idProductosEvaluar = null;
var arrIdProductoSerial = new Array();
var arrIdSelecSerial = new Array();
var arrIdElimOrdeTrabProd = new Array();
var arrSerialSelecc = new Array();
var arrBodegas = new Array();

var idOrdenTrabajoCliente = null;/*Variables Orden Trabajo Cliente*/

var arrOrdenTrabajClient =  new Array();
var arrElimOrdenTrabajClien = new Array();
var idPedido = null;
var idCliente = null;
var posicionEditar = null;
var editarServicioBand = false;
var secuenciaCliente = 0;
var arrIdElimOrdeTrabClie = new Array();

/*Variables Orden Trabajo Cliente*/
var tecnicoPrincipal = null;
var arrOrdenTrabajTecni = new Array();
var arrOpcioProduSerial = new Array();
var idTransaccion = null;

var permiteInventarioNegativo = null;


/*Variables Producto*/
var idProducto = null;
var productoSerial = null;
var cantidad = null;
var valorUnitario = null;
var nota = null;
var arrProductoComposicion = new Array(); 

/*Variables por defecto*/
var idEstaOrdeTrabProduDef = 1;
var estaOrdeTrabProdDefe = "Por Entregar";

var arrHistoProduInfor = new Array();

$(function(){
    $("#pnlInforProduCompo").css("display","none");
    $("#linkServicioInstalado").css("display","none");
    $("#selEstadoOrdenTrabajo").attr("disabled",true);
    cargarListadoTecnico();
    
    crearCalendario("txtFechaOrden");
    crearCalendario("txtFechaInicio");
    crearCalendario("txtFechaFin");
    
    cargarTipoDocumento();
    cargarBodegas();
    cargarSelectBodega("selBodega");
    cargarSelectBodega("selBodegaProducto");
    consultarOrdenador();
    consultarTecnico();
    consultarListadoVehiculos();
    consultarTipoOrdenTrabajo();
    consultarEstadoOrdenTrabajo();
    autoCompletarMunicipio();
    autoCompletarProducto();
    autoCompletarProductoCompuesto();
    autoCompletarCliente();
    
    /*Obtener Parámetros Aplicaciones*/
    permiteInventarioNegativo = obtenerParametroAplicacion(18);//Permitir inventario negativo
    
    $("#txtMunicipio").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                idMunicipio = null;
            break;
        }
    });
    
    if(idOrdenTrabajo != "" && idOrdenTrabajo != null && idOrdenTrabajo != "null"){
        consultarInformacionOrden();
        consultarInformacionTecnico();
        consultarInformacionOrdenCliente();
        consultarInformacionVehiculo();
        validarSeleccionOpcioSerial();
    }else{
        if((tipo == "" || tipo == null || tipo == "null") || tipo == "Instalación" ){
            $("#selTipoOrdenTrabajo").val(1);//Instalación
            $("#selTipoOrdenTrabajo").attr("disabled",true);
            $("#selTipoDocumento").val(4);//Instalación
            $("#selTipoDocumento").attr("disabled",true);
            idPedidos = valorRecibido[2];
        }else{
            idPedidos = valorRecibido[2];
            if(tipo == "Mantenimiento"){
                $("#selTipoOrdenTrabajo").val(2);//Mantenimiento
                $("#selTipoDocumento").val(10);//Mantenimiento
            }else{
                $("#selTipoOrdenTrabajo").val(3);//Desinstalación
                $("#selTipoDocumento").val(5);//Desinstalación
            }
            
            $("#selTipoOrdenTrabajo").attr("disabled",true);
            $("#selTipoDocumento").attr("disabled",true);
            $("#linkServicioInstalado").css("display","block");
        }
        
        $("#trEstadoOrden").css("display","none");
        obtenerNumeroTipoDocum();
    }
    
    if(idPedidos != "" && idPedidos != null && idPedidos != "null"){
        consultarInformacionPedidos();
    }
        
    $("#imgLimpiarProducto").click(function(){
        limpiarControlesFormulario(document.frmOrdenProducto);
        limpiarProductoComposicion();
        $(".tdBodegaProductos").css("display","block");
        $("#selBodega").attr("disabled",false);
        idCliente = null;
        idProductoCompuesto = null;
        idProductoComposicion = null;
        posicionEditar = null;
        editarServicioBand = false;
    });
    
    $("#selTecnico").change(function(){
        var arrOpciones = $("#selTecnico").val();
        var opcionSeleccionada = $('input:radio[name=rdoTecnico]:checked').val();
        
        $('input:radio[name=rdoTecnico]').attr("disabled",true);
        
        if(arrOpciones != null && arrOpciones != "" ){
            $.each(arrOpciones, function(contador, fila){
                $("input[name=rdoTecnico][value='" + fila + "']").attr("disabled",false);
            });
            if(arrOpciones.indexOf(opcionSeleccionada) != -1){
                $("input[name=rdoTecnico][value='" + opcionSeleccionada + "']").prop("checked",true);
            }else{
                $("input[name=rdoTecnico]").prop("checked",false);
            }
        }else{
            $("input[name=rdoTecnico]").prop("checked",false);
        }
        
        
    });
    
    
    $("#imgGuardar").click(function(){
       if(validarVacios(document.frmOrdenTrabajo) == false)
            return false; 
        
        if ($('#pnlInforProduCompo').is (':visible')){
            alerta("Debe adicionar el servicio dando clic en el botón de adicionar");
            return false;
        }
        
        if(arrOrdenTrabajClient.length == 0){
            alerta("Debe adicionar mínimo un cliente");
            return false;
        }
        
        if($("#selTecnico").val() == "" || $("#selTecnico").val() == null || $("#selTecnico").val() == "null"){
            alerta("Debe seleccionar los técnicos vinculados a la orden de trabajo");
            return false;
        }
        
        var opcionSeleccionada = $('input:radio[name=rdoTecnico]:checked').val();
        if(opcionSeleccionada == null || opcionSeleccionada == "null" || opcionSeleccionada == "" || opcionSeleccionada == "undefined"){
            alerta("Debe seleccionar el técnico principal");
            return false;
        }
        
        var vehiculoSeleccionado = $('input:checkbox[name=chkVehiculo]:checked').val();
        if(vehiculoSeleccionado == "" || vehiculoSeleccionado == null || vehiculoSeleccionado == "null"){
            alerta("Debe seleccionar mínimo un vehículo");
            return false;
        }
        

        if(validarInformacionServicio() == false){
            return false;
        }
        
        var urlControlador = "";
        var accion = "";
        
        if(idOrdenTrabajo != "" && idOrdenTrabajo != null && idOrdenTrabajo != "null"){
            urlControlador = "controlador/ordenTrabajo.modificar.php";
            accion = "M";//MODIFICAR
        }else{
            urlControlador = "controlador/ordenTrabajo.adicionar.php";
            accion = "A";//ADICIONAR
        }
        
        obtenerDatosEnvioOrdenTrabajo();
        
        //Adicionar o modificar la información de la orden de trabajo
        $.ajax({
            async:false,
            url:localStorage.modulo + urlControlador,
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
                
                if(idOrdenTrabajo == "" || idOrdenTrabajo == null || idOrdenTrabajo == "null"){
                    idOrdenTrabajo = json.idOrdenTrabajo;
                }
                
            },error: function(xhr, opciones, error){
                alerta (error);
                return false;
            }
        });
        
        //Modificar la información de la orden de trabajo
        if(accion  == "M"){//Modificar
            try {
                //Servicio sin productos
                validarServicioProducto();
                
                //Orden Trabajo Técnico
                elimiOrdenTrabaTecni();
                adiciOrdenTrabaTecni();

                //Orden Trabajo Cliente
                elimInforOrdenTrabaClie()
                modificarOrdenTrabaClie();
                
                //Orden Trabajo Vehiculo
                elimiOrdenTrabaVehi();
                adicionarOrdenTrabaVehic();
                
                if(mensajeServicio != "" && mensajeServicio != null && mensajeServicio != "null"){
                    mensajeServicio = "<ul>" + mensajeServicio  + "</ul>";
                    bootbox.alert(mensajeServicio, function() {
                        generarArchivoOrdenTrabajo();
                    });
                }else{
                    generarArchivoOrdenTrabajo();
                }
            }catch(error) {
                alerta(error);
            }
            
        }else{//Adicionar
            try {
                //Orden Trabajo Técnico
                adiciOrdenTrabaTecni();
                
                //Orden Trabajo Cliente
                adicionarOrdenTrabaClie();
                
                //Orden Trabajo Vehiculo
                adicionarOrdenTrabaVehic();
            }catch(error) {
                alerta(error);
            }
            alerta("La orden de trabajo se adicionó correctamente",true);
        }
        
    });
    
    
    $("#imgAdicionarProducto").click(function(){
        if(validarVacios(document.frmOrdenProducto) == false)
            return false;
        
        if(idCliente == null || idCliente == "null" || idCliente == ""){
            alerta("Debe seleccionar un cliente válido");
            return false;
        }
        
        if(idProductoCompuesto == null || idProductoCompuesto == "null" || idProductoCompuesto == ""){
            alerta("Debe seleccionar un producto válido");
            return false;
        }
        
        //Recorrer los valores y asignarles el valor indicados en los campos de cantidad y nota
        for(var i = 0 ;i < arrProductoComposicion.length;i++){
            arrProductoComposicion[i].cantidad = $("#txtCantidad" + arrProductoComposicion[i].secuencial).val();
            arrProductoComposicion[i].nota = $("#txaNota" + arrProductoComposicion[i].secuencial).val();
            arrProductoComposicion[i].idBodega = $("#selBodegaProducto" + arrProductoComposicion[i].secuencial).val();
            arrProductoComposicion[i].bodega = $("#selBodegaProducto" + arrProductoComposicion[i].secuencial + " option:selected").text();
            
            var objProducto = new Object();
            objProducto.idProducto = arrProductoComposicion[i].idProducto;
            objProducto.producto = arrProductoComposicion[i].producto;
            objProducto.cantidad = arrProductoComposicion[i].cantidad;
            objProducto.secuencial = arrProductoComposicion[i].secuencial;
            objProducto.productoSerial = arrProductoComposicion[i].productoSerial;
            objProducto.idTransaccion = arrProductoComposicion[i].idTransaccion;
            
            var objBodega = new Object();
            objBodega.idBodega = arrProductoComposicion[i].idBodega;
            objBodega.bodega = arrProductoComposicion[i].bodega;
            
            //Se valida si permite inventario negativo
            if(permiteInventarioNegativo == 0){
                
                //Se valida la existencia del producto en el inventaripo
                if(objProducto.idTransaccion == "" || objProducto.idTransaccion == null || objProducto.idTransaccion == "null"){
                    if(validarExistenciaProducto(objProducto,objBodega,true) == false){
                        return false;
                    }
                }
            }
        }
        
        actualizarHistorialProducto(arrProductoComposicion);
        
        var objOrdenTrabClien = new Object();
        secuenciaCliente++;
        objOrdenTrabClien.idOrdenTrabajoCliente = idOrdenTrabajoCliente;
        objOrdenTrabClien.idOrdenTrabajo = idOrdenTrabajo;
        objOrdenTrabClien.secuencia = secuenciaCliente;
        objOrdenTrabClien.idCliente = idCliente;
        objOrdenTrabClien.cliente = $("#txtCliente").val();
        objOrdenTrabClien.nitCliente = $("#txtNit").val();
        objOrdenTrabClien.idProductoComposicion = idProductoComposicion;
        objOrdenTrabClien.idProductoCompuesto = idProductoCompuesto;
        objOrdenTrabClien.productoComposicion = $("#txtProductoCompuesto").val();
        objOrdenTrabClien.codigoProducto = $("#txtCodigoProductoCompuesto").val();
        objOrdenTrabClien.idPedido = idPedido;
        objOrdenTrabClien.productos = arrProductoComposicion;
        
        //Indica que esta adicionando un servicio
        
        if(editarServicioBand == true){
            objOrdenTrabClien.idEstaOrdeTrabClie = arrOrdenTrabajClient[posicionEditar].idEstaOrdeTrabClie;
            arrOrdenTrabajClient[posicionEditar] = objOrdenTrabClien;
        }else{
            objOrdenTrabClien.idEstaOrdeTrabClie = 1;
            arrOrdenTrabajClient.push(objOrdenTrabClien);
        }
        
        crearListaOrdeTrabClie();
        
        limpiarControlesFormulario(document.frmOrdenProducto);
        limpiarProductoComposicion();
        $(".tdBodegaProductos").css("display","block");
        $("#selBodega").attr("disabled",false);
        idProductoCompuesto = null;
        idProductoComposicion = null;
        posicionEditar = null;
        editarServicioBand = false;
        
        if((tipo == "" || tipo == null || tipo == "null") || tipo == "Instalación" ){
            idCliente = null;
        }
        
    });
    
    $("#imgAdicioProducCompus").click(function(){
        if(validarVacios(document.frmProductoCompuesto) == false)
            return false;
        
        if(idProductoComposicion == null || idProductoComposicion == "null" || idProductoComposicion == ""){
            alerta("Debe seleccionar un producto válido");
        }
        
        //Recorrer los valores y asignarles el valor indicados en los campos de cantidad y nota
        for(var i = 0 ;i < arrProductoComposicion.length;i++){
            arrProductoComposicion[i].cantidad = $("#txtCantidad" + arrProductoComposicion[i].secuencial).val();
            arrProductoComposicion[i].nota = $("#txaNota" + arrProductoComposicion[i].secuencial).val();
            arrProductoComposicion[i].idBodega = $("#selBodegaProducto" + arrProductoComposicion[i].secuencial).val();
            arrProductoComposicion[i].bodega = $("#selBodegaProducto" + arrProductoComposicion[i].secuencial + " option:selected").text();
        }
        
        var clienteId = actualizarHistorialProducto(arrProductoComposicion);
        
        //Se valida si es de producto serial y se envian cada uno de los productos con valor de 1
        if(productoSerial == true && idOrdenTrabajo != null && idOrdenTrabajo != "null" && idOrdenTrabajo != ""){
            for(var i = 0;i < $("#txtCantidad").val();i++){
                secuencialProducto++;
                var objProductoComposicion = new Object();
                objProductoComposicion.idOrdenTrabajoProducto = null;
                objProductoComposicion.idProductoComposicion = idProductoComposicion;
                objProductoComposicion.idProducto = idProducto;
                objProductoComposicion.productoSerial = productoSerial;
                objProductoComposicion.codigo = $("#txtCodigoProducto").val();
                objProductoComposicion.producto = $("#txtProducto").val();
                objProductoComposicion.unidadMedida = $("#txtUnidadMedida").val();
                objProductoComposicion.valor = quitarSeparadorMil($("#txtValorUnitario").val().toString());
                objProductoComposicion.cantidad = 1;
                objProductoComposicion.nota = $("#txaNota").val();
                objProductoComposicion.idBodega = $("#selBodegaProducto").val();
                objProductoComposicion.bodega = $("#selBodegaProducto option:selected").text();
                objProductoComposicion.secuencial = secuencialProducto;
                objProductoComposicion.idTransaccion = "";
                objProductoComposicion.idEstaOrdeTrabProd = idEstaOrdeTrabProduDef;
                objProductoComposicion.estaOrdeTrabProd = estaOrdeTrabProdDefe;
                
                arrProductoComposicion.push(objProductoComposicion);
            }
        }else{
            secuencialProducto++;
            var objProductoComposicion = new Object();
            objProductoComposicion.idOrdenTrabajoProducto = null;
            objProductoComposicion.idProductoComposicion = idProductoComposicion;
            objProductoComposicion.idProducto = idProducto;
            objProductoComposicion.productoSerial = productoSerial;
            objProductoComposicion.codigo = $("#txtCodigoProducto").val();
            objProductoComposicion.producto = $("#txtProducto").val();
            objProductoComposicion.unidadMedida = $("#txtUnidadMedida").val();
            objProductoComposicion.valor = quitarSeparadorMil($("#txtValorUnitario").val().toString());
            objProductoComposicion.cantidad = $("#txtCantidad").val();
            objProductoComposicion.nota = $("#txaNota").val();
            objProductoComposicion.idBodega = $("#selBodegaProducto").val();
            objProductoComposicion.bodega = $("#selBodegaProducto option:selected").text();
            objProductoComposicion.secuencial = secuencialProducto;
            objProductoComposicion.idTransaccion = "";
            objProductoComposicion.idEstaOrdeTrabProd = idEstaOrdeTrabProduDef;
            objProductoComposicion.estaOrdeTrabProd = estaOrdeTrabProdDefe;
            arrProductoComposicion.push(objProductoComposicion);
        }
        
        crearListadoProductosCompos(clienteId);
        
        limpiarControlesFormulario(document.frmProductoCompuesto);
        idProducto =  null;
        productoSerial = null;
    });
    
    
    /*-----------------VALIDACIONES--------------------------*/
    /*--------------------------Producto-----------------*/
    $("#imgGuardarProducto").click(function(){
       abrirPopup(localStorage.modulo + 'vista/frmProducto.html?id=&origen=1', 'Productos'); 
    });    
    $("#txtCodigoProductoCompuesto").change(function(){
        consultarProductoCompuesto($("#txtCodigoProductoCompuesto").val());
    }).keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtProductoCompuesto").val("");
                 $("#txtValorUnitario").val('');
                idProductoCompuesto = null;
                limpiarProductoComposicion();
            break;
        }
    });
    $("#txtProductoCompuesto").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtCodigoProductoCompuesto").val("");
                 $("#txtValorUnitario").val('');
                idProductoCompuesto = null;
                limpiarProductoComposicion();
            break;
        }
    });
    
    
    $("#txtCodigoProducto").change(function(){
        consultarProducto($("#txtCodigoProducto").val());
    }).keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtProducto, #txtCantidad , #txtUnidadMedida").val("");
                 $("#txtValorUnitario").val('');
                idProducto = null;
            break;
        }
    });
    $("#txtProducto").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtCodigoProducto, #txtCantidad , #txtUnidadMedida").val("");
                 $("#txtValorUnitario").val('');
                idProducto = null;
            break;
        }
    });
    
    
    $("#txtCantidad").change(function(){
        validarCantidad(this);
    });
    
    /*--------------------------Cliente-----------------*/
    $("#txtNit").change(function(){    
        if($("#txtNit").val() != ""){
            consultarTercero( $("#txtNit").val());
        }
    }).keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtCliente").val("");
                $("#txtCliente").keypress();
                idCliente = null;
            break;
        }
    });
    $("#txtCliente").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtNit").val("");
                autoCompletarCliente();
                idCliente = null;
            break;
        }
    });
    
    
});

function actualizarHistorialProducto(arregloInformacion){
    //Adicionamos la informacion
    var clienteId = null;
    for(i = 0; i < arrHistoProduInfor.length; i++){
        if(arrHistoProduInfor[i].estadoEditado = true){
            arrHistoProduInfor[i].arregloInformacion = arregloInformacion;
            clienteId = arrHistoProduInfor[i].idCliente;
        }
    }
    return clienteId;
}

function cargarListadoTecnico(){
    $("#divInformacionTecnico").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">'; 
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Técnico</th>'; 
    tabla += '<th>Principal</th>';   
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    tabla += '</table>';
    $("#divInformacionTecnico").html(tabla);	
}
function cargarTipoDocumento(){
    $.ajax({
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarTipoDocumento.php',
        type:'POST',
        dataType:"json",
        data:{estado:true},
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje,true);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                return false;
            }
            
            var control = $('#selTipoDocumento');
            control.empty();
            
            if(json.numeroRegistros == 1){
                $.each(json.data, function(contador, fila){
                    control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
                });
                $('#selTipoDocumento').attr("disabled",true);
            }else{
                control.append('<option value="">--Seleccione--</option>');
                $.each(json.data, function(contador, fila){
                    control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
                });
            }
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarOrdenador(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarOrdenador.php',
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
            
            var control = $('#selOrdenador');
            control.empty();
            if(json.numeroRegistros != 1){
                control.append('<option value="">--Seleccione--</option>');
                $.each(json.data, function(contador, fila){
                    control.append('<option value="' + fila.idOrdenador + '">' + fila.ordenador + '</option>');
                });
            }else{
                $.each(json.data, function(contador, fila){
                    control.append('<option value="' + fila.idOrdenador + '">' + fila.ordenador + '</option>');
                });
                control.attr("disabled",true);
            }
            
            
            
        }, error: function(xhr, opciones, error){
            alert(error);
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
            tabla.append('<tr><td><b>Principal</b></td>');
            control.empty();
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idTecnico + '">' + fila.tecnico + '</option>');
                tabla.append('<tr><td><input type="radio" id="rdoTecnico' + fila.idTecnico + '" name="rdoTecnico" class="rdoTecnico" value="' + fila.idTecnico + '" style="margin-bottom:2px" disabled></tr></td>');
            });
            $("#selTecnico").attr("size",$("#selTecnico option").length);
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function autoCompletarMunicipio(){
    $("#txtMunicipio").autocomplete({
        source: localStorage.modulo + 'ajax/ordenTrabajo.autocompletarMunicipio.php',
        select:function(event, ui){
            idMunicipio = ui.item.idMunicipio;
        }
    });
}
/*Funciones Tab Clientes*/
function autoCompletarCliente(){
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select:function(event, ui){
            $("#txtNit").val(ui.item.nit);
            idCliente = ui.item.idCliente;
        }
    });
}
function consultarTercero(nit){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarCliente.php',
        type:'POST',
        dataType:"json",
        data:{nit:nit},
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
            
            if(json.numeroRegistros == 1){
                $.each(json.data, function(contador, fila){
                    idCliente = fila.idCliente;
                    $("#txtCliente").val(fila.cliente);
                });
            }else{
                var html = '';
                html += '<div style="min-height: 12.4286px;'
                html += 'padding: 0px;'
                html += 'border-bottom: 1px solid #E5E5E5;';
                html += 'background-image: linear-gradient(to bottom, #455A64 0%, #455A64 100%);';
                html += 'background-repeat: repeat-x;';
                html += 'font-weight: bold;';
                html += 'border-radius: 4px;';
                html += 'padding-top: 0.6%;';
                html += 'font-size: 1.6em;';
                html += 'color: #FFF;">';
                html += 'Por favor escoja una sucursal</div>';
                
                html += '<ul style="list-style:none; margin-top:1%;">';
                $.each(json.data, function(contador, fila){
                    html += '<li><a data-dismiss="modal" style="cursor:pointer" onclick="asignar(\''+fila.cliente+'\','+fila.idCliente+')">'+fila.cliente+'</li>';
                });
                html += '</ul>';

                bootbox.alert(html);
            }
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function asignar(sucursal, idClient){
    idCliente = idClient;
    $('#txtCliente').val(sucursal);
}
function autoCompletarProductoCompuesto(){
    $("#txtProductoCompuesto").autocomplete({
        source: localStorage.modulo + 'ajax/ordenTrabajo.autoCompletarProducto.php?idProductos=&compuesto=true&servicio=false',
        select:function(event, ui){
            idProductoCompuesto = ui.item.idProducto;
            idProductoComposicion = ui.item.idProducto;
            $("#txtCodigoProductoCompuesto").val(ui.item.codigo);
            consultarProductosComposicion(idProductoCompuesto,ui.item.producto);
        }
    });
}
function autoCompletarProducto(){
    //Se elimina la condición de que no tenga en cuenta los productos ya adicionados idProductosEvaluar
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/ordenTrabajo.autoCompletarProducto.php?idProductos=&compuesto=false&servicio=true',
        select:function(event, ui){
            idProducto = ui.item.idProducto;
            productoSerial = ui.item.productoSerial;
            $("#txtCodigoProducto").val(ui.item.codigo);
            $("#txtValorUnitario").val(agregarSeparadorMil(parseInt(ui.item.valorEntraConImpue).toString()));
            $("#txtUnidadMedida").val(ui.item.unidadMedida);
            validarCantidad(document.getElementById('txtCantidad'));
        }
    });
}
function validarCantidad(elemento){
    if(elemento.value == '' || elemento.value == '0'){
        elemento.value = 1;
    }
}
function obtenerDatosEnvioOrdenTrabajo(){
    asignarDatosEnvioOrdenTrabajo();
    data = "idOrdenTrabajo=" + idOrdenTrabajo + "&fecha=" + fecha + "&idTipoDocumento="+idTipoDocumento+"&numeroOrden=" + numeroOrden + "&idOrdenador=" + idOrdenador + "&fechaInicio=" + fechaInicio + "&fechaFin=" + fechaFin + "&idMunicipio=" + idMunicipio + "&idEstadoOrdenTrabajo=" +idEstadoOrdenTrabajo + "&tipoOrdenTrabajo=" + tipoOrdenTrabajo + "&idClienteServicio=" + idClienteServicio + "&observacion=" + observacion;
}
function asignarDatosEnvioOrdenTrabajo(){
    fecha = $("#txtFechaOrden").val();
    idTipoDocumento = $("#selTipoDocumento").val();;
    numeroOrden = $("#txtNoOrdenTrabajo").val();
    idOrdenador = $("#selOrdenador").val();
    fechaInicio = $("#txtFechaInicio").val();
    fechaFin = $("#txtFechaFin").val();
    idEstadoOrdenTrabajo = $("#selEstadoOrdenTrabajo").val();
    tipoOrdenTrabajo = $("#selTipoOrdenTrabajo").val();
    observacion = $("#txaObservacion").val();
    
    /*if(idEstadoOrdenTrabajo == 1 && idOrdenTrabajo != "" && idOrdenTrabajo != null){
        idEstadoOrdenTrabajo = 2;//Entregado Técnico
    }*/
    
}
function consultarProductosComposicion(idProducto,producto){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarProductosComposicion.php',
        type:'POST',
        dataType:"json",
        data:{idProducto:idProducto},
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                $("#pnlInforProduCompo").css("display","block");
                $("#spnProductoCompuesto").html(producto);
                crearListadoProductosCompos(json);
                return false;
            }
            $("#pnlInforProduCompo").css("display","block");
            $("#spnProductoCompuesto").html(producto);
            crearListadoProductosCompos(json);
            
            arrProductoComposicion = new Array();
            $.each(json.data, function(contador, fila){
                //Se valida si el producto es de tipo serial , y se adicionan cada uno de los productos a partir de la cantidad
                secuencialProducto++;
                var objProductoComposicion = new Object();
                objProductoComposicion.idOrdenTrabajoProducto = null;
                objProductoComposicion.idProductoComposicion = fila.idProductoComposicion;
                objProductoComposicion.idProducto = fila.idProducto;
                objProductoComposicion.productoSerial = fila.productoSerial;
                objProductoComposicion.codigo = fila.codigo;
                objProductoComposicion.producto = fila.producto;
                objProductoComposicion.unidadMedida = fila.unidadMedida;
                objProductoComposicion.valor = parseInt(fila.valorEntraConImpue);
                objProductoComposicion.idBodega = "";
                objProductoComposicion.bodega = "";
                objProductoComposicion.cantidad = fila.cantidad;
                objProductoComposicion.nota = "";
                objProductoComposicion.secuencial = secuencialProducto;
                objProductoComposicion.idTransaccion = "";
                objProductoComposicion.idEstaOrdeTrabProd = idEstaOrdeTrabProduDef;
                objProductoComposicion.estaOrdeTrabProd = estaOrdeTrabProdDefe;
                arrProductoComposicion.push(objProductoComposicion);
                
            });
            crearListadoProductosCompos();
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function crearListadoProductosCompos(clienteId){
    
    $("#tblInformacionProducto").html(tabla);		
    var tabla = '';
    tabla += '<table>';
    tabla += '<tr>';
    tabla += '<th>Cod.</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>U. Medid</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Bodega</th>';
    tabla += '<th>Cant.</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    
    if(arrProductoComposicion.length == 0){
        cargarListadoProductosCompos();
        return false;
    }
    
    $.each(arrProductoComposicion, function(contador, fila){
        tabla += '<tr>';
        tabla += "<td style='text-align:right'>" + fila.codigo + "</td>";		
        tabla += "<td>" + fila.producto + "</td>";
        tabla += "<td>" + fila.unidadMedida + "</td>";
        tabla += "<td style='text-align:right'>" + agregarSeparadorMil(parseInt(fila.valor).toString()) + "</td>";
        
        var atributoCampo = "";
        if(idOrdenTrabajoCliente != "" && idOrdenTrabajoCliente != null && idOrdenTrabajoCliente != "null"){
            atributoCampo = "disabled";
        }
        
        var controlOpcion = "";
        
        if(idOrdenTrabajoCliente != "" && idOrdenTrabajoCliente != null && idOrdenTrabajoCliente != "null" && $("#selEstadoOrdenTrabajo").val() == 2){
            /*tabla += "<td>" + fila.bodega + "</td>";
            tabla += "<td style='text-align:right'>" + parseInt(fila.cantidad) + "</td>";
            if(fila.nota != "" && fila.nota != null && fila.nota != "null"){
                tabla += "<td>" + fila.nota + "</td>";
            }else{
                tabla += "<td></td>";
            }*/
            controlOpcion = " disabled ";
            
        }
        
        tabla += "<td>";
        if(arrBodegas.length == 1){
            tabla += "<select id='selBodegaProducto" + fila.secuencial + "' name='selBodegaProducto' disabled class='form-control medium'>";
        }else{
            tabla += "<select id='selBodegaProducto" + fila.secuencial + "' name='selBodegaProducto' class='form-control medium' " + controlOpcion + ">";
        }

        for(var i = 0 ; i < arrBodegas.length;i++){
            var objBodega = arrBodegas[i];
            var idComparar = fila.idBodega;

            if(objBodega.idBodega == idComparar){
                tabla += "<option selected value='" + objBodega.idBodega + "'>" + objBodega.bodega + "</option>";
            }else{
                tabla += "<option value='" + objBodega.idBodega + "'>" + objBodega.bodega + "</option>";
            }
        }
        tabla += "</select>";
        tabla += "</td>";
        tabla += "<td><input type='text' id='txtCantidad" + fila.secuencial + "' " + atributoCampo + " name='txtCantidad' value='" + parseInt(fila.cantidad) + "' class='form-control verySmall' " + controlOpcion + "></td>";

        if(fila.nota == "" || fila.nota == null || fila.nota == "null"){
            fila.nota = "";
        }

        tabla += "<td><textarea id='txaNota" + fila.secuencial + "' class='form-control' accesskey='V' placeholder='Nota' style='width: 150px; height: 33px;' " + controlOpcion + " >" + fila.nota + "</textarea></td>";
        
        tabla += '<td align="center" colspan="2"><span class="fa fa-trash-o imagenesTabla" id="imgEliminarProducto' + contador + '" onclick="eliminarProducto(' + contador + ', ' + clienteId + ')"></span></td>';
        tabla += '</tr>';
    });
    tabla += '</table>';
    $("#tblInformacionProducto").html(tabla);
    
    actualizarValorInformacion(clienteId);
    
    obtenerIdProductosEvaluar();
    
    //Validamos la existencia sino agregamos la informcion historial
    for(i = 0; i < arrHistoProduInfor.length; i++){
        arrHistoProduInfor[i].estadoEditado = false;
    }
    var contadorArreglo = 0;
    if(arrHistoProduInfor.length > 0){
        for(i = 0; i < arrHistoProduInfor.length; i++){
            var objArregloHistorial = arrHistoProduInfor[i];
            if(objArregloHistorial.idCliente == clienteId){
                arrHistoProduInfor[i].estadoEditado = true;
                contadorArreglo++;
            }
        }
        if(contadorArreglo == 0){
            var objArregloHistorial = new Object();
            objArregloHistorial.idCliente = clienteId;
            objArregloHistorial.estadoEditado = true;
            arrHistoProduInfor.push(objArregloHistorial);
        }
    }else{
       var objArregloHistorial = new Object();
       objArregloHistorial.idCliente = clienteId;
       objArregloHistorial.arregloInformacion = null;
       objArregloHistorial.estadoEditado = true;
       arrHistoProduInfor.push(objArregloHistorial);
    }
}

function actualizarValorInformacion(clienteId){
    var objInformacionTemporal = null;
    for(i = 0; i < arrHistoProduInfor.length; i++){
        var objArregloHistorial = arrHistoProduInfor[i];
        if(objArregloHistorial.idCliente == clienteId){
            if(objArregloHistorial.arregloInformacion != null){
                objInformacionTemporal = objArregloHistorial.arregloInformacion;
            }
        }
    }
    if(objInformacionTemporal != null){
        $.each(objInformacionTemporal, function (contador, fila){
            if($("#selBodegaProducto"+fila.secuencial).length){
                $("#selBodegaProducto"+fila.secuencial).val(fila.idBodega);
            }
            if($("#txtCantidad"+fila.secuencial).length){
                $("#txtCantidad"+fila.secuencial).val(fila.cantidad);
            }
            if($("#txaNota"+fila.secuencial).length){
                $("#txaNota"+fila.secuencial).val(fila.nota);
            }
        });
    }
}

function cargarListadoProductosCompos(){
    $("#tblInformacionProducto").html(tabla);		
    var tabla = '';
    tabla += '<table>';
    tabla += '<tr>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>U. Medid</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Bodega</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Nota</th>';
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
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#tblInformacionProducto").html(tabla);
}
function limpiarProductoComposicion(){
    $("#pnlInforProduCompo").css("display","none");
    $("#tblInformacionProducto").html("");
    arrProductoComposicion = new Array();
    limpiarControlesFormulario(document.frmProductoCompuesto);
}
function eliminarProducto(contador, clienteId){
    bootbox.confirm("¿ Está seguro de eliminar el producto ?", function(result) {
        if(result == true){
            
            if(arrProductoComposicion[contador].idOrdenTrabajoProducto != "" && arrProductoComposicion[contador].idOrdenTrabajoProducto != null && arrProductoComposicion[contador].idOrdenTrabajoProducto != "null"){
                arrIdElimOrdeTrabProd.push(arrProductoComposicion[contador].idOrdenTrabajoProducto);
            }
            
            arrProductoComposicion.splice(contador,1);
            crearListadoProductosCompos(clienteId);
        }
    });
}
function consultarProducto(codigoProducto){
    if(codigoProducto == ''){
        return false;
    }
    var idProductos = obtenerIdProductosEvaluar();
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajo.consultarProductos.php',
        type:'POST',
        dataType:"json",
        data:{
                codigoProducto:codigoProducto
                //,idProducto:idProductosEvaluar
                ,idProducto:null
                ,compuesto:'false'
             },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                idProducto = '';
                productoSerial = null;
                return false;
            }
            
            if(json.numeroRegistros == 0){
                idProducto = '';
                productoSerial = null;
                return false;
            }
            
            $.each(json.data, function(contador, fila){
                idProducto = fila.idProducto;
                productoSerial = fila.productoSerial;
                $("#txtProductoCompuesto").val(fila.producto);
                $("#txtUnidadMedida").val(fila.unidadMedida);
                $("#txtValorUnitario").val(agregarSeparadorMil(parseInt(fila.valor).toString()));
                validarCantidad(document.getElementById('txtCantidad'));
            });
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function consultarProductoCompuesto(codigoProducto){
    if(codigoProducto == ''){
        return false;
    }
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajo.consultarProductos.php',
        type:'POST',
        dataType:"json",
        data:{
                codigoProducto:codigoProducto
             },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                idProducto = '';
                limpiarProductoComposicion();
                return false;
            }
            
            if(json.numeroRegistros == 0){
                idProductoCompuesto = '';
                limpiarProductoComposicion();
                return false;
            }
            
            $.each(json.data, function(contador, fila){
                idProductoCompuesto = fila.idProducto;
                $("#txtProductoCompuesto").val(fila.producto);
                arrProductoComposicion = new Array();
                consultarProductosComposicion(idProductoCompuesto,fila.producto);
            });
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}

function crearListaOrdeTrabClie(clienteId){
    $("#tblInformacionClienteProducto").html("");
    if(arrOrdenTrabajClient.length == 0){
        return false;
    }
    
    var objInforClien  = null;
    var ij = 0;
    var kl = 0;
    var html = '';
    html += '<table class="table table-bordered table-striped consultatabla tree">';
    html += '<tr>';
    html += '<th colspan="8">Usuario</th>';
    html += '<th>Editar</th>';
    html += '<th>Eliminar</th>';
    html += '</tr>';
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        
        objInforClien = arrOrdenTrabajClient[i];
        
        ij++;
        html += '<tr class="treegrid-' + (ij) + '" style="font-weight: bold;">';
        html += '<td colspan="8"><span class="fa fa-user"></span> ' + objInforClien.cliente + ' - ' + objInforClien.productoComposicion + "</td>";
        html += '<td align="center"><span id="imgEditarServicio" class="fa fa-pencil fa-2x imagenesTabla" title="Editar" style="cursor: pointer; margin-left: 6px;" onclick="editarServicio(' + i + ', ' + objInforClien.idCliente +'' + i + ')"></span></td>';
        html += '<td align="center"><span id="imgEliminarServicio" class="fa fa-trash fa-2x imagenesTabla" title="Eliminar" style="cursor: pointer; margin-left: 8px;" onclick="eliminarServicio(' + i + ',' + objInforClien.idCliente +'' + i + ')"></span></td>';
        html += '</tr>';
        kl = ij;
        kl++;
        html += '<tr class="treegrid-' + (kl) + ' treegrid-parent-' + (ij) + ' " style="font-weight: bold;" >';
        html += '<td>Código</th>';
        html += '<td>Producto</td>';
        html += '<td>U. Medid</td>';
        html += '<td>Valor</td>';
        html += '<td>Cantidad</td>';
        html += '<td>Bodega</td>';
        html += '<td style="width: 100px;">Estado</td>';
        html += '<td>Serial</td>';
        html += '<td>Nota</td>';
        html += '<td>Acción</td>';
        html += '</tr>';
        kl++;
        
        if(objInforClien.productos.length == 0){
            html += '<tr class="treegrid-' + (kl) + ' treegrid-parent-' + (ij) + '" >';
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += "<td>&nbsp;</td>";
            html += '</tr>';
        }
        
        for(var j = 0;j < objInforClien.productos.length ;j++){
            
            //Se valida si es una posicion vacia
            if(objInforClien.productos[j] == null || objInforClien.productos[j] == ""){
                continue;
            }
            var objProducto = objInforClien.productos[j];
            html += '<tr class="treegrid-' + (kl) + ' treegrid-parent-' + (ij) + '" >';
            kl++;
            html += "<td align='right'>" + objProducto.codigo + "</td>";		
            html += "<td>" + objProducto.producto + "</td>";
            html += "<td>" + objProducto.unidadMedida + "</td>";
            html += "<td align='right'>" + agregarSeparadorMil(parseInt(objProducto.valor).toString()) + "</td>";
            html += "<td align='right'>" + parseInt(objProducto.cantidad) + "</td>";
            html += "<td>" + objProducto.bodega + "</td>";
            html += "<td>" + objProducto.estaOrdeTrabProd + "</td>";
            
            if(idOrdenTrabajo != "" && idOrdenTrabajo != null && objProducto.productoSerial == true){
                html += "<td style='width: 150px;'>";
                html += "<select id='selSerialProd" + objProducto.secuencial + "' name='selSerialProd'  class='form-control medium selSerialProd' onchange='validarOpcionSerial(" + i + "," + j + ",\"selSerialProd" + objProducto.secuencial + "\")' value='" + objProducto.serial + "'>";
                html += "<option value=''>--SELECCIONE--</option>";
                
                if(arrIdSelecSerial.indexOf("selSerialProd" + objProducto.secuencial) == -1){
                    arrIdSelecSerial.push("selSerialProd" + objProducto.secuencial);
                }
                 
                
                       
                if(arrOpcioProduSerial[objProducto.idProducto] == "" || arrOpcioProduSerial[objProducto.idProducto] == null){
                    arrOpcioProduSerial[objProducto.idProducto] = new Array();
                }

                if(arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega] == "" || arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega] == null){
                    arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega] = new Array();
                    consultarProductosSerial(objProducto.idProducto,objProducto.idBodega);
                }
                
                for(var p = 0;p<arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega].length;p++){
                    //Se valida si ya se encuentra seleccionado para que no lo cargue en el select
                    var seleccion = "";
                    if(objProducto.serial == arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega][p]){
                        seleccion = " selected ";
                    }else{
                        if(arrSerialSelecc.indexOf(arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega][p]) != "-1"){
                            continue;
                        }
                    }
                    html += "<option " + seleccion + " value='" + arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega][p] + "'>" + arrOpcioProduSerial[objProducto.idProducto][objProducto.idBodega][p] + "</option>";
                }
                
                html += "</select>";
                html += "</td>";
            }else{
                if(objProducto.serial != "" && objProducto.serial != null && objProducto.serial != "NULL"){
                    html += "<td><b>" + objProducto.serial + "</b></td>";
                }else{
                    html += "<td></td>";
                }
                
            }
            
            if(objProducto.nota != "" && objProducto.nota != null && objProducto.nota != "NULL" && objProducto.nota != "null"){
                html += "<td><b>" + objProducto.nota + "</b></td>";
            }else{
                html += "<td></td>";
            }
            html += '<td align="center" colspan="2"><span class="fa fa-trash-o imagenesTabla" id="imgEliminarProducto' + j + '" onclick="eliminarProductoCliente(' + i + ' , ' + j + ')"></span></td>';
            html += '</tr>';
        }
        ij = kl;
    }
    html += '</table>';
    html += '</div';
    html += '</div';
    
    $("#tblInformacionClienteProducto").append(html);
    if(clienteId != undefined && clienteId != null){
        actualizarValorInformacion(clienteId);
    }
    $('.selSerialProd').multiselect({
        maxHeight: 600
        ,nonSelectedText: '--Seleccione--'	
        ,enableFiltering: true
        ,filterPlaceholder: 'Buscar'
        ,numberDisplayed: 1
        ,enableCaseInsensitiveFiltering: true
        ,buttonWidth: '150px'
    });
    
    if(idOrdenTrabajo != "" && idOrdenTrabajo != null){
        $('.tree').treegrid({
            'initialState': 'expanded',
        });
        
    }else{
        $('.tree').treegrid({
            'initialState': 'collapsed',
        });
    }
    
    
    
}
function eliminarProductoCliente(posicionCliente,posicionProducto){
    bootbox.confirm("¿ Está seguro de eliminar el producto ?", function(result) {
        if(result == true){
            //Se elimina la posición del arreglo
            var idEliminar = arrOrdenTrabajClient[posicionCliente].productos[posicionProducto].idOrdenTrabajoProducto;
            
            if(idEliminar != "" && idEliminar != null && idEliminar != "null"){
                arrIdElimOrdeTrabProd.push(idEliminar);
            }
            
            arrOrdenTrabajClient[posicionCliente].productos.splice(posicionProducto,1);
            crearListaOrdeTrabClie();
        }
    });
}
function eliminarServicio(posicionCliente, clienteId){
    bootbox.confirm("¿ Está seguro de eliminar el servicio ?", function(result) {
        if(result == true){
            
            var idEliminar = arrOrdenTrabajClient[posicionCliente].idOrdenTrabajoCliente;
            if(idEliminar != "" && idEliminar != null && idEliminar != "null"){
                arrIdElimOrdeTrabClie.push(idEliminar);
            }
            
            //Se elimina la posición del arreglo del servicio
            arrOrdenTrabajClient.splice(posicionCliente,1);  
            eliminarInforTemporalCliente(clienteId);
            crearListaOrdeTrabClie();
        }
   });
}
function eliminarInforTemporalCliente(clienteId){
    for(i = 0; i < arrHistoProduInfor.length; i++){
        var objInfromacion = arrHistoProduInfor[i];
        if(objInfromacion.idCliente == clienteId){
            arrHistoProduInfor.splice(i, 1);
        }
    }
}
function editarServicio(posicionCliente, clienteId){
    var objInforCliente = arrOrdenTrabajClient[posicionCliente];
    
    idCliente = objInforCliente.idCliente;
    idProductoCompuesto = objInforCliente.idProductoCompuesto;
    idProductoComposicion = objInforCliente.idProductoComposicion;
    idPedido = objInforCliente.idPedido;
    idOrdenTrabajoCliente = objInforCliente.idOrdenTrabajoCliente;
    
    
    $("#txtNit").val(objInforCliente.nitCliente);
    $("#txtCliente").val(objInforCliente.cliente);
    
    $("#txtCodigoProductoCompuesto").val(objInforCliente.codigoProducto);
    $("#txtProductoCompuesto").val(objInforCliente.productoComposicion);
    
    $("#pnlInforProduCompo").css("display","block");
    
    $(".tdBodegaProductos").css("display","none");
    $("#selBodega").attr("disabled",true);
    
    $("#spnProductoCompuesto").html(objInforCliente.productoComposicion);
    
    posicionEditar = posicionCliente;
    editarServicioBand = true;
    arrProductoComposicion = new Array();   
    arrProductoComposicion = objInforCliente.productos; 
    
    obtenerIdProductosEvaluar();
    
    crearListadoProductosCompos(clienteId);   
}
function obtenerNumeroTipoDocum(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajo.obtenerNumerTipoDocu.php',
        type:'POST',
        dataType:"json",
        data:{
                idTipoDocumento:$("#selTipoDocumento").val()
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            $("#txtNoOrdenTrabajo").val(json.numero);
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
//Funciones orden trabajo técnico
function obtenerDatosOrdenTrabTecni(){
    var arrOpciones = $("#selTecnico").val();
    var opcionSeleccionada = $('input:radio[name=rdoTecnico]:checked').val();
    
    $.each(arrOpciones, function(contador, fila){
        var objOrdeTrabTecni = new Object();
        objOrdeTrabTecni.idOrdenTrabajo = idOrdenTrabajo;
        objOrdeTrabTecni.idOrdenTrabajoTecnico = "";
        objOrdeTrabTecni.idTecnico = fila;
        
        if(fila == opcionSeleccionada){
            objOrdeTrabTecni.principal = true;
        }else{
            objOrdeTrabTecni.principal = false;
        }
        arrOrdenTrabajTecni.push(objOrdeTrabTecni);
    });
}
function adiciOrdenTrabaTecni(){
    obtenerDatosOrdenTrabTecni();
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajoTecnico.adicionar.php',
        type:'POST',
        dataType:"json",
        data:{
                ordenTrabajoTecnico:arrOrdenTrabajTecni
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                throw mensaje;
                return false;
            }
        },error: function(xhr, opciones, error){
            throw error;
            return false;
        }
    });
}
function elimiOrdenTrabaTecni(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajoTecnico.eliminar.php',
        type:'POST',
        dataType:"json",
        data:{
                idOrdenTrabajo:idOrdenTrabajo
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                throw mensaje;
                return false;
            }
        },error: function(xhr, opciones, error){
            throw error;
            return false;
        }
    });
}
function adicionarOrdenTrabaClie(){
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        var objInforClie = arrOrdenTrabajClient[i];
        
        $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/ordenTrabajoCliente.adicionar.php',
            type:'POST',
            dataType:"json",
            data:{
                    idOrdenTrabajo:idOrdenTrabajo
                    ,idCliente:objInforClie.idCliente
                    ,idProductoComposicion:objInforClie.idProductoComposicion
                    ,idPedido:objInforClie.idPedido
                },
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    alerta(mensaje);
                    return false;
                }
                idOrdenTrabajoCliente = json.idOrdenTrabajoCliente;
                
                adicionarOrdenTrabProdu(idOrdenTrabajoCliente,objInforClie.productos);
                
            },error: function(xhr, opciones, error){
                throw error;
                return false;
            }
        });
    }
}
function adicionarOrdenTrabProdu(idOrdenTrabajoCliente,arrProductos){
    for(var i = 0;i < arrProductos.length ;i++){
        var objInforProd = arrProductos[i];
        
        $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/ordenTrabajoProducto.adicionar.php',
            type:'POST',
            dataType:"json",
            data:{
                    idOrdenTrabajoCliente:idOrdenTrabajoCliente
                    ,idProducto:objInforProd.idProducto
                    ,nota:objInforProd.nota
                    ,cantidad:objInforProd.cantidad
                    ,valorUnitario:objInforProd.valor
                    ,serial:objInforProd.serial
                    ,idBodega:objInforProd.idBodega
                    ,productoSerial:objInforProd.productoSerial
                    ,idEstaOrdeTrabProd:objInforProd.idEstaOrdeTrabProd
                },
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    throw mensaje;
                }

            },error: function(xhr, opciones, error){
                throw error;
                return false;
            }
        });
        
    }    
}
function modificarOrdenTrabProdu(objInforClie,arrProductos){
    
    for(var i = 0;i < arrProductos.length ;i++){
        var objInforProd = arrProductos[i];
        
        if((objInforProd.idTransaccion == "" || objInforProd.idTransaccion == null || objInforProd.idTransaccion == "null") && (objInforProd.idOrdenTrabajoProducto == "" || objInforProd.idOrdenTrabajoProducto == null)){
            if(idTransaccion == "" || idTransaccion == null || idTransaccion == "null"){
                adicionarTransaccion(objInforClie);
                objInforProd.idTransaccion = idTransaccion;
            }else{
                objInforProd.idTransaccion = idTransaccion;
            }
        }else{
            if(idTransaccion != "" && idTransaccion != null && idTransaccion != "null"){
                objInforProd.idTransaccion = idTransaccion;
            }
        }
        
        $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/ordenTrabajoProducto.modificar.php',
            type:'POST',
            dataType:"json",
            data:{
                    idOrdenTrabajoProducto:objInforProd.idOrdenTrabajoProducto
                    ,idOrdenTrabajoCliente:objInforClie.idOrdenTrabajoCliente
                    ,idProducto:objInforProd.idProducto
                    ,nota:objInforProd.nota
                    ,cantidad:objInforProd.cantidad
                    ,valorUnitario:objInforProd.valor
                    ,serial:objInforProd.serial
                    ,idBodega:objInforProd.idBodega
                    ,idTransaccion:objInforProd.idTransaccion
                },
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    throw mensaje;
                }

            },error: function(xhr, opciones, error){
                throw error;
                return false;
            }
        });
        
    }    
}
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
function consultarInformacionPedidos(){
    $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/ordenTrabajo.consultarProductosPedidos.php',
            type:'POST',
            dataType:"json",
            data:{
                    idPedido:idPedidos
                },
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    alerta(mensaje);
                    return false;
                }
                
                if(json.numeroRegistros == 0){
                    alerta("Los pedidos seleccionados no tiene vinculados productos");
                    return false;
                }
                
                
                $.each(json.data, function(contador, fila){  
                    arrProductoComposicion = new Array(); 

                    var objOrdenTrabClien = new Object();
                    objOrdenTrabClien.idOrdenTrabajoCliente = "";
                    objOrdenTrabClien.idOrdenTrabajo = idOrdenTrabajo;
                    objOrdenTrabClien.idCliente = fila.idCliente;
                    objOrdenTrabClien.cliente = fila.cliente;
                    objOrdenTrabClien.nitCliente = fila.nit;
                    objOrdenTrabClien.idProductoComposicion = fila.idProductoComposicion;
                    objOrdenTrabClien.idProductoCompuesto = fila.idProductoCompuesto;
                    objOrdenTrabClien.productoComposicion = fila.productoComposicion;
                    objOrdenTrabClien.codigoProducto = fila.codigo;
                    objOrdenTrabClien.idPedido = fila.idPedido;
                    objOrdenTrabClien.idEstaOrdeTrabClie = 1;

                    //Se consulta la información vinculada al producto compuesto
                    consultarInforProduComp(fila.idProductoCompuesto);
                    objOrdenTrabClien.productos = arrProductoComposicion;
                    
                    arrOrdenTrabajClient.push(objOrdenTrabClien);
                    
                    idClienteServicio = fila.idClienteServicio;
                });
                
                if(idClienteServicio != "" && idClienteServicio != null && idClienteServicio != "null"){
                    consultarInformacionServicio();
                    consultarClienteServicioProducto();
                }
                crearListaOrdeTrabClie();
                
            },error: function(xhr, opciones, error){
                throw error;
                return false;
            }
        });
}
function consultarInforProduComp(idProducto,producto){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarProductosComposicion.php',
        type:'POST',
        dataType:"json",
        data:{idProducto:idProducto},
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
            arrProductoComposicion = new Array();   
            $.each(json.data, function(contador, fila){
                //Se valida si el producto es de tipo serial , y se adicionan cada uno de los productos a partir de la cantidad
                secuencialProducto++;
                var objProductoComposicion = new Object();
                objProductoComposicion.idOrdenTrabajoProducto = null;
                objProductoComposicion.idProductoComposicion = fila.idProductoComposicion;
                objProductoComposicion.idProducto = fila.idProducto;
                objProductoComposicion.productoSerial = fila.productoSerial;
                objProductoComposicion.codigo = fila.codigo;
                objProductoComposicion.producto = fila.producto;
                objProductoComposicion.unidadMedida = fila.unidadMedida;
                objProductoComposicion.valor = parseInt(fila.valorEntraConImpue);
                objProductoComposicion.cantidad = fila.cantidad;
                objProductoComposicion.nota = "";
                objProductoComposicion.idBodega = "";
                objProductoComposicion.bodega = "";
                objProductoComposicion.idTransaccion = "";
                objProductoComposicion.idEstaOrdeTrabProd = idEstaOrdeTrabProduDef;
                objProductoComposicion.estaOrdeTrabProd = estaOrdeTrabProdDefe;
                objProductoComposicion.secuencial = secuencialProducto;
                arrProductoComposicion.push(objProductoComposicion);
            });
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function obtenerIdProductosEvaluar(){
    idProductosEvaluar = "";
    for(var i = 0;i<arrProductoComposicion.length;i++){
        idProductosEvaluar = idProductosEvaluar + arrProductoComposicion[i].idProducto + ",";
    }
    idProductosEvaluar = idProductosEvaluar.substring(0, idProductosEvaluar.length - 1);
    autoCompletarProducto();
}
function consultarInformacionOrden(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultar.php',
        type:'POST',
        dataType:"json",
        data:{
            idOrdenTrabajo:idOrdenTrabajo
        },
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
            
            $.each(json.data, function(contador, fila){
                
                idMunicipio = fila.idMunicipio;
                idClienteServicio = fila.idClienteServicio;
                
                
                if(idClienteServicio != "" && idClienteServicio != null && idClienteServicio != "null"){
                    $("#linkServicioInstalado").css("display","block");
                    consultarInformacionServicio();
                    consultarClienteServicioProducto();
                }
                
                $("#txtFechaOrden").datepicker("update",fila.fecha);        
                $("#txtFechaOrden").val(fila.fecha);
                
                $("#selTipoDocumento").val(fila.idTipoDocumento);
                $("#selTipoDocumento").attr("disabled",true);
                $("#txtNoOrdenTrabajo").val(fila.numero);
                $("#selOrdenador").val(fila.idOrdenador);
                $("#txtMunicipio").val(fila.municipio);
                $("#txtFechaInicio").val(fila.fechaInicio);
                $("#txtFechaInicio").datepicker("update", fila.fechaInicio);
                $("#txtFechaFin").val(fila.fechaFin);
                $("#txtFechaFin").datepicker("update", fila.fechaFin);
                $("#selEstadoOrdenTrabajo").val(fila.idEstadoOrdenTrabajo);
                $("#selTipoOrdenTrabajo").val(fila.tipoOrdenTrabajo);
                $("#selTipoOrdenTrabajo").attr("disabled",true);
                $("#txaObservacion").val(fila.observacion);
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarInformacionTecnico(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoTecnico.consultar.php',
        type:'POST',
        dataType:"json",
        data:{
            idOrdenTrabajo:idOrdenTrabajo
            ,estado:true
        },
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
            
            var arrOpciones = new Array();
            var idTecnicoPrincipal = null
            $.each(json.data, function(contador, fila){    
                if(fila.principal == true){
                    idTecnicoPrincipal = fila.idTecnico;
                }
                $("input[name=rdoTecnico][value='" + fila.idTecnico + "']").attr("disabled",false);
                arrOpciones.push(fila.idTecnico);
            });
            
            $("input[name=rdoTecnico][value='" + idTecnicoPrincipal + "']").prop("checked",true);
            $("#selTecnico").val(arrOpciones);
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarInformacionOrdenCliente(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoCliente.consultar.php',
        type:'POST',
        dataType:"json",
        data:{
            idOrdenTrabajo:idOrdenTrabajo
            ,estado:true
        },
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
            
            $.each(json.data, function(contador, fila){    
                secuenciaCliente++;
                var objOrdenTrabClien = new  Object();
                objOrdenTrabClien.idOrdenTrabajoCliente = fila.idOrdenTrabajoCliente;
                objOrdenTrabClien.idOrdenTrabajo = fila.idOrdenTrabajo;
                objOrdenTrabClien.secuencia = secuenciaCliente;
                objOrdenTrabClien.idCliente = fila.idCliente;
                objOrdenTrabClien.cliente = fila.cliente;
                objOrdenTrabClien.nitCliente = fila.nit;
                objOrdenTrabClien.idProductoComposicion = fila.idProductoComposicion;
                objOrdenTrabClien.idProductoCompuesto = fila.idProductoCompuesto;
                objOrdenTrabClien.productoComposicion = fila.productoComposicion;
                objOrdenTrabClien.codigoProducto = fila.codigo;
                objOrdenTrabClien.idPedido = fila.idPedido;
                objOrdenTrabClien.idEstaOrdeTrabClie = fila.idEstaOrdeTrabClie;
                
                consultarProductosOrdenTrabajClie(fila.idOrdenTrabajoCliente);
                
                objOrdenTrabClien.productos = arrProductoComposicion;
                arrOrdenTrabajClient.push(objOrdenTrabClien);
            });
            
            
            crearListaOrdeTrabClie();
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarProductosOrdenTrabajClie(idOrdenTrabajoCliente){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoProducto.consultar.php',
        type:'POST',
        dataType:"json",
        data:{
            idOrdenTrabajoCliente:idOrdenTrabajoCliente
            ,estado:true
        },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            arrProductoComposicion = new Array();
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                return false;
            }
            

            $.each(json.data, function(contador, fila){  
               secuencialProducto++; 
               var objProductoComposicion = new Object();
                objProductoComposicion.idOrdenTrabajoProducto = fila.idOrdenTrabajoProducto;
                objProductoComposicion.idProductoComposicion = fila.idProductoComposicion;
                objProductoComposicion.productoComposicion = fila.productoComposicion;
                objProductoComposicion.idProducto = fila.idProducto;
                objProductoComposicion.codigo = fila.codigoProductoCompone;
                objProductoComposicion.producto = fila.productoCompone;
                objProductoComposicion.unidadMedida = fila.unidadMedida;
                objProductoComposicion.valor = fila.valorUnitario;
                objProductoComposicion.cantidad = fila.cantidad;
                objProductoComposicion.idBodega = fila.idBodega;
                objProductoComposicion.bodega = fila.bodega;
                objProductoComposicion.nota = fila.nota;
                objProductoComposicion.serial = fila.serial;
                objProductoComposicion.secuencial = secuencialProducto;
                objProductoComposicion.idTransaccion = fila.idTransaccion;
                objProductoComposicion.idEstaOrdeTrabProd = fila.idEstaOrdeTrabProd;
                objProductoComposicion.estaOrdeTrabProd = fila.estaOrdeTrabProd;
                
                //Se valida si ya ha sido indicado el serial con anterioridad para no indicarlo de nuevo
                if(fila.serial != "" && fila.serial != null && fila.serial != "null" && fila.serial != "NULL" && fila.idTransaccion != "" && fila.idTransaccion != null){
                    objProductoComposicion.productoSerial = false;
                }else{
                    objProductoComposicion.productoSerial = fila.productoSerial;
                }

                //Se adiciona el producto para consultar los valores de serial disponibles
                if(fila.productoSerial == true){
                    if(arrIdProductoSerial.indexOf(fila.idProducto) == "-1"){
                        consultarProductosSerial(fila.idProducto,fila.idBodega)
                    }
                }
                
                arrProductoComposicion.push(objProductoComposicion);     
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarProductosSerial(idProducto,idBodega){
    arrIdProductoSerial.push(idProducto);
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoProducto.consultarSerialProducto.php',
        type:'POST',
        dataType:"json",
        data:{
            idProducto:idProducto
            ,idBodega:idBodega
            ,estado:true
        },
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
            
            $.each(json.data, function(contador, fila){    
                if(arrOpcioProduSerial[fila.idProducto] == "" || arrOpcioProduSerial[fila.idProducto] == null){
                    arrOpcioProduSerial[fila.idProducto] = new Array();
                }
                
                if(arrOpcioProduSerial[fila.idProducto][fila.idBodega] == "" || arrOpcioProduSerial[fila.idProducto][fila.idBodega] == null){
                    arrOpcioProduSerial[fila.idProducto][fila.idBodega] = new Array();
                }
                
                arrOpcioProduSerial[fila.idProducto][fila.idBodega].push(fila.serial);
                
            });
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function optimizarEspacio(id,divOptimizar){
    $("#" + divOptimizar).slideToggle("medium");
    if(banderaMostrarOcultar == false){
        $('#' + divOptimizar).css("min-height","20px");
        $("#"+id).removeClass("fa fa-angle-double-up imagenesTabla");
        $("#"+id).addClass("fa fa-angle-double-down imagenesTabla");
        banderaMostrarOcultar = true;
    }else{
        $("#"+id).removeClass("fa fa-angle-double-down imagenesTabla");
        $("#"+id).addClass("fa fa-angle-double-up imagenesTabla");
        banderaMostrarOcultar = false;
    }
}
function validarOpcionSerial(posicionCliente,posicionProducto,idControl){
    arrOrdenTrabajClient[posicionCliente].productos[posicionProducto].serial = $("#" + idControl).val();
    validarSeleccionOpcioSerial();
}
function validarSeleccionOpcioSerial(){
    arrSerialSelecc =  new Array();
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        var objInforClien = arrOrdenTrabajClient[i];
        for(var j = 0;j < objInforClien.productos.length ;j++){
            if(objInforClien.productos[j] == null || objInforClien.productos[j] == ""){
                continue;
            }
            var objProducto = objInforClien.productos[j];
            
            if(objProducto.productoSerial == true){
                if($("#selSerialProd" + objProducto.secuencial).val() != "" || $("#selSerialProd" + objProducto.secuencial).val() != null || $("#selSerialProd" + objProducto.secuencial).val() != "null"){
                    arrSerialSelecc.push($("#selSerialProd" + objProducto.secuencial).val());
                }
            }
        }
    }
    crearListaOrdeTrabClie();
}
function elimInforOrdenTrabaClie(){
    if($("#selEstadoOrdenTrabajo").val() == 2){// Entregado Técnico
        eliminarTransaccion();
    }
    
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoCliente.eliminar.php',
        type:'POST',
        dataType:"json",
        data:{
            idOrdenTrabajoCliente:arrIdElimOrdeTrabClie
        },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }
            
        }, error: function(xhr, opciones, error){
            throw  error;
        }
    });
}
function modificarOrdenTrabaClie(){
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        var objInforClie = arrOrdenTrabajClient[i];
        
        idTransaccion = null;
        if(objInforClie.idEstaOrdeTrabClie == 1){ //Se adiciona la transacción cuando esta con estado por entregar
            //Se validan si los productos del servicio existen en inventario
            if(validarExistenciaProductoServicio(objInforClie) == true){
                adicionarTransaccion(objInforClie);
            }else{
               mensajeServicio += "<li>Los productos del servicio <b>" + objInforClie.productoComposicion + "</b> del cliente <b>" + objInforClie.cliente + "</b> no existen completamente o no han sido seleccionados del inventario.<br><br></li>";
            }
        }
        
        $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/ordenTrabajoCliente.modificar.php',
            type:'POST',
            dataType:"json",
            data:{
                    idOrdenTrabajoCliente:objInforClie.idOrdenTrabajoCliente
                    ,idOrdenTrabajo:idOrdenTrabajo
                    ,idCliente:objInforClie.idCliente
                    ,idProductoComposicion:objInforClie.idProductoComposicion
                    ,idPedido:objInforClie.idPedido
                },
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    throw  mensaje;
                }
                
                if(objInforClie.idOrdenTrabajoCliente == "" || objInforClie.idOrdenTrabajoCliente == null || objInforClie.idOrdenTrabajoCliente == "null"){
                    objInforClie.idOrdenTrabajoCliente = json.idOrdenTrabajoCliente;
                }
                
                eliminarOrdenTrabProdu();
                modificarOrdenTrabProdu(objInforClie,objInforClie.productos);
                
            },error: function(xhr, opciones, error){
                throw  error;
            }
        });
    }
}
function eliminarOrdenTrabProdu(){
    
    if($("#selEstadoOrdenTrabajo").val() == 2 && envioInformacion == false){// Entregado Técnico
        reversarTransaccionProducto(arrIdElimOrdeTrabProd);
        envioInformacion = true; 
    }
    
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoProducto.eliminar.php',
        type:'POST',
        dataType:"json",
        data:{
            idOrdenTrabajoProducto:arrIdElimOrdeTrabProd
        },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }
            
        }, error: function(xhr, opciones, error){
            throw  error;
        }
    });
}
function validarSeleccionSerial(){
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        var objInforClien = arrOrdenTrabajClient[i];
        for(var j = 0;j < objInforClien.productos.length ;j++){
            if(objInforClien.productos[j] == null || objInforClien.productos[j] == ""){
                continue;
            }
            
            var objProducto = objInforClien.productos[j];
            
            if(objProducto.productoSerial == true){
                if($("#selSerialProd" + objProducto.secuencial).val() == "" || $("#selSerialProd" + objProducto.secuencial).val() == null || $("#selSerialProd" + objProducto.secuencial).val() == "null"){
                    alerta("Debe seleccionar el serial del producto <b>" + objProducto.producto + "</b> del servicio <b>" + objInforClien.productoComposicion + "</b> del cliente <b>" + objInforClien.cliente + "</b>");
                    return false;
                }
            }
        }
    }
}
function consultarEstadoOrdenTrabajo(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarEstados.php',
        type:'POST',
        dataType:"json",
        data:{
                estado:true
            },
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
            
            var control = $('#selEstadoOrdenTrabajo');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function(contador, fila){
                if(fila.principal == true){
                    control.append('<option selected value="' + fila.idEstadoOrdenTrabajo + '">' + fila.estadoOrdenTrabajo+ '</option>');
                }else{
                    control.append('<option value="' + fila.idEstadoOrdenTrabajo + '">' + fila.estadoOrdenTrabajo+ '</option>');
                }
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarTipoOrdenTrabajo(){
    var control = $('#selTipoOrdenTrabajo');
    control.empty();
    control.append('<option value="">--Seleccione--</option>');
    control.append('<option value="1">Instalación</option>');
    control.append('<option value="2">Mantenimiento</option>');
    control.append('<option value="3">Desinstalación</option>');
}
function cargarBodegas(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarBodegas.php',
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
            
            $.each(json.data, function(contador, fila){                
                var objBodega = new Object();
                objBodega.idBodega = fila.idBodega;
                objBodega.bodega = fila.bodega;
                
                arrBodegas.push(objBodega);
                
            });
            
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function cargarSelectBodega(control){
    $("#" + control).empty();
    if(arrBodegas.length == 1){
        for(var i = 0 ; i < arrBodegas.length;i++){
            var objBodega = arrBodegas[i];
            $("#" + control).append("<option value='" + objBodega.idBodega + "'>" +  objBodega.bodega + "</option>");
        }
        $("#" + control).attr("disabled",true);
    }else{
        $("#" + control).append("<option value=''>--SELECCIONE--</option>");
        for(var i = 0 ; i < arrBodegas.length;i++){
            var objBodega = arrBodegas[i];
            $("#" + control).append("<option value='" + objBodega.idBodega + "'>" +  objBodega.bodega + "</option>");
        }
    }    
}
function validarExistenciaProducto(objProducto,objBodega,validarCantidadTemporal){
    var retorno = false;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.validarExistenciaProducto.php',
        type:'POST',
        dataType:"json",
        data:{
                idProducto:objProducto.idProducto
                ,idBodega:objBodega.idBodega
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
            
            if(json.numeroRegistros == 0){
                alerta(" El producto <b>" + objProducto.producto + "</b> no está disponible en la bodega <b>" + objBodega.bodega + "</b>");
                retorno = false;
            }else{
                
                var cantidadTemporal = 0;
                if(validarCantidadTemporal == true){
                    cantidadTemporal = obtenerCantidadProductoTemporal(objProducto,objBodega);
                }
                
                $.each(json.data,function(contador,fila){
                    if(fila.manejaInventario == false || fila.manejaInventario == "false"){
                        retorno = true;
                    }else{
                        var cantidad = parseInt(fila.cantidad) - cantidadTemporal;
                    
                        if(objProducto.cantidad > cantidad){
                            if(validarCantidadTemporal == true){
                                alerta(" El producto <b>" + objProducto.producto + "</b> a ingresar no existe completamente en la bodega <b>" + objBodega.bodega + "</b><br><br><b>Cantidad requerida :</b> " + objProducto.cantidad + " - " + objProducto.producto +" <br><b>Cantidad disponible : </b>" + cantidad  + " - " + objProducto.producto + "<br><b>Cantidad Faltante : </b>" + (objProducto.cantidad - Math.abs(cantidad)) + " - " + objProducto.producto);
                            }
                        }else{
                            if(validarCantidadTemporal == false){
                                if(objProducto.productoSerial == true){
                                    if($("#selSerialProd" + objProducto.secuencial).val() == "" || $("#selSerialProd" + objProducto.secuencial).val() == null || $("#selSerialProd" + objProducto.secuencial).val() == "null"){
                                        retorno = false;
                                    }else{
                                        retorno = true;
                                    }
                                }else{
                                    retorno = true;    
                                }
                            }else{
                                retorno = true;
                            }
                        }
                    }
               });
            }
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
    return retorno;
}
function obtenerCantidadProductoTemporal(objProductoEvaluar,objBodegaEvaluar){
    var cantidad = 0;
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        var objInforClien = arrOrdenTrabajClient[i];
        for(var j = 0;j < objInforClien.productos.length ;j++){
            
            if(objInforClien.productos[j] == null || objInforClien.productos[j] == ""){
                continue;
            }
            
            var objProducto = objInforClien.productos[j];
            if(objProducto.idTransaccion == "" || objProducto.idTransaccion == null || objProducto.idTransaccion == "null"){
                if(objProducto.idProducto == objProductoEvaluar.idProducto && objProducto.idBodega == objBodegaEvaluar.idBodega && objProducto.secuencial != objProductoEvaluar.secuencial){
                    cantidad = cantidad + parseInt(objProducto.cantidad);
                }
            }
        }
    }
    return cantidad;
}
function adicionarTransaccion(objInforClie){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.adicionarTransaccion.php',
        type:'POST',
        dataType:"json",
        data:{
                idTipoDocumento:idTipoDocumento
                ,idCliente:objInforClie.idCliente
                ,productos:objInforClie.productos
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
            
            idTransaccion = json.idTransaccion;
                    
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function reversarTransaccionProducto(arrIdOrdenTrabProd){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.reversarTransaccionProducto.php',
        type:'POST',
        dataType:"json",
        data:{
                idTipoDocumento:idTipoDocumento
                ,idOrdenTrabajoProducto:arrIdOrdenTrabProd
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                alerta (mensaje);
                return false;
            }
                    
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}
function consultarInformacionServicio(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajo.consultarClienteServicio.php',
        data:{idClienteServicio:idClienteServicio},
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
                alerta("No se encontró información vinculada al servicio seleccionado");
                return false;
            }
            crearListadoServicio(json);

        },error: function(xhr, opciones, error){
            alerta(error);
        }
    });
}
function crearListadoServicio(json){
    $('#divInformacionServicio').html("");
    var tabla = '';
    tabla += '<table class="table table-bordered table-striped consultaTabla">';
    $.each(json.data, function(contador, fila){
        tabla += '<tr>';
        tabla += '<th colspan="2">Información Servicio</th>';
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td><b>Cliente</b></td>';
        tabla += '<td>'+fila.cliente+'</td>';
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td><b>Nro Servicio</b></td>';
        tabla += '<td>'+fila.numero+'</td>';
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td><b>Servicio</b></td>';
        tabla += '<td>'+fila.servicio+'</td>';
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td><b>Fecha Inicial</b></td>';
        tabla += '<td>'+fila.fechaInicial+'</td>';
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td><b>Fecha Final</b></td>';
        
        if(fila.fechaFinal != "" && fila.fechaFinal != null){
            tabla += '<td>'+fila.fechaFinal+'</td>';
        }else{
            tabla += '<td></td>';
        }
        
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td><b>Municipio</b></td>';
        tabla += '<td>'+fila.municipio+'</td>';
        tabla += '</tr>';
        tabla += '<tr>';
        tabla += '<td><b>Valor</b></td>';
        tabla += '<td>'+agregarSeparadorMil(parseInt(fila.valor).toString())+'</td>';
        tabla += '</tr>';
        
        $("#txtNit").val(fila.nit);
        $("#txtCliente").val(fila.cliente);
        idCliente = fila.idCliente;
        
        $("#txtNit").attr("accesskey","L");
        $("#txtNit").attr("disabled",true);
        $("#txtCliente").attr("accesskey","L");
        $("#txtCliente").attr("disabled",true);
        
        
        
    });
    tabla += '</table>';
    $('#divInformacionServicio').html(tabla);
}
function eliminarTransaccion(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.eliminarTransaccion.php',
        type:'POST',
        dataType:"json",
        data:{
            idOrdenTrabajoCliente:arrIdElimOrdeTrabClie
        },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }
            
        }, error: function(xhr, opciones, error){
            throw  error;
        }
    });
}
function obtenerParametroAplicacion(idParametroAplicacion){
    var valorParametro = 0;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.validarParametro.php',
        type:'POST',
        dataType:"json",
        data:{
            idParametroAplicacion:idParametroAplicacion
        },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;
            
            if(exito == 0){
                throw mensaje;
            }
            
            valorParametro = json.valor;
        }, error: function(xhr, opciones, error){
            throw  error;
        }
    });
    return valorParametro;
}

function validarExistenciaProductoServicio(objInforClien){
    var existenciaProductoServicio = true;
    for(var j = 0;j < objInforClien.productos.length ;j++){

        if(objInforClien.productos[j] == null || objInforClien.productos[j] == ""){
            continue;
        }
        var informacionProducto = objInforClien.productos[j];
        
        var objProducto = new Object();
        objProducto.idProducto = informacionProducto.idProducto;
        objProducto.producto = informacionProducto.producto;
        objProducto.cantidad = informacionProducto.cantidad;
        objProducto.secuencial = informacionProducto.secuencial;
        objProducto.productoSerial = informacionProducto.productoSerial;
        objProducto.idTransaccion = informacionProducto.idTransaccion;
        

        var objBodega = new Object();
        objBodega.idBodega = informacionProducto.idBodega;
        objBodega.bodega = informacionProducto.bodega;
        
        if(validarExistenciaProducto(objProducto,objBodega,false) == false){
            existenciaProductoServicio = false;
            break;
        }
    }
    return existenciaProductoServicio;
}
function generarArchivoOrdenTrabajo(){
    if($("#selTipoOrdenTrabajo").val() == 1){//Instalación
        bootbox.confirm("¿Desea generar la orden de trabajo y servicio?", function(result) {
            if(result == true){
                window.open('../controlador/ordenTrabajo.generarOrdenTrabajo.php?idOrdenTrabajo=' + idOrdenTrabajo + "&numeroOrden=" + numeroOrden);

                var idGenerar = "";
                for(var i = 0;i < arrOrdenTrabajClient.length;i++){
                    var objCliente = arrOrdenTrabajClient[i];   
                    idGenerar += objCliente.idOrdenTrabajoCliente + ",";
                }
                idGenerar  = idGenerar.slice(0,-1)
                window.open('../controlador/ordenTrabajo.generarOrdenServicio.php?idOrdenTrabajoCliente=' + idGenerar);

            }
            alerta("La orden de trabajo se modificó correctamente",true);
        }); 
    }else{
        bootbox.confirm("¿Desea generar la orden de trabajo y mantenimiento?", function(result) {
            if(result == true){
                //window.open('../controlador/ordenTrabajo.generarOrdenTrabajo.php?idOrdenTrabajo=' + idOrdenTrabajo + "&numeroOrden=" + numeroOrden);
                /*
                var idGenerar = "";
                for(var i = 0;i < arrOrdenTrabajClient.length;i++){
                    var objCliente = arrOrdenTrabajClient[i];   
                    idGenerar += objCliente.idOrdenTrabajoCliente + ",";
                }
                idGenerar  = idGenerar.slice(0,-1)
                window.open('../controlador/ordenTrabajo.generarOrdenMantenimiento.php?idOrdenTrabajoCliente=' + idGenerar);
                */
               window.open('../controlador/ordenTrabajo.generarOrdenMantenimiento.php?idOrdenTrabajo=' + idOrdenTrabajo + "&numeroOrden=" + numeroOrden);
               
               var idGenerar = "";
                for(var i = 0;i < arrOrdenTrabajClient.length;i++){
                    var objCliente = arrOrdenTrabajClient[i];   
                    idGenerar += objCliente.idOrdenTrabajoCliente + ",";
                }
                idGenerar  = idGenerar.slice(0,-1)
                window.open('../controlador/ordenTrabajo.generarOrdenServicio.php?idOrdenTrabajoCliente=' + idGenerar);
               
            }
            alerta("La orden de trabajo se modificó correctamente",true);
        }); 
    }
}


function validarInformacionServicio(){
    var retorno = true;
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        var objInforClien = arrOrdenTrabajClient[i];
        for(var j = 0;j < objInforClien.productos.length ;j++){

            if(objInforClien.productos[j] == null || objInforClien.productos[j] == ""){
                continue;
            }

            var objProducto = objInforClien.productos[j];
            if(objProducto.idBodega == ""  || objProducto.idBodega == null || objProducto.idBodega == "null"){
                alerta("Debe indicar la bodega de los productos vinculados al servicio");
                return false;
            }
        }
    }
    return retorno;
}
function validarServicioProducto(){
    for(var i = 0;i < arrOrdenTrabajClient.length ;i++){
        var objInforClien = arrOrdenTrabajClient[i];
        
        if(objInforClien.idOrdenTrabajoCliente != "" && objInforClien.idOrdenTrabajoCliente != null && objInforClien.idEstaOrdeTrabClie == 1 && objInforClien.productos.length == 0){
            $.ajax({
                async: false,
                url: localStorage.modulo + 'controlador/ordenTrabajoCliente.actualizarEstado.php',
                type:'POST',
                dataType:"json",
                data:{
                    idOrdenTrabajoCliente:objInforClien.idOrdenTrabajoCliente
                    ,idEstaOrdeTrabClie:2//Instalado
                },
                success: function(json){
                    var mensaje = json.mensaje;
                    var exito = json.exito;

                    if(exito == 0){
                        throw mensaje;
                    }
                }, error: function(xhr, opciones, error){
                    throw  error;
                }
            });
        }
    }
}
function consultarListadoVehiculos(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajo.consultarVehiculo.php',
        type:'POST',
        dataType:"json",
        data:null,
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;

            if(exito == 0){
                throw mensaje;
            }
            
            if(json.numeroRegistros == 0){
                cargarListadoVehiculos();
                return false;
            }
            crearListadoVehiculos(json);
            
        }, error: function(xhr, opciones, error){
            throw  error;
        }
    });
}
function cargarListadoVehiculos(){
    $("#divInformacionVehiculos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">'; 
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Vehiculo</th>'; 
    tabla += '<th>Marca</th>';
    tabla += '<th>Tipo Vehículo</th>';
    tabla += '<th>Color</th>';
    tabla += '<th>Seleccionar</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    tabla += '</table>';
    $("#divInformacionVehiculos").html(tabla);	
}
function crearListadoVehiculos(json){
    $("#divInformacionVehiculos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">'; 
    tabla += '<tr>';
    tabla += '<th colspan="6">Vehículos</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Vehiculo</th>'; 
    tabla += '<th>Marca</th>';
    tabla += '<th>Tipo Vehículo</th>';
    tabla += '<th>Color</th>';
    tabla += '<th>Seleccionar</th>';
    tabla += '</tr>';
    $.each(json.data,function(contador,fila){
        tabla += '<tr>';
        tabla += "<td>" + (contador + 1) + "</td>";
        tabla += "<td>" + fila.vehiculo + "</td>";
        tabla += "<td>" + fila.marca + "</td>";
        tabla += "<td>" + fila.tipoVehiculo + "</td>";
        tabla += "<td>" + fila.color + "</td>";
        tabla += "<td style='text-align:center'><input type='checkbox' id='chkVehiculo" + fila.idVehiculo + "' name='chkVehiculo' value='" + fila.idVehiculo + "'></td>";
        tabla += '</tr>';
    });
    tabla += '</table>';
    $("#divInformacionVehiculos").html(tabla);	
}
function adicionarOrdenTrabaVehic(){
    arrOrdenTrabajVehic = new Array();
    
    $('input:checkbox[name=chkVehiculo]:checked').each(function() {
        var objOrdenTrabaVehic = new Object();
        objOrdenTrabaVehic.idVehiculo = $(this).val();
        objOrdenTrabaVehic.estado = "true";
        arrOrdenTrabajVehic.push(objOrdenTrabaVehic);
    });
    
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajoVehiculo.adicionar.php',
        type:'POST',
        dataType:"json",
        data:{
                idOrdenTrabajo:idOrdenTrabajo
                ,ordenTrabajoVehiculo:arrOrdenTrabajVehic
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }
            
        },error: function(xhr, opciones, error){
            throw error;
            return false;
        }
    });
    
}
function elimiOrdenTrabaVehi(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/ordenTrabajoVehiculo.eliminar.php',
        type:'POST',
        dataType:"json",
        data:{
                idOrdenTrabajo:idOrdenTrabajo
            },
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                throw mensaje;
                return false;
            }
        },error: function(xhr, opciones, error){
            throw error;
            return false;
        }
    });
}
function consultarInformacionVehiculo(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/ordenTrabajoVehiculo.consultar.php',
        type:'POST',
        dataType:"json",
        data:{
                idOrdenTrabajo:idOrdenTrabajo
                ,estado:true
            },
        success: function(json){
            var mensaje = json.mensaje;
            var exito = json.exito;

            if(exito == 0){
                throw mensaje;
            }
            
            if(json.numeroRegistros != 0){
                $.each(json.data,function(contador,fila){
                   $("#chkVehiculo" + fila.idVehiculo).prop("checked", true);
                });
            }
            
            
        }, error: function(xhr, opciones, error){
            throw  error;
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
            
            var tabla = '';
            tabla += '<tr>';
            tabla += '<th colspan="8">Productos instalados</th>';
            tabla += '</tr>';
            tabla += '<tr id="trEstatico">';
            tabla += '<th rowspan="2">Código Producto</th>';
            tabla += '<th rowspan="2">Producto</th>';
            tabla += '<th colspan="9">Productos que Componen</th>';
            tabla += '</tr>';
            tabla += '<tr id="trEstatico">';
            tabla += '<th>Código Producto</th>';
            tabla += '<th>Producto</th>';
            tabla += '<th>Cantidad</th>';
            tabla += '<th>Unidad Medida</th>';
            tabla += '<th>Serial</th>';
            tabla += '<th>Nota</th>';
            tabla += '</tr>';
            
            var idProductoComposicionTabla = null;
            
            $.each(json.data, function(contador, fila){
                
                tabla += '<tr>';
                if(idProductoComposicionTabla != fila.idProductoComposicion){
                    idProductoComposicionTabla = fila.idProductoComposicion;
                    tabla += '<td rowspan="'+fila.cantidadProductoCompone+'" align="right">'+fila.codigoProductoComposicion+'</td>';
                    tabla += '<td rowspan="'+fila.cantidadProductoCompone+'">'+fila.productoComposicion+'</td>';
                }
                tabla += '<td>'+fila.codigoProductoCompone+'</td>';
                tabla += '<td>'+fila.productoCompone+'</td>';
                tabla += '<td align="right">'+parseInt(fila.cantidad)+'</td>';
                tabla += '<td>'+fila.unidadMedida+'</td>';
                
                if(fila.serial != "" &&  fila.serial != null &&  fila.serial != "null"){
                    tabla += '<td>'+fila.serial+'</td>';
                }else{
                    tabla += '<td></td>';
                }
                
                var nota = fila.nota;
                if(nota == "" || nota == null ||  nota == "null"){
                    nota = "";
                }
                tabla += '<td>' + nota + '</td>';
                tabla += '</tr>';
            });
            $('#divInformacionProductosServicio').html(tabla);
            
        }, error: function(xhr, opciones, error){
            throw error;
        }
    });
}