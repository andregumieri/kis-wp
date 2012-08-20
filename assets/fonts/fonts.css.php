<?php
$saida = "";
$extensoesNecessarias = array("eot", "woff", "ttf", "svg");
if($dir = opendir(".")) {
	$fontes = array();
	while (false !== ($entry = readdir($dir))) {
		$fileInfo = pathinfo($entry);
		
		if($entry=="." || $entry==".." || is_dir($entry) || !preg_match('/(' . implode("|", $extensoesNecessarias) . ')/i', $fileInfo['extension'])) continue;
		
		$fontes[$fileInfo['filename']][$fileInfo['extension']] = $entry;
	}
	
	foreach($fontes as $fonte=>$variacoes) {
		$saida .= "/* Fonte $fonte */\n";
		$fonteErro = "";
		foreach($extensoesNecessarias as $ext) {
			if(!isset($variacoes[$ext])) {
				$fonteErro = "Arquivo {$fonte}.{$ext} não encontrado\n";
			}
		}
		
		if(!empty($fonteErro)) {
			$saida .= "/*\nErros encontrados:\n";
			$saida .= $fonteErro;
			$saida .= "*/\n\n";
			continue ;
		}
		
		$saida .= "@font-face {\n";
		$saida .= "\tfont-family: '{$fonte}';\n";
		$saida .= "\tsrc: url('{$variacoes['eot']}');\n";
		$saida .= "\tsrc: url('{$variacoes['eot']}?#iefix') format('embedded-opentype'),\n";
		$saida .= "\t\turl('{$variacoes['woff']}') format('woff'),\n";
		$saida .= "\t\turl('{$variacoes['ttf']}') format('truetype'),\n";
		$saida .= "\t\turl('{$variacoes['svg']}#ColaborateThinRegular') format('svg');\n";
		$saida .= "\tfont-weight: normal;\n";
		$saida .= "\tfont-style: normal;\n";
		$saida .= "}\n\n";
	}
}

header("Content-type: text/css; charset=utf-8");
echo $saida;
?>