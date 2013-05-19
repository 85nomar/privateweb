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
            case 'updateaction':
                $lstrNew = LIBCore::getGet('strTag');
                $lstrOld = LIBCore::getGet('strTagOld');
                $lstrBefehl = "sh update.sh '".$lstrOld."' '".$lstrNew."'";
                exec($lstrBefehl, $larrOutput);
                LIBCore::print_r($larrOutput);
                break;
            case 'updaterescue':
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

        /**
         * Auslesen der Update-Rescue-Verzeichnisses
         */
        $larrUpdateRescue = scandir('_updaterescue');

        /**
         * Versionen welche Ausgecheckt wurden
         */
        $larrVersion = array();
        exec('git describe --always --tag', $larrVersion);

        /**
         * Tags welche es überhaupt gibt
         */
        $larrTags = array();
        exec("git tag", $larrTags, $lnumReturn);

        /**
         * Tag-Temp-Array über Versions-Informationen
         */
        $larrTempTag = array(
            'version' => null,
            'old' => true,
            'act' => false,
            'updaterescue' => false
        );

        /**
         * Versionen abfragen und alles abfüllen
         */
        $lstrVersion = null;
        foreach ($larrTags AS $lstrKey => $lstrTag) {
            if ($larrVersion[0] == $lstrTag) {
                $larrTempTag['old'] = false;
                $larrTempTag['act'] = true;
                $lstrVersion = $lstrTag;
            }

            $larrTempTagEnd = $larrTempTag;
            $larrTempTag['act'] = false;
            $larrTempTagEnd['version'] = $lstrTag;
            if (in_array($lstrTag, $larrUpdateRescue)) {
                $larrTempTagEnd['updaterescue'] = true;
            }

            $larrTags[$lstrKey] = $larrTempTagEnd;

        }
        $larrTags = array_reverse($larrTags);

        $larrDaten = array();
        $larrDaten['arrTags'] = $larrTags;
        $larrDaten['arrVersion'] = $larrVersion;
        $larrDaten['strVersion'] = $lstrVersion;

        $larrData = array(
            'strTyp' => 'list',
            'arrData' => $larrDaten,
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
