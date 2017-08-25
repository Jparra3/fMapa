<?php
    require_once '../entorno/configuracion.php';
    require_once '../entidad/OrdenTrabajo.php';
    require_once '../modelo/OrdenTrabajo.php';
    require_once '../entidad/OrdenTrabajoCliente.php';
    require_once '../modelo/OrdenTrabajoCliente.php';
    require_once '../entidad/OrdenTrabajoVehiculo.php';
    require_once '../modelo/OrdenTrabajoVehiculo.php';
    require_once '../entidad/OrdenTrabajoTecnico.php';
    require_once '../modelo/OrdenTrabajoTecnico.php';
    require_once '../entidad/OrdenTrabajoProducto.php';
    require_once '../modelo/OrdenTrabajoProducto.php';
    require_once '../entidad/TipoDocumento.php';
    require_once '../entidad/Ordenador.php';
    require_once '../entidad/ClienteServicio.php';
try{
    $idOrdenTrabajo = $_REQUEST["idOrdenTrabajo"];
    $numeroOrden = $_REQUEST["numeroOrden"];
    $tipoOrden = $_REQUEST["tipoOrden"];
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $arrOrdenTrabajo = $ordenTrabajoM->consultarOrdenTrabajoDatosOrdenador();
    
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setOrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
    $arrOrdenTrabajoCliente = $ordenTrabajoClienteM->consultarServicios();
    
    $ordenTrabajoVehiculoE = new \entidad\OrdenTrabajoVehiculo();
    $ordenTrabajoVehiculoE->setOrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoVehiculoM = new \modelo\OrdenTrabajoVehiculo($ordenTrabajoVehiculoE);
    $arrOrdenTrabajoVehiculo = $ordenTrabajoVehiculoM->consultar();
    
    $ordenTrabajoTecnicoE = new \entidad\OrdenTrabajoTecnico();
    $ordenTrabajoTecnicoE->setOrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoTecnicoM = new \modelo\OrdenTrabajoTecnico($ordenTrabajoTecnicoE);
    $arrOrdenTrabajoTecnico = $ordenTrabajoTecnicoM->consultarOrdenTrabajoTecnico();
    
    $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
    $ordenTrabajoProductoE->setOrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoProductoE->setEstado('TRUE');
    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    $arrOrdenTrabajoProductoInstalados = $ordenTrabajoProductoM->consultarOrdenTrabajoProductosInstalados();
    $arrOrdenTrabajoProductoAInstalar = $ordenTrabajoProductoM->consultar();
    
    $saltoPagina = '<div class="saltopagina"></div>';
    $style = '
        <style type="text/css">                      
            @media print{
               div.saltopagina{ 
                  display:block; 
                  page-break-before:always;
               }
            }
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
                    border: 2px solid; border-color: #000;
            }
            #infoServicios td{

                    font-size: 12px;
            }
            .bordeAbajo{
                border-bottom: 2px solid; 
                border-color: #000;
            }
            .negrilla{
                font-weight: bold;
            }
        </style>';
    
    $header = '';
     
    $bodyInicial = '
            <table class="tablaDatos" style="width: 100%">
            <tr>
            <td colspan="5" class="titulo" style="font-size: 16px">';
            if( $tipoOrden == 'MANTENIMIENTO'){
                $bodyInicial .='ORDEN DE '.$arrOrdenTrabajo['tipoDocumento'].' MAPA DE INGENIERIA';
            }    
            else {
                $bodyInicial .='ORDEN DE '.$tipoOrden.' MAPA DE INGENIERIA';
            }
           
           $bodyInicial .=' </td>
            </tr>
            <tr>
            	<td class="titulo" style="width: 20%">Orden de trabajo</td>
            	<td>&nbsp</td>
            	<td class="titulo">fecha</td>
            	<td>&nbsp</td>
            	<td rowspan="4" id="logoHeader">
            		<img src="../imagenes/logo2.png" alt="" width="200px">
            	</td>
            </tr>
            <tr>
            	<td  rowspan="2" class="titulo" style="border: 2px solid; border-color: #000;">'.$arrOrdenTrabajo['numero'].'</td>
            	<td></td>';
        //validación si selecciono orden de mantenimiento o por defecto trabajo
                if( $tipoOrden == 'MANTENIMIENTO'){
                    $bodyInicial .='<td style="text-align: center; border: 2px solid; border-color: #000;" >'.$arrOrdenTrabajo['fecha'].'</td>';
                }    
                else {
                    $bodyInicial .='<td style="text-align: center; border: 2px solid; border-color: #000;" >'.$arrOrdenTrabajo['fechaInicio'].'</td>';
                }     	
            $bodyInicial .= '</tr>
            <tr>
            	<td style="border: 0px solid; border-color: #000;">&nbsp</td>
            </tr>
            <tr>
            	<td>&nbsp</td>
            </tr>
        </table>       
        <table class="tablaDatos" width="100%">		
            <tr>
                <td class="titulo" style="width: 40%">Ordenador</td>
                <td style="width: 5%">&nbsp</td>
                <td class="titulo" style="width: 25%">Cedula</td>
                <td style="width: 5%">&nbsp</td>
                <td class="titulo" style="width: 40%">Cargo</td>
            </tr>
            <tr>
                <td class="celdasBorde titulo">'.$arrOrdenTrabajo['nombreEmpleado'].'</td>
                <td></td>
                <td class="celdasBorde titulo">'.$arrOrdenTrabajo['numeroIdentificacion'].'</td>
                <td></td>
                <td class="celdasBorde titulo">'.$arrOrdenTrabajo['cargo'].'</td>
            </tr>
        </table>
        <tr><td>
	<br>
	<table class="tablaDatos" width="100%">
	<tr>
            <td colspan="4" class="titulo"> Servicios</td>
	</tr>
            <tr>
                <td class="titulo celdasBorde" style="width: 30%">Servicio</td>
                <td class="titulo celdasBorde" style="width: 40%">Cliente</td>
                <td class="titulo celdasBorde" style="width: 16%">Direccion</td>
                <td class="titulo celdasBorde" style="width: 14%">Telefono</td>
            </tr>';
        foreach ($arrOrdenTrabajoCliente as $ordenTrabajoServicios) {
            $bodyInicial .= '<tr id="infoServicios">
                <td class="celdasBorde">'.$ordenTrabajoServicios['productoComposicion'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoServicios['cliente'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoServicios['terceroDireccion'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoServicios['terceroTelefono'].'</td>
                </tr>';
        }
    $bodyInicial .='
        </table>
            <br>
            <table class="tablaDatos" width="100%">
            <tr>
                    <td colspan="6" class="titulo"> Vehiculos</td>
            </tr>
                <tr>
                    <td class="titulo celdasBorde" >Vehículo</td>
                    <td class="titulo celdasBorde" >Tipo Vehículo</td>
                    <td class="titulo celdasBorde" >Placa</td>
                    <td class="titulo celdasBorde" >Marca</td>
                    <td class="titulo celdasBorde" >Color</td>
                    <td class="titulo celdasBorde" >Hora llegada</td>
                </tr>';
        foreach ($arrOrdenTrabajoVehiculo as $ordenTrabajoVehiculo) {
            $bodyInicial .=' <tr id="infoServicios">
                    <td class="celdasBorde">'.$ordenTrabajoVehiculo['vehiculo'].'</td>
                    <td class="celdasBorde">'.$ordenTrabajoVehiculo['tipoVehiculo'].'</td>
                    <td class="celdasBorde">'.$ordenTrabajoVehiculo['placa'].'</td>
                    <td class="celdasBorde">'.$ordenTrabajoVehiculo['marca'].'</td>
                    <td class="celdasBorde">'.$ordenTrabajoVehiculo['color'].'</td>
                    <td class="celdasBorde">'.$ordenTrabajoVehiculo['horaLlegada'].'</td>
                </tr>';
        }   
        
    $bodyInicial .='
        </table>
	<br>
	<table class="tablaDatos" width="100%">
	<tr>
            <td colspan="7" class="titulo"> Vehiculos</td>
	</tr>
            <tr>		  
                <td class="titulo celdasBorde" >#</td>
                <td class="titulo celdasBorde" >Nombre</td>
                <td class="titulo celdasBorde" >Cédula</td>
                <td class="titulo celdasBorde" >Cargo</td>
                <td class="titulo celdasBorde" >Teléfono</td>
                <td class="titulo celdasBorde" >Dirección</td>
                <td class="titulo celdasBorde" >EPS / ARL</td>
            </tr>';
        foreach ($arrOrdenTrabajoTecnico as $ordenTrabajoTecnico) {
            $bodyInicial .='<tr id="infoServicios">
                <td class="celdasBorde">'.$ordenTrabajoTecnico['item'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoTecnico['tecnico'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoTecnico['numeroIdentificacion'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoTecnico['cargo'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoTecnico['telefono'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoTecnico['direccion'].'</td>
                <td class="celdasBorde">'.$ordenTrabajoTecnico['epsArl'].'</td>
            </tr>';
            }
    $bodyInicial .='</table>
	<br>
	<table class="tablaDatos" width="100%">
        <thead>
	<tr>
            <td colspan="6" class="titulo">Productos instalados actualmente por cliente</td>
	</tr>
        <tr>  		  
            <td class="titulo celdasBorde" >Servicio</td>
            <td class="titulo celdasBorde" >Servicio</td>
            <td class="titulo celdasBorde" >Producto</td>
            <td class="titulo celdasBorde" >Uni Medi</td>
            <td class="titulo celdasBorde" >Can</td>
            <td class="titulo celdasBorde" >Bodega</td>
        </tr>
        </thead><tbody>';
        foreach ($arrOrdenTrabajoProductoInstalados as $productoInstalado) {
            $bodyInicial .='<tr id="infoServicios">			    
                <td class="celdasBorde">'.$productoInstalado['cliente'].'</td>
                <td class="celdasBorde">'.$productoInstalado['productoComposicion'].'</td>
                <td class="celdasBorde">'.$productoInstalado['productoCompone'].'</td>
                <td class="celdasBorde">'.$productoInstalado['unidadMedida'].'</td>
                <td class="celdasBorde">'.$productoInstalado['cantidad'].'</td>
                <td class="celdasBorde">'.$productoInstalado['serial'].'</td>
            </tr>';
        }
        
    $bodyInicial .='</tbody></table>
	<br>
	<table class="tablaDatos" width="100%">
        <thead>
	<tr>
            <td colspan="7" class="titulo">Productos a instalar por servicio</td>
	</tr>
        <tr>		  
            <td class="titulo celdasBorde" >Servicio</td>
            <td class="titulo celdasBorde" >Cod.</td>
            <td class="titulo celdasBorde" >Producto</td>
            <td class="titulo celdasBorde" >Unid Medi</td>
            <td class="titulo celdasBorde" >Valor</td>
            <td class="titulo celdasBorde" >Can.</td>
            <td class="titulo celdasBorde" >Serial</td>
        </tr>
        </thead><tbody>';
        foreach ($arrOrdenTrabajoProductoAInstalar as $productoInstalar) {
            $bodyInicial .='<tr id="infoServicios">
                <td class="celdasBorde">'.$productoInstalar['productoComposicion'].'</td>
                <td class="celdasBorde">'.$productoInstalar['codigoProductoCompone'].'</td>
                <td class="celdasBorde">'.$productoInstalar['productoCompone'].'</td>
                <td class="celdasBorde">'.$productoInstalar['unidadMedida'].'</td>
                <td class="celdasBorde">$&nbsp'.number_format($productoInstalar['valorEntraConImpue']).'</td>
                <td class="celdasBorde">'.$productoInstalar['cantidad'].'</td>
                <td class="celdasBorde">'.$productoInstalar['serial'].'</td>
            </tr>';
            }
    $bodyInicial .='</tbody></table>
        <br>
        <table width="100%">
            <tr>
                <td style="font-weight: bold;"> Observaciones</td>
            </tr>
            <tr>		  
                <td style="border:2px solid; border-radius: 25px" height="100px"></td>			
            </tr>		
        </table>       
        <table width="100%">
                <tr>
                <br><br>
                    <td colspan="4" class="negrilla">Quién Ordena</td>
                    <td colspan="3" class="negrilla">Quién desarrolla la actividad</td>
                </tr>
                <tr>
                    <td class="bordeAbajo" style="width: 22%"><br><br></td>
                    <td style="width: 4%"></td>
                    <td class="bordeAbajo" style="width: 22%"></td>
                    <td style="width: 4%"></td>
                    <td class="bordeAbajo" style="width: 22%"></td>
                    <td style="width: 4%"></td>
                    <td class="bordeAbajo" style="width: 22%"></td>
                </tr>
                <tr>
                    <td class="negrilla">Firma</td>
                    <td></td>
                    <td class="negrilla">Firma</td>
                    <td></td>
                    <td class="negrilla">Firma</td>
                    <td></td>
                    <td class="negrilla">Firma</td>
                </tr>
            </table>';
     
    $footer='<table width="100%" style="font-size: 10px">
            <tr>
                <td width="2.5%"></td>
                <td width="25%" class="negrilla">Calle 3 Sur 15 - 16 Barrio Timanco</td>
                <td width="2.5%"></td>
                <td width="2.5%"></td>
                <td width="20%" class="negrilla">NEIVA - HUILA</td>
                <td width="2.5%"></td>
                <td width="20%" class="negrilla">TEL.316 472 9684</td>
                <td width="2.5%"></td>
                <td width="22.5%" rowspan="3"><img src="../imagenes/logo2.png" alt="" width="100px"></td>
            </tr>
            <tr>
                <td class="negrilla" colspan="2">Soporte web App: mapa.freshdesk.com</td>
                <td></td>
                <td colspan="2" class="negrilla">e-mail: asistente@mapaingenieria.com</td>
                <td></td>
                <td class="negrilla">www.mapaingenieria.com</td>
                <td></td>
            </tr>
            <tr></tr>
        </table>';
    $html .= $style.'<table style="width:100%;"><thead><tr><td>'.$header.'</td></tr></thead><tbody><tr><td>'.$bodyInicial.$saltoPagina.'</tr></td></tbody><tfoot><tr><td>'.$footer.'</td></tr></tfoot></table>';
     
    }catch (Exception $exc) {
echo $exc->getMessage();
}
echo $html;
?>


