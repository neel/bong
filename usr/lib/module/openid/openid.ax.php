<?php
class OpenIdAXClient extends OpenIDClient{
	public function setup($assoc_handle, $params_additional = array()){
		$params = array();		
		$params['openid.ax.required']       = 'firstname,lastname,email,language,country';
		$params['openid.ax.type.firstname'] = OpenIdConstants::AX_FIRSTNAME;
		$params['openid.ax.type.lastname']  = OpenIdConstants::AX_LASTNAME;
		$params['openid.ax.type.email']     = OpenIdConstants::AX_EMAIL;
		$params['openid.ax.type.language']  = OpenIdConstants::AX_LANGUAGE;
		$params['openid.ax.type.country']   = OpenIdConstants::AX_COUNTRY;
		$params['openid.ax.type.gender']    = OpenIdConstants::AX_GENDER;
		
		$this->merge_params($params, $params_additional);
		return parent::setup($assoc_handle, $params);
	}
	public static function extract($request){
		$res = array();
		$mappings = static::mapping();
		foreach($mappings as $key => $value){
			if(isset($request[$key])){
				$res[$value] = $request[$key];
			}
		}
		return $res;
	}
}
?>
