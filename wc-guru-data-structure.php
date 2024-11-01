<?php  
if ( ! defined( 'ABSPATH' ) ) exit;
function ECGU_CreateTableConfiguracao()
{
	# code...
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE configuracao (
	id int AUTO_INCREMENT not null UNIQUE, 
	login varchar(155), 
	chaveUsuario varchar(155), 
	revenda int,
	dataUltimaIntegracao datetime DEFAULT '0000-00-00 00:00:00' not null,
	newsletter varchar(55) DEFAULT 'Empty' not null) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	$data = array(
	'login' => null,
	 'chaveUsuario' => null,
	 'revenda' => null);
	$wpdb->insert( 'configuracao', $data );
}

function ECGU_CreateTableIntegracao()
{
	# code...
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE integracao (
	IntegracaoId int not null PRIMARY key AUTO_INCREMENT, 
	Entidade varchar(15) not null, 
	EntidadeId int not null, 
	DataIntegracao datetime not null) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

function ECGU_CreteTableOrcamento()
{
	# code...
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE orcamento (
	OrcamentoId int not null PRIMARY key AUTO_INCREMENT, 
	Conteudo varchar(10000) not null, 
	CustomerId int not null) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

function ECGU_DropTableTeste()
{
	# code...
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "DROP TABLE tabela_teste";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}