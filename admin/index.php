<?php 
    session_start();
    require_once SIIGO_RUTA.'/includes/conexion.php';
    require_once SIIGO_RUTA.'/includes/auth.php';
    require_once SIIGO_RUTA.'/includes/request.php';
    
    $boleano = false;
    $class = "error";//updated
    $message = "";

    $declaracion = "".SIIGO_RUTA.'/includes/declaracion.php';
	if ( !file_exists($declaracion) ){
		$miarchivo=fopen($declaracion,'w');
		fwrite($miarchivo,
			"<?php 
            define('PREFIJO', '".$table_prefix."');
			define( 'DB_NAME', '".DB_NAME."' );
			define( 'DB_USER', '".DB_USER."' );
			define( 'DB_PASSWORD', '".DB_PASSWORD."');
			define( 'DB_HOST', '".DB_HOST."' );?>"
		);
		fclose($miarchivo);
	}
   

    if ( auth::check($Object) ){
        $boleano = true;
    }
    
    
    if( isset($_POST['email']) && isset($_POST['key']) ){
        if (auth::save($_POST['email'], $_POST['key'], $Object) ){
            header('Location: '.$_SERVER['REQUEST_URI']);
        }
    }

    if ( isset($_SESSION["message"]) ){
        $message = $_SESSION["message"];
        session_destroy();
    }

    /*
        Usuario API:

        k.katikastiendasas@gmail.com

        Access Key:

        YzBlOTQ4ZTYtYjdhNC00Nzc0LTk2MjQtNGJmODI2ZDVmOTAwOjZIez43TWNVOEg=
    
    */

    if ( !$boleano ){
?>
    <?php require_once SIIGO_RUTA.'/view/login.php'; ?>
<?php 
    }else{
?>

<?php require_once SIIGO_RUTA.'/view/dashboard.php'; ?>

<?php 
    }//fin del condicional

?>
<script>
    function closeMensaje(e){
        e.target.parentElement.style.display = 'none';
    }

</script>
<style>
.table100.ver1 {
    border-radius: 10px;
    overflow: hidden;
}
table{
    width: 100%;
}
.table100 {
    position: relative;
}
.table100 {
    background-color: #fff;
}
.m-b-110 {
    margin-bottom: 60px;
}
.table100-head {
    width: 100%;
    top: 0;
    left: 0;
}

.table100.ver1 th {
    font-size: 15px;
    color: #fff;
    line-height: 1.4;
    background-color: #6c7ae0;
    text-align:center;
}

.table100-head th {
    padding-top: 18px;
    padding-bottom: 18px;
}

.table100.ver1 td {
    font-size: 12px;
    color: #808080;
    line-height: 1.4;
}

.table100-body td {
    padding-top: 16px;
    padding-bottom: 16px;
}

.column1 {
    width: 15%;
}

.column2 {
    width: 15%;
}
.column3 {
    width: 40%;
}
.column4 {
    width: 30%;
}
th, td {
    font-weight: unset;
    padding-right: 10px;
    text-align: center;
}
th {
    text-align: center;
}

.table100.ver1 .table100-body tr:nth-child(even) {
    background-color: #f8f6ff;
}
.__form_sg_api{
        margin-top: 20px;
    }

    .__form_sg_api .group_input{
        width: 100%;
    }

    .form-label {
        margin-bottom: 0.5rem;
        font-size: 12px;
    }

    .form-control {
        margin-top: 8px !important;
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #212529;
        background-color: #f8fafc;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .__form_sg_api .input_style_0 {
        background: #f2f2f2;
        width: 100%;
        border: 0;
        margin: 0 0 15px;
        padding: 0 15px;
        box-sizing: border-box;
        font-size: 14px;
        min-height: 46px;
        border-radius: 0;
    }

    .__form_sg_api .btn_style_0 {
        font-family: "Poppins", sans-serif;
        text-transform: uppercase;
        background: #FE6700;
        width: 100%;
        border: 0;
        padding: 0 15px;
        min-height: 46px;
        color: #FFFFFF;
        font-size: 14px;
        -webkit-transition: all 0.3 ease;
        transition: all 0.3 ease;
        cursor: pointer;
    }

    .__form_sg_api .btn_style_0.btnDisabled, .__form_sg_api .btn_style_0.btnDisabled:hover {
        background-color: #8c8c8c;
    }

    .mt-35{
        margin-top: 35px;
    }
</style>
