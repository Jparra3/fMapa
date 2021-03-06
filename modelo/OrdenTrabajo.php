<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
require_once '../modelo/Tecnico.php';
class OrdenTrabajo {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idOrdenTrabajo;
    private $fecha;
    private $idTipoDocumento;
    private $numero;
    private $ordenador;
    private $idMunicipio;
    private $fechaInicio;
    private $fechaFin;
    private $idTecnico;
    private $idEstadoOrdenTrabajo;
    private $tipoOrdenTrabajo;
    private $estado;
    private $clienteServicio;
    private $observacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    

    public function __construct(\entidad\OrdenTrabajo $ordenTrabajo) {
        $this->idOrdenTrabajo = $ordenTrabajo->getIdOrdenTrabajo();
        $this->fecha = $ordenTrabajo->getFecha();
        $this->idTipoDocumento = $ordenTrabajo->getIdTipoDocumento();
        $this->numero = $ordenTrabajo->getNumero();
        $this->ordenador = $ordenTrabajo->getOrdenador() != "" ? $ordenTrabajo->getOrdenador(): new \entidad\Ordenador();
        $this->clienteServicio = $ordenTrabajo->getClienteServicio() != "" ? $ordenTrabajo->getClienteServicio(): new \entidad\ClienteServicio();
        $this->idMunicipio = $ordenTrabajo->getIdMunicipio();
        $this->fechaInicio = $ordenTrabajo->getFechaInicio();
        $this->fechaFin = $ordenTrabajo->getFechaFin();
        $this->tipoOrdenTrabajo = $ordenTrabajo->getTipoOrdenTrabajo();
        $this->estado = $ordenTrabajo->getEstado();
        $this->observacion = $ordenTrabajo->getObservacion();
        $this->idEstadoOrdenTrabajo = $ordenTrabajo->getIdEstadoOrdenTrabajo();
        $this->idTecnico = $ordenTrabajo->getIdTecnico();
        $this->idUsuarioCreacion = $ordenTrabajo->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $ordenTrabajo->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    function adicionar(){
        $sentenciaSql = "
                            INSERT INTO
                                publics_services.orden_trabajo
                            (
                                fecha
                                ,id_tipo_documento
                                ,numero
                                ,id_ordenador
                                ,id_municipio
                                ,fecha_inicio
                                ,fecha_fin
                                ,id_estado_orden_trabajo
                                ,tipo_orden_trabajo
                                ,observacion
                                ,estado
                                ,id_cliente_servicio
                                ,id_usuario_creacion
                                ,id_usuario_modificacion
                                ,fecha_creacion
                                ,fecha_modificacion
                            )
                            VALUES
                            (
                                NOW()--'".$this->fecha."'
                                ,$this->idTipoDocumento
                                ,$this->numero
                                ,".$this->ordenador->getIdOrdenador()."
                                , $this->idMunicipio
                                , '".$this->fechaInicio."'
                                , '".$this->fechaFin."'
                                , $this->idEstadoOrdenTrabajo
                                , $this->tipoOrdenTrabajo    
                                , '$this->observacion'
                                , ".$this->estado."
                                ,".$this->clienteServicio->getIdClienteServicio()."
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                            )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    function modificar(){
        
        $idClienteServicio = $this->clienteServicio->getIdClienteServicio();
        if( $idClienteServicio == "" || $idClienteServicio == null || $idClienteServicio == "null"){
            $idClienteServicio = "NULL";
        }
        
        $sentenciaSql = "
                            UPDATE 
                                publics_services.orden_trabajo
                            SET 
                                 fecha = NOW()
                                ,id_tipo_documento = $this->idTipoDocumento
                                ,numero = $this->numero
                                ,id_ordenador = ".$this->ordenador->getIdOrdenador()."
                                ,id_municipio = $this->idMunicipio
                                ,fecha_inicio = '".$this->fechaInicio."'
                                ,fecha_fin = '".$this->fechaFin."'
                                ,observacion = '".$this->observacion."'
                                ,estado = ".$this->estado."
                                ,id_cliente_servicio = ".$idClienteServicio."
                                ,id_estado_orden_trabajo = $this->idEstadoOrdenTrabajo
                                ,tipo_orden_trabajo = $this->tipoOrdenTrabajo
                                ,id_usuario_modificacion = $this->idUsuarioModificacion
                                ,fecha_modificacion = NOW()    
                            WHERE
                                id_orden_trabajo = $this->idOrdenTrabajo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT DISTINCT
                            ot.id_orden_trabajo
                            , ot.fecha
                            , ot.id_tipo_documento
                            , ot.numero
                            , ot.id_ordenador
                            , ot.id_municipio
                            , ot.fecha_inicio
                            , ot.fecha_fin
                            , ot.id_estado_orden_trabajo
                            , ot.observacion
                            , ot.estado
                            , ot.id_cliente_servicio
                            , eot.estado_orden_trabajo
                            , CASE 
				WHEN ot.tipo_orden_trabajo = 1 THEN 'Instalación'
			        WHEN ot.tipo_orden_trabajo = 2 THEN 'Mantenimiento'
                                WHEN ot.tipo_orden_trabajo = 3 THEN 'Desinstalación'
			        ELSE 'Inválido'
			    END AS tipo
                            , ot.tipo_orden_trabajo
                            , td.tipo_documento
                            , o.id_empleado
                            , CONCAT_WS(' ', po.primer_nombre, po.segundo_nombre, po.primer_apellido, po.segundo_apellido) as nombre_ordenador
                            , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                            ,CONCAT_WS(' - ', te.nit , te.tercero) as cliente
                            
                        FROM
                            publics_services.orden_trabajo ot
                            INNER JOIN contabilidad.tipo_documento td ON td.id_tipo_documento = ot.id_tipo_documento
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN nomina.empleado eo ON eo.id_empleado = o.id_empleado
                            INNER JOIN general.persona po ON po.id_persona = eo.id_persona
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                            INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                            INNER JOIN publics_services.estado_orden_trabajo AS eot ON eot.id_estado_orden_trabajo = ot.id_estado_orden_trabajo
                            INNER JOIN publics_services.orden_trabajo_tecnico AS ott ON ott.id_orden_trabajo = ot.id_orden_trabajo
                            INNER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_orden_trabajo = ot.id_orden_trabajo
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = otc.id_cliente
                            INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero AS te ON te.id_tercero = s.id_tercero
                            INNER JOIN publics_services.tecnico t ON t.id_tecnico = ott.id_tecnico
                        $this->condicion 
                            ".Tecnico::obtenerTecnicos('t.id_tecnico', $this->whereAnd, $this->conexion)."
                        ORDER BY 
                            ot.numero

                ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        
        while ($fila = $this->conexion->obtenerObjeto()){
            $fecha = new \DateTime($fila->fecha);
            $fechaInicio = new \DateTime($fila->fecha_inicio);
            $fechaFin = new \DateTime($fila->fecha_fin);
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['fecha'] = $fecha->format('Y-m-d');
            $retorno[$contador]['idTipoDocumento'] = $fila->id_tipo_documento;
            $retorno[$contador]['numero'] = $fila->numero;
            $retorno[$contador]['idOrdenador'] = $fila->id_ordenador;
            $retorno[$contador]['idMunicipio'] = $fila->id_municipio;
            $retorno[$contador]['fechaInicio'] = $fechaInicio->format('Y-m-d');
            $retorno[$contador]['fechaFin'] = $fechaFin->format('Y-m-d');
            $retorno[$contador]['estado'] = $fila->estado;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $retorno[$contador]['id_empleado'] = $fila->id_empleado;
            $retorno[$contador]['nombreOrdenador'] = $fila->nombre_ordenador;
            $retorno[$contador]['municipio'] = $fila->municipio;
            $retorno[$contador]['idEstadoOrdenTrabajo'] = $fila->id_estado_orden_trabajo;
            $retorno[$contador]['estadoOrdenTrabajo'] = $fila->estado_orden_trabajo;
            $retorno[$contador]['tipoOrdenTrabajo'] = $fila->tipo_orden_trabajo;
            $retorno[$contador]['observacion'] = $fila->observacion;
            $retorno[$contador]['tipo'] = $fila->tipo;
            $retorno[$contador]['cliente'] = $fila->cliente;
            $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
            $contador++;
        }
        return $retorno;
    }
    function obtenerNumeroOrdenTrabajo(){
        $sentenciaSql = "   
                            SELECT
                                MAX(numero) AS maximo
                            FROM 
                                publics_services.orden_trabajo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto(); 
        $numero = $fila->maximo + 1;
        return $numero;
    }
    
    function obtenerMaximo(){
        $sentenciaSql = "   
                            SELECT
                                MAX(id_orden_trabajo) AS maximo
                            FROM 
                                publics_services.orden_trabajo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto(); 
        $numero = $fila->maximo;
        return $numero;
    }
    function consultarProductosPedidos($idPedidos) {
            $sentenciaSql = "
                        SELECT DISTINCT
                            c.id_cliente
                            , CONCAT_WS(' - ', t.tercero, s.sucursal) AS cliente
                            , t.nit 
                            , pr.producto AS producto_composicion
                            , pr.codigo
                            , p.id_pedido
                            , p.tipo_pedido
                            , pr.id_producto AS id_producto_compuesto
                            , pr.id_producto AS id_producto_composicion 
                            , p.id_cliente_servicio
                        FROM
                            facturacion_inventario.pedido p
                            INNER JOIN facturacion_inventario.pedido_producto pp ON pp.id_pedido = p.id_pedido
                            LEFT OUTER JOIN facturacion_inventario.producto pr ON pr.id_producto = pp.id_producto
                            LEFT OUTER JOIN facturacion_inventario.estado_pedido_producto epp ON epp.id_estado_pedido_producto = pp.id_pedido_producto_estado
                            LEFT OUTER JOIN facturacion_inventario.producto_composicion pc ON pc.id_producto_compuesto = pr.id_producto
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = p.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                        WHERE
                            p.id_pedido IN ($idPedidos)
                ";
        
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idProductoCompuesto'] = $fila->id_producto_compuesto;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['cliente'] = $fila->cliente;
            $retorno[$contador]['nit'] = $fila->nit;
            $retorno[$contador]['idProductoComposicion'] = $fila->id_producto_composicion;
            $retorno[$contador]['productoComposicion'] = $fila->producto_composicion;
            $retorno[$contador]['codigo'] = $fila->codigo;
            $retorno[$contador]['idPedido'] = $fila->id_pedido;
            $retorno[$contador]['tipoPedido'] = $fila->tipo_pedido;
            $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
            $contador++;
        }
        
        return $retorno;
    }
    
    public function consultarClienteServicio(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT DISTINCT
			    cs.id_cliente_servicio	
                            , ecs.estado_cliente_servicio
                            ,otc.id_orden_trabajo_cliente
                            , otc.id_orden_trabajo
                            , otc.id_cliente
                            , otc.id_pedido
                            , otc.estado
                            , otc.id_producto_composicion
                            , p.producto AS servicio
                            , cs.valor
                            , p.codigo 
                            , s.sucursal
                            , t.tercero
                            , CONCAT_WS(' ', t.tercero, s.sucursal) as cliente
                            , t.nit
                            , ot.numero
                            ,p.id_producto
                            ,p.id_producto AS id_producto_compuesto
                            ,TO_CHAR(cs.fecha_inicial,'YYYY-mm-dd') AS fecha_inicial
                            ,TO_CHAR(cs.fecha_final,'YYYY-mm-dd') AS fecha_final                            
                            ,CONCAT_WS(' ', po.primer_nombre, po.segundo_nombre, po.primer_apellido, po.segundo_apellido) as ordenador
                            , regexp_replace(CONCAT_WS(' -',COALESCE(d.departamento,''),COALESCE(m.municipio,'')), '\t*', '', 'g') AS municipio
                        FROM
			    publics_services.cliente_servicio AS cs
                            INNER JOIN publics_services.estado_cliente_servicio ecs ON ecs.id_estado_cliente_servicio = cs.id_estado_cliente_servicio
                            INNER JOIN publics_services.orden_trabajo_cliente otc ON otc.id_orden_trabajo_cliente = cs.id_orden_trabajo_cliente
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = cs.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                            INNER JOIN publics_services.orden_trabajo_tecnico AS ott ON ott.id_orden_trabajo = ot.id_orden_trabajo
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = cs.id_producto_composicion
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN nomina.empleado eo ON eo.id_empleado = o.id_empleado
                            INNER JOIN general.persona po ON po.id_persona = eo.id_persona
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio	
                            INNER JOIN general.departamento AS d ON d.id_departamento = m.id_departamento
                            INNER JOIN publics_services.tecnico tec ON tec.id_tecnico = ott.id_tecnico
                        $this->condicion    
                            ".Tecnico::obtenerTecnicos('tec.id_tecnico', $this->whereAnd, $this->conexion)."
                ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
            $retorno[$contador]['idOrdenTrabajoCliente'] = $fila->id_orden_trabajo_cliente;
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['idPedido'] = $fila->id_pedido;
            $retorno[$contador]['estado'] = $fila->estado;
            $retorno[$contador]['idProductoComposicion'] = $fila->id_producto_composicion;
            $retorno[$contador]['servicio'] = $fila->servicio;
            $retorno[$contador]['codigo'] = $fila->codigo;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['cliente'] = $fila->cliente;
            $retorno[$contador]['numero'] = $fila->numero;
            $retorno[$contador]['nit'] = $fila->nit;
            $retorno[$contador]['valor'] = $fila->valor;
            $retorno[$contador]['fechaInicial'] = $fila->fecha_inicial;
            $retorno[$contador]['fechaFinal'] = $fila->fecha_final;
            $retorno[$contador]['ordenador'] = $fila->ordenador;
            $retorno[$contador]['municipio'] = $fila->municipio;
            $retorno[$contador]['idProducto'] = $fila->id_producto; 
            $retorno[$contador]['estadoClienteServicio'] = $fila->estado_cliente_servicio; 
            $retorno[$contador]['idProductoCompuesto'] = $fila->id_producto_compuesto; 
            $contador++;
        }
        return $retorno;
    }
    public function consultarOrdenTrabajoDatosOrdenador(){
        $sentenciaSql = "
                    SELECT
                    ot.id_orden_trabajo
                    , CONCAT_WS('',
                            CASE to_char(ot.fecha, 'd')
                            WHEN '1' THEN 'Domingo'
                            WHEN '2' THEN 'Lunes'
                            WHEN '3' THEN 'Martes'
                            WHEN '4' THEN 'Miércoles'
                            WHEN '5' THEN 'Jueves'
                            WHEN '6' THEN 'Viernes'
                            WHEN '7' THEN 'Sábado'
                    END
                    , to_char(ot.fecha, ', DD \"de\" ')
                    ,  CASE to_char(ot.fecha, 'MM')
                            WHEN '01' THEN 'Enero'
                            WHEN '02' THEN 'Febrero'
                            WHEN '03' THEN 'Marzo'
                            WHEN '04' THEN 'Abril'
                            WHEN '05' THEN 'Mayo'
                            WHEN '06' THEN 'Junio'
                            WHEN '07' THEN 'Julio'
                            WHEN '08' THEN 'Agosto'
                            WHEN '09' THEN 'Septiembre'
                            WHEN '10' THEN 'Octubre'
                            WHEN '11' THEN 'Noviembre'
                            WHEN '12' THEN 'Diciembre'
                    END
                    , to_char(ot.fecha, '  \"de\" YYYY')
                    ) AS fecha
                    , ot.id_tipo_documento
                    , ot.numero
                    , ot.id_ordenador
                    , ot.id_municipio
                    , ot.fecha_inicio
                    , ot.fecha_fin
                    , ot.estado
                    , td.tipo_documento
                    , o.id_empleado
                    , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) as nombre_empleado
                    , p.numero_identificacion
                    , regexp_replace(m.municipio, '\t*', '', 'g') as municipio
                    , c.cargo
                    FROM
                            publics_services.orden_trabajo ot
                            INNER JOIN contabilidad.tipo_documento td ON td.id_tipo_documento = ot.id_tipo_documento
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN nomina.empleado e ON e.id_empleado = o.id_empleado
                            INNER JOIN general.persona p ON p.id_persona = e.id_persona
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                            LEFT OUTER JOIN general.cargo c ON c.id_cargo = e.id_cargo
                    WHERE ot.id_orden_trabajo = $this->idOrdenTrabajo";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        
        while ($fila = $this->conexion->obtenerObjeto()){                  
              $retorno['idOrdenTrabajo'] = $fila->id_orden_trabajo;
              $retorno['fecha'] = $fila->fecha;
              $retorno['idTipoDocumento'] = $fila->id_tipo_documento;
              $retorno['numero'] = $fila->numero;
              $retorno['idOrdenador'] = $fila->id_ordenador;
              $retorno['idMunicipio'] = $fila->id_municipio;
              $retorno['fechaInicio'] = $fila->fecha_inicio;
              $retorno['fechaFin'] = $fila->fecha_fin;
              $retorno['estado'] = $fila->estado;
              $retorno['tipoDocumento'] = $fila->tipo_documento;
              $retorno['idEmpleado'] = $fila->id_empleado;
              $retorno['nombreEmpleado'] = $fila->nombre_empleado;
              $retorno['numeroIdentificacion'] = $fila->numero_identificacion;  
              $retorno['municipio'] = $fila->municipio;
              $retorno['cargo'] = $fila->cargo;           
        }
        return $retorno;
    }
    
    public function consultarOrdenServicio(){
        $sentenciaSql = "SELECT
                otc.id_orden_trabajo_cliente
                , otc.id_orden_trabajo
                , otc.id_pedido
                , otc.estado
                , otc.id_producto_composicion
                , p.codigo
                , p.producto
                , CASE
                    WHEN p.producto LIKE '%INSTALA%' THEN 0
                        ELSE p.valor_entrada
                END AS valor_salid_con_impue
                , CASE
                    WHEN p.producto LIKE '%INSTALA%' THEN p.valor_entrada
                        ELSE 0
                END AS valor_instalacion

                , s.sucursal
                , t.tercero
                , ot.id_municipio
                , m.municipio
                , t.nit
                , CONCAT_WS(' ', t.tercero, s.sucursal) as tercero_sucursal
                , COALESCE(td.tercero_direccion, '') as tercero_direccion
                , COALESCE(tf.tercero_telefono, '') as tercero_telefono
                , COALESCE(te.tercero_email, '') as tercero_email
                , re.factura
                , ot.numero
            , ot.fecha
            FROM
                publics_services.orden_trabajo_cliente otc
                INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = otc.id_cliente
                INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                INNER JOIN contabilidad.tipo_regimen AS re ON re.id_tipo_regimen = t.id_tipo_regimen
                INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                INNER JOIN facturacion_inventario.producto p ON p.id_producto = otc.id_producto_composicion
                INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                LEFT OUTER JOIN contabilidad.tercero_direccion td ON td.id_tercero = s.id_tercero AND td.principal = true
                LEFT OUTER JOIN contabilidad.tercero_telefono tf ON tf.id_tercero = s.id_tercero AND tf.principal = true
                LEFT OUTER JOIN contabilidad.tercero_email te ON te.id_tercero = s.id_tercero AND te.principal = true
           
                WHERE ot.id_orden_trabajo = $this->idOrdenTrabajo";
         
        $this->conexion->ejecutar($sentenciaSql);
        
        $retorno = array();
        
        while ($fila = $this->conexion->obtenerObjeto()){   
            $retorno['idOrdenTrabajoCliente'] = $fila->id_orden_trabajo_cliente;   
            $retorno['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno['idPedido'] = $fila->idPedido;
            $retorno['estado'] = $fila->estado;
            $retorno['idProductoComposicion'] = $fila->id_producto_composicion;
            $retorno['codigo'] = $fila->codigo;
            $retorno['producto'] = $fila->producto;
            $retorno['valorSalidaConImpue'] = $fila->valor_salid_con_impue;
            $retorno['valorInstalacion'] = $fila->valor_instalacion;
            $retorno['sucursal'] = $fila->sucursal;
            $retorno['tercero'] = $fila->tercero;
            $retorno['idMunicipio'] = $fila->id_municipio;
            $retorno['municipio'] = $fila->municipio;
            $retorno['nit'] = $fila->nit;
            $retorno['terceroSucursal'] = $fila->tercero_sucursal;
            $retorno['terceroDireccion'] = $fila->tercero_direccion;
            $retorno['terceroTelefono'] = $fila->tercero_telefono;
            $retorno['terceroEmail'] = $fila->tercero_email;
            $retorno['factura'] = $fila->factura;            
            $retorno['numero'] = $fila->numero;
            $retorno['fecha'] = $fila->fecha;
        }
        return $retorno;
    }
    
    public function actualizarEstado(){
        $sentenciaSql = "
                            UPDATE
                                publics_services.orden_trabajo
                            SET
                                id_estado_orden_trabajo = $this->idEstadoOrdenTrabajo
                            WHERE
                                id_orden_trabajo = $this->idOrdenTrabajo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function actualizarEstadoServicio(){
        $sentenciaSql = "
                            UPDATE
                                publics_services.orden_trabajo
                            SET
                                id_estado_orden_trabajo = $this->idEstadoOrdenTrabajo
                            WHERE
                                id_cliente_servicio = ".$this->clienteServicio->getIdClienteServicio()."
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->idOrdenTrabajo != '' && $this->idOrdenTrabajo != 'null' && $this->idOrdenTrabajo != null ){
            $this->condicion .= $this->whereAnd . ' ot.id_orden_trabajo = ' . $this->idOrdenTrabajo;
            $this->whereAnd = ' AND ';
        }
        
        if($this->idTipoDocumento != '' && $this->idTipoDocumento != 'null' && $this->idTipoDocumento != null ){
            $this->condicion .= $this->whereAnd . ' ot.id_tipo_documento = ' . $this->idTipoDocumento;
            $this->whereAnd = ' AND ';
        }
        
        if($this->numero != '' && $this->numero != 'null' && $this->numero != null ){
            $this->condicion .= $this-> whereAnd . ' ot.numero = ' . $this->numero;
            $this->whereAnd = ' AND ';
        }
        
        if($this->idTecnico != '' && $this->idTecnico != 'null' && $this->idTecnico != null ){
            $this->condicion .= $this->whereAnd . ' ott.id_tecnico IN (' . $this->idTecnico.")";
            $this->whereAnd = ' AND ';
        }
        
        if($this->tipoOrdenTrabajo != '' && $this->tipoOrdenTrabajo != 'null' && $this->tipoOrdenTrabajo != null ){
            $this->condicion .= $this->whereAnd . ' ot.tipo_orden_trabajo IN (' . $this->tipoOrdenTrabajo.")";
            $this->whereAnd = ' AND ';
        }
        
        if($this->idEstadoOrdenTrabajo != '' && $this->idEstadoOrdenTrabajo != 'null' && $this->idEstadoOrdenTrabajo != null ){
            $this->condicion .= $this->whereAnd . ' ot.id_estado_orden_trabajo IN (' . $this->idEstadoOrdenTrabajo.")";
            $this->whereAnd = ' AND ';
        }
        
        if($this->idMunicipio != '' && $this->idMunicipio != 'null' && $this->idMunicipio != null ){
            $this->condicion .= $this->whereAnd . ' ot.id_municipio = ' . $this->idMunicipio;
            $this->whereAnd = ' AND ';
        }
        
        if($this->fechaInicio != '' && $this->fechaInicio != 'null' && $this->fechaInicio != null ){
            $this->condicion .= $this->whereAnd . " ot.fecha >= '" . $this->fechaInicio."'";
            $this->whereAnd = ' AND ';
        }
        
        if($this->fechaFin != '' && $this->fechaFin != 'null' && $this->fechaFin != null ){
            $this->condicion .= $this->whereAnd . " ot.fecha <= '" . $this->fechaFin."'";
            $this->whereAnd = ' AND ';
        }
        if($this->ordenador != '' && $this->ordenador != 'null' && $this->ordenador != null ){
            if($this->ordenador->getIdOrdenador() != '' && $this->ordenador->getIdOrdenador() != 'null' && $this->ordenador->getIdOrdenador() != null ){
                $this->condicion .= $this->whereAnd . " ot.id_ordenador = " . $this->ordenador->getIdOrdenador();
                $this->whereAnd = ' AND ';
            }
        }    
        
        if($this->clienteServicio != '' && $this->clienteServicio != 'null' && $this->clienteServicio != null ){
            
            if($this->clienteServicio->getIdClienteServicio() != '' && $this->clienteServicio->getIdClienteServicio() != 'null' && $this->clienteServicio->getIdClienteServicio() != null && $this->clienteServicio->getIdClienteServicio() != "NULL" ){
                $this->condicion .= $this->whereAnd . " cs.id_cliente_servicio = ".$this->clienteServicio->getIdClienteServicio();
                $this->whereAnd = ' AND ';
            }
            
            if($this->clienteServicio->getIdProductoComposicion() != '' && $this->clienteServicio->getIdProductoComposicion() != 'null' && $this->clienteServicio->getIdProductoComposicion() != null ){
                $this->condicion .= $this->whereAnd . " cs.id_producto_composicion = ".$this->clienteServicio->getIdProductoComposicion();
                $this->whereAnd = ' AND ';
            }
            if($this->clienteServicio->getIdEstadoClienteServicio() != '' && $this->clienteServicio->getIdEstadoClienteServicio() != 'null' && $this->clienteServicio->getIdEstadoClienteServicio() != null ){
                $this->condicion .= $this->whereAnd . " cs.id_estado_cliente_servicio = ".$this->clienteServicio->getIdEstadoClienteServicio();
                $this->whereAnd = ' AND ';
            }
            
            if($this->clienteServicio->getFechaInicial() != '' && $this->clienteServicio->getFechaInicial() != 'null' && $this->clienteServicio->getFechaInicial() != null ){
                $this->condicion .= $this->whereAnd . " cs.fecha_inicial >= '".$this->clienteServicio->getFechaInicial()."'";
                $this->whereAnd = ' AND ';
            }
            
            if($this->clienteServicio->getFechaFinal() != '' && $this->clienteServicio->getFechaFinal() != 'null' && $this->clienteServicio->getFechaFinal() != 'NULL' && $this->clienteServicio->getFechaFinal() != null ){
                $this->condicion .= $this->whereAnd . " cs.fecha_final <= '".$this->clienteServicio->getFechaFinal()."'";
                $this->whereAnd = ' AND ';
            }
            
            if($this->clienteServicio->getCliente() != '' && $this->clienteServicio->getCliente() != 'null' && $this->clienteServicio->getCliente() != null ){    
                if($this->clienteServicio->getCliente()->getIdCliente() != '' && $this->clienteServicio->getCliente()->getIdCliente() != 'null' && $this->clienteServicio->getCliente()->getIdCliente() != null ){
                    $this->condicion .= $this->whereAnd . " c.id_cliente = ".$this->clienteServicio->getCliente()->getIdCliente();
                    $this->whereAnd = ' AND ';
                }
            }
        }    
        
    }
}
