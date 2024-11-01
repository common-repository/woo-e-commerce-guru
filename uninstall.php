<?php  
if ( ! defined( 'ABSPATH' ) ) exit;
if (! defined('WP_UNINSTALL_PLUGIN')) {
	# code...
	die();
}

global $wpdb;
	
$charset_collate = $wpdb->get_charset_collate();

$sql = "DROP TABLE integracao";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//dbDelta( $sql );

?>