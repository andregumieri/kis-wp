/**
 * Classe com funções de navegação
 *
 * @version 1.1
 */
 
var KisBrowser = {

	/**
	 * Função para saber a versão do IE
	 * @author André Gumieri
	 * @sinse 1.0
	 *
	 * @return string: Versão do IE ou false caso não seja IE
	 */
	IEVersion: function() {
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
	 * Função para saber a largura útil do navegador. Compatível com IE6+
	 * Adaptado do site do Andy Langton
	 * 
	 * @author André Gumieri
	 * @sinse 1.1
	 * @link http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
	 *
	 * @return int: Largura útil
	 */	
	InnerWidth: function() {
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
	InnerHeight: function() {
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
	
	
};