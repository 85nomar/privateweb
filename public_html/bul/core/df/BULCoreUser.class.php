<?php
namespace racore\bul\core\df;

use racore\dbl\core\df\DBLCoreUser;
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
class BULCoreUser extends LIBBul
{

    /**
     * Konstruktion mit den Grunddaten
     */
    public function __construct()
    {
        $ldbl = new DBLCoreUser();
        $this->setDbl($ldbl);
        $this->setListTemplate('user_list.tpl');
        $this->setFormTemplate('user_form.tpl');

        /*
        $ldbl = new LIBDbl();

        // @TODO
        $lfab = new LIBFeldaufbau();
        $lfab->setFeldaufbauByName('BULCoreUser');
        $ldbl->setTablename('core_df_user');
        $ldbl->setFeldaufbau($lfab);
        $this->setDbl($ldbl);
        */
    }

}
