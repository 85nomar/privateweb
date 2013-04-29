<?php
namespace racore\bul\core\df;

use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBFeldaufbau;
use racore\uil\router\UIL_router;

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
class BULCoreFeldaufbauFeld extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new LIBDbl();

        // @TODO
        $lfab = new LIBFeldaufbau();
        $lfab->setFeldaufbauByName('BULCoreFeldaufbauFeld');
        $ldbl->setTablename('core_df_feldaufbau_feld');
        $ldbl->setOrderBy('numOrder ASC, strLabel ASC');
        $ldbl->setFeldaufbau($lfab);
        $this->setDbl($ldbl);
    }

    /**
     * Dies Zeigt die Liste vom FeldaufbauFeld an
     *
     * @return bool
     * @access protected
     */
    protected function _listMask()
    {
        $lbooReturn = false;
        $luilRouter = new UIL_router();
        $ldbl = $this->getDbl();
        $lnumFeldaufbauID = (integer) LIBCore::getGet('numFeldaufbauID');
        if ($lnumFeldaufbauID === 0) {
            $lnumFeldaufbauID = LIBCore::getPOST('numFeldaufbauID');
        }
        $lstrWhere = 'numFeldaufbauID='.$lnumFeldaufbauID;


        $larrData = array(
            'strTyp' => 'list',
            'arrData' => $ldbl->getWhere($lstrWhere),
            'arrBreadcrumb' => $this->_getBreadCrumbListMask()
        );
        $larrDatatwo = array(
            'arrContent' => $larrData,
            'arrNavigation' => $this->_getNavigation()
        );
        $luilRouter->setDbl($ldbl);
        $luilRouter->route($larrDatatwo);
        return $lbooReturn;
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
        $ldblValid = new LIBDbl();
        $ldblValid->setTablename('core_df_validtyp');
        $lstrSelected = 0;
        if (isset($parrData['numValidTypID'])) {
            $lstrSelected = $parrData['numValidTypID'];
        }
        $larrData['numValidTypID'] = $ldblValid->getAllCodeAndValue(
            'numValidTypID', 'strName', $lstrSelected
        );
        return $larrData;
    }


}
