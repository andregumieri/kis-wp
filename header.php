<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php bloginfo("name"); ?> - <?php bloginfo("description"); ?></title>
	
	<?php
	$css = array();
	$css[] = get_template_directory_uri()."style.css"; // Estilo padrão do wordpress
	// $css[] = get_template_directory_uri()."assets/fonts/stylesheet.css";  // Folha de estilo adicional
	
	kis_minify($css, "css", kis_is_dev_enviroment());
	// Parametro 1 - Array com os arquivos que devem ser carregados
	// Parametro 2 - Tipo de carregamento (css ou js)
	// Parametro 3 - Boolean: True para debug mode (mostra os arquivos chamada por chamada, sem unificar);
	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
