var valorRecibido = variableUrl();
var idMovimientoContable = valorRecibido[1];
var idTipoDocumento = null;
var numeroTipoDocumento = null;
var fecha = null;
var nota = null;
var posicionTemp = null;

var dataMovimientoDetalle = new Array();
var arrIdEliminar = new Array();
var idTercero = null;

var data = null;
$(function(){
    crearCalendario("txtFecha");
    cargarListadoDetalle();
    autoCompletarTercero();
    formatearNumero("txtValor");
    cargarTipoDocumento();
    validarNumeros("txtNit");
    
    if(idMovimientoContable != "" && idMovimientoContable != null && idMovimientoContable != "null"){
        $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/movimientoContable.consultar.php',
            type:'POST',
            dataType:"json",
            data:{idMovimientoContable: idMovimientoContable},
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    alerta(mensaje);
                    return false;
                }
                
                crearListado(json);
                
            },error: function(xhr, opciones, error){
                alerta (error);
                return false;
            }
        });
    }
    
    $('#selTipoDocumento').multiselect({
        maxHeight:300
        ,nonSelectedText: '--Seleccione--'	
        ,enableFiltering: true
        ,filterPlaceholder: 'Buscar'
        ,enableCaseInsensitiveFiltering: true
    });
    $("button.multiselect").css("width","170px");
    $("input.form-control.multiselect-search").attr("accesskey", "V");
    
    $("#selTipoDocumento").change(function(){
        obtenerNumeroTipoDocumento($("#selTipoDocumento").val());
    });
    
    $("#imgGuardar").click(function(){
        obtenerDatosEnvio();
        
        if(validarVacios(document.frmMovimientoContable) == false)
            return false;
        
        if(dataMovimientoDetalle.length == 0){
            alerta("Debe adicionar mínimo un detalle.");
            return false;
        }
        
        if(idMovimientoContable != "" && idMovimientoContable != null && idMovimientoContable != "null"){
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/movimientoContable.modificar.php',
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
                },error: function(xhr, opciones, error){
                    alerta (error);
                    return false;
                }
            });
        }else{
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/movimientoContable.adicionar.php',
                type:'POST',
                dataType:"json",
                data:data,
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;
                    idMovimientoContable = json.idMovimientoContable;

                    if(exito == 0){
                        alerta(mensaje);
                        return false;
                    }
                },error: function(xhr, opciones, error){
                    alerta (error);
                    return false;
                }
            });
        }
        
        if(arrIdEliminar.length > 0){
            $.ajax({
                async:false,
                url:localStorage.modulo + 'controlador/movimientoContableDetalle.eliminar.php',
                type:'POST',
                dataType:"json",
                data:{arrIdEliminar: arrIdEliminar},
                success: function(json){
                    var exito = json.exito;
                    var mensaje = json.mensaje;

                    if(exito == 0){
                        alerta(mensaje);
                        return false;
                    }
                    
                },error: function(xhr, opciones, error){
                    alerta (error);
                    return false;
                }
            });
        }        
        
        $.ajax({
            async:false,
            url:localStorage.modulo + 'controlador/movimientoContableDetalle.adicionar.php',
            type:'POST',
            dataType:"json",
            data:{idMovimientoContable: idMovimientoContable
                  , data: dataMovimientoDetalle},
            success: function(json){
                var exito = json.exito;
                var mensaje = json.mensaje;

                if(exito == 0){
                    alerta(mensaje);
                    return false;
                }
                
                alerta(mensaje, true);
                
            },error: function(xhr, opciones, error){
                alerta (error);
                return false;
            }
        });
    });
    
    $("#imgNuevoDetalle").click(function(){
        if(posicionTemp != null){
            dataMovimientoDetalle.splice(posicionTemp, 1);
            crearListadoDetalle();
        }
        
        if(idTercero == null || idTercero == "" || idTercero == "null"){
            alerta("Por favor indique el tercero");
            return false;
        }
        
        if($("#txtValor").val() == "" || $("#txtValor").val() == null || $("#txtValor").val() == "null"){
            alerta("Por favor indique el valor");
            return false;
        }
        
        var obj = new Object();
        obj.idMovimientoDetalle = $("#hidIdMovimientoDetalle").val();
        obj.idTercero = idTercero;
        obj.nit = $("#txtNit").val();
        obj.tercero = $("#txtNombre").val();
        obj.valor = quitarSeparadorMil($("#txtValor").val());
        obj.nota = $("#txaNotaDetalle").val();
        dataMovimientoDetalle.push(obj);
        
        limpiarVariablesDetalle();
        crearListadoDetalle();
        posicionTemp = null;
    });
    
    $("#txtNit").change(function(){
        consultarTercero($("#txtNit").val());
    })
    
    $("#txtNit").keypress(function(e){
        switch(e.keyCode){
            case 13:
                consultarTercero($("#txtNit").val());
            break;
            case 08 || 46:
                $("#txtNombre").val("");
                idTercero = null;
            break;
        }
    });
    
    $("#txtNombre").keypress(function(e){
        switch(e.keyCode){
            case 08 || 46:
                $("#txtNit").val("");
                idTercero = null;
            break;
        }
    });
                    
});
function asignarDatosEnvio(){
    idTipoDocumento = $("#selTipoDocumento").val();
    numeroTipoDocumento = $("#txtNumeroTipoDocumento").val();
    fecha = $("#txtFecha").val();
    nota = $("#txaNota").val();

}
function obtenerDatosEnvio(){
    asignarDatosEnvio();    
    data = 'idMovimientoContable=' + idMovimientoContable + '&idTipoDocumento=' + idTipoDocumento + '&fecha=' + fecha + '&nota=' + nota + '&numeroTipoDocumento=' + numeroTipoDocumento;
}
function cargarTipoDocumento(){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/tipoDocumento.consultar.php',
        type:'POST',
        dataType:"json",
        data:{estado:true},
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }

            var control = $("#selTipoDocumento");
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data,function(contador,fila){
                control.append('<option value="' + fila.idTipoDocumento + '">' + fila.tipoDocumento + '</option>');
            });

        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function obtenerNumeroTipoDocumento(idTipoDocumento){
    $.ajax({
        async:false,
        url:localStorage.modulo + 'controlador/tipoDocumento.obtenerNumero.php',
        type:'POST',
        dataType:"json",
        data:{idTipoDocumento:idTipoDocumento},
        success: function(json){
            var exito = json.exito;
            var mensaje = json.mensaje;
            var numero = json.numero;

            if(exito == 0){
                alerta(mensaje);
                return false;
            }

            $("#txtNumeroTipoDocumento").val(numero);
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
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
                idTercero = fila.idTercero;
                $("#txtNombre").val(fila.tercero);
            });
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function autoCompletarTercero(){
    $("#txtNombre").autocomplete({
        source: '/Seguridad/ajax/empresa.autocompletarTercero.php',
        select:function(event, ui){
            idTercero = ui.item.idTercero;
            $("#txtNit").val(ui.item.nit);
        }
    });
}
function crearListado(json){
    $.each(json.data, function(contador, fila){
        $("#selTipoDocumento").val(fila.idTipoDocumento);
        $("#txtNumeroTipoDocumento").val(fila.numeroTipoDocumento);
        $("#txtFecha").val(fila.fecha);
        $("#txaNota").val(fila.nota);
    });
    consultarMovimientoDetalle();
}
function consultarMovimientoDetalle(){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/movimientoContableDetalle.consultar.php',
        type:'POST',
        dataType:"json",
        data:{idMovimientoContable:idMovimientoContable},
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
                var obj = new Object();
                obj.idMovimientoDetalle = fila.idMovimientoContableCuenta;
                obj.idTercero = fila.idTercero;
                obj.nit = fila.nit;
                obj.tercero = fila.tercero;
                obj.valor = fila.valor;
                obj.nota = fila.nota;
                dataMovimientoDetalle.push(obj);
            });
            
            crearListadoDetalle();
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}
function cargarListadoDetalle(){
    $("#divListadoDetalle").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">'; 
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Tercero</th>'; 
    tabla += '<th>Valor</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += "<td>&nbsp;</td>";
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += "<td>&nbsp;</td>";	
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoDetalle").html(tabla);	
}
function crearListadoDetalle(){
    $("#divListadoDetalle").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">'; 
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Tercero</th>'; 
    tabla += '<th>Valor</th>';
    tabla += '<th>Nota</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';
    
    if(dataMovimientoDetalle.length == 0){
        cargarListadoDetalle();
        return false;
    }
    
    for(var i = 0; i < dataMovimientoDetalle.length; i++){
        var obj = dataMovimientoDetalle[i];
        tabla += '<tr>';
        tabla += "<td align='center'>" + (i + 1) + "</td>";
        tabla += "<td>" + obj.tercero + "</td>";
        tabla += "<td align='right'>" + agregarSeparadorMil(obj.valor) + "</td>";
        
        if(obj.nota != "" && obj.nota != null && obj.nota != "null"){
            tabla += "<td>" + obj.nota + "</td>";
        }else{
            tabla += "<td>&nbsp;</td>";	
        }
        
        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgEditarMovimientoDetalle' + i + '" title="Editar" class="imagenesTabla" onclick="editarMovimientoDetalle('+i+')"></span></td>';	
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarMovimientoDetalle' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarMovimientoDetalle('+i+')"></span></td>';	
        tabla += '</tr>';
    }
    
    tabla += '</table>';
    $("#divListadoDetalle").html(tabla);	
}
function editarMovimientoDetalle(posicion){
    var obj = dataMovimientoDetalle[posicion];
    idTercero = obj.idTercero;
    $("#hidIdMovimientoDetalle").val(obj.idMovimientoDetalle);
    $("#txtNit").val(obj.nit);
    $("#txtNombre").val(obj.tercero);
    $("#txtValor").val(agregarSeparadorMil(obj.valor));
    $("#txaNotaDetalle").val(obj.nota);
    posicionTemp = parseInt(posicion);
}
function eliminarMovimientoDetalle(posicion){    
    var obj = dataMovimientoDetalle[posicion];
    idBorrar = obj.idMovimientoDetalle;
    if(idBorrar != "" && idBorrar != null && idBorrar != "null"){
        arrIdEliminar.push(idBorrar);
    }    
    dataMovimientoDetalle.splice(posicion, 1);
    crearListadoDetalle();
}
function limpiarVariablesDetalle(){
    idTercero = "";
    $("#hidIdMovimientoDetalle").val("");
    $("#txtNit").val("");
    $("#txtNombre").val("");
    $("#txtValor").val("");
    $("#txaNotaDetalle").val("");
    $("#txtNit").focus();
}
function crearCalendario(id){
    $.ajax({
        async:false,
        url: localStorage.modulo + 'controlador/cierre.obtenerMesCierre.php',
        type:'POST',
        dataType:"json",
        data:null,
        success: function(json){
            
            $("#"+id).datepicker({
		format: "yyyy-mm-dd",
                 startDate: json.mesAbierto
            });
            
        },error: function(xhr, opciones, error){
            alerta (error);
            return false;
        }
    });
}