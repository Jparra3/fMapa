<?php

namespace modelo;

session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../../Seguridad/entidad/Sucursal.php';

class Cliente {

    private $idCliente;
    private $sucursal;
    private $idClienteEstado;
    private $idZona;
    private $latitud;
    private $longitud;
    private $idListaPrecio;
    private $idCondicionPago;
    private $idEmpresaUnidadNegocio;
    private $nit;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $tipoClienteProveedor;
    private $idEmpresa;
    
    public $conexion;
    private $condicion;
    private $whereAnd;
    private $estadoSucursal;

    function __construct(\entidad\Cliente $cliente) {
        $this->idCliente = $cliente->getIdCliente();
        $this->sucursal = $cliente->getSucursal() != "" ? $cliente->getSucursal() : new \entidad\Sucursal();
        $this->idClienteEstado = $cliente->getIdClienteEstado();
        $this->nit = $cliente->getNit();
        $this->idZona = $cliente->getIdZona();
        $this->latitud = $cliente->getLatitud();
        $this->longitud = $cliente->getLongitud();
        $this->idListaPrecio = $cliente->getIdListaPrecio();
        $this->idCondicionPago = $cliente->getIdCondicionPago();
        $this->idEmpresaUnidadNegocio = $cliente->getIdEmpresaUnidadNegocio();
        $this->estadoSucursal = $cliente->getEstadoSucursal();
        $this->estado = $cliente->getEstado();
        $this->fechaCreacion = $cliente->getFechaCreacion();
        $this->fechaModificacion = $cliente->getFechaModificacion();
        $this->idUsuarioCreacion = $cliente->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $cliente->getIdUsuarioModificacion();
        $this->tipoClienteProveedor = $cliente->getTipoClienteProveedor();
        $this->idEmpresa = $cliente->getIdEmpresa();

        $this->conexion = new \Conexion();
    }

    public function adicionar() {
         $sentenciaSql = "
            INSERT INTO
                facturacion_inventario.cliente
                (
                      id_sucursal
                    , id_cliente_estado
                    , id_zona
                    , latitud
                    , longitud
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES
                (
                      " . $this->sucursal->getIdSucursal() . "
                    , $this->idClienteEstado
                    , $this->idZona
                    , '$this->latitud'
                    , '$this->longitud'    
                    , NOW()
                    , NOW()
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }

    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            id_cliente_proveedor
                            ,id_cliente
                            , id_tercero
                            , sucursal
                            , nit
                            , tercero
                            , id_zona
                            , tercero_telefono
                            , tercero_direccion
                            , tercero_sucursal
                            , estado
                            , id_municipio
                            , tipo
                            , empresa
                            FROM(

                                    SELECT
                                          c.id_cliente AS id_cliente_proveedor
                                        , c.id_cliente AS id_cliente
                                        , t.id_tercero
                                        , s.sucursal
                                        , s.id_sucursal
                                        , t.nit
                                        , t.tercero
                                        , c.id_zona
                                        , tt.tercero_telefono
                                        , td.tercero_direccion
                                        , CONCAT_WS(' - ', t.tercero, s.sucursal) AS tercero_sucursal
                                        , s.estado
                                        , td.id_municipio
                                        , 'C' AS tipo --SIGNIFICA QUE ES UN CLIENTE
                                        , te.tercero AS empresa
                                        , e.id_empresa
                                     FROM
                                        facturacion_inventario.cliente c
                                        INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                                        INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                                        INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                                        INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                                        INNER JOIN contabilidad.tercero_telefono AS tt ON tt.id_tercero = t.id_tercero AND tt.principal = TRUE
                                        INNER JOIN contabilidad.tercero_direccion AS td ON td.id_tercero = t.id_tercero AND td.principal = TRUE
                                    UNION 
                                    SELECT
                                        p.id_proveedor  AS id_cliente_proveedor
                                        , p.id_proveedor  AS id_cliente
                                        , t.id_tercero
                                        , s.sucursal
                                        , s.id_sucursal
                                        , t.nit
                                        , t.tercero
                                        , null AS id_zona
                                        , tt.tercero_telefono
                                        , td.tercero_direccion
                                        , CONCAT_WS(' - ', t.tercero, s.sucursal) AS tercero_sucursal
                                        , s.estado
                                        , td.id_municipio
                                        , 'P' AS tipo --SIGNIFICA QUE ES UN PROVEEDOR
                                        , te.tercero AS empresa
                                        , e.id_empresa
                                    FROM
                                        facturacion_inventario.proveedor p
                                        INNER JOIN contabilidad.sucursal s ON s.id_sucursal = p.id_sucursal
                                        INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                                        INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                                        INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                                        INNER JOIN contabilidad.tercero_telefono AS tt ON tt.id_tercero = t.id_tercero AND tt.principal = TRUE
                                        INNER JOIN contabilidad.tercero_direccion AS td ON td.id_tercero = t.id_tercero AND td.principal = TRUE
                            ) AS result
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idClienteProveedor'] = $fila->id_cliente_proveedor;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['idSucursal'] = $fila->id_sucursal;
            $retorno[$contador]['nit'] = $fila->nit;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['idTercero'] = $fila->id_tercero;
            $retorno[$contador]['idZona'] = $fila->id_zona;
            $retorno[$contador]['terceroSucursal'] = $fila->tercero_sucursal;
            $retorno[$contador]['terceroTelefono'] = $fila->tercero_telefono;
            $retorno[$contador]['terceroDireccion'] = $fila->tercero_direccion;
            $retorno[$contador]['estado'] = $fila->estado;
            $retorno[$contador]['idMunicipio'] = $fila->id_municipio;
            $retorno[$contador]['tipo'] = $fila->tipo;
            $retorno[$contador]['empresa'] = $fila->empresa;

            $contador++;
        }
        return $retorno;
    }

    public function consultarInventarioInstalado($arrParametros) {
        $condicion = "";
        $whereAnd = " WHERE ";
        for($i = 0; $i < count($arrParametros); $i++){
            if($arrParametros[$i]["valor"] != "" && $arrParametros[$i]["valor"] != "null" && $arrParametros[$i]["valor"] != null){
                if($i>0){
                   $condicion .= $whereAnd.$arrParametros[$i]["relacion"].$arrParametros[$i]["valor"].")"; 
                }else{
                    $condicion .= $whereAnd.$arrParametros[$i]["relacion"].$arrParametros[$i]["valor"];
                }
                $whereAnd = " AND ";
            }
        }
        $sentenciaSql = "
            /*SELECT
                  p.producto
                , p.id_producto
                , servicio.producto AS servicio
                , t.tercero
                , s.sucursal
                , CONCAT_WS(' - ',  z.zona, r.regional) as zona_regional
                , p.codigo
                , cs.id_cliente_servicio
                , c.id_cliente
                , otp.cantidad
                , otp.valor_unita_con_impue
                , valor_unita_con_impue*cantidad AS sub_total
            FROM
                publics_services.orden_trabajo_producto AS otp
                INNER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_orden_trabajo_cliente = otp.id_orden_trabajo_cliente
                INNER JOIN publics_services.orden_trabajo AS ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                --INNER JOIN publics_services.cliente_servicio_producto AS csp ON csp.id_orden_trabajo = ot.id_orden_trabajo
                INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = otp.id_producto
                INNER JOIN publics_services.cliente_servicio AS cs ON cs.id_orden_trabajo_cliente = otc.id_orden_trabajo_cliente
                INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = otc.id_cliente
                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                INNER JOIN facturacion_inventario.zona AS z ON z.id_zona = c.id_zona
                INNER JOIN facturacion_inventario.regional r ON r.id_regional = z.id_regional
                INNER JOIN (
                        SELECT
                          p.producto
                        , p.id_producto
                        FROM
                        facturacion_inventario.producto p
                ) AS servicio ON servicio.id_producto = cs.id_producto_composicion*/
                

            SELECT DISTINCT
                  p.producto
                , p.id_producto
                , servicio.producto AS servicio
                , t.tercero
                , s.sucursal
                , CONCAT_WS(' - ',  z.zona, r.regional) as zona_regional
                , p.codigo
                , cs.id_cliente_servicio
                , c.id_cliente
                , csp.cantidad
                , csp.valor_unita_con_impue
                , valor_unita_con_impue*cantidad AS sub_total
                , csp.id_bodega
            FROM
                publics_services.orden_trabajo AS ot 
                INNER JOIN (
			SELECT DISTINCT
				  csp.estado
				, csp.id_orden_trabajo
				, csp.cantidad
				, csp.id_producto
				, csp.id_cliente_servicio
				, csp.valor_unita_con_impue
                                , csp.id_bodega
			FROM
				publics_services.cliente_servicio_producto AS csp
                ) AS csp ON csp.id_orden_trabajo = ot.id_orden_trabajo
                INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = csp.id_producto
                INNER JOIN publics_services.cliente_servicio AS cs ON cs.id_cliente_servicio = csp.id_cliente_servicio
                INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = cs.id_cliente
                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                INNER JOIN facturacion_inventario.zona AS z ON z.id_zona = c.id_zona
                INNER JOIN facturacion_inventario.regional r ON r.id_regional = z.id_regional
                INNER JOIN (
                        SELECT DISTINCT
                          p.producto
                        , p.id_producto
                        FROM
                        facturacion_inventario.producto p
                ) AS servicio ON servicio.id_producto = cs.id_producto_composicion    
            $condicion  $whereAnd csp.estado = TRUE
            ORDER BY c.id_cliente DESC
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $total = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['producto'] = $fila->producto;
            $retorno[$contador]['servicio'] = $fila->servicio;
            $retorno[$contador]['cliente'] = $fila->tercero;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['valorUnidad'] = $fila->valor_unita_con_impue;
            $retorno[$contador]['subtotal'] = $fila->sub_total;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['zona'] = $fila->zona_regional;
            $retorno[$contador]['codigo'] = $fila->codigo;
            $retorno[$contador]['idBodega'] = $fila->id_bodega;
            $total += (floatval($fila->sub_total));
            
            $contador++;
        }
        $retorno[0]['total'] = $total;
        return $retorno;
    }

    public function consultarHistoricoCliente(){
        $sentenciaSql = "
            SELECT 
                  p.producto
                , servicio.producto AS servicio
                , td.tipo_documento
                , ot.id_orden_trabajo
                , p.id_producto
                , tp.cantidad
                , tp.valor_unitario_entrada
                , tp.valor_unitario_entrada*tp.cantidad AS sub_total
            FROM 
                publics_services.cliente_transaccion_producto AS ctp
                INNER JOIN facturacion_inventario.transaccion_producto AS tp ON tp.id_transaccion_producto = ctp.id_transaccion_producto
                INNER JOIN facturacion_inventario.transaccion AS tra ON tra.id_transaccion = tp.id_transaccion
                INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = tra.id_tipo_documento
                INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                INNER JOIN publics_services.cliente_servicio AS cs ON cs.id_cliente = ctp.id_cliente
                INNER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_orden_trabajo_cliente = cs.id_orden_trabajo_cliente
                INNER JOIN publics_services.orden_trabajo AS ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                INNER JOIN (
                        SELECT DISTINCT
                          p.producto
                        , p.id_producto
                        FROM
                        facturacion_inventario.producto p
                ) AS servicio ON servicio.id_producto = cs.id_producto_composicion  
            WHERE 
                ctp.id_cliente = $this->idCliente
            ORDER BY ot.id_orden_trabajo, p.id_producto
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['producto'] = $fila->producto;
            $retorno[$contador]['servicio'] = $fila->servicio;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['idProducto'] = $fila->id_producto;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['valorUnitarioEntrada'] = $fila->valor_unitario_entrada;
            $retorno[$contador]['subTotal'] = $fila->sub_total;
            
            //$retorno[0]['total'] += floatval($fila->sub_total);
            
            $contador++;
        }
        return $retorno;
    }
    
    public function consultarCliente() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                       /* SELECT
                            t.id_tercero
                            , c.id_cliente
                            , CONCAT_WS(' - ', t.tercero, s.sucursal) AS cliente
                            , s.id_sucursal
                        FROM
                            facturacion_inventario.cliente c
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                        WHERE
                            t.nit = " . $this->nit . "
                            AND t.estado = TRUE */
                        
                        
                        SELECT
                            id_tercero
                            , id_cliente_proveedor
                            , cliente
                            , id_sucursal
                            , nit
                            , tipo
                        FROM
                            (SELECT
                                t.id_tercero
                                , c.id_cliente AS id_cliente_proveedor
                                , CONCAT_WS(' - ', t.tercero, s.sucursal) AS cliente
                                , s.id_sucursal
                                , t.nit
                                , 'C' AS tipo
                            FROM
                                facturacion_inventario.cliente c
                                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            WHERE
                                t.estado = TRUE

                            UNION

                            SELECT
                                t.id_tercero
                                , p.id_proveedor AS id_cliente_proveedor
                                , CONCAT_WS(' - ', t.tercero, s.sucursal) AS cliente
                                , s.id_sucursal
                                , t.nit
                                , 'P' AS tipo
                            FROM
                                facturacion_inventario.proveedor p
                                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = p.id_sucursal
                                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            WHERE
                                t.estado = TRUE) AS result
                                
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idTercero'] = $fila->id_tercero;
            $retorno[$contador]['idClienteProveedor'] = $fila->id_cliente_proveedor;
            $retorno[$contador]['idCliente'] = $fila->id_cliente_proveedor;
            $retorno[$contador]['cliente'] = $fila->cliente;
            $contador++;
        }
        return $retorno;
    }

    public function buscarCliente($cliente, $limite = '', $tipo = '') {
        $top = '';
        if($limite != ''){
            $top = ' LIMIT '.$limite;
        }
        
        if($tipo != ''){
            $condicion = " AND tipo = '$tipo'";
        }
        
        $sentenciaSql = "SELECT
                                id_cliente_proveedor
				,id_cliente
                                , sucursal
                                , id_sucursal
                                , id_tercero
                                , nit
                                , tercero
                                , id_zona
                                , tercero_sucursal
                                , tipo
                                , factura
                        FROM
                                (
                                SELECT
                                    c.id_cliente AS id_cliente_proveedor
                                    ,c.id_cliente AS id_cliente
                                    , s.sucursal
                                    , s.id_sucursal
                                    , t.id_tercero
                                    , t.nit
                                    , t.tercero
                                    , c.id_zona
                                    , CONCAT_WS(' - ', te.tercero , t.tercero, s.sucursal) AS tercero_sucursal
                                    , 'C' AS tipo --SIGNIFICA QUE ES UN CLIENTE
                                    , tr.factura
                                 FROM
                                    facturacion_inventario.cliente c
                                    INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                                    INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                                    INNER JOIN contabilidad.tipo_regimen AS tr ON tr.id_tipo_regimen = t.id_tipo_regimen
                                    INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                                    INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                                UNION

                                SELECT
                                    p.id_proveedor AS id_cliente_proveedor
                                    ,p.id_proveedor AS id_cliente
                                    , s.sucursal
                                    , s.id_sucursal
                                    , t.id_tercero
                                    , t.nit
                                    , t.tercero
                                    , null AS id_zona
                                    , CONCAT_WS(' - ', te.tercero ,t.tercero, s.sucursal) AS tercero_sucursal
                                    , 'P' AS tipo --SIGNIFICA QUE ES UN PROVEEDOR
                                    , tr.factura
                                 FROM
                                    facturacion_inventario.proveedor p
                                    INNER JOIN contabilidad.sucursal s ON s.id_sucursal = p.id_sucursal
                                    INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                                    INNER JOIN contabilidad.tipo_regimen AS tr ON tr.id_tipo_regimen = t.id_tipo_regimen
                                    INNER JOIN contabilidad.empresa AS e ON e.id_empresa = t.id_empresa
                                    INNER JOIN contabilidad.tercero AS te ON te.id_tercero = e.id_tercero
                                ) AS result
                        WHERE
                                tercero_sucursal ILIKE '%$cliente%'
                                $condicion
                        ORDER BY 
                            tercero_sucursal
                        $top
                               ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array("value" => $fila->tercero_sucursal,
                "idClienteProveedor" => $fila->id_cliente_proveedor,
                "idCliente" => $fila->id_cliente,
                "idSucursal" => $fila->id_sucursal,
                "nit" => $fila->nit,
                "idZona" => $fila->id_zona,
                "idTercero" => $fila->id_tercero,
                "tipo" => $fila->tipo,
                "factura" => $fila->factura
            );
        }
        return $datos;
    }
    
    public function buscarClientePedido($cliente){
        $sentenciaSql = "SELECT
                            c.id_cliente
                            , c.cliente
                            , c.telefonos
                         FROM
                            pedido.cliente c
                         WHERE
                            c.cliente ILIKE '%$cliente%'
                               ";
       $this->conexion->ejecutar($sentenciaSql);
       while($fila = $this->conexion->obtenerObjeto()){
           $datos[] = array("value" => $fila->cliente,
                           "idCliente" => $fila->id_cliente,
                           );
       }
       return $datos;
    }
    
    public function buscarClienteNit($cliente){
        $sentenciaSql = "SELECT
                            c.id_cliente
                            , s.sucursal
                            , s.id_sucursal
                            , t.id_tercero
                            , t.nit
                            , t.tercero
                            , CONCAT_WS(' - ', t.nit, t.tercero, s.sucursal) AS sucursal
                         FROM
                            facturacion_inventario.cliente c
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                         WHERE
                            CONCAT_WS(' - ', t.nit, t.tercero, s.sucursal) ILIKE '%$cliente%'
                               ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array("value" => $fila->sucursal,
                "idCliente" => $fila->id_cliente,
                "idSucursal" => $fila->id_sucursal,
                "nit" => $fila->nit,
                "idTercero" => $fila->id_tercero
            );
        }
        return $datos;
    }

    public function validarExistenciaCliente($nit) {
        $sentenciaSql = "
            SELECT 
                  t.id_tercero
                , t.nit
                , t.tercero
                , t.digito_verificacion
                , t.id_empresa
                , t.estado
                , t.id_tipo_regimen
            FROM
                contabilidad.tercero AS t 
                LEFT OUTER JOIN contabilidad.sucursal AS s ON s.id_tercero = t.id_tercero
                LEFT OUTER JOIN facturacion_inventario.cliente AS c ON s.id_sucursal = c.id_sucursal
            WHERE 
                t.nit = $nit
            GROUP BY t.nit, t.id_tercero        
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTercero"] = $fila->id_tercero;
            $retorno[$contador]["nit"] = $fila->nit;
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["digitoVerificacion"] = $fila->digito_verificacion;
            $retorno[$contador]["idEmpresa"] = $fila->id_empresa;
            $retorno[$contador]["idTipoRegimen"] = $fila->id_tipo_regimen;
            $retorno[$contador]["idCliente"] = "";
            $retorno[$contador]["estado"] = $fila->estado;

            $contador++;
        }
        for ($i = 0; $i < count($retorno); $i++) {
            $retorno[$i]["idCliente"] = $this->consultarIdCliente($retorno[$i]["nit"]);
        }
        $this->conexion->ejecutar($sentenciaSql);
        return $retorno;
    }

    function consultarIdCliente($nit) {
        $sentenciaSql = "
            SELECT
                c.id_cliente
            FROM    
                contabilidad.tercero AS t 
                LEFT OUTER JOIN contabilidad.sucursal AS s ON s.id_tercero = t.id_tercero AND s.principal = true
                INNER JOIN facturacion_inventario.cliente AS c ON s.id_sucursal = c.id_sucursal
            WHERE
                t.nit = $nit
            GROUP BY c.id_cliente      
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_cliente;
    }

    public function obtenerMaximo() {
        $sentenciaSql = "
            SELECT
                MAX(id_cliente) AS id_cliente
            FROM
                facturacion_inventario.cliente
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_cliente;
    }

    function obtenerCondicion() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';

        if ($this->sucursal->getIdTercero() != '' && $this->sucursal->getIdTercero() != 'null' && $this->sucursal->getIdTercero() != null) {
            $this->condicion .= $this->whereAnd . 'id_tercero = ' . $this->sucursal->getIdTercero();
            $this->whereAnd = ' AND ';
        }
        
        if ($this->sucursal->getIdSucursal() != '' && $this->sucursal->getIdSucursal() != 'null' && $this->sucursal->getIdSucursal() != null) {
            $this->condicion .= $this->whereAnd . 'id_sucursal = ' . $this->sucursal->getIdSucursal();
            $this->whereAnd = ' AND ';
        }

        if ($this->nit != '' && $this->nit != 'null' && $this->nit != null) {
            $this->condicion .= $this->whereAnd . 'nit = ' . $this->nit;
            $this->whereAnd = ' AND ';
        }

        if ($this->idCliente != '' && $this->idCliente != 'null' && $this->idCliente != null) {
            $this->condicion .= $this->whereAnd . 'id_cliente_proveedor = ' . $this->idCliente;
            $this->whereAnd = ' AND ';
        }
        
         if ($this->tipoClienteProveedor != '' && $this->tipoClienteProveedor != 'null' && $this->tipoClienteProveedor != null) {
            $this->condicion .= $this->whereAnd . " tipo = '" . $this->tipoClienteProveedor . "'";
            $this->whereAnd = ' AND ';
        }

        if ($this->idZona != '' && $this->idZona != 'null' && $this->idZona != null) {
            $this->condicion .= $this->whereAnd . 'id_zona = ' . $this->idZona;
            $this->whereAnd = ' AND ';
        }

        if ($this->estadoSucursal != '' && $this->estadoSucursal != 'null' && $this->estadoSucursal != null) {
            $this->condicion .= $this->whereAnd . 'estado = ' . $this->estadoSucursal;
            $this->whereAnd = ' AND ';
        }
        
        
        if ($this->idEmpresa != '' && $this->idEmpresa != 'null' && $this->idEmpresa != null) {
            $this->condicion .= $this->whereAnd . ' id_empresa = ' . $this->idEmpresa;
            $this->whereAnd = ' AND ';
        }
    }
    public function validarFacturaRegimen(){
        $sentenciaSql = "
                            SELECT
                                tr.factura
                            FROM
                                facturacion_inventario.cliente c
                                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                                INNER JOIN contabilidad.tipo_regimen AS tr ON tr.id_tipo_regimen = t.id_tipo_regimen
                            WHERE 
                                c.id_cliente = $this->idCliente
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->factura;
    }

}
