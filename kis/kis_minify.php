<?php
/**
 * KIS: Wordpress - Minifying functions
 *
 * Functions with prime objective to minify javascript and css files
 *
 * @author André Gumieri
 * @version 1.2
 *
 * @package KIS
 * @subpackage Minify
 */
 
 
/**
 * kis_minify()
 * Join all files into only one call
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param array $items - The array of files to minimize. Each file must have it`s path from theme root. For example: assets/css/common/base.css
 * @param string $type - Type of return (js or css)
 * @param bool $debug - If setted to true, print individual calls for scripts
 * @return none.
 */
function kis_minify($items, $type='css', $debug=false) {
	if($type!="js" && $type!="css") {
		trigger_error("KIS - Minify: {$type} is not a supported minify type. Ignoring this minify output.", E_USER_WARNING);
		return;
	}
	
	if($debug) {
		foreach($items as $item) {
			echo kis_minify_create_tag($type, kis_get_theme_url()."/".$item);
		}
	} else {
		$path = kis_get_files_path("minify");
		$md5all = "";
		foreach($items as $item) {
			$md5all.=md5_file(kis_get_theme_path()."/".$item);
		}
		
		$minify_name = md5($md5all).".".$type;
		if(!file_exists($path."/".$minify_name)) {
			$minify_content = "";
			foreach($items as $item) {
				$minify_content .= "/**\n";
				$minify_content .= " * {$item}\n";
				$minify_content .= " * Created at " . date("Y-m-d H:i:s") . "\n";
				$minify_content .= " */\n\n";
				$tmp_content = file_get_contents(kis_get_theme_path()."/".$item);
				if($type=="css") {
					$url_base = kis_get_theme_url();
					preg_match_all('/url\([A-z.\/ \'"-_#\?]*\)/', $tmp_content, $out, PREG_PATTERN_ORDER);
					foreach($out[0] as $o){
						$item_path_arr = explode("/", $item);
						$item_path = "";
						for($x=0; $x<(count($item_path_arr)-1); $x++) {
							$item_path .= "/".$item_path_arr[$x];
						}
						$item_path = substr($item_path, 1);
						$new_o = kis_minify_fix_css_url_path(kis_get_theme_url(), $item_path, $o) . "\n";
						$tmp_content = str_replace($o, $new_o, $tmp_content);
					}
					$tmp_content = kis_minify_compress_css($tmp_content);
				}
				$minify_content .= $tmp_content;
				$minify_content .= "\n\n";
			}
			file_put_contents($path."/".$minify_name, $minify_content);
		}
		echo kis_minify_create_tag($type, kis_get_files_url("minify")."/".$minify_name);
	}
}


/**
 * kis_minify_create_tag()
 * Join all files into only one call
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param string $type - Type of return (js or css)
 * @param string $file - File path for the tag
 * @return string - created tag.
 */
function kis_minify_create_tag($type, $file) {
	if($type=="js") {
		return "<script src=\"" . $file . "\" type=\"text/javascript\"></script>\n";
	} elseif($type=="css") {
		return "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$file}\" />\n";
	}
}


/**
 * kis_minify_compress_css()
 * Compress the CSS files removing comments, tabs, spaces and newlines
 *
 * @author André Gumieri based on Reinhold Weber script
 * @link http://www.catswhocode.com/blog/3-ways-to-compress-css-files-using-php
 * @since 1.0
 *
 * @param string $buffer - Content of the CSS file
 * @return string - compressed css content.
 */
function kis_minify_compress_css($buffer) {
	/* remove comments */
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

	/* remove tabs, spaces, newlines, etc. */
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
	
	return $buffer;
}


/**
 * kis_minify_fix_path()
 * Fix the path for URL tags
 *
 * @author André Gumieri
 * @since 1.2
 *
 * @param string $prefix - Prefix of the new URL
 * @param string $root - Root of the CSS file
 * @param string $url_line - URL parameter content
 * @return string - compressed css content.
 */
function kis_minify_fix_css_url_path($prefix, $root, $url_line) {
	if(substr($prefix, -1)!="/") $prefix .= "/";
	if(substr($root, -1)=="/") $root = substr($root, 0,-1);
	$root_arr = explode("/", $root);
	$return = "";
	
	$arr_url_line = explode(",", $url_line);

	foreach($arr_url_line as $i_url_line) {
		$url_line = trim($i_url_line);
		if(strpos($url_line, "url")==0){
			$abre = strpos($url_line, "(")+1;
			$fecha = strpos($url_line, ")");
			$url = substr($url_line, $abre, ($fecha-$abre));
			$url = str_replace(array("'", "\""), "", $url);
			
			// Calcula o novo path
			if(substr($root,0,1)=="/") {
				// Retorna a string original se a URL começa com barra
				return $url_line;
			}
			
			if(substr($url, -1)=="/") $url = substr($url, 0,-1);
			$url_arr = explode("/", $url);
			
			$back = 0;
			$url_new_arr = array();
			foreach($url_arr as $u) {
				if($u=="..") $back++;
				else $url_new_arr[] = $u;
			}
			
			$url_new = "";
			for($x=0; $x<(count($root_arr)-$back); $x++) {
				$url_new .= "/".$root_arr[$x];
			}
			$url_new = $url_new . "/" . implode("/", $url_new_arr);
			$url_new = $prefix.substr($url_new, 1);
			$return .= ",".str_replace($url, $url_new, $url_line);
		} else {
			$return .= ",".$url_line;
		}
	}
	
	return substr($return,1);
	
}




 
?>