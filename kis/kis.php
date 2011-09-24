<?php
/**
 * Kis
 * @since 11/08/2011
 * @version 1.1
 * @author André Gumieri - andregumieri@gmail.com
 */


/**
 * Retorna o caminho do servidor para a pasta do template atual
 *
 * @author André Gumieri
 * @return string
 */
function kis_get_theme_path() {
	return TEMPLATEPATH;
}

/**
 * Retorna a url com o caminho do template atual
 *
 * @author André Gumieri
 * @return string
 */
function kis_get_theme_url() {
	return get_bloginfo("template_directory");
}

/**
 * Retorna o com o caminho do servidor para a pasta wp-content
 *
 * @author André Gumieri
 * @return string
 */
function kis_get_content_path() {
	return WP_CONTENT_DIR;
}

/**
 * Retorna a url do servidor para a pasta wp-content
 *
 * @author André Gumieri
 * @return string
 */
function kis_get_content_url() {
	return WP_CONTENT_URL;
}


/**
 * Retorna a pasta de arquivos do KIS. Se não existir, tenta criar. 
 * Se não der para criar, retorna false.
 *
 * @author André Gumieri
 * @return mixed - String com o caminho ou false caso não de para criar
 */
function kis_get_files_path($path="") {
	if(!empty($path) && substr($path, 0, 1)!="/") $path = "/{$path}";
	$path = kis_get_content_path() . "/kis" . $path;
	if(!file_exists($path)) {
		if(!mkdir($path, 0777, true)) {
			return false;
		}
	}
	
	return $path;
}


/**
 * Retorna a URL de arquivos do KIS.
 *
 * @author André Gumieri
 * @return mixed - String com o caminho ou false caso não de para criar
 */
function kis_get_files_url($path="") {
	$server_path = kis_get_content_path($path);
	if($server_path===false) return false;
	if(!empty($path) && substr($path, 0, 1)!="/") $path = "/{$path}";
	$path = kis_get_content_url() . "/kis" . $path;
	return $path;
}


/**
 * Retorna o conteúdo de um arquivo a partir de sua URL
 *
 * @author André Gumieri
 * @return string
 */
function kis_get_file_contents($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}



/**
 * Verifica se o site está rodando em ambiente de desenvolvimento
 *
 * @author André Gumieri
 * @return bool
 */
function kis_is_dev_enviroment() {
	$addr = explode(".",$_SERVER['SERVER_ADDR']);
	if(
		($addr[0]=="127" && $addr[1]=="0" && $addr[2]=="0" && $addr[3]=="1") ||
		($addr[0]=="192" && $addr[1]=="168") ||
		($addr[0]=="172" && $addr[1]=="16") ||
		($addr[0]=="10")
	) {
		return true;
	} else {
		return false;
	}
}















?>