var idCliente=null;
var idEstadoFactura=null;
var fechaInicialInicio=null;
var fechaInicialFin=null;
var fechaFinalInicio=null;
var fechaFinalFin=null;

var data = null;
var tabla = '';
    tabla += '<tr>';
    tabla += '<th>Cliente</th>';
    tabla += '<th>Fecha Inicial</th>';
    tabla += '<th>Fecha Final</th>';
    tabla += '<th>Servicio</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Estado</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';

$(function(){
    crearCalendarioDoble("txtFechaInicialInicio", "txtFechaInicialFin");
    crearCalendarioDoble("txtFechaFinalInicio", "txtFechaFinalFin");
    validarNumeros('txtNit');
    autoCompletarCliente();
    cargarEstados();
    cargarTabla();
    
    $("#txtNit").change(function(){
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
    
    $('#imgConsultar').bind({
        'click':function(){
            obtenerDatos();
            $.ajax({
               async:false,
               url:'../controlador/factura.consultar.php',
               type:'POST',
               dataType:'json',
               data:data,
               success:function(json){
                   var exito = json.exito;
                   var mensaje = json.mensaje;
                   
                   if(json.numeroRegistros == 0){
                       alerta('No se encontraron registros con los parámetros indicados.');
                       return;
                   }
                   
                   if(exito == 0){
                       alerta(mensaje);
                       return;
                   }
                   crearListado(json);
               }, error:function(xhr, opciones, error){
                   alerta(error);
               }
            });
        }
    });
     $('#imgLimpiar').bind({
        'click':function(){
            cargarTabla();
            limpiarVariables();
            limpiarControlesFormulario(document.fbeFormulario);
        }
    });
});
function asignarValores(){
    idEstadoFactura = $('#selEstadoFactura').val();
    fechaInicialInicio=$('#txtFechaInicialInicio').val();
    fechaInicialFin=$('#txtFechaInicialFin').val();
    fechaFinalInicio=$('#txtFechaFinalInicio').val();
    fechaFinalFin=$('#txtFechaFinalFin').val();
}
function obtenerDatos(){
    asignarValores();
    data = 'idEstadoFactura='+idEstadoFactura+'&fechaInicialInicio='+fechaInicialInicio+'&fechaInicialFin='+fechaInicialFin+'&fechaFinalInicio='+fechaFinalInicio+'&fechaFinalFin='+fechaFinalFin+'&idCliente='+idCliente;
}
function limpiarVariables(){
    idCliente=null;
    idEstadoFactura=null;
    fechaInicialInicio=null;
    fechaInicialFin=null;
    fechaFinalInicio=null;
    fechaFinalFin=null;

    data = null;
}
function cargarEstados(){
    $.ajax({
        async:false,
        url:'../controlador/estadoFactura.consultar.php',
        type:'POST',
        dataType:'json',
        data:null,
        success:function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){
                alerta(mensaje);
                return;
            }
            
            var control = $('#selEstadoFactura');
            control.empty();
            $.each(json.data, function(contador, fila){
                control.append('<option value="'+fila.idEstadoFactura+'">'+fila.estadoFactura+'</option>');
            });
            
            $("#selEstadoFactura").multiselect({
                maxHeight: 300
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,numberDisplayed: 1
                ,enableCaseInsensitiveFiltering: true
                ,buttonWidth: '200px'
            });
        },
        error:function(xhr,opciones, error){
            alert(error);
        }
    });
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
    table += '</tr>';
    
    $('#consultaTabla').html(tabla + table);
}
function crearListado(json){
    var table = '';
    $.each(json.data, function(contador, fila){
        table += '<tr>';
        table += '<td>'+fila.nombreCliente+'</td>';
        table += '<td>'+fila.fechaInicial+'</td>';
        table += '<td>'+fila.fechaFinal+'</td>';
        table += '<td>'+fila.producto+'</td>';
        table += '<td align="right">'+agregarSeparadorMil(parseInt(fila.valor).toString())+'</td>';
        table += '<td align="center">'+fila.estadoFactura+'</td>';
        if(fila.finalizado.toString() == "true"){
            table += '<td name="td'+fila.idFactura+'" align="center"><img id="img'+fila.idFactura+'" src="../imagenes/casilla_marcada.png" style="width: 30px;cursor:pointer" onclick="cambiarEstado(this,\''+fila.idFactura+'\');" name="false"></td>';    
        }else if(fila.finalizado.toString() == "false"){
            table += '<td name="td'+fila.idFactura+'" align="center"><img id="img'+fila.idFactura+'" src="../imagenes/casilla_vacia.png" style="width: 30px;cursor:pointer" onclick="cambiarEstado(this,\''+fila.idFactura+'\');" name="true"></td>';    
        }
        table += '</tr>';
    });
    
    $('#consultaTabla').html(tabla+table);
}
function cambiarEstado(control, id){
    var estado = $(control).attr('name');
    $.ajax({
        async:false,
        url:'../controlador/factura.cambiarEstado.php',
        type:'POST',
        dataType:'json',
        data:{
                estado:estado,
                idFactura:id
             },
        success:function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            
            if(exito == 0){
                alerta(mensaje);
                return;
            }
            
            if(estado == 'true'){
                $(control).attr('src','../imagenes/casilla_marcada.png');
                $(control).attr('name','false');
            }else{
                $(control).attr('src','../imagenes/casilla_vacia.png');
                $(control).attr('name','true');
            }
            $('#imgConsultar').click();
                    
        },error:function(xhr, opciones, error){
            alerta(error);
        }
    });
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