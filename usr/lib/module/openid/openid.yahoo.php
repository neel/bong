<?php
class YahooOpenIdClient extends OpenIdAXClient{
	public function __construct($callback){
		parent::__construct('https://open.login.yahooapis.com/openid/op/auth', $callback);
	}
	public function ax(){
		return array(
			OpenIdConstants::AX_FIRSTNAME,
			OpenIdConstants::AX_LASTNAME,
			OpenIdConstants::AX_FULLNAME,
			OpenIdConstants::AX_NICKNAME,
			OpenIdConstants::AX_EMAIL,
			OpenIdConstants::AX_COUNTRY,
			OpenIdConstants::AX_GENDER,
			OpenIdConstants::AX_LANGUAGE
		);
	}
}
?>
