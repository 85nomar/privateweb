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
class DBLCoreValidtyp extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('core_df_validtyp');
        $this->setOrderBy('strCode');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numValidTypID')->strValid = 'INTEGER';
        $lfab->getField('strCode')->strValid = 'STRING';
        $lfab->getField('strName')->strValid = 'STRING';
        $lfab->getField('strRegex')->strValid = 'ALLES';
    }

}
