<?php
/**
 * KIS: Wordpress - Theme Manager
 *
 * Funções para facilitar a criação de um painel de administração de tema
 *
 * @author André Gumieri
 * @version 1.0.1
 *
 * @package KIS
 * @subpackage Manager
 */

$kis_manager = array();


/**
 * kis_manager_add()
 * Cria uma nova tela de administração
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param $slug - Slug da tela
 * @param $titulo - Título do tela
 * @param $local - Em qual menu o tema deve aparecer (index, edit, upload, link-manager, edit.php?post_type=page...)
 * @param $role - Restrição de acesso do painel (super_admin, administrator, editor, author, contributor, subscriber). Default: administrator
 *
 * @link http://codex.wordpress.org/Function_Reference/add_submenu_page
 * @link http://codex.wordpress.org/Roles_and_Capabilities
 *
 * @return none.
 */
function kis_manager_add($slug, $titulo, $local='themes', $role="administrator") {
	global $kis_manager;
	
	if(isset($kis_manager[$slug])) trigger_error("[KIS Manager] já existe um menu com nome '{$slug}'.", E_USER_ERROR);
	
	$kis_manager[$slug] = array(
		"slug" => $slug,
		"titulo" => $titulo,
		"local" => $local,
		"role" => $role,
		"paineis" => array()
	);
}


/**
 * kis_manager_add_panel()
 * Cria um novo painel de administração de tema
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param $slug - Slug do Painel
 * @param $titulo - Título do Painel
 *
 * @return none.
 */
function kis_manager_add_panel($menu_slug, $slug, $titulo) {
	global $kis_manager;
	
	// Se não existir o menu
	if(!isset($kis_manager[$menu_slug])) trigger_error("[KIS Manager] Não foi encontrado o menu com nome {$menu_slug}", E_USER_ERROR);
	
	// Se já existir o painel
	if(isset($kis_manager[$menu_slug]['paineis'][$slug])) trigger_error("[KIS Manager] Um painel com nome '{$slug}' já foi registrado para o menu {$menu_slug}.", E_USER_ERROR);
	
	$kis_manager[$menu_slug]['paineis'][$slug] = array(
		"slug" => $slug,
		"titulo" => $titulo,
		"opcoes" => array()
	);
}


/**
 * kis_manager_add_option()
 * Cria um novo painel de administração de tema
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param $menu_slug - Slug do menu
 * @param $painel_slug - Slug do painel
 * @param $id - ID do campo
 * @param $titulo - Título do campo
 * @param $tipo - Tipo do campo
 * @param $default - Valor default do campo
 * @param $valores (array) - Array de valores para o Combo e Radio
 * @param $grupo_titulo - Titulo para agrupar campos por sessões
 *
 * @return none.
 */
function kis_manager_add_option($menu_slug, $painel_slug, $id, $titulo, $tipo="text", $grupo_titulo="", $default="", $valores=array()) {
	global $kis_manager;
	
	$id = "kis_manager_".$id;
	
	// Se não existir o menu
	if(!isset($kis_manager[$menu_slug]['paineis'][$painel_slug])) trigger_error("[KIS Manager] Não foi encontrado o painel com nome {$painel_slug}", E_USER_ERROR);
	
	// Se já existir o painel
	if(isset($kis_manager[$menu_slug]['paineis'][$painel_slug]['opcoes'][$id])) trigger_error("[KIS Manager] Um campo com ID '{$id}' já foi registrado para o painel {$painel_slug}.", E_USER_ERROR);
	
	
	$kis_manager[$menu_slug]['paineis'][$painel_slug]['opcoes'][$id] = array(
		"id" => $id,
		"titulo" => $titulo,
		"tipo" => $tipo,
		"default" => $default,
		"valores" => $valores,
		"grupo_titulo" => $grupo_titulo
	);
}


/**
 * kis_manager_parse_local()
 * Pega o local indicado pela criacao do menu e arruma para o formato
 * correto utilizado pelas funções do Wordpress
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param $local - Local em que o menu vai aparecer
 *
 * @return local arrumado (string).
 */
function kis_manager_parse_local($local) {
	if( strpos($local, ".php") === false ) $local .= ".php";
	return $local;
}


/**
 * kis_manager_parse_role()
 * Pega o role indicado na criação do menu e associa a um capability
 * compatível para as funções padrões do wordpress.
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @param $role - Role indicada na criação do menu
 *
 * @return Capability correta (string).
 */
function kis_manager_parse_role($role) {
	switch($role) {
		case "super_admin":
			$role = "manage_sites";
			break; 
			
		case "administrator":
			$role = "add_users";
			break;
			
		case "editor":
			$role = "edit_pages";
			break;
			
		case "author":
			$role = "publish_posts";
			break;
			
		case "contributor":
			$role = "edit_posts";
			break;
			
		case "subscriber":
			$role = "read";
			break;
	}
	
	return $role;
}


/**
 * kis_manager_make_input()
 * Cria os campos (inputs) dos formulários
 *
 * @param $name - Identificação (nome) do campo
 * @param $type - Tipo de campo
 * @param $default - Valor padrão do campo
 * @param $values - Valores para os campos de Radio e Select
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @return none.
 */
function kis_manager_make_input($name, $type, $value="", $values=array(), $check=false) {
	$e = "";
	
	switch($type) {
		case "select":				
			$e .= "<select name=\"{$name}\" id=\"{$name}\">";
			$e .= "<option value=\"\"></option>";	
			foreach($values as $fvkey=>$fv) {
				$selected = "";
				if($value==$fvkey) $selected = " selected=\"selected\"";
				$e .= "<option value=\"{$fvkey}\"{$selected}>{$fv}</option>";
			}
			$e .= "</select>";
			break;
	
		case "radio":
			foreach($values as $fvkey=>$fv) {
				$checked = "";
				if($value==$fvkey) $checked = " checked=\"checked\"";
				$e .= "<label>";
				$e .= "<input type=\"radio\" name=\"{$name}\" value=\"{$fvkey}\"{$checked} />";
				$e.= " {$fv}</label><br />";
				
			}
			break;
			
		case "checkbox":
			$checked = "";
			if($check) $checked = " checked=\"checked\"";
			$e .= "<input type=\"checkbox\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\"{$checked} />";
			$e.= " {$field['label']}</label>";
			break;
	
		case "textarea":
			$e .= "<textarea name=\"{$name}\" id=\"{$name}\"  class=\"regular-text\" style=\"height: 70px; width: 23em;\">{$value}</textarea>";
			break;
			
		case "upload": 		
			$e .= "<input type=\"text\" readonly=\"readonly\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\"  class=\"regular-text\" style=\"background-color: #eee; color: #888; \" />";
			
			if(empty($value)) {
				$e .= " <a href=\"#\" class=\"kis_manager_upload_btn\" data-ref-field=\"{$name}\">Upload</a>";
			} else {
				$e .= " <a href=\"#\" class=\"kis_manager_upload_btn\" data-ref-field=\"{$name}\">Alterar</a>";
				$e .= " ou <a href=\"#\" class=\"kis_manager_upload_remove_btn\" data-ref-field=\"{$name}\">Remover</a>";
			}
	
			break;
			
		case "text": 
		default:
			$e .= "<input type=\"text\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" class=\"regular-text\" />";
	}
	
	return $e;

}

/**
 * kis_manager_admin_menu()
 * Create a new Custom Post Type
 *
 * @author André Gumieri
 * @since 0.1
 *
 * @param $slug - Slug do Painel
 * @param $titulo - Título do Painel
 *
 * @return none.
 */
function kis_manager_admin_menu() {
	global $kis_manager;
	
	foreach($kis_manager as $m) {
		$role = kis_manager_parse_role($m['role']);
		$local = kis_manager_parse_local($m['local']);
		
		add_submenu_page($local, $m['titulo'], $m['titulo'], $role, $m['slug'], 'kis_manager_admin_menu_callback');
	}
}


/**
 * kis_manager_save()
 * Salva as configurações em banco de dados
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @return none.
 */
function kis_manager_save() {
	global $pagenow, $kis_manager;
	$pageget = $_GET['page'];
	
	/*
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	*/
	
	foreach($kis_manager as $km): if( $pagenow==kis_manager_parse_local($km['local']) && $pageget==$km['slug'] ): 
		
		foreach($km['paineis'] as $painel): foreach($painel['opcoes'] as $opcao):
			if(isset($_POST[$opcao['id']])) {
				update_option( $opcao['id'], $_POST[$opcao['id']] );
			} elseif ($opcao['tipo']=="checkbox") {
				update_option( $opcao['id'], "" );
			}
		endforeach; endforeach; 
		echo "<div class=\"wrap\">";
		echo "<h2>{$km['titulo']}</h2>";
		echo "<p>As configurações foram salvas. Você pode <a href=\"{$pagenow}?page={$km['slug']}\">voltar para as configurações</a> ou <a href=\"" . get_bloginfo("url") . "\" target=\"_blank\">ver o site</a></p>";
		echo "</div>";
	endif; endforeach;
	
	//$html .= '<div id="message" class="updated below-h2"><p>Configurações salvas.</p></div>';
}


/**
 * kis_manager_admin_menu_callback()
 * Monta a tela de configuração
 *
 * @author André Gumieri
 * @since 1.0
 *
 * @return none.
 */
function kis_manager_admin_menu_callback() {
	global $pagenow, $kis_manager;
	$pageget = $_GET['page'];
	
	if( !empty($_POST) ) {
		kis_manager_save();
		return;
	}
	
	foreach($kis_manager as $km):
		if( $pagenow==kis_manager_parse_local($km['local']) && $pageget==$km['slug'] ):
			$html = "<div class=\"wrap\">";
			
			$html .= "<h2 class=\"nav-tab-wrapper\"> <small style=\"margin: 0 12px; font-size: 12px; font-weight: bold; color: #999; text-transform: uppercase;\">{$km['titulo']}</small> ";
			
			$paineis = "";
			
			$first_panel = true;
			foreach($km['paineis'] as $painel) {
				$active = "";
				$style = "display: none;";
				if($first_panel) {
					$active = "nav-tab-active";
					$style = "";
				}
				
				$html .= "<a href=\"#{$painel['slug']}\" class=\"nav-tab {$active}\">{$painel['titulo']}</a>";
				
				$paineis .= "<div class=\"kis_manager_panel\" id=\"kis_manager_{$km['slug']}_{$painel['slug']}\" style=\"{$style}\">";
				$paineis .= "<table class=\"form-table\"><tbody>";

				$ultimo_grupo = "";
				$first = true;
				foreach($painel['opcoes'] as $opcao):
					if($ultimo_grupo!=$opcao['grupo_titulo'] && !empty($opcao['grupo_titulo'])) {
						if(!$first) {
							$paineis .= "<tr valign=\"top\">";
							$paineis .= "<th scope=\"row\"></th>";
							$paineis .= "<td></td>";
							$paineis .= "</tr>";
						}
						$paineis .= "<tr valign=\"top\">";
						$paineis .= "<th scope=\"row\"><strong style=\"font-size: 14px\">{$opcao['grupo_titulo']}</strong></th>";
						$paineis .= "<td></td>";
						$paineis .= "</tr>";
					}
					
					$paineis .= "<tr valign=\"top\">";
					$paineis .= "<th scope=\"row\"><label for=\"{$opcao['id']}\">{$opcao['titulo']}</label></th>";
					
					$opcao_checada = false;
					$f_value = get_option($opcao['id']);
					if( $f_value===false ) {
						$f_value = $opcao['default'];
					}
					
					if($opcao['tipo']=="checkbox") {
						$f_value = $opcao['default'];
						$cval = get_option($opcao['id']);
						if ( !empty($cval) ) {
							$opcao_checada = true;
						}
					}
					
					$f_value = str_replace(array("\\\"", "\\'"), array("\"", "'"), $f_value);
					
					//function kis_manager_make_input($name, $type, $value="", $values=array()) {
					$paineis .= "<td>" . kis_manager_make_input($opcao['id'], $opcao['tipo'], $f_value, $opcao['valores'], $opcao_checada) . "</td>";
					$paineis .= "</tr>";
					$ultimo_grupo = $opcao['grupo_titulo'];
					$first = false;
				endforeach;
				
				$paineis .= "</tbody></table>";
				$paineis .= "</div>";
				$first_panel = false;
			}
			
			$html .= "</h2>";
			
			$html .= "<form method=\"post\" action=\"$pagenow?page={$km['slug']}\">";
			$html .= $paineis;
			
			$html .= "<p class=\"submit\"><input type=\"submit\" name=\"submit\" id=\"submit\" class=\"button-primary\" value=\"Salvar Alterações\"></p>";
			
			$html .= "</form>";
			
			$html .= "</div>";
			
			echo $html;
		endif;
		
	endforeach;

}


/**
  * kis_manager_admin_scripts()
  * Scripts carregados juntos do admin
  *
  * @author André Gumieri
  * @since 1.0
  *
  * @return none.
  */
function kis_manager_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('kis_manager', get_bloginfo("template_directory").'/kis/kis_manager/default.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('kis_manager');
}


add_action('admin_print_scripts', 'kis_manager_admin_scripts');
add_action('admin_menu', 'kis_manager_admin_menu');
?>