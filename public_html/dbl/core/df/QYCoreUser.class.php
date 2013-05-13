<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Query User
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       11.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class QYCoreUser extends DBLCoreUser
{

    /**
     * Führt einen Insert Befehl auf die Datenbank aus
     *
     * @param array $parrData
     *
     * @return boolean
     * @access public
     */
    public function insert($parrData)
    {
        $lbooReturn = false;
        $larrData = $parrData;
        $larrUser = $parrData;
        unset($larrUser['arrRoll']);
        unset($parrData);
        if (parent::insert($larrUser)) {
            $larrUser = $this->getWhere(
                'strName = \''.$larrData['strName'].'\''
            );
            if (count($larrUser) == 1) {
                $larrUser = $larrUser[0];
                $lnumUserID = $larrUser['numUserID'];
                $ldblRollUser = new DBLCoreRollUser();
                $larrRoll =  $larrData['arrRoll'];
                foreach ($larrRoll AS $lnumRollID) {
                    $larrDataRoll = array();
                    $larrDataRoll['numRollUserID'] = 0;
                    $larrDataRoll['numRollID'] = $lnumRollID;
                    $larrDataRoll['numUserID'] = $lnumUserID;
                    $ldblRollUser->insert($larrDataRoll);
                }
            }
            $lbooReturn = true;
        }
        return $lbooReturn;
    }


}
