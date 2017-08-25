var valorRecibido = variableUrl();
var idProducto = valorRecibido[0];
var origen = valorRecibido[1];
var imagen = null;
var producto = null;
var tangible = null;
var idUnidadMedida = null;
var estado = null;
var idEmpresaUnidadNegocio = null;
var idLineaProducto = null;
var manejaInventario = null;
var valorEntrada = null;
var valorSalida = null;
var productoServicio = null;
var productoSerial = null;
var codigo = null;
var codigoInicial = null;
var productoComposicion = null;
var maximo = null;
var minimo = null;


var data = null;

var dataProductos = new Array();
var idProductosSeleccionados = new Array();
var idProductoCompuesto = null;
var posicionTemp = null;
var arrIdEliminar = new Array();


//--------VAIRABLES PRODUCTO IMPUESTO--------------
var dataImpuestos = new Array();
var arrIdEliminarImpuestos = new Array();

//--------VAIRABLES CODIGOS DE BARRA--------------
var dataCodigosBarra = new Array();
var arrIdEliminarCodigosBarra = new Array();

//--------VAIRABLES BODEGAS--------------
var dataBodegas = new Array();
var arrIdEliminarBodegas = new Array();
$(function () {

    if (origen == 1) {
        $("#imgCancelar").click(function () {
            window.close();
        });
    }

    cargarSelectSiNo($("#selTangible"));
    cargarSelectSiNo($("#selManejaInventario"));
    cargarSelectSiNo($("#selProductoSerial"));
    cargarSelectSiNo($("#selProductoCompuesto"));
    cargarSelectProductoServicio();
    cargarUnidadMedida();
    cargarEmpreUnidaNegoc();
    obtenerSelectEstado($("#selEstado"));
    formatearNumeroDecimal("txtValorEntrada");
    formatearNumeroDecimal("txtValorSalida");
    $("#linkProductosCompuestos").hide();
    obtenerValorEtiquetaConImpuesto();

    $("#fleImagen").change(function (e) {
        subirImagen(e);
    });

    $("#txtCodigoProducto").change(function () {
        if ($.trim($("#txtCodigoProducto").val()) != "") {
            if (validarExistenciaCodigoProducto() == true) {//SI EL CODIGO YA EXISTE
                $("#txtCodigoProducto").css("border-color", "#F44336");
                $("#spnImagen").html('<img src="../imagenes/mal.png">');
            } else {//SI EL CODIGO ESTA DISPONIBLE
                $("#txtCodigoProducto").css("border-color", "#4CAF50");
                $("#spnImagen").html('<img src="../imagenes/bien.png">');
            }

            $("#txtCodigoBarra").val($("#txtCodigoProducto").val());
        }
    });

    $("#txtCodigoProducto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProducto").css("border-color", "#ccc");
                $("#spnImagen").html('');
                break;
        }
    });

    $("#btnBuscarLineas").click(function () {
        cargarLinea();
    });

    $("#selProductoCompuesto").change(function () {
        var valor = $("#selProductoCompuesto").val();
        if (valor != "") {
            if (valor == "true") {
                cargarListadoProductoCompuesto();
                habilitarFuncionesProductoCompuesto();
                $('#listTabProductos li:eq(' + 1 + ') a').tab('show');
            } else {
                $("#linkProductosCompuestos").hide();
            }
        } else {
            $("#linkProductosCompuestos").hide();
        }
    });

    $("#txtCodigoProductoCompuesto").change(function () {
        if ($("#txtCodigoProductoCompuesto").val() != "") {
            consultarProducto($("#txtCodigoProductoCompuesto").val());
        }
    });

    $("#txtCodigoProductoCompuesto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtProductoCompuesto").val("");
                $("#txtUnidadMedidaCompuesto").val("");
                idProductoCompuesto = null;
                break;
        }
    });

    $("#txtProductoCompuesto").keypress(function (e) {
        switch (e.keyCode) {
            case 08 || 46:
                $("#txtCodigoProductoCompuesto").val("");
                $("#txtUnidadMedidaCompuesto").val("");
                idProductoCompuesto = null;
                break;
        }
    });

    //--------------------PRODUCTO IMPUESTO--------------------------------
    cargarImpuestos();
    cargarListadoImpuestos();

    $("#selImpuesto").change(function () {
        if ($("#selImpuesto").val() == "") {
            $("#spnTipoImpuesto").html("");
            return false;
        }

        var tipoImpuesto = $("#selImpuesto option:selected").attr('label');
        $("#spnTipoImpuesto").html("Tipo Impuesto: " + tipoImpuesto);
    });


    //----------------------CODIGOS DE BARRA---------------------------------
    cargarListadoCodigosBarra();
    realizarFoco(document.frmCodigosBarra, '$("#imgNuevoCodigoBarra").click()');
    $('#frmCodigosBarra').bind("keypress", function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });


    //---------------------------BODEGAS-------------------------------------
    cargarBodegas();
    cargarListadoBodegas();


    if (idProducto != '' && idProducto != null && idProducto != 'undefined') {
        $.ajax({
            async: false,
            url: localStorage.modulo + 'controlador/producto.consultar.php',
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
                crearListado(json);
            }
        });
    } else {
        $("#selEstado").attr('disabled', true);
    }

    $('#imgGuardar').bind({
        "click": function () {

            try {

                obtenerDatosEnvio();

                if (validarVacios(document.frmProducto) == false)
                    return false;

                if (idLineaProducto == "" || idLineaProducto == "null" || idLineaProducto == null) {
                    alerta("Por favor indique la linea del producto.");
                    return false;
                }


                if (dataImpuestos.length == 0) {
                    alerta("Debe ingresar mínimo un impuesto <br><br> (Si el producto a ingresar no posee ningún impuesto, por favor agregue 'SIN IMPUESTO' con valor 0).");
                    return false;
                }

                if (dataImpuestos.length > 1) {
                    alerta("No puede ingresar más de un impuesto");
                    return false;
                }

                if (idProducto != '' && idProducto != null && idProducto != 'undefined') {

                    if (codigoInicial != $("#txtCodigoProducto").val()) {
                        if (validarExistenciaCodigoProducto() == true) {
                            alerta("El código del producto ingresado ya existe.");
                            return false;
                        }
                    }

                    $.ajax({
                        async: false,
                        url: localStorage.modulo + "controlador/producto.modificar.php",
                        type: 'POST',
                        dataType: "json",
                        data: data,
                        success: function (json) {
                            var exito = json.exito;
                            var mensaje = json.mensaje;

                            if (exito == 0) {
                                throw mensaje;
                                return false;
                            }

                        }, error: function (xhr, opciones, error) {
                            throw error;
                        }
                    });
                } else {

                    if (validarExistenciaCodigoProducto() == true) {
                        alerta("El código del producto ingresado ya existe.");
                        return false;
                    }

                    $.ajax({
                        async: false,
                        url: localStorage.modulo + "controlador/producto.adicionar.php",
                        type: 'POST',
                        dataType: "json",
                        data: data,
                        success: function (json) {
                            var exito = json.exito;
                            var mensaje = json.mensaje;
                            idProducto = json.idProducto;

                            if (exito == 0) {
                                throw mensaje;
                                return false;
                            }
                        }, error: function (xhr, opciones, error) {
                            throw error;
                        }
                    });
                }

                if (arrIdEliminar.length > 0) {
                    $.ajax({
                        async: false,
                        url: localStorage.modulo + 'controlador/productoComposicion.eliminar.php',
                        type: 'POST',
                        dataType: "json",
                        data: {arrIdEliminar: arrIdEliminar},
                        success: function (json) {
                            var exito = json.exito;
                            var mensaje = json.mensaje;

                            if (exito == 0) {
                                throw mensaje;
                                return false;
                            }

                        }, error: function (xhr, opciones, error) {
                            throw error;
                            return false;
                        }
                    });
                }

                $.ajax({
                    async: false,
                    url: localStorage.modulo + 'controlador/productoComposicion.adicionar.php',
                    type: 'POST',
                    dataType: "json",
                    data: {idProducto: idProducto
                        , data: dataProductos},
                    success: function (json) {
                        var exito = json.exito;
                        var mensaje = json.mensaje;

                        if (exito == 0) {
                            throw mensaje;
                            return false;
                        }

                    }, error: function (xhr, opciones, error) {
                        throw error;
                        return false;
                    }
                });


                if (arrIdEliminarImpuestos.length > 0) {
                    $.ajax({
                        async: false,
                        url: localStorage.modulo + 'controlador/productoImpuesto.eliminar.php',
                        type: 'POST',
                        dataType: "json",
                        data: {arrIdEliminar: arrIdEliminarImpuestos},
                        success: function (json) {
                            var exito = json.exito;
                            var mensaje = json.mensaje;

                            if (exito == 0) {
                                throw mensaje;
                                return false;
                            }

                        }, error: function (xhr, opciones, error) {
                            throw error;
                            return false;
                        }
                    });
                }

                $.ajax({
                    async: false,
                    url: localStorage.modulo + 'controlador/productoImpuesto.adicionar.php',
                    type: 'POST',
                    dataType: "json",
                    data: {idProducto: idProducto
                        , valorEntrada: valorEntrada
                        , valorSalida: valorSalida
                        , data: dataImpuestos},
                    success: function (json) {
                        var exito = json.exito;
                        var mensaje = json.mensaje;

                        if (exito == 0) {
                            throw mensaje;
                            return false;
                        }

                    }, error: function (xhr, opciones, error) {
                        throw error;
                        return false;
                    }
                });

                if (arrIdEliminarCodigosBarra.length > 0) {
                    $.ajax({
                        async: false,
                        url: localStorage.modulo + 'controlador/productoCodigoBarras.eliminar.php',
                        type: 'POST',
                        dataType: "json",
                        data: {arrIdEliminar: arrIdEliminarCodigosBarra},
                        success: function (json) {
                            var exito = json.exito;
                            var mensaje = json.mensaje;

                            if (exito == 0) {
                                throw mensaje;
                                return false;
                            }

                        }, error: function (xhr, opciones, error) {
                            throw error;
                            return false;
                        }
                    });
                }

                $.ajax({
                    async: false,
                    url: localStorage.modulo + 'controlador/productoCodigoBarras.adicionar.php',
                    type: 'POST',
                    dataType: "json",
                    data: {idProducto: idProducto
                        , data: dataCodigosBarra},
                    success: function (json) {
                        var exito = json.exito;
                        var mensaje = json.mensaje;

                        if (exito == 0) {
                            throw mensaje;
                            return false;
                        }

                    }, error: function (xhr, opciones, error) {
                        throw error;
                        return false;
                    }
                });


                if (arrIdEliminarBodegas.length > 0) {
                    $.ajax({
                        async: false,
                        url: localStorage.modulo + 'controlador/productoExistencia.eliminar.php',
                        type: 'POST',
                        dataType: "json",
                        data: {arrIdEliminar: arrIdEliminarBodegas},
                        success: function (json) {
                            var exito = json.exito;
                            var mensaje = json.mensaje;

                            if (exito == 0) {
                                throw mensaje;
                                return false;
                            }

                        }, error: function (xhr, opciones, error) {
                            throw error;
                            return false;
                        }
                    });
                }

                $.ajax({
                    async: false,
                    url: localStorage.modulo + 'controlador/productoExistencia.adicionar.php',
                    type: 'POST',
                    dataType: "json",
                    data: {idProducto: idProducto
                        , data: dataBodegas},
                    success: function (json) {
                        var exito = json.exito;
                        var mensaje = json.mensaje;

                        if (exito == 0) {
                            throw mensaje;
                            return false;
                        }

                    }, error: function (xhr, opciones, error) {
                        throw error;
                        return false;
                    }
                });

                if (origen == 1) {
                    window.opener.document.getElementById("hidIdProducto").value = idProducto;
                    window.opener.asignarIdProducto();
                    alert("La información del producto se guardó correctamente.");
                    window.close();
                } else {
                    alerta("La información del producto se guardó correctamente.", true);
                }

            } catch (e) {
                alerta(e);
                $("body").removeClass("loading");
                return false;
            }
        }
    });

    $("#imgNuevoProducto").click(function () {
        if (validarVacios(document.frmProductoCompuesto) == false)
            return false;

        if (idProductoCompuesto == null || idProductoCompuesto == "" || idProductoCompuesto == "null") {
            alerta("Por favor indique el producto");
            return false;
        }

        if (posicionTemp != null) {
            dataProductos.splice(posicionTemp, 1);
            idProductosSeleccionados.splice(posicionTemp, 1);
            crearListadoProductoCompuesto();
        }

        var obj = new Object();
        obj.idProductoComposicion = $("#hidIdProductoComposicion").val();
        obj.idProducto = idProductoCompuesto;
        obj.codigo = $("#txtCodigoProductoCompuesto").val();
        obj.producto = $("#txtProductoCompuesto").val();
        obj.unidadMedida = $("#txtUnidadMedidaCompuesto").val();
        obj.cantidad = eliminarPuntos($("#txtCantidadProductoCompuesto").val());
        dataProductos.push(obj);
        idProductosSeleccionados.push(idProductoCompuesto);
        autoCompletarProducto();
        limpiarVariablesProductoCompuesto();
        crearListadoProductoCompuesto();
        posicionTemp = null;
    });


    $("#imgNuevoImpuesto").click(function () {

        if (dataImpuestos.length == 1) {
            alerta("No puede ingresar más de un impuesto");
            return false;
        }

        if (validarVacios(document.frmProductoImpuesto) == false)
            return false;

        var obj = new Object();
        obj.idProductoImpuesto = null;
        obj.idImpuesto = $("#selImpuesto").val();
        obj.impuesto = $("#selImpuesto option:selected").text();
        obj.idTipoImpuesto = $("#selImpuesto option:selected").attr('lang');
        obj.tipoImpuesto = $("#selImpuesto option:selected").attr('label');
        obj.valor = $("#txtValorImpuesto").val();
        dataImpuestos.push(obj);
        limpiarVariablesProductoImpuesto();
        crearListadoImpuestos();
        $('#selImpuesto option[value=' + obj.idImpuesto + ']').hide();
        $('#selImpuesto option[value=' + obj.idImpuesto + ']').attr("disabled", true);
    });


    $("#imgNuevoCodigoBarra").click(function () {
        if (validarVacios(document.frmCodigosBarra) == false)
            return false;

        if (validarCodigosBarraRepetidos($("#txtCodigoBarra").val()) == false) {
            return false;
        }

        var obj = new Object();
        obj.idProductoCodigoBarra = null;
        obj.codigoBarra = $("#txtCodigoBarra").val();
        dataCodigosBarra.push(obj);
        crearListadoCodigosBarra();

        $("#txtCodigoBarra").val("");
        $("#txtCodigoBarra").focus();
    });


    $("#imgNuevaBodega").click(function () {

        if (validarVacios(document.frmBodegas) == false)
            return false;

        var obj = new Object();
        obj.idProductoExistencia = null;
        obj.idBodega = $("#selBodega").val();
        obj.bodega = $("#selBodega option:selected").text();
        obj.maximo = $("#txtMaximo").val();
        obj.minimo = $("#txtMinimo").val();
        dataBodegas.push(obj);
        limpiarVariablesBodegas();
        crearListadoBodegas();
        $('#selBodega option[value=' + obj.idBodega + ']').hide();
    });
});

function cargarSelectSiNo(control) {
    control.empty();
    control.append('<option value="">---</option>');
    control.append('<option value="true">SI</option>');
    control.append('<option value="false">NO</option>');
}
function cargarSelectProductoServicio() {
    var control = $("#selProductoServicio");
    control.empty();
    control.append('<option value="">--Seleccione--</option>');
    control.append('<option value="true">Producto</option>');
    control.append('<option value="false">Servicio</option>');
}
function asignar(lineaProducto, idLinea) {
    idLineaProducto = idLinea;
    $("#txtLinea").val(lineaProducto);
}
function cargarLinea() {
    $.ajax({
        async: false,
        url: localStorage.modulo + "controlador/producto.cargarLinea.php",
        type: 'POST',
        dataType: "html",
        data: null,
        success: function (html) {
            bootbox.alert(html);
        }
    });
}
function cargarUnidadMedida() {
    $.ajax({
        async: false,
        url: localStorage.modulo + "controlador/producto.cargarUnidadMedida.php"
        , type: 'POST'
        , dataType: "json"
        , data: null
        , success: function (json) {
            var control = $('#selUnidadMedida');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idUnidadMedida + '">' + fila.unidadMedida + '</option>');
            });
        }
    });
}
function obtenerValorEtiquetaConImpuesto() {
    $.ajax({
        async: false,
        url: "/Seguridad/controlador/parametroAplicacion.consultar.php"
        , type: 'POST'
        , dataType: "json"
        , data: {idParametroAplicacion: '19,20'}
        , success: function (json) {

            if (json.exito == 0) {
                alerta(json.mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                return false;
            }

            $.each(json.data, function (contador, fila) {
                if (fila.idParametroAplicacion == 19) {
                    if (fila.valor == "1")
                        $("#spnTieneImpuestoValorEntrada").html("Con impuesto incluido");
                    else
                        $("#spnTieneImpuestoValorEntrada").html("Sin impuesto incluido");
                }

                if (fila.idParametroAplicacion == 20) {
                    if (fila.valor == "1")
                        $("#spnTieneImpuestoValorSalida").html("Con impuesto incluido");
                    else
                        $("#spnTieneImpuestoValorSalida").html("Sin impuesto incluido");
                }
            });
        }
    });
}

function cargarEmpreUnidaNegoc() {
    $.ajax({
        async: false,
        url: localStorage.modulo + "controlador/producto.cargarEmpresUnidaNegoc.php"
        , type: 'POST'
        , dataType: "json"
        , data: null
        , success: function (json) {
            var control = $('#selEmpresaUnidadNegocio');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idEmpresaUnidadNegocio + '">' + fila.empresaUnidadNegocio + '</option>');
            });
        }
    });
}
function subirImagen(e) {
    var archivos = document.getElementById("fleImagen");
    var archivo = archivos.files;
    var data = new FormData();
    for (i = 0; i < archivo.length; i++) {
        data.append('archivo' + i, archivo[i]);
    }
    data.append('modulo', localStorage.modulo);
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/producto.subirImagen.php',
        type: 'POST',
        dataType: "json",
        contentType: false,
        data: data,
        processData: false,
        cache: false,
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;
            imagen = json.ruta;

            if (exito == 0) {
                imagen = '';
                $("#fleImagen").val("");
                $("#divImagen").html("");
                alerta(mensaje);
                return false;
            }
            addImage(e);
        }
    });
}
function addImage(e) {
    var file = e.target.files[0],
            imageType = /image.*/;

    if (!file.type.match(imageType))
        return;

    var reader = new FileReader();
    reader.onload = fileOnload;
    reader.readAsDataURL(file);
}

function fileOnload(e) {
    var result = e.target.result;

    if (imagen == '') {
        alerta("Extensión inválida");
        $("#divImagen").html("");
        $("#fleImagen").val("");
        return false;
    }

    $("#divImagen").html("");
    $("#divImagen").html('<img style="width: 300px;height: 250px;" src="' + result + '">');
}
function asignarDatosEnvio() {
    producto = $("#txtProducto").val();
    tangible = $("#selTangible").val();
    idUnidadMedida = $("#selUnidadMedida").val();
    estado = $("#selEstado").val();
    idEmpresaUnidadNegocio = $("#selEmpresaUnidadNegocio").val();
    manejaInventario = $("#selManejaInventario").val();
    /*valorEntrada = quitarSeparadorMil($("#txtValorEntrada").val());
     valorSalida = quitarSeparadorMil($("#txtValorSalida").val());*/
    valorEntrada = $("#txtValorEntrada").val();
    valorSalida = $("#txtValorSalida").val();

    productoServicio = $("#selProductoServicio").val();
    productoSerial = $("#selProductoSerial").val();
    codigo = $("#txtCodigoProducto").val();
    productoComposicion = $("#selProductoCompuesto").val();
    maximo = $("#txtMaximoProducto").val();
    minimo = $("#txtMinimoProducto").val();
}
function obtenerDatosEnvio() {
    asignarDatosEnvio();
    data = 'idProducto=' + idProducto + '&imagen=' + imagen + '&producto=' + producto + '&tangible=' + tangible + '&idUnidadMedida=' + idUnidadMedida
            + '&estado=' + estado + '&idEmpresaUnidadNegocio=' + idEmpresaUnidadNegocio + '&idLineaProducto=' + idLineaProducto + '&manejaInventario=' + manejaInventario
            + '&valorEntrada=' + valorEntrada + '&valorSalida=' + valorSalida + '&productoServicio=' + productoServicio + '&productoSerial=' + productoSerial + '&codigo=' + codigo
            + '&productoComposicion=' + productoComposicion + '&maximo=' + maximo + '&minimo=' + minimo;
}
function crearListado(json) {
    $.each(json.data, function (contador, fila) {
        imagen = fila.imagen;
        $("#divImagen").html("");
        if (imagen != "" && imagen != "null" && imagen != null) {
            $("#divImagen").html('<img style="width: 300px;height: 250px;" src="' + imagen + '">');
        }
        idLineaProducto = fila.idLineaProducto;
        $("#txtLinea").val(fila.lineaProducto);
        $("#txtCodigoProducto").val(fila.codigo);
        codigoInicial = fila.codigo;
        $("#txtProducto").val(fila.producto);
        $("#txtMaximoProducto").val(fila.maximo);
        $("#txtMinimoProducto").val(fila.minimo);

        if (fila.tangible == true) {
            $("#selTangible").val("true");
        } else {
            $("#selTangible").val("false");
        }

        $("#selUnidadMedida").val(fila.idUnidadMedida);
        $("#selEmpresaUnidadNegocio").val(fila.idEmpresaUnidadNegocio);

        if (fila.manejaInventario == true) {
            $("#selManejaInventario").val("true");
        } else {
            $("#selManejaInventario").val("false");
        }

        if (fila.valorEntrada != null && fila.valorEntrada != "null") {
            $("#txtValorEntrada").val(numberFormat(fila.valorEntradaMostrar));
        } else {
            $("#txtValorEntrada").val("");
        }

        if (fila.valorSalida != null && fila.valorSalida != "null") {
            $("#txtValorSalida").val(numberFormat(fila.valorSalidaMostrar));
        } else {
            $("#txtValorSalida").val("");
        }

        if (fila.productoServicio == true) {
            $("#selProductoServicio").val("true");
        } else {
            $("#selProductoServicio").val("false");
        }

        if (fila.productoSerial == true) {
            $("#selProductoSerial").val("true");
        } else {
            $("#selProductoSerial").val("false");
        }

        if (fila.productoComposicion == true) {
            $("#selProductoCompuesto").val("true");
        } else {
            $("#selProductoCompuesto").val("false");
        }

        if (fila.estado == true) {
            $("#selEstado").val("true");
        } else {
            $("#selEstado").val("false");
        }
    });

    var valor = $("#selProductoCompuesto").val();
    if (valor == "true") {
        consultarProductoCompuesto();
        habilitarFuncionesProductoCompuesto();
    }

    consultarProductoImpuesto();
    consultarProductoCodigoBarras();
    consultarBodegas();
}
function consultarProductoCompuesto() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/productoComposicion.consultar.php',
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
                cargarListadoProductoCompuesto();
                return false;
            }

            $.each(json.data, function (contador, fila) {
                var obj = new Object();
                obj.idProductoComposicion = fila.idProductoComposicion;
                obj.idProducto = fila.idProductoCompone;
                obj.codigo = fila.codigo;
                obj.producto = fila.producto;
                obj.unidadMedida = fila.unidadMedida;
                obj.cantidad = fila.cantidad;
                dataProductos.push(obj);
            });

            crearListadoProductoCompuesto();

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function variableUrl() {
    var src = '';
    var cadenaPrueba = '';
    if (String(window.location.href).split('?')[1]) {
        src = String(window.location.href).split('?')[1];
        src = src.replace("%C2%A0%C2%A0%C2%A0", "");
        src = decodeURI(src);
        var srcDos = src.split('=');

        src = src.split('&');
        for (i = 0; i < src.length; i++) {
            src[i] = src[i].substring(src[i].indexOf('=') + 1);
        }
        if (src[3] != '1') {
            cadena = src[0].indexOf(' ');
            src[0] = src[0].substring(cadena + 1);
        }
    }
    return src;
}
function cargarListadoProductoCompuesto() {
    $("#divListadoProductos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Unidad Medida</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th>Acción</th>';
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
    $("#divListadoProductos").html(tabla);
}
function crearListadoProductoCompuesto() {
    $("#divListadoProductos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Código</th>';
    tabla += '<th>Producto</th>';
    tabla += '<th>Unidad Medida</th>';
    tabla += '<th>Cantidad</th>';
    tabla += '<th colspan="2">Acción</th>';
    tabla += '</tr>';

    if (dataProductos.length == 0) {
        cargarListadoProductoCompuesto();
        return false;
    }

    for (var i = 0; i < dataProductos.length; i++) {
        var obj = dataProductos[i];
        tabla += '<tr>';
        tabla += "<td align='center'>" + (i + 1) + "</td>";
        tabla += "<td>" + obj.codigo + "</td>";
        tabla += "<td>" + obj.producto + "</td>";
        tabla += "<td>" + obj.unidadMedida + "</td>";
        tabla += "<td align='center'>" + obj.cantidad + "</td>";
        tabla += '<td align="center"><span class="fa fa-pencil imagenesTabla" id="imgEditarProductoCompuesto' + i + '" title="Editar" class="imagenesTabla" onclick="editarProductoCompuesto(' + i + ')"></span></td>';
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarProductoCompuesto' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarProductoCompuesto(' + i + ')"></span></td>';
        tabla += '</tr>';
    }

    tabla += '</table>';
    $("#divListadoProductos").html(tabla);
}
function autoCompletarProducto() {
    $("#txtProductoCompuesto").autocomplete({
        source: localStorage.modulo + 'ajax/transaccion.autoCompletarProducto.php?idProductos=' + idProductosSeleccionados.join() + '&compuesto=false',
        select: function (event, ui) {
            idProductoCompuesto = ui.item.idProducto;
            $("#txtCodigoProductoCompuesto").val(ui.item.codigo);
            $("#txtUnidadMedidaCompuesto").val(ui.item.unidadMedida);
        }
    });
}
function consultarProducto(codigoProducto) {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/transaccion.consultarProductos.php',
        type: 'POST',
        dataType: "json",
        data: {codigoProducto: codigoProducto
            , idProducto: idProductosSeleccionados.join()
            , compuesto: false},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                $("#txtProductoCompuesto").val("");
                $("#txtUnidadMedidaCompuesto").val("");
                idProductoCompuesto = null;
                return false;
            }

            $.each(json.data, function (contador, fila) {
                idProductoCompuesto = fila.idProducto;
                $("#txtProductoCompuesto").val(fila.producto);
                $("#txtUnidadMedidaCompuesto").val(fila.unidadMedida);
            });

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function limpiarVariablesProductoCompuesto() {
    idProductoCompuesto = "";
    $("#hidIdProductoComposicion").val("");
    $("#txtCodigoProductoCompuesto").val("");
    $("#txtProductoCompuesto").val("");
    $("#txtCantidadProductoCompuesto").val("");
    $("#txtUnidadMedidaCompuesto").val("");
    $("#txtCodigoProductoCompuesto").focus();
}
function editarProductoCompuesto(posicion) {
    var obj = dataProductos[posicion];
    idProductoCompuesto = obj.idProducto;
    $("#hidIdProductoComposicion").val(obj.idProductoComposicion);
    $("#txtCodigoProductoCompuesto").val(obj.codigo);
    $("#txtProductoCompuesto").val(obj.producto);
    $("#txtUnidadMedidaCompuesto").val(obj.unidadMedida);
    $("#txtCantidadProductoCompuesto").val(reemplazarPuntoPorComa(obj.cantidad));
    $("#txtCantidadProductoCompuesto").focus();
    posicionTemp = parseInt(posicion);
}
function eliminarProductoCompuesto(posicion) {
    var obj = dataProductos[posicion];
    idBorrar = obj.idProductoComposicion;
    if (idBorrar != "" && idBorrar != null && idBorrar != "null") {
        arrIdEliminar.push(idBorrar);
    }
    dataProductos.splice(posicion, 1);
    idProductosSeleccionados.splice(posicion, 1);

    autoCompletarProducto();
    crearListadoProductoCompuesto();
}
function habilitarFuncionesProductoCompuesto() {
    $("#linkProductosCompuestos").show();
    autoCompletarProducto();
    //validarNumeros("txtCantidadProductoCompuesto");
    realizarFoco(document.frmProductoCompuesto, '$("#imgNuevoProducto").click()');
}
function validarExistenciaCodigoProducto() {
    var retorno;
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/producto.validarExistenciaCodigo.php',
        type: 'POST',
        dataType: "json",
        data: {codigo: $.trim($("#txtCodigoProducto").val())},
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            retorno = json.existe;

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
    return retorno;
}

//---------FUNCIONES PRODUCTO IMPUESTO----------------------
function cargarImpuestos() {
    $.ajax({
        async: false,
        url: localStorage.modulo + "controlador/producto.cargarImpuestos.php"
        , type: 'POST'
        , dataType: "json"
        , data: null
        , success: function (json) {
            var control = $('#selImpuesto');
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option lang="' + fila.idTipoImpuesto + '" label="' + fila.tipoImpuesto + '" value="' + fila.idImpuesto + '">' + fila.impuesto + '</option>');
            });
        }
    });
}
function consultarProductoImpuesto() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/productoImpuesto.consultar.php',
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
                cargarListadoImpuestos();
                return false;
            }

            $.each(json.data, function (contador, fila) {
                var obj = new Object();
                obj.idProductoImpuesto = fila.idProductoImpuesto;
                obj.idImpuesto = fila.idImpuesto;
                obj.impuesto = fila.impuesto;
                obj.idTipoImpuesto = fila.idTipoImpuesto;
                obj.tipoImpuesto = fila.tipoImpuesto;
                obj.valor = fila.valor;
                dataImpuestos.push(obj);

                $('#selImpuesto option[value=' + fila.idImpuesto + ']').hide();
                $('#selImpuesto option[value=' + fila.idImpuesto + ']').attr("disabled", true);
            });

            crearListadoImpuestos();

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarListadoImpuestos() {
    $("#divListadoImpuestos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Impuesto</th>';
    tabla += '<th>Tipo Impuesto</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoImpuestos").html(tabla);
}
function crearListadoImpuestos() {
    $("#divListadoImpuestos").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<thead>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Impuesto</th>';
    tabla += '<th>Tipo Impuesto</th>';
    tabla += '<th>Valor</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';

    if (dataImpuestos.length == 0) {
        cargarListadoImpuestos();
        return false;
    }

    for (var i = 0; i < dataImpuestos.length; i++) {
        var obj = dataImpuestos[i];
        tabla += '<tr>';
        tabla += '<td align="center">' + (i + 1) + '</td>';
        tabla += '<td>' + obj.impuesto + '</td>';
        tabla += '<td>' + obj.tipoImpuesto + '</td>';
        tabla += '<td align="right">' + String(parseInt(obj.valor)) + '</td>';
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarImpuesto' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarProductoImpuesto(' + i + ')"></span></td>';
        tabla += '</tr>';
    }
    tabla += '</tbody>';
    tabla += '</table>';
    $("#divListadoImpuestos").html(tabla);
}
function limpiarVariablesProductoImpuesto() {
    $("#selImpuesto").val("");
    $("#txtValorImpuesto").val("");
    $("#spnTipoImpuesto").html("");
    $("#selImpuesto").focus();
}
function eliminarProductoImpuesto(posicion) {
    var obj = dataImpuestos[posicion];
    $('#selImpuesto option[value=' + obj.idImpuesto + ']').show();
    $('#selImpuesto option[value=' + obj.idImpuesto + ']').attr("disabled", false);
    idBorrar = obj.idProductoImpuesto;
    if (idBorrar != "" && idBorrar != null && idBorrar != "null") {
        arrIdEliminarImpuestos.push(idBorrar);
    }
    dataImpuestos.splice(posicion, 1);
    crearListadoImpuestos();
}

//-------------------------FUNCIONES CODIGOS DE BARRA------------------------------
function consultarProductoCodigoBarras() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/productoCodigoBarras.consultar.php',
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
                cargarListadoCodigosBarra();
                return false;
            }

            $.each(json.data, function (contador, fila) {
                var obj = new Object();
                obj.idProductoCodigoBarra = fila.idProductoCodigoBarras;
                obj.codigoBarra = fila.codigoBarras;
                dataCodigosBarra.push(obj);

            });

            crearListadoCodigosBarra();

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
function cargarListadoCodigosBarra() {
    $("#divListadoCodigosBarra").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Codigo de barra</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoCodigosBarra").html(tabla);
}
function crearListadoCodigosBarra() {
    $("#divListadoCodigosBarra").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<thead>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Codigo de barra</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';

    if (dataCodigosBarra.length == 0) {
        cargarListadoCodigosBarra();
        return false;
    }

    for (var i = 0; i < dataCodigosBarra.length; i++) {
        var obj = dataCodigosBarra[i];
        tabla += '<tr>';
        tabla += '<td align="center">' + (i + 1) + '</td>';
        tabla += '<td>' + obj.codigoBarra + '</td>';
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarCodigoBarra' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarCodigoBarra(' + i + ')"></span></td>';
        tabla += '</tr>';
    }
    tabla += '</tbody>';
    tabla += '</table>';
    $("#divListadoCodigosBarra").html(tabla);
}
function eliminarCodigoBarra(posicion) {
    var obj = dataCodigosBarra[posicion];
    idBorrar = obj.idProductoCodigoBarra;
    if (idBorrar != "" && idBorrar != null && idBorrar != "null") {
        arrIdEliminarCodigosBarra.push(idBorrar);
    }
    dataCodigosBarra.splice(posicion, 1);
    crearListadoCodigosBarra();
    $("#txtCodigoBarra").focus();
}
function validarCodigosBarraRepetidos(codigoBarra) {
    for (var i = 0; i < dataCodigosBarra.length; i++) {
        var obj = dataCodigosBarra[i];
        if ($.trim(codigoBarra) == obj.codigoBarra) {
            alerta('El código de barras ya ha sido ingresado anteriormente.');
            return false;
        }
    }
    return true;
}

//-------------------------FUNCIONES BODEGA----------------------------
function cargarBodegas() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/transaccion.consultarBodegas.php',
        data: null,
        dataType: "json",
        type: 'POST',
        success: function (json) {
            var exito = json.exito;
            var mensaje = json.mensaje;

            if (exito == 0) {
                alerta(mensaje);
                return false;
            }

            if (json.numeroRegistros == 0) {
                var control = $("#selBodega");
                control.empty();
                return false;
            }
            var control = $("#selBodega");
            control.empty();
            control.append('<option value="">--Seleccione--</option>');
            $.each(json.data, function (contador, fila) {
                control.append('<option value="' + fila.idBodega + '">' + fila.bodega + '</option>');
            });
        }, error: function (xhr, opciones, error) {
            alerta(error);
        }
    });
}
function cargarListadoBodegas() {
    $("#divListadoBodegas").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Bodega</th>';
    tabla += '<th>Máximo</th>';
    tabla += '<th>Mínimo</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '<tr>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '<td>&nbsp;</td>';
    tabla += '</tr>';
    tabla += '</table>';
    $("#divListadoBodegas").html(tabla);
}
function crearListadoBodegas() {
    $("#divListadoBodegas").html("");
    var tabla = '<table class="table table-bordered table-striped consultaTabla">';
    tabla += '<thead>';
    tabla += '<tr>';
    tabla += '<th>#</th>';
    tabla += '<th>Bodega</th>';
    tabla += '<th>Máximo</th>';
    tabla += '<th>Mínimo</th>';
    tabla += '<th>Acción</th>';
    tabla += '</tr>';
    tabla += '</thead>';
    tabla += '<tbody>';

    if (dataBodegas.length == 0) {
        cargarListadoBodegas();
        return false;
    }

    for (var i = 0; i < dataBodegas.length; i++) {
        var obj = dataBodegas[i];
        tabla += '<tr>';
        tabla += '<td align="center">' + (i + 1) + '</td>';
        tabla += '<td>' + obj.bodega + '</td>';
        tabla += '<td align="right">' + obj.maximo + '</td>';
        tabla += '<td align="right">' + obj.minimo + '</td>';
        tabla += '<td align="center"><span class="fa fa-trash imagenesTabla" id="imgBorrarBodega' + i + '" title="Eliminar" class="imagenesTabla" onclick="eliminarBodega(' + i + ')"></span></td>';
        tabla += '</tr>';
    }
    tabla += '</tbody>';
    tabla += '</table>';
    $("#divListadoBodegas").html(tabla);
}
function limpiarVariablesBodegas() {
    $("#selBodega").val("");
    $("#txtMaximo").val("");
    $("#txtMinimo").val("");
    $("#selBodega").focus();
}
function eliminarBodega(posicion) {
    var obj = dataBodegas[posicion];
    $('#selBodega option[value=' + obj.idBodega + ']').show();
    $('#selBodega option[value=' + obj.idBodega + ']').attr("disabled", false);
    idBorrar = obj.idProductoExistencia;
    if (idBorrar != "" && idBorrar != null && idBorrar != "null") {
        arrIdEliminarBodegas.push(idBorrar);
    }
    dataBodegas.splice(posicion, 1);
    crearListadoBodegas();
}
function consultarBodegas() {
    $.ajax({
        async: false,
        url: localStorage.modulo + 'controlador/productoExistencia.consultar.php',
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
                cargarListadoBodegas();
                return false;
            }

            $.each(json.data, function (contador, fila) {
                var obj = new Object();
                obj.idProductoExistencia = fila.idProductoExistencia;
                obj.idBodega = fila.idBodega;
                obj.bodega = fila.bodega;
                obj.maximo = fila.maximo;
                obj.minimo = fila.minimo;
                dataBodegas.push(obj);

                $('#selBodega option[value=' + fila.idBodega + ']').hide();
                $('#selBodega option[value=' + fila.idBodega + ']').attr("disabled", true);
            });

            crearListadoBodegas();

        }, error: function (xhr, opciones, error) {
            alerta(error);
            return false;
        }
    });
}
