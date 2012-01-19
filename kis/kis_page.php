<?php
/**
 * KIS: Wordpress - Page Functions
 *
 * Page related functions
 *
 * @author André Gumieri
 * @version 1.0.1
 *
 * @package KIS
 * @subpackage Page
 */


/**
 * Monta o breadcrumb através das páginas
 *
 * @author André Gumieri
 * @since 1.0.1
 *
 * @param mixed $home FALSE caso não seja para mostrar a página inicial ou a string do texto da página inicial. Default: Página inicial.
 * @param mixed $container FALSE caso não seja mostrar o container, ou a string com o nome da tag. Default: FALSE.
 * @return string as tags do breadcrumb montadas.
 */
function kis_page_breadcrumb($home="Página inicial", $container=false) {
	global $post;
	$bc = "";
	
	if($home!==false) {
		$bc .= "<a href=\"" . get_bloginfo("url") . "\" class=\"first\"><span>{$home}</span><span class=\"d\"></span></a>";
	}
	
	$ancestors = array_reverse($post->ancestors);
	foreach($ancestors as $a) {
		$pg = get_page($a);
		$bc .= "<a href=\"" . get_page_link($a) . "\"><span class=\"e\"></span><span>" . $pg->post_title . "</span><span class=\"d\"></span></a>";
	}
	$bc .= "<a class=\"last\"><span class=\"e\"></span><span class=\"text\">" . $post->post_title . "</span><span class=\"d\"></span></a>";
	
	if($container!==false) {
		$bc = "<{$container} class=\"breadcrumb\">{$bc}</{$container}>";
	}
	
	echo $bc;
}

?>