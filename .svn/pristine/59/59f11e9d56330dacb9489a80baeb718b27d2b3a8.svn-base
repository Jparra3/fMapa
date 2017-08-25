var idProducto = null;
var cantidad = null;
var data = null;

$(function () {
    
    cargarListadoProductosComponen();
    cargarListadoProductosComponenCalculado();
    autoCompletarProducto();
    
    $("#txtCodigoProductoCompuesto").change(function () {
        if ($("#txtCodigoProductoCompuesto").val() != "") {
            consultarProducto($("#txtCodigoProductoCompuesto").val());
        }
    });
    
    $("#txtCodigoProductoCompuesto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtProductoCompuesto").val("");
                idProducto = null;
                cargarListadoProductosComponen();
                cargarListadoProductosComponenCalculado();
                break;
        }
    });
    
    $("#txtProductoCompuesto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProductoCompuesto").val("");
                idProducto = null;
                cargarListadoProductosComponen();
                cargarListadoProductosComponenCalculado();
                break;
        }
    });
    
    $("#btnCalcular").click(function(){
        
        obtenerDatosEnvio();
        
        if(idProducto == "" || idProducto == "null" || idProducto == null){
            alerta("Por favor indique el producto.");
            return false;
        }
        
        if(cantidad == "" || cantidad == "null" || cantidad == null){
            alerta("Por favor indique la cantidad a calcular.");
            return false;
        }
        
        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/productoCompuesto.calcular.php',
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

                if (json.numeroRegistros == 0) {
                    cargarListadoProductosComponenCalculado();
                    return false;
                }

                crearListadoProductosComponenCalculado(json);

            }, error: function (xhr, opciones, error) {
                alerta(error);
                return false;
            }
        });
    });
    
    $('#imgGuardar').click(function () {
        
        obtenerDatosEnvio();

        if(idProducto == "" || idProducto == "null" || idProducto == null){
            alerta("Por favor indique el producto.");
            return false;
        }

        if(cantidad == "" || cantidad == "null" || cantidad == null){
            alerta("Por favor indique la cantidad.");
            return false;
        }

        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/productoCompuesto.adicionar.php',
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

                alerta(mensaje, true);

            }, error: function (xhr, opciones, error) {
                alerta(error);
                return false;
            }
        });
    });
});


function asignarDatosEnvio() {
    cantidad = $("#txtCantidadProductoCompuesto").val();
}

function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idProducto=' + idProducto + '&cantidad=' + cantidad;
}

function consultarProducto(codigoProducto) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/productoCompuesto.consultarProducto.php',
        type: 'POST',
        dataType: "json",
        data: {codigoProducto: codigoProducto},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                $("#txtProductoCompuesto").val("");
                idProducto = null;
                cargarListadoProductosComponen();
                cargarListadoProductosComponenCalculado();
                return false;
            }

            $.each(json.data, function (contador, fila) {
                idProducto = fila.idProducto;
                $("#txtProductoCompuesto").val(fila.producto);
            });
            
            consultarProductosComponen();
            
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function autoCompletarProducto() {
    $("#txtProductoCompuesto").autocomplete({
        source: localStorage.modulo + 'ajax/productoCompuesto.autoCompletar.php',
        select: function (event, ui) {
            idProducto = ui.item.idProducto;
            $("#txtCodigoProductoCompuesto").val(ui.item.codigo);
            consultarProductosComponen();
        }
    });
}

function consultarProductosComponen() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/productoCompuesto.consultarProductosComponen.php',
        type: 'POST',
        dataType: "json",
        data: {idProducto: idProducto},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                cargarListadoProductosComponen();
                return false;
            }
            
            crearListadoProductosComponen(json);
            
        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function cargarListadoProductosComponen(){
    $("#divProductosComponen").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th colspan="5">PRODUCTOS QUE LO COMPONEN</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Unidad de medida</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#divProductosComponen").html(tabla);
}
function crearListadoProductosComponen(json){
    $("#divProductosComponen").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th colspan="5">PRODUCTOS QUE LO COMPONEN</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Unidad de medida</th>';
    tabla += '</tr>';
    
    $.each(json.data, function(contador, fila){
        tabla += '<tr>';
        tabla += '<td style="text-align:center;">' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.codigo + '</td>';
        tabla += '<td>' + fila.producto + '</td>';
        tabla += '<td style="text-align:right;">' + fila.cantidad + '</td>';
        tabla += '<td>' + fila.unidadMedida + '</td>';
        tabla += '</tr>';
    });
    
    tabla += '</table>';
    $("#divProductosComponen").html(tabla);
}
function cargarListadoProductosComponenCalculado(){
    $("#divProductosComponenCalculado").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla" style="width: 70%;">';
    tabla += '<tr>';
    tabla += '<th colspan="5">MATERIA PRIMA</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Unidad de medida</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#divProductosComponenCalculado").html(tabla);
}
function crearListadoProductosComponenCalculado(json){
    $("#divProductosComponenCalculado").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla" style="width: 70%;">';
    tabla += '<tr>';
    tabla += '<th colspan="5">MATERIA PRIMA</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Unidad de medida</th>';
    tabla += '</tr>';
    
    $.each(json.data, function(contador, fila){
        tabla += '<tr>';
        tabla += '<td>' + (contador + 1) + '</td>';
        tabla += '<td>' + fila.codigo + '</td>';
        tabla += '<td>' + fila.producto + '</td>';
        tabla += '<td style="text-align:right;">' + fila.cantidad + '</td>';
        tabla += '<td>' + fila.unidadMedida + '</td>';
        tabla += '</tr>';
    });
    
    tabla += '</table>';
    $("#divProductosComponenCalculado").html(tabla);
}