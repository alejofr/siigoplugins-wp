<?php

    class ProductosJobs{

        public static function ejecutar($fecha, $prefijo, $productos_siigo,$configuracion){
            $tabla = $prefijo.'wc_product_meta_lookup';
            $tabla_posts = $prefijo.'posts';
            $tabla_postmeta = $prefijo.'postmeta';
            // print_r($productos_siigo->results);
            
            
            
            foreach ($productos_siigo->results as $key => $item) {
                
                
                echo '<br>';
                echo 'producto codigo, de sigo: '.$item->code;
                echo '<br>';
                
                //Consultamos el codigo del producto de siigo, con la tabla que guarda los sku de los producto o variciones guardado en wp.
                
                $consultar_productos = ejecutarSQL::consultar("select * from ".$prefijo."wc_product_meta_lookup where sku='".$item->code."'");
                
                //si existe, entonces generamos los procesos de actualizacion
                if( mysqli_num_rows($consultar_productos) != 0 ){
                    
                   
                    
                    $productos = mysqli_fetch_array($consultar_productos);
                    
                     echo '<br>';
                    echo 'producto sku, los que coinciden. codigo: '.$item->code.' y codigo de sku: '.$productos['sku'];
                    echo '<br>';
                    
                    if( $configuracion->min_price != 'NO' && $configuracion->stock_quantity != 'NO'){
                        
                                self::actualizar_precio($item->prices, $item->code, $tabla, $tabla_postmeta, $productos['product_id']);
                                
                               
                                
                                self::actualizar_stock($item->code,intval($item->available_quantity), $tabla, $tabla_postmeta,$productos['product_id']);
                                
                            }else if( $configuracion->min_price != 'NO' ){
                                self::actualizar_precio($item->prices, $item->code, $tabla, $tabla_postmeta, $productos['product_id']);
                            }else if($configuracion->stock_quantity != 'NO'){
                                self::actualizar_stock($item->code,intval($item->available_quantity), $tabla, $tabla_postmeta, $productos['product_id']);
                            }


                            if( !self::check($productos['product_id'], $item->code) ){
                                self::save($productos['product_id'],$item->code,json_encode($configuracion), $fecha);
                            }else{
                                self::update($productos['product_id'],$item->code,json_encode($configuracion), $fecha);
                            }
                            consultasSQL::UpdateSQL($tabla_posts,"
                                    post_modified = '$fecha',
                                    post_modified_gmt= '$fecha'
                                ", " ID='".$productos['product_id']."' ");
                }
         
            }

           
        }

        public static function actualizar_stock($sku,$stock, $tabla, $tabla_postmeta, $id_producto){
            $stock_status = ( $stock > 0 ) ? 'instock' : 'outofstock';
            
            
            consultasSQL::UpdateSQL($tabla,'
                stock_quantity = "'.$stock.'",
                stock_status = "'.$stock_status.'"  
            ',' sku = "'.$sku.'" ');
            
            consultasSQL::UpdateSQL($tabla_postmeta,'
                meta_value = "'.$stock.'"
            ',' post_id ="'.$id_producto.'" and meta_key = "_stock" ');
            consultasSQL::UpdateSQL($tabla_postmeta,'
                meta_value = "'.$stock_status.'"  
            ',' post_id ="'.$id_producto.'" and meta_key = "_stock_status" ');
        }

        public static function actualizar_precio($precios_siigo, $sku, $tabla, $tabla_postmeta, $id_producto){
            foreach ($precios_siigo as $key => $item) {
                foreach ($item->price_list as $clave => $valor) {
                    $valor->value = intval($valor->value);
                    consultasSQL::UpdateSQL($tabla,'
                        min_price = "'.$valor->value.'",
                        max_price = "'.$valor->value.'"   
                    ',' sku = "'.$sku.'" ');
                    //meta_value = "'.$valor->value.'" 
                    consultasSQL::UpdateSQL($tabla_postmeta,'
                        meta_value = "'.$valor->value.'"  
                    ',' post_id ="'.$id_producto.'" and (meta_key = "_price" or meta_key = "_regular_price")');
                }
            }
        }

        public static function check($id, $sku)
        {
            $consultar = ejecutarSQL::consultar("select * from sg_productos_jobs where sku='".$sku."' and id_producto='".$id."' ");

            if( mysqli_num_rows($consultar) != 0 ){
                return true;
            }

            return false;
        }

        public static function save($id, $sku, $campos, $fecha){
            consultasSQL::InsertSQL('sg_productos_jobs','id_producto, sku, campos_actualizados,fecha_creado,fecha_editado',"
                '$id',
                '$sku',
                '$campos',
                '$fecha',
                '$fecha'
            ");
        }

        public static function update($id, $sku,$campos,$fecha){
            consultasSQL::UpdateSQL('sg_productos_jobs',"
                campos_actualizados = '$campos',
                fecha_editado = '$fecha'
            ", "sku='".$sku."' and id_producto='".$id."'");
        }

    }

?>