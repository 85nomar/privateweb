<?php
namespace racore\phplibs\core;

require_once 'phplibs/PHPWord-0.6.2/PHPWord.php';

/**
 * Klasse für PHPWord
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       01.05.13
 * @version     1.00
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 *
 *
 * ERST IN TESTPHASE läuft noch nicht alles...
 *
 */
class LIBWord extends \PHPWord
{

    private $_arrData = array();

    /**
     * @var LIBWordTemplate|null
     *
     */
    private $_objDocument = null;

    private $_strTemplate = '';

    public function getData()
    {
        return $this->_arrData;
    }

    public function setData($parrData)
    {
        $this->_arrData = $parrData;
    }

    public function setTemplate($pstrTemplate)
    {
        $this->_strTemplate = $pstrTemplate;
    }

    public function getTemplate()
    {
        return $this->_strTemplate;
    }

    public function loadTemplate($strFilename)
    {
        if (file_exists($strFilename)) {
            $template = new LIBWordTemplate($strFilename);
            return $template;
        } else {
            trigger_error('Template file '.$strFilename.' not found.');
        }
    }


    public function download($pcfilename = 'Example.docx')
    {
        $lcfilename = $pcfilename;
        unset($pcpath, $pcfilename);

        /**
         * Aufbereitung des Dokumentes
         */
        $this->_prepareDocument();
        $this->_fillData();

        //$this->_objDocument->replaceBookmarkValue('name', 'name', 'xyznamexyz');





        //LIBCore::print_r(htmlentities($this->_objDocument->getDocumentXML()));
        //echo htmlentities($this->_objDocument->getDocumentXML()).'<hr>';
        //exit;

        $this->_objDocument->save($lcfilename);


        /**
         * Versendung des Dokumentes
         */
        header('Content-Description: File Transfer');
        header('Content-type: application/force-download');
        header('Content-Disposition: attachment; filename='.basename($lcfilename));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.filesize($lcfilename));
        readfile($lcfilename);



        /**
         * Löschen des Dokumentes
         */
        unlink($lcfilename);
        exit;


    }


    private function _prepareDocument()
    {
        $lbreturn = false;
        if ($this->getTemplate() == '') {
            trigger_error('Es wurde kein Template angegeben');
        } else if (!file_exists($this->getTemplate())) {
            trigger_error('Das Template \''.$this->getTemplate().'\' existiert nicht');
        } else {
            $this->_objDocument = $this->loadTemplate($this->getTemplate());
        }
        return $lbreturn;
    }


    private function _fillData()
    {
        $lbreturn = true;

        $larrdata = $this->getData();
        foreach ($larrdata AS $lstrKey => $lobjValue) {
            $lbreturn = $this->_objDocument->fillData($lstrKey, $lobjValue);
        }

        $this->_objDocument->removeAllBookmarks();
        return $lbreturn;
    }







}
