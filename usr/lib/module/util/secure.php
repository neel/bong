<?php
class Secure{
	private static function _basePath(){
		return rtrim(Path::instance()->currentProject('keys'), "/");
	}
	/**
	 * Generates Private/public key Pair for the uri given
	 * keys get stored in /bong/projects/PROJECT_NAME/var/keys directory
	 * returns true if generation of both keys are successful, returns false otherwise
	 */
	public static function genKeys($uri, $ts){
		$_private_path    = self::_basePath().'/'.md5($uri.$ts).'.pri';
		$_public_path     = self::_basePath().'/'.md5($uri.$ts).'.pub';
		$_private_command = "openssl ecparam -name secp112r1 -genkey -out {$_private_path}";
		$_public_command  = "openssl ec -conv_form compressed -pubout -in {$_private_path} -out {$_public_path}";
		$_pri_handle = popen($_private_command, 'r');
		if($_pri_handle){
			$buff = fread($_pri_handle, 16);
			$_pub_handle = popen($_public_command, 'r');
			if($_pub_handle){
				$buff = fread($_pub_handle, 16);
			}else{
				return false;
			}
			pclose($_pub_handle);
		}else{
			return false;
		}
		pclose($_pri_handle);
		return true;
	}
	/**
	 * Checks whether both keys exists. returns one if both or one of them is doesn't exist.
	 */
	public static function keyExists($uri, $ts){
		return file_exists(self::_basePath().'/'.md5($uri.$ts).'.pri') && file_exists(self::_basePath().'/'.md5($uri.$ts).'.pub');
	}
	public static function privateExists($uri, $ts){
		return file_exists(self::_basePath().'/'.md5($uri.$ts).'.pri');
	}
	public static function publicExists($uri, $ts){
		return file_exists(self::_basePath().'/'.md5($uri.$ts).'.pub');
	}
	public static function privateKey($uri, $ts){
		if(self::privateExists($uri, $ts)){
			return openssl_pkey_get_private(array('file://'.self::_basePath().'/'.md5($uri.$ts).'.pri', ''));
		}		
		return false;
	}
	public static function publicKey($uri, $ts){
		if(self::publicExists($uri, $ts)){
			return openssl_pkey_get_public('file://'.self::_basePath().'/'.md5($uri.$ts).'.pub');
		}
		return false;
	}
	public static function sign($uri, $ts, $data){
		$pkid = self::privateKey($uri, $ts);
		if(!$pkid)
			return false;
		$signature = '';
		$res = @openssl_sign($data, $signature, $pkid, OPENSSL_ALGO_SHA1);
		openssl_free_key($pkid);
		return $signature;
	}
	public static function verify($uri, $ts, $signature){
		$pkid = self::publicKey($uri, $ts);
		if(!$pkid)
			return false;
		$res = openssl_verify($data, $signature, $pkid, OPENSSL_ALGO_SHA1);
		openssl_free_key($pkid);
		return $res;
	}
	public static function removeKeys($uri, $ts){
		$_private_path    = self::_basePath().'/'.md5($uri.$ts).'.pri';
		$_public_path     = self::_basePath().'/'.md5($uri.$ts).'.pub';
		unlink($_private_path);
		unlink($_public_path);
	}
}
?>
