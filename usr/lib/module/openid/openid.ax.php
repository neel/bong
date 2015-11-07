<?php
class OpenIdAXClient extends OpenIDClient{
	public function setup($assoc_handle, $params_additional = array()){
		$params = array();
		$required = array();
		foreach($this->ax() as $ax){
			$field = OpenIdAXConstants::Map($ax);
			$params['openid.ax.type.'.$field] = $ax;
			$required[] = $field;
		}
		$params['openid.ax.required'] = implode(',', $required);
		return parent::setup($assoc_handle, $this->merge_params($params, $params_additional));
	}
	public function ax(){}
	public static function extract($request){
		$res = array();
		$dict = OpenIdAXConstants::ax_dict();
		foreach($request as $key => $value){
			if(in_array($value, $dict)){
				$r = preg_match('~([\w\_\.]+)type([\w\_\.]+)~', $key, $m);
				if($r && count($m) >= 1){
					$value_attr = $m[1].'value'.@$m[2];
					if(strpos($value_attr, 'ax') || strpos($value_attr, 'ext1')){
						if(isset($request[$value_attr])){
							$value_value = $request[$value_attr];
							$res[OpenIdAXConstants::Map($value)] = $value_value;
						}
					}
				}
			}
		}
		return $res;
	}
}
?>
