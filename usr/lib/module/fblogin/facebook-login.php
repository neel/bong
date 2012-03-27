<?php
class FacebookLoginClient{
	private $_client_id;
	private $_redirect_uri;

	public function __construct($client_id, $redirect_uri){
		$this->_client_id    = $client_id;
		$this->_redirect_uri = $redirect_uri;
	}

	public function redirect_uri(){
		return "https://graph.facebook.com/oauth/authorize?type=web_server&client_id={$this->_client_id}&redirect_uri={$this->_redirect_uri}";
	}

	public function access_token($code, $client_secret){
		$url = "https://graph.facebook.com/oauth/access_token?client_id={$this->_client_id}&redirect_uri={$this->_redirect_uri}&client_secret={$client_secret}&code={$code}";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$buff_res = curl_exec($curl);
		$res_parts = explode('&', $buff_res);
		$access_token_parts = explode('=', $res_parts[0]);
		return $access_token_parts[1];
	}
}
?>