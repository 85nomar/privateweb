<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreUser;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBFeldaufbau;
use racore\uil\router\UIL_router;

/**
 * Das ist der Business-Layer vom Login
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       26.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreLogin extends LIBBul
{
    /**
     * Dieses Routing ist Speziell und gehört nicht zum Standard
     *
     * @param string $pstrData
     * @return bool
     * @access public
     */
    public function routeExtension($pstrData)
    {
        $lbooReturn = false;
        $luilRouter = new UIL_router();
        $luilRouter->setDbl($this->getDbl());
        switch($pstrData) {
            case 'login':
                /** @var DBLCoreUser $ldbl  */
                $ldbl = $this->getDbl();
                $lstrUser = LIBCore::getPost('strName');
                $lstrPassword = LIBCore::getPost('strPassword');
                if ($ldbl->validateLogin($lstrUser, $lstrPassword)) {
                    $lstrWhere = '    strName = \''.$lstrUser.'\'
                                  AND strPassword = \''.$lstrPassword.'\' ';
                    $larrData = $ldbl->getWhere($lstrWhere);
                    LIBCore::registerUser($larrData[0]['numUserID']);
                    $larrSuccess = array();
                    $larrSuccess['type'] = 'success';
                    $larrSuccess['strLabel'] = LIBCore::getLabel('LOGINOK');
                    LIBCore::setMessage($larrSuccess);

                    /*
                     * @TODO Muss noch eine Startseite erstellt werden
                     */
                    $lbulUser = new BULCoreUser();
                    $lbulUser->route('listMask');
                } else {
                    $larrSuccess = array();
                    $larrSuccess['type'] = 'error';
                    $larrSuccess['strLabel'] = LIBCore::getLabel('LOGINERROR');
                    LIBCore::setMessage($larrSuccess);
                    $lbooReturn = $this->loginMaske(array(LIBCore::getPost()));
                }
                break;
            case 'loginMask':
                if (isset($_SESSION['arrUser'])) {
                    $larrSuccess = array();
                    $larrSuccess['type'] = 'warning';
                    $larrSuccess['strLabel'] =
                                        LIBCore::getLabel('BEREITSEINGELOGGT');
                    LIBCore::setMessage($larrSuccess);

                    /*
                     * @TODO Muss noch eine Startseite erstellt werden
                     */
                    $lbulUser = new BULCoreUser();
                    $lbulUser->route('listMask');
                } else {
                    $lbooReturn = $this->loginMaske();
                }
                break;
            case 'logout':
                LIBCore::unregisterUser();
                $larrSuccess = array();
                $larrSuccess['type'] = 'success';
                $larrSuccess['strLabel'] = LIBCore::getLabel('LOGOUTED');
                LIBCore::setMessage($larrSuccess);
                $this->routeExtension('loginMask');
                break;
            default:
                break;
        }
        unset($pstrData);
        return $lbooReturn;
    }

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLCoreUser();
        $lfab = new LIBFeldaufbau();
        $lfab->setFeldaufbauByName('BULCoreLogin');
        $ldbl->setTablename('core_user');
        $ldbl->setFeldaufbau($lfab);
        $this->setDbl($ldbl);
    }

    /**
     * Hier wird die Maske für das Login aufbereitet
     *
     * @param array $parrData
     * @return bool
     * @access public
     */
    public function loginMaske($parrData = array())
    {
        $luilRouter = new UIL_router();
        $luilRouter->setDbl($this->getDbl());
        $larrData = array(
            'strTyp' => 'form',
            'strAction' => '?strBul=CoreLogin&strView=html&strAction=login',
            'arrData' => array($parrData),
            'arrBreadcrumb' => array()

        );
        $larrDataTwo = array(
            'strTemplate' => $this->getFormTemplate(),
            'arrContent' => $larrData,
            'arrNavigation' => $this->_getNavigation(),
            'strAction' => LIBCore::getBaseLink(true).'&strAction=login',
            'strTemplate' => 'login_form.tpl'
        );
        //$luilRouter->setTemplate('login_form.tpl');
        $luilRouter->route($larrDataTwo);
        return true;
    }


}
