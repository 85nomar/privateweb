<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreLabel;
use racore\phplibs\core\LIBBul;
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
class BULCoreLabel extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLCoreLabel();
        $this->setDbl($ldbl);
        $this->setListTemplate('label_list.tpl');
        $this->setFormTemplate('label_form.tpl');

        /*
        $ldbl = new LIBDbl();

        // @TODO
        $lfab = new LIBFeldaufbau();
        $lfab->setFeldaufbauByName('BULCoreLabel');
        $ldbl->setTablename('core_df_label');
        $ldbl->setFeldaufbau($lfab);
        $this->setDbl($ldbl);
        */
    }

    /**
     * Holt eine Liste mit Business-Layer Daten für die Form-Maske
     *
     * @param array $parrData
     * @return array
     * @access protected
     */
    protected function _loadArrayData($parrData = array())
    {
        $larrData = $parrData;
        $ldblValid = new LIBDbl();
        $ldblValid->setTablename('core_df_bul');
        $lstrSelected = 0;
        if (isset($parrData['numBulID'])) {
            $lstrSelected = $parrData['numBulID'];
        }
        $larrData['numBulID'] = $ldblValid->getAllCodeAndValue(
            'numBulID', 'strName', $lstrSelected
        );
        return $larrData;
    }

}
