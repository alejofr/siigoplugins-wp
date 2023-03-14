<?php 
    require_once dirname(__DIR__).'/includes/declaracion.php';
    require_once dirname(__DIR__).'/includes/horario.php';
    require_once dirname(__DIR__).'/includes/conexion.php';
    require_once dirname(__DIR__).'/includes/request.php';
    require_once dirname(__DIR__).'/includes/auth.php';
    
    
    $geo = ejecutarSQL::consultar("select * from sg_geo");

    if( mysqli_num_rows($geo) == 0 ){
        echo "a";
        require_once dirname(__DIR__).'/includes/geoCo.php';

        $count_geo = count($city_code);

        for ($i=0; $i < $count_geo; $i++) { 
            consultasSQL::InsertSQL('sg_geo','
                city_code, 
                city_name, 
                state_code,
                country_code,
                country_name,
                state_name_code
                ',"
                '".$city_code[$i]."',
                '".$city_name[$i]."',
                '".$state_code[$i]."',
                '".$country_code[$i]."',
                '".$country_name[$i]."',
                '".$state_name_code[$i]."'
            ");
        }
    }
    
    if( auth::check($Object) ){
        require_once dirname(__DIR__).'/jobs/controller.php';
        $Object = $Object->format("Y-m-d h:i:s");
        Controller::ejecutar($Object,PREFIJO);
       
    }

    
?>