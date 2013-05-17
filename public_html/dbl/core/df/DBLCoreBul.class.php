<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Database-Layer vom Business-Layer
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       03.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLCoreBul extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('core_bul');
        $this->setOrderBy('strName');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numBulID')->strValid = 'INTEGER';
        $lfab->getField('strName')->strValid = 'STRING';
    }

}
