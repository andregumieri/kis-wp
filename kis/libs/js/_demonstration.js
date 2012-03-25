;(function($) {
	var Demonstration = {
		init: function() {
			this.Hash.init();
			this.AjaxForm.init();
			this.Placeholder.init();
		},
		
		AjaxForm: {
			init: function() {
				$form = $("#ajaxForm,#ajaxFormWithPlaceholder");
				Kis.AjaxForm.init($form, "ajaxForm", "Função executada sem erros");
			}
		},
		
		Placeholder: {
			init: function() {
				$form = $("form");
				Kis.Placeholder.init($form);
			}
		},
		
		Hash: {
			init: function() {
				var $container = $('<ul id="hashTest" />');
				$container.append('<li><a href="#/hash1" class="hash1">Hash Teste 1 (/#/hash1)</a></li>');
				$container.append('<li><a href="#/hash2" class="hash2">Hash Teste 2 (/#/hash2)</a><ul><li><a href="#/hash2/primeiro" class="hash2_primeiro">Hash Teste 2.1 (/#/hash2/primeiro)</a></li><li><a href="#/hash2/segundo" class="hash2_segundo">Hash Teste 2.2 (/#/hash2/segundo)</a><ul><li><a href="#/hash2/segundo/1" class="hash2_segundo_1">Hash Teste 2.2.1 (/#/hash2/segundo/1)</a></li></li></ul></li>');
				$container.append('<li><a href="#/hash3" class="hash3">Hash Teste 3 (/#/hash3)</a></li>');
			
				$("body").append($container);
				
				Kis.Hash.adicionaHash("hash1", this.urlChanged);
				Kis.Hash.adicionaHash("hash2", this.urlChanged);
				Kis.Hash.adicionaHash("hash3", this.urlChanged);
				Kis.Hash.init({prefixo: '/', inicial:'hash1', erro404: this.erro });
			},
			
			urlChanged: function(url, splited) {
				$("#hashTest").find("a").css("font-weight", "normal");
				if(splited.length==1) {
				
					$("#hashTest").find("."+splited[0]).css("font-weight", "bold");
				
				} else if(splited.length==2) {
					
					var $links = $("#hashTest").find("."+splited[0]+"_"+splited[1]);
					if($links.is("*")) {
						$links.css("font-weight", "bold");
					} else {
						Kis.Hash.erro404(url, splited);
					}
					
				} else if(splited.length==3) {
					var $links = $("#hashTest").find("."+splited[0]+"_"+splited[1]+"_"+splited[2]);
					if($links.is("*")) {
						$links.css("font-weight", "bold");
					} else {
						Kis.Hash.erro404(url, splited);
					}
				
				} else {
					Kis.Hash.erro404(url, splited);
				}
			}, 
			
			erro: function(url, splited) {
				alert("A URL '" + url + "' não foi encontrada. Tente outra.");
			}
		
		}
	};
	
	$(document).ready(function() { Demonstration.init(); });
})(jQuery);