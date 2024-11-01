<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
function ECGU_SalvarIntegracao($entidadeId, $entidade)
{
	global $wpdb;
	$data = array(
		'Entidade' => $entidade,
	 	'EntidadeId' => $entidadeId);
	$wpdb->insert( 'integracao', $data );	
}

function ECGU_SalvarIntegracaoList($entidades, $entidade)
{
	foreach ($entidades as $entidadeId) {
		global $wpdb;
		$data = array(
			'Entidade' => $entidade,
			'EntidadeId' => $entidadeId);
		$wpdb->insert( 'integracao', $data );	
	}
}

function ECGU_SetNewsletter()
{
	$newsletter = sanitize_text_field($_POST['newsletter']);
	global $wpdb;
	$data = array(
	 'newsletter' => $newsletter);
	$where = array('id' => 1);
	$result = $wpdb->update( 'configuracao', $data, $where, $format = null, $where_format = null );
	echo $result;
	wp_die();
}

function ECGU_UpdateOrcamento($customerId, $conteudo)
{
	global $wpdb;
	$data = array(
	 'conteudo' => $conteudo);
	$where = array('CustomerId' => $customerId);
	$wpdb->update( 'orcamento', $data, $where, $format = null, $where_format = null );
}

function ECGU_SalvarUsuario()
{
	$user = $_POST['dados'];
	$login = sanitize_text_field($user['Login']);
	$senha = sanitize_text_field($user['Senha']);
	$revendaId = sanitize_text_field($user['RevendaId']);
	ECGU_SalvarDadosConexao($login, $senha, $revendaId);
	wp_die();
}

function ECGU_SalvarDadosConexao($login, $senha, $revendaId)
{
	# code...
	global $wpdb;
	$data = array(
	'login' => $login,
	 'senha' => $senha,
	 'revenda' => $revendaId);
	$where = array('id' => 1);
	$wpdb->update( 'configuracao', $data, $where, $format = null, $where_format = null );
}

function ECGU_SalvarDadosConexaoToken($login, $token, $revendaId)
{
	# code...
	global $wpdb;
	$data = array(
	'login' => $login,
	'chaveUsuario' => $token,
	'revenda' => $revendaId);
	$where = array('id' => 1);
	$wpdb->update( 'configuracao', $data, $where, $format = null, $where_format = null );
}

function ECGU_UpdateDataIntegracao($date)
{
	global $wpdb;
	$data = array(
	'dataUltimaIntegracao' => $date);
	$where = array('id' => 1);
	$wpdb->update( 'configuracao', $data, $where, $format = null, $where_format = null );
}

function ECGU_GetUrl()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT (CASE WHEN O.option_name='siteurl' 
	 									THEN O.option_value 
	 									ELSE NULL 
	 								END) AS 'url'
                                    FROM wp_options O
                                    WHERE O.option_name='siteurl';", OBJECT);
	return $results;
}

function ECGU_GetNewsletterPlugin()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT newsletter from configuracao where id = 1", OBJECT);
	return $results;
}

function ECGU_ScriptNewsletter($script)
{
	global $wpdb;		
	$results = $wpdb->get_results("$script", OBJECT);
	return $results;
}

function ECGU_GetRazaoSocial()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT (CASE WHEN O.option_name='blogname' 
	 									THEN O.option_value 
	 									ELSE NULL 
	 								END) AS 'razaoSocial'
                                    FROM wp_options O
                                    WHERE O.option_name='blogname';", OBJECT);
	return $results;
}

function ECGU_GetContatos()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT ID as id, user_email as email, R.first_name as nome, R.last_name as sobrenome, R.billing_address_1 as logradouro, R.billing_address_2 as complemento, R.billing_city as cidade, R.billing_state as estado, R.billing_country as pais, R.billing_postcode as cep, R.billing_phone as telefone
	FROM wp_users 
	INNER JOIN
		(SELECT FN.user_id, FN.first_name, LN.last_name, BA.billing_address_1, BA2.billing_address_2, C.billing_city, S.billing_state, CO.billing_country, PC.billing_postcode, PH.billing_phone
		 FROM 
		 (SELECT UM.user_id, UM.meta_value AS 'first_name'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'first_name') FN
						INNER JOIN
		 (SELECT UM.user_id, UM.meta_value AS 'last_name'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'last_name') LN ON FN.user_id = LN.user_id
		 INNER JOIN
		 (SELECT UM.user_id, UM.meta_value AS 'billing_address_1'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'billing_address_1') BA ON LN.user_id = BA.user_id
		 INNER JOIN
		 (SELECT UM.user_id,  UM.meta_value AS 'billing_address_2'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'billing_address_2') BA2 ON BA.user_id = BA2.user_id
		 INNER JOIN
		 (SELECT UM.user_id, UM.meta_value AS 'billing_city'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'billing_city') C ON BA2.user_id = C.user_id
		 INNER JOIN
		 (SELECT UM.user_id, UM.meta_value AS 'billing_state'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'billing_state') S ON C.user_id = S.user_id
		 INNER JOIN
		 (SELECT UM.user_id, UM.meta_value AS 'billing_country'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'billing_country') CO ON S.user_id = CO.user_id
         INNER JOIN
		 (SELECT UM.user_id, UM.meta_value AS 'billing_postcode'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'billing_postcode') PC ON CO.user_id = PC.user_id
		 INNER JOIN
		 (SELECT UM.user_id, UM.meta_value  AS 'billing_phone'
						FROM wp_usermeta UM 
						inner JOIN wp_users U on UM.user_id = U.ID 
						where UM.meta_key = 'billing_phone') PH ON PC.user_id = PH.user_id
		 
						) R
		 
		on id = R.user_id
        where NOT EXISTS (
        SELECT EntidadeId
            from integracao as I
            where I.EntidadeId = wp_users.ID and I.Entidade = 'Customer'
        )", OBJECT);
	return $results;
	//echo ECGU_IntegrarContatos($results);
	//wp_die();
}

function ECGU_GetCategorias()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT T.term_id as id, T.term_group as grupo, T.name as nome, TT.description as descricao, TT.parent as categoriaPaiId from wp_terms T
		INNER JOIN
		wp_term_taxonomy TT
		ON T.term_id = TT.term_id
		where TT.taxonomy = 'product_cat'", OBJECT);
	return $results;
}

function ECGU_GetProdutos()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT ID as id, post_title as nome, post_content as descricao, post_status, SEL._regular_price as preco, 
	SEL._stock_status as ativo, SEL._stock as estoque, TT.term_taxonomy_id as categoriaId, post_modified
	from wp_posts 
	INNER JOIN 
			(SELECT PR.post_id, PR._regular_price, ST._stock_status, STO._stock 
			FROM 
				(SELECT PM.post_id, (CASE WHEN PM.meta_key='_regular_price' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_regular_price'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'product' 
					and PM.meta_key = '_regular_price') PR
	    	INNER JOIN 
	    		(SELECT PM.post_id, (CASE WHEN PM.meta_key='_stock_status' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_stock_status'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'product' 
					and PM.meta_key = '_stock_status') ST ON PR.post_id = ST.post_id
			INNER JOIN 
				(SELECT PM.post_id, (CASE WHEN PM.meta_key='_stock' 
										THEN PM.meta_value 
										ELSE NULL 
										END) AS '_stock'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'product' 
					and PM.meta_key = '_stock') STO ON ST.post_id = STO.post_id
		) SEL ON ID = SEL.post_id
        LEFT JOIN
        wp_term_relationships TR ON TR.object_id = id
    	INNER JOIN
    	wp_term_taxonomy TT ON TR.term_taxonomy_id = TT.term_taxonomy_id
    	where TT.taxonomy = 'product_cat' and (NOT EXISTS (
        SELECT EntidadeId
            from integracao as I 
            WHERE I.EntidadeId = wp_posts.ID and I.Entidade = 'Product'
        ) or post_modified > (SELECT dataUltimaIntegracao from configuracao))", OBJECT);
		return $results;
		//echo ECGU_IntegrarProdutos($results);
		//wp_die();
}

function ECGU_GetVendas()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT ID as id, post_date as data, SEL._order_total as total, SEL._cart_discount as desconto, SEL._customer_user as usuarioId, post_status as status, SEL._payment_method_title as metodoPagamento, post_modified, (SELECT 
																				(CASE WHEN O.option_name='blogname' 
	 																				THEN O.option_value 
	 																				ELSE NULL 
	 																			END) AS 'blogname'
																				FROM wp_options O 
																				where O.option_name = 'blogname') as nomeEmpresa
	from wp_posts 
	INNER JOIN 
			(SELECT PR.post_id, PR._order_total, ST._cart_discount, STO._customer_user, STP._payment_method_title
			FROM 
				(SELECT PM.post_id, (CASE WHEN PM.meta_key='_order_total' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_order_total'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_order_total') PR
	    	INNER JOIN 
	    		(SELECT PM.post_id, (CASE WHEN PM.meta_key='_cart_discount' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_cart_discount'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_cart_discount') ST ON PR.post_id = ST.post_id
             INNER JOIN 
	    		(SELECT PM.post_id, (CASE WHEN PM.meta_key='_customer_user' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_customer_user'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_customer_user') STO ON ST.post_id = STO.post_id
             INNER JOIN 
	    		(SELECT PM.post_id, (CASE WHEN PM.meta_key='_payment_method_title' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_payment_method_title'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_payment_method_title') STP ON STO.post_id = STP.post_id
		) SEL ON ID = SEL.post_id where post_status = 'wc-completed' and( NOT EXISTS (
        SELECT EntidadeId
            from integracao as I
            where I.EntidadeId = wp_posts.ID and I.Entidade = 'Order'
        ) or post_modified > (SELECT dataUltimaIntegracao from configuracao))", OBJECT);
		return $results;
		//echo ECGU_IntegrarVendas($results);
		//wp_die();
}

function ECGU_GetVendasExcluidas()
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT ID as id, post_date as data, SEL._order_total as total, SEL._cart_discount as desconto, SEL._customer_user as usuarioId, post_status as status, SEL._payment_method_title as metodoPagamento, post_modified, (SELECT 
																				(CASE WHEN O.option_name='blogname' 
	 																				THEN O.option_value 
	 																				ELSE NULL 
	 																			END) AS 'blogname'
																				FROM wp_options O 
																				where O.option_name = 'blogname') as nomeEmpresa
	from wp_posts 
	INNER JOIN 
			(SELECT PR.post_id, PR._order_total, ST._cart_discount, STO._customer_user, STP._payment_method_title
			FROM 
				(SELECT PM.post_id, (CASE WHEN PM.meta_key='_order_total' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_order_total'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_order_total') PR
	    	INNER JOIN 
	    		(SELECT PM.post_id, (CASE WHEN PM.meta_key='_cart_discount' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_cart_discount'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_cart_discount') ST ON PR.post_id = ST.post_id
             INNER JOIN 
	    		(SELECT PM.post_id, (CASE WHEN PM.meta_key='_customer_user' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_customer_user'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_customer_user') STO ON ST.post_id = STO.post_id
             INNER JOIN 
	    		(SELECT PM.post_id, (CASE WHEN PM.meta_key='_payment_method_title' 
	 									THEN PM.meta_value 
	 									ELSE NULL 
	 								END) AS '_payment_method_title'
				FROM wp_postmeta PM 
				inner JOIN wp_posts P on PM.post_id = P.ID 
				where P.post_type = 'shop_order' 
					and PM.meta_key = '_payment_method_title') STP ON STO.post_id = STP.post_id
		) SEL ON ID = SEL.post_id where (post_status = 'wc-cancelled' or post_status = 'wc-refunded' or post_status = 'wc-failed') and( NOT EXISTS (
        SELECT EntidadeId
            from integracao as I
            where I.EntidadeId = wp_posts.ID and I.Entidade = 'OrderCancel'
        ) or post_modified > (SELECT dataUltimaIntegracao from configuracao))", OBJECT);
		return $results;
		//echo ECGU_IntegrarVendas($results);
		//wp_die();
}

function ECGU_GetDetalheVenda($vendaId)
{
	global $wpdb;
	$results = $wpdb->get_results("
	SELECT O.order_id as vendaId, O.order_item_name as nome, O.order_item_id as detalheId, SEL._product_id as produtoId, SEL._qty as quantidade, SEL._line_total as valor, SEL._line_subtotal as subtotal from 
	wp_woocommerce_order_items O
	INNER JOIN
	(SELECT Pr.order_item_id, PR._product_id, QT._qty, T._line_total, ST._line_subtotal from
					(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='_product_id' 
		 									THEN OIM.meta_value 
		 									ELSE NULL 
		 								END) AS '_product_id'
					FROM wp_woocommerce_order_itemmeta OIM 
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id 
					where OI.order_item_type = 'line_item' 
						and OIM.meta_key = '_product_id') PR
	                    INNER JOIN 
		    		(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='_qty' 
		 									THEN OIM.meta_value 
		 									ELSE NULL 
		 								END) AS '_qty'
					FROM wp_woocommerce_order_itemmeta OIM
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id
					where OI.order_item_type = 'line_item' 
						and OIM.meta_key = '_qty') QT on QT.order_item_id = PR.order_item_id
						INNER JOIN 
		    		(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='_line_total' 
		 									THEN OIM.meta_value 
		 									ELSE NULL 
		 								END) AS '_line_total'
					FROM wp_woocommerce_order_itemmeta OIM
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id
					where OI.order_item_type = 'line_item' 
						and OIM.meta_key = '_line_total') T on T.order_item_id = QT.order_item_id
                        INNER JOIN 
		    		(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='_line_subtotal' 
		 									THEN OIM.meta_value 
		 									ELSE NULL 
		 								END) AS '_line_subtotal'
					FROM wp_woocommerce_order_itemmeta OIM
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id
					where OI.order_item_type = 'line_item' 
						and OIM.meta_key = '_line_subtotal') ST on ST.order_item_id = T.order_item_id) SEL 
                        
	                    on O.order_item_id = SEL.order_item_id
	                    WHERE O.order_id = $vendaId
	                    
	UNION
	                    
	SELECT O.order_id as vendaId, O.order_item_name as nome, O.order_item_id as detalheId, SEL._product_id as produtoId, SEL._qty as quantidade, SEL._line_total as valor, SEL._line_subtotal as subtotal from 
	wp_woocommerce_order_items O
	INNER JOIN
	(SELECT Pr.order_item_id, PR._product_id, QT._qty, T._line_total, ST._line_subtotal from
					(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='method_id' 
		 									THEN -8 
		 									ELSE NULL 
		 								END) AS '_product_id'
					FROM wp_woocommerce_order_itemmeta OIM 
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id 
					where OI.order_item_type = 'shipping' 
						and OIM.meta_key = 'method_id') PR
	                    INNER JOIN 
		    		(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='instance_id' 
		 									THEN OIM.meta_value 
		 									ELSE NULL 
		 								END) AS '_qty'
					FROM wp_woocommerce_order_itemmeta OIM
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id
					where OI.order_item_type = 'shipping'
	                	and OIM.meta_key = 'instance_id') QT on QT.order_item_id = PR.order_item_id
						INNER JOIN 
		    		(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='cost' 
		 									THEN OIM.meta_value 
		 									ELSE NULL 
		 								END) AS '_line_total'
					FROM wp_woocommerce_order_itemmeta OIM
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id
					where OI.order_item_type = 'shipping' 
						and OIM.meta_key = 'cost') T on T.order_item_id = QT.order_item_id
                        INNER JOIN 
		    		(SELECT OIM.order_item_id, (CASE WHEN OIM.meta_key='cost' 
		 									THEN OIM.meta_value 
		 									ELSE NULL 
		 								END) AS '_line_subtotal'
					FROM wp_woocommerce_order_itemmeta OIM
					inner JOIN wp_woocommerce_order_items OI on OIM.order_item_id = OI.order_item_id
					where OI.order_item_type = 'shipping' 
						and OIM.meta_key = 'cost') ST on ST.order_item_id = T.order_item_id) SEL 
	                    on O.order_item_id = SEL.order_item_id
	                    WHERE O.order_id = $vendaId", OBJECT);

	return $results;
}

function ECGU_GetCart()
{
	global $wpdb;
	$results = $wpdb->get_results("SELECT UM.user_id , um.meta_value  AS 'cart'
														FROM wp_usermeta UM
																INNER JOIN orcamento o
														        on um.meta_value <> o.Conteudo
														        and um.user_id = o.CustomerId
														WHERE UM.meta_key='_woocommerce_persistent_cart_1'
														UNION 
														SELECT UM.user_id , um.meta_value  AS 'cart'
														FROM wp_usermeta UM
														WHERE UM.meta_key='_woocommerce_persistent_cart_1'
														AND NOT EXISTS (
																Select o.CustomerId
														    	FROM orcamento o
														        WHERE um.user_id = o.CustomerId
														)", OBJECT);
    return $results;	  	
}