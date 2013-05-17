<?php
namespace racore\dbl\core\df;

use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;

/**
 * Query Recht
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       08.04.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class QYCoreRight extends DBLCoreRight
{

    public function getAllWithBul()
    {
        $lstrQuery = 'SELECT cr.*, cb.strName AS strBul
                      FROM       '.$this->getTablename().' AS cr
                      INNER JOIN '.LIBCore::getTableName('core_bul').' AS cb
                        ON cr.numBulID = cb.numBulID';
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
        } else {
            $larrReturn = array();
        }
        return $larrReturn;
    }

    public function getAllWithBulAndHaveRight()
    {
        $lstrQuery = 'SELECT cr.*, cb.strName AS strBul
                      FROM       '.$this->getTablename().' AS cr
                      INNER JOIN '.LIBCore::getTableName('core_bul').' AS cb
                        ON cr.numBulID = cb.numBulID';
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
            foreach ($larrReturn AS $lstrKey => $larrValue) {
                if (LIBCore::hasRight(
                    $larrValue['strCode'], $larrValue['strBul']
                )) {
                    $larrReturn[$lstrKey]['numRight'] = 1;
                } else {
                    $larrReturn[$lstrKey]['numRight'] = 0;
                }
            }
        } else {
            $larrReturn = array();
        }
        return $larrReturn;
    }

    public function getAllWithBulAndHaveRightForRoll()
    {
        $lstrQuery = 'SELECT cr.*, cb.strName AS strBul
                      FROM       '.$this->getTablename().' AS cr
                      INNER JOIN '.LIBCore::getTableName('core_bul').' AS cb
                        ON cr.numBulID = cb.numBulID';
        if (LIBDB::query($lstrQuery)) {
            $larrReturn = LIBDB::getData();
            foreach ($larrReturn AS $lstrKey => $larrValue) {
                if (LIBCore::hasRight(
                    $larrValue['strCode'], $larrValue['strBul'], false
                )) {
                    $larrReturn[$lstrKey]['numRight'] = 1;
                } else {
                    $larrReturn[$lstrKey]['numRight'] = 0;
                }
            }
        } else {
            $larrReturn = array();
        }
        return $larrReturn;
    }

}
