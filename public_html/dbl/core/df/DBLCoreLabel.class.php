<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Database-Layer vom Label
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       06.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLCoreLabel extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('core_df_label');
        $this->setOrderBy('strName');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numLabelID')->strValid = 'INTEGER';
        $lfab->getField('numBulID')->strValid = 'INTEGER';
        $lfab->getField('strName')->strValid = 'STRING';
        $lfab->getField('strLabel')->strValid = 'STRING';
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
        $lstrQuery = 'SELECT cl.*,
                             cb.strName AS strBulName
                      FROM ' . $this->getTablename() .' AS cl
                      INNER JOIN core_df_bul AS cb
                        ON cl.numBulID = cb.numBulID ';
        if ($this->getOrderBy() != '') {
            $lstrQuery .= ' ORDER BY strBulName, ' . $this->getOrderBy();
        }
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
        }
        return $larrReturn;
    }

}
