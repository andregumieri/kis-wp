<?php
require("kis/kis.php"); // Funções padrões do KIS (Required)
require("kis/kis_cpt.php"); // Módulo de custom post type
require("kis/kis_manager.php"); // Módulo de painel de settings
require("kis/kis_minify.php"); // Módulo para fazer minify de scripts e css
require("kis/kis_string.php"); // Módulo com funções de strings
require("kis/kis_thumbnail.php"); // Módulo de thumbnails do post
require("kis/kis_social.php");

// Inicia o jQuery
wp_enqueue_script("jquery");

// Adiciona suporte ao Post Thumbnail
add_theme_support( 'post-thumbnails' ); 

// Alguns Exemplos de uso das funções do KIS.
// Apague esta linha e este arquivo quando for programar
require("functions-example.php");
?>