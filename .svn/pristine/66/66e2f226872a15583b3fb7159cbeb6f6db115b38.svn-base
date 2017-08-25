<?php

namespace modelo;

session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');

class ClientServicio {

    public $conexion;
    private $condicion;
    private $whereAnd;
    private $idClienteServicio;
    private $cliente;
    private $idProductoComposicion;
    private $fechaInicial;
    private $fechaFinal;
    private $ordenTrabajoCliente;
    private $idEstadoClienteServicio;
    private $valor;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;

    public function __construct(\entidad\ClienteServicio $clienteServicio) {
        $this->idClienteServicio = $clienteServicio->getIdClienteServicio();
        $this->cliente = $clienteServicio->getCliente() != "" ? $clienteServicio->getCliente() : new \entidad\Cliente();
        $this->idProductoComposicion = $clienteServicio->getIdProductoComposicion();
        $this->fechaInicial = $clienteServicio->getFechaInicial();
        $this->fechaFinal = $clienteServicio->getFechaFinal();
        $this->ordenTrabajoCliente = $clienteServicio->getOrdenTrabajoCliente() != "" ? $clienteServicio->getOrdenTrabajoCliente() : new \entidad\OrdenTrabajoCliente();
        $this->idEstadoClienteServicio = $clienteServicio->getIdEstadoClienteServicio();
        $this->valor = $clienteServicio->getValor();
        $this->estado = $clienteServicio->getEstado();
        $this->idUsuarioCreacion = $clienteServicio->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $clienteServicio->getIdUsuarioModificacion();

        $this->conexion = new \Conexion();
    }

    function adicionar() {
        $sentenciaSql = "
                            INSERT INTO
                                publics_services.cliente_servicio
                            (
                                id_cliente
                                ,id_producto_composicion
                                ,fecha_inicial
                                ,fecha_final
                                ,id_orden_trabajo_cliente
                                ,valor
                                ,estado
                                ,id_usuario_creacion
                                ,id_usuario_modificacion
                                ,fecha_creacion
                                ,fecha_modificacion
                            )
                            VALUES
                            (
                                " . $this->cliente->getIdCliente() . "
                                ,$this->idProductoComposicion
                                ,'" . $this->fechaInicial . "'
                                ," . $this->fechaFinal . "
                                ," . $this->ordenTrabajoCliente->getIdOrdenTrabajoCliente() . "
                                , " . $this->valor . "
                                , " . $this->estado . "
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                            )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    function modificar() {
        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio
                            SET
                                id_cliente = " . $this->cliente->getIdCliente() . "
                                ,id_producto_composicion = $this->idProductoComposicion
                                ,fecha_inicial = '" . $this->fechaInicial . "'
                                ,fecha_final = '" . $this->fechaFinal . "'
                                ,id_orden_trabajo_cliente = " . $this->ordenTrabajoCliente->getIdOrdenTrabajoCliente() . "
                                ,id_estado_cliente_servicio = " . $this->idEstadoClienteServicio . "
                                ,valor = " . $this->valor . "
                                ,estado = " . $this->estado . "
                                ,id_usuario_modificacion = $this->idUsuarioModificacion
                                ,fecha_modificacion = NOW()
                            WHERE
                                id_cliente_servicio = $this->idClienteServicio
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            cs.id_cliente_servicio
                            , cs.id_estado_cliente_servicio
                            , ecs.estado_cliente_servicio
                            , otc.id_orden_trabajo_cliente
                            , otc.id_orden_trabajo
                            , cs.id_cliente
                            , otc.id_pedido
                            , otc.estado
                            , otc.id_producto_composicion
                            , p.codigo
                            , p.producto
                            , s.sucursal
                            , t.tercero
                            , ot.id_municipio
                            , CONCAT_WS(' ', t.tercero, s.sucursal) as cliente
                            ,TO_CHAR(cs.fecha_inicial,'YYYY-mm-dd') AS fecha_inicial
                            ,TO_CHAR(cs.fecha_final,'YYYY-mm-dd') AS fecha_final
                            ,TO_CHAR(ot.fecha,'YYYY-mm-dd') AS fecha
                            ,ot.id_ordenador
                            ,ot.id_tipo_documento
                            ,ot.numero
                            , eot.estado_orden_trabajo
                            , CONCAT_WS(' ', po.primer_nombre, po.segundo_nombre, po.primer_apellido, po.segundo_apellido) as ordenador
                            , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                            , td.tipo_documento
                            , CASE 
				WHEN ot.tipo_orden_trabajo = 1 THEN 'Instalación'
			        WHEN ot.tipo_orden_trabajo = 2 THEN 'Mantenimiento'
                                WHEN ot.tipo_orden_trabajo = 3 THEN 'Desinstalación'
			        ELSE 'Inválido'
			    END AS tipo
                        FROM
                            publics_services.cliente_servicio cs
                            INNER JOIN publics_services.orden_trabajo_cliente otc ON otc.id_orden_trabajo_cliente = cs.id_orden_trabajo_cliente
                            INNER JOIN publics_services.estado_cliente_servicio ecs ON ecs.id_estado_cliente_servicio = cs.id_estado_cliente_servicio
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = cs.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = ot.id_tipo_documento
                            INNER JOIN publics_services.estado_orden_trabajo AS eot ON eot.id_estado_orden_trabajo = ot.id_estado_orden_trabajo
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = cs.id_producto_composicion
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN nomina.empleado eo ON eo.id_empleado = o.id_empleado
                            INNER JOIN general.persona po ON po.id_persona = eo.id_persona
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                            INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                            $this->condicion
                ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();

        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
            $retorno[$contador]['estadoOrdenTrabajo'] = $fila->estado_orden_trabajo;
            $retorno[$contador]['idEstadoClienteServicio'] = $fila->id_estado_cliente_servicio;
            $retorno[$contador]['estadoClienteServicio'] = $fila->estado_cliente_servicio;
            $retorno[$contador]['idOrdenTrabajoCliente'] = $fila->id_orden_trabajo_cliente;
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['idProducto'] = $fila->id_producto;
            $retorno[$contador]['nota'] = $fila->nota;
            $retorno[$contador]['secuencia'] = $fila->secuencia;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['valorUnitario'] = $fila->valor_unitario;
            $retorno[$contador]['serial'] = $fila->serial;
            $retorno[$contador]['estado'] = $fila->estado;
            $retorno[$contador]['idProductoComposicion'] = $fila->id_producto_composicion;
            $retorno[$contador]['productoComposicion'] = $fila->producto_composicion;
            $retorno[$contador]['codigo'] = $fila->codigo;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['ordenador'] = $fila->ordenador;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['cliente'] = $fila->cliente;
            $retorno[$contador]['terceroSucursal'] = $fila->tercero_sucursal;
            $retorno[$contador]['idMunicipio'] = $fila->id_municipio;
            $retorno[$contador]['municipio'] = $fila->municipio;
            $retorno[$contador]['fechaInicial'] = $fila->fecha_inicial;
            $retorno[$contador]['fechaFinal'] = $fila->fecha_final;
            $retorno[$contador]['fecha'] = $fila->fecha;
            $retorno[$contador]['numero'] = $fila->numero;
            $retorno[$contador]['tipo'] = $fila->tipo;
            $retorno[$contador]['idOrdenador'] = $fila->id_ordenador;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['idTipoDocumento'] = $fila->id_tipo_documento;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;

            $contador++;
        }
        return $retorno;
    }
    
    
    public function consultarInformacionServiciosCliente() {
        try{
            $retorno =  array("exito"=>1,"mensaje"=>"","data"=>"");
            $sentenciaSql = "
                            SELECT
                                cs.id_cliente_servicio
                                , p.codigo
                                , p.producto
                                , s.sucursal
                                , t.tercero
                                , ot.id_municipio
                                , CONCAT_WS(' ', t.tercero, s.sucursal) as cliente
                                ,TO_CHAR(cs.fecha_inicial,'YYYY-mm-dd') AS fecha_inicial
                                ,TO_CHAR(cs.fecha_final,'YYYY-mm-dd') AS fecha_final
                                ,TO_CHAR(ot.fecha,'YYYY-mm-dd') AS fecha
                                ,ot.id_ordenador
                                ,ot.id_tipo_documento
                                ,ot.numero
                                , eot.estado_orden_trabajo
                                , CONCAT_WS(' ', po.primer_nombre, po.segundo_nombre, po.primer_apellido, po.segundo_apellido) as ordenador
                                , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                                , td.tipo_documento
                                , ot.numero
                            FROM
                                publics_services.cliente_servicio cs
                                INNER JOIN publics_services.orden_trabajo_cliente otc ON otc.id_orden_trabajo_cliente = cs.id_orden_trabajo_cliente
                                INNER JOIN publics_services.estado_cliente_servicio ecs ON ecs.id_estado_cliente_servicio = cs.id_estado_cliente_servicio
                                INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = cs.id_cliente
                                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                                INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                                INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = ot.id_tipo_documento
                                INNER JOIN publics_services.estado_orden_trabajo AS eot ON eot.id_estado_orden_trabajo = ot.id_estado_orden_trabajo
                                INNER JOIN facturacion_inventario.producto p ON p.id_producto = cs.id_producto_composicion
                                INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                                INNER JOIN nomina.empleado eo ON eo.id_empleado = o.id_empleado
                                INNER JOIN general.persona po ON po.id_persona = eo.id_persona
                                INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                                INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                            WHERE 
                                cs.id_cliente = ".$this->cliente->getIdCliente()."
                                AND ot.tipo_orden_trabajo = 1 -- Instalación    
                            ORDER BY 
                                ot.numero DESC
                    ";
            $this->conexion->ejecutar($sentenciaSql);
            $contador = 0;
            $retorno = array();
            while ($fila = $this->conexion->obtenerObjeto()) {
                $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
                $retorno[$contador]['codigo'] = $fila->codigo;
                $retorno[$contador]['sucursal'] = $fila->sucursal;
                $retorno[$contador]['ordenador'] = $fila->ordenador;
                $retorno[$contador]['tercero'] = $fila->tercero;
                $retorno[$contador]['cliente'] = $fila->cliente;
                $retorno[$contador]['productoComposicion'] = $fila->producto;
                $retorno[$contador]['terceroSucursal'] = $fila->tercero_sucursal;
                $retorno[$contador]['idMunicipio'] = $fila->id_municipio;
                $retorno[$contador]['municipio'] = $fila->municipio;
                $retorno[$contador]['fechaInicial'] = $fila->fecha_inicial;
                $retorno[$contador]['fechaFinal'] = $fila->fecha_final;
                $retorno[$contador]['fecha'] = $fila->fecha;
                $retorno[$contador]['numero'] = $fila->numero;
                $retorno[$contador]['tipo'] = $fila->tipo;
                $retorno[$contador]['idOrdenador'] = $fila->id_ordenador;
                $retorno[$contador]['idCliente'] = $fila->id_cliente;
                $retorno[$contador]['numero'] = $fila->numero;
                $retorno[$contador]['idTipoDocumento'] = $fila->id_tipo_documento;
                $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
                $retorno[$contador]['productos'] = $this->consultarProductosServicios($fila->id_cliente_servicio);
                $contador++;
            }
        }  catch (Exception $e){
            $retorno["exito"] = 0;
            $retorno["mensaje"] = $e->getMessage();
        }
        return $retorno;
    } 
    function consultarProductosServicios($idClienteServicio){
        $conexionProductos = new \Conexion();
        $sentenciaSql = "
                        SELECT DISTINCT
                            csp.id_cliente_servicio_producto
                            , csp.id_cliente_servicio
                            , otc.id_orden_trabajo_cliente
                            , csp.id_producto
                            , csp.nota
                            , csp.secuencia
                            , csp.cantidad
                            , csp.valor_unita_con_impue
                            , csp.serial
                            , csp.estado
                            , csp.id_bodega
                            , b.bodega
                            , p.producto_serial
                            , p.valor_entrada
                            , p.valor_salida
                            , p.valor_entra_con_impue
                            , p.valor_salid_con_impue
                            , p.codigo as codigo_producto_compone
                            , p.producto as producto_compone
                            , COUNT(prc.id_producto) as cantidad_producto_compone
                            , cs.id_producto_composicion
                            , prc.codigo as codigo_producto_composicion
                            , prc.producto as producto_composicion
                            , um.unidad_medida
                            , s.id_tercero
                            , csp.id_esta_clie_serv_prod
                            , csp.id_orden_trabajo
                            ,cs.id_cliente
                        FROM
                            publics_services.cliente_servicio_producto csp
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = csp.id_bodega
                            INNER JOIN publics_services.cliente_servicio cs ON cs.id_cliente_servicio = csp.id_cliente_servicio
                            INNER JOIN facturacion_inventario.cliente AS c ON c.id_cliente = cs.id_cliente
                            INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_orden_trabajo_cliente = cs.id_orden_trabajo_cliente
                            INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = csp.id_producto
                            INNER JOIN publics_services.cliente_servicio_producto cspc ON cspc.id_cliente_servicio = cs.id_cliente_servicio
                            INNER JOIN facturacion_inventario.producto prc ON prc.id_producto = cs.id_producto_composicion
                            INNER JOIN facturacion_inventario.unidad_medida um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                        WHERE 
                            cs.id_cliente_servicio = $idClienteServicio
                        GROUP BY 
                               csp.id_cliente_servicio_producto, csp.id_cliente_servicio, csp.id_producto
                                , csp.nota, csp.secuencia, csp.cantidad, csp.valor_unita_con_impue, csp.serial, csp.estado
                                , p.codigo, p.producto, cs.id_producto_composicion, prc.codigo, prc.producto
                                ,otc.id_orden_trabajo_cliente,p.producto_serial,um.unidad_medida,b.bodega,s.id_tercero,p.valor_salida
                                ,p.valor_entra_con_impue,p.valor_salid_con_impue,p.valor_entrada,csp.id_esta_clie_serv_prod,
                                csp.id_transaccion,csp.id_orden_trabajo,cs.id_cliente
                        ORDER BY csp.id_cliente_servicio_producto
                ";
        $conexionProductos->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        
        while ($fila = $conexionProductos->obtenerObjeto()){
            $retorno[$contador]['idClienteServicioProducto'] = $fila->id_cliente_servicio_producto;
            $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
            $retorno[$contador]['idOrdenTrabajoCliente'] = $fila->id_orden_trabajo_cliente;
            $retorno[$contador]['idProducto'] = $fila->id_producto;
            $retorno[$contador]['productoSerial'] = $fila->producto_serial;
            $retorno[$contador]['nota'] = $fila->nota;
            $retorno[$contador]['secuencia'] = $fila->secuencia;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['valorUnitario'] = $fila->valor_unita_con_impue;
            $retorno[$contador]['serial'] = $fila->serial;
            $retorno[$contador]['estado'] = $fila->estado;
            $retorno[$contador]['idBodega'] = $fila->id_bodega;
            $retorno[$contador]['bodega'] = $fila->bodega;
            $retorno[$contador]['unidadMedida'] = $fila->unidad_medida;
            $retorno[$contador]['productoSerial'] = $fila->producto_serial;
            $retorno[$contador]['valorSalida'] = $fila->valor_salida;
            $retorno[$contador]['valorEntrada'] = $fila->valor_entrada;
            $retorno[$contador]['valorEntraConImpue'] = $fila->valor_entra_con_impue;
            $retorno[$contador]['valorSalidConImpue'] = $fila->valor_salid_con_impue;
            $retorno[$contador]['codigoProductoCompone'] = $fila->codigo_producto_compone;
            $retorno[$contador]['productoCompone'] = $fila->producto_compone;
            $retorno[$contador]['idProductoComposicion'] = $fila->id_producto_composicion;
            $retorno[$contador]['codigoProductoComposicion'] = $fila->codigo_producto_composicion;
            $retorno[$contador]['productoComposicion'] = $fila->producto_composicion;
            $retorno[$contador]['cantidadProductoCompone'] = $fila->cantidad_producto_compone;
            $retorno[$contador]['idTercero'] = $fila->id_tercero;
            $retorno[$contador]['idEstaClieServProd'] = $fila->id_esta_clie_serv_prod;
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $contador++;
        }
        return $retorno;
    }

    public function consultarServiciosValidos() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        /*----------------------------------------Orden Trabajo Instalación----------------------------------*/  
                        SELECT DISTINCT
                            0 AS id_cliente_servicio
                            ,ot.id_orden_trabajo
                            ,otc.id_orden_trabajo_cliente
                            ,otc.id_cliente
                            ,TO_CHAR(ot.fecha_inicio,'YYYY-mm-dd') AS fecha_inicial
                            ,TO_CHAR(ot.fecha_fin,'YYYY-mm-dd') AS fecha_final
                            , ot.numero    
                            , td.tipo_documento
                            , p.producto
                            , CONCAT_WS(' ', t.tercero, s.sucursal) as cliente
                            , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                            ,'Instalación' AS tipo
                            ,1 AS tipo_servicio
                            ,te.tercero AS empresa
                        FROM
                            publics_services.orden_trabajo_cliente otc
                            INNER JOIN  publics_services.orden_trabajo AS ot ON otc.id_orden_trabajo = ot.id_orden_trabajo
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = otc.id_producto_composicion
                            INNER JOIN contabilidad.tipo_documento td ON td.id_tipo_documento = ot.id_tipo_documento
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN nomina.empleado eo ON eo.id_empleado = o.id_empleado
                            INNER JOIN general.persona po ON po.id_persona = eo.id_persona
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                            INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                            INNER JOIN publics_services.estado_orden_trabajo AS eot ON eot.id_estado_orden_trabajo = ot.id_estado_orden_trabajo
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = otc.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero    
                            INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                            INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                        $this->condicion $this->whereAnd    
                            ot.tipo_orden_trabajo = 1
                            AND ot.estado = TRUE
                            AND otc.id_esta_orde_trab_clie = 2
                            AND otc.id_orden_trabajo_cliente IN (SELECT id_orden_trabajo_cliente FROM publics_services.orden_trabajo_producto)
                        UNION ALL
                        /*----------------------------------------Orden Trabajo Mantenimiento----------------------------------*/  
                        SELECT DISTINCT
                            cs.id_cliente_servicio	
                            , ot.id_orden_trabajo
                            , otc.id_orden_trabajo_cliente
                            ,otc.id_cliente
                            ,TO_CHAR(ot.fecha_inicio,'YYYY-mm-dd') AS fecha_inicial
                            ,TO_CHAR(ot.fecha_fin,'YYYY-mm-dd') AS fecha_final
                            , ot.numero
                            , td.tipo_documento
                            , p.producto
                            , CONCAT_WS(' ', t.tercero, s.sucursal) as cliente
                            , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                            ,'Mantenimiento' AS tipo
                            ,2 AS tipo_servicio
                            ,te.tercero AS empresa
                        FROM
                            publics_services.orden_trabajo_cliente otc
                            INNER JOIN  publics_services.orden_trabajo AS ot ON otc.id_orden_trabajo = ot.id_orden_trabajo
                            INNER JOIN publics_services.cliente_servicio AS cs ON cs.id_cliente_servicio = ot.id_cliente_servicio
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = otc.id_producto_composicion
                            INNER JOIN contabilidad.tipo_documento td ON td.id_tipo_documento = ot.id_tipo_documento
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN nomina.empleado eo ON eo.id_empleado = o.id_empleado
                            INNER JOIN general.persona po ON po.id_persona = eo.id_persona
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                            INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                            INNER JOIN publics_services.estado_orden_trabajo AS eot ON eot.id_estado_orden_trabajo = ot.id_estado_orden_trabajo
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = otc.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                            INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                        $this->condicion $this->whereAnd    
                            ot.tipo_orden_trabajo = 2 --Mantenimiento
                            AND ot.estado = TRUE
                            AND ot.id_estado_orden_trabajo = 2
                            AND cs.id_estado_cliente_servicio <> 2 -- Desinstalación
                            AND otc.id_orden_trabajo_cliente IN (SELECT id_orden_trabajo_cliente FROM publics_services.orden_trabajo_producto)
                        UNION ALL
                        /*----------------------------------------Orden Trabajo Desinstalación----------------------------------*/  
                        SELECT DISTINCT
                            cs.id_cliente_servicio
                            , ot.id_orden_trabajo
                            , otc.id_orden_trabajo_cliente
                            ,otc.id_cliente
                            ,TO_CHAR(ot.fecha_inicio,'YYYY-mm-dd') AS fecha_inicial
                            ,TO_CHAR(ot.fecha_fin,'YYYY-mm-dd') AS fecha_final
                            , ot.numero
                            , td.tipo_documento
                            , p.producto
                            , CONCAT_WS(' ', t.tercero, s.sucursal) as cliente
                            , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                            ,'Desinstalacion' AS tipo
                            ,3 AS tipo_servicio
                            ,te.tercero AS empresa
                        FROM
                            publics_services.orden_trabajo_cliente otc
                            INNER JOIN  publics_services.orden_trabajo AS ot ON otc.id_orden_trabajo = ot.id_orden_trabajo
                            INNER JOIN publics_services.cliente_servicio AS cs ON cs.id_cliente_servicio = ot.id_cliente_servicio
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = otc.id_producto_composicion
                            INNER JOIN contabilidad.tipo_documento td ON td.id_tipo_documento = ot.id_tipo_documento
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN nomina.empleado eo ON eo.id_empleado = o.id_empleado
                            INNER JOIN general.persona po ON po.id_persona = eo.id_persona
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                            INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                            INNER JOIN publics_services.estado_orden_trabajo AS eot ON eot.id_estado_orden_trabajo = ot.id_estado_orden_trabajo
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = otc.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                            INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                        $this->condicion $this->whereAnd    
                            ot.tipo_orden_trabajo = 3 --Desinstalación
                            AND ot.estado = TRUE
                            AND ot.id_estado_orden_trabajo = 2
                            AND cs.id_estado_cliente_servicio <> 2 -- Desinstalación    
                            --AND otc.id_orden_trabajo_cliente IN (SELECT id_orden_trabajo_cliente FROM publics_services.orden_trabajo_producto)

                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        while ($fila = $this->conexion->obtenerObjeto()) {

            $color = "";
            switch ($fila->tipo_servicio) {
                case 1://Instalación
                    $color = "#BDFFBB"; //VERDE
                    break;
                case 2://Mantenimiento
                    $color = "#FFE7BB"; //NARANJA
                    break;
                case 3://Desinstalación
                    $color = "#FFCECE"; //ROJO
                    break;
            }

            $retorno[$contador]['color'] = $color;
            $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['idOrdenTrabajoCliente'] = $fila->id_orden_trabajo_cliente;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['fechaInicial'] = $fila->fecha_inicial;
            $retorno[$contador]['fechaFinal'] = $fila->fecha_final;
            $retorno[$contador]['numero'] = $fila->numero;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $retorno[$contador]['producto'] = $fila->producto;
            $retorno[$contador]['cliente'] = $fila->cliente;
            $retorno[$contador]['tipo'] = $fila->tipo;
            $retorno[$contador]['empresa'] = $fila->empresa;
            $retorno[$contador]['tipoServicio'] = $fila->tipo_servicio;
            $retorno[$contador]['municipio'] = $fila->municipio;
            $contador++;
        }
        return $retorno;
    }

    function adicionarCancelado($idOrdenTrabajoProducto, $cantidadDevolver) {
        $sentenciaSql = "
                            INSERT INTO
                                publics_services.orde_trab_prod_canc
                                    SELECT
                                        *, $cantidadDevolver
                                    FROM
                                        publics_services.orden_trabajo_producto
                                    WHERE
                                        id_orden_trabajo_producto = $idOrdenTrabajoProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    function actualizarFechaFinal() {
        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio
                            SET
                                fecha_final = NOW()
                            WHERE
                                id_cliente_servicio = $this->idClienteServicio
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultarEstadoCancelado() {
        $sentenciaSql = "SELECT
                            estado_cliente_servicio
                            , id_estado_cliente_servicio
                        FROM
                            publics_services.estado_cliente_servicio 
                        WHERE
                            cancelado = TRUE
                ";

        $this->conexion->ejecutar($sentenciaSql);

        if ($this->conexion->obtenerNumeroRegistros() == 0) {
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'No hay ningún estado de producto del pedido asignado.';
            return $retorno;
        }

        if ($this->conexion->obtenerNumeroRegistros() > 1) {
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'Hay mas de un estado de tipo finalizado.';
            return $retorno;
        }

        $fila = $this->conexion->obtenerObjeto();

        return $fila->id_estado_cliente_servicio;
    }

    public function cancelarServicio() {

        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio
                            SET
                                id_estado_cliente_servicio = " . $this->idEstadoClienteServicio . "
                                , fecha_final = NOW()
                            WHERE
                                id_cliente_servicio = $this->idClienteServicio
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    function obtenerMaximo() {
        $sentenciaSql = "   
                            SELECT
                                MAX(id_cliente_servicio) AS maximo
                            FROM 
                                publics_services.cliente_servicio
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $numero = $fila->maximo;
        return $numero;
    }

    function actualizarEstado() {
        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio
                            SET
                                id_estado_cliente_servicio = " . $this->idEstadoClienteServicio . "
                            WHERE
                                id_cliente_servicio = " . $this->idClienteServicio;
        $this->conexion->ejecutar($sentenciaSql);
    }

    function reporteServiciosInstalados($idVendedor, $idProducto) {
        $this->obtenerCondicion();
        if ($idVendedor != null && $idVendedor != "null" && $idVendedor != " " && $idVendedor != "") {
            $this->condicion .= $this->whereAnd . " ven.id_vendedor = " . $idVendedor;
            $this->whereAnd = " AND ";
        }
        if ($idProducto != null && $idProducto != "null" && $idProducto != " " && $idProducto != "") {
            $this->condicion .= $this->whereAnd . " p.id_producto IN (" . $idProducto . ")";
            $this->whereAnd = " AND ";
        }
        $sentenciaSql = "
            SELECT
                  p.producto
                , p.id_producto  
                , cs.valor
                , CONCAT_WS(' ', t.tercero, ' - ', s.sucursal) as cliente  
                , ot.id_orden_trabajo
                , td.tipo_documento
                , otc.id_orden_trabajo_cliente
                , otc.id_cliente
                , TO_CHAR(ot.fecha_inicio,'YYYY-mm-dd') AS fecha_inicial
                , TO_CHAR(ot.fecha_fin,'YYYY-mm-dd') AS fecha_final
                , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                , CONCAT_WS
                    (
                          ' '
                        , po.primer_nombre
                        , po.segundo_nombre
                        , po.primer_apellido
                        , po.segundo_apellido
                    ) as vendedor
                , ot.id_orden_trabajo 
                , cs.id_cliente_servicio
                , otc.id_orden_trabajo_cliente
                , ecs.estado_cliente_servicio
                , te.tercero AS empresa
            FROM
                publics_services.orden_trabajo_cliente otc
                INNER JOIN facturacion_inventario.pedido AS ped ON ped.id_pedido = otc.id_pedido
                INNER JOIN facturacion_inventario.vendedor AS ven ON ven.id_vendedor = ped.id_vendedor
                INNER JOIN  publics_services.orden_trabajo AS ot ON otc.id_orden_trabajo = ot.id_orden_trabajo
                INNER JOIN publics_services.cliente_servicio AS cs ON cs.id_orden_trabajo_cliente = otc.id_orden_trabajo_cliente
                INNER JOIN publics_services.estado_cliente_servicio AS ecs ON ecs.id_estado_cliente_servicio = cs.id_estado_cliente_servicio
                INNER JOIN facturacion_inventario.producto p ON p.id_producto = otc.id_producto_composicion
                INNER JOIN contabilidad.tipo_documento td ON td.id_tipo_documento = ot.id_tipo_documento
                INNER JOIN general.persona po ON po.id_persona = ven.id_persona
                INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                INNER JOIN publics_services.estado_orden_trabajo AS eot ON eot.id_estado_orden_trabajo = ot.id_estado_orden_trabajo AND eot.id_estado_orden_trabajo = 3
                INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = otc.id_cliente
                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero  
                INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero

            $this->condicion AND p.producto_servicio = FALSE
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["idCliente"] = $fila->id_cliente;
            $retorno[$contador]["valor"] = $fila->valor;
            $retorno[$contador]["cliente"] = $fila->cliente;
            $retorno[$contador]["idOrdenTrabajo"] = $fila->id_orden_trabajo;
            $retorno[$contador]["tipoServicio"] = $fila->tipo_documento;
            $retorno[$contador]["fechaInicial"] = $fila->fecha_inicial;
            $retorno[$contador]["fechaFinal"] = $fila->fecha_final;
            $retorno[$contador]["municipio"] = $fila->municipio;
            $retorno[$contador]["vendedor"] = $fila->vendedor;
            $retorno[$contador]["empresa"] = $fila->empresa;
            $retorno[$contador]["idOrdenTrabajo"] = $fila->id_orden_trabajo;
            $retorno[$contador]["idClienteServicio"] = $fila->id_cliente_servicio;
            $retorno[$contador]["idOrdenTrabajoCliente"] = $fila->id_orden_trabajo_cliente;
            $retorno[$contador]["estadoClienteServicio"] = $fila->estado_cliente_servicio;

            $contador++;
        }
        return $retorno;
    }

    function obtenerCondicion() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';

        if ($this->idClienteServicio != '' && $this->idClienteServicio != 'null' && $this->idClienteServicio != null && $this->idClienteServicio != 'NULL') {
            $this->condicion .= $this->whereAnd . ' cs.id_cliente_servicio IN(' . $this->idClienteServicio . ')';
            $this->whereAnd = ' AND ';
        }
        if ($this->ordenTrabajoCliente->getIdOrdenTrabajoCliente() != '' && $this->ordenTrabajoCliente->getIdOrdenTrabajoCliente() != 'null' && $this->ordenTrabajoCliente->getIdOrdenTrabajoCliente() != null) {
            $this->condicion .= $this->whereAnd . ' otc.id_orden_trabajo_cliente = ' . $this->ordenTrabajoCliente->getIdOrdenTrabajoCliente();
            $this->whereAnd = ' AND ';
        }
        if ($this->fechaInicial != '' && $this->fechaInicial != 'null' && $this->fechaInicial != null  && $this->fechaInicial != "NULL") {
            if(is_array($this->fechaInicial)) {
                if ($this->fechaInicial['inicio'] != '' && $this->fechaInicial['fin'] == '') {
                    $this->condicion .= $this->whereAnd . " ot.fecha >= '" . $this->fechaInicial['inicio'] . "'";
                    $this->whereAnd = ' AND ';
                }
                if ($this->fechaInicial['inicio'] == '' && $this->fechaInicial['fin'] != '') {
                    $this->condicion .= $this->whereAnd . " ot.fecha <= '" . $this->fechaInicial['fin'] . "'";
                    $this->whereAnd = ' AND ';
                }
                if ($this->fechaInicial['inicio'] != '' && $this->fechaInicial['fin'] != '') {
                    $this->condicion .= $this->whereAnd . " ot.fecha BETWEEN '" . $this->fechaInicial['inicio'] . "' AND '" . $this->fechaInicial['fin'] . "'";
                    $this->whereAnd = ' AND ';
                }
            }  else {
                if($this->fechaFinal != '' && $this->fechaFinal != 'null' && $this->fechaFinal != null && $this->fechaFinal != "NULL"){
                    $this->condicion .= $this->whereAnd . " CAST(ot.fecha AS DATE) >= '" . $this->fechaInicial . "'";
                    $this->whereAnd = ' AND ';
                }else{
                    $this->condicion .= $this->whereAnd . " CAST(ot.fecha AS DATE) = '" . $this->fechaInicial . "'";
                    $this->whereAnd = ' AND ';
                }
            }
        }
        if ($this->fechaFinal != '' && $this->fechaFinal != 'null' && $this->fechaFinal != null && $this->fechaFinal != "NULL") {

            if (is_array($this->fechaFinal)) {
                if ($this->fechaFinal['inicio'] != '' && $this->fechaFinal['fin'] == '') {
                    $this->condicion .= $this->whereAnd . " ot.fecha >= '" . $this->fechaFinal['inicio'] . "'";
                    $this->whereAnd = ' AND ';
                }
                if ($this->fechaFinal['inicio'] == '' && $this->fechaFinal['fin'] != '') {
                    $this->condicion .= $this->whereAnd . " ot.fecha <= '" . $this->fechaFinal['fin'] . "'";
                    $this->whereAnd = ' AND ';
                }
                if ($this->fechaFinal['inicio'] != '' && $this->fechaFinal['fin'] != '') {
                    $this->condicion .= $this->whereAnd . " ot.fecha BETWEEN '" . $this->fechaFinal['inicio'] . "' AND '" . $this->fechaFinal['fin'] . "'";
                    $this->whereAnd = ' AND ';
                }
            }else{
                if($this->fechaInicial != '' && $this->fechaInicial != 'null' && $this->fechaInicial != null  && $this->fechaInicial != "NULL"){
                    $this->condicion .= $this->whereAnd . " CAST(ot.fecha AS DATE) <= '" . $this->fechaFinal . "'";
                    $this->whereAnd = ' AND ';
                }else{
                    $this->condicion .= $this->whereAnd . " CAST(ot.fecha AS DATE) = '" . $this->fechaFinal . "'";
                    $this->whereAnd = ' AND ';
                }
            }
        }
        if ($this->idEstadoClienteServicio != '' && $this->idEstadoClienteServicio != 'null' && $this->idEstadoClienteServicio != null) {
            $this->condicion .= $this->whereAnd . ' cs.id_estado_cliente_servicio IN(' . $this->idEstadoClienteServicio . ')';
            $this->whereAnd = ' AND ';
        }
        if ($this->ordenTrabajoCliente->getOrdenTrabajo()->getNumero() != '' && $this->ordenTrabajoCliente->getOrdenTrabajo()->getNumero() != 'null' && $this->ordenTrabajoCliente->getOrdenTrabajo()->getNumero() != null) {
            $this->condicion .= $this->whereAnd . ' ot.numero = ' . $this->ordenTrabajoCliente->getOrdenTrabajo()->getNumero();
            $this->whereAnd = ' AND ';
        }


        if ($this->ordenTrabajoCliente->getOrdenTrabajo()->getTipoOrdenTrabajo() != '' && $this->ordenTrabajoCliente->getOrdenTrabajo()->getTipoOrdenTrabajo() != 'null' && $this->ordenTrabajoCliente->getOrdenTrabajo()->getTipoOrdenTrabajo() != null) {
            $this->condicion .= $this->whereAnd . ' ot.tipo_orden_trabajo IN (' . $this->ordenTrabajoCliente->getOrdenTrabajo()->getTipoOrdenTrabajo() . ")";
            $this->whereAnd = ' AND ';
        }

        if ($this->ordenTrabajoCliente->getOrdenTrabajo()->getIdMunicipio() != '' && $this->ordenTrabajoCliente->getOrdenTrabajo()->getIdMunicipio() != 'null' && $this->ordenTrabajoCliente->getOrdenTrabajo()->getIdMunicipio() != null) {
            $this->condicion .= $this->whereAnd . ' ot.id_municipio IN(' . $this->ordenTrabajoCliente->getOrdenTrabajo()->getIdMunicipio() . ')';
            $this->whereAnd = ' AND ';
        }
        if ($this->cliente->getIdCliente() != '' && $this->cliente->getIdCliente() != 'null' && $this->cliente->getIdCliente() != null) {
            $this->condicion .= $this->whereAnd . ' otc.id_cliente = ' . $this->cliente->getIdCliente();
            $this->whereAnd = ' AND ';
        }
    }

    public function subirArchivo($archivo, $usuario) {
        $retorno = array("exito" => 1, "mensaje" => "el archivo se subio correctamente.", "ruta" => "", "nombre" => "", "extension" => "");
        $fecha = date("YmdHis");
        foreach ($archivo as $key) {
            $retorno["nombre"] = $key['name'];
            $temporal = $key['tmp_name'];
            $retorno["extension"] = substr(strrchr($retorno["nombre"], "."), 1);
            $retorno["ruta"] = '../../archivos/PublicServices/temporal/';
            if (!file_exists($retorno["ruta"])) {
                mkdir($retorno["ruta"], 0777, true);
            }
            $retorno["ruta"] .= $usuario . '-temporal-' . $fecha . '.' . $retorno["extension"];
            if (!move_uploaded_file($temporal, $retorno["ruta"])) {
                $retorno['exito'] = 0;
                $retorno['mensaje'] = 'Ocurrio un error subiendo el archivo.';
            }
        }
        return $retorno;
    }

    public function renombrarArchivo($rutaAnterior, $rutaNueva) {
        $retorno = array("exito" => 1, "mensaje" => "El archivo se renombro correctamente.");
        if (rename($rutaAnterior, $rutaNueva) == false) {
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'Ocurrio un error renombrando el archivo.';
        }
        return $retorno;
    }

}
