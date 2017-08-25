var idMunicipio = null;
var idCliente = null;
var idClienteServicio = null;
var idEstadoClienteServicio = null;
var numero = null;
var fechaInicialInicio = null;
var fechaInicialFin = null;
var fechaFinalInicio = null;
var fechaFinalFin = null;
var tipoOrden = null;

var data = null;
var tabla = '';
tabla += '<tr id="trEstatico">';
tabla += '<th>Nro.</th>';
tabla += '<th>Cliente</th>';
tabla += '<th>Empresa</th>';
tabla += '<th>Tipo</th>';
tabla += '<th>Fecha Inicial</th>';
tabla += '<th>Fecha Final</th>';
tabla += '<th>Tipo Documento</th>';
tabla += '<th>Producto</th>';
tabla += '<th>Municipio</th>';
tabla += '<th colspan="3">Acción</th>';
tabla += '</tr>';

$(function(){
    cargarListado();
    autoCompletarMunicipio();
    consultarTipoOrdenTrabajo();
    validarNumeros('txtNit');
    validarNumeros('txtNumeroOrden');
    
    crearCalendario("txtFechaInicialInicio");
    crearCalendario("txtFechaInicialFin");
    crearCalendario("txtFechaFinalInicio");
    crearCalendario("txtFechaFinalFin");
    
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
        $('#selEstadoServicio').val("");
        $('#selEstadoServicio').multiselect('destroy');
        $('#selEstadoServicio').multiselect({
            maxHeight:300
            ,nonSelectedText: '--Seleccione--'	
            ,enableFiltering: true
            ,filterPlaceholder: 'Buscar'
            ,enableCaseInsensitiveFiltering: true
        });
        $("button.multiselect").css("width","170px");
    });
    
    $('#imgConsultar').bind({
        'click':function(){
            obtenerDatos();
            $.ajax({
                async:false,
                url: localStorage.modulo + 'controlador/clienteServicio.consultarInformacionServicio.php',
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
                    $.each(json.data, function(contador, fila){
                        table += '<tr>';
                        table += '<td style="background-color:' + fila.color + '" >'+fila.numero+'</td>';
                        table += '<td style="background-color:' + fila.color + '" >'+fila.cliente+'</td>';
                        table += '<td style="background-color:' + fila.color + '" >'+fila.empresa+'</td>';
                        table += '<td style="background-color:' + fila.color + '" align="center" >'+fila.tipo+'</td>';
                        table += '<td style="background-color:' + fila.color + '" align="center">'+fila.fechaInicial+'</td>';
                        
                        if(fila.fechaFinal != "" && fila.fechaFinal != null && fila.fechaFinal != "null" && fila.fechaFinal != "NULL"){
                            table += '<td style="background-color:' + fila.color + '" align="center">'+fila.fechaFinal+'</td>';
                        }else{
                            table += '<td style="background-color:' + fila.color + '" align="center"></td>';
                        }
                        
                        table += '<td style="background-color:' + fila.color + '" >'+fila.tipoDocumento+'</td>';
                        table += '<td style="background-color:' + fila.color + '">'+fila.producto+'</td>';
                        table += '<td style="background-color:' + fila.color + '">'+fila.municipio+'</td>';
                        table += '<td style="background-color:' + fila.color + '" align="center"><span class="fa fa-file fa-2x imagenesTabla" id="imgAdicionarArchivos' + contador + '" title="Adicionar Archivos" class="imagenesTabla" onclick="editarServicio('+fila.idClienteServicio +' , ' + fila.idOrdenTrabajoCliente + ' , ' + fila.tipoServicio + ',1)"></span></td>';
                        table += '<td style="background-color:' + fila.color + '" align="center"><img src="../imagenes/verificacion.png" id="imgVerificar' + contador + '" title="Verificar Orden Trabajo" class="imagenesTabla" onclick="editarServicio('+fila.idClienteServicio +' , ' + fila.idOrdenTrabajoCliente + ' , ' + fila.tipoServicio + ',0)" style="width: 27px;"></td>';
                        table += '<td style="background-color:' + fila.color + '" align="center"><span class="fa fa-minus-circle fa-2x imagenesTabla" id="imgAnularOrden' + contador + '" title="Anular Servicio" class="imagenesTabla" onclick="anularServicio('+fila.idClienteServicio +' , ' + fila.idOrdenTrabajoCliente + ' , ' + fila.idCliente + ')"></span></td>';
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
    fechaInicialInicio = $('#txtFechaInicialInicio').val();
    fechaInicialFin = $('#txtFechaInicialFin').val();
    fechaFinalInicio = $('#txtFechaFinalInicio').val();
    fechaFinalFin = $('#txtFechaFinalFin').val();
    tipoOrden = $('#selTipoOrdenTrabajo').val();
}
function obtenerDatos(){
    asignarValores();
    data = 'idMunicipio='+idMunicipio+'&idEstadoClienteServicio='+idEstadoClienteServicio+'&numero='+numero+'&fechaInicialInicio='+fechaInicialInicio+'&fechaInicialFin='+fechaInicialFin+'&fechaFinalFin='+fechaFinalFin+'&fechaFinalInicio='+fechaFinalInicio+'&idCliente='+idCliente  +'&tipoOrden=' + tipoOrden;
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
    table += '</tr>';
    
    $('#consultaTabla').html(tabla + table);
}
function editarServicio(idClienteServicio,idOrdenTrabajoCliente,tipo,manejarArchivos){
    
    var arrParametros = new Array();
    var objParametro = new Object();
    objParametro.id = "idClienteServicio";
    objParametro.value = idClienteServicio;
    arrParametros.push(objParametro);
    
    var objParametro = new Object();
    objParametro.id = "idOrdenTrabajoCliente";
    objParametro.value = idOrdenTrabajoCliente;
    arrParametros.push(objParametro);
    
    var objParametro = new Object();
    objParametro.id = "tipo";
    objParametro.value = tipo;
    arrParametros.push(objParametro);
    
    
    var objParametro = new Object();
    objParametro.id = "archivos";
    objParametro.value = manejarArchivos;
    arrParametros.push(objParametro);
    
    abrirVentanaParametros(localStorage.modulo + 'vista/frmClienteServicio.html',arrParametros);

}
function anularServicio(idClienteServicio,idOrdenTrabajoCliente,idCliente){
    bootbox.confirm("¿ Está seguro de anular el servicio ?", function(result) {
        if(result == true){
            $.ajax({
                async:false,
                url: localStorage.modulo + 'controlador/clienteServicio.cancelar.php',
                type:'POST',
                dataType:"json",
                data:{
                        idClienteServicio:idClienteServicio
                        ,idOrdenTrabajoCliente:idOrdenTrabajoCliente
                        ,idCliente:idCliente
                    },
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;

                    alerta(mensaje);

                    if(exito == 0){
                        return false;
                    }
                },error: function(xhr, opciones, error){
                    alerta (error);
                    return false;
                }
            });
        }
    }); 
}
function cargarEstadoServicio(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/estadoClienteServicio.consultar.php',
        type:'POST',
        dataType:"json",
        data:null,
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
            var control = $('#selEstadoServicio');
            control.empty();
            $.each(json.data, function(contador, fila){
                control.append('<option value="' + fila.idEstadoClienteServicio + '">' + fila.estadoClienteServicio + '</option>');
            });
            
            $('#selEstadoServicio').multiselect({
                maxHeight:300
                ,nonSelectedText: '--Seleccione--'	
                ,enableFiltering: true
                ,filterPlaceholder: 'Buscar'
                ,enableCaseInsensitiveFiltering: true
            });
            $("button.multiselect").css("width","170px");
            $("input.form-control.multiselect-search").attr("accesskey", "V");
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
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