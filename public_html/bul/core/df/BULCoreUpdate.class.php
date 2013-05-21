<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreUser;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBConfig;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDbl;
use racore\uil\router\UIL_router;

/**
 * Das ist der Business-Layer vom Update
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       26.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreUpdate extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new LIBDbl();
        $ldbl->setTablename('core_user');
        $this->setDbl($ldbl);
        $this->setListTemplate('update_list.tpl');
        exec('git fetch --tags');
    }

    /**
     * Dieses Routing ist Speziell und gehört nicht zum Standard
     *
     * @param string $pstrData
     * @return bool
     * @access public
     */
    public function routeExtension($pstrData)
    {
        $lbooReturn = false;
        $luilRouter = new UIL_router();
        $luilRouter->setDbl($this->getDbl());
        switch($pstrData) {
            case 'updateaction':
                $lstrNew = LIBCore::getGet('strTag');
                $lstrOld = LIBCore::getGet('strTagOld');
                if (!is_dir('../updaterescue/'.$lstrOld)) {
                    mkdir('../_updaterescue/'.$lstrOld);
                }
                $lstrBefehl = "cd .. && sh update.sh '".
                    $lstrOld."' '".$lstrNew."'";
                exec($lstrBefehl, $larrOutput);
                $_GET = LIBCore::getGet();
                LIBCore::loadGet();
                unset($_GET['strTagOld']);
                unset($_GET['strTag']);
                $this->_listMask();
                break;
            case 'updaterescue':
                $lstrOld = LIBCore::getGet('strTagOld');
                $lstrBefehl = "cd .. && sh updaterescue.sh '".
                    $lstrOld."' 'dbname'";
                exec($lstrBefehl);
                $_GET = LIBCore::getGet();
                unset($_GET['strTagOld']);
                unset($_GET['strTag']);
                LIBCore::loadGet();
                $this->_listMask();
                break;
            default:
                break;
        }
        unset($pstrData);
        return $lbooReturn;
    }

    public function _listMask()
    {
        $lbooReturn = false;
        $luilRouter = new UIL_router();

        /**
         * Auslesen der Update-Rescue-Verzeichnisses
         */
        if (!is_dir('../_updaterescue')) {
            mkdir('../_updaterescue');
        }
        $larrUpdateRescue = scandir('../_updaterescue');

        /**
         * Versionen welche Ausgecheckt wurden
         */
        $larrVersion = array();
        exec('git describe --always --tag', $larrVersion);

        /**
         * Tags welche es überhaupt gibt
         */
        $larrTags = array();
        exec("git tag", $larrTags, $lnumReturn);

        /**
         * Tag-Temp-Array über Versions-Informationen
         */
        $larrTempTag = array(
            'version' => null,
            'old' => true,
            'act' => false,
            'updaterescue' => false
        );

        /**
         * Versionen abfragen und alles abfüllen
         */
        $lstrVersion = null;
        foreach ($larrTags AS $lstrKey => $lstrTag) {
            if ($larrVersion[0] == $lstrTag) {
                $larrTempTag['old'] = false;
                $larrTempTag['act'] = true;
                $lstrVersion = $lstrTag;
            }

            $larrTempTagEnd = $larrTempTag;
            $larrTempTag['act'] = false;
            $larrTempTagEnd['version'] = $lstrTag;
            if (in_array($lstrTag, $larrUpdateRescue)) {
                $larrTempTagEnd['updaterescue'] = true;
            }
            $larrTags[$lstrKey] = $larrTempTagEnd;

        }
        $larrTags = array_reverse($larrTags);

        $larrDaten = array();
        $larrDaten['arrTags'] = $larrTags;
        $larrDaten['arrVersion'] = $larrVersion;
        $larrDaten['strVersion'] = $lstrVersion;

        $larrData = array(
            'strTyp' => 'list',
            'arrData' => $larrDaten,
            'arrBreadcrumb' => $this->_getBreadCrumbListMask()
        );
        $larrDataTwo = array(
            'strTemplate' => $this->getListTemplate(),
            'arrContent' => $larrData,
            'arrNavigation' => $this->_getNavigation()
        );
        $luilRouter->setDbl($this->getDbl());
        $luilRouter->route($larrDataTwo);
        return $lbooReturn;
    }

    public function dbImport($pstrTagOld)
    {
        $lstrSourceServer = LIBConfig::getSourceServer();
        $lstrSourceDatabase = LIBConfig::getSourceDatabase();
        $lstrSourceUser = LIBConfig::getSourceUser();
        $lstrSourcePassword = LIBConfig::getSourcePassword();
        $lstrServer = LIBConfig::getServer();
        $lstrDatabase = LIBConfig::getDatabase();
        $lstrUser = LIBConfig::getUser();
        $lstrPassword = LIBConfig::getPassword();
        $lstrDir = '_updaterescue/'.$pstrTagOld.'/';
        $lstrTempDB = $lstrDatabase.'_'.$pstrTagOld;

        $lbooReturn = true;
        if (is_dir($lstrDir)) {

            // Datenbank DB erstellen
            $lstrFileName = $lstrDir.'backup.sql';
            $lbooReturn = $this->dbDump(
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword,
                $lstrFileName
            );

            // Temporäre DB erstellen
            if ($lbooReturn) {
                $lbooReturn = $this->dbCreate(
                    $lstrDatabase,
                    $lstrTempDB,
                    $lstrUser,
                    $lstrPassword
                );
            }

            // Content-Tables in Temp-DB einlesen
            if ($lbooReturn) {
                $lstrFileName = $lstrDir.'contenttable_source.sql';
                $lbooReturn = $this->dbImportDump(
                    $lstrDatabase,
                    $lstrTempDB,
                    $lstrUser,
                    $lstrPassword,
                    $lstrFileName
                );
            }

            // DB Struktur Dumpen
            if ($lbooReturn) {
                $lstrFileName = $lstrDir.'struktur.xml';
                $lbooReturn = $this->dbDumpStructureToXML(
                    $lstrServer,
                    $lstrDatabase,
                    $lstrUser,
                    $lstrPassword,
                    $lstrFileName
                );
            }

            // Strukturdaten einlesen









        }

    }

    public function dbDump( $pstrServer,
                            $pstrDatabase,
                            $pstrUser,
                            $pstrPassword,
                            $pstrFileName)
    {
        $lstrBefehl =  "mysqldump --opt -h'".$pstrServer."' ";
        $lstrBefehl .= "-u ".$pstrUser." --password='".$pstrPassword."' ";
        $lstrBefehl .= $pstrDatabase." > ".$pstrFileName;
        exec($lstrBefehl, $larrReturn);

        if (!is_file($pstrFileName)) {
            return false;
        }

    }

    public function dbCreate( $pstrServer,
                              $pstrDatabase,
                              $pstrUser,
                              $pstrPassword)
    {
        $lbooReturn = false;
        $lrefDB = $this->dbConnect($pstrServer, $pstrUser, $pstrPassword);
        if ($lrefDB) {
            mysql_query("CREATE DATABASE $pstrDatabase", $lrefDB);
            if (mysql_select_db($pstrDatabase, $lrefDB)) {
                $lbooReturn = true;
            }
        }
        return $lbooReturn;
    }

    public function dbConnect( $pstrServer, $pstrUser, $pstrPassword)
    {
        $lbooReturn = false;
        $lref = mysql_connect($pstrServer, $pstrUser, $pstrPassword);
        if ($lref) {
            $lbooReturn = true;
        }
        return $lbooReturn;
    }

    public function dbImportDump($pstrServer,
                                 $pstrDatabase,
                                 $pstrUser,
                                 $pstrPassword,
                                 $pstrFileName)
    {
        $lbooReturn = false;
        if (is_file($pstrFileName)) {
            $lstrContent = file_get_contents($pstrFileName);
            $lstrSearch =  "(DEFINER=`){1}([A-Za-z0-9]){1,}(`@`){1}";
            $lstrSearch .= "([A-Za-z0-9_%]){1}(`){1}";
            $lstrReplace = "DEFINER=`".$pstrUser."`@`".$pstrServer."` ";
            $lstrContent = preg_replace(
                '/'.$lstrSearch.'/', $lstrReplace, $lstrContent
            );
            file_put_contents($pstrFileName, $lstrContent);
            $lstrBefehl =  "mysql -h'".$pstrServer."' ";
            $lstrBefehl .= "-u ".$pstrUser." --password='".$pstrPassword."' ";
            $lstrBefehl .= $pstrDatabase." < ".$pstrFileName;
            exec($lstrBefehl, $larrReturn);
            $lbooReturn = true;
        }
        return $lbooReturn;
    }

    public function dbDumpStructureToXML($pstrServer,
                                         $pstrDatabase,
                                         $pstrUser,
                                         $pstrPassword,
                                         $pstrFileName)
    {
        $lbooReturn = false;
        $lstrBefehl =  "mysqldump --opt --no-data --xml -h'".$pstrServer."' ";
        $lstrBefehl .= "-u ".$pstrUser." --password='".$pstrPassword."' ";
        $lstrBefehl .= $pstrDatabase." > ".$pstrFileName;
        exec($lstrBefehl);
        if (is_file($pstrFileName)) {
            $lbooReturn = true;
        }
        return $lbooReturn;
    }

    public function getDBChanges()
    {

    }

    public function dbWriteAndDoSQL()
    {

    }

    public function getDBIndex()
    {

    }

    public function getKeyChanges()
    {

    }

    public function dbInsertNewRecords()
    {

    }

    public function dbDeleteViews()
    {

    }

    public function loadXMLIntoArray($pstrFileName)
    {
        $ladatabases = simplexml_load_file($pstrFileName);
        $ladaten = array();
        foreach ($ladatabases AS $ladatabase) {
            foreach ($ladatabase AS $latable) {
                $lstrTable = (string) $latable['name'];
                if ($latable->options['Comment'] != 'VIEW') {
                    foreach ($latable->field AS $laelement) {
                        $lstrFeld = (string) $laelement['Field'];
                        $ladaten[$lstrTable][$lstrFeld] = $lstrFeld;

                    }
                }
            }
        }



        return $ladaten;
    }

    public function getKeyDrops()
    {

    }

    public function dbDrop()
    {

    }



}
