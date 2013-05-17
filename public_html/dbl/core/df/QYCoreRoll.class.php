<?php
namespace racore\dbl\core\df;

use racore\bul\core\df\BULCoreRollUser;
use racore\phplibs\core\LIBCore;
use racore\phplibs\core\LIBDB;

/**
 * Query Roll
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       11.05.13
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 * @subpackage  Default
 */
class QYCoreRoll extends DBLCoreRoll
{
    /**
     * Gibt die Rollen für den Benutzer zurück
     *
     * Wenn der Paramteter userID mitübergeben wird, werden dessen Rollen
     * mitgegeben bzw. als Selected deklariert
     *
     * @param integer $pnumUserID
     * @return array
     * @access public
     */
    public function getRollenForUsers($pnumUserID = 0)
    {
        $lnumUserID = (integer) $pnumUserID;
        unset($pnumUserID);
        $ldblrr = new BULCoreRollUser();
        $lstrQuery = 'SELECT cr.numRollID,
                             cr.strName
                      FROM '.$this->getTablename().' AS cr
                      WHERE cr.numRollID > 0';
        LIBDB::query($lstrQuery);
        $larrReturn = LIBDB::getData();
        foreach ($larrReturn AS $lstrKey => $larrValue) {
            $larrRollRight = $ldblrr->getDbl()->getWhere(
                'numRollID = ' . $larrValue['numRollID'].
                ' AND numUserID = '.$lnumUserID
            );
            $lbooSelected = false;
            if (count($larrRollRight) > 0) {
                $lbooSelected = true;
            }
            $larrReturn[$lstrKey]['selected'] = $lbooSelected;
        }

        return $larrReturn;
    }

}
