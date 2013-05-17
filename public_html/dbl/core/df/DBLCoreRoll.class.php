<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Database-Layer vom Rollen
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       07.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLCoreRoll extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('core_roll');
        $this->setOrderBy('strKuerzel, strName');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numRollID')->strValid = 'INTEGER';
        $lfab->getField('strKuerzel')->strValid = 'STRING';
        $lfab->getField('strName')->strValid = 'STRING';
    }

}
