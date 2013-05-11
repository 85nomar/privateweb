<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;
use racore\phplibs\core\LIBDbl;

/**
 * QUERY vom Label
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       08.04.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class QYCoreLabel extends DBLCoreLabel
{

    public function getAllWithBul()
    {
        $lstrQuery = 'SELECT cl.*, cb.strName AS strBul
                      FROM       '.$this->getTablename().' AS cl
                      INNER JOIN core_df_bul AS cb
                        ON cl.numBulID = cb.numBulID';
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
        } else {
            $larrReturn = array();
        }
        return $larrReturn;
    }

}
