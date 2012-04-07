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
	protected static function mapping(){
		return array(
			'openid_ext1_value_firstname' => 'firstname',
			'openid_ext1_value_lastname'  => 'lastname',
			'openid_ext1_value_email'     => 'email',
			'openid_ext1_value_language'  => 'language',
			'openid_ext1_value_country'   => 'country',
			'openid_ext1_value_gender'    => 'gender',
			'openid_ext1_value_fullname'  => 'fullname',
			'openid_ext1_value_nickname'  => 'nickname'
		);
	}
}
?>
