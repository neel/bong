<?php
class AOLOpenIdClient extends OpenIdAXClient{
	public function __construct($callback){
		parent::__construct('https://api.screenname.aol.com/auth/openidServer', $callback);
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
