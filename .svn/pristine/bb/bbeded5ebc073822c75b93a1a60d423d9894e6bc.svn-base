<?php
require_once '../entidad/SolicitudConcepto.php';
require_once '../modelo/SolicitudConcepto.php';
require_once '../entidad/Pago.php';
require_once '../modelo/Pago.php';
require_once '../entidad/Credito.php';
require_once '../modelo/Credito.php';
require_once '../entidad/Solicitud.php';
require_once '../modelo/Solicitud.php';
require_once '../entidad/EstadoConceptoCredito.php';
require_once '../modelo/EstadoConceptoCredito.php';

$contadorAplicacionConcepto = 0;
while ($contadorAplicacionConcepto<  count($arrDataParametros)){
    $idSolicitud = $arrDataParametros[$contadorAplicacionConcepto]->idSolicitud;
    $tipoPago = $arrDataParametros[$contadorAplicacionConcepto]->tipoPago;
    $valorPago = str_replace(".", "", $arrDataParametros[$contadorAplicacionConcepto]->valorConcepto);
    $idUsuario = $_SESSION["idUsuario"];
    
    //Obtenemos idCredito
    $solicitudE = new \entidad\Solicitud();
    $solicitudE ->setIdSolicitud($idSolicitud);
    $solicitudM = new \modelo\Solicitud($solicitudE);
    
    //Consultamos y traemos idCredito
    $creditoE = new \entidad\Credito();
    $creditoE -> setSolicitud($solicitudE);
    $creditoM = new \modelo\Credito($creditoE);
    $arrCredito = $creditoM -> consultar();
    
    $idCredito = $arrCredito[0]["idCredito"];
    
    //Obtener id_estado_concepto_credito
    $estadoConceptoCreditoE = new \entidad\EstadoConceptoCredito();
    $estadoConceptoCreditoE -> setCodigo("PRC");
    $estadoConceptoCreditoM = new \modelo\EstadoConceptoCredito($estadoConceptoCreditoE);
    $arrEstadoConceptoCredito = $estadoConceptoCreditoM -> consultar();
    
    $idEstadoConceptoCredito = $arrEstadoConceptoCredito[0]["idEstadoConceptoCredito"];
    $estadoConceptoCreditoE = new \entidad\EstadoConceptoCredito();
    $estadoConceptoCreditoE ->setIdEstadoConceptoCredito($idEstadoConceptoCredito);
    
    //Instaciamos solicitud concepto para actualizar estado
    $solicitudConceptoE = new \entidad\SolicitudConcepto();
    $solicitudConceptoM = new \modelo\SolicitudConcepto($solicitudConceptoE);
    $solicitudConceptoM ->actualizarEstadoSolicitudConcepto($idSolicitud, $idEstadoConceptoCredito);
    
    //Intanciamos credito
    $creditoE = new \entidad\Credito();
    $creditoE ->setIdCredito($idCredito);

    //Intanciamos Pago
    $pagoE = new \entidad\Pago();
    $pagoE -> setCredito($creditoE);
    $pagoE ->setValor($valorPago);
    $pagoE ->setTipoPago($tipoPago);
    $pagoE ->setIdUsuarioCreacion($idUsuario); 

    //Modelo de pago
    $pagoM = new \modelo\Pago($pagoE);
    $idPago = $pagoM ->aplicarPagoCredito();
    $contadorAplicacionConcepto++;
}
?>