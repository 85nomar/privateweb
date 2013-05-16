<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreRollRight;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBFeldaufbau;

/**
 * Das ist der Business-Layer von der Rollenrechte
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       22.04.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreRollRight extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLCoreRollRight();
        $this->setDbl($ldbl);
    }

    /**
     * Holt ein Array von Daten für die Form-Maske
     *
     * @param array $parrData
     * @return array
     * @access protected
     */
    protected function _loadArrayData($parrData = array())
    {
        $larrData = $parrData;
        $ldblroll = new LIBDbl();
        $ldblroll->setTablename('core_roll');
        $ldblright = new LIBDbl();
        $ldblright->setTablename('core_right');

        // Rolle
        $lstrSelected = 0;
        if (isset($parrData['numRollID'])) {
            $lstrSelected = $parrData['numRollID'];
        }
        $larrData['numRollID'] = $ldblroll->getAllCodeAndValue(
            'numRollID', 'strName', $lstrSelected
        );

        // Recht
        $lstrSelected = 0;
        if (isset($parrData['numRightID'])) {
            $lstrSelected = $parrData['numRightID'];
        }
        $larrData['numRightID'] = $ldblright->getAllCodeAndValue(
            'numRightID', 'strCode, strName', $lstrSelected
        );

        return $larrData;
    }

    /**
     * Ändert die Felder mit einem Select für die Anzeige
     *
     * @param array $parrData
     * @return array
     * @access protected
     */
    protected function _loadArrayDataForList($parrData)
    {
        $ldbl = new LIBDbl();
        $ldbl->setTablename('core_bul');
        foreach ($parrData AS $lstrKey => $larrData) {
            if (isset($larrData['numBulID'])) {
                $larr = $ldbl->getWhere('numBulID = '.$larrData['numBulID']);
                $parrData[$lstrKey]['numBulID'] = $larr[0]['strName'];
            }
        }
        $ldbl->setTablename('core_right');
        foreach ($parrData AS $lstrKey => $larrData) {
            if (isset($larrData['numRightID'])) {
                $larr = $ldbl->getWhere(
                    'numRightID = '.$larrData['numRightID']
                );
                $parrData[$lstrKey]['numRightID'] = $larr[0]['strName'];
            }
        }
        return $parrData;
    }

}
