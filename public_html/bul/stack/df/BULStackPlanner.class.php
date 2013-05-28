<?php
namespace racore\bul\stack\df;

use racore\dbl\stack\df\DBLStackPlanner;
use racore\phplibs\core\LIBBul;
use racore\phplibs\core\LIBCore;

/**
 * Das ist der Business-Layer vom Business-Layer
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       28.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class BULStackPlanner extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLStackPlanner();
        $this->setDbl($ldbl);
        $this->setListTemplate('planner_list.tpl');
        $this->setFormTemplate('planner_form.tpl');
    }

}
