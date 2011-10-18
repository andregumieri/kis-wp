/**
 * Classe com funções para strings
 *
 * @version 1.0.1
 */

var KisStrings = {
	/**
	 * Função para truncar uma string sem cortar a última palavra
	 *
	 * @param string (string): String com o texto a ser truncado
	 * @param token (string): Tamanho do texto a ser truncado
	 * @param strtruncate (string): String a ser colocada após truncar a string
	 *
	 * @return string: Nova string substituida
	 */
	truncate : function(string, size, strtruncate) {
		var sizeOriginal = size;
		if(string.length<=size) {
			return string;
		}
		
		var validaCaractereFinal = function(char) {
			if(char==" " || char=="." || char==";") {
				return true;
			} else {
				return false;
			}
		}
		
		var ultimoCaractere = string.substr(size-1,1);
		
		var proximoCaractere = string.substr(size,1); // Caractere que vem logo após o truncate da string
		var encontrado = validaCaractereFinal(proximoCaractere);

		
		while(encontrado==false) {
			size++;
			proximoCaractere = string.substr(size,1);
			encontrado = validaCaractereFinal(proximoCaractere);

			if(size==string.length) {
				encontrado = true;	
				strtruncate = "";
			}
		}
		
		ultimoCaractere = string.substr(size-1,1);
		if(ultimoCaractere==".") {
			strtruncate="";
		}
		
		return string.substr(0,size)+strtruncate;
	}
};