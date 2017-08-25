<?php
require_once '../entorno/configuracion.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';

require_once '../entidad/OrdenTrabajoCliente.php';


require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';

try {
    
 //display errors should be off in the php.ini file
    ini_set('display_errors', 0);
    
    /*$idOrdenTrabajoCliente = $_REQUEST["idOrdenTrabajoCliente"];
    $idOrdenTrabajo = $_REQUEST["idOrdenTrabajo"];*/
    
    $idOrdenTrabajoCliente = $_REQUEST["idOrdenTrabajoCliente"];
    $idOrdenTrabajo = $_REQUEST["idOrdenTrabajo"];
    
    /*if($idOrdenTrabajo != "" && $idOrdenTrabajo != null){
        $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
        
        $ordenTrabajoClienteM =  new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
        $idOrdenTrabajoCliente = $ordenTrabajoClienteM->consultarIdOrdenTrabajoCliente($idOrdenTrabajo);            
    }
      /*$PHPJasperXML = new PHPJasperXML();

    $PHPJasperXML->arrayParameter=array("idOrdenTrabajoCliente"=>$idOrdenTrabajoCliente);

    $nombreArchivo = "OrdenServicio_".$idOrdenTrabajo."_".$idOrdenTrabajoCliente.".pdf";
    $PHPJasperXML->load_xml_file("../reportes/ordenServicioXCliente.jrxml");

    $ruta = "../../archivos/PublicServices/ordenServicio";
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
    
    $fechaActual = date("Y-m-d");
    $rutaFinal = "../../archivos/PublicServices/ordenServicio/".$nombreArchivo;*/
     
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $arrOrdenTrabajo = $ordenTrabajoM->consultarOrdenServicio();
    
    $idOrdenTrabajoCliente = $arrOrdenTrabajo['idOrdenTrabajoCliente'];
    
    $ordenTrabajoClienteE =new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    
    $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
    $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE); 
    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    $arrOrdenServicioEquipos = $ordenTrabajoProductoM->consultarOrdenServicioEquiposAInstalar();
   
    $saltoPagina = '<div class="saltopagina"></div>';
    $style = '
        <style type="text/css">
		.tablaDatos{
			
			border-collapse: collapse;
			
		}
		.titulo{
			text-align: center;
			font-weight: bold;
		}
		#logoHeader{
			width:20%;
		}
		.celdasBorde{
			border: 1px solid; border-color: #000;
		}
		
		.bordeAbajo{
			border-bottom: 2px solid; 
			border-color: #000;
		}
		.negrilla{
			font-weight: bold;
			font-size: 12px;

		}
		.centrar{
                    text-align: center;
		}
		.derecha{
                    text-align: right;
		}
		.tamanoK{
                    font-size: 12px;
                    background-color:  #f0f0f0;
		}
		.bordeSimple{
                    border: 1px solid; border-color: #000;
		}
		.tituloServicios{
                    background-color: #009999;
                    font-weight: bold;
                    color:#fff;
                    text-align: center;
		}
		.tamnoDatosOrdenServicio td{
                    font-size: 11px;
                    padding-left: 5px;
		}
		.redesSociales{
			font-size: 10px;
		}
	</style>';
    
    $header = '<table class="tablaDatos" style="width: 100%">
            <tr>
            <td colspan="2" rowspan="3"><img src="../imagenes/logo2.png" alt="" width="200px"></td>
            <td colspan="8" class="titulo" style="font-size: 16px">
            	ORDEN DE SERVICIO
            </td>
            </tr>
            <tr>
            	<td colspan="6"></td>
            	<td colspan="3" class="centrar">ORDEN SERVICIO</td>
            </tr>
            <tr>
            	<td colspan="6"></td>
            	<td colspan="3" class="centrar celdasBorde">'.$arrOrdenTrabajo['numero'].'</td>
            </tr>
            <tr>
            	<td colspan="2" width="10%" class="negrilla derecha">SERVICIO:</td>
            	<td width="4%">&nbsp</td>
            	<td colspan="4" class="celdasBorde centrar" >'.$arrOrdenTrabajo['producto'].'</td>
            	<td width="4%">&nbsp</td>
            	<td width="12%" class="negrilla">
            		OTRO:            	</td>
            	<td width="8%" colspan="2">&nbsp</td>
            </tr>
        </table>
        <table width="100%">
            <tr><td width="16%" style="padding-top: 5px"></td>
            </tr>
            <tr>			
            <td  class="negrilla">FECHA</td>
            <td colspan="3" class="celdasBorde"></td>			
            <td width="17%" class="negrilla">MUNICIPIO</td>			
            <td  colspan="5" class="celdasBorde">'.$arrOrdenTrabajo['municipio'].'</td>
            </tr>            
            <tr>			
            <td  class="negrilla">CLIENTE</td>
            <td colspan="6" class="celdasBorde">'.$arrOrdenTrabajo['terceroSucursal'].'</td>			
            <td width="7%"  class="negrilla">TELEFONOS</td>
            <td width="16%" colspan="2" class="celdasBorde">'.$arrOrdenTrabajo['terceroTelefono'].'</td>
            </tr>           
        </table>
        <table width="100%">
                <tr>			
                <td class="negrilla">CÉDULA / NIT</td>
                <td width="27%" class="celdasBorde">'.$arrOrdenTrabajo['nit'].'</td>			
                <td width="13%" class="negrilla">DIRECCION</td>			
                <td width="44%" class="celdasBorde">'.$arrOrdenTrabajo['terceroDireccion'].'</td>
                </tr>           
        </table>
        <table width="100%">			
                <tr>			
                <td class="negrilla">NOMBRE DEL ESTABLECIMIENTO</td>
                <td width="29%" class="celdasBorde">'.$arrOrdenTrabajo['sucursal'].'</td>			
                <td width="15%" class="negrilla">CORREO ELECTRONICO</td>			
                <td width="38%" class="celdasBorde">'.$arrOrdenTrabajo['terceroEmail'].'</td>
                </tr>           
        </table>
        <table width="100%">
                <tr>			
                <td width="16%" class="negrilla">CONTACTO</td>
                <td width="28%" class="celdasBorde"></td>			
                <td width="13%" class="negrilla">TELÉFONOS FIJO / MÓVIL</td>			
                <td width="43%" class="celdasBorde"></td>
                </tr>           
        </table>
        <table width="100%">
                <tr>			
                <td width="16%" class="negrilla">TIPO CLIENTE</td>
                <td width="19%" class="celdasBorde centrar">FAMILIA</td>
                <td width="4%">&nbsp</td>			
                <td width="20%" class="celdasBorde centrar">CAFÉ INTERNET</td>			
                <td width="4%">&nbsp</td>	
                <td width="17%" class="celdasBorde centrar">CORPORATIVO</td>		
                <td width="20%" >&nbsp</td>
                </tr>           
        </table>
        <table width="100%">
                <tr>			
                <td width="18%"  class="negrilla">ANCHO DE BANDA CONTRATADO</td>
                <td width="10%"  class="celdasBorde centrar tamanoK">1.000 KB</td>
                <td width="1%"></td>			
                <td width="10%"  class="celdasBorde centrar tamanoK">2.000 KB</td>
                <td width="1%"></td>
                <td width="10%"  class="celdasBorde centrar tamanoK">3.000 KB</td>
                <td width="1%"></td>
                <td width="10%"  class="celdasBorde centrar tamanoK">5.000 KB</td>
                <td width="1%"></td>
                <td width="10%"  class="celdasBorde centrar tamanoK">6.000 KB</td>
                <td width="1%"></td>
                <td width="10%"  class="celdasBorde centrar tamanoK">10.000 KB</td>
                <td width="1%"></td>
                <td width="6%"  class="negrilla" style="font-size: 10px;">OTRO ANCHO DE BANDA</td>
                <td width="10%"  class="celdasBorde centrar"></td>
                </tr>           
        </table>
        <table width="100%">
                <tr><td width="16%" style="padding-top: 5px"></td>
                </tr>
                <tr>			
                <td width="16%" class="negrilla">VALOR DE LA INSTALACIÓN</td>
                <td width="26%" class="celdasBorde">$&nbsp&nbsp&nbsp'.number_format($arrOrdenTrabajo['valorInstalacion']).'</td>
                <td width="4%">&nbsp</td>			
                <td width="18%" class="negrilla">VALOR MENSUAL DEL SERVICIO</td>			
                <td width="4%">&nbsp</td>	
                <td width="27%" class="celdasBorde">$&nbsp&nbsp&nbsp'.number_format($arrOrdenTrabajo['valorSalidaConImpue']).'</td>		
                <td width="5%" >&nbsp</td>
                </tr>           
        </table>';
    
    $bodyInicial='<table width="100%">	
                    <tr>
                    <td class="negrilla" width="50%">
                            <div style="margin-top: 30px; margin-bottom: 20px;">EQUIPOS ORDEN DE SERVICIO</div>
                    </td>
                    <td>

                    </td>
                    </tr>
		</table>
		<div style="width: 100%">
                    <div style="width: 55%; float:left;">
                        <table  class="tablaDatos" width="100%" >
                            <thead>
                            <tr>
                                <td class="bordeSimple tituloServicios">
                                        EQUIPO
                                </td>
                                <td class="bordeSimple tituloServicios">
                                        CAN
                                </td>
                                <td class="bordeSimple tituloServicios">
                                        SERIAL
                                </td>
                                <td class="bordeSimple tituloServicios">
                                        TIPO
                                </td>
                            </tr></thead><tbody>';
                        foreach ($arrOrdenServicioEquipos as $servicioEquipo) {
                            $bodyInicial .=' <tr><td style="height: 4px;"></td></tr>
                                <tr class="tamnoDatosOrdenServicio">
                                    <td class="bordeSimple">'.$servicioEquipo['productoCompone'].'</td>
                                    <td class="bordeSimple">'.$servicioEquipo['cantidad'].'</td>
                                    <td class="bordeSimple">'.$servicioEquipo['serial'].'</td>
                                    <td class="bordeSimple">'.$servicioEquipo['tipo'].'</td>
                                </tr>';
                        }      					
        $bodyInicial .='</tbody></table>
                    </div>
		<div style="width: 45%; float: left; ">
                    <table width="100%">
                    <tr>
                        <td>
                            <table width="100%">
                            <tr>
                                <td width="50%" class="negrilla centrar" style="border:1px solid; border-radius: 25px">
                                    OBSERVACIONES
                                </td>
                                <td width="50%"> </td>
                            </tr>
                            <tr>
                                <td colspan="2" rowspan="10" style="border:1px solid; border-radius: 25px" height="150px"></td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    </table>
		</div>
		</div>
		<div style="font-size: 8px; font-family:  sans-serif; ">
		<p>	*Los equipos instalados son propiedad de MAPA INGENIERIA S.A.S <br>y son entregados en calidad de comodato. </p>
		<p>**EL CLIENTE entiende y acepta que la presente orden de servicio <br>
		quedará en firme mientras haya viabilidad técnica para hacer la instalación del servicio, en caso contrario MAPA INGENIERIA S.A.S. podrá dar por terminado el contrato sin que haya derecho a ninguna reclamación por ninguna de las partes.</p>
		</div>
		<table width="100%">
                    <tr><td width="8%"></td>
                    <td width="33%" class="negrilla">Recibo satisfecho,</td>
                    <td colspan="4" class="negrilla centrar">Instalador:</td>
                    <td width="16%" rowspan="2"  align="center"><img src="../imagenes/logo2.png" alt="" width="120"></td>
                    </tr>
                    <tr>
                        <td align="right">Firma:</td>
                        <td class="bordeAbajo"></td>
                        <td width="3%"></td>
                        <td width="7%" align="right">Firma:</td>
                        <td colspan="2" width="33%" class="bordeAbajo"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Nombre completo :</td>	
                        <td></td>	
                        <td colspan="2">Nombre completo :</td>		
                    </tr>
                    <tr>
                        <td>CC :</td>
                    </tr>
		</table>';
     
    $footer='<table width="100%">
                    <tr class="redesSociales">
                        <td width="82"></td>
                        <td width="227"><img src="../imagenes/RedesSociales/facebook.jpg" width="60px" alt="">Mapa Ingeniería</td>
                        <td width="120"></td>
                        <td width="255"><img src="../imagenes/RedesSociales/twitter2.png" width="60px" alt="">www.twitter.com/mapa.net</td>
                        <td width="67"></td>
                        <td width="305"><img src="../imagenes/RedesSociales/google-plus.jpg" width="60px" alt="">Mapa Ingeniería</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="centrar negrilla">MAPA INGENIERÍA www.mapaingenieria.com</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="font-size: 10px; font-weight: bold;">NEIVA-HUILA CALLE 3 SUR 15 - 16 BARRIO TIMANCO IV ETAPA&nbsp&nbspTEL 316 472 9684&nbsp&nbsp&nbsp&nbspCorreo Electrónico: <u>asistente@mapaingenieria.com</u> </td>
                    </tr>
		</table>';
    $html .= $style.'<table style="width:100%;"><thead><tr><td>'.$header.'</td></tr></thead><tbody>'.$bodyInicial.$saltoPagina.'</tbody><tfoot><tr><td>'.$footer.'</td></tr></tfoot></table>';
    
}catch (Exception $exc) {
    echo $exc->getMessage();
}
echo $html;

?>