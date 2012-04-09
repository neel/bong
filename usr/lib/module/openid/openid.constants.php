<?php
class OpenIdConstants{
	const NS                     = 'http://specs.openid.net/auth/2.0';
	const CLAIMED_ID             = 'http://specs.openid.net/auth/2.0/identifier_select';
	const IDENTITY               = 'http://specs.openid.net/auth/2.0/identifier_select';
	const NS_AX                  = 'http://openid.net/srv/ax/1.0';
	const NS_PAPE                = 'http://specs.openid.net/extensions/pape/1.0';
	const MODE_ASSOCIATE         = 'associate';
	const MODE_CHECKID_SETUP     = 'checkid_setup';
	const MODE_CHECKID_IMMEDIATE = 'checkid_immediate';
	const AX_MODE_FETCH          = 'fetch_request';
	const AX_FIRSTNAME           = 'http://axschema.org/namePerson/first';
	const AX_LASTNAME            = 'http://axschema.org/namePerson/last';
	const AX_EMAIL               = 'http://axschema.org/contact/email';
	const AX_COUNTRY             = 'http://axschema.org/contact/country/home';
	const AX_LANGUAGE            = 'http://axschema.org/pref/language';
	const AX_GENDER              = 'http://axschema.org/person/gender';
	const AX_NICKNAME            = 'http://axschema.org/namePerson/friendly';
	const AX_FULLNAME 			 = 'http://axschema.org/namePerson';
}
class OpenIdAXConstants{
	public static function ax_dict(){
		return array(
			OpenIdConstants::AX_FIRSTNAME ,
			OpenIdConstants::AX_LASTNAME  ,
			OpenIdConstants::AX_EMAIL     ,
			OpenIdConstants::AX_NICKNAME  ,
			OpenIdConstants::AX_FULLNAME  ,
			OpenIdConstants::AX_GENDER    ,
			OpenIdConstants::AX_LANGUAGE  ,
			OpenIdConstants::AX_COUNTRY   
		);
	}
	public static function Map($name){
		static $dict = array(
			OpenIdConstants::AX_FIRSTNAME => 'firstname',
			OpenIdConstants::AX_LASTNAME  => 'lastname',
			OpenIdConstants::AX_EMAIL     => 'email',
			OpenIdConstants::AX_NICKNAME  => 'nickname',
			OpenIdConstants::AX_FULLNAME  => 'fullname',
			OpenIdConstants::AX_GENDER    => 'gender',
			OpenIdConstants::AX_LANGUAGE  => 'language',
			OpenIdConstants::AX_COUNTRY   => 'country'
		);
		if(array_key_exists($name, $dict)){
			return $dict[$name];
		}
		return null;
	}
	public static function MapFriendly($name){
		static $dict = array(
			OpenIdConstants::AX_FIRSTNAME => 'First Name',
			OpenIdConstants::AX_LASTNAME  => 'Last Name',
			OpenIdConstants::AX_EMAIL     => 'Email Address',
			OpenIdConstants::AX_NICKNAME  => 'Nick Name',
			OpenIdConstants::AX_FULLNAME  => 'Full Name',
			OpenIdConstants::AX_GENDER    => 'Gender',
			OpenIdConstants::AX_LANGUAGE  => 'Language',
			OpenIdConstants::AX_COUNTRY   => 'Country'
		);
		if(array_key_exists($name, $dict)){
			return $dict[$name];
		}
		return null;
	}
	public static function MapIndex($name){
		static $dict = array(
			'firstname' => 'First Name',
			'lastname'  => 'Last Name',
			'email'     => 'Email Address',
			'nickname'  => 'Nick Name',
			'fullname'  => 'Full Name',
			'gender'    => 'Gender',
			'language'  => 'Language',
			'country'   => 'Country'
		);
		if(array_key_exists($name, $dict)){
			return $dict[$name];
		}
		return null;
	}
}

?>