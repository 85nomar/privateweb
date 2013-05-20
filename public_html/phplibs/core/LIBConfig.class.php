<?php
namespace racore\phplibs\core;

/**
 * Datenbanklasse welche Statisch ist und Abfragen bei einer Datenbank macht
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       22.02.2013 22:34
 * @version     1.0.1
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 * @final
 */
final class LIBConfig
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
     * Enhält den Source-Server
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strSourceServer = '';

    /**
     * Enhält die Source-Datenbank
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strSourceDatabase = '';

    /**
     * Enthält den Source-User
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strSourceUser = '';

    /**
     * Enthält den Source-Password
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strSourcePassword = '';

    /**
     * Enhält die Instanz des Objektes DB
     *
     * @var null|LIBConfig
     * @access private
     * @static
     */
    private static $_objLIBConfig = null;

    /**
     * Enthält Informationen zum gewählen Mandanten
     *
     * @since 28.02.2013 06:05
     *
     * @var string
     * @access private
     * @static
     */
    private static $_strMandant = "";

    /**
     * Gibt den Server nach aussen ab
     *
     * @return string|bool
     * @access public
     * @static
     */
    public static function getServer()
    {
        return self::$_strServer;
    }

    /**
     * Gibt den Datenbanknamen zurück
     *
     * @return string
     * @access public
     * @static
     */
    public static function getDatabase()
    {
        return self::$_strDatabase;
    }

    /**
     * Gibt den Datenbank-Benutzernamen zurück
     *
     * @return string
     * @access public
     * @static
     */
    public static function getUser()
    {
        return self::$_strUser;
    }

    /**
     * Gibt das Datenbank-Passwort zurück
     *
     * @return string
     * @access public
     * @static
     */
    public static function getPassword()
    {
        return self::$_strPassword;
    }

    /**
     * Gibt den Mandanten zurück
     *
     * @since 28.02.2013 06:05
     *
     * @return string
     * @access public
     * @static
     */
    public static function getMandant()
    {
        return self::$_strMandant;
    }

    /**
     * Gibt den ServerNamen zurück
     *
     * @return string
     * @access public
     * @static
     */
    private static function _getServerName()
    {
        return (string) $_SERVER['SERVER_NAME'];
    }

    /**
     * Setzt die Konfiguration gemäss einer Datei
     *
     * @return bool
     * @access public
     * @static
     */
    public static function setConfigByConfigFile()
    {
        $lbooReturn = true;
        $lstrFilePath = "config/" . self::_getServerName().'.php';
        if (file_exists($lstrFilePath)) {
            $lstrFileData = file_get_contents($lstrFilePath);
            @eval($lstrFileData);
        } else {
            $lstrFilePath = "config/" . self::_getServerName();
            if (file_exists($lstrFilePath)) {
                $lstrFileData = file_get_contents($lstrFilePath);
                @eval($lstrFileData);
            } else {
                $lbooReturn = false;
            }
        }
        return $lbooReturn;
    }

    /**
     * Fügt einen IncludePath Hinzu
     *
     * @param $pstrPath
     *
     * @return bool
     * @access private
     * @static
     */
    private static function _addIncludePath($pstrPath)
    {
        $lbooReturn = true;
        set_include_path(get_include_path() . PATH_SEPARATOR . $pstrPath);
        return $lbooReturn;
    }

    /**
     * Override the Constructer
     *
     * @access public
     * @return LIBConfig
     * @access public
     */
    public function __construct()
    {
        if (!self::$_objLIBConfig) {
            self::$_objLIBConfig = new self();
            self::_addIncludePath("");
        }
        return self::$_objLIBConfig;
    }

    /**
     * Override the Cloner
     *
     * @access public
     *
     */
    public function __clone()
    {
    }


}
