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
}
?>