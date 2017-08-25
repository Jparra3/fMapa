var idBodega = null;
var idProducto = null;
var idLineaProducto = null;
var bodega = null;
var serial = null;

var data = null;
var tabla = '';
    tabla += '<tr id="trEstatico">';
    tabla += '<th>#</th>';
    tabla += '<th>Código Producto</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Serial</th>';
    tabla += '<th>Serial Interno</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Subtotal($)</th>';
    tabla += '<th>Línea Producto</th>';
    tabla += '<th>Oficina</th>';
    tabla += '<th>Bodega</th>';
    tabla += '</tr>';

//INVENTARIO INSTALADO
var idCliente = null;
$(function(){
    cargarTabla();
    cargarProductos();
    cargarBodegas();
    
    clickInventario();
    
    //Inventario instalado
    autoCompletarCliente();
    cargarServicio();
    cargarProductoInstalado();
    cargarBodegaProductoInstalado();
//    crearTablaResporte();
    $("#tabProducto").bind({
       "click":function(){
           cargarTabla();
           clickInventario();
       } 
    });
    $("#tabProductoInstalado").bind({
       "click":function(){
           crearTablaResporteInventarioInstalado();
           clickInventarioInstalado();
       } 
    });
    
    $("#txtProducto").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                idProducto = null;
            break;
        }
    });
    
    $("#btnBuscarLineas").click(function(){
        cargarLinea();
    });
});

function clickInventario(){
    $('#imgConsultar').unbind("click");
    $('#imgLimpiar').unbind("click");
    $('#imgConsultar').bind({
        'click':function(){
            
            obtenerDatos();
            $.ajax({
                async:false,
                url:'../controlador/productoExistencia.consultarInventario.php',
                type:'POST',
                dataType:'json',
                data:data,
                success:function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    
                    if(exito == 0){
                        alerta(mensaje);
                        return;
                    }
                    if(json.numeroRegistros == 0){
                        cargarTabla();
                        alerta('No se encontraron registros con los parámetros indicados');
                        return;
                    }
                    
                    crearListado(json);
                }
            });
        }
    });
    $('#imgLimpiar').bind({
        'click':function(){
            limpiarVariables();
            cargarTabla();
            limpiarControlesFormulario(document.fbeFormulario);
            $('#selBodega').val('').multiselect('destroy');
            $('#selBodega').multiselect({
                maxHeight: 600
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
            
            $("#selProducto").multiselect('destroy');
            $("#selProducto").val('');
            $('#selProducto').multiselect({
                maxHeight: 400
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
        }
    });
}

function clickInventarioInstalado(){
    $('#imgConsultar').bind({
        'click':function(){
            var data = obtenerValorEnvio();
            consultarInventarioInstalado(data);
        }
    });
    
    $('#imgLimpiar').bind({
        'click':function(){
            limpiarControles();
        }
    });
}

function obtenerValorEnvio() {
    var data = "";
    data += "idCliente=" + idCliente;
    data += "&idProducto=" + $("#selProductoInstalado").val();
    data += "&idServicio=" + $("#selServicioInstalado").val();
    data += "&idBodega=" + $("#selBodegaProductoInstalado").val();
    data += "&serial=" + $("#txtSerial").val();
    return data;
}

function asignarValores(){
    bodega = $('#imgBodega').prop('name');
    idBodega = $('#selBodega').val();
    idProducto = $('#selProducto').val();
    serial = $("#txtSerial").val();
}
function obtenerDatos(){
    asignarValores();
    data = 'idLineaProducto='+idLineaProducto+'&bodega='+bodega+'&idProducto='+idProducto+'&idBodega='+idBodega +'&serial='+serial;
}
function limpiarVariables(){
    idProducto = null;
    idBodega = null;
    idLineaProducto = null;
    idOficina = null;
    bodega = null;
    data = null;
}
function cargarTabla(){
    var table = '';
    table += '<tr>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '<td>&nbsp</td>';
    table += '</tr>';
    
    $('#consultaTabla').html(tabla + table);
}
function cargarProductos(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/producto.consultar.php',
        type:'POST',
        dataType:"json",
        data:{estado: true},
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
            var control = $('#selProducto');
            control.empty();
            
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idProducto + '">' + fila.codigo + ' - ' + fila.producto + '</option>');
            });
            
            $('#selProducto').multiselect({
                maxHeight: 400
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
            
            $("button.multiselect").css("width","300px");
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function cargarBodegas(){
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
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
            
            var control = $('#selBodega');
            control.empty();
            
            $.each(json.data, function(contador, fila){                
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
            $('#selBodega').multiselect({
                maxHeight: 600
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
            });
        }, error: function(xhr, opciones, error){
            alert(error);
            return false;
        }
    });
}

function autoCompletarProducto(){
    $("#txtProducto").autocomplete({
        source: localStorage.modulo + 'ajax/producto.autoCompletarProducto.php',
        select:function(event, ui){
            idProducto = ui.item.idProducto;
        }
    });
}
function crearListado(json){
    var table = '';
    
    var sumatoriaTotal = 0;
    $.each(json.data, function(contador, fila){
        
        sumatoriaTotal += fila.total;
        
        table += '<tr>';
        table += '<td align="right">'+(contador+1)+'</td>';
        table += '<td align="right">'+fila.codigo+'</td>';
        table += '<td>'+fila.producto+'</td>';
        table += '<td align="right">'+fila.cantidad+'</td>';
        
        if(fila.serial != "" && fila.serial != null && fila.serial != null){
            table += '<td align="right">'+fila.serial+'</td>';
        }else{
            table += '<td align="right"></td>';
        }
        
        if(fila.serialInterno != "" && fila.serialInterno != null && fila.serialInterno != null){
            table += '<td align="right">'+fila.serialInterno+'</td>';
        }else{
            table += '<td align="right"></td>';
        }
        
        table += '<td align="right">'+agregarSeparadorMil(parseInt(fila.valor).toString())+'</td>';
        table += '<td align="right">'+agregarSeparadorMil(parseInt(fila.total).toString())+'</td>';
        table += '<td>'+fila.lineaProducto+'</td>';
        table += '<td>'+fila.oficina+'</td>';
        if(fila.bodega != 'null' && fila.bodega != '' && fila.bodega != null)
            table += '<td>'+fila.bodega+'</td>';
        else
            table += '<td>TODAS</td>';
        table += '</tr>';
    });
    $('#consultaTabla').html(tabla + table);
}
function cambiarCheck(control){
    var name = $(control).prop('name');
    if(name == 'true'){
        $(control).attr('src', '../imagenes/casilla_vacia.png');
        $(control).attr('name', 'false');
    }else{
        $(control).attr('src', '../imagenes/casilla_marcada.png');
        $(control).attr('name', 'true');
    }
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

///Funciones de inventario instalado
function autoCompletarCliente() {
    $("#txtCliente").autocomplete({
        source: localStorage.modulo + 'ajax/cliente.autocompletarSucursal.php',
        select: function(event, ui) {
            idCliente = ui.item.idCliente;
        }
    });
}

function cargarServicio() {
    var dataProducto = "";
    dataProducto += "estado=TRUE";
    dataProducto += "&productoServicio=FALSE";
    crearSelectServicio(consultarProductoServicio(dataProducto));
}

function cargarProductoInstalado() {
    var dataProducto = "";
    dataProducto += "estado=TRUE";
    dataProducto += "&productoServicio=TRUE";
    crearSelectProducto(consultarProductoServicio(dataProducto));
}

function cargarBodegaProductoInstalado() {
    $.ajax({
        url: localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
        type: 'POST',
        dataType: "json",
        data: null,
        success: function(json) {
            var mensaje = json.mensaje;
            var exito = json.exito;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            var control = $('#selBodegaProductoInstalado');
            control.empty();

            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
            $('#selBodegaProductoInstalado').multiselect({
                maxHeight: 600
                , nonSelectedText: '--Seleccione--'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });
            $("button.multiselect").css("width", "300px");
        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}

function crearSelectServicio(json) {
    var control = $('#selServicioInstalado');
    control.empty();

    $.each(json.data, function(contador, fila) {
        control.append('<option value="' + fila.idProducto + '">' + fila.codigo + ' - ' + fila.producto + '</option>');
    });

    $('#selServicioInstalado').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $("button.multiselect").css("width", "300px");
}

function consultarProductoServicio(data) {
    var jsonServicioProducto = null;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/producto.consultar.php',
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
                return false;
            }

            jsonServicioProducto = json;
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
    return jsonServicioProducto;
}

function crearSelectProducto(json) {
    var control = $('#selProductoInstalado');
    control.empty();

    $.each(json.data, function(contador, fila) {
        control.append('<option value="' + fila.idProducto + '">' + fila.codigo + ' - ' + fila.producto + '</option>');
    });

    $('#selProductoInstalado').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $("button.multiselect").css("width", "300px");
}

function consultarProductoServicio(data) {
    var jsonServicioProducto = null;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/producto.consultar.php',
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
                return false;
            }

            jsonServicioProducto = json;
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
    return jsonServicioProducto;
}

function crearTablaResporteInventarioInstalado() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Código </th>";
    html += "<th> Producto </th>";
    html += "<th> Servicio </th>";
    html += "<th> Cliente - Sucursal </th>";
    html += "<th> Zona </th>";
    html += "<th> Cantidad </th>";
    html += "<th> ($)Valor por unidad </th>";
    html += "<th> ($)Subtotal </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "</tr>";
    $('#consultaTabla').html(html);
}

function consultarInventarioInstalado(data) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.consultarInventarioInstalado.php',
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
                alerta("No se encontraron registros con los parámetros indicados.");
                limpiarControles();
                return false;
            }
            visualizarReporteInventarioInstalado(json);
            $("#imgExportar").show();
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function visualizarReporteInventarioInstalado(json) {
    if (json.data != null) {
        var html = "";
        html += "<tr>";
        html += "<th> # </th>";
        html += "<th> Código </th>";
        html += "<th> Producto </th>";
        html += "<th> Servicio </th>";
        html += "<th> Cliente - Sucursal</th>";
        html += "<th> Zona </th>";
        html += "<th> Cantidad </th>";
        html += "<th> ($)Valor por unidad </th>";
        html += "<th> ($)Subtotal </th>";
        //html += "<th> idCliente </th>"; Para verificar los colores
        html += "</tr>";
        var clienteAnterior = null;
        var color = "";
        var estilo = "";
        $.each(json.data, function(contador, fila) {
            if (idCliente == null || idCliente == undefined) {
                if (clienteAnterior != fila.idCliente) {
                    clienteAnterior = fila.idCliente;
                    if (color != "") {
                        color = "";
                    } else {
                        color = "#E6E6E6";
                    }
                }
            }
            html += "<tr bgcolor='" + color + "'>";
            html += "<td class='valorFijo'> " + parseInt(contador + 1) + " </td>";
            html += "<td class='valorNumerico'> " + fila.codigo + " </td>";
            html += "<td class='valorTexto'> " + fila.producto + " </td>";
            html += "<td class='valorTexto'> " + fila.servicio + " </td>";
            html += "<td class='valorTexto'> " + fila.cliente + " - " + fila.sucursal + " </td>";
            html += "<td class='valorTexto'> " + fila.zona + " </td>";
            html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(fila.cantidad).toString()) + " </td>";
            html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(fila.valorUnidad).toString()) + " </td>";
            html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(fila.subtotal).toString()) + " </td>";
            //html += "<td class='valorNumerico'> "+fila.idCliente+" </td>";  Para verificar los colores
            html += "</tr>";
        });
        html += "<tr>";
        html += "<td colspan='8' class='valorNumerico'> ($)Total</td>";
        html += "<td class='valorNumerico'> " + agregarSeparadorMil(parseInt(json.data[0].total).toString()) + " </td>";
        html += "</tr>";
        $('#consultaTabla').html(html);
    }
}

function limpiarControles() {
    limpiarControlesFormulario(document.fbeFormularioInstaldo);
    $('#selServicioInstalado').val("");
    $('#selServicioInstalado').multiselect('destroy');
    $('#selServicioInstalado').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $('#selProductoInstalado').val("");
    $('#selProductoInstalado').multiselect('destroy');
    $('#selProductoInstalado').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });

    $('#selBodegaProductoInstalado').val("");
    $('#selBodegaProductoInstalado').multiselect('destroy');
    $('#selBodegaProductoInstalado').multiselect({
        maxHeight: 400
        , nonSelectedText: 'Seleccione'
        , enableFiltering: true
        , filterPlaceholder: 'Buscar'
        , numberDisplayed: 1
        , enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width", "300px");

    idCliente = null;
    crearTablaResporteInventarioInstalado();
}