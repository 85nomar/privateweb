<?php
namespace racore\phplibs\core;

/**
 * Description
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       01.03.13 06:15
 * @version     1.0.0
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 */
class LIBFeldaufbau
{
    /**
     * Hier kommen alle Feldaufbau-Felder hin
     *
     * @var array
     * @access private
     */
    private $_arrFelder = array();

    private $_arrFields = array();

    /**
     * Gibt den Inhalt des gewünschten Feldes zurück
     *
     * @param string $pstrName
     *
     * @return array|bool
     * @access public
     */
    public function getFeld($pstrName)
    {
        $lareturn = false;
        if (isset($this->_arrFelder[$pstrName])) {
            $lareturn = $this->_arrFelder[$pstrName];
        }
        return $lareturn;
    }

    /**
     * Gibt alle Felder in einem Array zurück
     *
     * @return array|bool
     * @access public
     */
    public function getFelder()
    {
        $lareturn = false;
        if (is_array($this->_arrFelder)) {
            $lareturn = $this->_arrFelder;
        }
        return $lareturn;
    }


    public function getFields()
    {
        $lareturn = false;
        if (is_array($this->_arrFields)) {
            $lareturn = $this->_arrFields;
        }
        return $lareturn;
    }

    /**
     * Setzt ein einzelnes Feld in den Feldaufbau
     *
     * @param $pstrName
     * @param $parrFeld
     *
     * @return bool
     * @access public
     */
    public function setFeld($pstrName, $parrFeld)
    {
        $lbreturn = false;
        if ($pstrName != "" AND is_array($parrFeld)) {
            $this->_arrFelder[$pstrName] = $parrFeld;
            if (is_array($this->_arrFelder[$pstrName])) {
                $lbreturn = true;
            }
        }
        return $lbreturn;
    }

    public function setField($pstrName, $pfield)
    {
        $lbreturn = false;
        if ($pstrName != '') {
            $this->_arrFields[$pstrName] = $pfield;
            $lbreturn = true;
        }
        return $lbreturn;
    }

    /**
     * @param $pstrName
     *
     * @return bool|LIBFeldaufbauFeld
     */
    public function getField($pstrName)
    {
        $lfld = false;
        if (isset($this->_arrFields[$pstrName])) {
            $lfld = $this->_arrFields[$pstrName];
        }
        return $lfld;
    }


    public function setFeldValue($pstrName, $pstrTyp, $pstrValue)
    {
        $lbooReturn = true;
        if (!isset($this->_arrFelder[$pstrName])) {
            $this->_arrFelder[$pstrName] = array();
        }
        $this->_arrFelder[$pstrName][$pstrTyp] = $pstrValue;
        return $lbooReturn;
    }

    /**
     * Setzt alle Felder in den Feldaufbau
     *
     * @param $parrFelder
     *
     * @return bool
     * @access public
     */
    public function setFelder($parrFelder)
    {
        $lbreturn = false;
        if (is_array($parrFelder)) {
            $this->_arrFelder = $parrFelder;
            $lbreturn = true;
        }
        $this->checkRight();
        return $lbreturn;
    }

    /**
     * Kontrolliert ob die Daten gemäss Feld Valide sind, gemäss
     * Validierungstyp
     *
     * @param string $parrData
     *
     * @return bool
     * @access public
     */
    public function isValidData($parrData)
    {
        $lbooReturn = true;
        $larrWarning = array();
        $lstrWarning = '<ul>';
        $ldbl = new LIBDbl();
        $ldbl->setTablename('core_validtyp');
        $larrFields = $this->getFields();
        /** @var LIBFeldaufbauFeld $lfeld */
        $lfeld = null;
        foreach ($larrFields AS $lstrKey => $lfeld) {
            $lstrPostValue = '';
            if (isset($parrData[$lstrKey])) {
                $lstrPostValue = $parrData[$lstrKey];
            }

            /**
             * Regex-Prüfung
             */
            $larrValid = $ldbl->getWhere(
                'strCode = \''.$lfeld->strValid.'\''
            );
            if (count($larrValid) > 0) {
                $lstrRegex = $larrValid[0]['strRegex'];
                if (!preg_match('/'.$lstrRegex.'/', $lstrPostValue)) {
                    $lbooReturn = false;
                    $lstrWarning = $lfeld->strLabel;
                    if ($lfeld->strLabel == '') {
                        $lstrWarning = $lstrKey;
                    }
                    $lstrWarning .=
                        ' ('.LIBCore::getLabel('UNGUELTIGEZEICHEN').')';
                    array_push($larrWarning, $lstrWarning);

                    /*
                    $lstrWarning .= '<li>'.$lfeld->strLabel.' ';
                    $lstrWarning .=
                        '('.LIBCore::getLabel('UNGUELTIGEZEICHEN').')';
                    $lstrWarning .= '</li>';
                    */
                }
            }


            /**
             * Länge Prüfen
             */
            if (    strlen($lstrPostValue) > $lfeld->numLength
                AND $lfeld->numLength > 0) {
                $lbooReturn = false;
                $lstrWarning = $lfeld->strLabel;
                if ($lfeld->strLabel == '') {
                    $lstrWarning = $lstrKey;
                }
                $lstrWarning .=
                    ' ('.LIBCore::getLabel('ZULANG').')';
                array_push($larrWarning, $lstrWarning);

                /*
                $lstrWarning .= '<li>'.$lfeld->strLabel.' ';
                $lstrWarning .= '('.LIBCore::getLabel('ZULANG').')';
                $lstrWarning .= '</li>';
                */
            }

            /**
             * Required-Prüfen
             */
            if (    $lfeld->booRequired == 1
                AND $lstrPostValue == '') {
                $lbooReturn = false;
                $lstrWarning = $lfeld->strLabel;
                if ($lfeld->strLabel == '') {
                    $lstrWarning = $lstrKey;
                }
                $lstrWarning .=
                    ' ('.LIBCore::getLabel('MUSSANGEGEBENSEIN').')';
                array_push($larrWarning, $lstrWarning);

                /*
                $lstrWarning .= '<li>'.$lfeld->strLabel.' ';
                $lstrWarning .= '('.LIBCore::getLabel('MUSSANGEGEBENSEIN').')';
                $lstrWarning .= '</li>';
                */
            }
        }

        if (!$lbooReturn) {
            $larrWarningM = array();
            $larrWarningM['type'] = 'warning';
            $larrWarningM['arrWarning'] = $larrWarning;
            /*
            $larrWarning['strLabel'] = LIBCore::getLabel('VALIDWARNING').
                $lstrWarning;
            */
            LIBCore::setMessage($larrWarningM);
        }

        return $lbooReturn;
    }

    /**
     * Kontrolliert ob es im Feldaufbau ein entsprechendes Feld gibt
     *
     * @param string $pstrField
     * @return bool
     * @access public
     */
    public function hasField($pstrField)
    {
        $lbooReturn = false;
        foreach ($this->_arrFelder AS $larrValue) {
            if ($pstrField == $larrValue['strDatabaseField']) {
                $lbooReturn = true;
            }
        }
        return $lbooReturn;
    }

    /**
     * Kontrolliert die Rechte
     *
     * @return bool
     * @access public
     */
    public function checkRight()
    {
        $larrData = array();
        foreach ($this->_arrFelder AS $larrValue) {
            $larrValue['booRightEdit'] = LIBCore::hasRightEdit();
            $larrValue['booRight'] = LIBCore::hasRight($larrValue['strRight']);
            $larrValue['booRollRight'] = LIBCore::hasRight(
                $larrValue['strRight'], null, false
            );
            if ($larrValue['strRight'] === '') {
                $larrValue['booRollRight'] = true;
            }
            $lstrRight = $larrValue['strRight'];
            $lstrBaseLink = LIBCore::getBaseLink('true');
            $lstrBaseLink .= '&strAction='.LIBCore::getGet('strAction');
            $lstrRemoveLink = $lstrBaseLink.'&strRemoveRight='.$lstrRight;;
            $lstrAddLink = $lstrBaseLink.'&strAddRight='.$lstrRight;
            $larrValue['strRightAddLink'] = $lstrAddLink;
            $larrValue['strRightRemoveLink'] = $lstrRemoveLink;
            array_push($larrData, $larrValue);
        }
        $this->_arrFelder = $larrData;
        return true;
    }


}
