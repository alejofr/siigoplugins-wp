<?php 

/*
Plugin Name: OnexDev-Siigo
Plugin URI: https://onexmedia.co
Description: Plugins Para utilizar la Api de Siigo
Version: 1.0
Author: Onex Media
Author URI: https://onexmedia.co
License: GPL2
*/

defined( 'ABSPATH' ) || exit;

define( 'SIIGO_NOMBRE', 'Siigo Api' );

if ( ! defined( 'SIIGO_RUTA' ) ) {
	define( 'SIIGO_RUTA', plugin_dir_path(__FILE__));
}

include(SIIGO_RUTA.'/includes/horario.php');
 
if ( ! function_exists( 'is_plugin_active' ) ){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	//register_deactivation_hook(__FILE__, 'error_activacion');
}
if ( is_plugin_active('woocommerce/woocommerce.php') ){

	register_activation_hook(__FILE__,'activar');
	//registrando el hook menu
	add_action( 'admin_menu', 'sig_menu_administrador' );	
}



function activar(){
	define( 'SIGO_STATUS', 'actvado' );
	
	
	//conexion
	include(SIIGO_RUTA.'/includes/conexion.php');

	if(!ejecutarSQL::check_table('sg_credentials')){
		//Creamos la Tabla Crendenciales
		ejecutarSQL::create_sg_credentials_table();
	}

	if (!ejecutarSQL::check_table('sg_configuracion_jobs')){
		//creamos la tabla configuacion
		ejecutarSQL::create_sg_configuracion_jobs_table();	
	}

}

//Menu y Submenu
function sig_menu_administrador()
{
	add_menu_page(SIIGO_NOMBRE,SIIGO_NOMBRE,'manage_options',SIIGO_RUTA.'/admin/index.php');
}



?>