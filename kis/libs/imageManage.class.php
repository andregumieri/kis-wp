<?php
/*
@class imageManage
@abstract Esta classe faz o manage de imagens no PHP
@version 0.1.1
*/
class imageManage {
	protected $path;
	protected $type;
	protected $width;
	protected $height;
	private $handle;
	
	/*
	@function __construct
	@abstract Construtor do imageResize
	@param	imgPath	string	- Caminho para a imagem
	@param	type	string	- Tipo de imagem [jpeg|gif|png]. Default: jpeg
	@version 0.1
	*/
	function __construct($imgPath, $type="jpeg") {
		// Gera erro se a imagem não existir
		if(!file_exists($imgPath)) trigger_error("Image '{$imgPath}' not found.", E_USER_ERROR);
		
		// Seta as propriedades de caminho e tipo da imagem
		$this->path = $imgPath;
		$this->type = strtolower($type);
		
		// Cria o handle da imagem
		switch($this->type) {
			case "gif":
				$this->handle = imagecreatefromgif($imgPath);
				break;
			case "png":
				$this->handle = imagecreatefrompng($imgPath);
				break;
			case "jpeg":
				$this->handle = imagecreatefromjpeg($imgPath);
				break;
			default:
				trigger_error("Invalid image type '{$this->type}'.", E_USER_ERROR);
		}
		
		
		// Seta as propriedades de largura e altura da imagem
		$dimension = $this->getDimension($this->handle);
		$this->width = $dimension['width'];
		$this->height = $dimension['height'];
	}
	
	
	/*
	@function __destruct
	@abstract Destrutor do imageResize
	@version 0.1
	*/
	function __destruct() {
		$this->destroyResource($this->handle);
	}
	
	
	/*
	@function getDimension
	@abstract Destrutor do imageResize
	@param	resource	img_resource	- Resource da imagem que será extraida a dimensão.
	@version 0.1
	*/
	function getDimension($resource) {
		$return = array("width"=>0,"height"=>0);
		$return["width"] = imagesx($resource);
		$return["height"] = imagesy($resource);
		return $return;
	}
	
	
	/*
	@function resize
	@abstract Faz o resize exato da imagem baseado em uma largura e altura
	@param	width	int	- Largura da imagem. Positivo maior que 0
	@param	height	int	- Altura da imagem. Positivo maior que 0
	@return img_resource em caso de sucesso ou false caso ocorra qualquer erro.
	@version 0.1
	*/
	function resize($width, $height) {
		// Verifica se os números são inteiros maiores que zero e dispara erro caso não seja
		$width = intval($width);
		$height = intval($height);
		if($width<=0) trigger_error("Invalid width size: '$width'", E_USER_ERROR);
		if($height<=0) trigger_error("Invalid height size: '$height'", E_USER_ERROR);		
		
		// Redimenciona a imagem
		$img = imagecreatetruecolor($width, $height);
		if(!imagecopyresampled($img, $this->handle, 0, 0, 0, 0, $width, $height, $this->width, $this->height)) {
			trigger_error("There was an error trying to resize the image.", E_USER_ERROR);
			return false;
		}
		
		return $img;
	}
	
	
	/*
	@function resizeProportionalByWidth
	@abstract Faz o resize proporcional da imagem baseado em uma largura máxima
	@param	maxWidth	int	- Largura máxima para a imagem. Inteiro positivo maior que 0.
	@return img_resource em caso de sucesso ou false caso ocorra qualquer erro.
	@version 0.1
	*/
	function resizeProportionalByWidth($maxWidth) {
		// Verifica se o número é inteiro maior que zero e dispara erro caso não seja
		$maxWidth = intval($maxWidth);
		if($maxWidth<=0) trigger_error("Invalid max width number: '$maxWidth'", E_USER_ERROR);
		
		// Calcula a nova altura
		$newWidth = $maxWidth;
		$newHeight = round(($this->height*$maxWidth)/$this->width);
		
		// Redimenciona a imagem
		$img = $this->resize($newWidth, $newHeight);
		return $img;
	}
	
	
	/*
	@function resizeProportionalByHeight
	@abstract Faz o resize proporcional da imagem baseado em uma largura máxima
	@param	maxHeight	int	- Altura máxima para a imagem. Inteiro positivo maior que 0.
	@return img_resource em caso de sucesso ou false caso ocorra qualquer erro.
	@version 0.1
	*/
	function resizeProportionalByHeight($maxHeight) {
		// Verifica se o número é inteiro maior que zero e dispara erro caso não seja
		$maxHeight = intval($maxHeight);
		if($maxHeight<=0) trigger_error("Invalid max height number: '$maxHeight'", E_USER_ERROR);
		
		// Calcula a nova altura
		$newHeight = $maxHeight;
		$newWidth = round(($this->width*$maxHeight)/$this->height);
		
		// Redimenciona a imagem
		$img = $this->resize($newWidth, $newHeight);
		return $img;
	}
	
	
	/*
	@function resizeProportionalByWidthAndHeight
	@abstract Faz o resize proporcional da imagem baseado em uma largura máxima
	@param	maxWidth	int	- Largura máxima para a imagem. Inteiro positivo maior que 0.
	@param	maxHeight	int	- Altura máxima para a imagem. Inteiro positivo maior que 0.
	@param	toCrop		bool- [true|*false]. Determina se deve haver sobra na largura ou altura.
	@return img_resource em caso de sucesso ou false caso ocorra qualquer erro.
	@version 0.1
	*/
	function resizeProportionalByWidthAndHeight($maxWidth, $maxHeight, $toCrop=false) {
		// Verifica se o número é inteiro maior que zero e dispara erro caso não seja
		$maxHeight = intval($maxHeight);
		$maxWidth = intval($maxWidth);
		if($maxHeight<=0) trigger_error("Invalid max height number: '$maxHeight'", E_USER_ERROR);
		if($maxWidth<=0) trigger_error("Invalid max width number: '$maxWidth'", E_USER_ERROR);
		
		
		// Calcula a nova altura
		$newHeight = $maxHeight;
		$newWidth = round(($this->width*$maxHeight)/$this->height);

		if( ($newWidth>$maxWidth && !$toCrop) || ($newWidth<$maxWidth && $toCrop) ) {
			// Calcula a nova altura
			$newWidth = $maxWidth;
			$newHeight = round(($this->height*$maxWidth)/$this->width);
		}
		
		// Redimenciona a imagem
		$img = $this->resize($newWidth, $newHeight);
		return $img;
	}
	
	
	/*
	@function cropProportional
	@abstract Faz o crop da imagem dando resize proporcional
	@param	width	int	- Largura para a imagem. Inteiro positivo maior que 0.
	@param	height	int	- Altura para a imagem. Inteiro positivo maior que 0.
	@return img_resource em caso de sucesso ou false caso ocorra qualquer erro.
	@version 0.1
	*/
	function cropProportional($width, $height) {
		// Verifica se os números são inteiros maiores que zero e dispara erro caso não seja
		$width = intval($width);
		$height = intval($height);
		if($width<=0) trigger_error("Invalid width size: '$width'", E_USER_ERROR);
		if($height<=0) trigger_error("Invalid height size: '$height'", E_USER_ERROR);
		
		$imgs = $this->resizeProportionalByWidthAndHeight($width, $height, true);
		$imgs_d = $this->getDimension($imgs);
		$imgd = imagecreatetruecolor($width, $height);
		if(!imagecopy($imgd, $imgs, 0, 0, (($imgs_d['width']-$width)/2), (($imgs_d['height']-$height)/2), $imgs_d['width'], $imgs_d['height'])) {
			trigger_error("There was an error trying to crop the image.", E_USER_ERROR);
			return false;
		}
		
		$this->destroyResource($imgs);
		return $imgd;
	}
	
	
	/*
	@function placePng
	@abstract Aloca um PNG em cima de uma imagem
	@description Cada alinhamento utiliza algunas (ou nenhuma) variavel de top, right, bottom, left:
	- TL (Top & Left): $top, $left;
	- T (Top & Center): $top;
	- TR (Top & Right): $top, $right;
	- CL (Center & Left): $left;
	- C (Center): Nenhuma variavel;
	- CR (Center & Right): $right;
	- BL (Bottom & Left): $bottom, $left;
	- B (Bottom & Center): $bottom;
	- BR (Bottom & Right): $bottom, $right;
	@param	resource	img_resource	- Resource da imagem de fundo.
	@param	pathPng		string			- Caminho do arquivo png
	@param	align		string			- TL|T|TR|CL|C|CR|BL|B|BR*
	@param	top			int				- pixels de margem do topo
	@param	right		int				- pixels de margem da direita
	@param	bottom		int				- pixels de margem do rodape
	@param	left		int				- pixels de margem da esquerda
	@version 0.1
	*/
	function placePng($resource, $pathPng, $align="BR", $top=0, $right=0, $bottom=0, $left=0) {
		// Verifica se a imagem PNG existe
		if(!file_exists($pathPng)) trigger_error("PNG image '{$pathPng}' not found.", E_USER_ERROR);
		
		// Cria o resource do png
		$ipng = imagecreatefrompng($pathPng);
		$ipng_d = $this->getDimension($ipng);
		
		// Permite alpha no resource
		//@imagealphablending($resource);
		
		// Calcula as posições da imagem
		$place_x = 0;
		$place_y = 0;
		$resource_d = $this->getDimension($resource);
		switch(strtoupper($align)) {
			case "TL":
				$place_x = $left;
				$place_y = $top;
				break;
			case "T":
			case "TC":
				$place_x = ($resource_d['width']-$ipng_d['width'])/2;
				$place_y = $top;
				break;
			case "TR":
				$place_x = ($resource_d['width']-$ipng_d['width'])-$right;
				$place_y = $top;
				break;
			case "CL":
				$place_x = $left;
				$place_y = ($resource_d['height']-$ipng_d['height'])/2;
				break;
			case "C":
			case "CC":
				$place_x = ($resource_d['width']-$ipng_d['width'])/2;
				$place_y = ($resource_d['height']-$ipng_d['height'])/2;
				break;
			case "CR":
				$place_x = ($resource_d['width']-$ipng_d['width'])-$right;
				$place_y = ($resource_d['height']-$ipng_d['height'])/2;
				break;
			case "BL":
				$place_x = $left;
				$place_y = ($resource_d['height']-$ipng_d['height'])-$bottom;
				break;
			case "B":
			case "BC":
				$place_x = ($resource_d['width']-$ipng_d['width'])/2;
				$place_y = ($resource_d['height']-$ipng_d['height'])-$bottom;
				break;
			case "BR":
				$place_x = ($resource_d['width']-$ipng_d['width'])-$right;
				$place_y = ($resource_d['height']-$ipng_d['height'])-$bottom;
				break;
		}
		
		// Junta as imagens
		if(!imagecopy($resource, $ipng, $place_x, $place_y, 0, 0, $ipng_d['width'], $ipng_d['height'])) {
			trigger_error("There was an error trying to join the images.", E_USER_ERROR);
			return false;
		}
		
		// Destroi o png
		$this->destroyResource($ipng);
		
		// Retorna o resultado
		return $resource;
	}
	
	
	/*
	@function saveImage
	@abstract Salva a imagem em arquivo
	@param	resource	img_resource	- Resource da imagem que será salva.
	@param	path		string			- Caminho completo para o arquivo.
	@param	type		string			- Tipo que será salvo [jpeg|gif|png]. Se vazio, usa o mesmo do original.
	@param	quality		int				- Qualidade da imagem (válido apenas para typo jpeg). [0-100]
	@return true em caso de sucesso.
	@version 0.1
	*/
	function saveImage($resource, $path, $type="", $quality="100") {
		if(empty($type)) $type = $this->type;
		switch($type) {
			case "jpeg":
				if(!imagejpeg($resource, $path, $quality)) trigger_error("There was an error trying to save the jpeg image.", E_USER_ERROR);
				break;
			case "gif":
				if(!imagegif($resource, $path)) trigger_error("There was an error trying to save the gif image.", E_USER_ERROR);
				break;
			case "png":
				if(!imagepng($resource, $path)) trigger_error("There was an error trying to save the png image.", E_USER_ERROR);
				break;
			default:
				trigger_error("Invalid image type '{$type}'.", E_USER_ERROR);
		}
		
		return true;
	}
	
	
	/*
	@function destroyResource
	@abstract Tira o resource da memoria do sistema
	@param	resource	img_resource	- Resource da imagem que será destruida.
	@version 0.1
	*/
	function destroyResource(&$resource) {
		if(is_resource($resource)) {
			imagedestroy($resource);
		} else {
			trigger_error("Trying to destroy an invalid resource.", E_USER_WARNING);
		}
	}
}
?>