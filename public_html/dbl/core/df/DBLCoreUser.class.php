<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Database-Layer vom Core-User
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       08.04.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLCoreUser extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('core_user');
        $this->setOrderBy('strName');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numUserID')->strValid = 'INTEGER';
        $lfab->getField('strName')->strValid = 'STRING';
        $lfab->getField('strPassword')->strValid = 'STRINGOREMPTY';
    }

    /**
     * Kontrolliert ob Login-Daten (User, Passwort) korrekt sind
     *
     * @param $pstrUser
     * @param $pstrPassword
     * @return bool
     *
     * @access public
     */
    public function validateLogin($pstrUser, $pstrPassword)
    {
        $lbooReturn = false;
        $lstrQuery = 'SELECT *
                      FROM '.$this->getTablename().'
                      WHERE strName = \''.$pstrUser.'\'
                        AND strPassword = \''.$pstrPassword.'\' ';
        if (LIBDB::query($lstrQuery)) {
            $larrData = LIBDB::getData();
            if (count($larrData) > 0) {
                $lbooReturn = true;
            }
        }
        return $lbooReturn;
    }



}
