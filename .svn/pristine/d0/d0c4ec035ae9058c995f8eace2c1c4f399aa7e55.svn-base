<?php

namespace modelo;

session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../entidad/Pedido.php';
require_once '../entidad/Cliente.php';
require_once '../entidad/Vendedor.php';

class Pedido {

    private $idPedido;
    private $cliente;
    private $idPedidoEstado;
    private $vendedor;
    private $idZona;
    private $fecha;
    private $nota;
    private $tipoPedido;
    private $idClienteServicio;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaModificacion;
    private $fechaCreacion;
    public $conexion;
    private $condicion;
    private $whereAnd;

    function __construct(\entidad\Pedido $pedido) {
        $this->idPedido = $pedido->getIdPedido();
        $this->cliente = $pedido->getCliente() != "" ? $pedido->getCliente() : new \entidad\Cliente();
        $this->vendedor = $pedido->getVendedor() != "" ? $pedido->getVendedor() : new \entidad\Vendedor();
        $this->idZona = $pedido->getIdZona();
        $this->idPedidoEstado = $pedido->getIdPedidoEstado();
        $this->fecha = $pedido->getFecha();
        $this->nota = $pedido->getNota();
        $this->tipoPedido = $pedido->getTipoPedido();
        $this->idClienteServicio = $pedido->getIdClienteServicio();
        $this->idUsuarioCreacion = $pedido->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $pedido->getIdUsuarioModificacion();
        $this->fechaCreacion = $pedido->getFechaCreacion();
        $this->fechaModificacion = $pedido->getFechaModificacion();

        $this->conexion = new \Conexion();
    }

    public function adicionar() {
        $sentenciaSql = "INSERT INTO
                            facturacion_inventario.pedido(
                                id_cliente
                                , id_pedido_estado
                                , id_vendedor
                                , id_zona
                                , fecha
                                , nota
                                , tipo_pedido
                                , id_cliente_servicio
                                , id_usuario_creacion
                                , id_usuario_modificacion
                                , fecha_creacion
                                , fecha_modificacion
                            )VALUES(
                                " . $this->cliente->getIdCliente() . "
                                , $this->idPedidoEstado
                                , " . $this->vendedor->getIdVendedor() . " 
                                , $this->idZona
                                , '$this->fecha'
                                , '$this->nota'
                                , $this->tipoPedido
                                , $this->idClienteServicio
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                            )
                 ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function modificar() {
        $sentenciaSql = "UPDATE 
                            facturacion_inventario.pedido
                        SET
                            id_cliente = " . $this->cliente->getIdCliente() . "
                            , id_pedido_estado = $this->idPedidoEstado
                            , id_vendedor = " . $this->vendedor->getIdVendedor() . " 
                            , id_zona = $this->idZona
                            , fecha = '$this->fecha'
                            , nota = '$this->nota'
                            , tipo_pedido = $this->tipoPedido
                            , id_cliente_servicio = $this->idClienteServicio
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                        WHERE
                            id_pedido = $this->idPedido
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function inactivar() {
        $sentenciaSql = "UPDATE 
                            facturacion_inventario.pedido
                        SET
                            id_pedido_estado = $this->idPedidoEstado
                        WHERE
                            id_pedido = $this->idPedido
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            p.id_pedido
                            , p.id_cliente
                            , p.id_vendedor
                            , p.id_zona
                            , p.fecha
                            , p.nota
                            , p.tipo_pedido
                            , p.fecha_creacion
                            , s.sucursal
                            , p.id_pedido_estado
                            , ep.estado_pedido
                            , CONCAT_WS(' ', per.primer_nombre, per.segundo_nombre, per.primer_apellido, per.segundo_apellido) AS usuario_creacion
                            , u.id_usuario
                            , (SELECT SUM(pp.cantidad * pp.valor_unita_con_impue) FROM facturacion_inventario.pedido_producto pp WHERE pp.id_pedido = p.id_pedido GROUP BY pp.id_pedido) as valor_total
                            , CONCAT_WS(' ', pv.primer_nombre, pv.segundo_nombre, pv.primer_apellido, pv.segundo_apellido) as nombre_vendedor
                            , z.zona
                            , t.tercero
                            , t.nit
                            , pv.numero_identificacion as numero_identificacion_vendedor
                            , CASE 
				WHEN p.tipo_pedido = 1 THEN 'Instalación'
			        WHEN p.tipo_pedido = 2 THEN 'Mantenimiento'
			        ELSE 'Inválido'
			    END AS tipo
                            , p.tipo_pedido
                        FROM
                            facturacion_inventario.pedido p
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = p.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            INNER JOIN facturacion_inventario.estado_pedido ep ON ep.id_estado_pedido = p.id_pedido_estado
                            INNER JOIN seguridad.usuario u ON u.id_usuario = p.id_usuario_creacion
                            INNER JOIN general.persona per ON per.id_persona = u.id_persona
                            INNER JOIN facturacion_inventario.zona z ON z.id_zona = p.id_zona
                            INNER JOIN facturacion_inventario.vendedor v ON v.id_vendedor = p.id_vendedor
                            INNER JOIN general.persona pv ON pv.id_persona = v.id_persona
                            
                        $this->condicion
                ";

        $this->conexion->ejecutar($sentenciaSql);

        $contador = 0;
        $retorno = array();

        while ($fila = $this->conexion->obtenerObjeto()) {
            $fecha = new \DateTime($fila->fecha);

            $retorno[$contador]['idPedido'] = $fila->id_pedido;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['idVendedor'] = $fila->id_vendedor;
            $retorno[$contador]['idZona'] = $fila->id_zona;
            $retorno[$contador]['zona'] = $fila->zona;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['nit'] = $fila->nit;
            $retorno[$contador]['numeroIdentificacionVendedor'] = $fila->numero_identificacion_vendedor;
            $retorno[$contador]['nombreVendedor'] = $fila->nombre_vendedor;
            $retorno[$contador]['fecha'] = $fecha->format('Y-m-d');
            ;
            $retorno[$contador]['fechaCreacion'] = $fila->fecha_creacion;
            $retorno[$contador]['notaPedido'] = $fila->nota;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['idPedidoEstado'] = $fila->id_pedido_estado;
            $retorno[$contador]['pedidoEstado'] = $fila->estado_pedido;
            $retorno[$contador]['idUsuarioCreacion'] = $fila->id_usuario_creacion;
            $retorno[$contador]['usuarioCreacion'] = $fila->usuario_creacion;
            $retorno[$contador]['valorTotal'] = $fila->valor_total;
            $retorno[$contador]['tipoPedido'] = $fila->tipo_pedido;
            $retorno[$contador]['tipo'] = $fila->tipo;

            $contador++;
        }

        return $retorno;
    }

    function consultarReportePedidoCliente($tieneOrdenTrabajo) {
        $this->obtenerCondicion();
        $campos = "  p.id_pedido
                , p.id_cliente
                , p.id_vendedor
                , p.id_zona
                , p.fecha
                , p.nota
                , p.tipo_pedido
                , p.fecha_creacion
                , s.sucursal
                , p.id_pedido_estado
                , ep.estado_pedido
                , CONCAT_WS(' ', per.primer_nombre, per.segundo_nombre, per.primer_apellido, per.segundo_apellido) AS usuario_creacion
                , u.id_usuario
                , (SELECT SUM(pp.cantidad * pp.valor_unita_con_impue) FROM facturacion_inventario.pedido_producto pp WHERE pp.id_pedido = p.id_pedido GROUP BY pp.id_pedido) as valor_total
                , CONCAT_WS(' ', pv.primer_nombre, pv.segundo_nombre, pv.primer_apellido, pv.segundo_apellido) as nombre_vendedor
                , CONCAT_WS(' - ',  z.zona, r.regional) as zona_regional
                , t.tercero
                , t.nit
                , pv.numero_identificacion as numero_identificacion_vendedor
                , CASE 
                    WHEN p.tipo_pedido = 1 THEN 'Instalación'
                    WHEN p.tipo_pedido = 2 THEN 'Mantenimiento'
                    ELSE 'Inválido'
                    END AS tipo
                , p.tipo_pedido
                , pro.producto AS servicio
                ";
        if ($tieneOrdenTrabajo != 'null' && $tieneOrdenTrabajo != null && $tieneOrdenTrabajo != 'false' && $tieneOrdenTrabajo == 'true') {
            $campos .= "
                , otc.id_orden_trabajo_cliente
                , ot.id_orden_trabajo";
            $whereSentencia = "
                INNER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_pedido = p.id_pedido
                INNER JOIN publics_services.orden_trabajo AS ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                $this->condicion    
                ORDER BY p.id_pedido ASC
            ";
        } else {
            if ($tieneOrdenTrabajo != 'null' && $tieneOrdenTrabajo != null && $tieneOrdenTrabajo != "true" && $tieneOrdenTrabajo != "false") {
                $campos .= "
                , otc.id_orden_trabajo_cliente
                , ot.id_orden_trabajo";
                $whereSentencia = "
                LEFT OUTER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_pedido = p.id_pedido
                LEFT OUTER JOIN publics_services.orden_trabajo AS ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                $this->condicion    
                ORDER BY p.id_pedido ASC
            ";
            } else {
                if($tieneOrdenTrabajo != 'null' && $tieneOrdenTrabajo != null && $tieneOrdenTrabajo != 'true' && $tieneOrdenTrabajo == 'false') {
                    $whereSentencia = "
                        LEFT OUTER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_pedido = p.id_pedido
                        LEFT OUTER JOIN publics_services.orden_trabajo AS ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                        $this->condicion $this->whereAnd ot.id_orden_trabajo IS NULL
                        ORDER BY p.id_pedido ASC
                    ";
                }
            }
        }
        if ($whereSentencia != '' && $whereSentencia != 'null' && $whereSentencia != null) {
            
        } else {
            $campos .= "
                , otc.id_orden_trabajo_cliente
                , ot.id_orden_trabajo";
                $whereSentencia = "
                LEFT OUTER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_pedido = p.id_pedido
                LEFT OUTER JOIN publics_services.orden_trabajo AS ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                $this->condicion    
                ORDER BY p.id_pedido ASC";
        }
        $sentenciaSql = "
            SELECT
                $campos
            FROM
                facturacion_inventario.pedido p
                INNER JOIN facturacion_inventario.pedido_producto AS pp ON pp.id_pedido = p.id_pedido
                INNER JOIN facturacion_inventario.producto AS pro ON pro.id_producto = pp.id_producto
                INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = p.id_cliente
                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                INNER JOIN facturacion_inventario.estado_pedido ep ON ep.id_estado_pedido = p.id_pedido_estado
                INNER JOIN seguridad.usuario u ON u.id_usuario = p.id_usuario_creacion
                INNER JOIN general.persona per ON per.id_persona = u.id_persona
                INNER JOIN facturacion_inventario.zona z ON z.id_zona = p.id_zona
                INNER JOIN facturacion_inventario.regional r ON r.id_regional = z.id_regional
                INNER JOIN facturacion_inventario.vendedor v ON v.id_vendedor = p.id_vendedor
                INNER JOIN general.persona pv ON pv.id_persona = v.id_persona
                $whereSentencia
                ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $fecha = new \DateTime($fila->fecha);
            $retorno[$contador]['idPedido'] = $fila->id_pedido;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['idVendedor'] = $fila->id_vendedor;
            $retorno[$contador]['idZona'] = $fila->id_zona;
            $retorno[$contador]['zona'] = $fila->zona_regional;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['nit'] = $fila->nit;
            $retorno[$contador]['numeroIdentificacionVendedor'] = $fila->numero_identificacion_vendedor;
            $retorno[$contador]['nombreVendedor'] = $fila->nombre_vendedor;
            $retorno[$contador]['fecha'] = $fecha->format('Y-m-d');
            ;
            $retorno[$contador]['fechaCreacion'] = $fila->fecha_creacion;
            $retorno[$contador]['notaPedido'] = $fila->nota;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['idPedidoEstado'] = $fila->id_pedido_estado;
            $retorno[$contador]['pedidoEstado'] = $fila->estado_pedido;
            $retorno[$contador]['idUsuarioCreacion'] = $fila->id_usuario_creacion;
            $retorno[$contador]['usuarioCreacion'] = $fila->usuario_creacion;
            $retorno[$contador]['valorTotal'] = $fila->valor_total;
            $retorno[$contador]['tipoPedido'] = $fila->tipo_pedido;
            $retorno[$contador]['tipo'] = $fila->tipo;
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['servicio'] = $fila->servicio;

            $contador++;
        }

        return $retorno;
    }

    public function consultarPedidosOrdenTrabajo() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            p.id_pedido
                            , p.id_cliente
                            , p.id_vendedor
                            , p.id_zona
                            , p.fecha
                            , p.nota
                            , p.fecha_creacion
                            , s.sucursal
                            , p.id_pedido_estado
                            , ep.estado_pedido
                            , CONCAT_WS(' ', per.primer_nombre, per.segundo_nombre, per.primer_apellido, per.segundo_apellido) AS usuario_creacion
                            , u.id_usuario
                            , (SELECT SUM(pp.cantidad * pp.valor_unita_con_impue) FROM facturacion_inventario.pedido_producto pp WHERE pp.id_pedido = p.id_pedido GROUP BY pp.id_pedido) as valor_total
                            , CONCAT_WS(' ', pv.primer_nombre, pv.segundo_nombre, pv.primer_apellido, pv.segundo_apellido) as nombre_vendedor
                            , z.zona
                            , CONCAT_WS(' - ',  z.zona, r.regional) as zona_regional
                            , t.tercero
                            , t.nit
                            , pv.numero_identificacion as numero_identificacion_vendedor
                            , CASE 
				WHEN p.tipo_pedido = 1 THEN 'Instalación'
			        WHEN p.tipo_pedido = 2 THEN 'Mantenimiento'
			        ELSE 'Inválido'
			    END AS tipo
                            , p.tipo_pedido
                            , te.tercero AS empresa
                        FROM
                            facturacion_inventario.pedido p
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = p.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
			    INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
			    INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                            INNER JOIN facturacion_inventario.estado_pedido ep ON ep.id_estado_pedido = p.id_pedido_estado
                            INNER JOIN seguridad.usuario u ON u.id_usuario = p.id_usuario_creacion
                            INNER JOIN general.persona per ON per.id_persona = u.id_persona
                            INNER JOIN facturacion_inventario.zona z ON z.id_zona = p.id_zona
                            INNER JOIN facturacion_inventario.regional r ON r.id_regional = z.id_regional
                            INNER JOIN facturacion_inventario.vendedor v ON v.id_vendedor = p.id_vendedor
                            INNER JOIN general.persona pv ON pv.id_persona = v.id_persona
                        $this->condicion
                        $this->whereAnd
                        p.id_pedido NOT IN (SELECT DISTINCT COALESCE(id_pedido,0) FROM publics_services.orden_trabajo_cliente WHERE estado = true)    
                ";

        $this->conexion->ejecutar($sentenciaSql);

        $contador = 0;
        $retorno = array();

        while ($fila = $this->conexion->obtenerObjeto()) {
            $fecha = new \DateTime($fila->fecha);

            $retorno[$contador]['idPedido'] = $fila->id_pedido;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['idVendedor'] = $fila->id_vendedor;
            $retorno[$contador]['idZona'] = $fila->id_zona;
            $retorno[$contador]['zona'] = $fila->zona;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['nit'] = $fila->nit;
            $retorno[$contador]['numeroIdentificacionVendedor'] = $fila->numero_identificacion_vendedor;
            $retorno[$contador]['nombreVendedor'] = $fila->nombre_vendedor;
            $retorno[$contador]['fecha'] = $fecha->format('Y-m-d');
            $retorno[$contador]['empresa'] = $fila->empresa;
            $retorno[$contador]['fechaCreacion'] = $fila->fecha_creacion;
            $retorno[$contador]['notaPedido'] = $fila->nota;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['idPedidoEstado'] = $fila->id_pedido_estado;
            $retorno[$contador]['pedidoEstado'] = $fila->estado_pedido;
            $retorno[$contador]['idUsuarioCreacion'] = $fila->id_usuario_creacion;
            $retorno[$contador]['usuarioCreacion'] = $fila->usuario_creacion;
            $retorno[$contador]['valorTotal'] = $fila->valor_total;
            $retorno[$contador]['zonaRegional'] = $fila->zona_regional;
            $retorno[$contador]['tipoPedido'] = $fila->tipo_pedido;
            $retorno[$contador]['tipo'] = $fila->tipo;

            $contador++;
        }

        return $retorno;
    }

    function obtenerMaximo() {
        $sentenciaSql = "SELECT
                            COALESCE(MAX(id_pedido), 0) AS maximo
                        FROM 
                            facturacion_inventario.pedido
                ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }

    public function consultarEstadoFinalizado() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            COALESCE(COUNT(*),0) as validacion
                        FROM
                            facturacion_inventario.pedido p
                            INNER JOIN facturacion_inventario.estado_pedido ep ON ep.id_estado_pedido = p.id_pedido_estado
                            
                        $this->condicion
                        $this->whereAnd ep.finalizado = TRUE
                        GROUP BY p.id_pedido_estado, p.id_pedido
                ";

        $this->conexion->ejecutar($sentenciaSql);

        if ($this->conexion->obtenerNumeroRegistros() == 0) {
            $return['exito'] = 0;
            $return['mensaje'] = 'No hay ningún estado de producto del pedido asignado.';
        }

        if ($this->conexion->obtenerNumeroRegistros() > 1) {
            $return['exito'] = 0;
            $return['mensaje'] = 'Hay mas de un estado de tipo finalizado.';
        }

        $fila = $this->conexion->obtenerObjeto();

        return $fila->validacion;
    }

    function obtenerCondicion() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';

        if ($this->idPedido != '' && $this->idPedido != 'null' && $this->idPedido != null) {
            $this->condicion .= $this->whereAnd . ' p.id_pedido = ' . $this->idPedido;
            $this->whereAnd = ' AND ';
        }
        if ($this->cliente->getIdCliente() != '' && $this->cliente->getIdCliente() != 'null' && $this->cliente->getIdCliente() != null) {
            $this->condicion .= $this->whereAnd . ' c.id_cliente = ' . $this->cliente->getIdCliente();
            $this->whereAnd = ' AND ';
        }
        
        if ($this->cliente->getIdEmpresa() != '' && $this->cliente->getIdEmpresa() != 'null' && $this->cliente->getIdEmpresa() != null) {
            $this->condicion .= $this->whereAnd . ' t.id_empresa = ' . $this->cliente->getIdEmpresa();
            $this->whereAnd = ' AND ';
        }
        
        if ($this->cliente->getSucursal() != '' && $this->cliente->getSucursal() != 'null' && $this->cliente->getSucursal() != null) {
            if ($this->cliente->getSucursal()->getIdSucursal() != '' && $this->cliente->getSucursal()->getIdSucursal() != 'null' && $this->cliente->getSucursal()->getIdSucursal() != null) {
                $this->condicion .= $this->whereAnd . ' s.id_sucursal = ' . $this->cliente->getSucursal()->getIdSucursal();
                $this->whereAnd = ' AND ';
            }
            if ($this->cliente->getSucursal()->getSucursal() != '' && $this->cliente->getSucursal()->getSucursal() != 'null' && $this->cliente->getSucursal()->getSucursal() != null) {
                $this->condicion .= $this->whereAnd . " s.sucursal ILIKE '%" . $this->cliente->getSucursal()->getSucursal() . "%'";
                $this->whereAnd = ' AND ';
            }
        }

        if ($this->fecha != '' && $this->fecha != 'null' && $this->fecha != null) {
            if ($this->fecha['inicio'] != '' && $this->fecha['fin'] == '') {// FECHA INICIO EN ADELANTE
                $this->condicion .= $this->whereAnd . " p.fecha >= '" . $this->fecha['inicio'] . "'";
                $this->whereAnd = ' AND ';
            }
            if ($this->fecha['inicio'] == '' && $this->fecha['fin'] != '') {// FECHA INICIO EN ADELANTE
                $this->condicion .= $this->whereAnd . " p.fecha <= '" . $this->fecha['fin'] . "'";
                $this->whereAnd = ' AND ';
            }
            if ($this->fecha['inicio'] != '' && $this->fecha['fin'] != '') {// FECHA INICIO EN ADELANTE
                $this->condicion .= $this->whereAnd . " p.fecha BETWEEN '" . $this->fecha['inicio'] . "' AND '" . $this->fecha['fin'] . "'";
                $this->whereAnd = ' AND ';
            }
        }
        if ($this->idPedidoEstado != '' && $this->idPedidoEstado != 'null' && $this->idPedidoEstado != null) {
            $this->condicion .= $this->whereAnd . ' p.id_pedido_estado IN (' . $this->idPedidoEstado . ')';
            $this->whereAnd = ' AND ';
        }

        if ($this->vendedor->getIdVendedor() != '' && $this->vendedor->getIdVendedor() != 'null' && $this->vendedor->getIdVendedor() != null) {
            $this->condicion .= $this->whereAnd . ' p.id_vendedor = ' . $this->vendedor->getIdVendedor();
            $this->whereAnd = ' AND ';
        }

        if ($this->idZona != '' && $this->idZona != 'null' && $this->idZona != null) {
            $this->condicion .= $this->whereAnd . ' p.id_zona IN(' . $this->idZona . ')';
            $this->whereAnd = ' AND ';
        }

        if ($this->tipoPedido != '' && $this->tipoPedido != 'null' && $this->tipoPedido != null) {
            $this->condicion .= $this->whereAnd . ' p.tipo_pedido IN(' . $this->tipoPedido . ')';
            $this->whereAnd = ' AND ';
        }
    }

}
