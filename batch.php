<?php
error_reporting(255);
ini_set('display_errors','On');
date_default_timezone_set('Asia/Calcutta');
header('Content-Type: text/plain');
header('X-Platform: Bong');
//{ Exceptions
require 'usr/lib/include/bongexceptionparam.php';
require 'usr/lib/include/bongexception.php';
require 'usr/lib/include/exceptions/InvalidExceptionException.php';
require 'usr/lib/include/exceptions/MalformedUrlException.php';
require 'usr/lib/include/exceptions/FileSystemException.php';
require 'usr/lib/include/exceptions/FileException.php';
require 'usr/lib/include/exceptions/FileNotFoundException.php';
require 'usr/lib/include/exceptions/FileNotReadableException.php';
require 'usr/lib/include/exceptions/FileNotWritableException.php';
require 'usr/lib/include/exceptions/DirectoryException.php';
require 'usr/lib/include/exceptions/DirectoryNotFoundException.php';
require 'usr/lib/include/exceptions/ModuleException.php';
require 'usr/lib/include/exceptions/ModuleNotFoundException.php';
require 'usr/lib/include/exceptions/ModuleDependencyNotSatisfiableException.php';
require 'usr/lib/include/exceptions/ModuleIncludingNonExistingFileException.php';
require 'usr/lib/include/exceptions/ModuleIncludingNonReadableFileException.php';
require 'usr/lib/include/exceptions/ProjectException.php';
require 'usr/lib/include/exceptions/ProjectNotFoundException.php';
require 'usr/lib/include/exceptions/ProjectDirException.php';
require 'usr/lib/include/exceptions/ProjectDirNotFoundException.php';
require 'usr/lib/include/exceptions/ProjectDirNotReadableException.php';
require 'usr/lib/include/exceptions/ControllerNotFoundException.php';
require 'usr/lib/include/exceptions/ControllerNotReadableException.php';
require 'usr/lib/include/exceptions/MethodNotFoundException.php';
require 'usr/lib/include/exceptions/ArgumentNotGivenException.php';
//}

//{ Utility data Structures
require 'usr/lib/include/singleton.php';
require 'usr/lib/include/stdmap.php';
require 'usr/lib/include/mempool.php';
//}

//{ Configuration Related (fstab, path, conf)
require 'usr/lib/include/xpathconfig.php';//interface
require 'usr/lib/include/configuratonadapter.php';
require 'usr/lib/include/conf.php';
require 'usr/lib/include/fstab.php';
require 'usr/lib/include/path.php';
require 'usr/lib/include/moduleconf.php';
require 'usr/lib/include/modulemeta.php';
//}

//{ Factories
require 'usr/lib/include/factory.php';
require 'usr/lib/include/routers/routerfactory.php';
require 'usr/lib/include/engines/enginefactory.php';
//}

require 'usr/lib/include/decider.php';//interface

//{ Routers
require 'usr/lib/include/routers/abstractcontentrouter.php';
require 'usr/lib/include/routers/ResourceRouter.php';
require 'usr/lib/include/routers/mvcrouter.php';
require 'usr/lib/include/routers/ServiceRouter.php';
require 'usr/lib/include/routers/AppServiceRouter.php';
require 'usr/lib/include/routers/SpiritServiceRouter.php';
require 'usr/lib/include/routers/staticcontentrouter.php';
require 'usr/lib/include/routers/fsmrouter.php';
//}

require 'usr/lib/include/Runnable.php';//Interface

//{ HTTP Headers
require 'usr/lib/include/HTTPHeaderTrait.php';
require 'usr/lib/include/HTTPHeader.php';
require 'usr/lib/include/HTTPHeaders.php';
//}

//{ Engines
require 'usr/lib/include/engines/AbstractContentEngine.php';
require 'usr/lib/include/engines/AbstractMicroEngine.php';
require 'usr/lib/include/engines/ContentEngine.php';
require 'usr/lib/include/engines/MVCEngine.php';
require 'usr/lib/include/engines/ResourceEngine.php';
require 'usr/lib/include/engines/FSMEngine.php';
//}

//{ Helpers and Utils
require 'usr/lib/include/common.php';
require 'usr/lib/include/resourcehelper.php';
require 'usr/lib/include/susax.php';
require 'usr/lib/include/bongparser.php';
require 'usr/lib/include/UUID.php';
//}

require 'usr/lib/include/urlanalyzer.php';
require 'usr/lib/include/runtime.php';

require 'usr/lib/include/AbstractDataTray.php';
require 'usr/lib/include/backend.php';

/*{ Unpack requests*/
/**/
if(!isset($_POST) || !isset($_GET['n']) || !isset($_GET['hash']) || !isset($_POST['payload']))
	return 0;

$n        = $_GET['n'];
$checksum = $_GET['hash'];
$payload  = $_POST['payload'];
//echo $payload.' '.md5($payload).' '.$checksum;
/*
if(md5($payload) != $checksum){
	return 0;
}
*/
$batch = json_decode($payload);
//$batch is an array of request parameters
if(count($batch->loads) != $n){
	return 0;
}
$installation_base = trim(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME), "/");
$response_buff = array();
foreach($batch->loads as $request){
	$urlPathParts = pathinfo($_SERVER['SCRIPT_NAME']);
	MemPool::instance()->set("bong.url.base", rtrim($urlPathParts['dirname'], "/"));

	/**
	 * Set the current directoty as Bong Root Directoty.
	 * Furtur Directory Manipulation will be relative to this path.
	 * e.g. will be / concated with teh root and then executed as absolute.
	 */
	MemPool::instance()->set("bong.root", rtrim(getcwd(), "/"));

	$r_url    = $request->url;
	$r_method = @$request->method;
	$r_get    = @$request->get;
	$r_post   = @$request->post;
	$r_format = @$request->format;

	$_SERVER['SCRIPT_FILENAME']   = str_replace('batch.php', 'index.php', $_SERVER['SCRIPT_FILENAME']);
	$_SERVER['REQUEST_URI']       = $r_url;
	$_SERVER['QUERY_STRING']      = @$r_get ? $r_get : parse_url($r_url, PHP_URL_QUERY);
	$_SERVER['SCRIPT_NAME']       = str_replace('batch.php', 'index.php', $_SERVER['SCRIPT_NAME']);
	//$_SERVER['PATH_TRANSLATED'] = do bong depend on this parameter ?
	$_SERVER['PHP_SELF']          = str_replace('batch.php', 'index.php', $r_url);
	$_SERVER['PATH_INFO']         = '/'.trim(str_replace($installation_base, '', $r_url), "/");
	$_GET                         = parse_url($_SERVER['QUERY_STRING']);

	unset($_POST['payload']);
	$_POST                        = @$r_post;
	if(count($_POST) > 0){
		$_SERVER['REQUEST_METHOD'] = 'POST';
	}

	/**
	 * Slash the Url to Get URL Parts.
	 * e.g. exploding it with '/' will extract all URL Parts in an array
	 */
	MemPool::instance()->set("bong.url.path", isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');
	Runtime::loadModule('rom');
	
	\ROM\BongCurrentUserData::startSession();
	
	$router = URLAnalyzer::instance()->decide();

	\ROM\BongCurrentUserData::instance()->load();
	if(!\ROM\BongCurrentUserData::instance()->identical()){
		\ROM\BongCurrentUserData::reset();
	}
	$urlReq = new \ROM\UrlRequest(time(), session_id(), $_SERVER['SCRIPT_NAME']);
	\ROM\BongCurrentUserData::instance()->addUrlRequest($urlReq);
	
	/*AbstractContentEngine* */ $engine = $router->engine();
	$engine->run();
	HTTPHeaders::send();
	$response = $engine->response();
	$res = new stdClass;
	$res->url = $r_url;
	$res->res = base64_encode($response);
	$response = "";
	$response_buff[] = $res;
	
	\ROM\BongCurrentUserData::instance()->dump();
	Singleton::clearAllInstances();
	//var_dump(Path::instance()->evaluate(":mkt.apps.view.+&controller.-&method.@&method.view.php"));
}
/**/
/*}*/
header('Content-Type: application/json');
echo json_encode($response_buff);

?>
