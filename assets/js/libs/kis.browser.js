var KisBrowser = {

	/**
	 * Função para saber a versão do IE
	 * @author André Gumieri
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
	}
}