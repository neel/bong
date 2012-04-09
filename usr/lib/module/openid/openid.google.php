<?php
class GoogleOpenIdClient extends OpenIdAXClient{
	public function __construct($callback){
		parent::__construct('https://www.google.com/accounts/o8/ud', $callback);
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
