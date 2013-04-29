<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBFeldaufbau;

/**
 * Database-Layer vom Core-Rollright
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       23.04.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class DBLCoreRollRight extends LIBDbl
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $lfab = new LIBFeldaufbau();
        $lfab->setFeldaufbauByName('BULCoreRollRight');
        $this->setTablename('core_df_rollright');
    }

}
