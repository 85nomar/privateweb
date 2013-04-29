<?php
namespace racore\phplibs\core;
use \PDO AS PDO;
use \PDOStatement AS PDOStatement;

/**
 * Datenbanklasse welche Statisch ist und Abfragen bei einer Datenbank macht
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       01.02.2013
 * @version     1.0.2
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 * @final
 *
 */
final class LIBDB
{
    /**
     * Enthält die Adresse des Datenbankservers
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strServer = "";

    /**
     * Enthält den Namen der Datenbank
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strDatabase = "";

    /**
     * Enthält den Namen des Datenbankbenutzers
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strUser = "";

    /**
     * Enthält das Passwort des Datenbankbenutzers
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strPassword = "";

    /**
     * Enhält die Instanz des Objektes DB
     *
     * @var null|LIBDB
     * @private
     * @static
     */
    private static $_objLIBDB = null;

    /**
     * Enthält die Objekt-Referenz zum PDO-Objekt
     *
     * @var null|PDO
     * @access private
     * @static
     */
    private static $_objReference = null;

    /**
     * Enthält die Objekt-Referenz zum PDOStatement-Objekt
     *
     * @var null|PDOStatement
     * @access private
     * @static
     */
    private static $_objStatement = null;

    /**
     * Die Datenbankverbindung wird komplett beendet
     *
     * @return boolean
     * @access public
     * @static
     */
    public static function close()
    {
        $lbooReturn = false;
        self::$_objLIBDB = null;
        self::$_objReference = null;
        self::$_objStatement = null;
        return $lbooReturn;
    }

    /**
     * Verbindung zur Datenbank wird hergestellt
     *
     * @return bool
     * @access public
     * @static
     */
    public static function connect()
    {
        $lbooReturn = false;
        $lstrdns = 'mysql:host=' . self::$_strServer . ';dbname='
            . self::$_strDatabase;
        if (self::$_objReference
            = new PDO($lstrdns, self::$_strUser, self::$_strPassword)
        ) {
            $lbooReturn = true;
        }
        return $lbooReturn;
    }

    /**
     * Bereites das Ergebnis vor
     *
     * @param string $pstrQuery
     *
     * @return PDOStatement
     * @access private
     * @static
     */
    private static function _prepare($pstrQuery)
    {
        return self::$_objReference->prepare($pstrQuery);
    }

    /**
     * Führt ein Query aus und gibt nur True/False zurück
     *
     * @param string $pstrQuery
     * @param array  $parrData
     * @param array  $parrFormat
     *
     * @return bool
     * @access public
     * @static
     */
    public static function query(
        $pstrQuery, $parrData = array(), $parrFormat = array()
    )
    {
        $lbooReturn = false;
        $lstrQuery = $pstrQuery;
        $larrData = $parrData;
        $larrFormat = $parrFormat;
        unset($pstrQuery);
        unset($parrData);
        unset($parrFormat);
        self::$_objStatement = self::_prepare($lstrQuery);
        foreach ($larrData AS $lstrKey => $lobjvalue) {
            $lobjdata = $lobjvalue;
            $linttyp = self::_getFormat($lstrKey, $larrFormat);
            self::$_objStatement->bindValue(
                ':' . $lstrKey, $lobjdata, $linttyp
            );
        }
        if (self::$_objStatement->execute()) {
            $lbooReturn = true;
        } else {
            $larrError = array();
            $larrError['type'] = 'systemerror';
            $larrError['strLabel'] = '<h4>';
            $larrError['strLabel'] .= '<i class="icon-exclamation-sign"></i>';
            $larrError['strLabel'] .= ' SQLERROR</h4><br>';
            $larrError['strLabel'] .= self::$_objStatement->errorCode().'<br>';
            $larrError['strLabel'] .= '<pre>';
            $larrError['strLabel'] .= print_r(
                self::$_objStatement->errorInfo(), true
            );
            $larrError['strLabel'] .= '<hr>'.$lstrQuery.'<hr>';
            $larrError['strLabel'] .= print_r($larrData, true);
            $larrError['strLabel'] .= '</pre><br>';
            LIBCore::setMessage($larrError);
        }
        return $lbooReturn;
    }

    /**
     * Gibt den PDO-Integer-Typ zurück, also ob es INT String etc. ist
     *
     * @param $pstrKey
     * @param $parrFormat
     * @return int
     * @access private
     * @static
     */
    private static function _getFormat($pstrKey, $parrFormat)
    {
        $lstrFormat = '';
        if (isset($parrFormat[$pstrKey])) {
            $lstrFormat = $parrFormat[$pstrKey]['Type'];
        }
        switch ($lstrFormat) {
            case "int":
                $lintTyp = PDO::PARAM_INT;
                break;
            default:
                $lintTyp = PDO::PARAM_STR;
                break;
        }
        return $lintTyp;
    }

    /**
     * Gibt die Daten aus dem Letzen-Query Request zurück
     *
     * @return array
     * @access public
     * @static
     */
    public static function getData()
    {
        return self::$_objStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gibt den Primar-KeyName zurück
     *
     * @param $pstrTablename
     *
     * @return bool|string
     * @access public
     */
    public static function getPrimaryKeyName($pstrTablename)
    {
        $lstrQuery = 'SHOW KEYS FROM ' . $pstrTablename . '
                      WHERE Key_name = \'PRIMARY\'';
        self::$_objStatement = self::_prepare($lstrQuery);
        if (self::$_objStatement->execute()) {
            $larrData = self::$_objStatement->fetch();
            return (string)$larrData['Column_name'];
        } else {
            return false;
        }
    }

    /**
     * Gibt alle Feldinformationen zu einer Tabelle zurück
     *
     * @param $pstrTablename
     *
     * @return array|bool
     * @access public
     */
    public static function getFieldInformations($pstrTablename)
    {
        $lstrQuery = 'SHOW FIELDS FROM ' . $pstrTablename;
        self::$_objStatement = self::_prepare($lstrQuery);
        if (self::$_objStatement->execute()) {
            $larrData = self::$_objStatement->fetchAll();
            $larrReturn = array();
            foreach ($larrData AS $lstrValue) {
                $larrField = array();
                $lstrType = $lstrValue['Type'];
                $larrSplit = explode('(', $lstrType);
                $lstrLength = preg_replace('([^0-9])', '', $lstrType);
                $larrField['Type'] = $larrSplit[0];
                $larrField['Length'] = $lstrLength;
                $larrReturn[$lstrValue['Field']] = $larrField;
            }
            return $larrReturn;
        } else {
            return false;
        }
    }

    /**
     * Hier werden die Einstellungen vorgenommen
     *
     * @param $pstrServer
     * @param $pstrDatabase
     * @param $pstrUser
     * @param $pstrPassword
     *
     * @return bool
     * @access public
     * @static
     */
    public static function setup(
        $pstrServer, $pstrDatabase, $pstrUser, $pstrPassword
    )
    {
        $lbooReturn = true;
        self::$_strServer = $pstrServer;
        self::$_strDatabase = $pstrDatabase;
        self::$_strUser = $pstrUser;
        self::$_strPassword = $pstrPassword;
        return $lbooReturn;
    }


    /**
     * Override the Constructer
     *
     * @access public
     */
    public function __construct()
    {
        if (!self::$_objLIBDB) {
            self::$_objLIBDB = new self();
        }
        return self::$_objLIBDB;
    }

    /**
     * Override the Cloner
     *
     * @access public
     */
    public function __clone()
    {
    }

}

