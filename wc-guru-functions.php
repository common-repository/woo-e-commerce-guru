<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$pos;
$cart;
$total_integrar;
$integrados;
$tipo;

require dirname(__FILE__).'/wc-guru-functions-webservice.php';
require dirname(__FILE__).'/wc-guru-access-database.php';

add_action( 'wp_ajax_ECGU_GetContatos', 'ECGU_GetContatos' );
add_action( 'wp_ajax_ECGU_GetProdutos', 'ECGU_GetProdutos' );
add_action( 'wp_ajax_ECGU_GetVendas', 'ECGU_GetVendas' );
add_action( 'wp_ajax_ECGU_IntegrarDados', 'ECGU_IntegrarDados' );
add_action( 'wp_ajax_ECGU_GetQntdObjetos', 'ECGU_GetQntdObjetos' );
add_action( 'wp_ajax_ECGU_IntegrarCategorias', 'ECGU_IntegrarCategorias' );
add_action( 'wp_ajax_ECGU_CreateTable_teste', 'ECGU_CreateTable_teste' );
add_action( 'wp_ajax_ECGU_CreateView_teste', 'ECGU_CreateView_teste' );
add_action( 'wp_ajax_ECGU_CadastrarCliente', 'ECGU_CadastrarCliente' );
add_action( 'wp_ajax_ECGU_SalvarUsuario', 'ECGU_SalvarUsuario' );
add_action( 'wp_ajax_ECGU_SetNewsletter', 'ECGU_SetNewsletter' );
add_action( 'wp_ajax_ECGU_RecuperarChaveIntegracao', 'ECGU_RecuperarChaveIntegracao' );
add_action( 'ECGU_IntegracaoGuru_hook', 'ECGU_IntegracaoGuru' );
if ( ! wp_next_scheduled( 'ECGU_IntegracaoGuru_hook' ) ) {
    wp_schedule_event( time(), 'hourly', 'ECGU_IntegracaoGuru_hook' );
}

function ECGU_IntegracaoGuru() 
{
  	ECGU_IntegrarContatos();
	ECGU_IntegrarCategorias();
	ECGU_IntegrarProdutos();
	ECGU_IntegrarVendas();
	ECGU_IntegrarVendasExcluidas();
	ECGU_MountCart();
	ECGU_IntegrarNewsletter();
	ECGU_UpdateDataIntegracao(date('Y-m-d H:i:s'));	
}


function ECGU_AdminMenuItem() 
{
  // criamos a pagina de opções com esta função
  add_menu_page(
	  'E-Commerce Guru',
	  'E-Commerce Guru',
	  'manage_options', 
	  'e-guru', 
	  'ECGU_AddMenuGuru',
	  plugin_dir_url(__FILE__) . 'images/logo.png'
	);
} 
// Interior da página de Opções.
// Esta função imprime o conteúdo da página no ecrã.
// O HTML necessário encontra-se já escrito.
function ECGU_AddMenuGuru() 
{	
wp_enqueue_style('plugin_css'); wp_enqueue_script('plugin_js'); wp_enqueue_script('mask_plugin_js'); $user = ECGU_GetUser(); 
$razaoSocial = ECGU_GetRazaoSocial()[0]->razaoSocial; $url = ECGU_GetUrl()[0]->url;?>
<div class="wrap">
	<div class="container">
	  <?php  
	  global $integrados; 
	  echo $integrados;//screen_icon(); ?>
	  <h3>Configurações	de integração E-CommerceGuru</h3>
	  <h4>Última integracao <?php echo date('d/m/Y H:m:s');?></h4>
	  <label id="qtdIntegracao" class=""></label>		   
	  <div class="row">
	  	<div class="card border-primary col-sm-6">
	  		<div class="card-header" style=""><h4>Selecione plugin de newsletter para importação</h4></div>
	  		<div class="card-body">
			  	<?php $plugin_newsletter = ECGU_GetNewsletterPlugin(); ?>
	  			<input type="radio" name="newsletter" value="Email Subscribers & Newsletters" <?php echo ($plugin_newsletter[0]->newsletter == "Email Subscribers & Newsletters") ? "checked" : null; ?>> <a target="_blank" rel="noopener noreferrer" href="https://br.wordpress.org/plugins/email-subscribers/">Email Subscribers & Newsletters</a> <br>
	  			<input type="radio" name="newsletter" value="Newsletter" <?php echo ($plugin_newsletter[0]->newsletter == "Newsletter") ? "checked" : null; ?>> <a target="_blank" rel="noopener noreferrer" href="https://br.wordpress.org/plugins/newsletter/">Newsletter</a> <br>
	  			<input type="radio" name="newsletter" value="MailPoet Newsletters" <?php echo ($plugin_newsletter[0]->newsletter == "MailPoet Newsletters") ? "checked" : null; ?>> <a target="_blank" rel="noopener noreferrer" href="https://br.wordpress.org/plugins/wysija-newsletters/">MailPoet Newsletters</a> <br>
	  			<input type="radio" name="newsletter" value="Empty" <?php echo ($plugin_newsletter[0]->newsletter == "Empty") ? "checked" : null; ?>>Nenhum <br>
	  			<button onclick="ECGU_SetNewsletter()" class="btn btn-success">Salvar</button>
	  		</div>
	  	</div>
	  	<?php if ($user->chaveUsuario != null) { ?>
	  		<div class="col-sm-6">
	  			<div class="card border-primary" style="display: block; height: parent;">
	  				<button style="display: block; margin: auto;" id="btn-integrar" class="btn btn-primary btn-lg" onclick="ECGU_IntegrarAgora()">Integrar Agora</button>
	  			</div>
	  		</div>
	  	<?php } ?>
	  	
	  </div>
		<div class="row">
			<div class="card border-primary col-sm-6">
			    <div class="card-header" style=""><h3>Dados Conexão</h3></div>
				<div class="card-body">
					<form method="post">
						<div class="row">
						    <div class="form-group col-sm-6">
							    <label>Login</label>
							    <input type="text" name="Login" id="login" class="form-control" value="<?php echo $user->login?>">
							</div>
							<div class="form-group col-sm-6">
							    <label>Senha</label>
							    <input type="password" name="Senha" id="senha" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-12">
								<label>Chave Integração</label>
							    <input type="text" name="ChaveUsuario" id="chaveUsuario" class="form-control" value="<?php echo $user->chaveUsuario?>">
							</div>
						</div>
						<button type="button" class="btn btn-primary" onclick="ECGU_GetChaveIntegracao()">Salvar dados</button>	
					</form>	
			  	</div>	
		  	</div>
		  	
		  	<div class="col-sm-6">
		  		<div class="card border-primary">
				  <div class="card-header" style="">
				  	<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			          <h3>Nova Conta</h3>
			        </button>
				  	</div>
				  	<div id="collapseOne" class="collapse">
					  <div class="card-body">
						  <form method="post">
						    <div class="row">
						    	<div class="form-group col-lg-6">
							    	<label>Razão Social</label>
							    	<input type="text" name="RazaoSocial" id="razaoSocial" class="form-control" value="<?php echo $razaoSocial?>">
							    </div>
							    <div class="form-group col-lg-6">
							    	<label>Nome Fantasia</label>
							    	<input type="text" name="NomeFantasia" id="nomeFantasia" class="form-control">
							    </div>
						    </div>
						    <div class="row">
							    <div class="form-group col-lg-6">
							    	<label>CNPJ</label>
							    	<input type="text" onblur="javascript:ECGU_Validar()" name="CNPJ" id="cnpj" class="form-control" data-mask="00/00/0000">
							    	<div id="span-cnpj"><span></span></div>
							    </div>
							    <div class="form-group col-lg-6">
							    	<label>Site</label>
							    	<input type="text" name="Dominio" id="dominio" class="form-control" value="<?php echo $url?>">
							    </div>
						    </div>
						    <div class="row">
							    <div class="form-group col-lg-6">
							    	<label>Email</label>
							    	<input type="email" name="Email" id="email" class="form-control">
							    </div>
							    <div class="form-group col-lg-6">
							    	<label>Nome de Usuário</label>
							    	<input type="text" name="NomeUsuario" id="nomeUsuario" class="form-control">
							    </div>
						    </div>
						    <div class="row">
							    <div class="form-group col-lg-6">
							    	<label>Senha</label>
							    	<input type="password" name="SenhaCadastro" id="senhaCadastro" class="form-control">
							    </div>
								<div class="form-group col-lg-6">
								    <label>Confirme sua Senha</label>
								    <input type="password" name="ConfirmacaoSenhaCadastro" id="confimacaoSenhaCadastro" class="form-control">
							 	</div>
						    </div>
						  </form>

						    <button class="btn btn-primary" id="btn-cadastar" onclick="javascript:ECGU_ValidarSenha()">Cadastrar</button>	
				  		</div>
				  	</div>
				</div>
		  	</div>
		</div>
		
	</div>

</div>
<?php
}

function ECGU_IntegrarDados()
{
	global $integrados;
	ECGU_IntegrarContatos();
	ECGU_IntegrarCategorias(); // depois testar categorias list
	ECGU_IntegrarProdutos();
	ECGU_IntegrarVendas();
	ECGU_IntegrarVendasExcluidas();
	ECGU_MountCart();
	ECGU_IntegrarNewsletter();
	ECGU_UpdateDataIntegracao(date('Y-m-d H:i:s'));

}

function ECGU_GetQntdObjetos()
{
	global $integrados;
	$total_integrar;
	$tipo;
	$dados = array('Qnt' => $GLOBALS['integrados'], 'Total' => $GLOBALS['integrados'], 'Obj' => $tipo);
	echo json_encode($dados);
	wp_die();
}

// INTEGRAÇÃO -------------------------------------------------------------------------------------


// CONTATOS ---------------------------------------------------------------------------------------

function ECGU_IntegrarContatos()
{	
	$contatos = ECGU_GetContatos();
	$dados = array();
	foreach ($contatos as $contato) {
		$endereco = array(
		'Logradouro' => $contato->logradouro, 
		'Numero' => null,
		'Complemento' => $contato->complemento,
		'Bairro' => $contato->bairro,
		'Cidade' => $contato->cidade,
		'Estado' => $contato->estado,
		'CEP' => $contato->cep,
		'Pais' => $contato->pais);
		$telefone = array(
		'TipoTelefone' => 1,
		'DDI' => "55",
		'DDD' => substr($contato->telefone, 0, 2),
		'Telefone' => substr($contato->telefone, 2, 9));
		$email = array(
		'TipoEmail' => 1,
		'Email' => $contato->email);
		$d = array(
		'ContatoCI' => $contato->id,
		'Nome' => $contato->nome,
		'Sexo' => null,
		'DataNascimento' => null,
		'CPF' => null,
		'RG' => null,
		'EmpresaId' => null,
		'Endereco' => $endereco,
		'Email' => $email,
		'Telefone1' => $telefone,
		'Telefone2' => null,
		'Telefone3' => null);
		array_push($dados, $d);
	}
	$total_integrar = sizeof($dados);
	$integrados = 0;
	foreach ($dados as $contato) {
		$result = ECGU_CadastrarContato(json_encode($contato));
		if(strpos($result->mensagem, 'Registro Inserido') !== false){
			ECGU_SalvarIntegracao($contato['ContatoCI'], 'Customer');
		}
		$integrados++;
	}
	return json_encode($result);
}

// CATEGORIAS ---------------------------------------------------------------------------------------

function ECGU_IntegrarCategorias()
{
	$categorias = ECGU_GetCategorias();
	$dados = array();
	foreach ($categorias as $categoria) {
		$cat = array(
		'CodigoIntegracao' => $categoria->id,
		'NomeProdutoCategoria' => $categoria->nome,
		'ProdutoCategoriaPaiCI' => $categoria->categoriaPaiId,
		'TipoCategoriaId' => 1);
		array_push($dados, $cat);
	}
	$result = ECGU_Cat($dados, 1);
	return $result;
}

function ECGU_InserirCategorias($categorias)
{
	foreach ($categorias as $categoria) {
		$result = ECGU_CadastrarCategoria(json_encode($categoria));
	}
	return json_encode($result);
}

// LOGICA DAS CATEGORIAS --------------------------------------------------------------------

function ECGU_Cat($categorias, $indice)
{
	$catInserir = array();
	$catFilhas = array();
	foreach ($categorias as $categoria) {
		if(ECGU_VerificaPai($categorias, $categoria['ProdutoCategoriaPaiCI'])){
			array_push($catFilhas, $categoria);
		}else{
			$categoria['TipoCategoriaId'] = $indice;
			array_push($catInserir, $categoria);
		}
	}
	if (sizeof($catInserir) != 0) {
		ECGU_InserirCategorias($catInserir);
	}
	
	if(sizeof($catFilhas) != 0){
		ECGU_Cat($catFilhas, $indice+1);
	}
	return;
}

function ECGU_VerificaPai($categorias, $idPai)
{
	foreach ($categorias as $categoria) {
		if($categoria['CodigoIntegracao'] == $idPai){
			return true;
		}
	}
	return false;
}

// CLIENTE -------------------------------------------------------------------------------------

function ECGU_RecuperarChaveIntegracao()
{ 
	$userId = sanitize_email($_POST['userId']);
	$accessKey = sanitize_text_field($_POST['accessKey']);
	$revendaId = sanitize_text_field($_POST['revendaId']);

	$result = json_decode(ECGU_RecoveryToken($userId, $accessKey), true);
	$t = $result['authenticated']; 
	if($result['authenticated'] == true){
		ECGU_SalvarDadosConexaoToken($userId, $result['chaveUsuario'], $revendaId);
		echo json_encode(array('status' => 'Ok', 'chaveUsuario' => $result['chaveUsuario']));
		wp_die();
	}else{
		echo json_encode(array('status' => 'NotOk'));
		wp_die();
	}	
}

function ECGU_FormatCnpj($cnpj){
	$cnpj = str_replace('.', '', $cnpj);
	$cnpj = str_replace('/', '', $cnpj);
	$cnpj = str_replace('-', '', $cnpj);
	return $cnpj;
}

function ECGU_CadastrarCliente()
{
	$newUserData = $_POST['newUserData'];
	$newUserData['Email'] = sanitize_email($newUserData['Email']);
	$newUserData['RazaoSocial'] = sanitize_text_field($newUserData['NomeFantasia']);
	$newUserData['NomeFantasia'] = sanitize_text_field($newUserData['NomeFantasia']);
	$newUserData['NomeUsuario'] = sanitize_text_field($newUserData['NomeUsuario']);
	$newUserData['CNPJ'] = ECGU_FormatCnpj(sanitize_text_field($newUserData['CNPJ']));
	$newUserData['Senha'] = sanitize_text_field($newUserData['Senha']);
	$newUserData['Site'] = sanitize_text_field($newUserData['Site']);
	$json_usuario = json_encode($newUserData);
 
    $result = ECGU_CadastrarCliente_usuario($json_usuario);
	if($result == false){
		echo json_encode(array('status' => 'NotOk'));
	}else{
		if(strpos($result->mensagem[0], 'Registro Inserido') !== false ){
			$chaveUsuario = $result->chaveUsuario[0];
			ECGU_SalvarDadosConexaoToken($newUserData['Email'], $chaveUsuario, $newUserData['RevendaId']);
			echo json_encode(array('status' => 'Ok', 'chaveUsuario' => $tok));
		}
		// $result = json_decode($result, true);
		// $msg = explode(":", $result['mensagem'][0]);
		// $tok = explode(":", $result['chaveUsuario'][0]);
		// $codigoVenda = $msg[0];
		// $chaveUsuario = $tok[0];
		// if ($codigoVenda == 'Registro Inserido') {
		// 	ECGU_SalvarDadosConexaoToken($newUserData['Email'], $chaveUsuario, $newUserData['RevendaId']);
		// 	echo json_encode(array('status' => 'Ok', 'chaveUsuario' => $tok));
		// }
	}
	wp_die();
}

// PRODUTOS ------------------------------------------------------------------------------------

function ECGU_IntegrarProdutos()
{	
	setcookie("tipo", "Produtos");
	$d = array(
		'FabricanteCI' => null,
		'CategoriaProdutoCI' => null,
		'CodigoIntegracao' => "-8",
		'NomeProduto' => "Frete",
		'Descricao' => "Frete de entraga do produto",
		'Preco' => 0,
		'Estoque' => 1,
		'Ativo' => 1);
	ECGU_CadastrarProdutos(json_encode($d));
	$produtos = ECGU_GetProdutos();
	$dados = array();
	foreach ($produtos as $produto) {
		$d = array(
		'FabricanteCI' => null,
		'CategoriaProdutoCI' => "".$produto->categoriaId,
		'CodigoIntegracao' => "".$produto->id,
		'NomeProduto' => $produto->nome,
		'Descricao' => $produto->descricao,
		'Preco' => $produto->preco,
		'Estoque' => 1,
		'Ativo' => 1);
		array_push($dados, $d);	
	}
	$total_integrar = sizeof($dados);
	foreach ($dados as $produto) {
		$result = ECGU_CadastrarProdutos(json_encode($produto));	
		if(strpos($result->mensagem[0], 'Registro Inserido') !== false){
			ECGU_SalvarIntegracao($produto['CodigoIntegracao'], 'Product');
		}
	}
	//return json_encode($result);
}

// VENDAS --------------------------------------------------------------------------------------

function ECGU_IntegrarVendas()
{	
	global $tipo;
	global $integrados;
	global $total_integrar;

	$vendasIntegradas = array();

	$tipo = "Vendas";
	$vendas = ECGU_GetVendas();
	$dados = array();
	$total_integrar = sizeof($dados);
	foreach ($vendas as $venda) {
		$vendaContato = array(
		'CodigoIntegracao' => $venda->id,
		'Numero' => $venda->id,
		'DataVenda' => $venda->data,
		'ContatoCi' => $venda->usuarioId,
		'VendedorCi' => 'Ecommerce99',
		'NomeVendedor' => $venda->nomeEmpresa.' - E-Commerce',
		'Valor' => $venda->total,
		'ValorDesconto' => 0, // DEFINIDO PASSAR 0 - OS DESCONTOS SERÃO NOS PRODUTOS
		'NomeFormaPagamento' => $venda->metodoPagamento,
		'FilialNomeFantasia' => $venda->nomeEmpresa.' - E-Commerce');
		array_push($dados, $vendaContato);
		$result = ECGU_CadastrarVenda(json_encode($vendaContato));
		$partes = explode(":", $result->mensagem[0]);
		$codigoVenda = $partes[1];
		$msg = $partes[0];
		if($msg == "Registro Inserido"){
			array_push($vendasIntegradas, $vendaContato['CodigoIntegracao']);
		}
		$result = ECGU_IntegrarDetalhesVenda($codigoVenda, $vendaContato['CodigoIntegracao']);
	}
	ECGU_SalvarIntegracaoList($vendasIntegradas, "Order");
}

function ECGU_IntegrarVendasExcluidas()
{	
	$vendas = ECGU_GetVendasExcluidas();
	$dados = array();
	foreach ($vendas as $venda) {
		$vendaCancelada = array(
		'VendaContatoCI' => $venda->id);

		array_push($dados, $vendaCancelada);
		$result = ECGU_ExcluirVenda(json_encode($vendaCancelada));
	}
	return $result; 
}

function ECGU_IntegrarDetalhesVenda($codigoVenda, $vendaId)
{	
	$detalhesVenda = ECGU_GetDetalheVenda($vendaId);
	$dados = array();
	foreach ($detalhesVenda as $detalhe) {
		$detalheVenda = array(
		'VendaDetalheContatoCi' => "".$detalhe->detalheId,
		'VendaContatoId' => $codigoVenda,
		'ProdutoCi' => "".$detalhe->produtoId,
		'Quantidade' => $detalhe->quantidade,
		'Valor' => $detalhe->valor,
		'Desconto' => $detalhe->subtotal - $detalhe->valor);
		
		array_push($dados, $detalheVenda);
		
	}
	$result = ECGU_CadastrarDetalheVenda(json_encode($dados));
	return json_encode($result);
}

// NEWSLETTER ------------------------------------------------------------------------------------

function ECGU_IntegrarNewsletter()
{
	$plugin_newsletter = ECGU_GetNewsletterPlugin();
	$newsletter_emails;
	$script = '';
	switch ($plugin_newsletter[0]->newsletter) {
		case 'Email Subscribers & Newsletters':
			$script = "SELECT es_email_id as id, es_email_mail as email from wp_es_emaillist where es_email_status = 'Confirmed';";
			break;
		
		case 'MailPoet Newsletters':
			$script = "SELECT user_id as id, email from wp_wysija_user where status = 1;";
			break;

		case 'Newsletter':
			$script = "SELECT id, email from wp_newsletter where status = 'C' 
							and NOT EXISTS (
							SELECT EntidadeId 
							    from integracao as I
							    where I.EntidadeId = wp_newsletter.id and I.Entidade = 'Newsletter'
							)";
			break;

		default:
			return $results = array(); // verificar
			break;
	}

	$object_newsletter_emails = ECGU_ScriptNewsletter($script);
	$newsletter_emails = array();
	foreach ($object_newsletter_emails as $object_email) {
		$newsletter_email = array(
			'CodigoIntegracao' => $object_email->id,
			'Ativo' => true,
			'Email' => $object_email->email
		);
		echo json_encode($newsletter_email);
		array_push($newsletter_emails, $newsletter_email);
	}
	$result = ECGU_CadastrarNewsletterEmail(json_encode($newsletter_emails));
	$result = json_decode($result, true);
	$integrados = $result['integrados'];
	ECGU_SalvarIntegracao($integrados, "Newsletter");
	echo json_encode($result);
}

// ORCAMENTOS -------------------------------------------------------------------------------------

function ECGU_IntegrarOrcamento($carrinhoAbandonado)
{
	$result = ECGU_GetCart();
	foreach ($result as $cart_user) {
		# code...
		$carrinhoAbandonado = ECGU_MountCart($cart_user);
	}
}

// MONTAGEM CARRINHO ABANDONADO -------------------------------------------------------------------

function ECGU_MountJsonCart($carrinhoAbandonado, $usuarioId)
{
	$orcamentoDetalhe = array();
	$total = 0;
	foreach ($carrinhoAbandonado as $item) {
		$total += $item['line_subtotal'];
	}
	$date = date('Y-m-d H:i:s');
	$orcamento = array(
		'OrcamentoContatoCi' => ''.$usuarioId,
		'DataOrcamento' => $date,
		'ContatoCi' => ''.$usuarioId,
		'VendedorCi' => 'Ecommerce99',
		'Valor' => $total,
		'NomeStatus' => 'Aberto');
	var_dump($orcamento);
	$result = ECGU_CadastrarOrcamento(json_encode($orcamento));

	echo $result;

	$result = json_decode($result, true);
	$partes = explode(":", $result['mensagem'][0]);
	$codigoOrcamento = $partes[1];
	// integrar o orçamento
	// pegar o id retornado($orcamentoId)
	$orcamentoDetalhe = array();
	foreach ($carrinhoAbandonado as $item) {
		# code...
		$detalhe = array(
			'OrcamentoContatoId' => ''.$codigoOrcamento,
			'OrcamentoDetalheContatoCI' => ''.$codigoOrcamento.$item['product_id'],
			'DataOrcamento' => $date,
			'StoreCi' => '',
			'ProdutoCI' => ''.$item['product_id'],
			'Quantidade' => $item['quantity'],
			'CustomerCi' => ''.$usuarioId,
			'Valor' =>  $item['line_subtotal']/$item['quantity']);
		echo json_encode($detalhe);
		$result_detalhe = ECGU_CadastrarDetalheOrcamento(json_encode($detalhe));
		echo $result_detalhe;
		echo "----------------------------------------";
		// integrar detalhes orcamento a cada iteracao
		array_push($orcamentoDetalhe, $detalhe);
	}
}

function ECGU_MountCart()
{	
	global $pos;
	global $cart;
	$pos = 18;
	$results = ECGU_GetCart();
	foreach ($results as $cart_list) {
		$pos = 18;
		$cart = $cart_list->cart;
		$val = $cart[18];
		$carrinhoAbandonado = ECGU_MountArray($val);
		ECGU_MountJsonCart($carrinhoAbandonado, $cart_list->user_id);
		// salvar na tabela de orcamento as strings dos carrinhos
		ECGU_UpdateOrcamento($cart_list->user_id, $cart_list->cart);
	}
	return $carrinhoAbandonado;
}

function ECGU_InitArray()
{		
	global $pos;
	global $cart;
	$val = ECGU_MountData();
	$pos--;
	$pos--;
	return ECGU_MountArray($val);
}

function ECGU_MountArray($val)
{
	global $pos;
	global $cart;
	$result = array();
	$pos += 3;
	if($val == 0)
	{
		return $result;
	}
	else
	{
		for($i=0;$i<$val;$i++){
			$key = ECGU_MountKey();
			$value = ECGU_MountValue();
			if(strlen($key)==32)
			{
				$result[$i] = $value;
			}
			else
			{
				$result[$key] = $value;
			}
		}
	}
	return $result;
}

function ECGU_MountKey()
{
	global $pos;
	global $cart;
	$pos++;
	$token = "";
	$token = ECGU_MountToken($token);
	$pos++;
	return ECGU_MountString($token);
}

function ECGU_MountString($val)
{
	global $pos;
	global $cart;
	$string = "";
	for($i=0; $i<$val; $i++){
		$pos++;
		$string .= $cart[$pos];
	}
	$pos+=3;
	return $string;
}

function ECGU_MountData()
{
	global $pos;
	global $cart;
	$pos++;
	$token = ECGU_MountToken($token);
	$pos++;
	return $token;
}

function ECGU_MountToken($token)
{
	global $pos;
	global $cart;
	$pos++;
	if($cart[$pos] == ';' || $cart[$pos] == ':'){
		return $token;
	}else
	{
		$token .= "$cart[$pos]";
	}
	return ECGU_MountToken($token);
}

function ECGU_MountValue()
{
	global $pos;
	global $cart;
	$result;
	switch ($cart[$pos]) {
		case 'a':
			$partial_array = ECGU_InitArray();
			$pos++;
			return $partial_array;
			break;

		case 's':
			$pos++;
			$token = "";
			$token = ECGU_MountToken($token, $pos);
			$pos++;
			return ECGU_MountString($token);
			break;

		case 'd':
		    $data = ECGU_MountData();
			return $data;
			break;

		case 'i':
			$data = ECGU_MountData();
			return $data;
			break;
		
		default:
			$pos++;
			return ECGU_MountValue($cart, $pos);
			break;
	}
	return $result;
}