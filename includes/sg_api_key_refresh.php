<?php 

class refreshAuth{
    public static function refresh($username, $api_key, $Object){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.siigo.com/auth");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
        \"username\": \"".$username."\",
        \"access_key\": \"".$api_key."\"
        }");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        
    
        //recibir respuesta
        $response = json_decode($response);

        if(  isset( $response->status) ){
            consultasSQL::DeleteSQL('sg_credentials', ' username = "'.$username.'" ');
            
            return false;
        }

        if( isset($response->access_token) ){
            $hora_fecha = $Object->format("Y-m-d h:i:s");  

            consultasSQL::UpdateSQL('sg_credentials',"
                access_token = '".$response->access_token."', 
                expires_in  = '".$response->expires_in."', 
                token_type = '".$response->token_type."', 
                fecha_editado = '".$hora_fecha."' ",
                'username = "'.$username.'" ');

            return true;
        }
    }
}


?>