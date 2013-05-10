<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreMenu;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBFeldaufbau;

/**
 * Das ist der Business-Layer vom Feldaufbau
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       30.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreMenu extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLCoreMenu();
        $this->setDbl($ldbl);
        $this->setListTemplate('menu_list.tpl');
        $this->setFormTemplate('menu_form.tpl');

        /*
        $ldbl = new LIBDbl();

        // @TODO
        $lfab = new LIBFeldaufbau();
        $lfab->setFeldaufbauByName('BULCoreMenu');
        $ldbl->setTablename('core_df_menu');
        $ldbl->setOrderBy('numOrder');
        $ldbl->setFeldaufbau($lfab);
        $this->setDbl($ldbl);
        */
    }

    /**
     * Holt eine Liste mit den Menu-Punkten wo ausgewählt werden können
     *
     * @param array $parrData
     * @return array
     * @access protected
     */
    protected function _loadArrayData($parrData = array())
    {
        $larrData = $parrData;
        $ldblValid = new LIBDbl();
        $ldblValid->setTablename('core_df_menu');
        $ldblBul = new LIBDbl();
        $ldblBul->setTablename('core_df_bul');
        $lstrSelected = 0;
        if (isset($parrData['numParentMenuID'])) {
            $lstrSelected = $parrData['numParentMenuID'];
        }
        $larrData['numParentMenuID'] = $ldblValid->getAllCodeAndValue(
            'numMenuID', 'strName', $lstrSelected
        );
        $larrZusatz = array(
            'code' => 0,
            'value' => LIBCore::getLabel('KEINPARENT'),
            'selected' => 0
        );
        array_unshift($larrData['numParentMenuID'], $larrZusatz);


        $lstrSelected = -1;
        if (isset($parrData['numBulID'])) {
            $lstrSelected = $parrData['numBulID'];
        }
        $larrData['numBulID'] = $ldblBul->getAllCodeAndValue(
            'numBulID', 'strName', $lstrSelected
        );
        $larrZusatz = array(
            'code' => 0,
            'value' => LIBCore::getLabel('KEINBUL'),
            'selected' => 0
        );
        array_unshift($larrData['numBulID'], $larrZusatz);

        return $larrData;
    }


}
