<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\QYCoreRoll;
use racore\dbl\core\df\QYCoreUser;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBFeldaufbau;
use racore\phplibs\core\LIBFeldaufbauFeld;
use racore\uil\router\UIL_router;

/**
 * Das ist der Business-Layer vom User
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       30.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreUser extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new QYCoreUser();
        $this->setDbl($ldbl);
        $this->setListTemplate('user_list.tpl');
        $this->setFormTemplate('user_form.tpl');
    }

    /**
     * @return bool|null|QYCoreUser
     */
    public function getDbl()
    {
        return parent::getDbl();
    }

    /**
     * Bereitet die INSERT-Maske vor
     *
     * @param $parrData
     *
     * @return bool
     * @access protected
     */
    protected function _insertMask($parrData = array())
    {
        $lbooReturn = false;
        $luilRouter = new UIL_router();
        if (count($parrData) > 0) {
            $larrDBLData = $parrData;
        } else {
            $larrDBLData = array();
            $lfab = $this->getDbl()->getFeldaufbau();
            /** @var  $larrFab */
            $larrFab = $lfab->getFields();
            /** @var LIBFeldaufbauFeld $larrField */
            foreach ($larrFab AS $lstrKey => $larrField) {
                unset($larrField);
                $larrDBLData[$lstrKey] = '';
            }
            $larrDBLData = $this->_loadArrayData($larrDBLData);
        }
        $ldblroll = new QYCoreRoll();
        $larrDBLData['larrRollen'] = $ldblroll->getRollenForUsers();

        $lstrLink = LIBCore::getBaseLink(true).'&strAction=';
        $larrBreadcrumb = array();
        $larrData = array();
        $larrData['link'] = $lstrLink.'listMask';
        $larrData['label'] = LIBCore::getGet('strBul');
        array_push($larrBreadcrumb, $larrData);
        $larrData = array();
        $larrData['link'] = '';
        $larrData['label'] = LIBCore::getLabel('ERSTELLEN');
        array_push($larrBreadcrumb, $larrData);
        $larrDBLData = $this->_prepareDataForView($larrDBLData);
        $larrData = array(
            'strTyp' => 'form',
            'arrData' => $larrDBLData,
            'arrBreadcrumb' => $this->_getBreadCrumbInsertMask(),
            'strAction' => LIBCore::getBaseLink(true).'&strAction=insert'
        );
        $larrDataTwo = array(
            'strTemplate' => $this->getFormTemplate(),
            'arrContent' => $larrData,
            'arrNavigation' => $this->_getNavigation(),
            'strAction' => LIBCore::getBaseLink(true).'&strAction=insert'
        );
        $luilRouter->setDbl($this->getDbl());
        $luilRouter->route($larrDataTwo);

        return $lbooReturn;
    }

    /**
     * Bereitet die UPDATE-Maske vor
     *
     * @param array $parrData
     * @param bool  $pbooOnError
     * @return bool
     * @access protected
     */
    protected function _updateMask($parrData, $pbooOnError = false)
    {
        $larrData = $parrData;
        unset($parrData);
        $lbooReturn = false;
        $luilRouter = new UIL_router();
        $ldbl = $this->getDbl();
        /** @var LIBFeldaufbau $lfab  */
        $lfab = $ldbl->getFeldaufbau();
        $larrFields = $lfab->getFields();
        if (!$pbooOnError) {
            $larrWhere = array();
            /** @var LIBFeldaufbauFeld $larrValue */
            foreach ($larrFields AS $lstrKey => $larrValue) {
                if (isset($larrData[$lstrKey])) {
                    array_push($larrWhere, $lstrKey .' = '.$larrData[$lstrKey]);
                }
            }
            $larrDBLData = $this->getDbl()->getWhere(
                implode($larrWhere, ' AND ')
            );
            if (count($larrDBLData) > 0) {
                $larrDBLData[0] = $this->_loadArrayData($larrDBLData[0]);
                $larrDBLData = $larrDBLData[0];
            } else {
                $larrDBLData = $this->_loadArrayData($larrDBLData);
            }
            $ldblroll = new QYCoreRoll();
            $larrDBLData['larrRollen'] = $ldblroll->getRollenForUsers(
                $larrDBLData['numUserID']
            );
            /** @var LIBFeldaufbauFeld $larrValue */
            foreach ($larrFields AS $lstrKey => $larrValue) {
                $larrDBLData[$lstrKey.'Helptext'] = $larrValue->strHelptext;
            }
            $lstrLink = LIBCore::getBaseLink(true).'&strAction=';
            $larrBreadcrumb = array();
            $larrData = array();
            $larrData['link'] = $lstrLink.'listMask';
            $larrData['label'] = LIBCore::getGet('strBul');
            array_push($larrBreadcrumb, $larrData);
            $larrData = array();
            $larrData['link'] = '';
            $larrData['label'] = LIBCore::getLabel('BEARBEITEN');
            array_push($larrBreadcrumb, $larrData);
            $larrDBLData = $this->_prepareDataForView($larrDBLData);
            $larrData = array(
                'strTyp' => 'form',
                'arrData' => $larrDBLData,
                'arrBreadcrumb' => $this->_getBreadCrumbUpdateMask(),
                'strAction' => LIBCore::getBaseLink(true).'&strAction=update'
            );
        } else {
            $larrData = array(
                'strTyp' => 'form',
                'arrData' => array($larrData),
                'arrBreadcrumb' => $this->_getBreadcrumb(),
                'strAction' => LIBCore::getBaseLink(true).'&strAction=update'
            );
        }
        $larrDataTwo = array(
            'strTemplate' => $this->getFormTemplate(),
            'arrContent' => $larrData,
            'arrNavigation' => $this->_getNavigation(),
            'strAction' => LIBCore::getBaseLink(true).'&strAction=update'
        );
        $luilRouter->setDbl($this->getDbl());
        $luilRouter->route($larrDataTwo);
        return $lbooReturn;
    }

}
