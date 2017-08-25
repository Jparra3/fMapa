    <?php
session_start();
require_once '../entidad/Pago.php';
require_once '../modelo/Pago.php';
require_once '../entidad/Credito.php';

    $contadorAplicacion = 0;
    while($contadorAplicacion<  count($arrDataParametros)){
        $idCredito = $arrDataParametros[$contadorAplicacion]->idCredito;
        $tipoPago = $arrDataParametros[$contadorAplicacion]->tipoPago;
        $valorPago = str_replace(".", "", $arrDataParametros[$contadorAplicacion]->valorConcepto);
        $idUsuario = $_SESSION["idUsuario"];
        
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
        
        $contadorAplicacion++;
    }
    
?>