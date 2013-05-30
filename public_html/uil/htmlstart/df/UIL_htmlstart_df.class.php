<?php
namespace racore\uil\htmlstart\df;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBUil AS LIBUil;
use racore\phplibs\core\LIBValid;

/**
 * Hiermit soll der HTML-Return-Value ermöglicht werden
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       27.03.13
 * @version     1.00
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     User-Interface-Layer
 * @subpackage  HTML
 */
class UIL_htmlstart_df extends LIBUil
{

    /**
     * Gibt den Array im HTML-Format zurück
     *
     * @param array $parrData
     *
     * @return null|bool
     * @access public
     */
    public function show($parrData)
    {
        if (LIBValid::isArray($parrData)) {
            $lsmarty = new \Smarty();
            $lsmarty->display('uil/htmlstart/template/df/markup.tpl');
            $lbooReturn = null;
        } else {
            $lbooReturn = false;
        }
        return $lbooReturn;
    }

    /**
     * Baut die Content-Liste auf
     *
     * @param array $parrData
     * @param array $parrBreadcrumb
     * @return string
     * @access private
     */
    private function _getContentList($parrData, $parrBreadcrumb)
    {
        $lsmarty = new \Smarty();
        $lfab = $this->getFeldaufbau();
        $larrFelder = $lfab->getFelder();
        $lbooHatSortfield = $lfab->hasField('numOrder');
        $larrSort = array();
        if ($lbooHatSortfield) {
            $larrSort['link']  = LIBCore::getBaseLink(true);
            $larrSort['link'] .='&strAction=updateSort';
            $larrSort['label'] = LIBCore::getLabel('SORTSAVE');
        }

        /**
         * Buttons
         */
        $larrButtons = array();
        foreach ($larrFelder AS $larrValue) {
            if (     $larrValue['strDatabaseField'] === ''
                 AND ($larrValue['strComponent'] === 'buttonForm'
                 OR   $larrValue['strComponent'] === 'buttonFormPost')) {
                $larrSpalte = array();
                $lstrLink = $larrValue['strLink'];
                $lstrLink = $this->replaceLinkParameters(
                    LIBCore::getGet(), $lstrLink
                );
                $lstrLink = $this->replaceLinkParameters(
                    LIBCore::getPost(), $lstrLink
                );
                $larrSpalte['link'] = $lstrLink;
                $larrSpalte['label'] = $larrValue['strLabel'];
                $larrSpalte['icon'] = $larrValue['strIcon'];
                $larrSpalte['booRight'] = $larrValue['booRight'];
                $larrSpalte['booRightEdit'] = $larrValue['booRightEdit'];
                $larrSpalte['booRollRight'] = $larrValue['booRollRight'];
                $larrSpalte['strRight'] = $larrValue['strRight'];
                $larrSpalte['strRightAddLink'] = $larrValue['strRightAddLink'];
                $larrSpalte['strRightRemoveLink'] =
                    $larrValue['strRightRemoveLink'];
                if ($larrValue['strComponent'] === 'buttonFormPost') {
                    $larrSpalte['datapost'] = 'true';
                }
                array_push($larrButtons, $larrSpalte);
            }
        }

        /**
         * TabellenKopf
         */
        $larrHeader = array();
        foreach ($larrFelder AS $larrValue) {
            if ($larrValue['strDatabaseField'] !== '') {
                $larrSpalte = array();
                $larrSpalte['label'] = $larrValue['strLabel'];
                $larrSpalte['display'] = $larrValue['numDisplayInList'];
                array_push($larrHeader, $larrSpalte);
            }
        }
        $larrSpalte = array();
        $larrSpalte['label'] = '';
        $larrSpalte['display'] = 1;
        array_push($larrHeader, $larrSpalte);

        /**
         * Tabellen-Inhalt
         */
        $larrDaten = array();
        foreach ($parrData AS $larrRow) {

            $larrRowNew =  array();
            $larrIcons = array();
            foreach ($larrFelder AS $larrValue) {
                if ($larrValue['strDatabaseField'] != '') {
                    $larrSpalte = array();
                    $larrSpalte['label'] =
                                    $larrRow[$larrValue['strDatabaseField']];
                    $larrSpalte['display'] = $larrValue['numDisplayInList'];
                    array_push($larrRowNew, $larrSpalte);
                } else if ($larrValue['strComponent'] === 'ListIcon') {
                    $larrSpalte = array();
                    $lstrLink = $larrValue['strLink'];
                    $lstrLink = $this->replaceLinkParameters(
                        $larrRow, $lstrLink
                    );
                    $larrSpalte['label'] = $larrValue['strLabel'];
                    $larrSpalte['link'] = $lstrLink;
                    $larrSpalte['icon'] = $larrValue['strIcon'];
                    $larrSpalte['display'] = $larrValue['numDisplayInList'];
                    array_push($larrIcons, $larrSpalte);
                }
            }
            array_push($larrRowNew, $larrIcons);
            array_push($larrDaten, $larrRowNew);
        }

        $larrData = array();
        $larrData['larrBreadcrumb'] = $parrBreadcrumb;
        $larrData['larrButtons'] = $larrButtons;
        $larrData['larrHeader'] = $larrHeader;
        $larrData['larrDaten'] = $larrDaten;
        $larrData['larrSort'] = $larrSort;
        $lsmarty->assign($larrData);
        $lsmarty->assign('isSortable', $lbooHatSortfield);
        $lctemplate = 'uil/html/template/smarty/df/component_list.tpl';
        return $lsmarty->fetch($lctemplate);
    }

    /**
     * Baut Content-Form auf
     *
     * @param array  $parrData
     * @param string $pstrAction
     * @param array  $parrBreadcrumb
     *
     * @return string
     * @access private
     */
    private function _getContentForm($parrData, $pstrAction, $parrBreadcrumb)
    {
        $lsmarty = new \Smarty();
        $lfab = $this->getFeldaufbau();
        $larrFelder = $lfab->getFelder();

        if (count($parrData) === 0) {
            array_push($parrData, array());
        }
        $parrData[0] = array_merge(LIBCore::getGet(), $parrData[0]);
        $larrControls = array();
        foreach ($larrFelder AS $larrValue) {
            $larrControl = array();
            $larrControl['data'] = $this->_getContentByFeldaufbauField(
                $larrValue, $parrData[0]
            );
            array_push($larrControls, $larrControl);
        }

        $larrData = array();
        $larrData['larrControls'] = $larrControls;
        $larrData['larrBreadcrumb'] = $parrBreadcrumb;
        $lsmarty->assign($larrData);
        $lsmarty->assign('lstrFormAction', $pstrAction);
        $lctemplate = 'uil/html/template/smarty/df/component_form.tpl';
        return $lsmarty->fetch($lctemplate);


    }

    /**
     * Holt ein Component-Teil
     *
     * @param array $parrField
     * @param array $parrData
     * @return string
     * @access private
     */
    private function _getContentByFeldaufbauField($parrField, $parrData)
    {
        $lstrReturn = '';

        switch ($parrField['strComponent']) {
            case 'button':
                $lstrReturn .= $this->_getButton($parrField);
                break;
            case 'submit':
                $lstrReturn .= $this->_getButton($parrField);
                break;
            case 'reset':
                $lstrReturn .= $this->_getButton($parrField);
                break;
            case 'input':
                $lstrReturn .= $this->_getInput($parrField, $parrData);
                break;
            case 'inputHidden':
                $lstrReturn .= $this->_getInputHidden($parrField, $parrData);
                break;
            case 'inputPassword':
                $lstrReturn .= $this->_getInputPassword($parrField, $parrData);
                break;
            case 'textarea':
                $lstrReturn .= $this->_getTextarea($parrField, $parrData);
                break;
            case 'select':
                $lstrReturn .= $this->_getSelect($parrField, $parrData);
                break;
        }

        return $lstrReturn;
    }

    /**
     * Baut die Select-Component auf
     *
     * @param array $parrField
     * @param array $parrData
     * @return string
     * @access protected
     */
    protected function _getSelect($parrField, $parrData)
    {
        $lsmarty = new \Smarty();

        $lstrDatabaseField = $parrField['strDatabaseField'];
        $lstrLabel = $parrField['strLabel'];
        $lstrRight = $parrField['strRight'];
        $lstrHelptext = ''; //$parrField['strHelptext'];

        $lsmarty->assign('COMPONENTID', $lstrDatabaseField);
        $lsmarty->assign('COMPONENTLABEL', $lstrLabel);
        $lsmarty->assign('COMPONENTHELPTEXT', $lstrHelptext);
        $lsmarty->assign('COMPONENTRIGHT', $lstrRight);

        $larrValue = array();
        if (    isset($parrData[$lstrDatabaseField])
            AND $parrData[$lstrDatabaseField] !== '') {
            $larrValue = $parrData[$lstrDatabaseField];
        }
        $lsmarty->assign('larrValues', $larrValue);

        $lctemplate = 'uil/html/template/smarty/df/component_select.tpl';
        return $lsmarty->fetch($lctemplate);
    }

    /**
     * Baut die Input-Component auf
     *
     * @param array $parrField
     * @param array $parrData
     * @return string
     * @access protected
     */
    protected function _getInput($parrField, $parrData)
    {
        $lsmarty = new \Smarty();

        $lstrDatabaseField = $parrField['strDatabaseField'];
        $lstrMaxLength = $parrField['numLength'];
        $lstrLabel = $parrField['strLabel'];
        $lstrRight = $parrField['strRight'];
        $lstrHelptext = ''; //$parrField['strHelptext'];
        if ($lstrHelptext == '') {
            $larrText = array();
            $larrText['ANZAHL'] = $lstrMaxLength;
            $lstrHelptext = LIBCore::getLabel('MAXLENGTHFROM', $larrText);
        }
        $lstrValue = '';
        if (    isset($parrData[$lstrDatabaseField])
            AND $parrData[$lstrDatabaseField] !== '') {
            $lstrValue = $parrData[$lstrDatabaseField];
        }

        $lsmarty->assign('COMPONENTID', $lstrDatabaseField);
        $lsmarty->assign('COMPONENTLABEL', $lstrLabel);
        $lsmarty->assign('COMPONENTVALUE', $lstrValue);
        $lsmarty->assign('COMPONENTMAXLENGTH', $lstrMaxLength);
        $lsmarty->assign('COMPONENTHELPTEXT', $lstrHelptext);
        $lsmarty->assign('COMPONENTRIGHT', $lstrRight);

        $lctemplate = 'uil/html/template/smarty/df/component_input.tpl';
        return $lsmarty->fetch($lctemplate);
    }

    /**
     * Baut die Textarea-Component auf
     *
     * @param array $parrField
     * @param array $parrData
     * @return string
     * @access protected
     */
    protected function _getTextarea($parrField, $parrData)
    {
        $lsmarty = new \Smarty();

        $lstrDatabaseField = $parrField['strDatabaseField'];
        $lstrMaxLength = $parrField['numLength'];
        $lstrLabel = $parrField['strLabel'];
        $lstrRight = $parrField['strRight'];
        $lstrHelptext = ''; //$parrField['strHelptext'];
        if ($lstrHelptext == '' AND $lstrMaxLength > 0) {
            $larrText = array();
            $larrText['ANZAHL'] = $lstrMaxLength;
            $lstrHelptext = LIBCore::getLabel('MAXLENGTHFROM', $larrText);
        }
        $lstrValue = '';
        if (    isset($parrData[$lstrDatabaseField])
            AND $parrData[$lstrDatabaseField] !== '') {
            $lstrValue = $parrData[$lstrDatabaseField];
        }

        $lsmarty->assign('COMPONENTID', $lstrDatabaseField);
        $lsmarty->assign('COMPONENTLABEL', $lstrLabel);
        $lsmarty->assign('COMPONENTVALUE', trim($lstrValue));
        $lsmarty->assign('COMPONENTMAXLENGTH', $lstrMaxLength);
        $lsmarty->assign('COMPONENTHELPTEXT', $lstrHelptext);
        $lsmarty->assign('COMPONENTRIGHT', $lstrRight);

        $lctemplate = 'uil/html/template/smarty/df/component_textarea.tpl';
        return $lsmarty->fetch($lctemplate);

    }

    /**
     * Baut den Input-Hidden-Component auf
     *
     * @param array $parrField
     * @param array $parrData
     * @return string
     * @access protected
     */
    protected function _getInputHidden($parrField, $parrData)
    {

        $lsmarty = new \Smarty();

        $lstrDatabaseField = $parrField['strDatabaseField'];
        $lstrLabel = $parrField['strLabel'];
        $lstrRight = $parrField['strRight'];
        $lstrValue = '';
        if (    isset($parrData[$lstrDatabaseField])
            AND $parrData[$lstrDatabaseField] !== '') {
            $lstrValue = $parrData[$lstrDatabaseField];
        }

        $lsmarty->assign('ONLYHIDDEN', true);
        $lsmarty->assign('COMPONENTID', $lstrDatabaseField);
        $lsmarty->assign('COMPONENTLABEL', $lstrLabel);
        $lsmarty->assign('COMPONENTVALUE', $lstrValue);
        $lsmarty->assign('COMPONENTRIGHT', $lstrRight);

        $lctemplate = 'uil/html/template/smarty/df/component_input.tpl';
        return $lsmarty->fetch($lctemplate);
    }

    /**
     * Baut die InputPassword-Component auf
     *
     * @param array $parrField
     * @param array $parrData
     * @return string
     * @access protected
     */
    protected function _getInputPassword($parrField, $parrData)
    {
        $lsmarty = new \Smarty();

        $lstrDatabaseField = $parrField['strDatabaseField'];
        $lstrLabel = $parrField['strLabel'];
        $lstrMaxLength = $parrField['numLength'];
        $lstrRight = $parrField['strRight'];
        $lstrHelptext = ''; //$parrField['strHelptext'];
        if ($lstrHelptext == '') {
            $larrText = array();
            $larrText['ANZAHL'] = $lstrMaxLength;
            $lstrHelptext = LIBCore::getLabel('MAXLENGTHFROM', $larrText);
        }
        $lstrValue = '';
        if (    isset($parrData[$lstrDatabaseField])
            AND $parrData[$lstrDatabaseField] !== '') {
            $lstrValue = $parrData[$lstrDatabaseField];
        }

        $lsmarty->assign('COMPONENTTYPE', 'password');
        $lsmarty->assign('COMPONENTID', $lstrDatabaseField);
        $lsmarty->assign('COMPONENTLABEL', $lstrLabel);
        $lsmarty->assign('COMPONENTVALUE', $lstrValue);
        $lsmarty->assign('COMPONENTMAXLENGTH', $lstrMaxLength);
        $lsmarty->assign('COMPONENTHELPTEXT', $lstrHelptext);
        $lsmarty->assign('COMPONENTRIGHT', $lstrRight);

        $lctemplate = 'uil/html/template/smarty/df/component_input.tpl';
        return $lsmarty->fetch($lctemplate);
    }

    /**
     * Baut die Button-Component auf
     *
     * @param array $parrField
     * @return string
     * @access protected
     */
    protected function _getButton($parrField)
    {
        $lsmarty = new \Smarty();

        $lstrLabel = $parrField['strLabel'];
        $lstrType = $parrField['strComponent'];
        $lstrIcon = $parrField['strIcon'];
        $lbooRight = $parrField['booRight'];
        $lbooRightEdit = $parrField['booRightEdit'];
        $lbooRollRight = $parrField['booRollRight'];
        $lstrRight = $parrField['strRight'];
        $lstrRightAddLink = $parrField['strRightAddLink'];
        $lstrRightRemoveLink = $parrField['strRightRemoveLink'];

        $lsmarty->assign('COMPONENTTYPE', $lstrType);
        $lsmarty->assign('COMPONENTLABEL', $lstrLabel);
        $lsmarty->assign('COMPONENTICON', $lstrIcon);
        $lsmarty->assign('COMPONENTRIGHT', $lbooRight);
        $lsmarty->assign('COMPONENTRIGHTEDIT', $lbooRightEdit);
        $lsmarty->assign('COMPONENTROLLRIGHT', $lbooRollRight);
        $lsmarty->assign('COMPONENTRIGHTTEXT', $lstrRight);
        $lsmarty->assign('COMPONENTRIGHTADDLINK', $lstrRightAddLink);
        $lsmarty->assign('COMPONENTRIGHTREMOVELINK', $lstrRightRemoveLink);

        $lctemplate = 'uil/html/template/smarty/df/component_button.tpl';
        return $lsmarty->fetch($lctemplate);
    }

    /**
     * Ersetzt gewisse Parameter vom Link
     *
     * @param array  $parrData
     * @param string $pstrLink
     * @return string
     * @access public
     */
    public function replaceLinkParameters($parrData, $pstrLink)
    {
        $lstrLink = $pstrLink;
        $larrData = $parrData;
        $larrLink = array();
        if (preg_match_all('/(%%[A-Za-z]+%%)/', $lstrLink, $larrLink)) {
            foreach ($larrLink[0] AS $lstrSLink) {
                if (isset($lstrSLink)) {
                    $lstrSearch = preg_replace('/(%)/', '', $lstrSLink);
                    if (isset($larrData[$lstrSearch])) {
                        $lstrReplace = $larrData[$lstrSearch];
                        $lstrLink = preg_replace(
                            '/('.$lstrSLink.')/', $lstrReplace, $lstrLink
                        );
                    }
                }
            }
        }
        return $lstrLink;
    }


}
