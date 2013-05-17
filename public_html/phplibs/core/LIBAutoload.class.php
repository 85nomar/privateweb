<?php
namespace racore\phplibs\core;

/**
 * Datenbanklasse welche Statisch ist und Abfragen bei einer Datenbank macht
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       22.02.2013 22:34
 * @version     1.0.0
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 * @final
 */
final class LIBAutoload
{

    /**
     * @param $pstrClassname
     *
     * @return bool
     * @access public
     * @static
     */
    public static function loadClass($pstrClassname)
    {
        $lbooReturn = true;
        $lstrClassname = str_replace("racore\\", "", $pstrClassname);
        $lstrClassname = str_replace("\\", "/", $lstrClassname);
        $lstrClassname .= ".class.php";
        $lstrFileName = DOCUMENT_ROOT . "/" . $lstrClassname;
        if (file_exists($lstrFileName)) {
            require_once $lstrFileName;
        } else {
            $lstrFileName = DOCUMENT_ROOT_TEST . "/" . $lstrClassname;
            if (file_exists($lstrFileName)) {
                require_once $lstrFileName;
            } else {
                $lbooReturn = false;
            }
        }
        return $lbooReturn;
    }

}
