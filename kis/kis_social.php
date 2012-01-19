<?php
/**
 * KIS: Wordpress - Social Functions
 *
 * Funções de redes sociais
 *
 * @author André Gumieri
 * @version 1.0
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
 * Monta o share button do twitter
 *
 * @author André Gumieri
 * @since 1.0
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
		"container-class"=>"kis-social-facebook-like-button"
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
	
	$button.= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
	if($settings['echo']) {
		echo $button;
	} else {
		return $button;
	}
}

?>