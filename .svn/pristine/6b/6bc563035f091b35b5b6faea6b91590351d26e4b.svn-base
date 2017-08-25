<?php
function obtenerSolicitudEtapa(){
    require_once '../entorno/Conexion.php';
    $html = "";
    $contador = 0;
    $conexionEtapa = new \Conexion();
    $conexionSolicitud = new \Conexion();
    
    $sentenciaSql = "
                        SELECT DISTINCT
                            es.id_etapa_solicitud
                            ,es.etapa_solicitud
                            ,es.orden
                        FROM 
                            financiera.etapa_solicitud_sublinea AS ess
                            INNER JOIN financiera.etapa_solicitud AS es ON es.id_etapa_solicitud = ess.id_etapa_solicitud
                            INNER JOIN financiera.sublinea AS s ON s.id_sublinea = ess.id_sublinea
                        WHERE
                            ess.estado = TRUE
                        ORDER BY
                            es.orden
                    ";
    $conexionEtapa->ejecutar($sentenciaSql);
    if($conexionEtapa->obtenerNumeroRegistros() != 0){
        while($fila = $conexionEtapa->obtenerObjeto()){
           $html .= "<table class='table table-bordered table-striped consultaTabla'>"; 
           $html .= "<tr>";
           $html .= "<th colspan='9'>".$fila->etapa_solicitud."</th>";
           $html .= "</tr>";
           
            $sentenciaSql = "
                                SELECT 
                                    s.id_solicitud 
                                    ,s.codigo 
                                    ,t.tercero AS cliente 
                                    ,TO_CHAR(se.fecha_asignacion,'YYYY-mm-dd') AS fecha 
                                    ,s.valor 
                                    ,sl.id_sublinea
                                    ,sl.sublinea
                                    ,f.formulario
                                    ,ess.id_etapa_solicitud
                                    ,ese.etapa_solicitud_estado
                                    ,ees.finalizado
                                    ,cre.id_credito
                                FROM 
                                    financiera.solicitud AS s 
                                    LEFT OUTER JOIN financiera.credito AS cre ON cre.id_solicitud = s.id_solicitud
                                    INNER JOIN financiera.cliente AS c ON c.id_cliente = s.id_cliente 
                                    INNER JOIN contabilidad.tercero AS t ON t.id_tercero = c.id_tercero 
                                    INNER JOIN financiera.sublinea AS sl ON sl.id_sublinea = s.id_sublinea 
                                    INNER JOIN financiera.solicitud_etapa AS se ON se.id_etapa_solicitud = s.id_etapa_solicitud AND se.id_solicitud = s.id_solicitud
                                    INNER JOIN financiera.etapa_solicitud_sublinea AS ess ON ess.id_sublinea = s.id_sublinea AND ess.id_etapa_solicitud = s.id_etapa_solicitud 
                                    INNER JOIN financiera.etapa_solicitud_estado AS ese ON ese.id_etapa_solicitud_estado = s.id_etapa_solicitud_estado
                                    INNER JOIN financiera.estado_etapa_solicitud AS ees ON ees.id_etapa_solicitud_sublinea = ess.id_etapa_solicitud_sublinea AND s.id_etapa_solicitud_estado = ees.id_etapa_solicitud_estado
                                    INNER JOIN seguridad.formulario AS f ON f.id_formulario = ess.id_formulario
                                WHERE 
                                    ess.id_etapa_solicitud = ".$fila->id_etapa_solicitud."
                                ORDER BY 
                                    se.fecha_creacion
                                " ;
           $conexionSolicitud->ejecutar($sentenciaSql);
           if($conexionSolicitud->obtenerNumeroRegistros() != 0){
                $html .= "<tr>";
                $html .= "<th>Fecha</th>";
                $html .= "<th>Código Solicitud</th>";
                $html .= "<th>Código Crédito</th>";
                $html .= "<th>Cliente</th>";
                $html .= "<th>Sublinea</th>";
                $html .= "<th>Valor</th>";
                $html .= "<th>Estado</th>";
                $html .= "<th colspan='2'>Acción</th>";
                $html .= "</tr>";
                while($solicitud = $conexionSolicitud->obtenerObjeto()){
                    
                    if($solicitud->finalizado == "false" || $solicitud->finalizado == false){
                        $formularioSiguiente = obtenerFormularioSiguiente($solicitud->id_sublinea, $solicitud->id_etapa_solicitud);
                    }else{
                        $formularioSiguiente = $solicitud->formulario;
                    }
                    
                    $html .= "<tr>";
                    $html .= "<td>".$solicitud->fecha."</td>";
                    //$html .= "<td style='text-align:right'>".$solicitud->codigo."</td>";
                    $html .= "<td style='text-align:right'>".$solicitud->id_solicitud."</td>";
                    if($solicitud->id_credito != "" && $solicitud->id_credito != null){
                        $html .= "<td style='text-align:right'>".$solicitud->id_credito."</td>";
                    }else{
                        $html .= "<td></td>";
                    }
                    $html .= "<td>".$solicitud->cliente."</td>";
                    $html .= "<td>".$solicitud->sublinea."</td>";
                    $html .= "<td style='text-align:right'>".number_format($solicitud->valor, 0, '', '.')."</td>";
                    $html .= "<td>".$solicitud->etapa_solicitud_estado."</td>";
                    if($formularioSiguiente == "" || $formularioSiguiente == null || $formularioSiguiente == "null" ){
                        $html .= '<td align="center" class="accionesTabla"><span id="imgReimprimir'.$contador.'" class="fa fa-book imagenesTabla" title="Reimprimir documentos" onclick="reimprimirDocumentos('.$solicitud->id_solicitud.')"></span></td>';
                    }else{
                        $html .= '<td align="center" class="accionesTabla"><span id="imgModificar'.$contador.'" class="fa fa-pencil imagenesTabla" title="Modificar" onclick="modificarSolicitud(\''.$solicitud->formulario.'\','.$solicitud->id_solicitud.')"></span></td>';
                        $html .= '<td align="center" class="accionesTabla"><span id="imgSiguiente'.$contador.'" title="Siguiente Etapa" class="fa fa-mail-forward imagenesTabla" onclick="abrirFormulario(\''.$formularioSiguiente.'\','.$solicitud->id_solicitud.')"></span></td>';
                    }
                    $html .= "</tr>";
                    $contador++;
               }
           }else{
                $html .= "<tr>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "<td></td>";
                $html .= "</tr>";
           }
           $html .= "</table><br>"; 
        }
    }
    return $html;
}
function obtenerFormularioSiguiente($idSublinea,$idEtapaSolicitud){
    $formulario = "";
    $conexionEtapaSiguiente = new \Conexion();
    $sentenciaSql = "
                        SELECT 
                            f.formulario
                        FROM 
                            financiera.etapa_solicitud_sublinea AS ess
                            INNER JOIN financiera.sublinea AS s ON s.id_sublinea = ess.id_sublinea
                            INNER JOIN seguridad.formulario AS f ON f.id_formulario = ess.id_formulario
                        WHERE
                            ess.id_sublinea = $idSublinea
                            AND ess.orden IN ( SELECT (orden + 1) AS orden FROM financiera.etapa_solicitud_sublinea WHERE id_etapa_solicitud = $idEtapaSolicitud AND id_sublinea = $idSublinea)                                
                    ";
    $conexionEtapaSiguiente->ejecutar($sentenciaSql);
    if($conexionEtapaSiguiente->obtenerNumeroRegistros() != 0){
        $filaFormulario = $conexionEtapaSiguiente->obtenerObjeto();
        $formulario = $filaFormulario->formulario;
    }
    return $formulario;
}
function obtenerTextoNumero($numero) {
    // Primero tomamos el numero y le quitamos los caracteres especiales y extras
    // Dejando solamente el punto "." que separa los decimales
    // Si encuentra mas de un punto, devuelve error.
    // NOTA: Para los paises en que el punto y la coma se usan de forma
    // inversa, solo hay que cambiar la coma por punto en el array de "extras"
    // y el punto por coma en el explode de $partes
    
    $extras= array("/[\$]/","/ /","/,/","/-/");
    $limpio=preg_replace($extras,"",$numero);
    $partes=explode(".",$limpio);
    if (count($partes)>2) {
        return "Error, el n&uacute;mero no es correcto";
        exit();
    }
    
    // Ahora explotamos la parte del numero en elementos de un array que
    // llamaremos $digitos, y contamos los grupos de tres digitos
    // resultantes
    
    $digitos_piezas=chunk_split ($partes[0],1,"#");
    $digitos_piezas=substr($digitos_piezas,0,strlen($digitos_piezas)-1);
    $digitos=explode("#",$digitos_piezas);
    $todos=count($digitos);
    $grupos=ceil (count($digitos)/3);
    
    // comenzamos a dar formato a cada grupo

    $unidad = array   ('UN','DOS','TRES','CUATRO','CINCO','SEIS','SIETE','OCHO','NUEVE');
    $decenas = array ('DIEZ','ONCE','DOCE', 'TRECE','CATORCE','QUINCE');
    $decena = array   ('DIECI','VEINTI','TREINTA','CUARENTA','CINCUENTA','SESENTA','SETENTA','OCHENTA','NOVENTA');
    $centena = array   ('CIENTO','DOSCIENTOS','TRESCIENTOS','CUATROCIENTOS','QUINIENTOS','SEISCIENTOS','SETECIENTOS','OCHOCIENTOS','NOVECIENTOS');

    
/*    $unidad = array   ('un','dos','tres','cuatro','cinco','seis','siete'  ,'ocho','nueve');
    $decenas = array ('diez','once','doce', 'trece','catorce','quince');
    $decena = array   ('dieci','veinti','treinta','cuarenta','cincuenta'  ,'sesenta','setenta','ochenta','noventa');
    $centena = array   ('ciento','doscientos','trescientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos');*/
    $resto=$todos;

    
    for ($i=1; $i<=$grupos; $i++) {
        
        // Hacemos el grupo
        if ($resto>=3) {
            $corte=3; 
        } 
        else {
            $corte=$resto;
        }
            $offset=(($i*3)-3)+$corte;
            $offset=$offset*(-1);
        
        // la siguiente seccion es una adaptacion de la contribucion de cofyman y JavierB
        
        $num=implode("",array_slice ($digitos,$offset,$corte));
        $resultado[$i] = "";
        $cen = (int) ($num / 100);              //Cifra de las centenas
        $doble = $num - ($cen*100);             //Cifras de las decenas y unidades
        $dec = (int)($num / 10) - ($cen*10);    //Cifra de las decenas
        $uni = $num - ($dec*10) - ($cen*100);   //Cifra de las unidades
        if ($cen > 0) {
           if ($num == 100) $resultado[$i] = "CIEN";
           else $resultado[$i] = $centena[$cen-1].' ';
        }//end if
        if ($doble>0) {
           if ($doble == 20) {
              $resultado[$i] .= " VEINTE";
           }elseif (($doble < 16) and ($doble>9)) {
              $resultado[$i] .= $decenas[$doble-10];
           }else {
              $resultado[$i] .=' '. $decena[$dec-1];
           }//end if
           if ($dec>2 and $uni<>0) $resultado[$i] .=' Y ';
           if (($uni>0) and ($doble>15) or ($dec==0)) {
              if ($i==1 && $uni == 1) $resultado[$i].="UN";
              elseif ($i==2 && $num == 1) $resultado[$i].="";
              else $resultado[$i].=$unidad[$uni-1];
           }
        }

        // Le agregamos la terminacion del grupo
        switch ($i) {
            case 2:
            $resultado[$i].= ($resultado[$i]=="") ? "" : " MIL ";
            break;
            case 3:
            $resultado[$i].= ($num==1) ? " MILLON " : " MILLONES ";
            break;
        }
        $resto-=$corte;
    }
    
    $de = ($resultado[1] == '' && $resultado[2] == '') ? ' ' : '';
    
    // Sacamos el resultado (primero invertimos el array)
    $resultado_inv= array_reverse($resultado, TRUE);
    $final="";
    foreach ($resultado_inv as $parte){
        $final.=$parte;
    }

	if($numero == 0)
		$final = "CERO";
	
	if($numero == 1)
		$final = "UNO";
	
	$final.= $de;
	
    return $final;
}
function obtenerTasaInteresMensual($tasaInteresAnual){
    $tasaInteresAnual = ($tasaInteresAnual/100);
    $tasaNominalAnual = ((pow((1 + $tasaInteresAnual), (1/12))-1) * 12) * 100;
    $tasaNominalMensual = ($tasaNominalAnual/12)/100;
    return number_format($tasaNominalMensual*100, 2);
} 

function obtenerNombreSoftware() {
    $conexion = new \Conexion();
    $sentenciaSql = "
                    SELECT
                        valor
                    FROM
                        general.parametro_aplicacion
                    WHERE
                        id_parametro_aplicacion = 4
                    ";
    $conexion->ejecutar($sentenciaSql);
    $fila = $conexion->obtenerObjeto();
    return $fila->valor;
}

function ceiling($number, $significance = 1){
    return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
}

function truncateFloat($number, $digitos){
    $raiz = 10;
    $multiplicador = pow ($raiz,$digitos);
    $resultado = ((int)($number * $multiplicador)) / $multiplicador;	
    return number_format($resultado, $digitos, '.', ''); 
}

//Limpiar Espacios
function limpiar_espacios($cadena) 
{ 
    $limpia    = ""; 
    $parts    = array(); 
     
    // divido la cadena con todos los espacios q haya 
    $parts = explode(" ",$cadena); 
     
    foreach($parts as $subcadena) 
    { 
        // de cada subcadena elimino sus espacios a los lados 
        $subcadena = trim($subcadena); 
         
        // luego lo vuelvo a unir con un espacio para formar la nueva cadena limpia 
        // omitir los que sean unicamente espacios en blanco 
        if($subcadena!="") 
        { $limpia .= $subcadena." "; } 
    } 
    $limpia = trim($limpia); 
     
    return $limpia; 
} 
function obtenerNombre($nombre) {
    $retorno = array();
    $arrNombreCompleto = explode(" ", $nombre); //Divido el nombre
    
    if(count($arrNombreCompleto) == 1){
        $primerNombre = $arrNombreCompleto[0];
    }else{
        $numeroPalabrasNombre = count($arrNombreCompleto);
        $mitad = floor($numeroPalabrasNombre / 2);

        for ($i = 0; $i < $mitad; $i++) {
            $arrNombres[$i] = $arrNombreCompleto[$i];
        }

        $contador = 0;
        for ($i = $mitad; $i < $numeroPalabrasNombre; $i++) {
            $arrApellidos[$contador] = $arrNombreCompleto[$i]." ";
            $contador++;
        }

        $primerNombre = $arrNombres[0]; //Obtengo el primer nombre
        for ($i = 1; $i < count($arrNombres); $i++) {//Recorro lo que resta del nombre por si es un nombre compuesto: Ej. MARIA DE LOS ANGELES
            $segundoNombre .= $arrNombres[$i]." "; //Se concatena todo para que quede como uno solo: Ej. DELOSANGELES
        }

        $primerApellido = $arrApellidos[0]; //Obtengo el primer apellido
        for ($i = 1; $i < count($arrApellidos); $i++) {//Recorro lo que resta del apellido por si es un apellido compuesto: Ej. DE LA ROSA
            $segundoApellido .= $arrApellidos[$i]." "; //Se concatena todo para que quede como uno solo: Ej. DELAROSA
        }
    }
    
    

    if ($segundoApellido == "")//Si no tiene segundo apellido se debe respetar el campo dejando un espacio en blanco.
        $segundoApellido = " ";
    
    $retorno["primerNombre"] = $primerNombre;
    $retorno["segundoNombre"] = $segundoNombre;
    $retorno["primerApellido"] = $primerApellido;
    $retorno["segundoApellido"] = $segundoApellido;
    
    return $retorno;
}
?>
