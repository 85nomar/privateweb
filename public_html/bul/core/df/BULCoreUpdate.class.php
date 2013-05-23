<?php
namespace racore\bul\core\df;

use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBConfig;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBValid;
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
        $lstrDir = './';
        $lstrRescueDir = '../_updaterescue/'.$pstrTagOld;
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
            $larrStruktur = $this->loadXMLIntoArray($lstrDir.'struktur.xml');
            $larrSourceStruktur = $this->loadXMLIntoArray(
                $lstrDir.'source_struktur.xml'
            );

            // Struktur vergleichen
            $lstrChanges = $this->getDBChanges(
                $larrStruktur, $larrSourceStruktur
            );

            // Struktur updaten
            $this->dbWriteAndDoSQL(
                $lstrChanges,
                $lstrDir.'db_changes.sql',
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword
            );

            // Struktur nochmals vom Ziel erstellen
            $this->dbDumpStructureToXML(
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword,
                $lstrDir.'struktur_neu.xml'
            );

            // Index einlesen
            $larrIndex = $this->getDBIndex($lstrDir.'struktur_neu.xml');
            $larrSourceIndex = $this->getDBIndex(
                $lstrDir.'struktur_source.xml'
            );

            // Index Vergleichen
            $lstrKeyChanges = $this->getKeyChanges(
                $larrSourceIndex, $larrIndex
            );

            // Index updaten
            $this->dbWriteAndDoSQL(
                $lstrKeyChanges,
                $lstrDir.'index_changes.sql',
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword
            );

            // Neue Records
            $this->dbInsertNewRecords(
                $lstrServer,
                $lstrUser,
                $lstrPassword,
                $lstrDatabase,
                $lstrTempDB,
                LIBConfig::getContentTables(),
                $larrSourceStruktur
            );

            // Full-Tables einlesen
            $this->dbImportDump(
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword,
                $lstrDir.'fulltable_quelle.sql'
            );

            // Views auf Ziel ermitteln
            $larrViews = $this->getViewsFromDump($lstrDir.'struktur.xml');

            // Views auf Ziel löschen
            $this->deleteViews(
                $larrViews, $lstrServer, $lstrUser, $lstrPassword, $lstrDatabase
            );

            // Views auf Ziel neu erstellen
            $this->dbImportDump(
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword,
                $lstrDir.'view_quelle.sql'
            );

            // Struktur nochmals erstellen
            $this->dbDumpStructureToXML(
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword,
                $lstrDir.'struktur_last.xml'
            );

            // Struktur nochmals einlesen
            $larrStruktur = $this->loadXMLIntoArray(
                $lstrDir.'struktur_last.xml'
            );

            // Zu löschende Objekte Prüfen
            $lstrDrop = $this->getDBDrops($larrSourceStruktur, $larrStruktur);

            // SQL Statement Ausführen
            $this->dbWriteAndDoSQL(
                $lstrDrop,
                $lstrDir.'db_drop.sql',
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword
            );

            // Struktur nochmals erstellen
            $this->dbDumpStructureToXML(
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword,
                $lstrDir.'struktur_afterdrop.xml'
            );

            // Index Einlesen
            $larrIndex = $this->getDBIndex($lstrDir.'struktur_afterdrop.xml');

            // Drop Index prüfen
            $lstrKeyDrops = $this->getKeyDrops($larrSourceIndex, $larrIndex);

            // SQL Aufsführen
            $this->dbWriteAndDoSQL(
                $lstrKeyDrops,
                $lstrDir.'index_drop.sql',
                $lstrServer,
                $lstrDatabase,
                $lstrUser,
                $lstrPassword
            );


            /**
             * Aufräumen
             */

            // Temporäre DB löschen
            $this->dbDrop($lstrServer, $lstrTempDB, $lstrUser, $lstrPassword);

            // Verzeichnise vorsichtshabler umbenennen, statt löschen
            $this->moveUpdateDir($lstrDir, $lstrRescueDir);
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

    public function getDBChanges($parrSource, $parrData)
    {
        $lstrSql = '';
        foreach ($parrSource AS $lstrTable => $larrSourceFields) {
            $larrFields = $parrData[$lstrTable];
            if (LIBValid::isArray($larrFields)) {
                $lstrSql .= $this->getCreateTableStatement(
                    $lstrTable, $parrSource
                );
            } else {
                $lstrSql .= $this->getTableChanges(
                    $lstrTable, $larrFields, $larrSourceFields
                );
            }
        }
        return $lstrSql;
    }

    public function dbWriteAndDoSQL(
        $pstrContent,
        $pstrDestination,
        $pstrServer,
        $pstrDatabase,
        $pstrUser,
        $pstrPassword
    )
    {
        if (strlen($pstrContent) > 0) {
            if ($this->writeFile($pstrDestination, $pstrContent, 'w')) {
                $this->dbImportDump(
                    $pstrServer,
                    $pstrDatabase,
                    $pstrUser,
                    $pstrPassword,
                    $pstrDestination
                );
            }
        }
    }

    public function getDBIndex($pstrFileName)
    {
        $larrDatabases = simplexml_load_file($pstrFileName);
        $larrDaten = array();
        foreach ($larrDatabases AS $larrDatabase) {
            foreach ($larrDatabase AS $larrTable) {
                $lstrTable = (string) $larrTable['name'];
                if ($lstrTable->options['Comment'] != 'VIEW') {
                    foreach ($larrTable->key AS $larrElement) {
                        $lstrKey = (string) $larrElement['Key_name'];
                        $lstrColumn = (string) $larrElement['Column_name'];
                        $lstrNotUnique = (string) $larrElement['Non_unique'];
                        $lstrTable = (string) $larrElement['Table'];
                        $larrDaten[$lstrTable][$lstrKey]['Key_name'] = $lstrKey;
                        $larrDaten[$lstrTable][$lstrKey]['Column_name'] =
                            $lstrColumn;
                        $larrDaten[$lstrTable][$lstrKey]['Non_unique'] =
                            $lstrNotUnique;
                        $larrDaten[$lstrTable][$lstrKey]['Table'] = $lstrTable;
                    }
                }
            }
        }
        return $larrDaten;
    }

    public function getKeyChanges($parrSource, $parrData)
    {
        $lstrSql = '';
        foreach ($parrSource AS $lstrTable => $larrIndex) {
            foreach ($larrIndex AS $lstrKey => $lstrValue) {
                if (!isset($parrData[$lstrTable][$lstrKey])) {
                    $lstrSql .= 'CREATE INDEX '.$lstrKey.' ON '.$lstrTable.' ';
                    $lstrSql .= '('.$lstrValue['Column_name'].' ); '." \n";
                }
            }
        }
        return $lstrSql;
    }

    public function dbInsertNewRecords(
        $pstrServer,
        $pstrUser,
        $pstrPassword,
        $pstrDatabase,
        $pstrTempDatabase,
        $parrContentTables,
        $parrStruktur
    )
    {
        $lbooReturn = true;
        if (count($parrContentTables) > 0) {
            foreach ($parrContentTables AS $larrTable) {
                $lrefDB = mysql_connect(
                    $pstrServer, $pstrServer, $pstrPassword
                );
                $this->selectDB($pstrDatabase, $lrefDB);
                $lstrSql =  'SELECT * FROM '.$pstrTempDatabase.'.';
                $lstrSql .= $larrTable['name'].' WHERE ';
                $lstrSql .= $larrTable['reffield'].' NOT IN ';
                $lstrSql .= '(SELECT '.$larrTable['reffield'].' FROM ';
                $lstrSql .= $pstrDatabase.'.'.$larrTable['name'].')';
                $lrefSql = mysql_query($lstrSql, $lrefDB);
                if (!$lrefSql) {
                    $lbooReturn = false;
                }
                if (mysql_num_rows($lrefSql)) {
                    if ($this->checkAutoIncrement(
                        $larrTable['name'], $parrStruktur
                    )) {
                        $lstrFieldlist = $this->getTableFieldList(
                            $larrTable['name'], $parrStruktur
                        );
                        $larrFields = preg_split('/,/', $lstrFieldlist);

                        while ($larrRow = mysql_fetch_assoc($lrefSql)) {
                            $lstrInsert = 'INSERT INTO '.$pstrDatabase.'.';
                            $lstrInsert .= $larrTable['name'].' (';
                            $lstrInsert .= $lstrFieldlist.') VALUES (';
                            foreach ($larrFields AS $lstrFKey => $lstrFValue) {
                                $lstrInsert .= '\''.$larrRow[$lstrFValue].'\'';
                            }
                            $lstrInsert = substr(
                                $lstrInsert, 0, strlen($lstrInsert) - 1
                            );
                            $lstrInsert .= ')';
                            mysql_query($lstrInsert, $lrefDB);
                        }
                    }
                }

            }
        }
        return $lbooReturn;
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
                        $lstrKey = (string) $laelement['Key'];
                        $lstrType = (string) $laelement['Type'];
                        $lstrNull = (string) $laelement['Null'];
                        $lstrDefault = (string) $laelement['Default'];
                        $lstrExtra = (string) $laelement['Extra'];
                        $ladaten[$lstrTable][$lstrFeld]['Field'] = $lstrFeld;
                        $ladaten[$lstrTable][$lstrFeld]['Key'] = $lstrKey;
                        $ladaten[$lstrTable][$lstrFeld]['Type'] = $lstrType;
                        $ladaten[$lstrTable][$lstrFeld]['Null'] = $lstrNull;
                        $ladaten[$lstrTable][$lstrFeld]['Default'] =
                            $lstrDefault;
                        $ladaten[$lstrTable][$lstrFeld]['Extra'] = $lstrExtra;
                    }
                }
            }
        }



        return $ladaten;
    }

    public function getDBDrops($parrSource, $parrData)
    {
        $lstrSql = '';
        foreach ($parrData AS $lstrTable => $larrFields) {
            if (!isset($parrSource[$lstrTable])) {
                $lstrSql .= $this->getDropTableStatement($lstrTable);
            } else {
                $lstrSql .= $this->getDropTableChanges(
                    $lstrTable, $parrSource, $parrData
                );
            }
        }
        return $lstrSql;
    }

    public function getDropTableStatement($pstrTable)
    {
        $lstrDrop = 'DROP TABLE IF EXISTS '.$pstrTable."; \n";
        return $lstrDrop;
    }

    public function getDropTableChanges($pstrTable, $parrSource, $parrData)
    {
        $lstrAlter = '';
        foreach ($parrData[$pstrTable] AS $larrField) {
            if (!isset($parrSource[$pstrTable][$larrField['Field']])) {
                $lstrAlter .= 'ALTER TABLE '.$pstrTable.' DROP COLUMN ';
                $lstrAlter .= $larrField['Field']."; \n";
            }
        }
        return $lstrAlter;
    }



    public function getKeyDrops($parrSource, $parrData)
    {
        $lstrSql = '';
        foreach ($parrData AS $lstrTable => $larrIndex) {
            foreach ($larrIndex AS $lstrIndexName => $lstrIndexValue) {
                if (!isset($parrSource[$lstrTable][$lstrIndexName])
                    AND $lstrIndexName != 'PRIMARY') {
                    $lstrSql .= 'DROP INDEX '.$lstrIndexName.' ON ';
                    $lstrSql .= $lstrTable."; \n";
                }
            }
        }
        return $lstrSql;
    }

    public function dbDrop($pstrServer, $pstrDatabase, $pstrUser, $pstrPassword)
    {
        $lbooReturn = false;
        $lrefDB = $this->dbConnect($pstrServer, $pstrUser, $pstrPassword);
        mysql_query('DROP DATABASE '.$pstrDatabase, $lrefDB);
        if (!mysql_select_db($pstrDatabase, $lrefDB)) {
            $lbooReturn = true;
        }
        mysql_close($lrefDB);
        return $lbooReturn;
    }

    public function getCreateTableStatement($pstrTable, $parrSource)
    {
        $lstrCreate = 'CREATE TABLE '.$pstrTable.' (';
        $lstrEnd = '';
        foreach ($parrSource AS $larrField) {
            $lstrCreate .= $larrField['Field'].' '.$larrField['Type'].' ';
            if ($larrField['Null'] == 'NO') {
                $lstrCreate .= ' NOT NULL ';
            } else {
                $lstrCreate .= ' NULL ';
            }
            if ($larrField['Default'] != '') {
                if ($larrField['Default'] == 'CURRENT_TIMESTAMP') {
                    $lstrCreate .= 'DEFAULT '.$larrField['Default'].' ';
                } else {
                    $lstrCreate .= 'DEFAULT \''.$larrField['Default'].'\' ';
                }
            }
            if ($larrField['Extra'] != '') {
                $lstrCreate .= $larrField['Extra'];
            }
            $lstrCreate .= ', ';
            if ($larrField['Key'] == 'PRI') {
                $lstrEnd = ', PRIMARY KEY ('.$larrField['Field'].')';
            }
        }
        $lstrCreate = substr($lstrCreate, 0, strlen($lstrCreate) - 2);
        $lstrCreate .= $lstrEnd. ');'." \n";
        return $lstrCreate;
    }

    public function getTableChanges($pstrTable, $parrFields, $parrSourceFields)
    {
        $lstrAlter = '';
        foreach ($parrSourceFields AS $lstrKey => $larrSourceField) {
            if (isset($parrFields[$larrSourceField['Field']])) {
                $larrField = $parrFields[$larrSourceField['Field']];
                $lstrAlter .= $this->getAlterStatementForField(
                    $pstrTable, $larrField, $larrSourceField
                );
            } else {
                $lstrAlter .= $this->getAlterStatementForNewField(
                    $pstrTable, $larrSourceField
                );
            }
        }
        return $lstrAlter;
    }

    public function getAlterStatementForField(
        $pstrTable, $parrField, $parrSourceField
    )
    {
        $lstrAlter = '';
        $lbooToChange = false;
        $lstrNull = ' NULL ';
        $lstrDefault = '';
        $lstrExtra = '';
        $lstrField = $parrSourceField['Field'];

        if ($parrField['Type'] != $parrSourceField['Type']) {
            $lbooToChange = true;
        }

        if ($parrField['Null'] != $parrSourceField['Null']) {
            $lbooToChange = true;
            if ($parrSourceField['Null'] == 'NO') {
                $lstrNull = ' NOT NULL';
            }
        }

        if ($parrField['Default'] != $parrSourceField['Default']) {
            $lbooToChange = true;
            if ($parrSourceField['Default'] != '') {
                $lstrDefault .= 'DEFAULT \'' .
                    $parrSourceField['Default']. '\' ';
            }
        }

        if ($parrField['Extra'] != $parrSourceField['Extra']) {
            $lbooToChange = true;
            if ($parrSourceField['Extra'] != '') {
                $lstrExtra .= $parrSourceField['Extra']. '; ';
            }
        }

        if ($lbooToChange) {
            $lstrAlter = 'ALTER TABLE '.$pstrTable.' CHANGE COLUMN ';
            $lstrAlter .= $lstrField . ' ';
            $lstrAlter .= $parrSourceField['Type']. ' ';
            $lstrAlter .= $lstrNull . ' '.$lstrDefault. ' '.$lstrExtra."; \n";
        }

        return $lstrAlter;
    }

    public function getAlterStatementForNewField(
        $pstrTable, $parrSourceField
    )
    {
        $lstrNull = ' NULL ';
        $lstrDefault = '';
        $lstrExtra = '';
        $lstrField = $parrSourceField['Field'];
        if ($parrSourceField['Null'] == 'NO') {
            $lstrNull .= ' NOT NULL ';
        } else {
            $lstrNull .= ' NULL ';
        }

        if ($parrSourceField['Default'] != '') {
            if ($parrSourceField['Default'] == 'CURRENT_TIMESTAMP') {
                $lstrDefault .= 'DEFAULT '.$parrSourceField['Default'].' ';
            } else {
                $lstrDefault .= 'DEFAULT \''.$parrSourceField['Default'].'\' ';
            }
        }

        if ($parrSourceField['Extra'] != '') {
            $lstrExtra = $parrSourceField['Extra'];
        }

        $lstrAlter = 'ALTER TABLE '.$pstrTable.' ADD COLUMN ';
        $lstrAlter .= $lstrField . ' ';
        $lstrAlter .= $parrSourceField['Type']. ' ';
        $lstrAlter .= $lstrNull . ' '.$lstrDefault. ' '.$lstrExtra."; \n";
        return $lstrAlter;
    }

    public function writeFile($pstrDestination, $pstrContent, $pstrMode)
    {
        $lref = fopen($pstrDestination, $pstrMode);
        if (!fwrite($lref, $pstrContent)) {
            $lbooReturn = false;
        } else {
            $lbooReturn = true;
            fclose($lref);
        }
        return $lbooReturn;
    }

    public function selectDB($pstrDatabase, $prefDB)
    {
        $lbooReturn = false;
        if (mysql_select_db($pstrDatabase, $prefDB)) {
            $lbooReturn = true;
        }
        return $lbooReturn;
    }

    public function checkAutoIncrement($pstrTable, $parrData)
    {
        $lbooReturn = false;
        foreach ($parrData AS $larrField) {
            if ($larrField['Key'] == 'PRI') {
                if ($larrField['Extra'] == 'auto_increment') {
                    $lbooReturn = true;
                }
            }
        }
        return $lbooReturn;
    }

    public function getTableFieldList($pstrTable, $parrData)
    {
        $lstrFieldlist = '';
        foreach ($parrData[$pstrTable] AS $larrField) {
            if ($larrField['Key'] != 'PRI') {
                $lstrFieldlist .= $larrField['Field'].',';
            }
            $lstrFieldlist = substr(
                $lstrFieldlist, 0, strlen($lstrFieldlist) - 1
            );
        }
        return $lstrFieldlist;
    }

    public function deleteViews(
        $parrViews, $pstrServer, $pstrUser, $pstrPassword, $pstrDatabase
    )
    {
        if (count($parrViews) > 0) {
            $lrefDB = $this->dbConnect($pstrServer, $pstrUser, $pstrPassword);
            if (mysql_select_db($pstrDatabase, $lrefDB)) {
                foreach ($parrViews AS $lstrKey => $larrView) {
                    $lstrSql = 'DROP VIEW IF EXISTS '.$larrView['name'];
                    mysql_query($lstrSql, $lrefDB);
                }
            }
            mysql_close($lrefDB);
        }
    }

    public function getViewsFromDump($pstrFileName)
    {
        $larrViews = array();
        if (is_file($pstrFileName)) {

            $larrDatabases = simplexml_load_file($pstrFileName);
            foreach ($larrDatabases AS $larrDatabase) {
                foreach ($larrDatabase AS $larrTable) {
                    if ($larrTable->options['Comment'] == 'VIEW') {
                        $larrTemp['name'] =
                            (string) $larrTable->options['Name'];
                        array_push($larrViews, $larrTemp);
                    }
                }
            }
        }
        return $larrViews;
    }

    public function moveUpdateDir($pstrDir, $pstrDestination)
    {
        $lbooReturn = false;
        if (is_dir($pstrDir)) {
            rename($pstrDir, $pstrDestination);
        }
        if (is_dir($pstrDestination)) {
            $lbooReturn = true;
        }
        return $lbooReturn;
    }



}
