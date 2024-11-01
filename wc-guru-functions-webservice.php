<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$url = 'http://api.ecommerceguru.com.br/';
$chave = '9E477448-4018-4269-AD2B-1F10719C7802';
$revenda = 1;
add_action( 'wp_ajax_ECGU_TesteWebservice', 'ECGU_TesteWebservice' );
	
	function ECGU_GetUser()
	{
		global $wpdb;
		$results = $wpdb->get_results("SELECT * from configuracao", OBJECT);
		return $results[0];
	}

	function ECGU_RecoveryToken($email, $senha)
	{
		
		$token = ECGU_GetToken();
		$data = array('Email' => $email, 'Senha' => $senha, 'RevendaId' => 5);
		$data_json = json_encode($data);
		$args = array(
			'method' => 'POST',
			'body' => $data_json,
			'headers' => array(
				'Content-Type' => 'application/json'
			)
		);
		 
		$response = wp_remote_post( 'http://api.ecommerceguru.com.br/Login/GetToken/GetChaveIntegracao', $args); 
		$body = $response['body'];
		return $body;
	}

	function ECGU_GetToken()
	{
		global $url, $chave, $revenda;
		$user = ECGU_GetUser();
		if($user->login == null){
			$data = array('ChaveUsuario' => $chave, 'revendaId' => $revenda );
		}else{
			$data = array('ChaveUsuario' => $user->chaveUsuario, 'revendaId' => $user->revenda);
		}
		$data_json = json_encode($data);
		$args = array(
			'method' => 'POST',
			'body' => $data_json,
			'headers' => array(
				'Content-Type' => 'application/json'
			)
		);
		 
		$response = wp_remote_post( 'http://api.ecommerceguru.com.br/Login/GetToken/ChaveUsuario', $args); 
		$body = json_decode($response['body']);
		return $body;
	}

	function SendData($token, $url, $data){
		$args = array(
			'method' => 'POST',
			'timeout' => '60',
			'body' => $data,
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer '.$token
			)
		);
		 
		$response = wp_remote_post( $url, $args); 
		$body = json_decode($response['body']);
		return $body;
	}

	function ECGU_CadastrarCliente_usuario($json_cliente){
		global $url;
		$token = ECGU_GetToken();
		if($token->authenticated == false){
			return false;
		}
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/ClienteUsuario/CadastroGuru', $json_cliente);
		return $result;

	}

	function ECGU_CadastrarContato($json_contato){	
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/contato', $json_contato);
		return $result;
	}

	function ECGU_CadastrarNewsletterEmail($json_newsletter_email){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/Newsletter/List', $json_newsletter_email);
		return $result;
	}

	function ECGU_CadastrarCategoria($json_categoria){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/ProdutoCategoria', $json_categoria);
		return $result;
	}

	function ECGU_CadastrarVenda($json_venda){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/VendaContato', $json_venda);
		return $result;
	}

	function ECGU_CadastrarDetalheVenda($json_detalhe_venda){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/VendaDetalheContato', $json_detalhe_venda);
		return $result;
	}

	function ECGU_ExcluirVenda($json_venda){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/ExcluirVenda', $json_venda);
		return $result;
	}

	function ECGU_CadastrarOrcamento($json_orcamento){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/orcamento', $json_orcamento);
		return $result;

	}

	function ECGU_CadastrarDetalheOrcamento($json_detalhe_orcamento){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/orcamentodetalhe', $json_detalhe_orcamento);
		return $result;
	}

	function ECGU_CadastrarProdutos($json_produto){
		global $url;
		$token = ECGU_GetToken();
		$token = $token->accessToken;
		$result = SendData($token, $url.'api/Produto', $json_produto);
		return $result;
	}
 
 