<?php
/**
 * KIS: Wordpress - Tumbnail Functions
 *
 * Related thumbnails functions
 *
 * @author André Gumieri
 * @version 1.0
 *
 * @package KIS
 * @subpackage Thumbnail
 */


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
function kis_get_thumbnail_url($post_id, $size='original') {
	$image_id = get_post_thumbnail_id($post_id);  
	if(!empty($image_id)) {
		$image_url = wp_get_attachment_image_src($image_id, $size);  
		$image_url = $image_url[0];
		return $image_url;
	} else {
		return false;
	}
}
?>