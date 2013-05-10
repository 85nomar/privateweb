<?php
namespace racore\uil\json\df;
use racore\phplibs\core\LIBUil AS LIBUil;
use racore\phplibs\core\LIBValid;

/**
 * Hiermit soll der JSON-Return-Value ermöglicht werden
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       24.03.13
 * @version     1.00
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     User-Interface-Layer
 * @subpackage  JSON
 */
class UIL_json_df extends LIBUil
{

    /**
     * Gibt den Array im JSON-Format zurück
     *
     * @param array $parrData
     *
     * @return string|bool
     * @access public
     */
    public function show($parrData)
    {
        if (LIBValid::isArray($parrData)) {
            return json_encode($parrData);
        } else {
            return false;
        }
    }

}
