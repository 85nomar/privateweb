<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreRoll;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBDbl;
use racore\phplibs\core\LIBFeldaufbau;

/**
 * Das ist der Business-Layer von der Rolle
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       21.04.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreRoll extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLCoreRoll();
        $this->setDbl($ldbl);
        $this->setListTemplate('roll_list.tpl');
        $this->setFormTemplate('roll_form.tpl');
    }


}
