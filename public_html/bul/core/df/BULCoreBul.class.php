<?php
namespace racore\bul\core\df;

use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBFeldaufbau;

/**
 * Das ist der Business-Layer vom Feldaufbau
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       30.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreBul extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new LIBDbl();

        // @TODO
        $lfab = new LIBFeldaufbau();
        $lfab->setFeldaufbauByName('BULCoreBul');
        $ldbl->setTablename('core_df_bul');
        $ldbl->setOrderBy('strName');
        $ldbl->setFeldaufbau($lfab);
        $this->setDbl($ldbl);
    }


}
