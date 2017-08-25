<?php
require_once '../entorno/Conexion.php';
require_once '../entorno/ConexionSQLite.php';
ini_set("max_execution_time",0);
try {
    $idEmpresa = "6";
    $postgres = new \Conexion();
    $SQLite = new \ConexionSQLite();
    
    //---------------SE LIMPIA LA BD (SQLITE)---------------------
    $sql = "DELETE FROM producto;DELETE FROM categoria;DELETE FROM forma_pago;DELETE FROM empresa;DELETE FROM sucursal;DELETE FROM barrio;";
    $SQLite->exec($sql);
    //------------------------------------------------------------

    //-------------------------CATEGORIAS---------------------------
    $sentenciaSql = "
                    SELECT
                        lp.id_linea_producto AS id_categoria
                        , lp.linea_producto AS categoria
                        , lp.imagen_movil AS imagen
                        , lp.id_linea_producto_padre
                        , lp.descripcion
						, lp.orden
                    FROM
                        facturacion_inventario.linea_producto AS lp
                    WHERE
                        lp.estado = TRUE
                    ORDER BY 	
                            lp.id_linea_producto
                    ";
    $postgres->ejecutar($sentenciaSql);
    while ($fila = $postgres->obtenerObjeto()) {
		
		if($fila->id_linea_producto_padre != "" && $fila->id_linea_producto_padre != "null" && $fila->id_linea_producto_padre != null){
			$posicionPager = '-1';
		}else{
			$posicionPager = $fila->orden;
		}
        $sql = "
                INSERT INTO
                    categoria
                (
                    id_categoria
                    , categoria
                    , imagen
                    , posicion_pager
                    , id_categoria_padre
                    , descripcion
                )
                VALUES
                (
                    $fila->id_categoria
                    , '$fila->categoria'
                    , '$fila->imagen'
                    , $posicionPager
                    , '$fila->id_linea_producto_padre'
                    , '$fila->descripcion'
                )
               ";
        $SQLite->exec($sql);
    }
    //-------------------------------------------------------------


    //-------------------------PRODUCTOS---------------------------
    $sentenciaSql = "
                    SELECT
                        p.id_producto
                        , p.producto
                        , p.id_linea_producto AS id_categoria
                        , p.imagen_movil AS imagen
                        , p.descripcion
                        , CEILING(p.valor_salid_con_impue) AS precio
                    FROM
                        facturacion_inventario.producto AS p
                    WHERE
                        p.estado = TRUE
                    ";
    $postgres->ejecutar($sentenciaSql);
    while ($fila = $postgres->obtenerObjeto()) {
        $sql = "
                INSERT INTO
                    producto
                (
                    id_producto
                    , producto
                    , id_categoria
                    , imagen
                    , descripcion
                    , precio
                )
                VALUES
                (
                    $fila->id_producto
                    , '$fila->producto'
                    , $fila->id_categoria
                    , '$fila->imagen'
                    , '$fila->descripcion'
                    , $fila->precio
                )
               ";
        $SQLite->exec($sql);
    }
    //-------------------------------------------------------------
    
    
    //-------------------------FORMAS DE PAGO---------------------------
    $sentenciaSql = "
                    SELECT
                        fp.id_forma_pago
                        , fp.forma_pago
                        , fp.principal
                    FROM
                        facturacion_inventario.forma_pago AS fp
                    WHERE
                        fp.estado = TRUE
                    ";
    $postgres->ejecutar($sentenciaSql);
    while ($fila = $postgres->obtenerObjeto()) {
        
        if($fila->principal == true)
            $principal = 1;
        else
            $principal = 0;
        
        $sql = "
                INSERT INTO
                    forma_pago
                (
                    id_forma_pago
                    , forma_pago
                    , principal
                )
                VALUES
                (
                    $fila->id_forma_pago
                    , '$fila->forma_pago'
                    , $principal
                )
               ";
        $SQLite->exec($sql);
    }
    //-------------------------------------------------------------
    
    //-----------------INFORMACIÓN DE LA EMPRESA-------------------
    $sentenciaSql = "
                    SELECT
                        e.id_empresa
                        , t.tercero AS empresa
                        , e.descripcion
                        , e.telefono
                        , e.celular
                        , e.url_pag_web
                        , e.url_twitter
                        , e.url_facebook
                    FROM
                        contabilidad.empresa AS e
                        INNER JOIN contabilidad.tercero AS t ON t.id_tercero = e.id_tercero
                    WHERE
                        e.id_empresa = $idEmpresa
                    ";
    $postgres->ejecutar($sentenciaSql);
    $fila = $postgres->obtenerObjeto();
    
    $sql = "
            INSERT INTO
                empresa
            (
                id_empresa
                , empresa
                , descripcion
                , celular
                , telefono
                , url_web
                , url_facebook
                , url_twitter
            )
            VALUES
            (
                $fila->id_empresa
                , '$fila->empresa'
                , '$fila->descripcion'
                , '$fila->celular'
                , '$fila->telefono'
                , '$fila->url_pag_web'
                , '$fila->url_facebook'
                , '$fila->url_twitter'
            )
           ";
    $SQLite->exec($sql);
    //-------------------------------------------------------------
    
    //----------------------SUCURSALES-----------------------------
    $sentenciaSql = "
                    SELECT
                        s.id_sucursal
                        , s.sucursal
                        , td.tercero_direccion AS direccion
                        , s.latitud
                        , s.longitud
                    FROM
                        contabilidad.sucursal AS s
                        INNER JOIN contabilidad.tercero_direccion AS td ON td.id_tercero_direccion = s.id_tercero_direccion
                        INNER JOIN contabilidad.tercero AS t ON t.id_tercero = s.id_tercero
                        INNER JOIN contabilidad.empresa AS e ON e.id_tercero = s.id_tercero
                    WHERE
                        e.id_empresa = $idEmpresa
                    ";
    $postgres->ejecutar($sentenciaSql);
    while ($fila = $postgres->obtenerObjeto()) {
        $sql = "
                INSERT INTO
                    sucursal
                (
                    id_sucursal
                    , sucursal
                    , direccion
                    , latitud
                    , longitud
                )
                VALUES
                (
                    $fila->id_sucursal
                    , '$fila->sucursal'
                    , '$fila->direccion'
                    , $fila->latitud
                    , $fila->longitud
                )
               ";
        $SQLite->exec($sql);
    }
    //-------------------------------------------------------------
    
    //----------------------BARRIOS-----------------------------
    $sentenciaSql = "
                    SELECT
                        id_barrio
                        , barrio
                        , valor_domicilio
                    FROM
                        general.barrio
                    WHERE
                        estado = TRUE
                    ";
    $postgres->ejecutar($sentenciaSql);
    while ($fila = $postgres->obtenerObjeto()) {
        $sql = "
                INSERT INTO
                    barrio
                (
                    id_barrio
                    , barrio
                    , valor_domicilio
                )
                VALUES
                (
                    $fila->id_barrio
                    , '$fila->barrio'
                    , $fila->valor_domicilio
                )
               ";
        $SQLite->exec($sql);
    }
    //-------------------------------------------------------------
    
    echo "La información se migró correctamente.";
} catch (Exception $exc) {
    echo $exc->getMessage();
}