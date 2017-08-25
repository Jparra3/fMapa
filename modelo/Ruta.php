<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../entidad/PeriodicidadVisita.php';
require_once '../modelo/PeriodicidadVisita.php';
require_once '../entidad/Cliente.php';
date_default_timezone_set('America/Bogota');
class Ruta {
    public $conexion = null;
    public $numeroRegistros = null;
    private $condicion;
    private $whereAnd;
    
    private $idRuta;
    private $fechaHora;
    private $estado;
    private $idRutaEstado;
    private $idRutaPadre;
    private $idZona;
    private $idRegional;
    private $archivo;
    private $jsonData;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    private $cliente;
    private $fechaInicio;
    private $fechaFin;
    private $periodicidad;
    private $diaSeleccionado;
    private $fechaSelecDiaUnix = null;
    
    public function __construct(\entidad\Ruta $ruta) {
        
        $this->idRuta = $ruta->getIdRuta();
        $this->fechaHora = $ruta->getFechaHora();
        $this->estado = $ruta->getEstado();
        $this->idRutaEstado = $ruta->getIdRutaEstado();
        $this->idRutaPadre = $ruta->getIdRutaPadre();
        $this->idZona = $ruta->getIdZona();
        $this->idRegional = $ruta->getIdRegional();
        $this->archivo = $ruta->getArchivo();
        $this->jsonData = $ruta->getJsonData();
        $this->idUsuarioCreacion = $ruta->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $ruta->getIdUsuarioModificacion();
        $this->fechaCreacion = $ruta->getFechaCreacion();
        $this->fechaModificacion = $ruta->getFechaModificacion();
        
        $this->cliente = $ruta->getCliente() != "" ? $ruta->getCliente() : new \entidad\Cliente();    
        $this->fechaFin = $ruta->getFechaFin();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.ruta
                        (
                            fecha_hora
                            , estado
                            , id_ruta_estado
                            , id_ruta_padre
                            , id_cliente
                            , id_zona
                            , id_regional
                            , archivo
                            , data_json
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                        )
                        VALUES
                        (
                            '$this->fechaHora'
                            , $this->estado
                            , $this->idRutaEstado
                            , $this->idRutaPadre
                            , ".$this->cliente->getIdCliente()."
                            , $this->idZona
                            , $this->idRegional
                            , $this->archivo
                            , $this->jsonData
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function eliminar() {
        $sentenciaSql = "
                        DELETE FROM
                            facturacion_inventario.ruta
                        WHERE
                            id_ruta = $this->idRuta
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function obtenerMaximo() {
        $sentenciaSql = "
                        SELECT
                            MAX(id_ruta) AS maximo
                        FROM
                            facturacion_inventario.ruta
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }
    
    public function actualizarArchivo() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.ruta
                        SET
                            archivo = $this->archivo
                        WHERE
                            id_ruta = $this->idRuta
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            t.nit
                            , t.tercero
                            , CAST(NOW() AS DATE) AS fecha_inicial_visita
                            , to_char(c.hora_visita, 'HH12:MI AM') AS hora_visita
                            , c.tiempo_visita
                            , c.visita_festivos
                            , c.id_cliente
                            , c.id_periodicidad_visita
                            , c.orden
                        FROM
                            facturacion_inventario.cliente AS c
                            INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero AS t ON t.id_tercero = s.id_tercero
                        $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $fechaUltimaVisita = date('Y-m-d');
        $this->numeroRegistros = $this->conexion->obtenerNumeroRegistros();
        $arrFila = $this->conexion->obtenerObjetoCompleto();
        while ($contador < count($arrFila)) {
            $fila = $arrFila[$contador];
            $retorno[$contador]["nit"] = $fila->nit;
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["fechaInicialVisita"] = $fila->fecha_inicial_visita;
            $retorno[$contador]["horaVisita"] = $fila->hora_visita;
            $retorno[$contador]["tiempoVisita"] = $fila->tiempo_visita;
            $retorno[$contador]["orden"] = $fila->orden;
            switch ($fila->visita_festivos) {
                case 0:
                    $retorno[$contador]["visitaFestivos"] = 'NO';
                break;
                case 1:
                    $retorno[$contador]["visitaFestivos"] = 'SI';
                break;
                case 2:
                    $retorno[$contador]["visitaFestivos"] = 'SE VISITA AL SIGUIENTE DÍA';
                break;
                default :
                    $retorno[$contador]["visitaFestivos"] = '';
                break;
            }
            
            $retorno[$contador]["fechaVisita"] = $this->obtenerRutasCliente($fila->id_periodicidad_visita, $this->fechaFin, $fila->hora_visita
                                                                            , $fechaUltimaVisita, $fila->visita_festivos);
            $contador++;
        }
        return $retorno;
    }
    
    private function obtenerCondicion() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->cliente->getIdZona() != "" && $this->cliente->getIdZona() != "null" && $this->cliente->getIdZona() != null){
            $this->condicion = $this->condicion.$this->whereAnd." c.id_zona = ".$this->cliente->getIdZona();
            $this->whereAnd = " AND ";
        }
        
        if($this->cliente->getIdPeriodicidadVisita() != "" && $this->cliente->getIdPeriodicidadVisita() != "null" && $this->cliente->getIdPeriodicidadVisita() != null){
            $this->condicion = $this->condicion.$this->whereAnd." c.id_periodicidad_visita = ".$this->cliente->getIdPeriodicidadVisita();
            $this->whereAnd = " AND ";
        }
    }
    
    public function consultarBandejaEntrada() {
        $this->obtenerCondicionBandejaEntrada();
        $sentenciaSql = "
                        SELECT
                            r.id_ruta
                            , to_char(r.fecha_hora, 'YYYY-MM-DD HH12:MI AM') AS fecha_hora
                            , data_json
                            , CONCAT_WS(' - ', z.zona, re.regional) AS zona
                            , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) AS vendedor
                            , r.archivo
                        FROM
                            facturacion_inventario.ruta AS r
                            INNER JOIN facturacion_inventario.zona AS z ON z.id_zona = r.id_zona
                            INNER JOIN facturacion_inventario.regional AS re ON re.id_regional = z.id_regional
                            INNER JOIN facturacion_inventario.vendedor AS v ON v.id_zona = z.id_zona
                            INNER JOIN general.persona AS p ON p.id_persona = v.id_persona
                        $this->condicion
                        ORDER BY
                            r.fecha_hora
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idRuta"] = $fila->id_ruta;
            $retorno[$contador]["fechaHora"] = $fila->fecha_hora;
            $retorno[$contador]["zona"] = $fila->zona;
            $retorno[$contador]["vendedor"] = $fila->vendedor;
            $retorno[$contador]["archivo"] = $fila->archivo;
            
            if($fila->data_json != "" && $fila->data_json != "null" && $fila->data_json != null){
                $retorno[$contador]["dataJson"] = json_decode($fila->data_json, true);
            }else{
                $retorno[$contador]["dataJson"] = null;
            }
            
            $contador++;
        }
        return $retorno;
    }
    
    private function obtenerCondicionBandejaEntrada() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->idZona != "" && $this->idZona != "null" && $this->idZona != null){
            $this->condicion = $this->condicion.$this->whereAnd." r.id_zona = ".$this->idZona;
            $this->whereAnd = " AND ";
        }
        
        if ($this->fechaHora["inicio"] != '' && $this->fechaHora["fin"]) {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(r.fecha_hora AS DATE) BETWEEN '" . $this->fechaHora["inicio"] . "' AND '" . $this->fechaHora["fin"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fechaHora["inicio"] != '' && $this->fechaHora["fin"] == '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(r.fecha_hora AS DATE) = '" . $this->fechaHora["inicio"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fechaHora["inicio"] == '' && $this->fechaHora["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(r.fecha_hora AS DATE) = '" . $this->fechaHora["fin"] . "'";
            $this->whereAnd = ' AND ';
        }
    }
    
    public function organizarArreglo($data) {
        /*------RECONSTRUYO EL ARREGLO PARA QUE CADA FECHA QUEDE EN UNA FILA SEPARADA--------*/
        $retorno = array();
        $contadorFinal = 0;
        $contador = 0;
        while ($contador < count($data)) {
            $arrFechas = $data[$contador]["fechaVisita"];
            $contador2 = 0;
            while ($contador2 < count($arrFechas)) {
                $retorno[$contadorFinal]["nit"] = $data[$contador]["nit"];
                $retorno[$contadorFinal]["tercero"] = $data[$contador]["tercero"];
                $retorno[$contadorFinal]["fechaInicialVisita"] = $data[$contador]["fechaInicialVisita"];
                $retorno[$contadorFinal]["horaVisita"] = $data[$contador]["horaVisita"];
                $retorno[$contadorFinal]["tiempoVisita"] = $data[$contador]["tiempoVisita"];
                $retorno[$contadorFinal]["visitaFestivos"] = $data[$contador]["visitaFestivos"];
                $retorno[$contadorFinal]["orden"] = $data[$contador]["orden"];
                $retorno[$contadorFinal]["fechaVisita"] = $arrFechas[$contador2];
                $contadorFinal++;
                $contador2++;
            }
            $contador++;
        }
        /*----------------------------------------------------------------------------------*/
        
        /*----ORDENO EL ARRAY DE FORMA ASCENDENTE POR FECHA/ORDEN----*/
        $sort = array();
        foreach($retorno as $k=>$v) {
            $sort['fechaVisita'][$k] = date('Y-m-d', strtotime($v['fechaVisita']));
            $sort['orden'][$k] = $v['orden'];
        }
        array_multisort($sort['fechaVisita'], SORT_ASC, $sort['orden'], SORT_ASC, $retorno);
        /*-----------------------------------------------------*/
        
        return $retorno;
    }
/*------------------------------PROGRAMACIÓN RUTA---------------------------------------*/
    public function obtenerRutasCliente($idPeriodicidadVisita, $fechaFin, $horaVisita, $fechaUltimaVisita, $visitaFestivos){
        
        $periodicidadVisitaE = new \entidad\PeriodicidadVisita();
        $periodicidadVisitaE->setIdPeriodicidadVisita($idPeriodicidadVisita);
        $periodicidadVisitaM = new \modelo\PeriodicidadVisita($periodicidadVisitaE);
        $dias = $periodicidadVisitaM->consultar();

        $arrFechas = array();
        $fechaActual = date('Y-m-d');
        foreach ($dias as $value) {
            $fechaDiaSel = $this->buscarDiaInicial($value['idDia'], strtotime($fechaActual));
            $retornoF = $this->programarFechaRuta($fechaDiaSel, $value['idPeriodicidad'], strtotime($fechaFin), $horaVisita, $fechaUltimaVisita);
            if(count($retornoF) != 0){
                array_push($arrFechas, $retornoF);
            }

        }

        $fechas = array();
        /*VALIDACIÓN DE FESTIVOS*/
        switch ($visitaFestivos) {
            case "0"://No visita festivos
                foreach ($arrFechas as $value) {
                    foreach ($value as $fecha) {
                        $date = new \DateTime($fecha);
                        $fecha = $date->format('Y-m-d');
                        $hora = $date->format('h:i A');

                        if($this->validarFestivo($fecha) == true){
                            continue;
                        }

                        array_push($fechas, $fecha." ".$hora);
                        sort($fechas);
                    }
                }
            break;
            case "1"://Si visita festivos
                foreach ($arrFechas as $value) {
                    foreach ($value as $fecha) {
                        array_push($fechas, $fecha);
                        sort($fechas);
                    }
                }     
            break;
            case "2"://Se visita al siguiente dia
                foreach ($arrFechas as $value) {
                    foreach ($value as $fecha) {
                        array_push($fechas, $fecha);
                    }
                } 
                sort($fechas);
                for($i = 0; $i < count($fechas); $i++){
                    $date = new \DateTime($fechas[$i]);
                    $fecha = $date->format('Y-m-d');
                    $hora = $date->format('h:i A');

                    if($this->validarFestivo($fecha) == true){
                        for($j = $i; $j < count($fechas); $j++){
                            
                            if($fechas[$i] == $fechas[$j])
                                $fechas[$j] = strtotime ( '+1 day' , strtotime($fechas[$j])) ;
                            else
                                $fechas[$j] = strtotime($fechas[$j]);
                            
                            $fechas[$j] = date("Y-m-d", $fechas[$j]);
                            $fechas[$j] = $fechas[$j]." ".$hora;
                        }

                        $fecha = strtotime ( '+1 day' , strtotime($fecha)) ;
                        $fecha = date("Y-m-d", $fecha);
                        if($this->validarFestivo($fecha) == true){
                            for($j = $i; $j < count($fechas); $j++){
                                
                                $fechaEvaluar = strtotime ($fechas[$j]) ;
                                $fechaEvaluar = date("Y-m-d", $fechaEvaluar);
                                
                                if($fecha == $fechaEvaluar)
                                    $fechas[$j] = strtotime ( '+1 day' , strtotime($fechas[$j])) ;
                                else
                                    $fechas[$j] = strtotime($fechas[$j]);
                                
                                $fechas[$j] = strtotime ( '+1 day' , strtotime($fechas[$j])) ;
                                $fechas[$j] = date("Y-m-d", $fechas[$j]);
                                $fechas[$j] = $fechas[$j]." ".$hora;
                            }   
                        }
                    }
                }

            break;
        }
        return $fechas; 
    }
    
    public function buscarDiaInicial($diaSel,$fechaInicio){

        $fechaSel = null;
        $bandera = false;
        $fechaInicioBuscarDia = date('d-m-Y',$fechaInicio);//fecha inicio
        $dia = date('w',strtotime($fechaInicioBuscarDia));//obtengo día 0-6
       
        // busco el día seleccionado
        while($bandera == false){
            if($diaSel == $dia){
                $fechaSel = $fechaInicioBuscarDia;//fecha que coincide con el día seleccionado.
                $this->fechaSelecDiaUnix = strtotime($fechaSel); //se convierte a tiempo unix.
                $bandera = true;
            }else{
                $fechaInicioBuscarDia = date("Y-m-d",strtotime($fechaInicioBuscarDia)+86400);
                $dia = date('w', strtotime($fechaInicioBuscarDia));
                $contador++;
            }
        }
        return $this->fechaSelecDiaUnix;
    }
    
    public function programarFechaRuta($fechaDiaSel, $idPeriodicidad, $fechaFin, $horaVisita, $fechaUltimaVisita){
        $periodicidad = $this->periocidad($idPeriodicidad);
        $diaFechaUnix = $fechaDiaSel;
        $fechaFormatoNormal = date('Y-m-d', $fechaDiaSel);
        $fechaFinNormal = date('Y-m-d', $fechaFin);
        $fechasRuta = array();
        $contador = 1;
        $fechaFinalUnix = strtotime($fechaUltimaVisita);        
        
        if($fechaDiaSel != null){
            if(($diaFechaUnix==$fechaFin && $diaFechaUnix!=$fechaFinalUnix) || ($diaFechaUnix <= $fechaFin)){
                $fecha = date('Y-m-d', $diaFechaUnix);
                $fecha .= " ".$horaVisita;
                array_push($fechasRuta, $fecha);
                $diaFechaUnixNormal = date('Y-m-d',$diaFechaUnix);
            }
            while($diaFechaUnix <= $fechaFin){
                $diaFechaUnix = $fechaDiaSel + ($periodicidad * $contador);//obtiene el unix del día
                $diaFechaUnixNormal = date('Y-m-d', $diaFechaUnix);
                $fechaFinNormal = date('Y-m-d', $diaFechaUnix);
                
                if($diaFechaUnix <= $fechaFin){//valido que la fecha actual no traspase la final
                    $fecha = date('Y-m-d', $diaFechaUnix);
                    $fecha .= " ".$horaVisita;
                   array_push($fechasRuta, $fecha);
                   $contador++;
                }
            }
        }
        return $fechasRuta;
    }
    public function periocidad($id){
        switch ($id) {
            case 1:
                $this->periodicidad = 604800;
                break;
            case 2:
                $this->periodicidad = 1209600;
                break;
            default:
                $this->periodicidad =  2419200;
                break;
        }
        return $this->periodicidad;
    }
    public function validarFestivo($dia) {
        $dia = strtotime($dia);
        $festivos = $this->consultarFestivos();
        if($festivos != null){
            foreach ($festivos as $value) {
                $festivoUnix = strtotime($value);
                if($dia == $festivoUnix)
                    return true;
            }
        }
        return false;
    }
    public function consultarFestivos() {
        $sentenciaSql = "
                        SELECT
                            festivo
                        FROM
                            general.festivo
                        WHERE
                            estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador] = $fila->festivo;
            $contador++;
        }
        return $retorno;
    }
}
