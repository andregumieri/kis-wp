/**
 * Funções para controlar hashtags
 *
 * @version 1.0.1
 * @author André Gumieri
 */
 
if(!window['Kis']) { var Kis={} }
(function(namespace, $) {
	if($==undefined) throw "Sorry, to use Kis.Hash you must use jQuery.";
	
	namespace.Hash = {
		inicial: null,
		prefixo: null,
		separador: null,
		erro404callback: null,
		hashs: {},
		
		
		/**
		 * adicionaHash
		 * Função que adiciona as hashs para troca pelo Kis.Hash
		 * e seus callbacks
		 *
		 * @param STRING hash: Hash para ser validado. Ex.: blog
		 * @param FUNCTION callback: O que será executado quando for mudada a hash. Ex.: function(url)
		 */
		adicionaHash: function(hash, callback) {
			this.hashs[hash] = callback;
		},
		
		
	
		/**
		 * init
		 * Função que inicia o controle de hashtags
		 *
		 * @param	OBJ		opcoes
		 *					inicial		STRING		Página inicial para quando a HASH for vazia. Default: ''
		 *					prefixo		STRING		Prefixo da URL, ou seja, string que vai depois do #.
		 *											Ex.: '/' fica #/url
		 *					separador	STRING		Separador de sub urls. Default: /. Ex.: #blog/post-do-blog
		 *					erro404		FUNCTION	Função de callback para quando der erro 404
		 */
		init: function(opcoes) {
			var self = this;
			var settings = {
				'inicial': '',
				'prefixo': '',
				'separador': '/',
				'erro404': null
			}
			$.extend(settings, opcoes);
			
			this.inicial = settings.inicial;
			this.prefixo = settings.prefixo;
			this.separador = settings.separador;
			this.erro404callback = settings.erro404;
			
			$(window).bind("hashchange", function() { self.executaCallback() } );
			if( this.hashEstaVazia() ) {
				if(self.inicial) {
					self.mudarHash(self.inicial);
				}
			} else {
				self.executaCallback();
			}
		},
		
		
		
		/**
		 * executaCallback
		 * Executa a função de callback para URL
		 */
		executaCallback: function() {
			var self = this;
			var ignore = "#"+this.prefixo;
			var hash = null;
			if( this.hashEstaVazia() ) {
				if(self.inicial) {
					self.mudarHash(self.inicial);
					return ;
				}
			} else {
				hash = location.hash.substr(ignore.length);
			}
			
			// Se o hash estiver terminando com o separador, tira.
			if(hash.substr(-1)==this.separador) {
				hash = hash.substr(0,hash.length-1);
			}
			
			// Pega apenas a primeira parte do hash
			var hashSplited = hash.split(self.separador);
			var hashPrimeiraParte = hashSplited[0];
			
			// Pega a função de ação da URL
			var action = self.hashs[hashPrimeiraParte];
			
			
			// Valida se a URL existe
			if(typeof(action)=="undefined") {
				this.erro404(hash, hashSplited);
			} else {
				action.call(self, hash, hashSplited);
			}
		},
		
		
		/**
		 * mudarHash
		 * Muda o HASH no browser nos padrões necessários para
		 * funcionamento desta classe
		 *
		 * @param STRING pagina: Mandar como parametro apenas a página, sem #
		 */
		mudarHash: function(pagina) {
			location.hash = this.prefixo + pagina;
		},
		
		
		
		/**
		 * hashEstaVazia
		 * Verifica se o HASH está vazio. O Hash pode ser passado como parâmetro
		 * mas se deixado em branco, verifica o hash do location.hash
		 *
		 * @param OPTIONAL STRING hash: String com o hash que deve ser validado
		 *
		 * @return BOOL: TRUE se o hash estiver vazio, FALSE se o hash estiver preenchido
		 */
		hashEstaVazia: function(hash) {
			var hashTeste = location.hash;
			if(hash!=undefined) { hashTeste = hash; }
			
			if( hashTeste=="" || hashTeste=="#" || hashTeste=="#"+this.prefixo ) {
				return true;
			}
			
			return false;
		},
		
		
		
		/**
		 * erro404
		 * Mensagem para quando a página não existe
		 */
		erro404: function(hash, hashSplited) {
			if(typeof(this.erro404callback)=="function") {
				this.erro404callback.call(this, hash, hashSplited);
				return ;
			}
			alert("Erro 404: Página não encontrada");
		}

	}
})(Kis, jQuery);