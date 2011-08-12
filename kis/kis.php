<?php
/**
 * Kis
 * @since 11/08/2011
 * @version 1.0
 * @author André Gumieri - andregumieri@gmail.com
 */
namespace kis;
include("util/Minify.class.php");

Class Kis {
	function getThemePath() {
		return TEMPLATEPATH;
	}
	
	function getThemeURL() {
		return get_bloginfo("template_directory");
	}
	
	function getContentPath() {
		return WP_CONTENT_DIR;
	}
	
	function getContentURL() {
		return WP_CONTENT_URL;
	}
}
?>