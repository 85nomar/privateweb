<?php
namespace racore\uil\json\router;

use racore\phplibs\core\LIBUilRouter;
use racore\uil\json\df\UIL_json_df;
use racore\phplibs\core\LIBValid;

/**
 * Hiermit wird das Routing JSON-Intern definiert
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       25.03.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     User-Interface-Layer
 * @subpackage  JSON
 */
class UIL_json_router extends LIBUilRouter
{
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
            $ljson = new UIL_json_df();
            echo $ljson->show($parrData);
            return true;
        } else {
            return false;
        }
    }
}
