<?php namespace core;

class tools{
    
    
    
    
    public static function getJsonPost()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
	
    
    public static function tokenGen(int $n=5)
    {
		// 0-1-2-5-6  --  O-o-i-I-L-l-s-S-G-g-z-Z  Omitidos por similitud visual
		$c = "ABCDEFHJKMNPQRTUVWXY34789abcdefhjkmnpqrtuvwxy34789";
		$s=null;
		for($i=0;$i<$n;$i++){$s.= substr($c,rand(0,strlen($c)),1);}
		return $s;
	}


	public static function sendMail($address,$subject,$content)
    {

		$css=file_get_contents(asset('css/normalize.css'));
		$css.=file_get_contents(asset('css/structures.css'));
		$css.=file_get_contents(asset('css/style.css'));

		$content = "<html>
				<head>
					<style>".$css."</style>
				</head>
				<body>
	
					<h2>".GENERAL['sitename']."</h2>
					<div class='container padd2'>

						<div class='container padd st-warning'>¡ Aviso importante !</div>
						<br>
						<div class='centered padd2 b'>".$content."</div>
					</div>
				</body>
				</html>";

		// Para enviar un correo HTML, debe establecerse la cabecera Content-type
		$mail_headers  = "MIME-Version: 1.0\r\n";
		$mail_headers  .= "Content-type: text/html; charset=utf-8\r\n";

		// Cabeceras adicionales
		//$mail_headers .= 'To: receiver@example.com'. "\r\n";
		$mail_headers .= "From: ".GENERAL['sitename']." <".GENERAL['supportEmail'].">\r\n";
		//$mail_headers .= 'Cc: example@example.com' . "\r\n";
		//$mail_headers .= 'Bcc: example-bis@example.com' . "\r\n";


			
		return mail($address, $subject, $content, $mail_headers);
	}





	public static function realIp()
    {

		if (isset($_SERVER["HTTP_CLIENT_IP"]))
		{
			return $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
		{
			return $_SERVER["HTTP_X_FORWARDED"];
		}
		elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
		{
			return $_SERVER["HTTP_FORWARDED_FOR"];
		}
		elseif (isset($_SERVER["HTTP_FORWARDED"]))
		{
			return $_SERVER["HTTP_FORWARDED"];
		}
		elseif (isset($_SERVER["HTTP_VIA"]))
		{
			return $_SERVER["HTTP_VIA"];
		}		
		else
		{
			return $_SERVER["REMOTE_ADDR"];
		}

	}

	public static function publicIp()
    {
		return $_SERVER["REMOTE_ADDR"];
	}


}