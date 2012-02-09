	<?php wp_footer(); ?>
	
	<?php 
	/* Chamadas manuais de scripts vão aqui */
	$js = array();
	// $js[] = get_template_directory_uri()."assets/js/base.js";
	// $js[] = get_template_directory_uri()."assets/js/classes/arquivo.class.js";
	
	kis_minify($js, "js", kis_is_dev_enviroment());
	// Parametro 1 - Array com os arquivos que devem ser carregados
	// Parametro 2 - Tipo de carregamento (css ou js)
	// Parametro 3 - Boolean: True para debug mode (mostra os arquivos chamada por chamada, sem unificar);
	?>
</body>
</html>