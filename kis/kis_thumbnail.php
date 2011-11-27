<?php
/**
 * KIS: Wordpress - Tumbnail Functions
 *
 * Related thumbnails functions
 *
 * @author André Gumieri
 * @version 1.1
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
 *
 * @param int $post_id O ID do post que contem o thumbnail
 * @param int $w A largura do crop
 * @param int $h A altura do crop
 *
 * @return mixed URL do thumb cropado ou FALSE caso o post não tenha thumb.
 */
function kis_thumbnail_crop($post_id, $w, $h) {
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
		
		return WP_CONTENT_URL . "/kis_crops/{$newName}";
	} else {
		return false;
	}
}

?>