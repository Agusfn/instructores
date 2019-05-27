<?php

namespace App\Lib;

class MercadoPago
{


	const PUBLIC_KEY_SANDBOX = "TEST-4dc699b4-f988-46cf-945d-48c4ec565ef9";
	const ACCESS_TOKEN_SANDBOX = "TEST-6705395561099232-052309-1580f7548ef0b8472cd109ec6e86c361-92382327";

	// These production keys must be enabled first
	const PUBLIC_KEY_PRODUCTION = "TEST-4dc699b4-f988-46cf-945d-48c4ec565ef9";
	const ACCESS_TOKEN_PRODUCTION = "TEST-6705395561099232-052309-1580f7548ef0b8472cd109ec6e86c361-92382327";



	public static function getPublicKey() 
	{
		if(config("app.env") == "production")
			return self::PUBLIC_KEY_PRODUCTION;
		else
			return self::PUBLIC_KEY_SANDBOX;
	}


}