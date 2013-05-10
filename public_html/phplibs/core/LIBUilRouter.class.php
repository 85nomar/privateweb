<?php
namespace racore\phplibs\core;

use racore\phplibs\core\LIBValid;

/**
 * Hiermit werden die Grundlagen für die einzelnen Routings geschaffen
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       28.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     PHPLib
 * @subpackage  Core
 */
class LIBUilRouter
{


    /**
     * Enthält Template-Informationen bereit (nicht Zwingend)
     * Dies ist vorallem für die HTML-Anzeige wichtig
     *
     * @var string
     * @access private
     */
    private $_strTemplate = '';

    /**
     * Enthält den Feldaufbau
     * @var null|LIBFeldaufbau
     * @access private
     */
    private $_fabFeldaufbau = null;

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
     * Gibt den Feldaufbau zurück
     *
     * @return null|LIBFeldaufbau
     * @access public
     */
    public function getFeldaufbau()
    {
        return $this->_fabFeldaufbau;
    }

    /**
     * Setzt den Feldaufbau
     *
     * @param $pfabFeldaufbau
     * @access public
     */
    public function setFeldaufbau($pfabFeldaufbau)
    {
        $this->_fabFeldaufbau = $pfabFeldaufbau;
    }



}
