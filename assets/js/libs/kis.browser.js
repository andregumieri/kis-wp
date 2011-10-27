/**
 * Classe com funções de navegação
 *
 * @version 2.1
 *
 * @package Kis
 * @subpackage Browser
 */

if(!window['Kis']) { var Kis={} }
(function(namespace) {
	namespace.Browser = {
		/**
		 * ieVersion()
		 *
		 * Função para saber a versão do IE
		 * @author André Gumieri
		 * @sinse 1.0
		 *
		 * @return string: Versão do IE ou false caso não seja IE
		 */
		ieVersion: function() {
			var version = 999; // we assume a sane browser
			if (navigator.appVersion.indexOf("MSIE") != -1) {
				// bah, IE again, lets downgrade version number
				version = parseFloat(navigator.appVersion.split("MSIE")[1]);
				return version;
			} else {
				return false;
			}
		},
		
		/**
		 * startMonitoringActiveWindow()
		 *
		 * Inicia o monitoramento de janela ativa
		 * @author André Gumieri
		 * @since 1.2
		 * 
		 */				
		startMonitoringActiveWindow: function() {
			var self = this;
			function onBlur() {
				self.isWindowActiveControl = false;
				//document.body.className = 'blurred';
			};
			function onFocus(){
				self.isWindowActiveControl = true;
				//document.body.className = 'focused';
			};
			
			if (/*@cc_on!@*/false) { // check for Internet Explorer
				document.onfocusin = onFocus;
				document.onfocusout = onBlur;
			} else {
				window.onfocus = onFocus;
				window.onblur = onBlur;
			}
		},
		

		/**
		 * isWindowActive()
		 *
		 * Funções para saber se a janela está ativa ou inativa
		 * @author André Gumieri
		 * @since 1.2
		 * 
		 * @return bool: TRUE para janela ativa, FALSE para janela inativa
		 */
		isWindowActiveControl: true,
		isWindowActive: function() {
			return this.isWindowActiveControl;
		},
		
	
		/**
		 * innerWidth()
		 *
		 * Função para saber a largura útil do navegador. Compatível com IE6+
		 * Adaptado do site do Andy Langton
		 * 
		 * @author André Gumieri
		 * @sinse 1.1
		 * @link http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
		 *
		 * @return int: Largura útil
		 */	
		innerWidth: function() {
			var viewportwidth;
			if (typeof window.innerWidth != 'undefined') {
				// the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
				viewportwidth = window.innerWidth;
			} else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
				// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
				viewportwidth = document.documentElement.clientWidth;
			} else {
				// older versions of IE
				viewportwidth = document.getElementsByTagName('body')[0].clientWidth;
			}
			
			return viewportwidth;
		},
	
	
	
		/**
		 * innerHeight()
		 *
		 * Função para saber a altura útil do navegador. Compatível com IE6+
		 * Adaptado do site do Andy Langton
		 * 
		 * @author André Gumieri
		 * @sinse 1.1
	 	 * @link http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
		 *
		 *
		 * @return int: Altura útil
		 */		
		innerHeight: function() {
			var viewportheight;
	
			if (typeof window.innerWidth != 'undefined') {
				// the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
				viewportheight = window.innerHeight;
			} else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
				// IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
				viewportheight = document.documentElement.clientHeight;
			} else {
				// older versions of IE
				viewportheight = document.getElementsByTagName('body')[0].clientHeight;
			}
			
			return viewportheight;
		}
	}
})(Kis);