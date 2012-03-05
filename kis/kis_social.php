<?php
/**
 * KIS: Wordpress - Social Functions
 *
 * Funções de redes sociais
 *
 * @author André Gumieri
 * @version 1.2.1
 *
 * @package KIS
 * @subpackage Social
 */


/**
 * Monta o like button do facebook
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param mixed $url URL da página que será dada a ação de like.
 * @param mixed $appId ID do APP cadastrado no facebook.
 * @param array $options Opções do plugin:
 * 		layoutStyle: (string) [*standard|button_count|box_count] Estilo do botão de like
 *		width: (int) Largura do iFrame do Facebook
 *		height: (int) Altura do iFrame do Facebook
 *		showFaces: (bool) [TRUE|*FALSE] Mostra as fotos das pessoas que curtiram
 *		verb: (string) [*like|recommend] Texto do botão de ação
 *		color: (string) [*light|dark] Estilo de cor do plugin
 *		echo: (bool) [*TRUE|FALSE] True se for para dar echo no iframe, false se for para retornar na função
 *		container: (mixed) [*FALSE|<div, span, section, …>] Se for false, não envolve em um container, se for uma tag, coloca no container
 *		container-class: (string) Classe que será colocada no container
 * @return (string) Tag do iFrame montada.
 */
function kis_social_facebook_like_button($url, $appId, $options=array()) {
	$settings = array(
		"layoutStyle" => "standard", 
		"width" => 450,
		"height" => -1,
		"showFaces" => false,
		"verb" => "like",
		"color" => "light",
		"echo" => true, 
		"container" => false,
		"container-class"=>"kis-social-facebook-like-button"
	);
	
	// Se a altura estiver setada como -1 (default), coloca as alturas padrões do facebook.
	if($settings['height'] == -1) {
		if($settings['layoutStyle'] == "standard" && $settings['showFaces']==true) {
			$settings['height'] = 80;
		} elseif($settings['layoutStyle'] == "standard" && $settings['showFaces']==false) {
			$settings['height'] = 35;
		} elseif($settings['layoutStyle'] == "button_count") {
			$settings['height'] = 21;
		} elseif($settings['layoutStyle'] == "box_count") {
			$settings['height'] = 90;
		}
	}
	$settings = array_merge($settings, $options);
	$urlFacebook = "http://www.facebook.com/plugins/like.php";
	
	$urlIFrame = "{$urlFacebook}?href=" . urlencode($url);
	$urlIFrame .= "&amp;send=false";
	$urlIFrame .= "&amp;layout=" . $settings['layoutStyle'];
	$urlIFrame .= "&amp;width=" . $settings['width'];
	$urlIFrame .= "&amp;show_faces=" . $settings['showFaces'];
	$urlIFrame .= "&amp;action=" . $settings['verb'];
	$urlIFrame .= "&amp;colorscheme=" . $settings['color'];
	$urlIFrame .= "&amp;font";
	$urlIFrame .= "&amp;height=" . $settings['height'];
	$urlIFrame .= "&amp;appId=" . $appId;
	
	$iframe = '<iframe src="' . $urlIFrame . '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $settings['width'] . 'px; height:' . $settings['height'] . 'px;" allowTransparency="true"></iframe>';
	
	if($settings['container']!==false) {
		$classeContainer = "";
		if(!empty($settings['container-class'])) $classeContainer = " class=\"{$settings['container-class']}\"";
		$iframe = "<" . $settings['container'] . $classeContainer . ">" . $iframe . "</" . $settings['container'] . ">";
	}
	
	if($settings['echo']) {
		echo $iframe;
	} else {
		return $iframe;
	}
}



/**
 * Monta o like box do facebook
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param mixed $fanpageUrl URL da fanpage que será dada a ação de like.
 * @param mixed $appId ID do APP cadastrado no facebook.
 * @param array $options Opções do plugin:
 *		width: (int) Largura do iFrame do Facebook
 *		height: (int) Altura do iFrame do Facebook
 *		color: (string) [*light|dark] Estilo de cor do plugin
 *		showFaces: (bool) [*TRUE|FALSE] Mostra as fotos das pessoas que curtiram
 *		borderColor: (string) [Hexadecimal com #] Cor da borda do box do facebook. Formato: #FF9900
 *		showStream: (bool) [TRUE|*FALSE] Mostra o stream do que foi postado na fanpage
 *		showHeader: (bool) [TRUE|*FALSE] Mostra o header com o logo do Facebook
 *		echo: (bool) [*TRUE|FALSE] True se for para dar echo no iframe, false se for para retornar na função
 *		container: (mixed) [*FALSE|<div, span, section, …>] Se for false, não envolve em um container, se for uma tag, coloca no container
 *		container-class: (string) Classe que será colocada no container
 * @return (string) Tag do iFrame montada.
 */
function kis_social_facebook_like_box($fanpageUrl, $appId, $options=array()) {
 	$settings = array(
 		"width" => 292,
 		"height" => -1,
 		"color" => "light",
 		"showFaces" => true,
 		"borderColor" => "",
 		"showStream" => false,
 		"showHeader" => false,
 		"echo" => true, 
 		"container" => false,
 		"container-class"=>"kis-social-facebook-like-box"
 	);
 	
 	$sf = $settings['showFaces'];
 	$ss = $settings['showStream'];
 	$sh = $settings['showHeader'];
 	
 	// Se a altura estiver setada como -1 (default), coloca as alturas padrões do facebook.
 	if($settings['height'] == -1) {
 		if(!$sf && !$ss && !$sh) $settings['height'] = 62;
 		elseif($sf && !$ss && !$sh) $settings['height'] = 258;
 		elseif(!$sf && $ss && !$sh) $settings['height'] = 395;
 		elseif(!$sf && !$ss && $sh) $settings['height'] = 62;
 		elseif($sf && $ss && !$sh) $settings['height'] = 558;
 		elseif($sf && !$ss && $sh) $settings['height'] = 290;
 		elseif(!$sf && $ss && $sh) $settings['height'] = 427;
 		elseif($sf && $ss && $sh) $settings['height'] = 590;
 	}
 	unset($sf);
 	unset($ss);
 	unset($sh);
 	$settings = array_merge($settings, $options);
 	
 	// Monta o iFrame
 	$urlFacebook = "http://www.facebook.com/plugins/likebox.php";
 	
 	$urlIFrame = "{$urlFacebook}?href=" . urlencode($fanpageUrl);
 	$urlIFrame .= "&amp;width=" . $settings['width'];
 	$urlIFrame .= "&amp;height=" . $settings['height'];
 	$urlIFrame .= "&amp;colorscheme=" . $settings['color'];
 	$urlIFrame .= "&amp;show_faces=" . $settings['showFaces'];
 	if(!empty($settings['borderColor'])) $urlIFrame .= "&amp;border_color=" . urlencode($settings['borderColor']);
 	else $urlIFrame .= "&amp;border_color";
 	$urlIFrame .= "&amp;stream=" . $settings['showStream'];
 	$urlIFrame .= "&amp;header=" . $settings['showHeader'];
 	$urlIFrame .= "&amp;appId=" . $appId;
 	
 	$iframe = '<iframe src="' . $urlIFrame . '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $settings['width'] . 'px; height:' . $settings['height'] . 'px;" allowTransparency="true"></iframe>';
 	
 	if($settings['container']!==false) {
 		$classeContainer = "";
 		if(!empty($settings['container-class'])) $classeContainer = " class=\"{$settings['container-class']}\"";
 		$iframe = "<" . $settings['container'] . $classeContainer . ">" . $iframe . "</" . $settings['container'] . ">";
 	}
 	
 	if($settings['echo']) {
 		echo $iframe;
 	} else {
 		return $iframe;
 	}
}
 


/**
 * Monta o share button do twitter
 *
 * @author André Gumieri
 * @since 1.1
 *
 * @param string $shareUrl URL da página que será dado o share. Se não especificado, pega a URL atual.
 * @param string $shareTexto Texto do que será compartilhado. Se não especificado, pega o título da página.
 * @param string $twitterUser Usuário do twitter que será mencionado no share.
 * @param array $options Opções do plugin:
 * 		showCount: (bool) [*TRUE|FALSE] Se deve mostrar o balão com a contagem de shares
 *		recommendUser: (string) Usuário recomendado no tweet
 *		hashtag: (string) Hashtag que será colocada junto do tweet
 *		largeButton: (bool) [TRUE|*FALSE] Versão grande do botão de tweet
 *		language: (string) [*pt] Linguagem do botão. Se vazio, é inglês
 *		textoBotao: (string) [*Tweetar] Texto do botão
 *		echo: (bool) [*TRUE|FALSE] True se for para dar echo no iframe, false se for para retornar na função
 *		container: (mixed) [*FALSE|<div, span, section, …>] Se for false, não envolve em um container, se for uma tag, coloca no container
 *		container-class: (string) Classe que será colocada no container
 *		printJavascript: (bool) [*TRUE|FALSE] True se for para exibir o Javscript e False se não.
 * @return (string) Tag do twitter montada.
 */
function kis_social_twitter_share_button($shareUrl="", $shareText="", $twitterUser="", $options=array()) {
	$settings = array(
		"showCount" => true,
		"recommendUser" => "",
		"hashtag" => "",
		"largeButton" => false,
		"language" => "pt",
		"textoBotao" => "Tweetar",
		"echo" => true, 
		"container" => false,
		"container-class"=>"kis-social-twitter-share-button",
		"printJavascript"=>true
	);
	$settings = array_merge($settings, $options);
	
	$attrs = "";
	if(!empty($shareUrl)) $attrs .= " data-url=\"{$shareUrl}\"";
	if(!empty($shareText)) $attrs .= " data-text=\"{$shareText}\"";
	if(!empty($twitterUser)) $attrs .= " data-via=\"{$twitterUser}\"";
	if(!$settings['showCount']) $attrs .= " data-count=\"none\"";
	if(!empty($settings['recommendUser'])) $attrs .= " data-related=\"{$settings['recommendUser']}\"";
	if(!empty($settings['hashtag'])) $attrs .= " data-hashtags=\"{$settings['hashtag']}\"";
	if($settings['largeButton']) $attrs .= " data-size=\"large\"";
	if(!empty($settings['language'])) $attrs .= " data-lang=\"{$settings['language']}\"";
	
	
	$button = '<a href="https://twitter.com/share" class="twitter-share-button" ' . $attrs . '>' . $settings['textoBotao'] . '</a>';
	
	if($settings['container']!==false) {
		$classeContainer = "";
		if(!empty($settings['container-class'])) $classeContainer = " class=\"{$settings['container-class']}\"";
		$button = "<" . $settings['container'] . $classeContainer . ">" . $button . "</" . $settings['container'] . ">";
	}
	
	if($settings['printJavascript']) {
		$button.= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
	}
	
	if($settings['echo']) {
		echo $button;
	} else {
		return $button;
	}
}



/**
 * Monta o twitter feed
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param string $twitterUser Usuário que será resgatado o feed.
 * @param string $qty Quantidade de tweets retornados. Default: 5
 * @param array $options Opções do plugin:
 *		classeAdicional: (string) Classes adicionais para o container
 *		containerId: (string) ID do container
 *		echo: (bool) [*TRUE|FALSE] True se for para dar echo no iframe, false se for para retornar na função
 * @return (string) Tags do feed montadas.
 */
$_kis_social_twitter_feed = array();
$_kis_social_twitter_nonce = "";
function kis_social_twitter_feed($twitterUser, $qty=5, $options=array()) {
	global $_kis_social_twitter_feed, $_kis_social_twitter_nonce;
 	$settings = array(
 		"classeAdicional" => "",
 		"containerId" => "",
 		"echo" => true
 		
 	);
 	$settings = array_merge($settings, $options);
 	
 	// Registra e chama o script social do twitter
 	wp_register_script("kis_social_twitter_feed_js", get_bloginfo("template_directory")."/kis/kis_social/kis.social.twitter.js", array("jquery", "kislibs_kis_general"), "1.0", true);
 	wp_enqueue_script("kis_social_twitter_feed_js");

	// determina as classes adicionais
	$classes = "";
	if(!empty($settings['classeAdicional'])) {
		$classes = " {$settings['classeAdicional']}";
	}
 	
 	// determina o ID do container
 	$id = "kis_social_twitter_feed_" . uniqid("");
 	if(!empty($settings['containerId'])) {
 		$id = $settings['containerId'];
 	}
 	
 	
 	$saida = "";
 	$saida .= "<div class=\"kis_social_twitter_feed{$classes}\" id=\"{$id}\"></div>\n";

	// Se for a primeira passagem, adiciona as actions
	if(empty($_kis_social_twitter_feed)) {
		$_kis_social_twitter_nonce = wp_create_nonce('kis_social_twitter_feed');
		add_action('wp_print_footer_scripts', '_kis_social_twitter_feed_print_script_on_footer');
	}
	
	// Adiciona o script que será impresso no footer
	$_kis_social_twitter_feed[] = "Kis.Social.Twitter.init({containerId: '{$id}', url: '" . get_bloginfo("url")."/wp-admin/admin-ajax.php" . "', twitterUser: '{$twitterUser}', qty: {$qty}, vars:{'action':'kis_social_twitter_feed_ajax', '_wpnonce':'{$_kis_social_twitter_nonce}' } });";
 	
 	
 	// Retorna ou imprime as tags
 	if($settings['echo']) echo $saida;
 	else return $saida;
}

function _kis_social_twitter_feed_print_script_on_footer() {
	global $_kis_social_twitter_feed;
	
	$saida = "<script>\n";
	foreach($_kis_social_twitter_feed as $inlineScript){
		$saida .= $inlineScript . "\n";
	}
	$saida .= "</script>\n";
	
	echo $saida;
}

function _kis_social_twitter_feed_ajax() {
	// Checa o NONCE
	$nonce=$_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($nonce, 'kis_social_twitter_feed') ) die("Security check failed.");
	
	
	// Seta as variaveis de configuracoes
	$username = $_POST['user'];
	$qty = $_POST['qty'];
	
	// Path para o cache
	$file_cache = kis_get_files_path("social") . "/twitter_{$username}.php";
	
	
	// Gera a URL
	$url = "https://api.twitter.com/1/statuses/user_timeline.json";
	$url.= "?include_entities=true";
	$url.= "&include_rts=true";
	$url.= "&screen_name={$username}";
	$url.= "&count={$qty}";
	
	
	// Verifica se o cache é mais velho do que 1 minuto
	$useCache = true;
	
	// Verifica se o arquivo de cache existe
	if(!file_exists($file_cache)) $useCache = false;
	
	// Verifica se o arquivo de cache é mais velho que 1 minuto
	if($useCache) {
		$timeCacheFile = filemtime($file_cache);
		$timeEndCache = mktime(
			intval(date("H", $timeCacheFile)), 
			intval(date("i", $timeCacheFile))+1, 
			intval(date("s", $timeCacheFile)), 
			intval(date("m", $timeCacheFile)), 
			intval(date("d", $timeCacheFile)), 
			intval(date("Y", $timeCacheFile))
		);
		if($timeEndCache>=mktime()) $useCache = false;
	}
	
	
	// Mostra o arquivo de cache ou busca um novo no twitter
	$json = "";
	if($useCache) {
		$json = file_get_contents($file_cache);
	} else {
		$json = kis_get_file_contents($url);
		file_put_contents($file_cache, $json);
	}
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	die($json);
}
add_action('wp_ajax_kis_social_twitter_feed_ajax', '_kis_social_twitter_feed_ajax');
add_action('wp_ajax_nopriv_kis_social_twitter_feed_ajax', '_kis_social_twitter_feed_ajax');



















?>