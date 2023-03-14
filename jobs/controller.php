<?php 
      require_once dirname(__DIR__).'/jobs/productos_jobs.php';
    require_once dirname(__DIR__).'/jobs/pedidos_jobs.php';


    class Controller{

        public static function ejecutar($Object, $prefijo){
            $usuario = auth::user();
            
            self::pedidos($Object, $prefijo, $usuario);
            self::productos($Object, $prefijo, $usuario);
        }

        public static function productos($Object, $prefijo, $usuario){
            $ruta = 'https://api.siigo.com/v1/products?created_start=';

            $sg_configuracion_jobs = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='producto'");
            
    
            
            if( mysqli_num_rows($sg_configuracion_jobs) != 0 ){
                $configuracion_jobs = mysqli_fetch_array($sg_configuracion_jobs);
                $configuracion_jobs = $configuracion_jobs['setting'];
                
                $productos_siigo = RequestApi::request('GET', $ruta,true,$usuario['access_token'], "");
                
                if( isset($productos_siigo->pagination) ){
                    $pagination = $productos_siigo->pagination;
                    $size = ceil($pagination->total_results/$pagination->page_size);
                    //echo '<br>';
                    //echo 'numero de pagina '.$size;
                    //echo '<br>';
                 
                    //print_r($productos_siigo->pagination);
                    for ($i=1; $i <= $size ; $i++) {
                        
                        
                        $url = $ruta.'&page='.$i;
                        //echo '<br>';
                        //echo $url;
                        //echo '<br>';
                        
                        $productos_siigo = RequestApi::request('GET', $url,true,$usuario['access_token'], "");
                        
                        //echo count($productos_siigo->results);
                        
                        ProductosJobs::ejecutar($Object, $prefijo, $productos_siigo, json_decode($configuracion_jobs));
                    }
                }
            }
        }

        public static function pedidos($Object, $prefijo, $usuario){
             $sg_configuracion_jobs = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='pedidos'");

            if( mysqli_num_rows($sg_configuracion_jobs) != 0 ){
                $configuracion_jobs = mysqli_fetch_array($sg_configuracion_jobs);
                $configuracion_jobs = $configuracion_jobs['setting'];
                
                PedidosJobs::ejecutar($Object, $prefijo, json_decode($configuracion_jobs));

            }
        }

        public static function error($error = []){

        }

    }


?>