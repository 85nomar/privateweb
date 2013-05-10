<?php
namespace racore\phplibs\core;
use racore\dbl\core\df\QYCoreLabel;
use racore\dbl\core\df\QYCoreRight;
use racore\phplibs\core\LIBValid AS LIBValid;
use racore\phplibs\core\LIBDB AS LIBDB;

/**
 * Hiermit wird eine Ansicht bzw. Daten ausgegeben je nach Formatierung
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       13.03.2013
 * @version     1.0.0
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 */
class LIBUil
{
    /**
     * Gibt das Feldaufbau-Objekt an
     *
     * @var null|LIBFeldaufbau
     * @access private
     */
    private $_fabFeldaufbau = null;


    /**
     * Enthält Template-Informationen bereit (nicht Zwingend)
     * Dies ist vorallem für die HTML-Anzeige wichtig
     *
     * @var string
     * @access private
     */
    private $_strTemplate = '';

    /**
     * @var \Smarty|null
     *
     */
    private $_smarty = null;

    /**
     * Gibt den Feldaufbau zurück
     *
     * @return LIBFeldaufbau|null
     * @access public
     */
    public function getFeldaufbau()
    {
        return $this->_fabFeldaufbau;
    }

    /**
     * Gibt das Smarty Objekt zurück
     *
     * @return null|\Smarty
     * @access public
     */
    public function getSmarty()
    {
        return $this->_smarty;
    }

    /**
     * Setzt das Smarty Objekt
     *
     * @param $psmarty
     * @access public
     */
    public function setSmarty($psmarty)
    {
        $this->_smarty = $psmarty;
    }

    /**
     * Gibt das Template zurück
     * Dieses wird auch gleich aufgesplittet und als Pfad zurückgegeben
     *
     * @return string
     * @access public
     */
    public function getTemplate()
    {
        $lstrTemplate = '';
        if ($this->_strTemplate != '') {
            $larrTemplate = explode('_', $this->_strTemplate);
            $lstrTemplate = '../public_html/uil/html/template/';
            foreach ($larrTemplate AS $lstrValue) {
                $lstrTemplate .= $lstrValue."/";
            }
            $lstrTemplate = substr($lstrTemplate, 0, -1).'.tpl';
        }
        return $lstrTemplate;
    }

    /**
     * Setzt den Feldaufbau
     *
     * @param $pfabFeldaufbau
     *
     * @return boolean
     * @access public
     */
    public function setFeldaufbau($pfabFeldaufbau)
    {
        $this->_fabFeldaufbau = $pfabFeldaufbau;
        return true;
    }

    /**
     * Setzt das Template
     *
     * @param $pstrTemplate
     *
     * @return bool
     * @access public
     */
    public function setTemplate($pstrTemplate)
    {
        $lbooReturn = false;
        if (LIBValid::isString($pstrTemplate)) {
            $lbooReturn = true;
            $this->_strTemplate = $pstrTemplate;
        }
        return $lbooReturn;
    }

    protected function _loadMainAssign()
    {
        $larrUser = LIBCore::getSession('arrUser');
        $lsmarty = $this->getSmarty();
        $lsmarty->clearAllAssign();

        /**
         * Labels
         */
        $ldbllabels = new QYCoreLabel();
        $lalabels = $ldbllabels->getAllWithBul();
        foreach ($lalabels AS $laValue) {
            $lsmarty->assign(
                'L_'.strtoupper($laValue['strBul']).
                '_'.$laValue['strName'], $laValue['strLabel']
            );
            if ($laValue['strBul'] === 'CoreLabel') {
                $lsmarty->assign(
                    'L_'.$laValue['strName'], $laValue['strLabel']
                );
            }
        }

        /**
         * Rechte
         */
        $ldblrights = new QYCoreRight();
        $larights = $ldblrights->getAllWithBulAndHaveRight();
        foreach ($larights AS $laValue) {
            $lsmarty->assign(
                'R_'.strtoupper($laValue['strBul']).
                '_'.$laValue['strCode'], $laValue['numRight']
            );
        }

        /**
         * Rollen-Rechte
         */
        $larights = $ldblrights->getAllWithBulAndHaveRightForRoll();
        foreach ($larights AS $laValue) {
            $lsmarty->assign(
                'RROLL_'.strtoupper($laValue['strBul']).
                    '_'.$laValue['strCode'], $laValue['numRight']
            );
        }
        $lnumRollID = 0;
        if (isset($larrUser['numRollID'])) {
            $lnumRollID = (integer) $larrUser['numRollID'];
        }
        $lsmarty->assign('G_NUMROLLEID', $lnumRollID);
        $lsmarty->assign('G_BASELINK', LIBCore::getBaseLink(true));
        $lstrBaseLinkAction = LIBCore::getBaseLink(true).
            '&strAction='.LIBCore::getGet('strAction');
        $lsmarty->assign(
            'G_BASELINKACTION',
            $lstrBaseLinkAction
        );
        $this->setSmarty($lsmarty);
    }


    /**
     * Ist für die Anzeige Zuständig
     *
     * @param $parrData
     * @return bool
     * @access public
     */
    public function show($parrData)
    {
        if (LIBValid::isArray($parrData)) {
            return true;
        } else {
            return false;
        }
    }


}
