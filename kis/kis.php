<?php
/**
 * Kis
 * @since 11/08/2011
 * @version 1.0
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
?>