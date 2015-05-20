<?php
/**
 * Title
 * @autor: Petr Supe <cmdconfig@gmail.com>
 * @date: 7/18/14
 * @time: 7:21 PM
 * @version 1.0
 */

include_once "init.php";

define('DOCROOT', __DIR__.DIRECTORY_SEPARATOR);
/**
 * Path to the application directory.
 */
define('APPPATH', realpath(__DIR__.'/app').DIRECTORY_SEPARATOR);
/**
 * The path to the core.
 */
define('COREPATH', realpath(__DIR__.'/core').DIRECTORY_SEPARATOR);


$Parsers = new Controller_Parsers();
$Parsers->index(true);
