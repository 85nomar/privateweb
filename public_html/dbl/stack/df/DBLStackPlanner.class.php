<?php
namespace racore\dbl\stack\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * Database-Layer vom StackPlanner
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       28.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLStackPlanner extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $this->setTablename('stack_planner');
        $this->setOrderBy('strName');
        $this->_createFeldaufbau();
        $lfab = $this->getFeldaufbau();
        $lfab->getField('numPlannerID')->strValid = 'INTEGER';
        $lfab->getField('strName')->strValid = 'STRING';
    }

}
