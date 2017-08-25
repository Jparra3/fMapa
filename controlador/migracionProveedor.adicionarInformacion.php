<?php

session_start();
set_time_limit(0);
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
require_once '../entidad/Proveedor.php';
require_once '../modelo/Proveedor.php';
require_once '../entidad/Zona.php';
require_once '../modelo/Zona.php';
require_once '../../Seguridad/entidad/Departamento.php';
require_once '../../Seguridad/modelo/Departamento.php';
require_once '../entidad/Regional.php';
require_once '../../Seguridad/entidad/Municipio.php';
require_once '../../Seguridad/modelo/Municipio.php';
$retorno = array("exito" => 1, "mensaje" => "Se registró la información correctamente", "data" => null);
try {
    $idUsuario = $_SESSION['idUsuario'];
    $arrInformacion = json_decode($_POST['arrInformacion']);

    $regional = "SIN DEFINIR";
    $zona = "SIN DEFINIR";
    $municipioEvaluar = "SIN DEFINIR";

    $idRegionalDefecto = validarExistenciaRegional($regional);
    $idZonaDefecto = validarExistenciaZona($zona, $idRegional);
    $idMunicipioDefecto = validarExistenciaMunicipio($municipioEvaluar);


    for ($i = 0; $i < count($arrInformacion); $i++) {

        //Tercero
        $arrTercero = validarExistenciaTercero($arrInformacion[$i]->nit);
        if ($arrTercero != null) {
            $terceroE = new \entidad\Tercero();
            $terceroE->setIdTercero($arrTercero[0]['idTercero']);
            $idTercero = $arrTercero[0]['idTercero'];
        } else {
            $terceroE = new \entidad\Tercero();
            $terceroE->setNit($arrInformacion[$i]->nit);
            $tercero = $arrInformacion[$i]->tercero;
            $terceroE->setTercero($tercero);
            $terceroE->setLogo("");
            $terceroE->setDigitoVerificacion(1);
            $terceroE->setIdTipoRegimen(4);
            $terceroE->setIdEmpresa(1);
            $terceroE->setIdUsuarioCreacion($idUsuario);
            $terceroE->setIdUsuarioModificacion($idUsuario);
            $terceroE->setEstado("TRUE");

            $terceroM = new \modelo\Tercero($terceroE);
            $terceroM->adicionar();
            $idTercero = $terceroM->obtenerMaximo();
        }

        //Tercero direccion
        if ($arrTercero != null && $arrTercero[0]['idTerceroDireccion'] != null && $arrTercero[0]['idTerceroDireccion'] != "null" && $arrTercero[0]['idTerceroDireccion'] != "") {
            
        } else {
            $municipioEvaluar = $arrInformacion[$i]->municipio;

            if ($municipioEvaluar != "" && $municipioEvaluar != null && $municipioEvaluar != "null") {
                $idMunicipio = validarExistenciaMunicipio($municipioEvaluar);
                if($idMunicipio == null){
                    $idMunicipio = $idMunicipioDefecto;
                }
            } else {
                $idMunicipio = $idMunicipioDefecto;
            }

            $terceroDireccionE = new \entidad\TerceroDireccion();
            $terceroDireccionE->setIdTercero($idTercero);
            $terceroDireccionE->setIdMunicipio($idMunicipio); //sin definir
            if ($arrInformacion[$i]->direccion == '') {
                $terceroDireccionE->setTerceroDireccion("SIN DEFENIR");
            } else {
                $terceroDireccionE->setTerceroDireccion($arrInformacion[$i]->direccion);
            }

            $terceroDireccionE->setEstado("'TRUE'");
            $terceroDireccionE->setPrincipal("true");

            $terceroDireccionM = new \modelo\TerceroDireccion($terceroDireccionE);
            $idTerceroDireccion = $terceroDireccionM->adicionar();
        }

        //Tercero telefono
        if ($arrTercero != null && $arrTercero[0]['idTerceroTelefono'] != null && $arrTercero[0]['idTerceroTelefono'] != "null" && $arrTercero[0]['idTerceroTelefono'] != "") {
            
        } else {
            $idTerceroTelefono = "";
            for ($j = 0; $j < 2; $j++) {

                if ($j == 0) {
                    if ($arrInformacion[$i]->celular == "" || $arrInformacion[$i]->celular == null || $arrInformacion[$i]->celular == "null" || $arrInformacion[$i]->celular == "0") {
                        continue;
                    }
                }

                $terceroTelefonoE = new \entidad\TerceroTelefono();
                $terceroTelefonoE->setIdTercero($idTercero);
                if ($j == 0) {
                    $terceroTelefonoE->setIdTipoTelefono(1); //CELULAR
                    $terceroTelefonoE->setTerceroTelefono($arrInformacion[$i]->celular);
                    $terceroTelefonoE->setPrincipal("true");
                } else {
                    $terceroTelefonoE->setIdTipoTelefono(2); //TELEFONO
                    $terceroTelefonoE->setTerceroTelefono($arrInformacion[$i]->telefono);

                    if ($idTerceroTelefono == "" || $idTerceroTelefono == null || $idTerceroTelefono == "null") {
                        $terceroTelefonoE->setPrincipal("true");
                    } else {
                        $terceroTelefonoE->setPrincipal("false");
                    }
                }
                $terceroTelefonoE->setContacto($tercero);
                $terceroTelefonoE->setEstado("TRUE");

                $terceroTelefonoM = new \modelo\TerceroTelefono($terceroTelefonoE);

                if ($j == 0) {
                    if ($arrInformacion[$i]->celular != '') {
                        $idTerceroTelefono = $terceroTelefonoM->adicionar();
                    }
                } else {
                    if ($idTerceroTelefono == "" || $idTerceroTelefono == null || $idTerceroTelefono == "null") {
                        $idTerceroTelefono = $terceroTelefonoM->adicionar();
                    } else {
                        if ($arrInformacion[$i]->telefono != '') {
                            $terceroTelefonoM->adicionar();
                        }
                    }
                }
            }
        }

        if ($arrTercero != null && $arrTercero[0]['idTerceroEmail'] != null && $arrTercero[0]['idTerceroEmail'] != "null" && $arrTercero[0]['idTerceroEmail'] != "") {
            
        } else {
            //Tercero email
            $terceroEmailE = new \entidad\TerceroEmail();
            $terceroEmailE->setIdTercero($idTercero);
            if ($arrInformacion[$i]->email == '') {
                $terceroEmailE->setTerceroEmail("sincorreo@gmail.com");
            } else {
                $terceroEmailE->setTerceroEmail($arrInformacion[$i]->email);
            }

            $terceroEmailE->setEstado("TRUE");
            $terceroEmailE->setPrincipal("true");

            $terceroEmailM = new \modelo\TerceroEmail($terceroEmailE);
            $idEmail = $terceroEmailM->adicionar();
        }

        //Se valida la existencia de la sucursal y proveedor
        if ($arrTercero != null) {
            
        } else {
            //Tercero sucursal
            $sucursalE = new \entidad\Sucursal();
            $sucursalE->setIdTercero($idTercero);
            $sucursalE->setSucursal('PRINCIPAL');
            $sucursalE->setIdTerceroDireccion($idTerceroDireccion);
            $sucursalE->setIdTerceroTelefono($idTerceroTelefono);
            $sucursalE->setIdTerceroMail($idEmail);
            $sucursalE->setIdUsuarioCreacion($idUsuario);
            $sucursalE->setIdUsuarioModificacion($idUsuario);
            $sucursalE->setEstado("TRUE");
            $sucursalE->setPrincipal("TRUE");

            $sucursalM = new \modelo\Sucursal($sucursalE);
            $idSucursal = $sucursalM->adicionar();
            $sucursalE->setIdSucursal($idSucursal);


            //Cliente
            $regional = trim($arrInformacion[$i]->departamento);
            $zona = trim($arrInformacion[$i]->zona);
            if ($regional != "" && $regional != null && $regional != "null") {
                $idRegional = validarExistenciaRegional($regional);
            } else {
                $idRegional = $idRegionalDefecto;
            }

            if ($zona != "" && $zona != null && $zona != "null") {
                $idZona = validarExistenciaZona($zona, $idRegional);
            } else {
                $idZona = $idZonaDefecto;
            }

            $proveedorE = new \entidad\Proveedor();
            $proveedorE->setSucursal($sucursalE);
            $proveedorE->setIdProveedorEstado(1);
            //$proveedorE->setIdZona($idZona);
            $proveedorE->setIdUsuarioCreacion($idUsuario);
            $proveedorE->setIdUsuarioModificacion($idUsuario);

            $clienteM = new \modelo\Proveedor($proveedorE);
            $idProveedor = $clienteM->adicionar();
        }
    }
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}

/* * *********************** Validar existencia tercero ********************** */

function validarExistenciaTercero($nit) {
    $terceroE = new \entidad\Tercero();
    $terceroE->setNit($nit);

    $terceroM = new \modelo\Tercero($terceroE);
    $arrTercero = $terceroM->consultar();
    if (count($arrTercero) > 0) {
        return $arrTercero;
    } else {
        return null;
    }
}

/* * ************** Validar existencia regional ***************** */

function validarExistenciaRegional($regional) {
    $idUsuario = $_SESSION['idUsuario'];
    $departamentoE = new \entidad\Departamento();
    $departamentoE->setDepartamento($regional);

    $departamentoM = new \modelo\Departamento($departamentoE);
    $arrDepartamento = $departamentoM->consultar();
    if (count($arrDepartamento) > 0) {
        $regionalE = new \entidad\Regional();
        $regionalE->setRegional($regional);

        $regionalM = new \modelo\Regional($regionalE);
        $arrRegional = $regionalM->consultar();
        if (count($arrRegional) <= 0) {
            $regionalE->setRegional($regional);
            $regionalE->setIdUsuarioCreacion($idUsuario);
            $regionalE->setIdUsuarioModificacion($idUsuario);

            $regionalM = new \modelo\Regional($regionalE);
            $idRegional = $regionalM->adicionar();
        } else {
            $idRegional = $arrRegional[0]['idRegional'];
        }
        return $idRegional;
    } else {
        return null;
    }
}

/* * ************** Validar existencia zona ********************* */

function validarExistenciaZona($zona, $idRegional) {
    $idUsuario = $_SESSION['idUsuario'];
    $zonaE = new \entidad\Zona();
    $zonaE->setZona($zona);
    $zonaE->setIdRegional($idRegional);

    $zonaM = new \modelo\Zona($zonaE);
    $arrZona = $zonaM->consultar();
    if (count($arrZona) > 0) {
        return $arrZona[0]['idZona'];
    } else {
        $zonaE = new \entidad\Zona();
        $zonaE->setZona($zona);
        $zonaE->setIdRegional($idRegional);
        $zonaE->setIdUsuarioCreacion($idUsuario);
        $zonaE->setIdUsuarioModificacion($idUsuario);

        $zonaM = new \modelo\Zona($zonaE);
        $idZona = $zonaM->adiconar();
        return $idZona;
    }
}

/* * ******************Validar existencia municipio ************************ */

function validarExistenciaMunicipio($municipio) {
    $municipioE = new \entidad\Municipio();
    $municipioE->setMunicipio($municipio);

    $municipioM = new \modelo\Municipio($municipioE);
    $arrMunicipio = $municipioM->consultar();
    if (count($arrMunicipio) > 0) {
        return $arrMunicipio[0]['idMunicipio'];
    } else {
        return null;
    }
}

echo json_encode($retorno);
?>

