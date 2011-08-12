<?php namespace kis;
/**
 * Kis.Minify
 * @since 11/08/2011
 * @version 1.0
 * @author André Gumieri - andregumieri@gmail.com
 */


Class Minify extends Kis {
	public $jsPath = "assets/js";
	public $cssPath = "assets/css";
	public $jsURL = "assets/js";
	public $cssURL = "assets/css";
	
	private $cachePath = "";
	private $cacheURL = "";
	private $cache = true;
	private $js = array();
	
	function __construct() {
		$this->jsPath = $this->getThemePath() . "/" . $this->jsPath;
		$this->cssPath = $this->getThemePath() . "/" . $this->cssPath;
		$this->jsURL = $this->getThemeURL() . "/" . $this->jsURL;
		$this->cssURL = $this->getThemeURL() . "/" . $this->cssURL;
		
		$this->cachePath = $this->getContentPath() . "/kis/cache/minify";
		$this->cacheURL = $this->getContentURL() . "/kis/cache/minify";
		
		
		if( !file_exists($this->cachePath) ) {
			// Se a pasta wp-content/kis/cache/minify não existir, cria
			if( !mkdir($this->cachePath, 0777, true) ) {
				$this->cache = false;
				trigger_error("kis\Minify: Unable to create the cache folder.",  E_WARNING);
			}
		}
		$this->cache = false;
	}
	
	function addJs($file) {
		$this->js[] = $file;
	}
	
	function getJsTag() {
		if($this->cache) {
			$minify = "/* File unified by kis.minify */";
			foreach($this->js as $js) {
				$jsFile = $this->jsPath . "/" . $js;
				$minify .= "\n\n/* {$js} */\n";
				if(file_exists($jsFile)) {
					$minify .= file_get_contents($jsFile);
				} else {
					$minify .= "/* Error: File doesn`t exist. */\n";
				}
			}
			$cacheFileName = md5($minify) . ".js";
			file_put_contents($this->cachePath . "/" . $cacheFileName, $minify);
			return "<script type=\"text/javascript\" src=\"" . $this->cacheURL . "/" . $cacheFileName . "\"></script>";
		} else {
			// no cache
			$ret = "";
			foreach($this->js as $js) {
				$ret .= "<script type=\"text/javascript\" src=\"" . $this->jsURL . "/" . $js . "\"></script>";
			}
			return $ret;
		}
	}
}


?>