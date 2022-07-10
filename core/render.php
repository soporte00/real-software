<?php namespace core;

class render
{

	private static $i;
	private $mssg=null,$mssgType=null;



/**
 * Render template
 */
	public static function html($meta=[]): void
	{
		/**
		 * default metatags
		 */
		$tags = [
			"lang" => GENERAL['sitelang'],
			"title" => GENERAL['sitename'],
			"type" => 'website', // website - article
			"description" => GENERAL['sitedescription'], // 140 -160 max characters
			"keywords" => GENERAL['sitekeywords'],
			"url" => URL,
		];

		if ($meta) foreach ($meta as $key => $value) $tags[$key] = $value;

		if(DEBUG) $tags['title'] = '¡¡¡ DEBUG !!!';
	?>
<!doctype HTML>
<html lang='<?= $tags['lang'] ?>'>
<head>
<title><?= $tags['title'] ?></title>
<meta charset="UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name='robots' content='Index, follow' />
<meta name="url" content="<?= URL ?>" />
<meta name="description" content="<?= $tags['description'] ?>" />
<meta name="keywords" content="<?= $tags['keywords'] ?>" />
<meta name="twitter:card" content="summary_large_image" />
<meta property="og:locale" content="<?= $tags['lang'] ?>" />
<meta property="og:type" content="<?= $tags['type'] ?>" />
<meta property="og:title" content="<?= $tags['title'] ?>" />
<meta property="og:description" content="<?= $tags['description'] ?>" />
<meta property="og:url" content="<?= $tags['url'] ?>" />
<meta property="og:site_name" content="<?= GENERAL['sitename'] ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="canonical" href="<?= URL ?>" /><?php
render::default('header.php');
	}

	public static function body():void
	{
?></head>
<body><?php
render::default('bodystart.php');
	}

	public static function end():void
	{
render::default('bodyend.php');
?></body>
</html><?php
	}




	public static function default(string $file){
		require_once dirname(__FILE__,2) .'/src/defaultHTML/'. $file;
	}


	/**
	 * Render view file
	 */
	public static function view(string $url,$d=[]): void
	{
		extract($d);
		require_once dirname(__FILE__, 2) . "/src/views/" . $url;
	}

	/**
	 * Json out
	 */
	public static function json(){
		if(!self::$i instanceof self){
			self::$i = new self;
		}

		return self::$i;
	}

	public function message($mssg,$type=null){
		$this->mssg=$mssg;
		$this->mssgType=$type;
		return $this;
	}

	/**
	 * Out response
	 */
	public function out($response=false)
	{	

		$r = [
			"response" => $response,
			"message" => [$this->mssg,$this->mssgType]
		];

		echo json_encode($r);

		die();
	}

}