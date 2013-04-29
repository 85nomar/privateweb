<?php

/**
 * Startklasse für den Live-Teil
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       01.02.2013 22:18
 * @version     1.0.0
 * @package     Start
 * @copyright   Copyright (c) 2013 Raffael Wyss
 */

/**
 * Setting the Namespace an the Usings
 */
namespace racore;
use racore\bul\router\BUL_router;
use \racore\phplibs\core\LIBConfig AS LIBConfig;
use \racore\phplibs\core\LIBAutoload AS LIBAutoload;

use racore\phplibs\core\LIBCore;
use \racore\phplibs\core\LIBDB AS LIBDB;

/**
 * Define the Document-Root for the Test
 */
define("DOCUMENT_ROOT", __DIR__);
$lstrtestpath = str_replace("public_html", "public_test", DOCUMENT_ROOT);

/**
 * Define the Document-Root
 */
define("DOCUMENT_ROOT_TEST", $lstrtestpath);

/**
 * Required-Classes
 */
require_once 'phplibs/core/LIBAutoload.class.php';

/**
 * Set the Autoloader
 */
spl_autoload_register(array("\\racore\phplibs\core\LIBAutoload", "loadClass"));

/**
 * Set the Config File -> by ServerName
 */
if (!LIBConfig::setConfigByConfigFile()) {
    echo "Error on Loading the Config-File";
}

/**
 * Start Application
 */
try {

    error_reporting(E_ALL);

    /**
     * Lädt die GET/POST/SESSION Variablen und Validiert diese
     */
    session_start();
    LIBCore::loadGet();
    LIBCore::loadPost();

    /**
     * Datenbankverbindung-Aufbauen
     */
    LIBDB::setup('localhost', 'racore2', 'racore', 'racoretest');
    LIBDB::connect(); // @TODO


    /**
     * Definition des zu Nutzenden Namensraum (Default = df = standard)
     */
    LIBCore::setGlobal('namensraum', 'df');

    /**
     * Grund- bzw. Hauptdaten laden
     */
    LIBCore::setGlobal('strPageTitle', 'raCore');
    LIBCore::loadLabel();
    LIBCore::loadConfig();
    LIBCore::loadRight();

    /**
     * Load-Daten wenn nicht definiert
     */
    if (LIBCore::getGet('strBul') == '') {
        $_GET['strBul'] = 'CoreLogin';
    }
    if (LIBCore::getGet('strView') == '') {
        $_GET['strView'] = 'html';
    }
    if (LIBCore::getGet('strAction') == '') {
        $_GET['strAction'] = 'loginMask';
    }
    LIBCore::loadGet();

    /**
     * Business-Router aufrufen
     */
    $lbulRouter = new BUL_router();
    $lbulRouter->route();


} catch (\Exception $e) {
    echo "<b>Exception</b><br>";
    echo $e->getMessage();
    echo $e->getTrace();
}

