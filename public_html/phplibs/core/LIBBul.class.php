<?php
namespace racore\phplibs\core;
use racore\bul\core\df\BULCoreBul;
use racore\bul\core\df\BULCoreMenu;
use racore\bul\core\df\BULCoreRight;
use racore\bul\core\df\BULCoreRollRight;
use racore\phplibs\core\LIBValid AS LIBValid;
use racore\phplibs\core\LIBDbl AS LIBDbl;
use racore\phplibs\core\LIBUil AS LibUil;
use racore\uil\router\UIL_router;
use racore\phplibs\core\LIBFeldaufbau;

/**
 * Dies ist der Controller, welche die Verbindung zu den gewünschten Punkten
 * definiert
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       13.03.2013
 * @version     1.0.3
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     PHPLib
 * @subpackage  Core
 */
class LIBBul
{

    /**
     * Enthält die Datenbankverbindungs-Klasse
     *
     * @var null|LIBDbl
     * @access private
     */
    private $_dbl = null;

    /**
     * Enthält die Verbindung zum View/UI/Ausgabe
     * @var null|LIBUil
     */
    private $_uil = null;

    private $_strFormTemplate = '';

    private $_strListTemplate = '';

    public function getFormTemplate()
    {
        return $this->_strFormTemplate;
    }

    public function setFormTemplate($pstrTemplate)
    {
        $this->_strFormTemplate = $pstrTemplate;
    }

    public function getListTemplate()
    {
        return $this->_strListTemplate;
    }

    public function setListTemplate($pstrTemplate)
    {
        $this->_strListTemplate = $pstrTemplate;
    }

    /**
     * Bestimmt das Routing mit den erhaltenen Daten
     *
     * @param string $pstrData
     * @return bool
     * @access public
     */
    public function route($pstrData)
    {
        $lbooReturn = false;

        $lbul = new BULCoreBul();
        $lbulright = new BULCoreRight();
        $labul = $lbul->getDbl()->getWhere(
            'strName=\''.LIBCore::getGet('strBul').'\''
        );
        $laright = $lbulright->getDbl()->getWhere(
            'numBulID='.$labul[0]['numBulID'].' AND strCode=\'ZUGANG\''
        );

        /**
         * Rechteanpassung wenn benötigt
         */
        $lstrAddRight = LIBCore::getGet('strAddRight');
        $lstrRemoveRight = LIBCore::getGet('strRemoveRight');
        if ($lstrAddRight != '') {
            $this->_addRight($lstrAddRight);
        }
        if ($lstrRemoveRight != '') {
            $this->_removeRight($lstrRemoveRight);
        }

        /**
         * Zugangsberechtigt
         */
        if (!LIBCore::hasRight('ZUGANG')
            AND count($laright) > 0) {
            $pstrData = 'accessdenied';
        }

        $ldbl = $this->getDbl();
        if ($ldbl) {
            switch ($pstrData) {
                case 'insert':
                    $lbooReturn = $ldbl->insert(LIBCore::getPost());
                    break;
                case 'insertMask':
                    $lbooReturn = $this->_insertMask();
                    break;
                case 'listMask':
                    $lbooReturn = $this->_listMask();
                    break;
                case 'update':
                    $lbooReturn = $ldbl->update(LIBCore::getPost());
                    break;
                case 'updateMask':
                    $lbooReturn = $this->_updateMask(LIBCore::getGet());
                    break;
                case 'updateSort':
                    $lbooReturn = $ldbl->updateSort(LIBCore::getPost());
                    break;
                case 'delete':
                    $lbooReturn = $ldbl->delete(LIBCore::getGet());
                    $this->_listMask();
                    break;
                case 'simulate':
                    LIBCore::simulateUser();
                    $lbooReturn = $this->_listMask();
                    break;
                case 'endsimulate':
                    LIBCore::simulateUserEnd();
                    $lbooReturn = $this->_listMask();
                    break;
                case 'simulateroll':
                    LIBCore::simulateRoll();
                    $lbooReturn = $this->_listMask();
                    break;
                case 'endsimulateroll':
                    LIBCore::simulateRollEnd();
                    $lbooReturn = $this->_listMask();
                    break;
                case 'accessdenied':
                    $lbooReturn = $this->_accessDenied();
                    break;
                default:
                    $lbooReturn = $this->routeExtension($pstrData);
                    break;
            }


            if ($pstrData === 'insert') {
                if ($lbooReturn) {
                    $this->_listMask();
                } else {
                    $this->_insertMask(LIBCore::getPost());
                }
            }

            if ($pstrData === 'update'
                OR $pstrData === 'updateSort') {
                if ($lbooReturn) {
                    $this->_listMask();
                } else {
                    $this->_updateMask(LIBCore::getPost(), true);
                }
            }



        }
        return $lbooReturn;
    }

    /**
     * Dieses Routing ist, wenn es nicht im Standard ist, also es ein Spezial-
     * fall sein sollte
     *
     * @param string $pstrData
     * @return bool
     * @access public
     */
    public function routeExtension($pstrData)
    {
        switch($pstrData) {
            case '???':
                break;
            default:
                break;
        }
        return false;
    }

    /**
     * Gibt das Objekt LIBDbl zurück (oder false)
     *
     * @return bool|null|LIBDbl
     * @access public
     */
    public function getDbl()
    {
        if (is_null($this->_dbl)) {
            return false;
        } else {
            return $this->_dbl;
        }
    }

    /**
     * Gibt das Objekt LIBUil zurück (oder false)
     *
     * @return bool|null|LIBUil
     * @access public
     */
    public function getUil()
    {
        if (is_null($this->_uil)) {
            return false;
        } else {
            return $this->_uil;
        }

    }

    /**
     * Setzt den LIBDbl (Database-Layer)
     *
     * @param LIBDbl $pDbl
     * @return bool
     * @access public
     */
    public function setDbl($pDbl)
    {
        if (is_null($pDbl) OR !($pDbl instanceof LIBDbl)) {
            return false;
        } else {
            $this->_dbl = $pDbl;
            return true;
        }
    }

    /**
     * Setzt den LIBUil (UserInterface-Layer)
     *
     * @param $pUil
     * @return bool
     * @access public
     */
    public function setUil($pUil)
    {
        if (is_null($pUil) OR !($pUil instanceof LIBUil)) {
            return false;
        } else {
            $this->_uil = $pUil;
            return true;
        }
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
            $larrFab = $lfab->getFields();
            foreach ($larrFab AS $lstrKey => $larrField) {
                unset($larrField);
                $larrDBLData[$lstrKey] = '';
            }
            $larrDBLData = $this->_loadArrayData($larrDBLData);
        }
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
     * Bereitet die List-Maske vor
     *
     * @return bool
     * @access protected
     */
    protected function _listMask()
    {

        $lbooReturn = false;
        $luilRouter = new UIL_router();
        $larrData = array(
            'strTyp' => 'list',
            'arrData' => $this->_loadArrayDataForList(
                $this->getDbl()->getAll()
            ),
            'arrBreadcrumb' => $this->_getBreadCrumbListMask()
        );
        $larrDataTwo = array(
            'strTemplate' => $this->getListTemplate(),
            'arrContent' => $larrData,
            'arrNavigation' => $this->_getNavigation()
        );
        $luilRouter->setDbl($this->getDbl());
        $luilRouter->route($larrDataTwo);
        return $lbooReturn;
    }

    protected function _accessDenied()
    {
        $lbooReturn = false;
        $luilRouter = new UIL_router();
        $larrData = array(
            'strTyp' => 'accessdenied',
            'arrData' => array(),
            'arrBreadcrumb' => $this->_getBreadCrumbListMask()
        );
        $larrDataTwo = array(
            'arrContent' => $larrData,
            'arrNavigation' => $this->_getNavigation()
        );
        $luilRouter->setDbl($this->getDbl());
        $luilRouter->route($larrDataTwo);
        return $lbooReturn;
    }

    /**
     * Ändert die Felder mit einem Select für die Anzeige
     *
     * @param $parrData
     * @return array
     * @access protected
     */
    protected function _loadArrayDataForList($parrData)
    {
        return $parrData;
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
            foreach ($larrFields AS $lstrKey => $larrValue) {
                unset($larrValue);
                if (isset($larrData[$lstrKey])) {
                    array_push($larrWhere, $lstrKey .' = '.$larrData[$lstrKey]);
                }
            }
            $larrDBLData = $this->_dbl->getWhere(implode($larrWhere, ' AND '));
            if (count($larrDBLData) > 0) {
                $larrDBLData[0] = $this->_loadArrayData($larrDBLData[0]);
                $larrDBLData = $larrDBLData[0];
            } else {
                $larrDBLData = $this->_loadArrayData($larrDBLData);
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

    /**
     * Veraltetes aufbereiten vom BreadCrumb
     *
     * @return array
     */
    protected function _getBreadcrumb()
    {
        trigger_error('_getBreadCrumb wird nicht mehr verwendet, bitte ändern');
        $larrReturn = array();
        $larrData = array();
        $larrData['link'] = '';
        $larrData['label'] = LIBCore::getGet('strBul');
        array_push($larrReturn, $larrData);
        return $larrReturn;
    }

    /**
     * Aufbereitung des Breadcrumbs für eine Liste
     *
     * @return array
     * @access protected
     */
    protected function _getBreadCrumbListMask()
    {
        $larrReturn = array();
        $larrData = array();
        $larrData['link'] = '';
        $larrData['label'] = LIBCore::getGet('strBul');
        array_push($larrReturn, $larrData);
        return $larrReturn;
    }

    /**
     * Aufbereitung des Breadcrumbs für die Insert-Maske
     *
     * @return array
     * @access protected
     */
    protected function _getBreadCrumbInsertMask()
    {
        $lstrLink  = LIBCore::getBaseLink();
        $lstrLink .= '&strAction=listMask';
        $larrBreadcrumb = array();

        $larrData = array();
        $larrData['link'] = $lstrLink;
        $larrData['label'] = LIBCore::getGet('strBul');
        array_push($larrBreadcrumb, $larrData);

        $larrData = array();
        $larrData['link'] = '';
        $larrData['label'] = LIBCore::getLabel('ERSTELLEN');
        array_push($larrBreadcrumb, $larrData);
        return $larrBreadcrumb;
    }

    /**
     * Aufbereitung des Breadcrumbs für die Update-Maske
     *
     * @return array
     * @access protected
     */
    protected function _getBreadCrumbUpdateMask()
    {
        $lstrLink  = LIBCore::getBaseLink();
        $lstrLink .= '&strAction=listMask';

        $larrBreadcrumb = array();
        $larrData = array();
        $larrData['link'] = $lstrLink;
        $larrData['label'] = LIBCore::getGet('strBul');
        array_push($larrBreadcrumb, $larrData);

        $larrData = array();
        $larrData['link'] = '';
        $larrData['label'] = LIBCore::getLabel('BEARBEITEN');

        array_push($larrBreadcrumb, $larrData);
        return $larrBreadcrumb;
    }

    /**
     * Platzhalter um Daten zu übererbeiten
     *
     * @param array $parrData
     * @return array
     * @access protected
     */
    protected function _loadArrayData($parrData = array())
    {
        return $parrData;
    }

    /**
     * Gibt das Navigations-Array zurück
     *
     * @access protected
     */
    protected function _getNavigation($pnumParentMenuID = 0)
    {
        $lbulbul = new BULCoreBul();
        $lbul = new BULCoreMenu();
        $lbul->getDbl()->setOrderBy('numParentMenuID, numOrder');
        $lcwhere = 'numParentMenuID = '.$pnumParentMenuID;
        $larrData = $lbul->getDbl()->getWhere($lcwhere);
        $larrReturn = array();
        foreach ($larrData AS $larrValue) {
            $larrChild = $this->_getNavigation($larrValue['numMenuID']);
            $larrLink = array();
            $larrLink['link'] = $larrValue['strLink'];
            $larrLink['label'] = $larrValue['strName'];
            $larrLink['icon'] = $larrValue['strIcon'];
            $larrLink['child'] = $larrChild;

            if ((integer) $larrValue['numBulID'] != 0) {
                $lcwherebul = 'numBulID='.(integer) $larrValue['numBulID'];
                $larrDataBul = $lbulbul->getDbl()->getWhere($lcwherebul);
                if ($larrValue['strRight'] !== '') {
                    $lbulRight = new BULCoreRight();
                    $larrRight = $lbulRight->getDbl()->getWhere(
                        'numBulID='.$larrDataBul[0]['numBulID'].'
                         AND strCode=\''.$larrValue['strRight'].'\''
                    );
                    if (count($larrRight)) {
                        $larrLink['hasright'] = LIBCore::hasRight(
                            $larrValue['strRight'],
                            $larrDataBul[0]['strName'],
                            false
                        );
                        $larrLink['hasrightedit'] = LIBCore::hasRightEdit();
                        $lstrBaseLink = LIBCore::getBaseLink('true');
                        $lstrBaseLink .= '&strAction=';
                        $lstrBaseLink .= LIBCore::getGet('strAction');
                        $lstrRemoveLink = $lstrBaseLink;
                        $lstrRemoveLink .= '&strRemoveRight=ZUGANG';
                        $lstrAddLink = $lstrBaseLink.'&strAddRight=ZUGANG';
                        $lstrBulRight = '&strBulRight=';
                        $lstrBulRight .= $larrDataBul[0]['strName'];
                        $larrLink['strRightAddLink'] =
                            $lstrAddLink.$lstrBulRight;
                        $larrLink['strRightRemoveLink'] =
                            $lstrRemoveLink.$lstrBulRight;
                    }
                }
                if (LIBCore::hasRight(
                    $larrValue['strRight'], $larrDataBul[0]['strName']
                )) {
                    array_push($larrReturn, $larrLink);
                }
            } else {
                array_push($larrReturn, $larrLink);
            }
        }
        return $larrReturn;
    }

    private function _addRight($pstrRight)
    {
        $larrSess = LIBCore::getSession('arrUser');
        $lbulBul = new BULCoreBul();
        $lstrBul = LIBCore::getGet('strBul');
        if (LIBCore::getGet('strBulRight') != '') {
            $lstrBul = LIBCore::getGet('strBulRight');
        }
        $larrBul = $lbulBul->getDbl()->getWhere(
            'strName=\''.$lstrBul.'\''
        );
        $lnumBulID = $larrBul[0]['numBulID'];
        $lbulRight = new BULCoreRight();
        $larrRight = $lbulRight->getDbl()->getWhere(
            'strCode=\''.$pstrRight.'\' AND numBulID='.$lnumBulID
        );
        $lnumRightID = $larrRight[0]['numRightID'];
        $lbulRollRight = new BULCoreRollRight();
        $larrData = array();
        $larrData['numRollRightID'] = 0;
        $larrData['numRightID'] = $lnumRightID;
        $larrData['numRollID'] = $larrSess['numRollID'];
        $lbooReturn = $lbulRollRight->getDbl()->insert($larrData);
        $larrMessages = LIBCore::getMessage();
        array_shift($larrMessages);
        $larrSuccess = array();
        $larrSuccess['type'] = 'success';
        $larrSuccess['strLabel'] = LIBCore::getLabel('RECHTEAENDERUNGSUCCESS');
        LIBCore::setMessage($larrSuccess, true);
        foreach ($larrMessages AS $larrValue) {
            LIBCore::setMessage($larrValue);
        }
        LIBCore::loadRight();
        $lfab = $this->getDbl()->getFeldaufbau();
        $lfab->checkRight();
        $this->getDbl()->setFeldaufbau($lfab);
        return $lbooReturn;
    }

    private function _removeRight($pstrRight)
    {
        $larrSess = LIBCore::getSession('arrUser');
        $lbulBul = new BULCoreBul();
        $lstrBul = LIBCore::getGet('strBul');
        if (LIBCore::getGet('strBulRight') != '') {
            $lstrBul = LIBCore::getGet('strBulRight');
        }
        $larrBul = $lbulBul->getDbl()->getWhere(
            'strName=\''.$lstrBul.'\''
        );
        $lnumBulID = $larrBul[0]['numBulID'];
        $lbulRight = new BULCoreRight();
        $larrRight = $lbulRight->getDbl()->getWhere(
            'strCode=\''.$pstrRight.'\' AND numBulID='.$lnumBulID
        );
        $lnumRightID = $larrRight[0]['numRightID'];

        $lbulRollRight = new BULCoreRollRight();
        $larrRollRight = $lbulRollRight->getDbl()->getWhere(
            'numRightID='.$lnumRightID.' AND numRollID='.$larrSess['numRollID']
        );
        $lnumRollRightID = $larrRollRight[0]['numRollRightID'];
        $larrData = array();
        $larrData['numRollRightID'] = $lnumRollRightID;
        $lbooReturn = $lbulRollRight->getDbl()->delete($larrData);
        $larrMessages = LIBCore::getMessage();
        array_shift($larrMessages);
        $larrSuccess = array();
        $larrSuccess['type'] = 'success';
        $larrSuccess['strLabel'] = LIBCore::getLabel('RECHTEAENDERUNGSUCCESS');
        LIBCore::setMessage($larrSuccess, true);
        foreach ($larrMessages AS $larrValue) {
            LIBCore::setMessage($larrValue);
        }
        LIBCore::loadRight();
        $lfab = $this->getDbl()->getFeldaufbau();
        $lfab->checkRight();
        $this->getDbl()->setFeldaufbau($lfab);
        return $lbooReturn;
    }

    protected function _prepareDataForView($parrData)
    {
        $larrData = $parrData;
        unset($parrData);
        $lfab = $this->getDbl()->getFeldaufbau();
        $larrFab = $lfab->getFields();
        /** @var LIBFeldaufbauFeld $lfeld */
        foreach ($larrFab AS $lstrKey => $lfeld) {
            $lstrHelptext = $lfeld->strHelptext;
            if ($lstrHelptext == '') {
                $larr = array();
                $larr['numLength'] = $lfeld->numLength;
                $lstrHelptext = LIBCore::getLabel('MAXLENGTHFROM', $larr);
            }
            $larrData[$lstrKey.'Helptext'] = $lstrHelptext;
            $larrData[$lstrKey.'MaxLength'] = $lfeld->numLength;
        }
        return $larrData;
    }

}
