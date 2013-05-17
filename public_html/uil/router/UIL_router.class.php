<?php
namespace racore\uil\router;

use racore\phplibs\core\LIBAutoload;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBValid;
use racore\phplibs\core\LIBUil;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBUilRouter;

/**
 * Hiermit wir das Routing bestimmt bzw. welcher Ausgabekanal genutzt wird
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       25.03.2013
 * @version     1.00
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     User-Interface-Layer
 * @subpackage  Router
 */
class UIL_router
{

    /**
     * Enthält die DBL-Verbindung
     *
     * @var null|LIBDbl
     * @access private
     */
    private $_dbl = null;

    /**
     * Enthält Template-Informationen bereit (nicht Zwingend)
     * Dies ist vorallem für die HTML-Anzeige wichtig
     *
     * @var string
     * @access private
     */
    private $_strTemplate = '';

    /**
     * Enthält die UIL-Verbindung
     *
     * @var null|LIBUil
     * @access private
     */
    private $_uil = null;

    /**
     * Hiermit wird die DBL-Verbindung zurückgegeben
     *
     * @return bool|null|LIBDbl
     * @access public
     */
    public function getDbl()
    {
        if (!is_null($this->_dbl)) {
            return $this->_dbl;
        } else {
            return false;
        }
    }

    /**
     * Gibt das Template zurück
     *
     * @return string
     * @access public
     */
    public function getTemplate()
    {
        return $this->_strTemplate;
    }

    /**
     * Hiermit wir die UIL-Verbindung zurückgegeben
     *
     * @return bool|null|LIBUil
     * @access public
     */
    public function getUil()
    {
        if (!is_null($this->_uil)) {
            return $this->_uil;
        } else {
            return false;
        }
    }

    /**
     * Hiermit wird das Routing bestimmt
     *
     * @param array $parrData
     *
     * @return bool
     * @access public
     */
    public function route($parrData)
    {
        if (LIBValid::isArray($parrData)) {
            $lstrClassname = '\\racore\\uil\\';
            $lstrClassname .= LIBCore::getGet('strView').'\\router\\';
            $lstrClassname .= 'UIL_'.LIBCore::getGet('strView').'_router';
            if (LIBAutoload::loadClass($lstrClassname)) {
                /** @var LIBUilRouter $lrefClass  */
                $lrefClass = new $lstrClassname();
                $lrefClass->setTemplate($this->getTemplate());
                $lrefClass->setFeldaufbau($this->_dbl->getFeldaufbau());
                $lrefClass->route($parrData);
            } else {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Hiermit wir die DBL-Verbindung bestimmt
     *
     * @param $pdbl
     *
     * @return bool
     * @access public
     */
    public function setDbl($pdbl)
    {
        $ldbl = new LIBDbl();
        if (!is_null($pdbl) AND is_a($pdbl, get_class($ldbl))) {
            $this->_dbl = $pdbl;
            return true;
        } else {
            return false;
        }
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

    /**
     * Hiermit wird die UIL-Verbindung bestimmt
     *
     * @param $puil
     *
     * @return bool
     * @access public
     */
    public function setUil($puil)
    {
        $luil = new LIBUil();
        if (!is_null($puil) AND is_a($puil, get_class($luil))) {
            $this->_uil = $puil;
            return true;
        } else {
            return false;
        }
    }



}
