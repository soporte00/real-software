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
		$content = "<html>
				<head>
				  <title>Free-framework AMS</title>
				</head>
				<body>".$content."
				</body>
				</html>";

		// Para enviar un correo HTML, debe establecerse la cabecera Content-type
		$mail_headers  = 'MIME-Version: 1.0' . "\r\n";
		$mail_headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		// Cabeceras adicionales
		//$mail_headers .= 'To: receiver@example.com'. "\r\n";
		$mail_headers .= 'From: Free-Framework <soporte@freeframework.net>' . "\r\n";
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