<?php 

    class ClientesJobs{

        public static function check($id_customer){
            $costomer = ejecutarSQL::consultar("select * from sg_clientes_jobs where customer_id='".$id_customer."'");

            if( mysqli_num_rows($costomer) != 0 ){
                return true;
            }

            return false;
        }

        public static function crear($id_customer, $prefijo, $order_id){
            $sg_configuracion_jobs = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='clientes'");
            $tabla = $prefijo.'wc_customer_lookup';
            $tabla_postmeta = $prefijo.'postmeta';

            if( mysqli_num_rows($sg_configuracion_jobs) != 0 ){
                $configuracion_jobs = mysqli_fetch_array($sg_configuracion_jobs);
                $configuracion = json_decode($configuracion_jobs['setting']);
                
                if( $configuracion->atributte_doc != '' ) {
                   

                    $consul_postmeta_atributte_doc = ejecutarSQL::consultar("select * from  ".$tabla_postmeta." where post_id='".$order_id."' 
                                 AND meta_key ='".$configuracion->atributte_doc."' ");

                    $consul_postmeta_atributte_address = ejecutarSQL::consultar("select * from  ".$tabla_postmeta." where post_id='".$order_id."' 
                    AND meta_key ='".$configuracion->atributte_address."' ");
                    
                  
                    if( mysqli_num_rows($consul_postmeta_atributte_doc) != 0 && mysqli_num_rows($consul_postmeta_atributte_address) != 0){
                        $postmeta_atributte_doc = mysqli_fetch_array($consul_postmeta_atributte_doc);
                        $postmeta_atributte_address = mysqli_fetch_array($consul_postmeta_atributte_address);

                        $consul_customer = ejecutarSQL::consultar("select first_name,last_name,email,country,postcode,city,state from ".$tabla." where customer_id='".$id_customer."'");
                        
                        if( mysqli_num_rows($consul_customer) != 0 ){
                            $customer = mysqli_fetch_array($consul_customer);

                            $consul_geo = ejecutarSQL::consultar("select city_code,state_code from sg_geo where state_name_code='".$customer['state']."' AND city_name='".$customer['city']."' ");

                            if( mysqli_num_rows($consul_geo) != 0 ){

                                $geo = mysqli_fetch_array($consul_geo);
                                $consul_postmeta_atributte_number = ejecutarSQL::consultar("select * from  ".$tabla_postmeta." where post_id='".$order_id."' 
                                AND meta_key ='".$configuracion->atributte_number."' ");

                                if ( mysqli_num_rows($consul_postmeta_atributte_number) ){
                                    $postmeta_atributte_number = mysqli_fetch_array($consul_postmeta_atributte_number);

                                    $cliente = [
                                        'person_type' => 'Person',
                                        'id_type' => $configuracion->atribute_type_doc,
                                        'identification' => $postmeta_atributte_doc['meta_value'],
                                        'name' => [$customer['first_name'], $customer['last_name']],
                                        'address' => [
                                            'address' => $postmeta_atributte_address['meta_value'],
                                            'city' => [
                                                'country_code' => $customer['country'],
                                                'state_code' => $geo['state_code'],
                                                'city_code' => $geo['city_code']
                                            ],
                                        ],
                                        'phones' => [
                                            [
                                                'indicative' => '57',
                                                'number' => $postmeta_atributte_number['meta_value']
                                            ]
                                        ],
                                        'contacts' => [
                                            [
                                                'first_name' => $customer['first_name'],
                                                'last_name' => $customer['last_name'],
                                                'email' => $customer['email']
                                            ]
                                        ]
                                    ];


                                    return self::send($cliente, $id_customer);

                                }

                            }

                            
                        }
                        
                    }

                    
                }
             }
            return [];

        }

        public static function send($data, $customer_id){
            $usuario = auth::user();

            $results = RequestApi::request('POST', 'https://api.siigo.com/v1/customers', true, $usuario['access_token'], json_encode($data));
         
            if( !isset($results->Status)  ){
                consultasSQL::InsertSQL('sg_clientes_jobs','
                        id,
                        customer_id,
                        person_type,
                        id_type,
                        identification,
                        name_customer,
                        address_customer,
                        phones,
                        contacts
                    ',"
                    '".$results->id."',
                    '".$customer_id."',
                    '".$data['person_type']."',
                    '".$data['id_type']."',
                    '".$data['identification']."',
                    '".$data['name']."',
                    '".json_encode($data['address'])."',
                    '".json_encode($data['phones'])."',
                    '".json_encode($data['contacts'])."'
                ");

                return [
                    'id' => $results->id,
                    'customer_id' => $customer_id,
                    'person_type' => $data['person_type'],
                    'id_type' => $data['id_type'],
                    'identification' => $data['identification'],
                    'name_customer' => $data['name'],
                    'address_customer' => json_encode($data['address']),
                    'phones' => json_encode($data['phones']),
                    'contacts' => json_encode($data['contacts'])
                ];
            }

            return [];
        }

        public static function get($id_customer){

            $costomer = ejecutarSQL::consultar("select * from sg_clientes_jobs where customer_id='".$id_customer."'");

            return mysqli_fetch_array($costomer);

        }
        
    }

?>