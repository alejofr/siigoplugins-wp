<?php 



/* Clase para ejecutar las consultas a la Base de Datos*/
class ejecutarSQL {
      
    public static $servername = DB_HOST;
    public static $username = DB_USER;
    public static $db_password = DB_PASSWORD;
    public static $db = DB_NAME;

    public static function conexion_db() {
        $con = mysqli_connect(self::$servername, self::$username, self::$db_password, self::$db);
        $con->set_charset("utf8");

        if(!$con){
            die("Error en:" . mysqli_connect_error());
        }

        return $con;
    }

    public static function check_table($table) {
        $con = self::conexion_db();

        $exists = mysqli_query($con, "select 1 from $table");

        if($exists== false){
            return false;
        }  

        return true;
    }

    public static function consultar($query) {
       
        $con= self::conexion_db();
        
        if(!$con){
            die("Error en:" . mysqli_connect_error());
        }
        

        
        $consul = mysqli_query($con, $query);
        
        if (!$consul) {
            die('Error en la consulta SQL ejecutada' );
        }
        return $consul;
    }


    
    public static function create_sg_credentials_table(){
        $sg_credentials = "CREATE TABLE sg_credentials(
            username VARCHAR(255) NOT NULL,
            api_key VARCHAR(255) NOT NULL,
            access_token TEXT NOT NULL,
            expires_in INT,
            token_type VARCHAR(50),
            fecha_creado DATETIME,
            fecha_editado DATETIME
        )";

        if( !mysqli_query(self::conexion_db(), $sg_credentials) ){
            die("Error al crear la tabla: " . mysqli_error(self::conexion_db()) );
        }

        return true;
    }

    public static function create_sg_configuracion_jobs_table(){
        $sg_configuracion_jobs = "CREATE TABLE sg_configuracion_jobs(
            tipo VARCHAR(55) NOT NULL,
            setting TEXT(255) NOT NULL,
            fecha_creado DATETIME,
            fecha_editado DATETIME
        )";

        if( !mysqli_query(self::conexion_db(), $sg_configuracion_jobs) ){
            die("Error al crear la tabla: " . mysqli_error(self::conexion_db()) );
        }

        return true;
    }

    public static function create_sg_productos_jobs_table(){
        $sg_productos_jobs = "CREATE TABLE sg_productos_jobs(
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            id_producto INT NOT NULL,
            sku VARCHAR(100) NOT NULL,
            campos_actualizados TEXT(255),
            fecha_creado DATETIME,
            fecha_editado DATETIME
        )";

        if( !mysqli_query(self::conexion_db(), $sg_productos_jobs) ){
            die("Error al crear la tabla: " . mysqli_error(self::conexion_db()) );
        }

        return true;
    }

    public static function create_sg_pedidos_jobs_table(){
        $sg_productos_jobs = "CREATE TABLE sg_pedidos_jobs(
            order_id VARCHAR(100),
            document TEXT(255) NOT NULL,
            customer TEXT(255) NOT NULL,
            payments TEXT(255) NOT NULL,
            total VARCHAR(100),
            fecha DATETIME
        )";

        if( !mysqli_query(self::conexion_db(), $sg_productos_jobs) ){
            die("Error al crear la tabla: " . mysqli_error(self::conexion_db()) );
        }

        return true;
    }
    
    public static function create_sg_clientes_jobs_table(){
        $sg_clientes_jobs = "CREATE TABLE sg_clientes_jobs(
            id VARCHAR(120) NOT NULL,
            customer_id INT NOT NULL,
            type_doc VARCHAR(30) NOT NULL,
            person_type VARCHAR(30) NOT NULL,
            id_type INT NOT NULL,
            identification varchar(30) NOT NULL,
            name_customer TEXT(255) NOT NULL,
            address_customer TEXT(255) NOT NULL,
            phones TEXT(255) NOT NULL,
            contacts TEXT(255) NOT NULL
        )";

        if( !mysqli_query(self::conexion_db(), $sg_clientes_jobs) ){
            die("Error al crear la tabla: " . mysqli_error(self::conexion_db()) );
        }

        return true;
    }
}

/* Clase para hacer las consultas Insertar, Eliminar y Actualizar */
class consultasSQL{
    public static function InsertSQL($tabla, $campos, $valores) {
      
       if (!$consul = ejecutarSQL::consultar("insert into $tabla ($campos) VALUES($valores)")) {
            die("Ha ocurrido un error al insertar los datos en la tabla $tabla");
        }

        return $consul;
    }
    public static function DeleteSQL($tabla, $condicion) {
        if (!$consul = ejecutarSQL::consultar("delete from $tabla where $condicion")) {
            die("Ha ocurrido un error al eliminar los registros en la tabla $tabla");
        }
        return $consul;
    }
    public static function UpdateSQL($tabla, $campos, $condicion) {
        if (!$consul = ejecutarSQL::consultar("update $tabla set $campos where $condicion")) {
            die("Ha ocurrido un error al actualizar los datos en la tabla $tabla");
        }
        return $consul;
    }
}


?>