var valorRecibido = variableUrl();
var idCliente = valorRecibido[0];
var origen = valorRecibido[1];
var arrDireccion = new Array();
var arrTelefono = new Array();
var arrEmail = new Array();
var idTercero = null;
var nit = null;
var tercero = null;
var digitoVerificacion = null;
var idTipoRegimen = null;
var idEmpresa;
var arrSucursal = new Array();

//ids artificiales para los registros temporales sin id
var idArtificialDireccion = 0;
var idArtificialTelefono = 0;
var idArtificialEmail = 0;

var data = null;
$(function() {
    crearTablaDireccion();
    crearTablaTelefono();
    crearTablaEmail();
    cargarZonas();
    crearTablaSucursal();
    cargarTipoRegimen();
    cargarEmpresas();
    crearTablaSucursal();
    //$("#liSucursal").hide();
    obtenerSelectEstado($("#selEstado"));
    obtenerSelectEstado($("#selEstadoDireccion"));
    obtenerSelectEstado($("#selEstadoTelefono"));
    obtenerSelectEstado($("#selEstadoEmail"));
    obtenerSelectEstado($("#selSucursalEstado"));

    $("#txtNumeroIdentificacion").change(function() {
        var numeroNit = $(this).val();
        limpiarControles();
        validarExistenciaCliente(numeroNit);
    });
    $("#txtNumeroIdentificacion").focus();
    validarNumeros("txtNumeroIdentificacion");
    validarNumeros("txtTelefono");
    cargarMunicipios();
    cargarTipoTelefono();

    $("#imgAdicionarDireccion").bind({
        "click": function() {
            if (validarVacios(document.frmDireccionTercero) == false)
                return false;
            adicionarDireccion();

            var control = $("#selMunicipio");
            $("#selMunicipio").multiselect('destroy');
            $("#selMunicipio").val('');
            $('#selMunicipio').multiselect({
                maxHeight: 400
                , nonSelectedText: 'Seleccione'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });
            $("input.form-control.multiselect-search").attr("accesskey", "V");
        }
    });

    $("#imgAdicionarTelefono").bind({
        "click": function() {
            if (validarVacios(document.frmTelefonoTercero) == false)
                return false;
            adicionarTelefono();
        }
    });

    $("#imgAdicionarEmail").bind({
        "click": function() {
            if (validarVacios(document.frmEmailTercero) == false)
                return false;
            adicionarEmail();
        }
    });

    $("#imgAdicionarSucursal").bind({
        "click": function() {
            if (validarVacios(document.frmInformacionSucursal) == false)
                return false;
            adicionarSucursal();
        }
    });

    if (idCliente != "" && idCliente != null && idCliente != "undefined" && idCliente != 'null') {
        consultarInformacionCliente();
    } else {
        $("#selEstado").attr("disabled", true);
    }
    if (origen == 1 || origen == null || origen == undefined) {
        $("#imgCerrar").click(function() {
            window.close();
        });
    }

    if (origen == 2) {
        $("#imgCerrar").click(function() {
            devolverValores();
        });
    }

    $("#imgGuardar").click(function() {
        if (validarVacios(document.frmCliente) == false)
            return false;
        if (arrDireccion.length > 0) {
            var principal = validarPrincipalTabla("consultaTablaDireccion");
            if (principal == false) {
                alerta("Debe elegir una dirección como principal.");
                return;
            }
        } else {
            alerta("Debe registrar almenos una dirección.");
            return;
        }
        if (arrTelefono.length > 0) {
            var principal = validarPrincipalTabla("consultaTablaTelefono");
            if (principal == false) {
                alerta("Debe elegir un contacto como principal.");
                return;
            }
        } else {
            alerta("Debe registrar almenos un contacto.");
            return;
        }
        if (arrEmail.length > 0) {
            var principal = validarPrincipalTabla("consultaTablaEmail");
            if (principal == false) {
                alerta("Debe elegir una cuenta email como principal.");
                return;
            }
        } else {
            alerta("Debe registrar almenos una cuenta de email.");
            return;
        }

        if (arrSucursal.length > 0) {
            var principal = validarPrincipalTabla("tblSucursalTercero");
            if (principal == false) {
                alerta("Debe elegir una sucursal como principal.");
                return;
            }
        } else {
            alerta("Debe registrar almenos una sucursal.");
            return;
        }

        obtenerDatosEnvio();
        if (idCliente != null && idCliente != "null" && idCliente != "") {
            modificarCliente();            
        } else {
            adicionarCliente();            
        }
    });
});

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

function cerrarVentana(mensaje) {
    bootbox.dialog({
        message: mensaje,
        title: "Información",
        buttons: {
            main: {
                label: "Ok",
                className: "btn-primary",
                callback: function() {
                    if (origen == 2) {
                        devolverValores();
                        limpiarControles();
                    } else {
                        window.close();
                    }
                }
            }
        }
    });
}

function validarPrincipalTabla(formulario) {
    var estado = false;
    $("#" + formulario + " input").each(function() {
        var id = this.id;
        if ($("#" + id).is(':checked')) {
            estado = true;
        }
    });
    return estado;
}

function validarExistenciaCliente(nitTercero) {
    if (nitTercero != null && nitTercero != "" && nitTercero != " ") {
        $("#txtNumeroIdentificacion").val(nitTercero);
        $.ajax({
            async: false,
            url: localStorage.modulo + 'ajax/cliente.validarExistencia.php',
            data: {nit: nitTercero},
            dataType: "json",
            type: 'POST',
            success: function(json) {
                var exito = json.exito;
                var mensaje = json.mensaje;

                if (exito == 0) {
                    alerta(mensaje);
                    return false;
                }
                if (json.numeroRegistros == 0) {
                    CalcularDv(nitTercero);
                    return false;
                }
                $("#selEstado").attr("disabled", false);
                mostrarInformacionTercero(json);
            }, error: function(xhr, opciones, error) {
                alerta(error);
            }
        });
    }
}
function mostrarInformacionTercero(json) {
    $.each(json.data, function(contador, fila) {
        $("#txtNumeroIdentificacion").val(fila.nit);
        $("#txtTercero").val(fila.tercero);
        if (fila.digitoVerificacion != "null" && fila.digitoVerificacion != null && fila.digitoVerificacion != "") {
            $("#txtDigitoVerificacion").val(fila.digitoVerificacion);
        }
        $("#selEmpresa").val(fila.idEmpresa);
        $("#selTipoRegimen").val(fila.idTipoRegimen);
        $("#selEstado").val(fila.estado);
        consultarDireccionTercero(fila.idTercero);
        consultarTelefonoTercero(fila.idTercero);
        consultarEmailTercero(fila.idTercero);
        consultarSucursalesTercero(fila.idTercero);
        ocultarItem();
        idTercero = fila.idTercero;
        idCliente = fila.idCliente;
    });
    if (idCliente == null || idCliente == undefined || idCliente == "") {
        alerta("Este tercero aún no es un cliente, registre esta información para guardar este tercero como cliente.");
    }else{
        consultarHistorialProducto();
    }
}
function asignarValoresEnvio() {
    nit = $("#txtNumeroIdentificacion").val();
    tercero = $("#txtTercero").val();
    digitoVerificacion = $("#txtDigitoVerificacion").val();
    idTipoRegimen = $("#selTipoRegimen").val();
    idEmpresa = $("#selEmpresa").val();
    estado = $("#selEstado").val();
}
function obtenerDatosEnvio() {
    asignarValoresEnvio();
    data = "";
    data += "idCliente=" + idCliente;
    data += "&nit=" + nit;
    data += "&tercero=" + tercero;
    data += "&digitoVerificacion=" + digitoVerificacion;
    data += "&idTipoRegimen=" + idTipoRegimen;
    data += "&idEmpresa=" + idEmpresa;
    data += "&estado=" + estado;
    data += "&arrDireccion=" + JSON.stringify(arrDireccion);
    data += "&arrTelefono=" + JSON.stringify(arrTelefono);
    data += "&arrEmail=" + JSON.stringify(arrEmail);
    data += "&arrSucursal=" + JSON.stringify(arrSucursal);
    data += "&idTercero=" + idTercero;
}
function consultarInformacionCliente() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idCliente: idCliente},
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

            crearListado(json);

        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}

function crearListado(json) {
    $.each(json.data, function(contador, fila) {
        validarExistenciaCliente(fila.nit);
        
    });
}

function adicionarCliente() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.adicionar.php',
        type: 'POST',
        dataType: "json",
        data: data,
        success: function(json) {
            var exito = json.exito;
            var mensaje = json.mensaje;
            idCliente = json.idCliente;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }
            idCliente = json.idClientePrincipal;
            cerrarVentana(mensaje);
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function modificarCliente() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.modificar.php',
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
            idCliente = json.idClientePrincipal;
            cerrarVentana(mensaje);
        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function cargarInformacionCliente(documentoIdentidad) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {
            numeroIdentificacion: documentoIdentidad
        },
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

            $.each(json.data, function(contador, fila) {
                $('#selMunicipio').multiselect('destroy');
                $("#selMunicipio").val(fila.idMunicipio);
                $('#selMunicipio').multiselect({
                    maxHeight: 600
                    , nonSelectedText: '--Seleccione--'
                    , enableFiltering: true
                    , filterPlaceholder: 'Buscar'
                    , enableCaseInsensitiveFiltering: true
                });
                $("button.multiselect").css("width", "200px");
                $("input.form-control.multiselect-search").attr("accesskey", "V");
            });

        }, error: function(xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}

function consultarSucursalesTercero(idTercero) {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/terceroSucursal.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idTercero: idTercero},
        success: function(json) {
            var numeroRegistros = json.numeroRegistros;
            if (numeroRegistros == 0) {
                alerta("El usuario no tiene sucursales");
                return false;
            }
            if (json.numeroRegistros != 0) {
                $.each(json.data, function(contador, fila) {
                    //Variables de sucursales
                    var objSucursal = new Object();
                    objSucursal.idSucursal = fila.idSucursal;
                    objSucursal.sucursal = fila.sucursal;
                    objSucursal.idDireccion = "idDir" + fila.idTerceroDireccion;
                    objSucursal.direccion = fila.terceroDireccion;
                    objSucursal.itemDireccion = null;
                    objSucursal.idTelefono = "idTel" + fila.idTerceroTelefono;
                    objSucursal.telefono = fila.terceroTelefono;
                    objSucursal.itemTelefono = null;
                    objSucursal.idEmail = "idEmail" + fila.idTerceroEmail;
                    objSucursal.email = fila.terceroEmail;
                    objSucursal.itemEmail = null;
                    if (fila.principal == true) {
                        objSucursal.principal = fila.principal.toString();
                    } else {
                        objSucursal.principal = "FALSE";
                    }
                    objSucursal.estado = fila.estado.toString();
                    var zona = "";
                    var latitud = "";
                    var longitud = "";
                    var idZona = null;
                    var clienteId = null;
                    if (fila.arrZona != null) {
                        $.each(fila.arrZona, function(contadorZona, filaZona) {
                            zona = filaZona.zona;
                            latitud = filaZona.latitud;
                            longitud = filaZona.longitud;
                            idZona = filaZona.idZona;
                            clienteId = filaZona.idCliente;
                        });
                    }
                    objSucursal.idCliente = clienteId;
                    objSucursal.idZona = idZona;
                    objSucursal.zona = zona;
                    objSucursal.latitud = latitud;
                    objSucursal.longitud = longitud;

                    arrSucursal.push(objSucursal);
                });
                visualizarSucursal();
            }
        }
    });
}

function cargarMunicipios() {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/tercero.cargarMunicipio.php',
        data: null,
        dataType: "json",
        type: 'POST',
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
            var control = $("#selMunicipio");
            control.empty();
            control.append('<option value=""> --Seleccione-- </option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idMunicipio + '">' + fila.departamento + ' -' + fila.municipio + '</option>');
            });

            $('#selMunicipio').multiselect({
                maxHeight: 400
                , nonSelectedText: 'Seleccione'
                , enableFiltering: true
                , filterPlaceholder: 'Buscar'
                , numberDisplayed: 1
                , enableCaseInsensitiveFiltering: true
            });
            $("input.form-control.multiselect-search").attr("accesskey", "V");
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}
function cargarTipoRegimen() {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/tercero.cargarTipoRegimen.php',
        data: null,
        dataType: "json",
        type: 'POST',
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
            var control = $("#selTipoRegimen");
            control.empty();
            control.append('<option value="">--SELECCIONE--</option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idTipoRegimen + '">' + fila.tipoRegimen + '</option>');
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}
function cargarEmpresas() {
    $.ajax({
        async: false,
        url: '/Seguridad/ajax/tercero.cargarEmpresas.php',
        data: null,
        dataType: "json",
        type: 'POST',
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
            var control = $("#selEmpresa");
            control.empty();
            control.append('<option value="">--SELECCIONE--</option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idEmpresa + '">' + fila.empresa + '</option>');
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}
function cargarTipoTelefono() {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/tercero.cargarTipoTelefono.php',
        data: null,
        dataType: "json",
        type: 'POST',
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
            var control = $("#selTipoTelefono");
            control.empty();
            control.append('<option value="">--SELECCIONE--</option>');
            $.each(json.data, function(contador, fila) {
                control.append('<option value="' + fila.idTipoTelefono + '">' + fila.tipoTelefono + '</option>');
            });
        }, error: function(xhr, opciones, error) {
            alerta(error);
        }
    });
}
function adicionarDireccion() {
    var objDireccion = new Object();
    objDireccion.idDireccion = null;
    objDireccion.idMunicipio = $("#selMunicipio").val();
    objDireccion.municipio = $("#selMunicipio option:selected").text();
    objDireccion.direccion = $("#txtDireccion").val();
    objDireccion.principal = "FALSE";
    objDireccion.idArtificial = idArtificialDireccion;
    idArtificialDireccion++;
    objDireccion.estado = $("#selEstadoDireccion").val().toString();

    arrDireccion.push(objDireccion);
    limpiarControlesFormulario(document.frmDireccionTercero);
    visualizarDireccion();
    cargarDireccionSucursal(objDireccion);
}

function adicionarTelefono() {
    var objTelefono = new Object();
    objTelefono.idTelefono = null;
    objTelefono.contacto = $("#txtContacto").val();
    objTelefono.numeroTelefono = $("#txtTelefono").val();
    objTelefono.tipoTelefono = $("#selTipoTelefono option:selected").text();
    objTelefono.idTipoTelefono = $("#selTipoTelefono").val();
    objTelefono.principal = "FALSE";
    objTelefono.idArtificial = idArtificialTelefono;
    idArtificialTelefono++;
    objTelefono.estado = $("#selEstadoTelefono").val().toString();

    arrTelefono.push(objTelefono);
    limpiarControlesFormulario(document.frmTelefonoTercero);
    visualizarTelefono();
    cargarTelefonoSucursal();
}

function adicionarEmail() {
    var objEmail = new Object();
    objEmail.idEmail = null;
    objEmail.email = $("#txtEmail").val();
    objEmail.principal = "FALSE";
    objEmail.idArtificial = idArtificialEmail;
    idArtificialEmail++;
    objEmail.estado = $("#selEstadoEmail").val().toString();

    arrEmail.push(objEmail);
    limpiarControlesFormulario(document.frmEmailTercero);
    visualizarEmail();
    cargarEmailSucursal(objEmail);
}

//Visualizar datos de arreglos
function visualizarDireccion() {
    if (arrDireccion.length == 0) {
        crearTablaDireccion();
        return;
    }
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Municipio </th>";
    html += "<th> Dirección </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    for (i = 0; i < arrDireccion.length; i++) {
        var objDireccion = arrDireccion[i];
        if (objDireccion.estado == true || objDireccion.estado == "true") {
            var chequeado = "";
            html += "<tr>";
            html += "<td class='valorFijo'> " + parseInt(i + 1) + " </td>";
            html += "<td class='valorTexto'> " + objDireccion.municipio + " </td>";
            html += "<td class='valorTexto'> " + objDireccion.direccion + " </td>";
            if (objDireccion.principal != "FALSE" && objDireccion.principal != false && objDireccion.principal != "false") {
                chequeado = "checked='checked'";
            }
            html += "<td  class='valorFijo'> <input type='radio' name='radioDireccion' id='rdDireccion" + i + "' " + chequeado + " title='Elegir la direccion " + objDireccion.direccion + " como principal' onChange='cambiarPrincipalArreglo(" + 1 + ", " + i + ")'> </td>";
            if (objDireccion.estado == true || objDireccion.estado == 'true') {
                html += "<td class='valorTexto'> Activo </td>";
            } else {
                html += "<td class='valorTexto'> Inactivo </td>";
            }
            html += '<td class="valorFijo"> <span class="fa fa-trash imagenesTabla" id="imgEliminarCaso' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarDireccion(' + objDireccion.idArtificial + ',' + i + ')"></span> </td>';
            html += "</tr>";
        }
    }
    $("#consultaTablaDireccion").html(html);

}

function visualizarTelefono() {
    if (arrTelefono.length == 0) {
        crearTablaTelefono();
        return;
    }
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Contacto </th>";
    html += "<th> Teléfono </th>";
    html += "<th> Tipo teléfono </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    for (i = 0; i < arrTelefono.length; i++) {
        var objTelefono = arrTelefono[i];
        if (objTelefono.estado == true || objTelefono.estado == "true") {
            var chequeado = "";
            html += "<tr>";
            html += "<td class='valorFijo'> " + parseInt(i + 1) + " </td>";
            html += "<td class='valorTexto'> " + objTelefono.contacto + " </td>";
            html += "<td class='valorTexto'> " + objTelefono.numeroTelefono + " </td>";
            html += "<td class='valorTexto'> " + objTelefono.tipoTelefono + " </td>";
            if (objTelefono.principal != "FALSE" && objTelefono.principal != false && objTelefono.principal != "false") {
                chequeado = "checked='checked'";
            }
            html += "<td  class='valorFijo'> <input type='radio' name='radioTelefono' id='rdTelefono" + i + "' " + chequeado + " title='Elegir el contacto " + objTelefono.contacto + " como principal' onChange='cambiarPrincipalArreglo(" + 2 + ", " + i + ")'> </td>";
            if (objTelefono.estado == true || objTelefono.estado == 'true') {
                html += "<td class='valorTexto'> Activo </td>";
            } else {
                html += "<td class='valorTexto'> Inactivo </td>";
            }
            html += '<td class="valorFijo"> <span class="fa fa-trash imagenesTabla" id="imgEliminarCaso' + objTelefono.idDireccion + '" title="Eliminar" class="imagenesTabla" onclick="eliminarTelefono(' + objTelefono.idArtificial + ',' + i + ')"></span> </td>';
            html += "</tr>";
        }
    }
    $("#consultaTablaTelefono").html(html);
}

function visualizarEmail() {
    if (arrEmail.length == 0) {
        crearTablaEmail();
        return;
    }
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Email </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    for (i = 0; i < arrEmail.length; i++) {
        var objEmail = arrEmail[i];
        if (objEmail.estado == true || objEmail.estado == "true") {
            var chequeado = "";
            html += "<tr>";
            html += "<td class='valorFijo'> " + parseInt(i + 1) + " </td>";
            html += "<td class='valorTexto'> " + objEmail.email + " </td>";
            if (objEmail.principal != "FALSE" && objEmail.principal != false && objEmail.principal != "false") {
                chequeado = "checked='checked'";
            }
            html += "<td  class='valorFijo'> <input type='radio' name='radioEmail' id='rdEmail" + i + "' " + chequeado + " title='Elegir el email " + objEmail.email + " como principal' onChange='cambiarPrincipalArreglo(" + 3 + ", " + i + ")'> </td>";
            if (objEmail.estado == true || objEmail.estado == 'true') {
                html += "<td class='valorTexto'> Activo </td>";
            } else {
                html += "<td class='valorTexto'> Inactivo </td>";
            }
            html += '<td class="valorFijo"> <span class="fa fa-trash imagenesTabla" id="imgEliminarCaso' + objEmail.idEmail + '" title="Eliminar" class="imagenesTabla" onclick="eliminarEmail(' + objEmail.idArtificial + ',' + i + ')"></span> </td>';
            html += "</tr>";
        }
    }
    $("#consultaTablaEmail").html(html);
}

function crearTablaDireccion() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Municipio </th>";
    html += "<th> Dirección </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "</tr>";
    $("#consultaTablaDireccion").html(html);
}

function crearTablaTelefono() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Contacto </th>";
    html += "<th> Teléfono </th>";
    html += "<th> Tipo teléfono </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "</tr>";
    $("#consultaTablaTelefono").html(html);
}

function crearTablaEmail() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Email </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    html += "<tr>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "<td> &nbsp; </td>";
    html += "</tr>";
    $("#consultaTablaEmail").html(html);
}

function crearTablaSucursal() {
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Sucursal </th>";
    html += "<th> Dirección </th>";
    html += "<th> Teléfono </th>";
    html += "<th> Email </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Acción </th>";
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
    html += "</tr>";
    $("#tblSucursalTercero").html(html);
}

function eliminarDireccion(itemDireccion, posicion) {
    var objDireccion = arrDireccion[posicion];
    if (objDireccion.idTerceroDireccion != null && objDireccion.idTerceroDireccion != "null") {
        for (i = 0; i < arrSucursal.length; i++) {
            var objSucursal = arrSucursal[i];
            if (objSucursal.estado != 'false' && objSucursal.estado != 'FALSE' && objSucursal.estado != false) {
                if (objSucursal.idDireccion == objDireccion.idTerceroDireccion) {
                    alerta("Esta dirección está asignada en sucursal, por favor elimine la sucursal antes de eliminar esta dirección.");
                    return;
                }
            }
        }
        arrDireccion[posicion].estado = "false";
    } else {
        for (i = 0; i < arrSucursal.length; i++) {
            var objSucursal = arrSucursal[i];
            if (objSucursal.itemDireccion == itemDireccion) {
                alerta("Esta dirección está asignada en sucursal, por favor elimine la sucursal antes de eliminar esta dirección.");
                return;
            }
        }
        arrDireccion.splice(posicion, 1);
    }
    restablecerArraySucursal();
}

function restablecerArraySucursal() {
    visualizarDireccion();
    visualizarTelefono();
    visualizarEmail();
    cargarDireccionSucursal();
    cargarTelefonoSucursal();
    cargarEmailSucursal();
    visualizarSucursal();
}

function eliminarTelefono(itemTelefono, posicion) {
    var objTelefono = arrTelefono[posicion];
    if (objTelefono.idTelefono != null && objTelefono.idTelefono != "null") {
        for (i = 0; i < arrSucursal.length; i++) {
            var objSucursal = arrSucursal[i];
            if (objSucursal.estado != 'false' && objSucursal.estado != 'FALSE' && objSucursal.estado != false) {
                if (objSucursal.idTelefono == objTelefono.idTelefono) {
                    alerta("Este contacto está asignado en sucursal, por favor elimine la sucursal antes de eliminar este contacto.");
                    return;
                }
            }

        }
        arrTelefono[posicion].estado = "false";
    } else {
        for (i = 0; i < arrSucursal.length; i++) {
            var objSucursal = arrSucursal[i];
            if (objSucursal.itemTelefono == itemTelefono) {
                alerta("Este contacto está asignado en sucursal, por favor elimine la sucursal antes de eliminar este contacto.");
                return;
            }
        }
        arrTelefono.splice(posicion, 1);
    }
    restablecerArraySucursal();
}
function eliminarEmail(itemEmail, posicion) {
    var objEmail = arrEmail[posicion];
    if (objEmail.idEmail != null && objEmail.idEmail != "null") {
        for (i = 0; i < arrSucursal.length; i++) {
            var objSucursal = arrSucursal[i];
            if (objSucursal.estado != 'false' && objSucursal.estado != 'FALSE' && objSucursal.estado != false) {
                if (objSucursal.idEmail == objEmail.idEmail) {
                    alerta("Este email está asignado en sucursal, por favor elimine la sucursal antes de eliminar este email.");
                    return;
                }
            }
        }
        arrEmail[posicion].estado = "false";
    } else {
        for (i = 0; i < arrSucursal.length; i++) {
            var objSucursal = arrSucursal[i];
            if (objSucursal.itemEmail == itemEmail) {
                alerta("Este email está asignado en sucursal, por favor elimine la sucursal antes de eliminar este email.");
                return;
            }
        }
        arrEmail.splice(posicion, 1);
    }
    restablecerArraySucursal();
}
function consultarDireccionTercero(idTercero) {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/terceroDireccion.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idTercero: idTercero},
        success: function(json) {

            var numeroRegistros = json.numeroRegistros;


            if (numeroRegistros == 0) {
                alerta("El usuario no tiene direcciones .");
                return false;
            }

            if (json.numeroRegistros != 0) {
                $.each(json.data, function(contador, fila) {
                    var objDireccion = new Object();
                    objDireccion.idTerceroDireccion = "idDir" + fila.idTerceroDireccion;
                    objDireccion.idMunicipio = fila.idMunicipio;
                    objDireccion.municipio = fila.municipio;
                    objDireccion.direccion = fila.terceroDireccion;
                    if (fila.principal) {
                        objDireccion.principal = fila.principal.toString();
                    } else {
                        objDireccion.principal = "FALSE";
                    }

                    objDireccion.estado = fila.estado.toString();
                    arrDireccion.push(objDireccion);
                });
            }
            visualizarDireccion();
            cargarDireccionSucursal();
        }
    });
}
function consultarTelefonoTercero(idTercero) {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/terceroTelefono.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idTercero: idTercero},
        success: function(json) {

            var numeroRegistros = json.numeroRegistros;

            if (numeroRegistros == 0) {
                alerta("El usuario no tiene ningún telefono");
                return false;
            }

            if (json.numeroRegistros != 0) {
                $.each(json.data, function(contador, fila) {

                    var objTelefono = new Object();
                    objTelefono.idTelefono = "idTel" + fila.idTerceroTelefono;
                    objTelefono.contacto = fila.contacto;
                    objTelefono.numeroTelefono = fila.terceroTelefono;
                    objTelefono.tipoTelefono = fila.tipoTelefono;
                    objTelefono.idTipoTelefono = fila.idTipoTelefono;
                    if (fila.principal) {
                        objTelefono.principal = fila.principal.toString();
                    } else {
                        objTelefono.principal = "FALSE";
                    }
                    objTelefono.estado = fila.estado.toString();

                    arrTelefono.push(objTelefono);
                });
            }
            visualizarTelefono();
            cargarTelefonoSucursal();
        }
    });
}
function consultarEmailTercero(idTercero) {
    $.ajax({
        async: false,
        url: '/Seguridad/controlador/terceroEmail.consultar.php',
        type: 'POST',
        dataType: "json",
        data: {idTercero: idTercero},
        success: function(json) {

            var numeroRegistros = json.numeroRegistros;

            if (numeroRegistros == 0) {
                alerta("El usuario no tiene ningún email");
                return false;
            }
            if (json.numeroRegistros != 0) {
                $.each(json.data, function(contador, fila) {
                    var objEmail = new Object();
                    objEmail.idEmail = "idEmail" + fila.idTerceroEmail;
                    objEmail.email = fila.terceroEmail;
                    if (fila.principal) {
                        objEmail.principal = fila.principal.toString();
                    } else {
                        objEmail.principal = "FALSE";
                    }
                    objEmail.estado = fila.estado.toString();
                    arrEmail.push(objEmail);
                });
            }
            cargarEmailSucursal();
            visualizarEmail();
        }
    });
}

function cambiarPrincipalArreglo(indice, posicion) {
    switch (indice) {
        case 1://arrDireccion
            for (i = 0; i < arrDireccion.length; i++) {
                var objDireccion = arrDireccion[i];
                if (objDireccion.principal == "true") {
                    arrDireccion[i].principal = "FALSE";
                }
            }
            arrDireccion[posicion].principal = "true";
            break;
        case 2://arrTelefono
            for (i = 0; i < arrTelefono.length; i++) {
                var objTelefono = arrTelefono[i];
                if (objTelefono.principal == "true") {
                    arrTelefono[i].principal = "FALSE";
                }
            }
            arrTelefono[posicion].principal = "true";
            break;
        case 3://arrEmail
            for (i = 0; i < arrEmail.length; i++) {
                var objEmail = arrEmail[i];
                if (objEmail.principal == "true") {
                    arrEmail[i].principal = "FALSE";
                }
            }
            arrEmail[posicion].principal = "true";
            break;
        case 4://arrSucursal
            for (i = 0; i < arrSucursal.length; i++) {
                var objSucursal = arrSucursal[i];
                if (objSucursal.principal == "true") {
                    arrSucursal[i].principal = "FALSE";
                }
            }
            arrSucursal[posicion].principal = "true";
            break;
    }
}

function limpiarControles() {
    crearTablaDireccion();
    crearTablaTelefono();
    crearTablaEmail();
    crearTablaSucursal();
    crearTablaSucursal();
    limpiarControlesFormulario(document.frmCliente);
    limpiarControlesFormulario(document.frmDireccionTercero);
    limpiarControlesFormulario(document.frmTelefonoTercero);
    limpiarControlesFormulario(document.frmEmailTercero);
    limpiarControlesFormulario(document.frmInformacionSucursal);

    //Limpiar variables
    idCliente = null;
    arrDireccion = new Array();
    arrTelefono = new Array();
    arrEmail = new Array();
    arrSucursal = new Array();
    idTercero = null;
    idArtificialDireccion = 0;
    idArtificialEmail = 0;
    idArtificialTelefono = 0;
}
function cargarDireccionSucursal() {
    var control = $("#selSucursalDireccion");
    control.empty();
    control.append("<option value=''>--Seleccione--</option>");
    for (i = 0; i < arrDireccion.length; i++) {
        var objDireccion = arrDireccion[i];
        if (objDireccion.estado != 'false' && objDireccion.estado != 'FALSE' && objDireccion.estado != false) {
            if (objDireccion.idTerceroDireccion != null) {
                control.append('<option id="' + objDireccion.idTerceroDireccion + '" value="val' + i + '">' + objDireccion.direccion + '</option>');
            } else {
                control.append('<option id="dir' + i + '" value="' + objDireccion.idArtificial + '">' + objDireccion.direccion + '</option>');
            }
        }
    }
    ocultarItem();
}

function cargarTelefonoSucursal() {
    var control = $("#selSucursalTelefono");
    control.empty();
    control.append("<option value=''>--Seleccione--</option>");
    for (i = 0; i < arrTelefono.length; i++) {
        var objTelefono = arrTelefono[i];
        if (objTelefono.estado != 'false' && objTelefono.estado != 'FALSE' && objTelefono.estado != false) {
            if (objTelefono.idTelefono != null) {
                control.append('<option id="' + objTelefono.idTelefono + '" value="val' + i + '">' + objTelefono.numeroTelefono + '</option>');
            } else {
                control.append('<option id="tel' + i + '" value="' + objTelefono.idArtificial + '">' + objTelefono.numeroTelefono + '</option>');
            }
        }
    }
    ocultarItem();
}

function cargarEmailSucursal() {
    var control = $("#selSucursalEmail");
    control.empty();
    control.append("<option value=''>--Seleccione--</option>");
    for (i = 0; i < arrEmail.length; i++) {
        var objEmail = arrEmail[i];
        if (objEmail.estado != 'false' && objEmail.estado != 'FALSE' && objEmail.estado != false) {
            if (objEmail.idEmail != null) {
                control.append('<option id="' + objEmail.idEmail + '" value="val' + i + '">' + objEmail.email + '</option>');
            } else {
                control.append('<option id="email' + i + '" value="' + objEmail.idArtificial + '">' + objEmail.email + '</option>');
            }
        }
    }
    ocultarItem();
}

function adicionarSucursal() {
    var objSucursal = new Object();
    objSucursal.idSucursal = null;
    objSucursal.sucursal = $("#txtSucursal").val();
    objSucursal.idDireccion = $("#selSucursalDireccion option:selected").attr("id");
    objSucursal.direccion = $("#selSucursalDireccion option:selected").text();
    objSucursal.itemDireccion = $("#selSucursalDireccion").val();
    objSucursal.idTelefono = $("#selSucursalTelefono option:selected").attr("id");
    objSucursal.telefono = $("#selSucursalTelefono option:selected").text();
    objSucursal.itemTelefono = $("#selSucursalTelefono").val();
    objSucursal.idEmail = $("#selSucursalEmail option:selected").attr("id");
    objSucursal.email = $("#selSucursalEmail option:selected").text();
    objSucursal.itemEmail = $("#selSucursalEmail").val();
    objSucursal.principal = "FALSE";
    objSucursal.estado = $("#selSucursalEstado").val();

    objSucursal.idZona = $("#selZona").val();
    objSucursal.zona = $("#selZona option:selected").text();
    objSucursal.latitud = '0';
    objSucursal.longitud = '0';

    arrSucursal.push(objSucursal);
    $("#selSucursalDireccion option:selected").hide();
    $("#selSucursalTelefono option:selected").hide();
    $("#selSucursalEmail option:selected").hide();
    limpiarControlesFormulario(document.frmInformacionSucursal);
    visualizarSucursal();
}

function visualizarSucursal() {
    if (arrSucursal.length == 0) {
        crearTablaSucursal();
        return;
    }
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Sucursal </th>";
    html += "<th> Direccion </th>";
    html += "<th> Telefono </th>";
    html += "<th> Email </th>";
    html += "<th> Principal </th>";
    html += "<th> Estado </th>";
    html += "<th> Zona </th>";
    html += "<th> Acción </th>";
    html += "</tr>";
    var contadorSucursal = 1;
    for (i = 0; i < arrSucursal.length; i++) {
        var objSucursal = arrSucursal[i];
        if (objSucursal.estado != "false") {
            html += "<tr>";
            html += "<td class='valorFijo'> " + contadorSucursal + " </td>";
            html += "<td class='valorTexto'> " + objSucursal.sucursal + " </td>";
            html += "<td class='valorTexto'> " + objSucursal.direccion + " </td>";
            html += "<td class='valorTexto'> " + objSucursal.telefono + " </td>";
            html += "<td class='valorTexto'> " + objSucursal.email + " </td>";
            var estadoChequeado = "";
            if (objSucursal.principal != "FALSE" && objSucursal.principal != false && objSucursal.principal != "false") {
                estadoChequeado = " checked = 'checked' ";
            }
            html += "<td class='valorFijo'> <input type='radio' name='radioSucursal' id='rdSucursal" + i + "' " + estadoChequeado + " title='Elegir la sucursal " + objSucursal.sucursal + " como principal' onChange='cambiarPrincipalArreglo(" + 4 + ", " + i + ")'> </td>";
            if (objSucursal.estado != "false" && objSucursal.estado != false && objSucursal.estado != "FALSE") {
                html += "<td class='valorTexto'> Activo </td>";
            } else {
                html += "<td class='valorTexto'> Inactivo </td>";
            }
            html += "<td class='valorTexto'> " + objSucursal.zona + " </td>";
            html += "<td class='valorFijo'> <span class='fa fa-trash imagenesTabla' title='Eliminar' class='imagenesTabla' onclick='eliminarSucursal(" + i + ")'></span> </td>";
            html += "</tr>";

            contadorSucursal++;
//            $("#idDir" + objSucursal.idDireccion).css("display", "none");
//            $("#idTel" + objSucursal.idTelefono).css("display", "none");
//            $("#idEmail" + objSucursal.idEmail).css("display", "none");
        }
    }
    $("#tblSucursalTercero").html(html);
}
function ocultarItem() {
    for (i = 0; i < arrSucursal.length; i++) {
        var objSucursal = arrSucursal[i];
        if (objSucursal.idSucursal != null) {
            if (objSucursal.estado != 'false') {
                $("#" + objSucursal.idDireccion).hide();
                $("#" + objSucursal.idTelefono).hide();
                $("#" + objSucursal.idEmail).hide();
            }
        } else {
            $("#" + objSucursal.idDireccion).hide();
            $("#" + objSucursal.idTelefono).hide();
            $("#" + objSucursal.idEmail).hide();
//            $("#dir" + i).css("display", "none");
//            $("#tel" + i).css("display", "none");
//            $("#email" + i).css("display", "none");
        }
    }
}
function eliminarSucursal(indice) {
    var objSucursal = arrSucursal[indice];
    if (objSucursal.idSucursal != null) {
        arrSucursal[indice].estado = "false";
        $("#" + objSucursal.idDireccion).show();
        $("#" + objSucursal.idTelefono).show();
        $("#" + objSucursal.idEmail).show();
    } else {
        arrSucursal.splice(indice, 1);
        $("#" + objSucursal.idDireccion).show();
        $("#" + objSucursal.idTelefono).show();
        $("#" + objSucursal.idEmail).show();
//        $("#dir" + indice).css("display", "block");
//        $("#tel" + indice).css("display", "block");
//        $("#email" + indice).css("display", "block");
    }

    visualizarSucursal();
}

function devolverValores() {
    if (origen == 2) {
        window.opener.asignarCliente(idCliente);
        window.close();
    }
}
function CalcularDv(nit){
    var vpri, x, y, z, i, nit1, dv1;
    nit1 = nit;
    if (isNaN(nit1)){
        alerta('El valor digitado no es un numero valido'+nit);
    } else {
        vpri = new Array(16);
        x = 0;
        y = 0;
        z = nit1.length;
        vpri[1] = 3;
        vpri[2] = 7;
        vpri[3] = 13;
        vpri[4] = 17;
        vpri[5] = 19;
        vpri[6] = 23;
        vpri[7] = 29;
        vpri[8] = 37;
        vpri[9] = 41;
        vpri[10] = 43;
        vpri[11] = 47;
        vpri[12] = 53;
        vpri[13] = 59;
        vpri[14] = 67;
        vpri[15] = 71;
        for (i = 0; i < z; i++){
            y = (nit1.substr(i, 1));
            //document.write(y+"x"+ vpri[z-i] +":");
            x += (y * vpri[z - i]);
            //document.write(x+"<br>");     
        }
        y = x % 11
        //document.write(y+"<br>");
        if (y > 1){
            dv1 = 11 - y;
        } else {
            dv1 = y;
        }
        $("#txtDigitoVerificacion").val(dv1);
    }
}


///Historial producto
function consultarHistorialProducto(){
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/cliente.consultarHistorialProducto.php',
        type: 'POST',
        dataType: "json",
        data: {idCliente: idCliente},
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

            crearListadoHistorial(json);

        }, error: function(xhr, opciones, error) {
            alert(error);
            return false;
        }
    });
}

function crearListadoHistorial(json){
    var html = "";
    html += "<tr>";
    html += "<th> # </th>";
    html += "<th> Servicio </th>";
    html += "<th> Producto </th>";
    html += "<th> Acción realizada </th>";
    html += "<th> Cantidad </th>";
    html += "<th> Valor </th>";
    html += "<th> Subtotal </th>";
    html += "</tr>";
    var tdServicio = "";
    var contadorBanderaServicio = 0;
    var contadorBanderaProducto = 0;
    var tdProducto = "";
    var tdTotal = "";
    var banderaServicio = null;
    var banderaProducto = null;
    var contadorServicios = 1;
    for(var i = 0; i < json.data.length; i++){
        var objServicioInstalado = json.data[i];
        
        if(banderaServicio != objServicioInstalado.idOrdenTrabajo){
            banderaServicio = objServicioInstalado.idOrdenTrabajo;
            if(tdServicio != ""){
                tdServicio = tdServicio.replace(/\#/g, contadorBanderaServicio);
                tdServicio = tdServicio.replace(/\$/g, contadorBanderaProducto);
                html += tdServicio+"</tr>";
                tdServicio += "<tr>";
                tdServicio += "<td rowspan='#' class='valorFijo'> "+contadorServicios+" </td>";
                tdServicio += "<td rowspan='#' class='valorTexto'> "+objServicioInstalado.servicio+" </td>";
                banderaProducto = null;
            }else{
                tdServicio += "<tr>";
                tdServicio += "<td rowspan='#' class='valorFijo'> "+contadorServicios+" </td>";
                tdServicio += "<td rowspan='#' class='valorTexto'> "+objServicioInstalado.servicio+" </td>";
            }
            contadorServicios++;
            contadorBanderaServicio = 0;
            contadorBanderaProducto = 0;
        }
        if(banderaProducto != objServicioInstalado.idProducto){
            if(banderaProducto != null){
                tdServicio = tdServicio.replace(/\$/g, contadorBanderaProducto);
                tdServicio += "<td rowspan='$' class='valorTexto'> "+objServicioInstalado.producto+" </td>";
                contadorBanderaProducto = 0;
            }else{
                tdServicio += "<td rowspan='$' class='valorTexto'> "+objServicioInstalado.producto+" </td>";
            }
            banderaProducto = objServicioInstalado.idProducto;
        }
        
        tdServicio += "<td class='valorNumerico'> "+objServicioInstalado.tipoDocumento+" </td>";
        tdServicio += "<td class='valorNumerico'> "+parseInt(objServicioInstalado.cantidad)+" </td>";
        tdServicio += "<td class='valorNumerico'> "+agregarSeparadorMil(parseInt(objServicioInstalado.valorUnitarioEntrada).toString())+" </td>";
        tdServicio += "<td class='valorNumerico'> "+agregarSeparadorMil(parseInt(objServicioInstalado.subTotal).toString())+" </td>";
        tdServicio += "</tr>";
        contadorBanderaServicio++;
        contadorBanderaProducto++;
    }
    tdServicio = tdServicio.replace(/\#/g, contadorBanderaServicio);
    tdServicio = tdServicio.replace(/\$/g, contadorBanderaProducto);
    html += tdServicio+"</tr>";
    $("#tblProductoInstalado").html(html);
}