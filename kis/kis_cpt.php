<?php
/**
 * KIS: Wordpress - Custom Post Type functions
 *
 * Functions to made easy the creation of custom post type in Wordpress.
 *
 * @author André Gumieri
 * @version 0.1
 *
 * @package KIS
 * @subpackage Minify
 */



/** Global Vars **/ 
$kis_cpt=array();


 
/**
  * kis_cpt_register()
  * Create a new Custom Post Type
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @param $slug - Slug do CPT
  * @param $plural - Título do CPT no plural
  * @param $singular - Título do CPT no singular
  * @param $supports (array) - Recursos suportados pela tela do admin: ('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats'
  *
  * @return none.
  */
function kis_cpt_register($slug, $plural, $singular, $supports=array()) {
	global $kis_cpt;
	if(!isset($kis_cpt[$slug])) {
		$kis_cpt[$slug] = array(
			"plural" => $plural,
			"singular" => $singular,
			"supports" => $supports,
			"meta-boxes" => array()
		);
	} else {
		trigger_error("[KIS CPT] o CPT com slug '{$slug}' já foi registrado", E_USER_ERROR);
	}
}



/**
  * kis_cpt_add_meta_box()
  * Create a new meta box for a Custom Post Type
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @param $cpt_slug (string) - Slug do CPT ao qual a Meta Box se refere
  * @param $slug (string) - Slug da Meta Box que está sendo criada
  * @param $title (string) - Título da meta box
  * @param $context (string) - Local da página onde a meta box vai aparecer ('normal', 'advanced', ou 'side'). Default: 'advanced'
  * @param $priority (string) - A prioridade com que o box deve ser executado ('high', 'core', 'default' ou 'low'). Default: 'default'
  * @param $callback_args (array) - Argumentos passados para a função de callback da Meta Box. Default: array()
  *
  * @return none.
  */
function kis_cpt_add_meta_box($cpt_slug, $slug, $title, $context='advanced', $priority='default', $callback_args=array()) {
	global $kis_cpt;
	
	if(isset($kis_cpt[$cpt_slug])) {
		
		if( !isset($kis_cpt[$cpt_slug]['meta-boxes'][$slug]) ) {
			
			$callback_args["kis_cpt"] = $cpt_slug;
			$callback_args["kis_cpt_metabox"] = $slug;
			
			$kis_cpt[$cpt_slug]['meta-boxes'][$slug] = array(
				"title" => $title,
				"context" => $context,
				"priority" => $priority,
				"callback_args" => $callback_args,
				"fields" => array()
			);
			
		} else {
		
			trigger_error("[KIS CPT] A meta box '{$slug}' já existe para o CPT com slug '{$cpt_slug}' já foi registrado", E_USER_WARNING);
			
		}
		
	} else {
	
		trigger_error("[KIS CPT] o CPT com slug '{$cpt_slug}' não está registrado", E_USER_WARNING);
		
	}	
}



/**
  * kis_cpt_add_field()
  * Adiciona campos na meta box
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @param $cpt_slug (string) - Slug do CPT
  * @param $mb_slug (string) - Slug da Meta Box
  * @param $name (string) - Nome do campo
  * @param $label (string) - Título do campo
  * @param $type (string) - Tipo do campo ('text', 'checkbox', 'radio', 'select', 'upload' ou 'textarea'). Default: 'text'
  * @param $default (string) - Valor padrão do campo. Default: ""
  * @param $values (array) - Array de valores para os campos do tipo "radio" ou "select". Formato: Array( "valor1"=>"Descrição 1", "valor2"=>"Descrição 2" […] "valorN"=>"Descrição N" ). Default: array()
  *
  * @return none.
  */
function kis_cpt_add_field($cpt_slug, $mb_slug, $name, $label, $type="text", $default="", $values=array() ) {

	global $kis_cpt;

	if( isset($kis_cpt[$cpt_slug]['meta-boxes'][$mb_slug]) ) {
		
		if( !isset($kis_cpt[$cpt_slug]['meta-boxes'][$mb_slug]['fields'][$name]) ) {
	
			$kis_cpt[$cpt_slug]['meta-boxes'][$mb_slug]['fields'][$name] = array(
				"name" => $name,
				"label" => $label,
				"type" => $type,
				"default" => $default,
				"values" => $values
			);

		} else {
		
			trigger_error("[KIS CPT] O campo '{$name}' já existe para a Meta Box '{$cpt_slug}: {$mb_slug}'", E_USER_WARNING);
		
		}
		
	} else {
	
		trigger_error("[KIS CPT] A meta box '{$cpt_slug}: {$mb_slug}' não está registrada. Impossível adicionar o campo {$name}", E_USER_WARNING);
		
	}
}



/**
  * kis_cpt_meta_box_callback()
  * Callback gerado por cada meta box no admin
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @return none.
  */
function kis_cpt_meta_box_callback($post, $args) {
	global $post;
	$custom = get_post_custom($post->ID);

	$separador = "<div style=\"height: 20px; border-bottom: 1px solid #dfdfdf; width: 100%; margin-bottom: 20px; \"></div>";


	$cpt = $args['args']['kis_cpt'];
	$mb = $args['args']['kis_cpt_metabox'];
	$fields = $args['args']['fields'];
	$e = "";

	foreach($fields as $name=>$field):
		$value = $custom[$name][0];
		
		$e .= $separador;
		
		switch($field['type']) {
			case "select":
				if(!empty($field['values'])) {
					if(empty($value)) $value = $field['default'];
					
					$e .= "<p><strong>{$field['label']}</strong></p>";
					$e .= "<label class=\"screen-reader-text\">{$field['label']}</label>";
					$e .= "<select name=\"{$name}\" id=\"{$name}\">";
					$e .= "<option value=\"\"></option>";
					
					foreach($field['values'] as $fvkey=>$fv) {
						$selected = "";
						if($value==$fvkey) $selected = " selected=\"selected\"";

						$e .= "<option value=\"{$fvkey}\"{$selected}>{$fv}</option>";
						
					}
					
					$e .= "</select>";
				}
				break;
		
			case "radio":
				if(!empty($field['values'])) {
					if(empty($value)) $value = $field['default'];
					
					$e .= "<p><strong>{$field['label']}</strong></p>";
					$e .= "<label class=\"screen-reader-text\">{$field['label']}</label>";
					
					foreach($field['values'] as $fvkey=>$fv) {
						$checked = "";
						if($value==$fvkey) $checked = " checked=\"checked\"";
						$e .= "<label>";
						$e .= "<input type=\"radio\" name=\"{$name}\" value=\"{$fvkey}\"{$checked} />";
						
						$e.= " {$fv}</label><br />";
						
					}
				}
				break;
				
			case "checkbox":
				$checked = "";
				if($value==$field['default']) $checked = " checked=\"checked\"";
				$e .= "<p><label>";
				$e .= "<input type=\"checkbox\" name=\"{$name}\" id=\"{$name}\" value=\"{$field['default']}\"{$checked} />";
				$e.= " {$field['label']}</label></p>";
				break;
		
			case "textarea":
				if(empty($value)) $value = $field['default'];
				$e .= "<p><strong>{$field['label']}</strong></p>";
				$e .= "<label class=\"screen-reader-text\" for=\"{$name}\">{$field['label']}</label>";
				$e .= "<textarea name=\"{$name}\" id=\"{$name}\" style=\"width: 80%; height: 70px;\">{$value}</textarea>";
				break;
				
			case "upload": 
				//if(empty($value)) $value = $field['default'];
				$e .= "<p><strong>{$field['label']}</strong></p>";
				$e .= "<label class=\"screen-reader-text\" for=\"{$name}\">{$field['label']}</label>";
				
				$e .= "<input type=\"text\" readonly=\"readonly\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" style=\"background-color: #eee; color: #888; width: 80%;\" />";
				
				if(empty($value)) {
					$e .= " <a href=\"#\" class=\"kis_cpt_upload_btn\" data-ref-field=\"{$name}\">Upload</a>";
				} else {
					$e .= " <a href=\"#\" class=\"kis_cpt_upload_btn\" data-ref-field=\"{$name}\">Alterar</a>";
					$e .= " ou <a href=\"#\" class=\"kis_cpt_upload_remove_btn\" data-ref-field=\"{$name}\">Remover</a>";
				}

				break;
				
			case "text": 
			default:
				if(empty($value)) $value = $field['default'];
				$e .= "<p><strong>{$field['label']}</strong></p>";
				$e .= "<label class=\"screen-reader-text\" for=\"{$name}\">{$field['label']}</label>";
				$e .= "<input type=\"text\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" style=\"width: 80%;\" />";
				
		}

	endforeach;
	
	echo substr($e, strlen($separador));

}



/**
  * kis_cpt_init()
  * Init the Custom Post Types
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @return none.
  */
function kis_cpt_init() {
	global $kis_cpt;
	
	foreach($kis_cpt as $slug=>$options):
	   $labels = array(
	       'name' => _x($options['plural'], 'post type general name'),
	       'singular_name' => _x($options['singular'], 'post type singular name'),
	       'add_new' => _x('Novo', 'portfolio item'),
	       'add_new_item' => __('Novo'),
	       'edit_item' => __('Editar'),
	       'new_item' => __('Novo'),
	       'view_item' => __('Ver'),
	       'search_items' => __('Procurar'),
	       'not_found' =>  __('Nada encontrado'),
	       'not_found_in_trash' => __('Nada encontrado na lixeira'),
	       'parent_item_colon' => ''
	   );
	
	   $args = array(
	       'labels' => $labels,
	       'public' => true,
	       'publicly_queryable' => true,
	       'show_ui' => true,
	       'query_var' => true,
	       /* 'menu_icon' => get_stylesheet_directory_uri() . '/article16.png', */
	       'rewrite' => true,
	       'capability_type' => 'post',
	       'hierarchical' => false,
	       'menu_position' => null,
	       'supports' => $options['supports']
	     );
	
		register_post_type( $slug , $args );
		
	endforeach;
}



/**
  * kis_cpt_admin_init()
  * Init the Custom Post Types
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @return none.
  */
function kis_cpt_admin_init() {
	global $kis_cpt;

	foreach($kis_cpt as $slug=>$options):
		foreach($options['meta-boxes'] as $mbslug=>$mb):
			
			$mbcallback = $mb['callback_args'];
			$mbcallback['fields'] = $mb['fields'];
			
			//print_r($mb);
			
			add_meta_box(
				$mbslug.'-meta', 
				$mb['title'], 
				"kis_cpt_meta_box_callback", 
				$slug, 
				$mb['context'], 
				$mb['priority'], 
				$mbcallback
			);
			
		endforeach;
		
	endforeach;
}



/**
  * kis_cpt_save_post()
  * Ações para salvar o post
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @return none.
  */
function kis_cpt_save_post() {
	global $kis_cpt, $post;
	$slug = $_POST['post_type'];
	if(!empty($kis_cpt[$slug]['meta-boxes'])) {
		foreach($kis_cpt[$slug]['meta-boxes'] as $mb_slug=>$mb) {
			// Passa por cada meta-box
			foreach($mb['fields'] as $name=>$field) {
				// Passa por cada campo
				if(isset($_POST[$name])) update_post_meta($post->ID, $name, $_POST[$name]);
			}
		}
	}
}



/**
  * kis_cpt_admin_scripts()
  * Scripts carregados juntos do admin
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @return none.
  */
function kis_cpt_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('kis_cpt', get_bloginfo("template_directory").'/kis/kis_cpt/default.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('kis_cpt');
}



/**
  * kis_cpt_admin_styles()
  * Styles carregados juntos do admin
  *
  * @author André Gumieri
  * @since 0.1
  *
  * @return none.
  */
function kis_cpt_admin_styles() {
	wp_enqueue_style('thickbox');
}


add_action('init', 'kis_cpt_init');
add_action("admin_init", "kis_cpt_admin_init");
add_action('save_post', 'kis_cpt_save_post');
add_action('admin_print_scripts', 'kis_cpt_admin_scripts');
add_action('admin_print_styles', 'kis_cpt_admin_styles');

?>