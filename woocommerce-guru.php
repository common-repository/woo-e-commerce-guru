<?php defined('ABSPATH') or die( 'No script kiddies please!' ); 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Plugin name: E-CommerceGuru for Woo
* Description: Plugin E-Commerce Guru para WooCommerce
* Version: 1.0
* Author: E-CommerceGuru
*/

register_activation_hook( __FILE__, 'ewp_install_hook' );


function ewp_install_hook()
{
	ECGU_CreateTableConfiguracao();
	ECGU_CreateTableIntegracao();
	ECGU_CreteTableOrcamento();
}

$pluginURL = plugins_url("",__FILE__);
$CSSURL = "$pluginURL/assets/bootstrap/css/bootstrap.min.css";
wp_register_style( 'plugin_css', $CSSURL);
$JSURL = "$pluginURL/assets/bootstrap/js/bootstrap.min.js";
wp_register_script( 'plugin_js', $JSURL);
$MaskJSURL = "$pluginURL/assets/jquery.mask.min.js";
wp_register_script( 'mask_plugin_js', $MaskJSURL);

require dirname(__FILE__).'/wc-guru-functions.php';
require dirname(__FILE__).'/wc-guru-javascript-functions.php';
require dirname(__FILE__).'/wc-guru-data-structure.php';
add_action( 'admin_menu', 'ECGU_AdminMenuItem' );