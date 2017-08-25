<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Caja {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idCaja;
    private $prefijo;
    private $numeroMaximo;
    private $direccionIp;
    private $nombrePc;
    private $fechaExpedicionResolucion;
    private $ultimoNumeroUtilizado;
    private $numeroMinimoSerial;
    private $serialPc;
    private $macPc;
    private $numeroResolucion;
    private $idTipoDocumento;
    private $idBodega;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $numeroCaja;


    public function __construct(\entidad\Caja $caja) {
        $this->idCaja = $caja->getIdCaja();
        $this->prefijo = $caja->getPrefijo();
        $this->numeroMaximo = $caja->getNumeroMaximo();
        $this->direccionIp = $caja->getDireccionIp();
        $this->nombrePc = $caja->getNombrePc();
        $this->fechaExpedicionResolucion = $caja->getFechaExpedicionResolucion();
        $this->ultimoNumeroUtilizado = $caja->getUltimoNumeroUtilizado();
        $this->numeroMinimoSerial = $caja->getNumeroMinimoSerial();
        $this->serialPc = $caja->getSerialPc();
        $this->macPc = $caja->getMacPc();
        $this->numeroResolucion = $caja->getNumeroResolucion();
        $this->idTipoDocumento = $caja->getIdTipoDocumento();
        $this->idBodega = $caja->getIdBodega();
        $this->estado = $caja->getEstado();
        $this->fechaCreacion = $caja->getFechaCreacion();
        $this->fechaModificacion = $caja->getFechaModificacion();
        $this->idUsuarioCreacion = $caja->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $caja->getIdUsuarioModificacion();
        $this->numeroCaja = $caja->getNumeroCaja();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            c.id_caja
                            , c.prefijo
                            , c.numero_maximo
                            , c.direccion_ip
                            , c.nombre_pc
                            , c.fecha_expedicion_resolucion
                            , c.ultimo_numero_utilizado
                            , c.numero_minimo
                            , c.serial_pc
                            , c.mac_pc
                            , c.numero_resolucion
                            , c.id_tipo_documento
                            , td.tipo_documento
                            , (td.numero_tipo_documento + 1) AS numero_tipo_documento
                            , c.id_bodega
                            , b.bodega
                            , c.numero_caja
                        FROM
                            facturacion_inventario.caja AS c
                            LEFT OUTER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = c.id_bodega
                            $this->condicion
                        ORDER BY
                            c.numero_caja
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idCaja"] = $fila->id_caja;
            $retorno[$contador]["prefijo"] = $fila->prefijo;
            $retorno[$contador]["numeroMaximo"] = $fila->numero_maximo;
            $retorno[$contador]["direccionIp"] = $fila->direccion_ip;
            $retorno[$contador]["nombrePc"] = $fila->nombre_pc;
            $retorno[$contador]["fechaExpedicionResolucion"] = $fila->fecha_expedicion_resolucion;
            $retorno[$contador]["ultimoNumeroUtilizado"] = $fila->ultimo_numero_utilizado;
            $retorno[$contador]["numeroMinimo"] = $fila->numero_minimo;
            $retorno[$contador]["serialPc"] = $fila->serial_pc;
            $retorno[$contador]["macPc"] = $fila->mac_pc;
            $retorno[$contador]["numeroResolucion"] = $fila->numero_resolucion;
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["idBodega"] = $fila->id_bodega;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["numeroCaja"] = $fila->numero_caja;
            $contador++;
        }
        return $retorno;
    }
    
    public function cajaConsultaMasiva($idUsuario, $idFormatoImpresion) {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            c.id_caja
                            , c.prefijo
                            , c.numero_maximo
                            , c.direccion_ip
                            , c.nombre_pc
                            , c.fecha_expedicion_resolucion
                            , c.ultimo_numero_utilizado
                            , c.numero_minimo
                            , c.serial_pc
                            , c.mac_pc
                            , c.numero_resolucion
                            , c.id_tipo_documento
                            , td.tipo_documento
                            , (td.numero_tipo_documento + 1) AS numero_tipo_documento
                            , c.id_bodega
                            , b.bodega
                            , c.numero_caja
                            , c.estado
                        FROM
                            facturacion_inventario.caja AS c
                            LEFT OUTER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = c.id_bodega
                            $this->condicion
                        ORDER BY
                            c.numero_caja
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idCaja"] = $fila->id_caja;
            $retorno[$contador]["prefijo"] = $fila->prefijo;
            $retorno[$contador]["numeroMaximo"] = $fila->numero_maximo;
            $retorno[$contador]["numeroMinimo"] = $fila->numero_minimo;
            $retorno[$contador]["ultimoNumeroUtilizado"] = $fila->ultimo_numero_utilizado;
            $retorno[$contador]["direccionIp"] = $fila->direccion_ip;
            $retorno[$contador]["nombrePc"] = $fila->nombre_pc;
            $retorno[$contador]["serialPc"] = $fila->serial_pc;
            $retorno[$contador]["macPc"] = $fila->mac_pc;
            $retorno[$contador]["numeroResolucion"] = $fila->numero_resolucion;
            $retorno[$contador]["fechaExpedicionResolucion"] = $fila->fecha_expedicion_resolucion;
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["idBodega"] = $fila->id_bodega;
            $retorno[$contador]["estado"] = $fila->estado;
            $retorno[$contador]["arrUsuario"] = null;
            $retorno[$contador]["arrFormatoImpresion"] = null;
            $contador++;
        }
        for($i = 0; $i < count($retorno); $i++){
            $retorno[$i]['arrUsuario'] = $this->consultarCajaUsuario($retorno[$i]['idCaja'], $idUsuario);
            $retorno[$i]['arrFormatoImpresion'] = $this->consultarCajaFormatoImpresion($retorno[$i]['idCaja'], $idFormatoImpresion);
        }
        $this->conexion->ejecutar($sentenciaSql);
        return $retorno;
    }
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO facturacion_inventario.caja
                (
                      prefijo
                    , numero_maximo
                    , direccion_ip
                    , nombre_pc
                    , fecha_expedicion_resolucion
                    , ultimo_numero_utilizado
                    , numero_minimo
                    , serial_pc
                    , mac_pc
                    , numero_resolucion
                    , id_tipo_documento
                    , id_bodega
                    , numero_caja
                    , estado
                    , id_usuario_creacion
                    , id_usuario_modificacion
                    , fecha_creacion
                    , fecha_modificacion
                )
            VALUES
                (
                      '$this->prefijo'
                    , $this->numeroMaximo
                    , '$this->direccionIp'
                    , '$this->nombrePc'
                    , '$this->fechaExpedicionResolucion'
                    , $this->ultimoNumeroUtilizado
                    , $this->numeroMinimoSerial
                    , '$this->serialPc'
                    , '$this->macPc'
                    , $this->numeroResolucion
                    , $this->idTipoDocumento
                    , $this->idBodega
                    , $this->numeroCaja
                    , $this->estado
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                    , NOW()
                    , NOW()
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }
    
    public function actualizar(){
        $sentenciaSql = "
            UPDATE 
                facturacion_inventario.caja
            SET
                  prefijo = '$this->prefijo'
                , numero_maximo = $this->numeroMaximo
                , direccion_ip = '$this->direccionIp'
                , nombre_pc = '$this->nombrePc'
                , fecha_expedicion_resolucion = '$this->fechaExpedicionResolucion'
                , ultimo_numero_utilizado = $this->ultimoNumeroUtilizado
                , numero_minimo = $this->numeroMinimoSerial
                , serial_pc = '$this->serialPc'
                , mac_pc = '$this->macPc'
                , numero_resolucion = $this->numeroResolucion
                , id_tipo_documento = $this->idTipoDocumento
                , id_bodega = $this->idBodega
                , estado = '$this->estado'
                , id_usuario_modificacion = $this->idUsuarioCreacion
                , fecha_modificacion = NOW()
            WHERE
                id_caja = $this->idCaja
        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function obtenerMaximo(){
        $sentenciaSql = "
            SELECT
                MAX(id_caja) AS id_caja
            FROM
                facturacion_inventario.caja
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_caja;
    }
    
    public function consultarCajaUsuario($idCaja, $idUsuario){
        $condicion = "";
        $whereAnd = " WHERE ";
        if($idCaja != null && $idCaja != "null" && $idCaja != ""){
            $condicion .= $whereAnd . " c.id_caja = " . $idCaja;
            $whereAnd = " AND ";
        }
        if($idUsuario != null && $idUsuario != "null" && $idUsuario != ""){
            $condicion .= $whereAnd . " u.id_usuario = " . $idUsuario;
            $whereAnd = " AND ";
        }
        $sentenciaSql = "
            SELECT
                  c.id_caja
                , u.id_usuario
                , u.usuario
                , p.persona
            FROM
                facturacion_inventario.caja AS c
                INNER JOIN facturacion_inventario.asignacion_caja AS ac ON ac.id_caja = c.id_caja
                INNER JOIN facturacion_inventario.cajero AS cj ON cj.id_cajero = ac.id_cajero
                INNER JOIN seguridad.usuario AS u ON u.id_usuario = cj.id_usuario
                INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
            $condicion
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idCaja'] = $fila->id_caja;
            $retorno[$contador]['idUsuario'] = $fila->id_usuario;
            $retorno[$contador]['usuario'] = $fila->usuario;
            $retorno[$contador]['persona'] = $fila->persona;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function consultarCajaFormatoImpresion($idCaja, $idFormatoImpresion){
        $condicion = "";
        $whereAnd = " WHERE ";
        if($idCaja != null && $idCaja != "null" && $idCaja != ""){
            $condicion .= $whereAnd . " c.id_caja = " . $idCaja;
            $whereAnd = " AND ";
        }
        if($idFormatoImpresion != null && $idFormatoImpresion != "null" && $idFormatoImpresion != ""){
            $condicion .= $whereAnd . " fi.id_formato_impresion = " . $idFormatoImpresion;
            $whereAnd = " AND ";
        }
        $sentenciaSql = "
            SELECT
                  c.id_caja
                , fi.id_formato_impresion
                , cfi.id_caja_formato_impresion
                , fi.formato_impresion
                , cfi.principal
                , cfi.estado
                , tn.tipo_naturaleza
            FROM
                facturacion_inventario.caja AS c
                INNER JOIN facturacion_inventario.caja_formato_impresion AS cfi ON cfi.id_caja = c.id_caja
                INNER JOIN facturacion_inventario.formato_impresion AS fi ON fi.id_formato_impresion = cfi.id_formato_impresion
                INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = fi.id_tipo_naturaleza
            $condicion $whereAnd cfi.estado = true
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idCaja'] = $fila->id_caja;
            $retorno[$contador]['idFormatoImpresion'] = $fila->id_formato_impresion;
            $retorno[$contador]['formatoImpresion'] = $fila->formato_impresion."-".$fila->tipo_naturaleza;
            $retorno[$contador]['idCajaFormatoImpresion'] = $fila->id_caja_formato_impresion;
            $retorno[$contador]['principal'] = $fila->principal;
            $retorno[$contador]['estado'] = $fila->estado;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function actualizarEstado(){
        $sentenciaSql = "
            UPDATE 
                facturacion_inventario.caja
            SET
                estado = '$this->estado'
            WHERE
                id_caja = $this->idCaja
        ";
        $this->conexion->ejecutar($sentenciaSql);
    }


    public function consultarCajaTipoDocumento($idUsuario, $idTipoNaturaleza, $codigoTipoNaturaleza){
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  td.tipo_documento
                , tn.id_tipo_naturaleza
                , tn.tipo_naturaleza
                , (td.numero_tipo_documento + 1) AS numero_tipo_documento
                , c.id_caja
                , c.prefijo
                , c.id_bodega
                , b.bodega
                , c.numero_caja
                , c.id_tipo_documento
                , c.numero_resolucion
                , c.nombre_pc
                , c.fecha_expedicion_resolucion
                , c.ultimo_numero_utilizado
                , c.numero_minimo
                , c.serial_pc
                , c.mac_pc
            FROM
                facturacion_inventario.caja AS c
                INNER JOIN facturacion_inventario.caja_tipo_documento AS ctd ON ctd.id_caja = c.id_caja
                INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = ctd.id_tipo_documento
                INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza =  td.id_naturaleza
                INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                INNER JOIN seguridad.usuario_oficina AS uo ON uo.id_oficina = td.id_oficina AND uo.principal = TRUE
                INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = c.id_bodega
            WHERE
                c.direccion_ip = '$this->direccionIp'
                AND tn.codigo = '$codigoTipoNaturaleza'
                AND uo.id_usuario = $idUsuario
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idCaja"] = $fila->id_caja;
            $retorno[$contador]["prefijo"] = $fila->prefijo;
            $retorno[$contador]["numeroMaximo"] = $fila->numero_maximo;
            $retorno[$contador]["direccionIp"] = $fila->direccion_ip;
            $retorno[$contador]["nombrePc"] = $fila->nombre_pc;
            $retorno[$contador]["fechaExpedicionResolucion"] = $fila->fecha_expedicion_resolucion;
            $retorno[$contador]["ultimoNumeroUtilizado"] = $fila->ultimo_numero_utilizado;
            $retorno[$contador]["numeroMinimo"] = $fila->numero_minimo;
            $retorno[$contador]["serialPc"] = $fila->serial_pc;
            $retorno[$contador]["macPc"] = $fila->mac_pc;
            $retorno[$contador]["numeroResolucion"] = $fila->numero_resolucion;
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["idBodega"] = $fila->id_bodega;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["numeroCaja"] = $fila->numero_caja;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerNumeroCaja(){
        $sentenciaSql = "
            SELECT
                MAX(numero_caja) AS numero_caja
            FROM
                facturacion_inventario.caja AS c
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $numeroCaja = (int)$fila->numero_caja+1;
        return $numeroCaja;
    }
    
    public function consultarCaja($prefijo){
        $sentenciaSql = "
            SELECT
                  c.id_caja
                , c.prefijo
                , c.direccion_ip
            FROM
                facturacion_inventario.caja AS c
            WHERE
                prefijo ILIKE '%$prefijo%'
        ";
        $this->conexion->ejecutar($sentenciaSql);
        while($fila = $this->conexion->obtenerObjeto()){
            $datos[] = array("value" => $fila->prefijo,
                            "prefijo" => $fila->prefijo,
                            "idCaja" => $fila->id_caja,
                            "direccionIp" => $fila->direccion_ip,
                             );
        }
        return $datos;
    }
    
    function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->direccionIp != "" && $this->direccionIp != "null" && $this->direccionIp != null){
            $this->condicion = $this->condicion.$this->whereAnd." c.direccion_ip = '".$this->direccionIp."'";
            $this->whereAnd = " AND ";
        }
        if($this->idCaja != "" && $this->idCaja != "null" && $this->idCaja != null){
            $this->condicion = $this->condicion.$this->whereAnd." c.id_caja = ".$this->idCaja;
            $this->whereAnd = " AND ";
        }
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion = $this->condicion.$this->whereAnd." c.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
    }
}
