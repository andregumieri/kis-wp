<?php
/**
 * KIS: Wordpress - Minifying functions
 *
 * Functions with prime objective to minify javascript and css files
 *
 * @author André Gumieri
 * @version 1.3
 *
 * @package KIS
 * @subpackage Minify
 */
 
 
 
 function kis_minify_print_css_on_header() {
 	//if(kis_is_dev_enviroment()) {
 	global $wp_styles;
 	
 	// Minify do CSS
 	$css = array();
 	foreach($wp_styles->queue as $q) {
 		$css[] = $wp_styles->registered[$q]->src;
 	}
 	$wp_styles->queue = array();
 	$novoCSS = kis_minify($css, 'css', false, false, true);
 	
 	wp_register_style('kis_minify_new_header_css', $novoCSS);
 	wp_enqueue_style('kis_minify_new_header_css');
 }
 
 function kis_minify_print_js_on_header() {
 	global $wp_scripts;
 	echo "wp_print_scripts ";
 //print_r($wp_scripts);
 	// Minify do JS
 	$js = array();
 	$queue_notin = array();
 	//print_r($wp_scripts->queue);
 	foreach($wp_scripts->queue as $q) {
 		if($wp_scripts->registered[$q]->extra['group']==$wp_scripts->group) {
 			$js[] = $wp_scripts->registered[$q]->src;
 			echo "wp_dequeue_script({$q}); ";
 			wp_dequeue_script($q);
 		}
 	}
 	
 	//$wp_scripts->queue = $queue_notin;
 	//print_r($wp_scripts->queue); die();
 	$novoJS = kis_minify($js, 'js', false, false, true);
 	
 	wp_register_script('kis_minify_new_header_js', $novoJS, false, false, false);
 	wp_enqueue_script('kis_minify_new_header_js');
 }
 
 function kis_minify_print_js_on_footer() {
 	global $wp_scripts, $wp_styles;
 	echo "<strong>wp_print_footer_scripts</strong> ";
 	//print_r($wp_scripts);
 	//print_r($wp_styles);
 	//die();
 }
 //add_action( 'wp_print_styles', 'kisMinifyOnPrintStyle' );
 //add_action( 'wp_print_styles', 'kis_minify_print_css_on_header');
 
 function kis_wp_footer() {
 	echo "<strong>wp_footer</strong> ";
 }
 
 function kis_wp_head() {
 	echo "<strong>wp_head</strong> ";
 	//global $wp_scripts;
 	//print_r($wp_scripts);
 }
 
 add_action( 'wp_head', 'kis_wp_head' );
 add_action( 'wp_print_scripts', 'kis_minify_print_js_on_header');
 add_action( 'wp_print_footer_scripts', 'kis_minify_print_js_on_footer');
 add_action( 'wp_footer', 'kis_wp_footer' );


/**
 * kis_minify_add()
 * Adiciona os scripts para minify
 * @param string $paths - Array com os caminhos relativos à raiz do tema
 * @param string $type - Tipo de arquivo (js or css)
 * @param mixed $deps - array com dependencias
 * @param mixed $in_footer - Colocar script no footer (js)
 * @param mixed $ver - Versao do arquivo
 * @param mixed $media - Tipo de midia (css)
 */
function kis_minify_add($path, $type, $deps=false, $in_footer = true, $ver=false, $media=false) {
	if($type == "css") {
		$name = basename($path, ".css");
		wp_register_style('kis_minify_add_css_'.$name, get_template_directory_uri()."/".$path, $deps, $ver, $media);
		wp_enqueue_style('kis_minify_add_css_'.$name);
	} elseif( $type == "js" ) {
		$name = basename($path, ".js");
		wp_register_script('kis_minify_add_js_'.$name, get_template_directory_uri()."/".$path, $deps, $ver, $in_footer);
		wp_enqueue_script('kis_minify_add_js_'.$name);
	}
	
	return false;
}
 
 
/**
 * kis_minify()
 * Join all files into only one call
 *
 * @author André Gumieri
 * @since 1.1
 *
 * @param array $items - The array of files to minimize. Each file must have it`s path from theme root. For example: assets/css/common/base.css
 * @param string $type - Type of return (js or css)
 * @param bool $debug - If setted to true, print individual calls for scripts
 * @param bool $echo - Echo the result
 * @param bool $onlyURL - Show only URL instead the complete tag.
 * @return none.
 */
function kis_minify($items, $type='css', $debug=false, $echo=true, $onlyURL=false) {
	if($type!="js" && $type!="css") {
		trigger_error("KIS - Minify: {$type} is not a supported minify type. Ignoring this minify output.", E_USER_WARNING);
		return;
	}
	
	if($debug) {
		$ret = null;
		foreach($items as $item) {
			if( (strpos($item, "http://")!==false || strpos($item, "https://")!==false)) {
				echo kis_minify_create_tag($type, $item);
			} else {
				echo kis_minify_create_tag($type, kis_get_theme_url()."/".$item);
			}
		}
	} else {
		$path = kis_get_files_path("minify");
		$md5all = "";
		foreach($items as $item) {
			$itemPath = str_replace(get_bloginfo("url"), "", $item);
			$itemUrlParsed = parse_url($itemPath);
			if ( !isset($itemUrlParsed['scheme']) ) {
				$md5all .= md5_file(ABSPATH.$itemPath);
			} else {
				$md5all .= md5(kis_get_file_contents($item));
			}
		}
		
		$minify_name = md5($md5all).".".$type;
		if(!file_exists($path."/".$minify_name)) {
			$minify_content = "";
			foreach($items as $item) {
				$minify_content .= "/**\n";
				$minify_content .= " * {$item}\n";
				$minify_content .= " * Created at " . date("Y-m-d H:i:s") . "\n";
				$minify_content .= " */\n\n";
				
				$itemPath = str_replace(get_bloginfo("url"), "", $item);
				$itemUrlParsed = parse_url($itemPath);
				$tmp_content = "";
				if ( !isset($itemUrlParsed['scheme']) ) {
					$tmp_content = file_get_contents(ABSPATH.$itemPath);
				} else {
					$tmp_content = kis_get_file_contents($item);
				}
				if(substr($item,0,1)!="/") $itemPath = kis_get_theme_path()."/".$item;
				
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
						$new_o = kis_minify_fix_css_url_path(get_bloginfo("url"), $item_path, $o) . "\n";
						$tmp_content = str_replace($o, $new_o, $tmp_content);
					}
					$tmp_content = kis_minify_compress_css($tmp_content);
				}
				$minify_content .= $tmp_content;
				$minify_content .= "\n\n";
			}
			file_put_contents($path."/".$minify_name, $minify_content);
		}
		
		$ret = null;
		if(!$onlyURL) $ret = kis_minify_create_tag($type, kis_get_files_url("minify")."/".$minify_name);
		else $ret = kis_get_files_url("minify")."/".$minify_name;

		if($echo) echo $ret;
		else return $ret;
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
 * @since 1.3
 *
 * @param string $prefix - Prefix of the new URL
 * @param string $root - Root of the CSS file
 * @param string $url_line - URL parameter content
 * @return string - compressed css content.
 */
function kis_minify_fix_css_url_path($prefix, $root, $url_line) {
	$rootPath = str_replace(get_bloginfo("url"), "", $root);
	$itemUrlParsed = parse_url($rootPath);
	if( isset($itemUrlParsed['scheme']) ) {
		return $url_line;
	}
	$root = $rootPath;
	
	if(substr($root, 0, 1)=="/") $root = substr($root,1);
	if(substr($prefix, -1)!="/") $prefix .= "/";
	if(substr($root, -1)=="/") $root = substr($root, 0,-1);
	$root_arr = explode("/", $root);
	$return = "";
	
	$arr_url_line = explode(",", $url_line);

	foreach($arr_url_line as $i_url_line) {
		$url_line = trim($i_url_line);
		if( (strpos($url_line, "http://")!==false || strpos($url_line, "https://")!==false) && strpos($url_line, $prefix)===false) {
			$return .= ",".$url_line;
		} else {
			if(strpos($url_line, "url")==0){
				$abre = strpos($url_line, "(")+1;
				$fecha = strpos($url_line, ")");
				$url = substr($url_line, $abre, ($fecha-$abre));
				$url = str_replace(array("'", "\""), "", $url);
				
				
				if(substr($url, -1)=="/") $url = substr($url, 0,-1);
				$url_arr = explode("/", $url);
				
				$back = 0;
				$url_new_arr = array();
				foreach($url_arr as $u) {
					if($u=="..") $back++;
					else $url_new_arr[] = $u;
				}
				
				if(isset($url_new_arr[0]) && empty($url_new_arr[0])) {
					$return .= ",".str_replace($url, implode("/", $url_new_arr), $url_line);
				} else {
					
					$url_new = "";
					for($x=0; $x<(count($root_arr)-$back); $x++) {
						$url_new .= "/".$root_arr[$x];
					}
					
					$url_new = $url_new . "/" . implode("/", $url_new_arr);
					$url_new = $prefix.substr($url_new, 1);
					
					$return .= ",".str_replace($url, $url_new, $url_line);
				}
			} else { // if(strpos($url_line, "url")==0)
				$return .= ",".$url_line;
			}
		}
	}
	
	return substr($return,1);
	
}




 
?>