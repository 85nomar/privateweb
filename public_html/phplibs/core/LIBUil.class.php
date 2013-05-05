<?php
namespace racore\phplibs\core;
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
        $lsmarty = $this->getSmarty();
        $lsmarty->clearAllAssign();
        $ldbllabels = new LIBDbl();
        $ldbllabels->setTablename('core_df_label');
        $ldbllabels->setOrderBy('strName');
        $lalabels = $ldbllabels->getAll();
        foreach ($lalabels AS $laValue) {
            $lsmarty->assign('L_'.$laValue['strName'], $laValue['strLabel']);
        }
        $lsmarty->assign('G_BASELINK', LIBCore::getBaseLink(true));
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
