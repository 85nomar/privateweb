<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBValid;

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
                if (isset($larrData['arrRoll'])) {
                    $larrRoll =  $larrData['arrRoll'];
                    foreach ($larrRoll AS $lnumRollID) {
                        $larrDataRoll = array();
                        $larrDataRoll['numRollUserID'] = 0;
                        $larrDataRoll['numRollID'] = $lnumRollID;
                        $larrDataRoll['numUserID'] = $lnumUserID;
                        $ldblRollUser->insert($larrDataRoll);
                        LIBCore::cleanMessage(-1);
                    }
                }
            }
            $lbooReturn = true;
        }
        return $lbooReturn;
    }

    /**
     * Führt ein Update durch
     *
     * @param $parrData
     *
     * @return boolean
     * @access public
     */
    public function update($parrData)
    {
        $lbooReturn = false;
        $larrData = $parrData;
        $larrUser = $parrData;
        unset($larrUser['arrRoll']);
        unset($parrData);

        if (parent::update($larrUser)) {
            $lnumUserID = $larrData['numUserID'];
            $ldblRollUser = new DBLCoreRollUser();
            $ldblRollUser->deleteWhere('numUserID = ' . $lnumUserID);
            LIBCore::cleanMessage(-1);
            if (isset($larrData['arrRoll'])) {
                $larrRoll =  $larrData['arrRoll'];
                foreach ($larrRoll AS $lnumRollID) {
                    $larrDataRoll = array();
                    $larrDataRoll['numRollUserID'] = 0;
                    $larrDataRoll['numRollID'] = $lnumRollID;
                    $larrDataRoll['numUserID'] = $lnumUserID;
                    $ldblRollUser->insert($larrDataRoll);
                    LIBCore::cleanMessage(-1);
                }
            }
            $lbooReturn = true;
        }
        return $lbooReturn;
    }


    /**
     * Für einen Delete aus
     *
     * @param $parrData
     *
     * @return boolean
     * @access public
     */
    public function delete($parrData)
    {
        $larrData = $parrData;
        unset($parrData);
        if (LIBValid::isArray($larrData)) {
            if (isset($larrData['numUserID'])) {
                $lnumUserID = (integer) $larrData['numUserID'];
                $ldblRollUser = new DBLCoreRollUser();
                if ($ldblRollUser->deleteWhere('numUserID = ' . $lnumUserID)) {
                    $lbooReturn = parent::delete($larrData);
                    LIBCore::cleanMessage(-1);
                    return $lbooReturn;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


}
