<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Database-Layer von der Konfiguration
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       09.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLCoreConfig extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('core_df_config');
        $this->setOrderBy('strName');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numConfigID')->strValid = 'INTEGER';
        $lfab->getField('strName')->strValid = 'STRING';
        $lfab->getField('strValue')->strValid = 'STRING';
    }

}
