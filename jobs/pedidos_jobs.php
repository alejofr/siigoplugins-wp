<?php 
   /**
    *   Tabla de WooCommerce
    *   prefijo + tabla
    *   1) prefijo_wc_order_stats //estado wc-processing ( se ha reducido el pago recibido (pagado) ). 
    *   2) prefijo_wc_order_product_lookup
   */


    /**
     * Nota: en la tabla prefijo_wc_order_stats, hay que tomas en cuenta:
     * Tomar en cuenta la propiedad nums_items_sold ( cantidad de productos )
     * total_sales ( que seria la suma total de los productos agregados )
     * tax_total (  )
     * shipping_total (  )
     * net_total ( es el total de la orden, que es la suma de todos los productos ( total_sales ) - la suma de ( tax_total + shipping_total ) )
     * customer_id ( para chequear los datos del usuario )
   */

   /**
     * Nota: en la tabla prefijo_wc_order_product_lookup, hay tomar en cuenta:
     * variation_id ( que seria el sku vinculado a un producto y a el sistema de siigo )
     * product_qty ( cantidad de producto )
     * product_gross_revenue ( precio del producto )
   */

  require_once dirname(__DIR__).'/jobs/clientes_jobs.php';

   class PedidosJobs{

      public static function ejecutar($fecha, $prefijo, $configuracion){
         $tabla_order_status = $prefijo.'wc_order_stats';
         
         $tabla_postmeta = $prefijo.'postmeta';
         
        
         $consul_order_status = ejecutarSQL::consultar("select order_id,total_sales,tax_total,shipping_total,net_total,customer_id 
                                                         from ".$tabla_order_status." where status='wc-completed' AND date_created >='".$configuracion->fecha_c."' ");

         if( mysqli_num_rows($consul_order_status) != 0 ){

            $pedidos_status = mysqli_fetch_all($consul_order_status);

            for ($i=0; $i < count($pedidos_status); $i++) {

              $consul_ped_job = ejecutarSQL::consultar("select * from sg_pedidos_jobs where order_id='".$pedidos_status[$i][0]."'");

               if( mysqli_num_rows($consul_ped_job) == 0 ){
                  self::crear($pedidos_status[$i], $prefijo, $configuracion);
               }
               
            }

         }
      }

      public static function crear($orden, $prefijo, $configuracion){
         $tabla_product = $prefijo.'wc_product_meta_lookup';
         $table_order_product_lookup = $prefijo.'wc_order_product_lookup';
         $consul_order_items = ejecutarSQL::consultar("select variation_id,product_net_revenue, product_qty 
                                 from ".$table_order_product_lookup." where order_id='".$orden[0]."'");
         
         $items = [];

         if( mysqli_num_rows($consul_order_items) != 0 ){
            $pedidos_items = mysqli_fetch_all($consul_order_items);
            for ($i=0; $i < count($pedidos_items); $i++) { 
               $consul_product = ejecutarSQL::consultar("select sku from ".$tabla_product." where product_id='".$pedidos_items[$i][0]."'");

               if( mysqli_num_rows($consul_product) != 0  ){
                  $product = mysqli_fetch_array($consul_product);
                  $aux = [
                     'code' => $product['sku'],
                     'quantity' => $pedidos_items[$i][2],
                     'price' => $pedidos_items[$i][1],
                     'taxes' => [
                        [
                           'id' => $configuracion->iva_id
                        ]
                     ]
                  ];

                  array_push($items, $aux);
               }
            }

         }

         if( count($items) != 0 ){
            self::send($orden, $prefijo, $items);
         }

      }

      public static function send($orden, $prefijo, $items){
         $usuario = auth::user();

         $config = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='pedidos'");
         $config = mysqli_fetch_array($config);
         $config= json_decode($config);

         if( isset($config->document_id) && $config->document_id != ''){
            $document_id = $config->document_id;
            $seller = $config->vendedor_id;    
            $id_payments = $config->pago_id;
                   
            $customer = [];
            if( !ClientesJobs::check($orden[5]) ){
               $customer = ClientesJobs::crear($orden[5], $prefijo, $orden[0]); 
            }else{
               $customer = ClientesJobs::get($orden[5]);
            }
                   
            if ( count($customer) != 0){
               $fecha = new DateTime();
               $fecha->setTimezone(new DateTimeZone('America/Caracas'));
               $fecha = $fecha->format("Y-m-d");
      
      
               $pedido = [
                  'document' => [
                     'id' => $document_id
                  ],
                  'date' => $fecha,
                  'customer' => [
                     'identification' => $customer['identification']
                  ],
                  'seller' => $seller,
                  'items' => $items,
                  'payments' => [
                     [
                           'id' => $id_payments,
                        'value' => $orden[1],
                        'due_date' => $fecha
                     ]
                  ],
                  'additional_fields' => []
               ];

               $results = RequestApi::request('POST', 'https://api.siigo.com/v1/invoices', true, $usuario['access_token'], json_encode($pedido));
               
               print_r($results);

               if( !isset($results->Status)  ){
                  consultasSQL::InsertSQL('sg_clientes_jobs','
                     order_id,
                     document,
                     customer,
                     payments,
                     total,
                     fecha
                     ',"
                     '".$orden[0]."',
                     '".json_encode($pedido['document'])."',
                     '".json_encode($pedido['customer'])."',
                     '".json_encode($pedido['payments'])."',
                     '".$orden[1]."',
                     '".$pedido['date']."'
                  ");
               }
         }
                   
               

               
               //

              
            
         }

         
         
      }

   }
?>