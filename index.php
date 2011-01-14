<?php
error_reporting(255);
ini_set('display_errors','On');

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
//}

//{ Helpers and Utils
require 'usr/lib/include/common.php';
require 'usr/lib/include/resourcehelper.php';
require 'usr/lib/include/susax.php';
require 'usr/lib/include/bongparser.php';
//}

require 'usr/lib/include/urlanalyzer.php';
require 'usr/lib/include/runtime.php';

require 'usr/lib/include/AbstractDataTray.php';
require 'usr/lib/include/backend.php';

$urlPathParts = pathinfo($_SERVER['SCRIPT_NAME']);
MemPool::instance()->set("bong.url.base", rtrim($urlPathParts['dirname'], "/"));

/**
 * Set the current directoty as Bong Root Directoty.
 * Furtur Directory Manipulation will be relative to this path.
 * e.g. will be / concated with teh root and then executed as absolute.
 */
MemPool::instance()->set("bong.root", rtrim(getcwd(), "/"));
/**
 * Slash the Url to Get URL Parts.
 * e.g. exploding it with '/' will extract all URL Parts in an array
 */
MemPool::instance()->set("bong.url.path", isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');


/*AbstractContentRouter* */ $router = URLAnalyzer::instance()->decide();
/*AbstractContentEngine* */ $engine = $router->engine();
$engine->run();
HTTPHeaders::send();
$engine->writeResponse();

//var_dump(Path::instance()->evaluate(":mkt.apps.view.+&controller.-&method.@&method.view.php"));

?>
