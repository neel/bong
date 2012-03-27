<?php
class AOLOpenIdClient extends OpenIdAXClient{
	public function __construct($callback){
		parent::__construct('https://api.screenname.aol.com/auth/openidServer', $callback);
	}
	public function setup($assoc_handle, $params_additional = array()){
		$params = array();		
		/*$params['openid.ns.pape']           = OpenIdConstants::NS_PAPE;
		$params['openid.pape.max_auth_age'] = 0;*/
		
		$this->merge_params($params, $params_additional);
		return parent::setup($assoc_handle, $params);
	}
}
?>
