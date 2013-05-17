<?php

namespace racore\bul\router;

use racore\phplibs\core\LIBAutoload;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBBul;

/**
 * Hiermit wird der Korrekte Business-Layer ausgwählt
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       26.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Business-Layer
 * @subpackage  Router
 */
class BUL_router
{

    /**
     * Mit dieser Methode wird man mit dem Korrekten Business-Layer verbunden
     *
     * @return bool
     * @access public
     */
    public function route()
    {
        $lbreturn = false;
        $lstrBul = LIBCore::getGet('strBul');
        $lstrAction = LIBCore::getGet('strAction');
        if (!$lstrBul) {
            $lstrBul = 'CoreLogin';
        }
        $larrBul = preg_split('/(?=[A-Z])/', $lstrBul);
        $lstrClass =  'racore\\bul\\'.strtolower($larrBul[1]);
        $lstrClassDF = $lstrClass;
        $lstrClass .= '\\'.LIBCore::getGlobal('namensraum').'\\BUL'.$lstrBul;
        $lstrClassDF .= '\\df\\BUL'.$lstrBul;

        /** @var LIBBul $lbul  */
        $lbul = null;
        if (LIBAutoload::loadClass($lstrClass)) {
            $lbul = new $lstrClass;
            $lbul->route($lstrAction);
            $lbreturn = true;
        } else if (LIBAutoload::loadClass($lstrClassDF)) {
            $lbul = new $lstrClassDF;
            $lbul->route($lstrAction);
            $lbreturn = true;
        }
        return $lbreturn;
    }

}
