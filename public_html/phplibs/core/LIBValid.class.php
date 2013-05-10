<?php
namespace racore\phplibs\core;

/**
 * Mit dieser Klasse werden alle Validierungen durchgeführt
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       07.03.2013
 * @version     1.0.0
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 */
class LIBValid
{

    /**
     * Sagt ob es sich um ein Array handelt
     *
     * @param $parr
     *
     * @return bool
     * @access public
     * @static
     */
    public static function isArray($parr)
    {
        if (!is_array($parr) OR is_null($parr)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Sagt ob es sich um einen Integer handelt
     *
     * @param $pint
     *
     * @return bool
     * @access public
     * @static
     */
    public static function isInteger($pint)
    {
        if (!is_integer($pint)
            OR is_null($pint)
            OR !self::isRegex('^[0-9]+$', (string)$pint)
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Sagt ob es sich um eine Nummer handelt
     *
     * @param $pnum
     *
     * @return bool
     * @access public
     * @static
     */
    public static function isNumber($pnum)
    {
        if (!is_numeric($pnum)
            OR is_null($pnum)
            OR !self::isRegex('^[0-9.]+$', (string)$pnum)
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Sagt ob es dem ebenfalls übergebenen Regex handelt
     *
     * @param $pstrRegex
     * @param $pstrText
     *
     * @return bool
     * @static
     */
    public static function isRegex($pstrRegex, $pstrText)
    {
        if (is_string($pstrRegex)
            AND is_string($pstrText)
                AND !is_null($pstrRegex)
                    AND !is_null($pstrText)
                        AND preg_match("/" . $pstrRegex . "/", $pstrText)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sagt ob es sich um einen String handelt
     *
     * @param string $pstr
     *
     * @return bool
     * @access public
     * @static
     */
    public static function isString($pstr)
    {
        if (!is_string($pstr)
            OR is_null($pstr)
            OR !self::isRegex('(\w|[ÄäÖöÜüÀÁáÂâÈèÉéÊêÙùÚúßÇç])+', $pstr)
        ) {
            return false;
        } else {
            return true;
        }
    }


}
