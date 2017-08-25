<?php
session_start();
require '../modelo/ClienteServicio.php';
require '../entidad/ClienteServicioArchivo.php';
require '../modelo/ClienteServicioArchivo.php';

try{
    $retorno = array("exito"=>1,"mensaje"=>"");
    $idClienteServicio = $_POST["idClienteServicio"];
    $idOrdenTrabajo = $_POST["idOrdenTrabajo"];
    $idOrdenTrabajoCliente = $_POST["idOrdenTrabajoCliente"];
    $informacionArchivos = $_POST["informacionArchivos"];
    $idUsuario = $_SESSION["idUsuario"];
    
    if($informacionArchivos != "" && $informacionArchivos != null){
        
        foreach($informacionArchivos as $archivo){
            
            if($archivo["idClienteServicioArchivo"] == "" || $archivo["idClienteServicioArchivo"] == null || $archivo["idClienteServicioArchivo"] == "null"){
                $ordenTrabajoClienteE  = new \entidad\OrdenTrabajoCliente();
                $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);

                $clienteServicioE = new \entidad\ClienteServicio();
                $clienteServicioE->setIdClienteServicio($idClienteServicio);

                $clienteServicioM = new \modelo\ClientServicio($clienteServicioE);


                $urlArchivo = $archivo["rutaArchivo"];
                $fecha = date("YmdHis");
                $extension = substr(strrchr($urlArchivo, "."), 1);

                $rutaFolder = "../../archivos/PublicServices/clienteServicioArchivo";

                //Creamos la ruta si no existe
                if (!file_exists($rutaFolder)) {
                    mkdir($rutaFolder, 0777, true);
                }

                $rutaNueva = $rutaFolder."/Servicio_".$idClienteServicio."_".$fecha.".".$extension;

                $clienteServicioM->renombrarArchivo($urlArchivo,$rutaNueva);

                $clienteServicioArchivoE = new \entidad\ClienteServicioArchivo();
                $clienteServicioArchivoE->setIdClienteServicioArchivo($archivo["idClienteServicioArchivo"]);
                $clienteServicioArchivoE->setClienteServicio($clienteServicioE);
                $clienteServicioArchivoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
                $clienteServicioArchivoE->setRutaArchivo($rutaNueva);
                $clienteServicioArchivoE->setEtiqueta($archivo["etiqueta"]);
                $clienteServicioArchivoE->setObservacion($archivo["observacion"]);
                $clienteServicioArchivoE->setEstado("TRUE");
                $clienteServicioArchivoE->setIdUsuarioCreacion($idUsuario);
                $clienteServicioArchivoE->setIdUsuarioModificacion($idUsuario);

                $clienteServicioArchivoM = new \modelo\ClienteServicioArchivo($clienteServicioArchivoE);
                $clienteServicioArchivoM->adicionar();
            }
        }
    }
}catch(Exception $e){
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $e->getMessage();
}
echo json_encode($retorno);