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
	protected static function mapping(){
		return array(
			'openid_ax_value_firstname' => 'firstname',
			'openid_ax_value_lastname'  => 'lastname',
			'openid_ax_value_email'     => 'email',
			'openid_ax_value_language'  => 'language',
			'openid_ax_value_country'   => 'country',
			'openid_ax_value_gender'    => 'gender',
			'openid_ax_value_fullname'  => 'fullname',
			'openid_ax_value_nickname'  => 'nickname'
		);
	}
	public static function authenticate($request){
		$params = $request;
		$params['openid_mode'] = 'check_authentication';
		$url = $request['openid_op_endpoint'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url."?".http_build_query($params));
		curl_setopt($curl, CURLOPT_VERBOSE, 1); 
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		/*
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		*/
		$res_buff = curl_exec($curl);
		curl_close($curl);
		list($headers, $body) = explode("\r\n\r\n", $res_buff, 2);
		while(strpos($headers,"100 Continue")!==false){
			list($headers, $body) = explode("\r\n\r\n", $body , 2);
		}
		$headers = self::disect($headers);
		$body = self::disect($body);
		if(!isset($headers['HTTP/1.1 200 OK']))
			return -2;
		if(!isset($body['is_valid']))
			return 0;
		return $body['is_valid'];
	}
}
?>
