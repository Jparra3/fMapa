<?php

session_start();
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../../Seguridad/modelo/Tercero.php';
require_once '../../Seguridad/entidad/Sucursal.php';
require_once '../../Seguridad/modelo/Sucursal.php';
require_once '../../Seguridad/entidad/TerceroDireccion.php';
require_once '../../Seguridad/modelo/TerceroDireccion.php';
require_once '../../Seguridad/entidad/TerceroTelefono.php';
require_once '../../Seguridad/modelo/TerceroTelefono.php';
require_once '../../Seguridad/entidad/TerceroEmail.php';
require_once '../../Seguridad/modelo/TerceroEmail.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
$retorno = array('exito' => 1, 'mensaje' => 'Se adicionó la información correctamente', 'idClientePrincipal' => '');
try {
    $tercero = $_POST['tercero'];
    $digitoVerificacion = $_POST['digitoVerificacion'];
    if($digitoVerificacion == "" || $digitoVerificacion == "null" || $digitoVerificacion == null){
        $digitoVerificacion = 0;
    }
    $nit = $_POST['nit'];
    $idTipoRegimen = $_POST['idTipoRegimen'];
    $idEmpresa = $_POST['idEmpresa'];
    $estado = $_POST['estado'];
    $idUsuario = $_SESSION['idUsuario'];
    $arrDireccion = json_decode($_POST["arrDireccion"]);
    $arrTelefono = json_decode($_POST["arrTelefono"]);
    $arrEmail = json_decode($_POST["arrEmail"]);
    $arrSucursal = json_decode($_POST['arrSucursal']);
    $idTercero = $_POST['idTercero'];

    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($idTercero);
    $terceroE->setNit($nit);
    $terceroE->setTercero($tercero);
    $terceroE->setLogo($logo);
    $terceroE->setDigitoVerificacion($digitoVerificacion);
    $terceroE->setIdTipoRegimen($idTipoRegimen);
    $terceroE->setIdEmpresa($idEmpresa);
    $terceroE->setIdUsuarioCreacion($idUsuario);
    $terceroE->setIdUsuarioModificacion($idUsuario);
    $terceroE->setEstado($estado);


    $terceroM = new \modelo\Tercero($terceroE);	
    if ($idTercero != null && $idTercero != 'null' && $idTercero != "") {
        $terceroM->modificar();
    } else {
        $terceroM->adicionar();
        $idTercero = $terceroM->obtenerMaximo();
    }	
    for ($i = 0; $i < count($arrDireccion); $i++) {
        $terceroDireccionE = new \entidad\TerceroDireccion();
        $idTerceroDireccion = substr($arrDireccion[$i]->idTerceroDireccion, 5);
        $terceroDireccionE->setIdTerceroDireccion($idTerceroDireccion);
        $terceroDireccionE->setIdTercero($idTercero);
        $terceroDireccionE->setIdMunicipio($arrDireccion[$i]->idMunicipio);
        $terceroDireccionE->setTerceroDireccion($arrDireccion[$i]->direccion);
        $terceroDireccionE->setEstado("'" . $arrDireccion[$i]->estado . "'");

        if ($arrDireccion[$i]->principal == "true") {
            $terceroDireccionE->setPrincipal("true");
        } else {
            $terceroDireccionE->setPrincipal("false");
        }
        //$terceroDireccionE->setIdUsuarioCreacion($idUsuarioCreacion);
        //$terceroDireccionE->setIdUsuarioModificacion($idUsuarioModificacion);
        $terceroDireccionM = new \modelo\TerceroDireccion($terceroDireccionE);
        if ($arrDireccion[$i]->idTerceroDireccion != null && $arrDireccion[$i]->idTerceroDireccion != 'null' && $arrDireccion[$i]->idTerceroDireccion != "") {
            $terceroDireccionM->modificar();
            $arrDireccion[$i]->idTerceroDireccion = $idTerceroDireccion;
        } else {
            $arrDireccion[$i]->idTerceroDireccion = $terceroDireccionM->adicionar();
        }
    }

    for ($i = 0; $i < count($arrTelefono); $i++) {
        $terceroTelefonoE = new \entidad\TerceroTelefono();
        $idTerceroTelefono = substr($arrTelefono[$i]->idTelefono, 5);
        $terceroTelefonoE->setIdTerceroTelefono($idTerceroTelefono);
        $terceroTelefonoE->setIdTercero($idTercero);
        $terceroTelefonoE->setIdTipoTelefono($arrTelefono[$i]->idTipoTelefono);
        $terceroTelefonoE->setTerceroTelefono($arrTelefono[$i]->numeroTelefono);
        $terceroTelefonoE->setContacto($arrTelefono[$i]->contacto);
        $terceroTelefonoE->setEstado($arrTelefono[$i]->estado);

        if ($arrTelefono[$i]->principal == "true") {
            $terceroTelefonoE->setPrincipal("true");
        } else {
            $terceroTelefonoE->setPrincipal("false");
        }
        //$terceroTelefonoE->setIdUsuarioCreacion($idUsuarioCreacion);
        //$terceroTelefonoE->setIdUsuarioModificacion($idUsuarioModificacion);
        $terceroTelefonoM = new \modelo\TerceroTelefono($terceroTelefonoE);
        if ($arrTelefono[$i]->idTelefono != null && $arrTelefono[$i]->idTelefono != 'null' && $arrTelefono[$i]->idTelefono != '') {
            $terceroTelefonoM->modificar();
            $arrTelefono[$i]->idTelefono = $idTerceroTelefono;
        } else {
            $arrTelefono[$i]->idTelefono = $terceroTelefonoM->adicionar();
        }
    }

    for ($i = 0; $i < count($arrEmail); $i++) {
        $terceroEmailE = new \entidad\TerceroEmail();
        $idTerceroEmail = substr($arrEmail[$i]->idEmail, 7);
        $terceroEmailE->setIdTerceroEmail($idTerceroEmail);
        $terceroEmailE->setIdTercero($idTercero);
        $terceroEmailE->setTerceroEmail($arrEmail[$i]->email);
        $terceroEmailE->setEstado($arrEmail[$i]->estado);

        if ($arrEmail[$i]->principal == "true") {
            $terceroEmailE->setPrincipal("true");
        } else {
            $terceroEmailE->setPrincipal("false");
        }
        //$terceroEmailE->setIdUsuarioCreacion($idUsuarioCreacion);
        //$terceroEmailE->setIdUsuarioModificacion($idUsuarioModificacion);

        $terceroEmailM = new \modelo\TerceroEmail($terceroEmailE);
        if ($arrEmail[$i]->idEmail != null && $arrEmail[$i]->idEmail != "null" && $arrEmail[$i]->idEmail != "") {
            $terceroEmailM->modificar();
            $arrEmail[$i]->idEmail = $idTerceroEmail;
        } else {
            $arrEmail[$i]->idEmail = $terceroEmailM->adicionar();
        }
    }
    for ($i = 0; $i < count($arrSucursal); $i++) {
        $sucursalE = new \entidad\Sucursal();
        $sucursalE->setIdSucursal($arrSucursal[$i]->idSucursal);
        $sucursalE->setIdTercero($idTercero);
        $sucursalE->setSucursal($arrSucursal[$i]->sucursal);
        
        $idTerceroDireccion = substr($arrSucursal[$i]->idDireccion, 5);
        if ($idTerceroDireccion != "" && $idTerceroDireccion != "null" && $idTerceroDireccion != null) {
            $sucursalE->setIdTerceroDireccion($idTerceroDireccion);
        } else {
            $itemDireccion = $arrSucursal[$i]->itemDireccion;
            for ($j = 0; $j < count($arrDireccion); $j++) {
                if ($arrDireccion[$j]->idArtificial == $itemDireccion) {
                    $idDireccionSucursal = $arrDireccion[$j]->idTerceroDireccion;
                }
            }
            $sucursalE->setIdTerceroDireccion($idDireccionSucursal);
        }

        $idTerceroTelefono = substr($arrSucursal[$i]->idTelefono, 5);
        if ($idTerceroTelefono != "" && $idTerceroTelefono != "null" && $idTerceroTelefono != null) {
            $sucursalE->setIdTerceroTelefono($idTerceroTelefono);
        } else {
            $itemTelefono = $arrSucursal[$i]->itemTelefono;
            for ($j = 0; $j < count($arrTelefono); $j++) {
                if ($arrTelefono[$j]->idArtificial == $itemTelefono) {
                    $idTelefonoSucursal = $arrTelefono[$j]->idTelefono;
                }
            }
            $sucursalE->setIdTerceroTelefono($idTelefonoSucursal);
        }

        $idTerceroEmail = substr($arrSucursal[$i]->idEmail, 7);
        if ($idTerceroEmail != "" && $idTerceroEmail != "null" && $idTerceroEmail != null) {
            $sucursalE->setIdTerceroMail($idTerceroEmail);
        } else {
            $itemEmail = $arrSucursal[$i]->itemEmail;
            for ($j = 0; $j < count($arrEmail); $j++) {
                if ($arrEmail[$j]->idArtificial == $itemEmail) {
                    $idEmailSucursal = $arrEmail[$j]->idEmail;
                }
            }
            $sucursalE->setIdTerceroMail($idEmailSucursal);
        }
        
        $sucursalE->setIdUsuarioCreacion($idUsuario);
        $sucursalE->setIdUsuarioModificacion($idUsuario);
        $sucursalE->setEstado($arrSucursal[$i]->estado);
        $sucursalE->setPrincipal($arrSucursal[$i]->principal);

        $sucursalM = new \modelo\Sucursal($sucursalE);
        if ($arrSucursal[$i]->idSucursal != null && $arrSucursal[$i]->idSucursal != "null" && $arrSucursal[$i]->idSucursal != "") {
            $sucursalM->modificar();
            $idSucursal = $arrSucursal[$i]->idSucursal;
            if($arrSucursal[$i]->principal == true || $arrSucursal[$i]->principal == "true" || $arrSucursal[$i]->principal == "TRUE"){
                $retorno['idClientePrincipal'] = $arrSucursal[$i]->idCliente;
            }
            
            if(($arrSucursal[$i]->idCliente == "" || $arrSucursal[$i]->idCliente == null || $arrSucursal[$i]->idCliente == "null") && $arrSucursal[$i]->estado != "false" ){				
                $idSucursal = $arrSucursal[$i]->idSucursal;
                $sucursalE->setIdSucursal($idSucursal);
                $clienteE = new \entidad\Cliente();
                $clienteE->setSucursal($sucursalE);
                $clienteE->setIdClienteEstado(1);
                $clienteE->setIdZona($arrSucursal[$i]->idZona);
                $clienteE->setLatitud($arrSucursal[$i]->latitud);
                $clienteE->setLongitud($arrSucursal[$i]->longitud);
                $clienteE->setIdUsuarioCreacion($idUsuario);
                $clienteE->setIdUsuarioModificacion($idUsuario);

                $clienteM = new \modelo\Cliente($clienteE);
                $idCliente = $clienteM->adicionar();
                if($arrSucursal[$i]->principal == true || $arrSucursal[$i]->principal == "true" || $arrSucursal[$i]->principal == "TRUE"){
                    $retorno['idClientePrincipal'] = $idCliente;
                    $idCliente = null;
                }else{
                    $idCliente = null;
                }
            }
        } else {
            $idSucursal = $sucursalM->adicionar();
            $sucursalE->setIdSucursal($idSucursal);
            $clienteE = new \entidad\Cliente();
            $clienteE->setSucursal($sucursalE);
            $clienteE->setIdClienteEstado(1);
            $clienteE->setIdZona($arrSucursal[$i]->idZona);
            $clienteE->setLatitud($arrSucursal[$i]->latitud);
            $clienteE->setLongitud($arrSucursal[$i]->longitud);
            $clienteE->setIdUsuarioCreacion($idUsuario);
            $clienteE->setIdUsuarioModificacion($idUsuario);

            $clienteM = new \modelo\Cliente($clienteE);
            $idCliente = $clienteM->adicionar();
            if($arrSucursal[$i]->principal == true || $arrSucursal[$i]->principal == "true" || $arrSucursal[$i]->principal == "TRUE"){
                $retorno['idClientePrincipal'] = $idCliente;
                $idCliente = null;
            }else{
                $idCliente = null;
            }
        }
    }	
} catch (Exception $ex) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $ex->getMessage();
}
echo json_encode($retorno);
?>