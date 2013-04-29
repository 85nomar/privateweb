<?php
namespace racore\phplibs\core;

use racore\bul\core\df\BULCoreConfig;
use racore\bul\core\df\BULCoreLabel;

/**
 * Mit dieser Klasse werden die Grundsätzlichen Daten verwaltet, welche für die
 * Applikation benötigt werden. Darin werden auch Grundsätzliche Prüfungen
 * der Daten gemacht, welche absolut verboten sind
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       15.03.2013
 * @version     1.0.0
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 */
class LIBCore
{

    /**
     * Enhält die Instanz des Objektes DB
     *
     * @var null|LIBCore
     * @private
     * @static
     */
    private static $_objLIBCore = null;

    /**
     * Enthält die POST-Daten
     *
     * @var array
     * @static
     * @access private
     */
    private static $_arrPost = array();

    /**
     * Enthält die GET-Daten
     *
     * @var array
     * @static
     * @access private
     */
    private static $_arrGet = array();

    /**
     * Enthält die Global-Daten
     *
     * @var array
     * @static
     * @access private
     */
    private static $_arrGlobal = array();


    /**
     * Enthält ein Error-Array mit allen Fehlermeldungen
     *
     * @var array
     * @static
     */
    private static $_arrMessage = array();

    /**
     * Enthält alle Labels
     *
     * @var array
     * @static
     */
    private static $_arrLabel = array();

    /**
     * Enthält die Komplette Konfiguration
     *
     * @var array
     * @static
     */
    private static $_arrConfig = array();

    /**
     * Enthält die Rechte
     *
     * @var array
     * @static
     */
    private static $_arrRight = array();

    /**
     * Gibt den Wert vom GET zurück
     *
     * @param string $pstrName
     *
     * @return bool|null|mixed
     * @access public
     * @static
     */
    public static function getGet($pstrName = null)
    {
        $lstrName = $pstrName;
        unset($pstrName);
        if (is_null($lstrName)) {
            return self::$_arrGet;
        } else if (!isset(self::$_arrGet[$lstrName])) {
           return null;
        } else {
            return self::$_arrGet[$lstrName];
        }
    }

    /**
     * Gibt die Global-Variablen zurück
     *
     * @param string $pstrName
     *
     * @return bool|null|mixed
     * @access public
     * @static
     */
    public static function getGlobal($pstrName)
    {
        $lstrName = $pstrName;
        unset($pstrName);
        if (is_null($lstrName)) {
            return self::$_arrGlobal;
        } else if (!isset(self::$_arrGlobal[$lstrName])) {
            return null;
        } else {
            return self::$_arrGlobal[$lstrName];
        }
    }

    /**
     * Gibt die POST Daten zurück
     *
     * @param string $pstrName
     *
     * @return bool|null|mixed
     * @access public
     * @static
     */
    public static function getPost($pstrName = null)
    {
        $lstrName = $pstrName;
        unset($pstrName);
        if (is_null($lstrName)) {
            return self::$_arrPost;
        } else if (!isset(self::$_arrPost[$lstrName])) {
            return null;
        } else {
            return self::$_arrPost[$lstrName];
        }
    }

    /**
     * Lädt alle GET-Variablen
     *
     * @return bool
     * @access public
     * @static
     */
    public static function loadGet()
    {
        if ($larrData = self::_validateArray($_GET)) {
            self::$_arrGet = $larrData;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lädt alle POST-Variablen
     *
     * @return bool
     * @access public
     * @static
     */
    public static function loadPost()
    {
        if ($larrData = self::_validateArray($_POST)) {
            self::$_arrPost = $larrData;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Setzt eine Globalen Teil
     *
     * @param string $pstrName
     * @param mixed $pstrContent
     *
     * @return bool
     * @access public
     * @static
     */
    public static function setGlobal($pstrName, $pstrContent)
    {
        $lstrName = $pstrName;
        $lstrContent = $pstrContent;
        unset($pstrName, $pstrContent);
        if (is_null($lstrName) OR is_null($lstrContent)) {
            return false;
        } else {
            self::$_arrGlobal[$lstrName] = $lstrContent;
            return true;
        }
    }

    /**
     * Setzt Nachricht in den Zwischenspeicher
     *
     * @param array $parrContent
     * @param boolean pbooInit
     *
     * @static
     */
    public static function setMessage($parrContent, $pbooInit = false)
    {
        $larrmessage = self::$_arrMessage;
        if ($pbooInit) {
            $larrmessage = array();
        }
        array_push($larrmessage, $parrContent);
        self::$_arrMessage = $larrmessage;
    }

    /**
     * Gibt alle Nachrichten aus dem Zwischenspeicher zurück
     *
     * @return array
     * @static
     */
    public static function getMessage()
    {
        return self::$_arrMessage;
    }

    /**
     * Lösche alle Errors aus dem Zwischenspeicher
     *
     * @return bool
     * @static
     */
    public static function cleanMessage()
    {
        self::$_arrMessage = array();
        return true;
    }

    /**
     * Gibt ein Label zurück
     *
     * @param string $pstrName
     * @param array  $parrParse Dieser Text wird später vom Text ersetzt
     *
     * @return string
     * @static
     */
    public static function getLabel($pstrName, $parrParse = array())
    {
        $lstrName = $pstrName;
        if (isset(self::$_arrLabel[$lstrName])) {
            $lstrName = self::$_arrLabel[$lstrName];
        }
        if (count($parrParse) > 0) {
            foreach ($parrParse AS $lstrKey => $lstrValue) {
                $lstrName = preg_replace(
                    '/(%%'.$lstrKey.'%%)/', $lstrValue, $lstrName
                );
            }
        }
        return $lstrName;
    }

    /**
     * Gibt eine Konfiguration zurück
     *
     * @param $pstrName
     * @return string
     * @static
     */
    public static function getConfig($pstrName)
    {
        $lstrName = '';
        if (isset(self::$_arrConfig[$pstrName])) {
            $lstrName = self::$_arrConfig[$pstrName];
        }
        return $lstrName;
    }

    /**
     * Gibt den Basis-Link zurück
     *
     * @param bool $pbooWithAllGet Ob mit allen Get's ausser Bul, View
     * und Action
     *
     * @return string
     * @static
     * @access public
     */
    public static function getBaseLink($pbooWithAllGet = false)
    {
        $lstrLink  = '?strBul='.LIBCore::getGet('strBul');
        $lstrLink .= '&strView='.LIBCore::getGet('strView');
        if ($pbooWithAllGet) {
            $larrGet = LIBCore::getGet();
            foreach ($larrGet AS $lstrKey => $lstrValue) {
                if (    $lstrKey != 'strBul'
                    AND $lstrKey != 'strView'
                    AND $lstrKey != 'strAction'
                    AND $lstrKey != 'strAddRight'
                    AND $lstrKey != 'strRemoveRight') {
                    $lstrLink .= '&'.$lstrKey.'='.$lstrValue;
                }
            }
        }
        return $lstrLink;
    }

    /**
     * Registriert einen Benutzer
     *
     * @param integer $pnumUserID
     *
     * @static
     * @access public
     */
    public static function registerUser($pnumUserID)
    {
        $_SESSION['arrUser'] = array();
        $_SESSION['arrUser']['numUserID'] = $pnumUserID;

        $lstrQuery = 'SELECT cru.numUserID
                      FROM       core_df_rolluser AS cru
                      INNER JOIN core_df_roll AS cr
                        ON cru.numRollID = cr.numRollID
                      WHERE cru.numUserID = :numUserID
                        AND cr.strKuerzel = \'ADMIN\' ';
        LIBDB::query($lstrQuery, $_SESSION['arrUser']);
        $larrData = LIBDB::getData();
        if (count($larrData) > 0) {
            $_SESSION['arrUser']['numADMIN'] = 1;
        }
        self::loadRight();
    }

    /**
     * Setzt die Registration der Benutzer zurück
     *
     * @static
     */
    public static function unregisterUser()
    {
        unset($_SESSION['arrUser']);
    }

    /**
     * Simuliert einen Benutzer
     *
     * @access public
     * @static
     */
    public static function simulateUser()
    {
        if (    isset($_SESSION['arrUser']['numADMIN'])
            AND $_SESSION['arrUser']['numADMIN'] == 1
            AND LIBCore::getGet('numUserID') != '') {
            $_SESSION['arrOriginalUser'] = $_SESSION['arrUser'];
            self::registerUser(LIBCore::getGet('numUserID'));
        }
    }

    /**
     * Beendet das Simulieren eines Benutzers
     *
     * @access public
     * @static
     */
    public static function simulateUserEnd()
    {
        if (isset($_SESSION['arrOriginalUser'])) {
            $_SESSION['arrUser'] = $_SESSION['arrOriginalUser'];
            unset($_SESSION['arrOriginalUser']);
        }
        LIBCore::loadRight();
    }

    /**
     * Simuliert eine Rolle
     *
     * @access public
     * @static
     */
    public static function simulateRoll()
    {
        if (    isset($_SESSION['arrUser']['numADMIN'])
            AND $_SESSION['arrUser']['numADMIN'] == 1
            AND LIBCore::getGet('numRollID') != '') {
            $_SESSION['arrOriginalUser'] = $_SESSION['arrUser'];
            $_SESSION['arrUser']['numRollID'] = LIBCore::getGet('numRollID');
            $_SESSION['arrUser']['numRollRechte'] = 1;
            unset($_SESSION['arrUser']['numADMIN']);
        }
        self::loadRight();
    }

    /**
     * Beendet die Simulation einer Rolle
     *
     * @access public
     * @static
     */
    public static function simulateRollEnd()
    {
        if (isset($_SESSION['arrOriginalUser'])) {
            $_SESSION['arrUser'] = $_SESSION['arrOriginalUser'];
            unset($_SESSION['arrOriginalUser']);
        }
        self::loadRight();
    }

    /**
     * Validiert ein Array nach definierten Regeln
     *
     * @param array $parrData
     *
     * @return bool
     * @access private
     * @static
     */
    private static function _validateArray($parrData)
    {
        $larrData = $parrData;
        unset($parrData);
        if (!LIBValid::isArray($larrData)) {
            return false;
        } else {
            $lareturn = array();
            foreach ($larrData AS $lstrKey => $lobjValue) {
                $lstrTyp = substr($lstrKey, 0, 3);
                switch($lstrTyp) {
                    case 'int':
                        $lobjData = (integer) $lobjValue;
                        break;
                    case 'num':
                        $lobjData = (float) $lobjValue;
                        break;
                    case 'str':
                        $lobjData = (string) $lobjValue;
                        break;
                    default:
                        $lobjData = null;
                        break;
                }
                $lareturn[$lstrKey] = $lobjData;
            }
            return $lareturn;
        }
    }

    /**
     * Lädt alle Labels
     *
     * @return bool
     * @static
     * @access public
     */
    public static function loadLabel()
    {
        $larrLabels = array();
        $lbul = new BULCoreLabel();
        $larrdata = $lbul->getDbl()->getAll();
        foreach ($larrdata AS $larrValue) {
            $larrLabels[$larrValue['strName']] = $larrValue['strLabel'];
        }
        self::$_arrLabel = $larrLabels;
        return true;
    }

    /**
     * Lädt alle Konfigurationen
     *
     * @return bool
     * @static
     * @access public
     */
    public static function loadConfig()
    {
        $larrConfig = array();
        $lbul = new BULCoreConfig();
        $larrdata = $lbul->getDbl()->getAll();
        foreach ($larrdata AS $larrValue) {
            $larrConfig[$larrValue['strName']] = $larrValue['strValue'];
        }
        self::$_arrConfig = $larrConfig;
        return true;
    }

    /**
     * Lädt alle Rechte
     *
     * @return boolean
     * @access public
     * @static
     */
    public static function loadRight()
    {
        $larrSess = self::getSession('arrUser');
        $larrparam = array();
        if (isset($_SESSION['arrOriginalUser'])
            AND isset($larrSess['numRollID'])) {
            $larrparam['numRollID'] = (integer) $larrSess['numRollID'];
            $lstrQuery = 'SELECT cr.strCode,
                             bul.strName AS BULName,
                             IF(!ISNULL(crr.numRollID), true, false) AS booRight
                          FROM            core_df_right AS cr
                          LEFT OUTER JOIN core_df_rollright AS crr
                            ON cr.numRightID = crr.numRightID
                            AND crr.numRollID = :numRollID
                          LEFT OUTER JOIN core_df_bul AS bul
                            ON cr.numBulID = bul.numBulID
                          WHERE cr.numRightID > 0 ';
        } else {
            $larrparam['numUserID'] = 0;
            if (isset($larrSess['numUserID'])) {
                $larrparam['numUserID'] = (integer) $larrSess['numUserID'];
            }
            $lstrQuery = 'SELECT cr.strCode,
                             bul.strName AS BULName,
                             IF(!ISNULL(cru.numUserID), true, false) AS booRight
                      FROM            core_df_right AS cr
                      LEFT OUTER JOIN core_df_rollright AS crr
                        ON cr.numRightID = crr.numRightID
                      LEFT OUTER JOIN core_df_bul AS bul
                        ON cr.numBulID = bul.numBulID
                      LEFT OUTER JOIN core_df_rolluser AS cru
                        ON  crr.numRollID = cru.numRollID
                        AND cru.numUserID = :numUserID
                      WHERE cr.numRightID > 0 ';
        }
        LIBDB::query($lstrQuery, $larrparam);
        $larrdata = LIBDB::getData();
        $larrReturn = array();
        foreach ($larrdata AS $larrValue) {
            if ((integer) $larrValue['booRight'] == 1) {
                $lstrName = $larrValue['BULName'].'_'.$larrValue['strCode'];
                $larrReturn[$lstrName] = $larrValue['booRight'];
            }
        }
        self::$_arrRight = $larrReturn;
        return true;
    }

    /**
     * Macht ein Print-R inkl. pre
     *
     * @param $pstrData
     * @access public
     * @static
     */
    public static function print_r($pstrData)
    {
        echo '<pre>';
        print_r($pstrData);
        echo '</pre>';
    }

    /**
     * Gibt ein Recht zurück
     *
     * @param integer $pstrRight
     * @param string  $pstrBul
     * @param boolean $pbooAdmin
     * @return boolean
     * @access public
     * @static
     */
    public static function hasRight($pstrRight,
        $pstrBul = null,
        $pbooAdmin = true)
    {
        $larrSess = self::getSession('arrUser');
        $larrSessOriginal = self::getSession('arrOriginalUser');
        $lbooReturn = false;
        $lstrRight = $pstrRight;
        $lstrBul = self::getGet('strBul');
        if ($pstrBul != null) {
            $lstrBul = $pstrBul;
        }
        unset($pstrRight, $pstrBul);
        $lstrcheck = $lstrBul.'_'.$lstrRight;
        if (isset(self::$_arrRight[$lstrcheck])
            AND self::$_arrRight[$lstrcheck] == 1) {
            $lbooReturn = true;
        }
        if ($pbooAdmin) {
            if (isset($larrSess['numADMIN'])
                AND $larrSess['numADMIN'] == 1) {
                $lbooReturn = true;
            } else if (isset($larrSessOriginal['numADMIN'])
                AND $larrSessOriginal['numADMIN'] == 1
                    AND isset($larrSess['numRollRechte'])) {
                $lbooReturn = true;
            }
        }
        return $lbooReturn;
    }

    /**
     * Gibt zurück ob man das Recht zur Rechteänerung hat
     *
     * @return boolean
     * @access public
     * @static static
     */
    public static function hasRightEdit()
    {
        $lbreturn = false;
        if (isset($_SESSION['arrOriginalUser'])
            AND isset($_SESSION['arrOriginalUser']['numADMIN'])
            AND (integer) $_SESSION['arrOriginalUser']['numADMIN'] == 1) {
            $lbreturn = true;
        }
        return $lbreturn;
    }

    /**
     * Gibt einen Session-Teil zurück
     *
     * @param string $pstrName
     * @return mixed
     * @access public
     * @static
     */
    public static function getSession($pstrName = null)
    {
        if ($pstrName === null) {
            $larrReturn = $_SESSION;
        } else {
            if (isset($_SESSION[$pstrName])) {
                $larrReturn = $_SESSION[$pstrName];
            } else {
                $larrReturn = array();
            }

        }
        return $larrReturn;
    }

    /**
     * Override the Constructer
     *
     * @return LIBCore
     * @access public
     */
    public function __construct()
    {
        if (!self::$_objLIBCore) {
            self::$_objLIBCore = new self();
        }
        return self::$_objLIBCore;
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
