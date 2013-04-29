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
     * Baut den Feldaufbau gemäss Namen, aus der Datenbank zusammen
     *
     * @param $pstrName
     * @return bool
     * @access public
     */
    public function setFeldaufbauByName($pstrName)
    {
        // @TODO
        $lclistqy = 'SELECT cdf.strTableName, cdff.*
                     FROM       core_df_feldaufbau AS cdf
                     INNER JOIN core_df_feldaufbau_feld AS cdff
                        ON cdf.numFeldaufbauID = cdff.numFeldaufbauID
                     WHERE cdf.strFeldaufbauName = :strName
                     ORDER BY cdff.numOrder ASC';
        $larrData = array();
        $larrData['strName'] = $pstrName;
        LIBDB::query($lclistqy, $larrData);
        $larrFelder = LIBDB::getData();

        foreach ($larrFelder AS $lstrKey => $larrValue) {
            $lstrFieldname = $larrValue['strDatabaseField'];
            if ($lstrFieldname != '') {
                $larrInfos =
                    LIBDB::getFieldInformations($larrValue['strTableName']);
                if ((integer) $larrValue['numLength'] == 0) {
                    $larrValue['numLength'] =
                        (integer) $larrInfos[$lstrFieldname]['Length'];
                }
                if ((string) $larrValue['strDataType'] == '') {
                    $larrValue['strDataType'] =
                        (string) $larrInfos[$lstrFieldname]['Type'];
                }
                if (LIBCore::hasRight($larrValue['strRight'])
                    OR $larrValue['strRight'] === '') {
                    $larrValue['booRight'] = true;
                }
            }
            $larrFelder[$lstrKey] = $larrValue;
        }
        $this->setFelder($larrFelder);
        return true;
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
        $lstrWarning = '<ul>';
        $ldbl = new LIBDbl();
        $ldbl->setTablename('core_df_validtyp');
        foreach ($this->_arrFelder AS $lstrValue) {
            $lstrPostValue = '';
            if (isset($parrData[$lstrValue['strDatabaseField']])) {
                $lstrPostValue = $parrData[$lstrValue['strDatabaseField']];
            }

            /**
             * Regex-Prüfung
             */
            $larrValid = $ldbl->getWhere(
                'numValidTypID = '.$lstrValue['numValidTypID']
            );
            $lstrRegex = $larrValid[0]['strRegex'];
            if (!preg_match('/'.$lstrRegex.'/', $lstrPostValue)) {
                $lbooReturn = false;
                $lstrWarning .= '<li>'.$lstrValue['strLabel'].' ';
                $lstrWarning .= '('.LIBCore::getLabel('UNGUELTIGEZEICHEN').')';
                $lstrWarning .= '</li>';
            }

            /**
             * Länge Prüfen
             */
            if (strlen($lstrPostValue) > $lstrValue['numLength']
                AND $lstrValue['numLength'] > 0) {
                $lbooReturn = false;
                $lstrWarning .= '<li>'.$lstrValue['strLabel'].' ';
                $lstrWarning .= '('.LIBCore::getLabel('ZULANG').')';
                $lstrWarning .= '</li>';
            }

            /**
             * Required-Prüfen
             */
            if ($lstrValue['numRequired'] == 1
                AND $lstrPostValue == '') {
                $lbooReturn = false;
                $lstrWarning .= '<li>'.$lstrValue['strLabel'].' ';
                $lstrWarning .= '('.LIBCore::getLabel('MUSSANGEGEBENSEIN').')';
                $lstrWarning .= '</li>';
            }
        }

        if (!$lbooReturn) {
            $larrWarning = array();
            $larrWarning['type'] = 'warning';
            $larrWarning['strLabel'] = LIBCore::getLabel('VALIDWARNING').
                $lstrWarning;
            LIBCore::setMessage($larrWarning);
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
