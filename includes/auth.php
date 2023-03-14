<?php 
    require_once dirname(__DIR__).'/includes/sg_api_key_refresh.php';
    class auth{
        
        public static function check($Object)
        {
            
            if(ejecutarSQL::check_table('sg_credentials')){
                $consultar_credentials = ejecutarSQL::consultar("select * from sg_credentials");
                if (mysqli_num_rows($consultar_credentials) != 0){
                    $credentials = mysqli_fetch_array($consultar_credentials);

                    $fecha_hora_actual = $Object->format("Y-m-d h:i:s");
                    $segundos_actual = strtotime($fecha_hora_actual) - strtotime($credentials['fecha_editado']);
            
                
                 
                    if ( $segundos_actual >=  $credentials['expires_in'] ){
                        if( !refreshAuth::refresh( $credentials['username'], $credentials['api_key'], $Object ) ){
                            $_SESSION["message"] = "Hubo un error de autentificación. Por favor revisar sus credenciales";
                            return false;
                        }
                            
                    }

                    return true;
                }
            }

       

            return false;
        }

        public static function user(){
            $consultar_credentials = ejecutarSQL::consultar("select * from sg_credentials");

            return mysqli_fetch_array($consultar_credentials);
        }

        public static function update($username, $key, $Object){
            $usuario = self::user();

            if( $usuario['username'] != $username || $usuario['api_key'] != $key ){
               consultasSQL::DeleteSQL('sg_credentials', 'username = "'.$usuario['username'].'"');
               return  self::save($username, $key, $Object);
            }

            return true;
        }

        public static function save($username, $key, $Object){
            $ruta = "https://api.siigo.com/auth";

            $json = "{
                \"username\": \"".$username."\",
                \"access_key\": \"".$key."\"
            }";

            $response = RequestApi::request('POST', $ruta,false,"", $json);

            if( isset($response->status) ){
                $_SESSION["message"] = "Hubo un error de autentificación. Por favor revisar sus credenciales";
                return false; 
            }

            
            $hora_fecha = $Object->format("Y-m-d h:i:s a");  
            consultasSQL::InsertSQL('sg_credentials','username, api_key, access_token, expires_in, token_type,fecha_creado, fecha_editado','
                "'.$username.'",
                "'.$key.'",
                "'.$response->access_token.'",
                "'.$response->expires_in.'",
                "'.$response->token_type.'",
                "'.$hora_fecha.'",
                "'.$hora_fecha.'"
            ');
            

            return true;
        }
    }

?>