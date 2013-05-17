<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Database-Layer vom Core-User
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       09.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLCoreMenu extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('core_menu');
        $this->setOrderBy('strName');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numMenuID')->strValid = 'INTEGER';
        $lfab->getField('numParentMenuID')->strValid = 'INTEGER';
        $lfab->getField('numBulID')->strValid = 'INTEGER';
        $lfab->getField('strName')->strValid = 'STRING';
        $lfab->getField('strLink')->strValid = 'STRING';
        $lfab->getField('strIcon')->strValid = 'STRING';
        $lfab->getField('strRight')->strValid = 'STRING';
        $lfab->getField('numOrder')->strValid = 'INTEGER';
    }

    /**
     * Gibt alle Daten der Tabelle in einem Array zurück
     *
     * @return array
     * @access public
     */
    public function getAll()
    {
        $larrReturn = array();
        $lstrQuery = 'SELECT cm.*,
                             if(!ISNULL(cb.strName),
                                cb.strName,
                                \'\') AS strBulName,
                             IF(!ISNULL(cmp.numMenuID),
                                cmp.strName,
                                \'\') AS strParentMenuName
                      FROM ' . $this->getTablename() .' AS cm
                      LEFT OUTER JOIN
                            '.LIBCore::getTableName('core_bul').' AS cb
                        ON cm.numBulID = cb.numBulID
                      LEFT OUTER JOIN
                                '.LIBCore::getTableName('core_menu').' AS cmp
                        ON  cm.numParentMenuID = cmp.numMenuID';
        if ($this->getOrderBy() != '') {
            $lstrQuery .= ' ORDER BY strParentMenuName,
                            strBulName, ' . $this->getOrderBy();
        }
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
        }
        return $larrReturn;
    }

}
