<?php
/**
 * KIS: Wordpress - Tumbnail Functions
 *
 * Related thumbnails functions
 *
 * @author André Gumieri
 * @version 1.1.2
 *
 * @package KIS
 * @subpackage Thumbnail
 */

require_once 'libs/imageManage.class.php';

/**
 * Retrieve the URL from a post thumbnail.
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param string $post_id The ID of the post.
 * @param mixed $size The string of a thumbnail size or a array with width and height.
 * @return mixed false when has no thumbnail or string when has.
 */
function kis_thumbnail_get_url($post_id, $size='original') {
	$image_id = get_post_thumbnail_id($post_id);  
	if(!empty($image_id)) {
		$image_url = wp_get_attachment_image_src($image_id, $size);  
		$image_url = $image_url[0];
		return $image_url;
	} else {
		return false;
	}
}



/**
 * Faz o crop da imagem de thumbnail nos tamanhos especificados
 * nos parametros W e H.
 *
 * @author André Gumieri
 * @since 1.1
 * @version 1.2
 *
 * @param int $post_id O ID do post que contem o thumbnail
 * @param int $w A largura do crop
 * @param int $h A altura do crop
 * @param string $returnType - Tipo de retorno. tag: Tag imagem, url: URL da imagem.
 * @param string $tag_attrs - Array com atributos e valores para adicionar à tag. array("atributo" => "Valor do attr", "alt"=>"Texto alternativo");
 *
 * @return mixed URL ou TAG do thumb cropado ou FALSE caso o post não tenha thumb.
 */
function kis_thumbnail_crop($post_id, $w, $h, $returnType="url", $tag_attrs=array()) {
	global $wpdb;
	$id = get_post_thumbnail_id($post_id);
	if(empty($id)) return false;
	
	$att = $wpdb->get_results("SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `post_id`='{$id}' AND `meta_key`='_wp_attached_file'");
	
	if(!empty($att)) {
		$att = WP_CONTENT_DIR . "/uploads/" . $att[0]->meta_value;
		$att_info = pathinfo($att);
		
		$extensao = $tipoArquivo = $att_info['extension'];
		switch($tipoArquivo) {
			case 'gif':
				$tipoArquivo = 'gif';
				break;
			case 'png':
				$tipoArquivo = 'png';
				break;
			case 'jpg':
			case 'jpeg':
				$tipoArquivo = 'jpeg';
				break;
		}
		
		$newName = $att_info['filename']."_{$w}x{$h}_".md5($att).".{$extensao}";
		
		if(!file_exists(WP_CONTENT_DIR . "/kis_crops/")) {
			mkdir(WP_CONTENT_DIR . "/kis_crops/", 0777);
		}
		if(!file_exists(WP_CONTENT_DIR . "/kis_crops/{$newName}")) {
			$imageManage = new imageManage($att, $tipoArquivo);
			
			$imageManage->saveImage($imageManage->cropProportional($w, $h), WP_CONTENT_DIR . "/kis_crops/{$newName}");
		}
		
		if($returnType=="tag") {
			$txtalt = "";
			if(isset($tag_attrs['alt'])) $txtalt = $tag_attrs['alt'];
			
			if(empty($txtalt)) {
				$wpalt = $wpdb->get_results("SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `post_id`='{$id}' AND `meta_key`='_wp_attachment_image_alt'");
				if(!empty($wpalt)) $txtalt = $wpalt[0]->meta_value;
			}
			
			if(!empty($txtalt)) {
				$txtalt = " alt=\"{$txtalt}\"";
			}
			
			$attrs = "";
			foreach($tag_attrs as $attr=>$valor) {
				if($attr!="alt") $attrs .= " {$attr}=\"{$valor}\"";
			}
			
			return "<img src=\"" . WP_CONTENT_URL . "/kis_crops/{$newName}" . "\" width=\"{$w}\" height=\"{$h}\"{$txtalt}{$attrs} />";
		} else {
			return WP_CONTENT_URL . "/kis_crops/{$newName}";
		}
	} else {
		return false;
	}
}

?>