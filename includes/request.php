<?php 
    class RequestApi{

        public static function request($metodo, $url, $auth = false,$access_token = "",$json = ""){
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            if ($metodo == 'POST' ){
                curl_setopt($ch, CURLOPT_POST, TRUE);

                if ( $json != "" ){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                }
               
                
            }

            $header = ( $auth ) ? "Authorization: ".$access_token."": "Accept: application/json"; 
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                $header
            ));
    
            $response = curl_exec($ch);
            curl_close($ch);

            return json_decode($response);
        } 

    }
?>