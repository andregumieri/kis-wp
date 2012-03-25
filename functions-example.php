<?php
	////////////////////////////////
	// KIS CPT
	////////////////////////////////
	
	// Registra novo custom post type (CPT)
	// Param 1 - Slug do CPT
	// Param 2 - Título (plural) do CPT
	// Param 3 - Título (singular) do CPT
	// Param 4 - Array dos campos a serem exibidos
	kis_cpt_register("projetos", "Projetos", "Projeto", array("revision","title","thumbnail"));
	
	// Registra a taxonomia (categoria ou tags)
	// Param 1 - Slug do CPT registrado acima
	// Param 2 - Slug da taxonomia a ser registrada
	// Param 3 - Título (singular) da taxonomia dentro do CPT
	// Param 4 - Título (plural) da taxonomia dentro do CPT
	kis_cpt_add_taxonomy("projetos", "projetos-categorias", "Categoria", "Categorias");
	
	// Adiciona uma metabox de campos personalizados no CPT
	// Param 1 - Slug do CPT registrado acima
	// Param 2 - Slug da metabox
	// Param 3 - Título da metabox
	kis_cpt_add_meta_box("projetos", "informacoes", "Informações");
	
	// Adiciona um campo na metabox registrada acima
	// Param 1 - Slug do CPT que tem a metabox
	// Param 2 - Slug da metabox dentro do CPT informado
	// Param 3 - Slug (name) do campo adicionado
	// Param 4 - Título do campo adicionado
	// Param 5 - Tipo do campo adicionado
	// Param 6 (opcional) - Valor padrão do campo, ou valor selecionado em checkbox, radio e option.
	// Param 7 (opcional) - Array de opções para determinados tipos de campos
	kis_cpt_add_field("projetos", "informacoes", "descricao", "Descrição", "textarea");
	
	
	////////////////////////////////
	// Exemplo Kis Ajax Form (JS)
	////////////////////////////////
	wp_enqueue_script("kislibs_kis_ajax-form");
	function ajaxForm() {
		$nome = $_POST['nome'];
		$email = $_POST['email'];
		
		if(empty($nome) || empty($email)) {
			die("Preencha os campos");
		}
		die("ok");
	}
	add_action('wp_ajax_nopriv_ajaxForm', 'ajaxForm');
	add_action('wp_ajax_ajaxForm', 'ajaxForm');
	
	
	////////////////////////////////
	// Placeholder
	////////////////////////////////
	wp_enqueue_script("kislibs_kis_placeholder");
?>