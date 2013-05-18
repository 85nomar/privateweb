<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreUser;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDbl;
use racore\uil\router\UIL_router;

/**
 * Das ist der Business-Layer vom Update
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       26.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreUpdate extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new LIBDbl();
        $ldbl->setTablename('core_user');
        $this->setDbl($ldbl);
        $this->setListTemplate('update_list.tpl');
    }

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
                break;
            default:
                break;
        }
        unset($pstrData);
        return $lbooReturn;
    }

    public function _listMask()
    {
        $lbooReturn = false;
        $luilRouter = new UIL_router();
        $larrTags = array();
        //exec("git tag -l", $latags);
        exec('git branch -r', $larrTags);


        $larrData = array(
            'strTyp' => 'list',
            'arrData' => $larrTags,
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



}
