<?php
namespace racore\phplibs\core;
use racore\phplibs\core\LIBValid AS LIBValid;
use racore\phplibs\core\LIBDB AS LIBDB;

/**
 * Hiermit wird die Verbindung mit der Datenbank gemacht, welche nach den Regeln
 * des Feldaufbaus auch automatisch gemacht werden kann
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       01.03.13 23:08
 * @version     1.0.1
 * @category    racore
 * @package     PHPLib
 * @subpackage  Core
 * @copyright   Copyright (c) 2013 Raffael Wyss
 */
class LIBDbl
{
    /**
     * Gibt das Feldaufbau-Objekt an
     *
     * @var null|LIBFeldaufbau
     * @access private
     */
    private $_fabFeldaufbau = null;

    /**
     * Gibt den Tabellennamen an
     *
     * @var string
     * @access private
     */
    private $_strTablename = '';

    /**
     * Enthält das OrderBy
     *
     * @var string
     * @access private
     */
    private $_strOrderBy = '';

    /**
     * Gibt alle Daten der Tabelle in einem Array zurück
     *
     * @return array
     * @access public
     */
    public function getAll()
    {
        $larrReturn = array();
        $lstrQuery
            = "SELECT *
                      FROM " . $this->getTablename();
        if ($this->getOrderBy() != '') {
            $lstrQuery .= ' ORDER BY ' . $this->getOrderBy();
        }
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
        }
        return $larrReturn;
    }

    /**
     * Gibt die Daten für z.B ein Dropdown zurück
     *
     * @param string $pstrCodeField
     * @param string $pstrValueField
     * @param integer $pstrSelectedCode
     *
     * @return array
     */
    public function getAllCodeAndValue(
        $pstrCodeField, $pstrValueField, $pstrSelectedCode = 0
    )
    {
        $larrReturn = array();
        $larrValueFields = explode(',', $pstrValueField);
        $lstrQuery = 'SELECT ' . $pstrCodeField . ' AS code,
                             CONCAT(' . implode(", ' ', ", $larrValueFields) . ') AS value,
                             IF(' . $pstrCodeField . ' =
                                ' . $pstrSelectedCode . ', 1, 0) AS selected
                      FROM ' . $this->getTablename() . '
                      ORDER BY value';
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
        }
        return $larrReturn;
    }


    /**
     * Gibt den Feldaufbau zurück
     *
     * @return LIBFeldaufbau|null
     * @access public
     */
    public function getFeldaufbau()
    {
        if (is_null($this->_fabFeldaufbau)) {
            $this->_fabFeldaufbau = $this->_feldaufbauByDatabaseTable();
        }
        return $this->_fabFeldaufbau;
    }

    /**
     * Gibt den Tabellennamen zurück
     *
     * @return string
     * @access public
     */
    public function getTablename()
    {
        return $this->_strTablename;
    }

    /**
     * Setzt den OrderBY
     *
     * @param $pstrOrderBy
     * @access public
     */
    public function setOrderBy($pstrOrderBy)
    {
        $this->_strOrderBy = $pstrOrderBy;
    }

    /**
     * Gibt den OrderBY zurück
     *
     * @return string
     * @access public
     */
    public function getOrderBy()
    {
        return $this->_strOrderBy;
    }

    /**
     * Gibt alle Daten gemäss Parameter zurück
     *
     * @param string $pstrWhere
     * @param int    $pintLimit
     *
     * @return array
     * @access public
     */
    public function getWhere($pstrWhere, $pintLimit = 0)
    {
        $larrReturn = array();
        $lstrQuery
            = "SELECT *
                      FROM " . $this->getTablename() . "
                      WHERE " . $pstrWhere;
        if ($this->getOrderBy() != '') {
            $lstrQuery .= ' ORDER BY ' . $this->getOrderBy();
        }
        if ($pintLimit > 0) {
            $lstrQuery .= "LIMIT " . $pintLimit;
        }
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
        }
        return $larrReturn;
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
     * Setzt den Tabellenname
     *
     * @param $pstrTablename
     *
     * @return boolean
     * @access public
     */
    public function setTablename($pstrTablename)
    {
        $this->_strTablename = $pstrTablename;
    }

    /**
     * Für einen Delete aus
     *
     * @param $parrData
     *
     * @return boolean
     * @access public
     */
    public function delete($parrData)
    {
        $larrData = $parrData;
        unset($parrData);
        if (LIBValid::isArray($larrData)) {
            $lfab = $this->getFeldaufbau();
            $larrFields = $lfab->getFields();
            $larrWhere = array();
            $larrDataDelete = array();
            foreach ($larrFields AS $lstrKey => $larrValue) {
                if (isset($larrData[$lstrKey])) {
                    array_push($larrWhere, $lstrKey .' = :'.$lstrKey);
                    $larrDataDelete[$lstrKey] = $larrData[$lstrKey];
                }
            }
            $lstrQuery = 'DELETE FROM ' . $this->getTablename() . '
                          WHERE ' . implode($larrWhere, " AND ");
            $larrFormat = LIBDB::getFieldInformations($this->getTablename());
            if (LIBDB::query($lstrQuery, $larrDataDelete, $larrFormat)) {
                $larrSuccess = array();
                $larrSuccess['type'] = 'success';
                $larrSuccess['strLabel'] = LIBCore::getLabel('DELETESUCCESS');
                LIBCore::setMessage($larrSuccess);
                return true;
            } else {
                $larrSuccess = array();
                $larrSuccess['type'] = 'error';
                $larrSuccess['strLabel'] = LIBCore::getLabel('DELETEERROR');
                LIBCore::setMessage($larrSuccess);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Bestimmt den Feldaufbau anhand der angegebenen Datenbanktabelle
     *
     * @return boolean
     * @access protected
     */
    protected function _feldaufbauByDatabaseTable()
    {
        $lbreturn = false;
        if ($larrFormat = LIBDB::getFieldInformations($this->getTablename())) {
            $lfab = new LIBFeldaufbau();
            $lbreturn = true;

            foreach ($larrFormat AS $lstrKey => $lstrValue) {
                $larr = array();
                $larr['type'] = preg_replace(
                    '/\([0-9]+\)/', "", $lstrValue['Type']
                );
                $larr['length'] = preg_replace(
                    '/([A-Za-z0-9])+\({1}|(\)){1}/', '', $lstrValue['Type']
                );
                if (!$lfab->setFeld($lstrKey, $larr)) {
                    $lbreturn = false;
                }
            }
        }
        return $lbreturn;
    }

    protected  function _createFeldaufbau()
    {
        $lbooReturn = false;
        $lfab = new LIBFeldaufbau();
        if ($larrFormat = LIBDB::getFieldInformations($this->getTablename())) {

            $lbooReturn = true;
            foreach ($larrFormat AS $lstrKey => $larrValue) {
                $lfld = new LIBFeldaufbauFeld();
                $lstr = preg_replace('/\([0-9]+\)/', "", $larrValue['Type']);
                $lfld->strType = $lstr;
                $lnum = preg_replace(
                    '/([A-Za-z0-9])+\({1}|(\)){1}/',
                    '',
                    (integer) $larrValue['Length']
                );
                $lfld->numLength = $lnum;
                if (!$lfab->setField($lstrKey, $lfld)) {
                    $lbooReturn = false;
                }
            }
        }
        $this->setFeldaufbau($lfab);
        return $lbooReturn;
    }

    /**
     * Führt einen Insert Befehl auf die Datenbank aus
     *
     * @param array $parrData
     *
     * @return boolean
     * @access public
     */
    public function insert($parrData)
    {
        $larrData = $parrData;
        unset($parrData);
        $larrUser = LIBCore::getSession('arrUser');
        $lfab = $this->getFeldaufbau();
        $larrFields = $lfab->getFields();
        /** @var LIBFeldaufbauFeld $lfield */
        $lfield = null;

        if (!$lfab->isValidData($larrData)) {
            return false;
        } else if (LIBValid::isArray($larrData)) {
            $larrInsertKeys = array();
            $larrInsertValues = array();
            foreach ($larrFields AS $lstrKey => $larrField) {
                if (    $lstrKey !== 'numCreatorID'
                    AND $lstrKey !== 'numEditorID'
                    AND $lstrKey !== 'datCreate'
                    AND $lstrKey !== 'datTimestamp'
                    AND $lstrKey !== 'numBulID') {
                    array_push($larrInsertKeys, $lstrKey);
                    array_push($larrInsertValues, ':'.$lstrKey);
                }
            }
            array_push($larrInsertKeys, 'numCreatorID');
            array_push($larrInsertValues, ':numCreatorID');
            $larrData['numCreatorID'] = $larrUser['numUserID'];
            array_push($larrInsertKeys, 'datCreate');
            array_push($larrInsertValues, ':datCreate');
            $larrData['datCreate'] = date('Y-m-d H:i:s');
            $lstrQuery = 'INSERT INTO ' . $this->getTablename() . '
                            (' . implode($larrInsertKeys, ',') . ') VALUES
                            (' . implode($larrInsertValues, ',') . ') ';
            $larrFormat = LIBDB::getFieldInformations($this->getTablename());
            if (LIBDB::query($lstrQuery, $larrData, $larrFormat)) {
                $larrSuccess = array();
                $larrSuccess['type'] = 'success';
                $larrSuccess['strLabel'] = LIBCore::getLabel('INSERTSUCCESS');
                LIBCore::setMessage($larrSuccess);
                return true;
            } else {
                $larrSuccess = array();
                $larrSuccess['type'] = 'error';
                $larrSuccess['strLabel'] = LIBCore::getLabel('INSERTERROR');
                LIBCore::setMessage($larrSuccess);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Führt ein Update durch
     *
     * @param $parrData
     *
     * @return boolean
     * @access public
     */
    public function update($parrData)
    {
        $larrData = $parrData;
        unset($parrData);
        $larrUser = LIBCore::getSession('arrUser');
        $lfab = $this->getFeldaufbau();
        $larrFields = $lfab->getFields();
        if (!$lfab->isValidData($larrData)) {
            return false;
        } else if (LIBValid::isArray($larrData)) {
            $lstrPrimaryKey = LIBDB::getPrimaryKeyName($this->getTablename());
            $larrSet = array();
            foreach ($larrFields AS $lstrKey => $larrField) {
                if (    $lstrKey !== 'numCreatorID'
                    AND $lstrKey !== 'numEditorID'
                    AND $lstrKey !== 'datCreate'
                    AND $lstrKey !== 'datTimestamp'
                    AND $lstrKey !== 'numBulID') {
                    array_push($larrSet, $lstrKey . " = :" .$lstrKey);
                }
            }
            array_push($larrSet, 'numEditorID = :numEditorID');
            $larrData['numEditorID'] = $larrUser['numEditorID'];
            $lstrQuery = "UPDATE " . $this->getTablename() . "
                          SET " . implode($larrSet, ',') . "
                          WHERE " . $lstrPrimaryKey . " = :" . $lstrPrimaryKey
                . " ";
            $larrFormat = LIBDB::getFieldInformations($this->getTablename());
            if (LIBDB::query($lstrQuery, $larrData, $larrFormat)) {
                $larrSuccess = array();
                $larrSuccess['type'] = 'success';
                $larrSuccess['strLabel'] = LIBCore::getLabel('UPDATESUCCESS');
                LIBCore::setMessage($larrSuccess);
                return true;
            } else {
                $larrSuccess = array();
                $larrSuccess['type'] = 'error';
                $larrSuccess['strLabel'] = LIBCore::getLabel('UPDATEERROR');
                LIBCore::setMessage($larrSuccess);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Updated das Sortierfeld (numOrder)
     *
     * @param array $parrData
     * @return bool
     * @access public
     */
    public function updateSort($parrData)
    {
        $larrPost = $parrData;
        $lstrPrimaryKey = LIBDB::getPrimaryKeyName($this->getTablename());
        $lbooError = false;

        foreach ($larrPost AS $lstrKey => $lnumID) {
            $lnumOrder = (integer) preg_replace('/(numOrder_)/', '', $lstrKey);
            $lstrQuery = "UPDATE " . $this->getTablename() . "
                          SET numOrder = :numOrder
                          WHERE ".$lstrPrimaryKey." = :".$lstrPrimaryKey." ";
            $larrData = array();
            $larrData['numOrder'] = $lnumOrder;
            $larrData[$lstrPrimaryKey] = $lnumID;
            if (!LIBDB::query($lstrQuery, $larrData)) {
                $lbooError = true;
            }
        }

        /**
         * Fehlerrückmeldung oder ob es OK ist
         */
        if (!$lbooError) {
            $larrSuccess = array();
            $larrSuccess['type'] = 'success';
            $larrSuccess['strLabel'] = LIBCore::getLabel('UPDATESUCCESS');
            LIBCore::setMessage($larrSuccess);
            return true;
        } else {
            $larrSuccess = array();
            $larrSuccess['type'] = 'error';
            $larrSuccess['strLabel'] = LIBCore::getLabel('UPDATEERROR');
            LIBCore::setMessage($larrSuccess);
            return false;
        }
    }

}
