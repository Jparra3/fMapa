var idMunicipio = null;
var idCliente = null;
var idClienteServicio = null;
var numero = null;
var serial = null;
var idProducto = null;

var data = null;
var tabla = '';
tabla += '<tr id="trEstatico">';
tabla += '<th>#</th>';
tabla += '<th>Nro. Orden</th>';
tabla += '<th>Cliente</th>';
tabla += '<th>Fecha Inicial</th>';
tabla += '<th>Fecha Final</th>';
tabla += '<th>Servicio</th>';
tabla += '<th>Producto</th>';
tabla += '<th>Unidad Medida</th>';
tabla += '<th>Serial</th>';
tabla += '<th>Municipio</th>';
tabla += '</tr>';

$(function(){
    autoCompletarCliente();
    cargarProductos();
    cargarListado();
    autoCompletarMunicipio();
    validarNumeros('txtNit');
    validarNumeros('txtNumeroOrden');
    
    $("#txtMunicipio").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                idMunicipio = null;
            break;
        }
    });
    
    $("#imgLimpiar").click(function(){
        limpiarVariables();
        cargarListado();
        limpiarControlesFormulario(document.fbeFormulario);
        $('#selProducto').multiselect("destroy");
        $('#selProducto').val("")
        $('#selProducto').multiselect({
                maxHeight: 400
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
        });

        $("button.multiselect").css("width","300px");
    });
    
    $('#imgConsultar').bind({
        'click':function(){
            obtenerDatos();
            $.ajax({
                async:false,
                url: localStorage.modulo + 'controlador/ordenServicioProducto.consultar.php',
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

                    if(json.numeroRegistros == 0){
                        alerta('No se encontraron registros con los parámetros indicados.');
                        cargarListado();
                        return false;
                    }
                    
                    var table = '';
                    var idClienteActual = null;
                    var colorActual = "";
                    $.each(json.data, function(contador, fila){
                        
                        if((idClienteActual != fila.idCliente || idClienteActual == null)){
                            if(colorActual == "#E6E6E6"){
                                colorActual = "#FAFAFA";
                            }else{
                                colorActual = "#E6E6E6";
                            }
                            idClienteActual = fila.idCliente;
                        }
                        table += '<tr>';
                        table += '<td style="background-color:' + colorActual + '" >'+(contador + 1)+'</td>';
                        table += '<td style="background-color:' + colorActual + '" >'+fila.numero+'</td>';
                        table += '<td style="background-color:' + colorActual + '" >'+fila.cliente+'</td>';
                        table += '<td style="background-color:' + colorActual + '" align="center">'+fila.fechaInicio+'</td>';
                        if(fila.fechaFin != "" && fila.fechaFin != null && fila.fechaFin != "null" && fila.fechaFin != "NULL"){
                            table += '<td style="background-color:' + colorActual + '" align="center">'+fila.fechaFin+'</td>';
                        }else{
                            table += '<td style="background-color:' + colorActual + '" align="center"></td>';
                        }
                        table += '<td style="background-color:' + colorActual + '">'+fila.productoComposicion +'</td>';
                        table += '<td style="background-color:' + colorActual + '">'+fila.productoCompone+'</td>';
                        table += '<td style="background-color:' + colorActual + '">'+fila.unidadMedida +'</td>';
                        
                        if(fila.serial != "" && fila.serial != null && fila.serial != "null"){
                            table += '<td style="background-color:' + colorActual + '">'+fila.serial +'</td>';
                        }else{
                            table += '<td style="background-color:' + colorActual + '"></td>';
                        }
                        table += '<td style="background-color:' + colorActual + '">'+fila.municipio +'</td>';
                        table += '</tr>';
                    });
                    
                    $('#consultaTabla').html(tabla + table);
                },error: function(xhr, opciones, error){
                    alerta (error);
                    return false;
                }
            });
        }
    });
    $("#txtNit").change(function(){
        if($("#txtNit").val() == '')
            return;
        consultarTercero( $("#txtNit").val());
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

function asignarValores(){
    numero = $('#txtNumeroOrden').val();
    idProducto = $('#selProducto').val();
    serial = $('#txtSerial').val();
}
function obtenerDatos(){
    asignarValores();
    data = 'idMunicipio='+idMunicipio+'&numero='+numero+'&idCliente='+idCliente  +'&serial=' + serial +'&idProducto=' + idProducto;
}

function cargarListado(){
    var table = '';
    table += '<tr>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '<td>&nbsp;</td>';
    table += '</tr>';
    
    $('#consultaTabla').html(tabla + table);
}
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
        url:'/Seguridad/controlador/tercero.consultar.php',
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
            
            $.each(json.data, function(contador, fila){
                consultarSucursal(fila.idTercero, fila.tercero);
            });
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function consultarSucursal(idTercero, tercero){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/cliente.consultar.php',
        type:'POST',
        dataType:"json",
        data:{idTercero:idTercero},
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
                    $("#txtCliente").val(tercero+' - '+fila.sucursal);
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
                html += 'Por favor escoja una sucursal de '+tercero+'</div>';
                
                html += '<ul style="list-style:none; margin-top:1%;">';
                $.each(json.data, function(contador, fila){
                    html += '<li><a data-dismiss="modal" style="cursor:pointer" onclick="asignar(\''+fila.terceroSucursal+'\','+fila.idCliente+')">'+fila.sucursal+'</li>'; 
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
function autoCompletarMunicipio(){
    $("#txtMunicipio").autocomplete({
        source: localStorage.modulo + 'ajax/ordenTrabajo.autocompletarMunicipio.php',
        select:function(event, ui){
            idMunicipio = ui.item.idMunicipio;
        }
    });
}
function limpiarVariables(){
    idMunicipio = null;
    idCliente = null;
    idClienteServicio = null;
    idEstadoClienteServicio = null;
    numero = null;
    fechaInicialInicio = null;
    fechaInicialFin = null;
    fechaFinalInicio = null;
    fechaFinalFin = null;
    tipoOrden = null;

    data = null;
}
function consultarTipoOrdenTrabajo(){
    var control = $('#selTipoOrdenTrabajo');
    control.empty();
    control.append('<option value="">--Seleccione--</option>');
    control.append('<option value="1">Instalación</option>');
    control.append('<option value="2">Mantenimiento</option>');
    control.append('<option value="3">Desinstalación</option>');
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