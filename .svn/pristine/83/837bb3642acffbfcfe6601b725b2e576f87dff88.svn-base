<?php

require_once '../../entorno/Conexion.php';
date_default_timezone_set("America/bogota");

class Servidor {

    public $conexion;
    public $fechaHoraActual;

    public function __construct() {
        $this->fechaHoraActual = date("Y-m-d h:i:s A");
        $this->conexion = new Conexion();
    }

    public function insertarPedido($parametros) {
        $retorno = array("exito" => "1", "mensaje" => "Hemos recibido su pedido con éxito.");
        try {
            
            $idCliente = $parametros["idCliente"];
            $idZona = $parametros["idZona"];
            $direcciones = $parametros["direcciones"];
            $idFormaPago = $parametros["idFormaPago"];
            $telefono = $parametros["telefono"];
            $idBarrio = $parametros["idBarrio"];
            $direccionEnvio = $parametros["direccionEnvio"];
            $valorDomicilio = $parametros["valorDomicilio"];
            $productos = $parametros["productos"];
            
            /*--------------------------Se valida el horario-----------------------------*/
            $arrHorarios = $this->obtenerHorario();
            
            if($arrHorarios["exito"] == "0"){//Si genera algún error obteniendo el horario
                $retorno["exito"] = "0";
                $retorno["mensaje"] = $arrHorarios["mensaje"];
                return $retorno;
            }
            
            if($arrHorarios["abierto"] == false){//Si el establecimiento se encuentra cerrado en este momento
                $retorno["exito"] = "0";
                $retorno["mensaje"] = "Lo sentimos, en este momento el establecimiento se encuentra cerrado.\r\n\r\nIntente más tarde en el siguiente horario:\r\n\r\n".$arrHorarios["horario"];
                return $retorno;
            }
            
            /* ---------------------------Se actualiza la información de las direcciones-------------- */
            $parametroDireccion = array();
            $parametroDireccion["idCliente"] = $idCliente;
            $parametroDireccion["direccion"] = $direcciones;

            $this->actualizarDireccionCliente($parametroDireccion);

            //--------------------------INSERTO EL PEDIDO---------------------------------------
            $sentenciaSql = "
                            INSERT INTO
                                pedido.pedido
                            (
                                id_cliente
                                , id_pedido_estado
                                , nota
                                , id_usuario_creacion
                                , id_usuario_modificacion
                                , fecha_creacion
                                , fecha_modificacion
                                , fecha
                                , id_zona
                                , telefono
                                , direccion
                                , id_forma_pago
                                , id_barrio
                                , valor_domicilio
                            )
                            VALUES
                            (
                                $idCliente
                                , 1
                                , null
                                , 1
                                , 1
                                , NOW()
                                , NOW()
                                , NOW()
                                , $idZona
                                , '$telefono'
                                , '$direccionEnvio'
                                , $idFormaPago
                                , $idBarrio
                                , $valorDomicilio
                            )
                            ";
            $this->conexion->ejecutar($sentenciaSql);
            $idPedido = $this->obtenerMaximoIdPedido();

            //--------------------------INSERTO EL DETALLE DEL PEDIDO---------------------------------------
            $productos = json_decode($productos);
            $contador = 0;
            $secuencia = 1;
            while ($contador < count($productos)) {

                if (isset($productos[$contador]->nota))
                    $nota = "'" . $productos[$contador]->nota . "'";
                else
                    $nota = "null";

                $sentenciaSql = "
                                INSERT INTO
                                    pedido.pedido_producto
                                (
                                    id_producto
                                    , id_pedido_producto_estado
                                    , nota
                                    , secuencia
                                    , cantidad
                                    , valor_unita_con_impue
                                    , id_pedido
                                    , id_usuario_creacion
                                    , id_usuario_modificacion
                                    , fecha_creacion
                                    , fecha_modificacion
                                )
                                VALUES
                                (
                                    " . $productos[$contador]->idProducto . "
                                    , 1
                                    , $nota
                                    , $secuencia
                                    , " . $productos[$contador]->cantidad . "
                                    , " . $productos[$contador]->precio . "
                                    , $idPedido
                                    , 1
                                    , 1
                                    , NOW()
                                    , NOW()
                                )
                                ";
                $this->conexion->ejecutar($sentenciaSql);
                $secuencia++;
                $contador++;
            }
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: insertarPedido-------------------//\r\n";
            $texto .= '*Error insertando el pedido => ' . $exc->getMessage() . "*\r\nSql => " . $sentenciaSql . "\r\n\r\n";
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */
            $retorno["exito"] = "0";
            $retorno["mensaje"] = "Ocurrió un error al insertar el pedido.";
        }
        return $retorno;
    }

    private function obtenerMaximoIdPedido() {
        $idPedido = null;
        try {
            $sentenciaSql = "
                            SELECT 
                                MAX(id_pedido) AS maximo
                            FROM
                                pedido.pedido
                            ";
            $this->conexion->ejecutar($sentenciaSql);
            $fila = $this->conexion->obtenerObjeto();
            $idPedido = $fila->maximo;
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: obtenerMaximoIdPedido-------------------//\r\n";
            $texto .= '*Error obteniendo maximo id pedido => ' . $exc->getMessage() . "*\r\nSql => " . $sentenciaSql . "\r\n\r\n";
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */
        }
        return $idPedido;
    }
    
    private function obtenerHorario(){
        date_default_timezone_set('America/Bogota');
        $arrHorario = array("exito"=>"1", "mensaje"=>"" ,"abierto"=>false, "horario"=>"");
        try {
            $dia = date("w");
            $hora = strtotime(date("H:i:s"));
            $sentenciaSql = "
                            SELECT 
                                hora_inicio
                                , hora_fin
                                , (to_char(hora_inicio, 'HH12:MI AM') || ' - ' || to_char(hora_fin, 'HH12:MI AM')) AS horario
                            FROM
                                facturacion_inventario.horar_empre_unida_negoc
                            WHERE
                                id_dia = ".$dia."
                            ORDER BY
				hora_inicio";
            $this->conexion->ejecutar($sentenciaSql);
            while ($fila = $this->conexion->obtenerObjeto()) {
                $horaInicio = strtotime($fila->hora_inicio);
                $horaFin = strtotime($fila->hora_fin);
                if($hora >= $horaInicio && $hora <= $horaFin){
                    $arrHorario["abierto"] = true;
                    break;
                }
            }
            
            $horario = "";
            $this->conexion->ejecutar($sentenciaSql);
            while ($fila = $this->conexion->obtenerObjeto()) {
                $horario .= $fila->horario."\r\n";
            }
            $arrHorario["horario"] = $horario;
            
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: obtenerHorario-------------------//\r\n";
            $texto .= '*Error obteniendo el horario => ' . $exc->getMessage() . "*\r\nSql => " . $sentenciaSql . "\r\n\r\n";
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */
            
            $arrHorario["exito"] = "0";
            $arrHorario["mensaje"] = "Error al obtener el horario";
        }
        return $arrHorario;
    }

    public function validarCliente($parametro) {
        $retorno = array("exito" => "1", "mensaje" => "", "data" => null, "usuarioLogueado" => false);
        try {
            
            if($parametro["contrasenia"] != "" && $parametro["contrasenia"] != null && $parametro["contrasenia"] != "null"){
                $condicion = "AND contrasenia = '" . md5($parametro["contrasenia"]) . "'";
            }
            
            $sentenciaSql = "
                                                    SELECT 
                                                            id_cliente
                                                            ,cliente
                                                            ,telefonos
                                                            ,correo_electronico
                                                            ,contrasenia
                                                            ,direcciones
                                                            ,tipo_logueo
                                                    FROM
                                                            pedido.cliente
                                                    WHERE
                                                            correo_electronico = '" . $parametro["usuario"] . "'
                                                            $condicion";
            $this->conexion->ejecutar($sentenciaSql);
            if ($this->conexion->obtenerNumeroRegistros() != 0) {
                $fila = $this->conexion->obtenerObjeto();
                $datos["idCliente"] = $fila->id_cliente;
                $datos["cliente"] = $fila->cliente;
                $datos["telefono"] = $fila->telefonos;
                $datos["correoElectronico"] = $fila->correo_electronico;
                $datos["contrasenia"] = $fila->contrasenia;
                $datos["direccion"] = $this->obtenerDirecciones($fila->direcciones);
                $datos["tipoLogueo"] = $fila->tipo_logueo;
                $retorno["usuarioLogueado"] = true;
            }
            $retorno["data"] = $datos;
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: validarCliente-------------------//\r\n";
            $texto .= $retorno[0]['mensaje'] . "\r\n\r\n";
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */

            $retorno['exito'] = (String) "0";
            $retorno['mensaje'] = (String) "Ocurrio un error al validar el cliente";
        }
        return $retorno;
    }

    public function obtenerDirecciones($xmlDireccion) {
        $retorno = array();
        $xml = simplexml_load_string($xmlDireccion);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        
        if(is_array($array["direccionBarrio"][0]) == false){
            $arrDireccion =  $array["direccionBarrio"];
            $array["direccionBarrio"] = null;
            $array["direccionBarrio"][0] = $arrDireccion;
        }
        
        for($i = 0 ; $i< count($array["direccionBarrio"]);$i++){
            $retorno[$i]["direccion"] = $array["direccionBarrio"][$i]["direccion"];
            $retorno[$i]["barrio"] = $array["direccionBarrio"][$i]["barrio"];
            $retorno[$i]["idBarrio"] = $array["direccionBarrio"][$i]["idBarrio"];
        }
        return $retorno;
    }

    public function registrarCliente($parametro) {
        $retorno = array("exito" => "1", "mensaje" => "", "idCliente" => null);
        try {
            
            if($parametro["contrasenia"] != "" && $parametro["contrasenia"] != null && $parametro["contrasenia"] != "null"){
                $contrasenia = md5($parametro["contrasenia"]);
            }else{
                $contrasenia = "";
            }
            
            //Se obtiene el XML de dirección
            $direcciones = $this->obtenerXmlDireccion($parametro["direccion"]);

            //Se valida la existencia de un cliente con ese correo electrónico
            $arrExistClien = $this->validarExistenciaCliente($parametro["correoElectronico"]);
            if ($arrExistClien["numeroRegistros"] != 0) {
                $retorno["exito"] = "0";
                $retorno["mensaje"] = "El correo electrónico " . $parametro["correoElectronico"] . " ya existe.";
            } else {
                $sentenciaSql = "
                                                            INSERT INTO
                                                                    pedido.cliente
                                                            (
                                                                    cliente
                                                                    ,telefonos
                                                                    ,correo_electronico
                                                                    ,contrasenia
                                                                    ,direcciones
                                                                    ,tipo_logueo
                                                            )
                                                            VALUES
                                                            (
                                                                    '" . $parametro["cliente"] . "'
                                                                    ," . $parametro["telefono"] . "
                                                                    ,'" . $parametro["correoElectronico"] . "'
                                                                    ,'" . $contrasenia . "'
                                                                    ,'" . $direcciones . "'
                                                                    ," . $parametro["tipoLogueo"] . "
                                                            )
                                                    ";
                $this->conexion->ejecutar($sentenciaSql);
                $retorno["idCliente"] = $this->obtenerMaximoIdCliente();
            }
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: registrarCliente-------------------//\r\n";
            $texto .= '*Error insertando el pedido => ' . $exc->getMessage() . "*\r\nSql => " . $sentenciaSql . "\r\n\r\n";
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */
            $retorno["exito"] = "0";
            $retorno["mensaje"] = "Ocurrio un error al registrar el cliente";
        }
        return $retorno;
    }

    private function obtenerMaximoIdCliente() {
        $sentenciaSql = "
                                                    SELECT MAX(id_cliente) AS maximo FROM pedido.cliente
                                            ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }

    public function validarExistenciaCliente($correoElectronico, $idCliente = "") {
        $retorno = array("exito" => "1", "mensaje" => "", "data" => null, "numeroRegistros" => 0);
        try {
            
            if($idCliente != "" && $idCliente != null && $idCliente != "null"){
                $condicion = " AND id_cliente <> ".$idCliente;
            }
            
            $sentenciaSql = "
                                                    SELECT 
                                                            id_cliente
                                                            ,cliente
                                                            ,telefonos
                                                            ,correo_electronico
                                                            ,contrasenia
                                                            ,direcciones
                                                            ,tipo_logueo
                                                    FROM
                                                            pedido.cliente
                                                    WHERE
                                                            correo_electronico = '" . $correoElectronico . "'
                                                            $condicion
                                                    ";            
            $this->conexion->ejecutar($sentenciaSql);
            $retorno["numeroRegistros"] = $this->conexion->obtenerNumeroRegistros();
            if ($this->conexion->obtenerNumeroRegistros() != 0) {
                $fila = $this->conexion->obtenerObjeto();
                $datos["idCliente"] = $fila->id_cliente;
                $datos["cliente"] = $fila->cliente;
                $datos["telefono"] = $fila->telefonos;
                $datos["correoElectronico"] = $fila->correo_electronico;
                $datos["contrasenia"] = $fila->contrasenia;
                $datos["direccion"] = $this->obtenerDirecciones($fila->direcciones);
                $datos["tipoLogueo"] = $this->tipo_logueo;
                $retorno["usuarioLogueado"] = true;
            }
            $retorno["data"] = $datos;
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: validarExistenciaCliente-------------------//\r\n";
            $texto .= $retorno[0]['mensaje'] . "\r\n\r\n";
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */

            $retorno['exito'] = (String) "0";
            $retorno['mensaje'] = (String) "Ocurrio un error al validar la existencia del cliente";
        }
        return $retorno;
    }

    public function actualizarDireccionCliente($parametros) {
        $retorno = array("exito" => "1", "mensaje" => "Las direcciones se actualizaron correctamente");

        try {
            $idCliente = $parametros["idCliente"];
            $xmlDireccion = $this->obtenerXmlDireccion($parametros["direccion"]);

            $sentenciaSql = "
                        UPDATE
                            pedido.cliente
                        SET
                            direcciones = '" . $xmlDireccion . "'
                        WHERE
                            id_cliente = $idCliente
                        ";
            $this->conexion->ejecutar($sentenciaSql);
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: actualizarDireccionCliente-------------------//\r\n";
            $texto .= '*Error insertando el pedido => ' . $exc->getMessage() . "*\r\nSql => " . $sentenciaSql . "\r\n\r\n";
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */
            $retorno["exito"] = "0";
            $retorno["mensaje"] = "Ocurrio un error al actualizar las direcciones del cliente";
        }
        return $retorno;
    }

    private function obtenerXmlDireccion($direcciones) {
        if (is_array($direcciones) == false) {
            $direcciones[0] = $direcciones;
        }
        
        
        //Se recorren las direcciones enviadas
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><direcciones></direcciones>');
        foreach (json_decode($direcciones) as $direccion) { 
            $direccionXml = $xml->addChild('direccionBarrio');
            $direccionXml->addChild('direccion', $direccion->direccion);
            $direccionXml->addChild('idBarrio', $direccion->id_barrio);
            $direccionXml->addChild('barrio', $direccion->barrio);
        }
        
        //Se adiciona la información del cliente 
        $xmlDireccion = $xml->asXML();
        return $xmlDireccion;
    }

    public function actualizarCliente($parametro) {
        $retorno = array("exito" => "1", "mensaje" => "El cliente se ha actualizado correctamente");
        try {
            
            //Se valida la existencia de un cliente con ese correo electrónico
            $arrExistClien = $this->validarExistenciaCliente($parametro["correoElectronico"],$parametro["idCliente"]);
            if ($arrExistClien["numeroRegistros"] != 0) {
                $retorno["exito"] = "0";
                $retorno["mensaje"] = "El correo electrónico " . $parametro["correoElectronico"] . " ya existe.";
            }else{
                //Se obtiene el XML de dirección
                $direcciones = $this->obtenerXmlDireccion($parametro["direccion"]);

                $sentenciaSql = "
                                                        UPDATE 
                                                                pedido.cliente
                                                        SET 
                                                                cliente = '" . $parametro["cliente"] . "'
                                                                ,telefonos = " . $parametro["telefono"] . "
                                                                ,correo_electronico = '" . $parametro["correoElectronico"] . "'
                                                                ,direcciones = '" . $direcciones . "'
                                                                ,tipo_logueo = " . $parametro["tipoLogueo"] . "
                                                        WHERE
                                                                id_cliente = " . $parametro["idCliente"];
                $this->conexion->ejecutar($sentenciaSql);

                //Se valida si se desea modificar la contraseña
                if ($parametro["nuevaContrasenia"] != "" && $parametro["nuevaContrasenia"] != null) {
                    $sentenciaSql = "
                                                        UPDATE 
                                                                pedido.cliente
                                                        SET 
                                                                contrasenia = '" . md5($parametro["nuevaContrasenia"]) . "'
                                                        WHERE
                                                                id_cliente = " . $parametro["idCliente"];
                    $this->conexion->ejecutar($sentenciaSql);
                }
            }
        } catch (Exception $exc) {
            /* -----SE CREA UN LOG DE ERRORES---- */
            $texto = "//------------FECHA: " . $this->fechaHoraActual . "----------MÉTODO: actualizarCliente-------------------//\r\n";
            $texto .= '*Error insertando el pedido => ' . $exc->getMessage() . "*\r\nSql => " . $sentenciaSql . "\r\n\r\n";
            $texto .= '*Parametro => ' . json_encode($parametro);
            $texto .= "//--------------------------------------------------------------------------------------------------------------------------------//\r\n\r\n\r\n";
            $ar = fopen("../log_errores.txt", "a+");
            fwrite($ar, $texto);
            fclose($ar);
            /* ---------------------------------- */
            $retorno["exito"] = "0";
            $retorno["mensaje"] = "Ocurrio un error al actualizar el cliente";
        }
        return $retorno;
    }

}
