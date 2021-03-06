<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreValidtyp;
use racore\phplibs\core\LIBBul;

/**
 * Das ist der Business-Layer vom Validtyp
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       30.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULCoreValidTyp extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLCoreValidtyp();
        $this->setDbl($ldbl);
        $this->setListTemplate('validtyp_list.tpl');
        $this->setFormTemplate('validtyp_form.tpl');
    }


}
