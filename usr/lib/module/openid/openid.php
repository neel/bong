<?php
class OpenIdClient{
	const CONSUMER_URL = 'http://voxiternal.com';
	private $_url;
	private $_return;

	public function __construct($url, $return_url){
		$this->_url = $url;
		$this->_return = $return_url;
	}
	private function disect($response){
		$body = explode("\n", trim($response));
		foreach($body as $i => $line){
			@list($key, $val) = explode(':', $line, 2);
			$body[trim($key)] = trim($val);
			unset($body[$i]);
		}
		return $body;
	}
	protected function merge_params($params, $additional = array()){
		foreach($additional as $key => $value)
			$params[$key] = $value;
		return $params;
	}
	public function associate($params_additional = array()){
		$params = array();
		$params['openid.realm']          = OpenIdClient::CONSUMER_URL;
		$params['openid.ns']             = OpenIDConstants::NS;
		$params['openid.mode']           = OpenIDConstants::MODE_ASSOCIATE;
		$params['openid.assoc_type']     = 'HMAC-SHA1';
		$params['openid.session_type']   = 'no-encryption';

		$params = $this->merge_params($params, $params_additional);

		$url = $this->_url.'?'.http_build_query($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$res_buff = curl_exec($curl);
		list($headers, $body) = explode("\r\n\r\n", $res_buff, 2);
		curl_close($curl);
		$body = $this->disect($body);
		if(isset($body['assoc_handle'])){
			return $body['assoc_handle'];
		}
	}
	public function setup($assoc_handle, $params_additional = array()){
		$params = array();
		$params['openid.realm']             = OpenIdClient::CONSUMER_URL;
		$params['openid.ns']                = OpenIDConstants::NS;
		$params['openid.claimed_id']        = OpenIDConstants::CLAIMED_ID;
		$params['openid.identity']          = OpenIDConstants::IDENTITY;

		$params['openid.mode']              = OpenIDConstants::MODE_CHECKID_SETUP;
		$params['openid.return_to']         = $this->_return;

		$params['openid.ns.ax']             = OpenIDConstants::NS_AX;
		$params['openid.ax.mode']           = OpenIDConstants::AX_MODE_FETCH;

		$params = $this->merge_params($params, $params_additional);

		$url = $this->_url.'?'.http_build_query($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_VERBOSE, 1); 
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$res_buff = curl_exec($curl);
		curl_close($curl);

		list($headers, $body) = explode("\r\n\r\n", $res_buff, 2);
		$headers = $this->disect($headers);
		return trim($headers['Location']);
	}
	public static function authenticate($request){
		$params = $request;
		$params['openid_mode'] = 'check_authentication';
		//$params['openid.mode'] = 'check_authentication';
		$url = $request['openid_op_endpoint'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_VERBOSE, 1); 
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		$res_buff = curl_exec($curl);
		curl_close($curl);
		echo $res_buff;
	}
}
?>