<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('admin_footer', 'javascript_test_webservice');

function javascript_test_webservice(){?>
<script type="text/javascript">

	jQuery( function($){
			$("#cnpj").mask("99.999.999/9999-99");
	});

	function ECGU_GetChaveIntegracao() {
		var userId = document.getElementById('login').value;
		var accessKey = document.getElementById('senha').value;
		var data = {
			'action': 'ECGU_RecuperarChaveIntegracao',
			'userId': userId,
			'accessKey': accessKey,
			'revendaId': 5
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			var myObj = JSON.parse(response);
			if(myObj.status == "Ok"){
				console.log(myObj.chaveUsuario);
				console.log(data.userId);
				alert('Chave de integração recuperada com sucesso!');
				document.getElementById('login').value = data.userId;
				document.getElementById('chaveUsuario').value = myObj.chaveUsuario;
			}else{
				alert('Chave de integração não recuperada!');
			}
		})
	}

	function ECGU_SalvarCliente() {
		var newUserData = {
            RazaoSocial: null,
            NomeFantasia: null,
            CNPJ: null,
            Site: null,
            Email: null,
            NomeUsuario: null,
            Senha: null,
            LinguagemId: 1,
            RevendaId: 5
        }
        newUserData.RazaoSocial = document.getElementById('razaoSocial').value;
        newUserData.NomeFantasia = document.getElementById('nomeFantasia').value;
        newUserData.CNPJ = document.getElementById('cnpj').value;
        newUserData.Site = document.getElementById('dominio').value;
        newUserData.Email = document.getElementById('email').value;
        newUserData.Senha = document.getElementById('senhaCadastro').value;
        newUserData.NomeUsuario = document.getElementById('nomeUsuario').value;

        console.log(newUserData);
        var data = {
			'action': 'ECGU_CadastrarCliente',
			'newUserData': newUserData
		};
        jQuery.post(ajaxurl, data, function(response) {
			var myObj = JSON.parse(response);
			console.log(response);
			if(myObj.status == 'Ok'){
				document.getElementById('login').value = newUserData.Email;
				document.getElementById('chaveUsuario').value = myObj.chaveUsuario;
				alert('Conta criada com sucesso!');
			}else {
				alert('Não foi possível criar sua conta, verifique os dados informados e tente novamente!');
			}
		})

	}

	function ECGU_ConectWebservice(){
		var data = {
			'action': 'ECGU_TesteWebservice',
			'userId': 'jose@agentemr.com.br',
			'accessKey': '123456',
			'revendaId': 1
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_IntegrarAgora(){
		button = document.getElementById("btn-integrar");
		button.disabled = true;
		jQuery('#qtdIntegracao').html(" ");
		var ok = 0;
		jQuery(document).ready(function () {
            var sendRequest = function () {
                	var data = {
						'action': 'ECGU_GetQntdObjetos',
						'userId': 'vitor@vitorpizarro10.com.br'
					};
					if (ok == 0) {
						jQuery('#qtdIntegracao').html("Integrando dados... Aguarde! Pode levar alguns minutos.");
					}
                if (ok == 0) {
                    setTimeout(sendRequest, 5000);
                }
            }
            sendRequest();
        });

		var data = {
			'action': 'ECGU_IntegrarDados',
			'userId': 'vitor@vitorpizarro10.com.br'
		};
		jQuery.post(ajaxurl, data, function(response) {
			ok = 1;
			console.log(response);
			alert('Integrados realizada com sucesso!');
			button.disabled = false;
			jQuery('#qtdIntegracao').html("Integrados realizada com sucesso!");
		})
	}

	function ECGU_IntegrarCategorias(){
		var data = {
			'action': 'ECGU_IntegrarCategorias',
			'userId': 'vitor@vitorpizarro10.com.br'
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_SetNewsletter(){
		var radioValue = jQuery("input[name='newsletter']:checked").val();
		var data = {
			'action': 'ECGU_SetNewsletter',
			'newsletter': radioValue
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			if(response == 1){
				alert('Plugin selecionado com sucesso');
			}else{
				//alert('Erro ao definir plugin, tente novamente');
			}			
		})
	}

	function ECGU_GetUsers(){
		var data = {
			'action': 'ECGU_GetContatos',
			'userId': 'vitor@vitorpizarro10.com.br'
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_GetProducts(){
		var data = {
			'action': 'ECGU_GetProdutos',
			'userId': 'vitor@vitorpizarro10.com.br'
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_GetSolds(){
		var data = {
			'action': 'ECGU_GetVendas',
			'userId': 'vitor@vitorpizarro10.com.br'
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_CreateTable(){
		var data = {
			'action': 'ECGU_CreateTable_teste',
			'userId': 'vitor@vitorpizarro10.com.br'
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_CreateView(){
		var data = {
			'action': 'ECGU_CreateView_teste',
			'userId': 'vitor@vitorpizarro10.com.br'
		};
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_ValidarSenha(){
		if(document.getElementById('senhaCadastro').value == document.getElementById('confimacaoSenhaCadastro').value){
			ECGU_SalvarCliente();
		}else {
			alert("Por favor, suas senhas devem ser iguais");
		}
	}

	function ECGU_SalvarDados(){
		var dados = {
            Login: null,
            Senha: null,
            RevendaId: 9
        }
        dados.Login = document.getElementById('login').value;
        dados.Senha = document.getElementById('senha').value;
        console.log(dados);
        var data = {
			'action': 'ECGU_SalvarUsuario',
			'dados': dados
		};
        jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			alert('Got this from the server: ' + response);
		})
	}

	function ECGU_ValidarCNPJ() {
 		
 		var cnpj = jQuery("#cnpj").val();
	    cnpj = cnpj.replace(/[^\d]+/g,'');
	 
	    if(cnpj == '') return false;
	     
	    if (cnpj.length != 14)
	        return false;
	    if (cnpj == "00000000000000" || 
	        cnpj == "11111111111111" || 
	        cnpj == "22222222222222" || 
	        cnpj == "33333333333333" || 
	        cnpj == "44444444444444" || 
	        cnpj == "55555555555555" || 
	        cnpj == "66666666666666" || 
	        cnpj == "77777777777777" || 
	        cnpj == "88888888888888" || 
	        cnpj == "99999999999999")
	        return false;
	         
	    // Valida DVs
	    tamanho = cnpj.length - 2
	    numeros = cnpj.substring(0,tamanho);
	    digitos = cnpj.substring(tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--) {
	      soma += numeros.charAt(tamanho - i) * pos--;
	      if (pos < 2)
	            pos = 9;
	    }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(0))
	        return false;
	         
	    tamanho = tamanho + 1;
	    numeros = cnpj.substring(0,tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--) {
	      soma += numeros.charAt(tamanho - i) * pos--;
	      if (pos < 2)
	            pos = 9;
	    }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(1))
	          return false;
	           
	    return true;
    
	}

	function ECGU_Validar(){
		if(ECGU_ValidarCNPJ()){
			jQuery('#span-cnpj span').text('');
			jQuery('#btn-cadastar').prop('disabled', false);
		}else {
			jQuery('#span-cnpj span').text('CNPJ inválido. Por favor insira um CNPJ válido.'); 	
			jQuery('#btn-cadastar').prop('disabled', true);
		}
	}
	
	
</script>
<?php  }
