<?php
namespace racore\uil\htmlstart\router;

use racore\phplibs\core\LIBUilRouter;
use racore\phplibs\core\LIBValid;
use racore\uil\htmlstart\df\UIL_htmlstart_df;

/**
 * Hiermit wird das Routing JSON-Intern definiert
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       27.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     User-Interface-Layer
 * @subpackage  HTML
 */
class UIL_htmlstart_router extends LIBUilRouter
{
    /**
     * Konstruktor -> damit geforderte sachen geladen werden können
     *
     */
    public function __construct()
    {
        require_once '../public_html/phplibs/smarty/Smarty.class.php';
        return $this;
    }

    /**
     * Hiermit wird das Routing bestimmt
     *
     * @param array $parrData
     *
     * @return bool
     * @access public
     */
    public function route($parrData)
    {
        if (LIBValid::isArray($parrData)) {
            $lhtml = new UIL_htmlstart_df();
            $lhtml->setFeldaufbau($this->getFeldaufbau());
            $lhtml->setTemplate($this->getTemplate());
            echo $lhtml->show($parrData);
            return true;
        } else {
            return false;
        }
    }
}
