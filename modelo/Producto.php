<?php

namespace modelo;

session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../../Seguridad/entidad/ParametroAplicacion.php';
require_once '../../Seguridad/modelo/ParametroAplicacion.php';

class Producto {

    public $conexion;
    private $condicion;
    private $whereAnd;
    private $idProducto;
    private $producto;
    private $tangible;
    private $idUnidadMedida;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idEmpresaUnidadNegocio;
    private $idLineaProducto;
    private $imagen;
    private $manejaInventario;
    private $valorEntrada;
    private $valorSalida;
    private $productoServicio;
    private $productoSerial;
    private $codigo;
    private $productoComposicion;
    private $valorEntraConImpue;
    private $valorSalidConImpue;
    private $maximo;
    private $minimo;

    public function __construct(\entidad\Producto $producto, $conexion = null) {
        $this->idProducto = $producto->getIdProducto();
        $this->producto = $producto->getProducto();
        $this->tangible = $producto->getTangible();
        $this->idUnidadMedida = $producto->getIdUnidadMedida();
        $this->estado = $producto->getEstado();
        $this->idUsuarioCreacion = $producto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $producto->getIdUsuarioModificacion();
        $this->fechaCreacion = $producto->getFechaCreacion();
        $this->fechaModificacion = $producto->getFechaModificacion();
        $this->idEmpresaUnidadNegocio = $producto->getIdEmpresaUnidadNegocio();
        $this->idLineaProducto = $producto->getIdLineaProducto();
        $this->imagen = $producto->getImagen();
        $this->manejaInventario = $producto->getManejaInventario();
        $this->valorEntrada = $producto->getValorEntrada();
        $this->valorSalida = $producto->getValorSalida();
        $this->productoServicio = $producto->getProductoServicio();
        $this->productoSerial = $producto->getProductoSerial();
        $this->codigo = $producto->getCodigo();
        $this->productoComposicion = $producto->getProductoComposicion();
        $this->valorEntraConImpue = $producto->getValorEntraConImpue();
        $this->valorSalidConImpue = $producto->getValorSalidConImpue();
        $this->maximo = $producto->getMaximo() != "" ? $producto->getMaximo() : "null";
        $this->minimo = $producto->getMinimo() != "" ? $producto->getMinimo() : "null";

        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }

    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.producto
                        (
                            producto
                            , tangible
                            , id_unidad_medida
                            , estado
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , id_empresa_unidad_negocio
                            , id_linea_producto
                            , imagen
                            , maneja_inventario
                            , valor_entrada
                            , valor_salida
                            , producto_servicio
                            , producto_serial
                            , codigo
                            , producto_composicion
                            , maximo
                            , minimo
                        )
                        VALUES
                        (
                            '$this->producto'
                            , $this->tangible
                            , $this->idUnidadMedida
                            , $this->estado
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                            , $this->idEmpresaUnidadNegocio
                            , $this->idLineaProducto
                            , $this->imagen
                            , $this->manejaInventario
                            , $this->valorEntrada
                            , $this->valorSalida
                            , $this->productoServicio
                            , $this->productoSerial
                            , '$this->codigo'
                            , $this->productoComposicion
                            , $this->maximo
                            , $this->minimo
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function modificar() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto
                        SET
                            producto = '$this->producto'
                            , tangible = $this->tangible
                            , id_unidad_medida = $this->idUnidadMedida
                            , estado = $this->estado
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                            , id_empresa_unidad_negocio = $this->idEmpresaUnidadNegocio
                            , id_linea_producto = $this->idLineaProducto
                            , imagen = $this->imagen
                            , maneja_inventario = $this->manejaInventario
                            , valor_entrada = $this->valorEntrada
                            , valor_salida = $this->valorSalida
                            , producto_servicio = $this->productoServicio
                            , producto_serial = $this->productoSerial
                            , codigo = '$this->codigo'
                            , producto_composicion = $this->productoComposicion
                            , maximo = $this->maximo
                            , minimo = $this->minimo
                        WHERE
                            id_producto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultar() {

        /* ----------------------------------------------------------------------------------------------------------------- */
        $parametroAplicacionE = new \entidad\ParametroAplicacion();
        $parametroAplicacionE->setIdParametroAplicacion(19); //PARAMETRO-> ENTRADA - Calcular valor base de los producto impuesto
        $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
        $arrParametroAplicacion = $parametroAplicacionM->consultar();
        $calculaValorBaseEntrada = $arrParametroAplicacion[0]["valor"];

        $parametroAplicacionE = new \entidad\ParametroAplicacion();
        $parametroAplicacionE->setIdParametroAplicacion(20); //PARAMETRO-> SALIDA - Calcular valor base de los producto impuesto
        $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
        $arrParametroAplicacion = $parametroAplicacionM->consultar();
        $calculaValorBaseSalida = $arrParametroAplicacion[0]["valor"];
        /* ----------------------------------------------------------------------------------------------------------------- */

        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            p.id_producto
                            , p.producto
                            , p.codigo
                            , (p.valor_entrada) AS valor_entrada
                            , (p.valor_salida) AS valor_salida
                            --, CEILING(p.valor_entra_con_impue) AS valor_entra_con_impue
                            --, CEILING(p.valor_salid_con_impue) AS valor_salid_con_impue
                            , p.valor_entra_con_impue AS valor_entra_con_impue
                            , p.valor_salid_con_impue AS valor_salid_con_impue
                            , p.id_unidad_medida
                            , um.unidad_medida
                            , p.producto_serial
                            , p.id_linea_producto
                            , lp.linea_producto
                            , p.imagen
                            , p.id_empresa_unidad_negocio
                            , p.maneja_inventario
                            , p.producto_servicio
                            , p.producto_composicion
                            , p.estado
                            , p.tangible
                            , p.maximo
                            , p.minimo
                        FROM
                            facturacion_inventario.producto AS p
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                            $this->condicion
                        ORDER BY
                            p.id_producto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["idUnidadMedida"] = $fila->id_unidad_medida;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["productoSerial"] = $fila->producto_serial;
            $retorno[$contador]["idLineaProducto"] = $fila->id_linea_producto;
            $retorno[$contador]["lineaProducto"] = $fila->linea_producto;
            $retorno[$contador]["valorEntrada"] = $fila->valor_entrada;
            $retorno[$contador]["valorSalida"] = $fila->valor_salida;
            $retorno[$contador]["valorEntraConImpue"] = $fila->valor_entra_con_impue;
            $retorno[$contador]["valorSalidConImpue"] = $fila->valor_salid_con_impue;
            $retorno[$contador]["imagen"] = $fila->imagen;
            $retorno[$contador]["idEmpresaUnidadNegocio"] = $fila->id_empresa_unidad_negocio;
            $retorno[$contador]["manejaInventario"] = $fila->maneja_inventario;
            $retorno[$contador]["productoServicio"] = $fila->producto_servicio;
            $retorno[$contador]["productoComposicion"] = $fila->producto_composicion;
            $retorno[$contador]["estado"] = $fila->estado;
            $retorno[$contador]["tangible"] = $fila->tangible;
            $retorno[$contador]["maximo"] = $fila->maximo;
            $retorno[$contador]["minimo"] = $fila->minimo;
            
            if($calculaValorBaseEntrada == "1"){
                $retorno[$contador]["valorEntradaMostrar"] = $fila->valor_entra_con_impue;
            }else{
                $retorno[$contador]["valorEntradaMostrar"] = $fila->valor_entrada;
            }
            
            if($calculaValorBaseSalida == "1"){
                $retorno[$contador]["valorSalidaMostrar"] = $fila->valor_salid_con_impue;
            }else{
                $retorno[$contador]["valorSalidaMostrar"] = $fila->valor_salida;
            }
            
            $contador++;
        }
        return $retorno;
    }

    function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->idProducto != null && $this->idProducto != "null" && $this->idProducto != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_producto IN (" . $this->idProducto . ")";
            $this->whereAnd = " AND ";
        }
        if ($this->codigo != null && $this->codigo != "null" && $this->codigo != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.codigo = '" . $this->codigo . "'";
            $this->whereAnd = " AND ";
        }
        if ($this->producto != null && $this->producto != "null" && $this->producto != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.producto ILIKE '%" . $this->producto . "%'";
            $this->whereAnd = " AND ";
        }
        if ($this->idUnidadMedida != null && $this->idUnidadMedida != "null" && $this->idUnidadMedida != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_unidad_medida = " . $this->idUnidadMedida;
            $this->whereAnd = " AND ";
        }
        if ($this->productoComposicion != null && $this->productoComposicion != "null" && $this->productoComposicion != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.producto_composicion = " . $this->productoComposicion;
            $this->whereAnd = " AND ";
        }
        if ($this->idLineaProducto != null && $this->idLineaProducto != "null" && $this->idLineaProducto != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_linea_producto = " . $this->idLineaProducto;
            $this->whereAnd = " AND ";
        }
        if ($this->estado != null && $this->estado != "null" && $this->estado != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }

    public function consultarProductos() {
        
        /* ----------------------------------------------------------------------------------------------------------------- */
        $parametroAplicacionE = new \entidad\ParametroAplicacion();
        $parametroAplicacionE->setIdParametroAplicacion(19); //PARAMETRO-> ENTRADA - Calcular valor base de los producto impuesto
        $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
        $arrParametroAplicacion = $parametroAplicacionM->consultar();
        $calculaValorBaseEntrada = $arrParametroAplicacion[0]["valor"];

        $parametroAplicacionE = new \entidad\ParametroAplicacion();
        $parametroAplicacionE->setIdParametroAplicacion(20); //PARAMETRO-> SALIDA - Calcular valor base de los producto impuesto
        $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
        $arrParametroAplicacion = $parametroAplicacionM->consultar();
        $calculaValorBaseSalida = $arrParametroAplicacion[0]["valor"];
        /* ----------------------------------------------------------------------------------------------------------------- */
        
        $this->obtenerCondicionConsultarProductos();
        $sentenciaSql = "
                        SELECT
                            p.id_producto
                            , p.producto
                            , p.codigo
                            , p.valor_entrada
                            , p.valor_salida
                            , p.valor_entra_con_impue
                            , p.valor_salid_con_impue
                            , um.unidad_medida
                            , p.producto_serial
                            , p.producto_composicion
                            , i.id_tipo_impuesto
                            , CAST(pi.valor AS INT) AS valor_impuesto
                            , (SELECT 
                                    CONCAT_WS(' - ', CONCAT(CEILING(pi.valor), i.simbolo), i.impuesto) AS impuesto
                            FROM
                                    facturacion_inventario.producto_impuesto AS pi
                                    INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = pi.id_impuesto
                            WHERE
                                    pi.id_producto = p.id_producto
                                    --AND pi.id_impuesto = 1 --SOLO MUESTRA IVA
                            ) AS impuesto
                        FROM
                            facturacion_inventario.producto AS p
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.producto_impuesto AS pi ON pi.id_producto = p.id_producto
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = pi.id_impuesto
                            $this->condicion
                        ORDER BY
                            p.id_producto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["valorEntrada"] = $fila->valor_entrada;
            $retorno[$contador]["valorSalida"] = $fila->valor_salida;
            $retorno[$contador]["valorEntraConImpue"] = $fila->valor_entra_con_impue;
            $retorno[$contador]["valorSalidConImpue"] = $fila->valor_salid_con_impue;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["productoSerial"] = $fila->producto_serial;
            $retorno[$contador]["productoComposicion"] = $fila->producto_composicion;
            
            if($calculaValorBaseEntrada == "1"){
                $retorno[$contador]["valorEntradaMostrar"] = $fila->valor_entra_con_impue;
            }else{
                $retorno[$contador]["valorEntradaMostrar"] = $fila->valor_entrada;
            }
            
            if($calculaValorBaseSalida == "1"){
                $retorno[$contador]["valorSalidaMostrar"] = $fila->valor_salid_con_impue;
            }else{
                $retorno[$contador]["valorSalidaMostrar"] = $fila->valor_salida;
            }
            
            if($fila->impuesto != "" && $fila->impuesto != null && $fila->impuesto != "null")
                $retorno[$contador]["impuesto"] = $fila->impuesto;
            else
                $retorno[$contador]["impuesto"] = "0%";
            
            $retorno[$contador]["idTipoImpuesto"] = $fila->id_tipo_impuesto;
            $retorno[$contador]["valorImpuesto"] = $fila->valor_impuesto;
            $contador++;
        }
        return $retorno;
    }

    public function consultarProductosComposicion() {
        $sentenciaSql = "
                        SELECT
                            p.id_producto
                            , p.producto
                            , p.codigo
                            , p.valor_entra_con_impue
                            , um.unidad_medida
                            , p.producto_serial
                            , pc.cantidad
                            ,pc.id_producto_composicion
                        FROM
                            facturacion_inventario.producto AS p
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.producto_composicion AS pc ON pc.id_producto_compone = p.id_producto
                        WHERE
                            pc.id_producto_compuesto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProductoComposicion"] = $fila->id_producto_composicion;
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["valorEntraConImpue"] = $fila->valor_entra_con_impue;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["productoSerial"] = $fila->producto_serial;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $contador++;
        }
        return $retorno;
    }

    function obtenerCondicionConsultarProductos() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->idProducto != null && $this->idProducto != "null" && $this->idProducto != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_producto NOT IN (" . $this->idProducto . ")";
            $this->whereAnd = " AND ";
        }

        if ($this->codigo != null && $this->codigo != "null" && $this->codigo != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.codigo = '" . $this->codigo . "'";
            $this->whereAnd = " AND ";
        }

        if ($this->productoComposicion != null && $this->productoComposicion != "null" && $this->productoComposicion != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.producto_composicion = " . $this->productoComposicion;
            $this->whereAnd = " AND ";
        }

        if ($this->estado != null && $this->estado != "null" && $this->estado != "") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }

    public function buscarProducto($producto, $idProductos, $compuesto, $servicio = "", $limite = '' , $facturaCliente = '') {
        
        /* ----------------------------------------------------------------------------------------------------------------- */
        $parametroAplicacionE = new \entidad\ParametroAplicacion();
        $parametroAplicacionE->setIdParametroAplicacion(19); //PARAMETRO-> ENTRADA - Calcular valor base de los producto impuesto
        $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
        $arrParametroAplicacion = $parametroAplicacionM->consultar();
        $calculaValorBaseEntrada = $arrParametroAplicacion[0]["valor"];

        $parametroAplicacionE = new \entidad\ParametroAplicacion();
        $parametroAplicacionE->setIdParametroAplicacion(20); //PARAMETRO-> SALIDA - Calcular valor base de los producto impuesto
        $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
        $arrParametroAplicacion = $parametroAplicacionM->consultar();
        $calculaValorBaseSalida = $arrParametroAplicacion[0]["valor"];
        
        /* ----------------------------------------------------------------------------------------------------------------- */
        
        $top = '';
        if($limite != ''){
            $top = ' LIMIT '.$limite;
        }
        
        $datos = array();
        $condicion = "";
        if ($idProductos != '' && $idProductos != 'null' && $idProductos != null) {
            $condicion .= " AND p.id_producto NOT IN ($idProductos)";
        }
        if ($compuesto != '' && $compuesto != 'null' && $compuesto != null) {
            $condicion .= " AND p.producto_composicion = $compuesto ";
        }
        if ($servicio != '' && $servicio != 'null' && $servicio != null) {
            $condicion .= " AND p.producto_servicio = $servicio ";
        }

        $sentenciaSql = "
                    SELECT
                        p.id_producto
                        , p.producto
                        , p.codigo
                        , p.valor_entrada
                        , p.valor_salida
                        , p.valor_entra_con_impue
                        , p.valor_salid_con_impue
                        , um.unidad_medida
                        , p.producto_serial
                        , p.producto_composicion
                        , i.id_tipo_impuesto
                        , CAST(pi.valor AS INT) AS valor_impuesto
                        , (SELECT 
                                CONCAT_WS(' - ', CONCAT(CEILING(pi.valor), i.simbolo), i.impuesto) AS impuesto
                        FROM
                                facturacion_inventario.producto_impuesto AS pi
                                INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = pi.id_impuesto
                        WHERE
                                pi.id_producto = p.id_producto
                                --AND pi.id_impuesto = 1 --SOLO IVA
                        ) AS impuesto
                    FROM
                        facturacion_inventario.producto AS p
                        INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                        INNER JOIN facturacion_inventario.producto_impuesto AS pi ON pi.id_producto = p.id_producto
                        INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = pi.id_impuesto
                    WHERE
                        p.producto ILIKE '%$producto%'
                        AND p.estado = TRUE
                        $condicion
                    ORDER BY
                            p.producto
                    $top
                    ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            
            if ($facturaCliente == true || $facturaCliente == "true"){
                $valorEntradaMostrar = $fila->valor_entra_con_impue;
                $valorSalidaMostrar = $fila->valor_salid_con_impue;
            }else{
                if ($facturaCliente == false || $facturaCliente == "false"){
                    $valorEntradaMostrar = $fila->valor_entrada;
                    $valorSalidaMostrar = $fila->valor_salida;
                }else{
                    if($calculaValorBaseEntrada == "1"){
                        $valorEntradaMostrar = $fila->valor_entra_con_impue;
                    }else{
                        $valorEntradaMostrar = $fila->valor_entrada;
                    }

                    if($calculaValorBaseSalida == "1"){
                        $valorSalidaMostrar = $fila->valor_salid_con_impue;
                    }else{
                        $valorSalidaMostrar = $fila->valor_salida;
                    }
                }
            }
            
            
            if($fila->impuesto != "" && $fila->impuesto != null && $fila->impuesto != "null")
                $impuesto = $fila->impuesto;
            else
                $impuesto = "0%";
            
            $datos[] = array("value" => "$".number_format((int)$valorEntradaMostrar, 0, '', '.')." - ".$fila->producto
                , "idProducto" => $fila->id_producto
                , "codigo" => $fila->codigo
                , "producto" => $fila->producto
                , "valorEntrada" => $fila->valor_entrada
                , "valorSalida" => $fila->valor_salida
                , "valorEntraConImpue" => $fila->valor_entra_con_impue
                , "valorSalidConImpue" => $fila->valor_salid_con_impue
                , "unidadMedida" => $fila->unidad_medida
                , "productoSerial" => $fila->producto_serial
                , "productoComposicion" => $fila->producto_composicion
                , "valorEntradaMostrar" => $valorEntradaMostrar
                , "valorSalidaMostrar" => $valorSalidaMostrar
                , "impuesto" => $impuesto
                , "idTipoImpuesto" => $fila->id_tipo_impuesto
                , "valorImpuesto" => $fila->valor_impuesto
            );
        }
        return $datos;
    }

    public function buscarProductoCodigo($producto) {
        $datos = array();

        $sentenciaSql = "
                    SELECT
                        p.id_producto
                        , p.producto
                        , p.codigo
                    FROM
                        facturacion_inventario.producto AS p
                        INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                    WHERE
                        CONCAT_WS(' - ', p.codigo, p.producto) ILIKE '%$producto%'
                    ORDER BY
                            p.id_producto
                    LIMIT 10
                    ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array("value" => "$fila->codigo - $fila->producto"
                , "idProducto" => $fila->id_producto
                , "codigo" => $fila->codigo
                , "producto" => $fila->producto
            );
        }
        return $datos;
    }

    public function buscarProductoCompuesto($producto, $limite = '') {
        
        $top = '';
        if($limite != ''){
            $top = ' LIMIT '.$limite;
        }
        
        $datos = array();

        $sentenciaSql = "
                    SELECT
                        p.id_producto
                        , p.codigo
                        , p.producto
                    FROM
                        facturacion_inventario.producto AS p
                    WHERE
                        p.producto ILIKE '%$producto%'
                        AND p.estado = TRUE
                        AND p.maneja_inventario = TRUE
                        AND p.producto_composicion = TRUE
                    ORDER BY
                            p.producto
                    $top
                    ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array("value" => $fila->producto
                , "idProducto" => $fila->id_producto
                , "codigo" => $fila->codigo
            );
        }
        return $datos;
    }
    
    public function consultarProductoCompuesto() {
        $sentenciaSql = "
                    SELECT
                        p.id_producto
                        , p.codigo
                        , p.producto
                    FROM
                        facturacion_inventario.producto AS p
                    WHERE
                        p.codigo = '$this->codigo'
                        AND p.estado = TRUE
                        AND p.maneja_inventario = TRUE
                        AND p.producto_composicion = TRUE
                    ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["producto"] = $fila->producto;
            $contador++;
        }
        return $retorno;
    }
    
    public function inactivar() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto
                        SET
                            estado = FALSE
                            , id_usuario_modificacion = " . $_SESSION["idUsuario"] . "
                            , fecha_modificacion = NOW()
                        WHERE
                            id_producto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function obtenerMaximo() {
        $sentenciaSql = "
                        SELECT
                            MAX(id_producto) AS maximo
                        FROM
                            facturacion_inventario.producto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }

    public function actualizarNombreImagen() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto
                        SET
                            imagen = $this->imagen
                        WHERE
                            id_producto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function validarExistenciaCodigo() {
        $sentenciaSql = "
                        SELECT
                            codigo
                        FROM
                            facturacion_inventario.producto
                        WHERE
                            codigo ILIKE '$this->codigo'
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() == 0) {
            $retorno = false;
        } else {
            $retorno = true;
        }
        return $retorno;
    }

    public function actualizarImpuestos() {
        $sentenciaSql = "
                        UPDATE
                           facturacion_inventario.producto 
                        SET
                            valor_entra_con_impue = $this->valorEntraConImpue
                            , valor_salid_con_impue = $this->valorSalidConImpue
                            , valor_entrada = $this->valorEntrada
                            , valor_salida =  $this->valorSalida
                        WHERE
                            id_producto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultarProductoBodega() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            p.id_producto
                            , p.producto
                            , p.codigo
                            , CEILING(p.valor_entrada) AS valor_entrada
                            , CEILING(p.valor_salida) AS valor_salida
                            , CEILING(p.valor_entra_con_impue) AS valor_entra_con_impue
                            , CEILING(p.valor_salid_con_impue) AS valor_salid_con_impue
                            , p.id_unidad_medida
                            , um.unidad_medida
                            , p.producto_serial
                            , p.id_linea_producto
                            , lp.linea_producto
                            , p.imagen
                            , p.id_empresa_unidad_negocio
                            , p.maneja_inventario
                            , p.producto_servicio
                            , p.producto_composicion
                            , p.estado
                            , p.tangible
                        FROM
                            facturacion_inventario.producto AS p
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                            $this->condicion
                        ORDER BY
                            p.id_producto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["idUnidadMedida"] = $fila->id_unidad_medida;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["productoSerial"] = $fila->producto_serial;
            $retorno[$contador]["idLineaProducto"] = $fila->id_linea_producto;
            $retorno[$contador]["lineaProducto"] = $fila->linea_producto;
            $retorno[$contador]["valorEntrada"] = $fila->valor_entrada;
            $retorno[$contador]["valorSalida"] = $fila->valor_salida;
            $retorno[$contador]["valorEntraConImpue"] = $fila->valor_entra_con_impue;
            $retorno[$contador]["valorSalidConImpue"] = $fila->valor_salid_con_impue;
            $retorno[$contador]["imagen"] = $fila->imagen;
            $retorno[$contador]["idEmpresaUnidadNegocio"] = $fila->id_empresa_unidad_negocio;
            $retorno[$contador]["manejaInventario"] = $fila->maneja_inventario;
            $retorno[$contador]["productoServicio"] = $fila->producto_servicio;
            $retorno[$contador]["productoComposicion"] = $fila->producto_composicion;
            $retorno[$contador]["estado"] = $fila->estado;
            $retorno[$contador]["tangible"] = $fila->tangible;
            $contador++;
        }
        return $retorno;
    }

    public function modificarValorEntrada() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto
                        SET
                            valor_entrada = $this->valorEntrada
                            , valor_entra_con_impue = $this->valorEntraConImpue
                            , id_usuario_modificacion = " . $_SESSION["idUsuario"] . "
                            , fecha_modificacion = NOW()
                        WHERE
                            id_producto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function modificarValorSalida() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto
                        SET
                            valor_salida = $this->valorSalida
                            , valor_salid_con_impue = $this->valorSalidConImpue
                            , id_usuario_modificacion = " . $_SESSION["idUsuario"] . "
                            , fecha_modificacion = NOW()
                        WHERE
                            id_producto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultarSaldosMinimos($parametros) {
        $this->obtenerCondicionSaldosMinimos($parametros);
        $sentenciaSql = "
                        SELECT
                            pe.maximo
                            , pe.minimo
                            , CAST(pe.cantidad AS INT) AS cantidad
                            , b.bodega
                            , p.codigo
                            , p.producto
                            , lp.linea_producto
                        FROM
                            facturacion_inventario.producto_existencia AS pe
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = pe.id_producto
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = pe.id_bodega
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["maximo"] = $fila->maximo;
            $retorno[$contador]["minimo"] = $fila->minimo;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["lineaProducto"] = $fila->linea_producto;
            $contador++;
        }
        return $retorno;
    }

    private function obtenerCondicionSaldosMinimos($parametros) {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->idProducto != "" && $this->idProducto != "null" && $this->idProducto != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " pe.id_producto = " . $this->idProducto;
            $this->whereAnd = " AND ";
        }

        if ($this->codigo != "" && $this->codigo != "null" && $this->codigo != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " p.codigo = '" . $this->codigo . "'";
            $this->whereAnd = " AND ";
        }

        if ($this->producto != "" && $this->producto != "null" && $this->producto != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " p.producto ILIKE '%" . $this->producto . "%'";
            $this->whereAnd = " AND ";
        }

        if ($this->idUnidadMedida != "" && $this->idUnidadMedida != "null" && $this->idUnidadMedida != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_unidad_medida = " . $this->idUnidadMedida;
            $this->whereAnd = " AND ";
        }

        if ($this->idLineaProducto != "" && $this->idLineaProducto != "null" && $this->idLineaProducto != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_linea_producto = " . $this->idLineaProducto;
            $this->whereAnd = " AND ";
        }

        if ($parametros['idBodega'] != "" && $parametros['idBodega'] != "null" && $parametros['idBodega'] != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " pe.id_bodega = " . $parametros['idBodega'];
            $this->whereAnd = " AND ";
        }

        if ($parametros['mostrarSaldosNegativos'] != "" && $parametros['mostrarSaldosNegativos'] != "null" && $parametros['mostrarSaldosNegativos'] != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " pe.cantidad " . $parametros['mostrarSaldosNegativos'];
            $this->whereAnd = " AND ";
        }

        if ($parametros['maximoMinimo'] != "" && $parametros['maximoMinimo'] != "null" && $parametros['maximoMinimo'] != null) {

            if ($parametros['maximoMinimo'] == '1') {//MAXIMO
                $this->condicion = $this->condicion . $this->whereAnd . " ( pe.maximo = pe.cantidad OR pe.cantidad > pe.maximo ) ";
            } else if ($parametros['maximoMinimo'] == '0') {//MÍNIMO
                $this->condicion = $this->condicion . $this->whereAnd . " ( pe.minimo = pe.cantidad OR pe.cantidad < pe.minimo ) ";
            }
            $this->whereAnd = " AND ";
        }
    }

}
