var kisGeneral = function() {
	var self = this;
	
	/**
	 * Função para dar um replace All em strings
	 *
	 * @param string (string): String com o texto a ser substituido
	 * @param token (string): Texto que deve ser procurado para ser substituido
	 * @param newtoken (string): Texto de substituição
	 *
	 * @return string: Nova string substituida
	 */
	this.replaceAll = function(string, token, newtoken) {
		if(string!="") {
			while (string.indexOf(token) != -1) {
		 		string = string.replace(token, newtoken);
			}
		}
		return string;
	}
}
