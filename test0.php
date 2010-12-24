<?php
error_reporting(255);
ini_set('display_errors','On');
header('Content-Type: text/plain');

require "usr/lib/include/singleton.php";
require "usr/lib/include/mempool.php";
require "usr/lib/include/configuratonadapter.php";
require "usr/lib/include/fstab.php";
require "usr/lib/include/xpathconfig.php";
require "usr/lib/include/path.php";

require "usr/lib/include/conf.php";
require "usr/lib/include/decider.php";
require "usr/lib/include/urlanalyzer.php";
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
MemPool::instance()->set("bong.url.path", $_SERVER['PATH_INFO']);

/**
 * \required instances
 * Fstab		Project details
 * Path			Path details
 * Conf			Configuration details
 * URLAnalyzer	URL details
 */

$fstab = Fstab::instance();
print_r($fstab->defaultProjectName());
echo PHP_EOL;
print_r($fstab->defaultProject());
print_r($fstab->projectNames());
print_r($fstab->projects());
print_r($fstab->projectExists("bong"));
echo PHP_EOL;
print_r($fstab->projectLocation("main"));
echo PHP_EOL;
var_dump($fstab->project("main")->exists());
echo "\n--------------------------\n";
$path = Path::instance();
var_dump($path->evaluate("etc.conf"));
var_dump($path->evaluate(":mkt"));
var_dump($path->evaluate("project:mkt"));
var_dump($path->evaluate(":mkt.etc.conf"));
$conf = Conf::instance();
var_dump($conf->evaluate("urlpatterns.sync"));
?>
