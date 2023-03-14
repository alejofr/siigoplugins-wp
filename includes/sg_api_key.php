<?php 


if( isset($_POST['email']) && isset($_POST['key']) ){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.siigo.com/auth");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_POST, TRUE);

    curl_setopt($ch, CURLOPT_POSTFIELDS, "{
    \"username\": \"".$_POST['email']."\",
    \"access_key\": \"".$_POST['key']."\"
    }");

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    
   
    //recibir respuesta
    $response = json_decode($response);

    if(  isset( $response->status) && $response->status == 500 ){
        $message = "Error del Servidor. Por favor intentar más tarde";
    }else if ( isset( $response->status) && $response->status == 401 ){
        $message = "Credenciales Incorrectas";
    }

    if( isset($response->access_token) ){
        $message = "Credenciales Correctas";
        $class = "update";
        $boleano = true;
        $hora_fecha = $Object->format("Y-m-d h:i:s a");  

       

        consultasSQL::InsertSQL('sg_credentials','username, api_key, access_token, expires_in, token_type,fecha_creado, fecha_editado','
            "'.$_POST['email'].'",
            "'.$_POST['key'].'",
            "'.$response->access_token.'",
            "'.$response->expires_in.'",
            "'.$response->token_type.'",
            "'.$hora_fecha.'",
            "'.$hora_fecha.'"
        ');

    }

}
  

?>