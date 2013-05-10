<?php
namespace racore\phplibs\core;

require_once 'phplibs/PHPWord-0.6.2/PHPWord/Template.php';

/**
 * Klasse für PHPWord->Template
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       01.05.13
 * @version     1.00
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Core
 */
class LIBWordTemplate extends \PHPWord_Template
{
    /**
     * ZipArchive
     *
     * @var \ZipArchive
     */
    private $_objZip;

    /**
     * Temporary Filename
     *
     * @var string
     */
    private $_tempFileName;

    /**
     * Document XML
     *
     * @var string
     */
    private $_documentXML;


    /**
     * Create a new Template Object
     *
     * @param string $strFilename
     */
    public function __construct($strFilename)
    {
        $path = dirname($strFilename);
        $this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.docx';
        copy($strFilename, $this->_tempFileName);
        $this->_objZip = new \ZipArchive();
        $this->_objZip->open($this->_tempFileName);
        $this->_documentXML = $this->_objZip->getFromName('word/document.xml');
    }

    /**
     * Setzt das XML
     *
     * @param string $pcxmlstring
     */
    public function setDocumentXML($pcxmlstring)
    {
        $this->_documentXML = $pcxmlstring;
    }

    /**
     * Gibt das XML zruück
     *
     * @return string
     */
    public function getDocumentXML()
    {
        return $this->_documentXML;
    }

    /**
     * Set a Template value
     *
     * @param mixed $search
     * @param mixed $replace
     */
    public function setValue($search, $replace)
    {
        trigger_error('setValue sollte nicht verwendet werden');
        if (substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${'.$search.'}';
        }

        if (!is_array($replace)) {
            $replace = utf8_encode($replace);
        }
        $this->_documentXML = str_replace(
            $search, $replace, $this->_documentXML
        );
    }

    /**
     * Clone a table row
     *
     * @param mixed $search
     * @param mixed $numberOfClones
     */
    public function cloneRow($search, $numberOfClones)
    {
        if (substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${'.$search.'}';
        }
        $tagPos = strpos($this->_documentXML, $search);
        $rowStartPos = strrpos(
            $this->_documentXML, "<w:tr", (
                (strlen($this->_documentXML) - $tagPos) * -1)
        );
        $rowEndPos = strpos($this->_documentXML, "</w:tr>", $tagPos) + 7;

        $result = substr($this->_documentXML, 0, $rowStartPos);
        $xmlRow = substr(
            $this->_documentXML, $rowStartPos, ($rowEndPos - $rowStartPos)
        );
        for ($i = 1; $i <= $numberOfClones; $i++) {
            $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#'.$i.'}', $xmlRow);
        }
        $result .= substr($this->_documentXML, $rowEndPos);
        $this->_documentXML = $result;
    }

    /**
     * Save Template
     *
     * @param string $strFilename
     *
     * @throws \Exception
     */
    public function save($strFilename)
    {
        if (file_exists($strFilename)) {
            unlink($strFilename);
        }
        $this->_objZip->addFromString('word/document.xml', $this->_documentXML);

        // Close zip file
        if ($this->_objZip->close() === false) {
            throw new \Exception('Could not close zip file.');
        }

        rename($this->_tempFileName, $strFilename);
    }

    /**
     * Ersetzt einfach ein Bookmark
     *
     * @param $pstrXML
     * @param $pstrBookmark
     * @param $pstrReplace
     *
     * @return string
     */
    public function replaceBookmarkValue($pstrXML, $pstrBookmark, $pstrReplace)
    {
        $lstrRegex = '/(<w:bookmarkStart.*?w:name="'.$pstrBookmark.'".*?>)|';
        $lstrRegex .= '(<w:bookmarkEnd.*?>)/i';
        $lstrStartRegex = '/(<w:bookmarkStart.*?w:name="';
        $lstrStartRegex .= $pstrBookmark.'".*?>)/i';
        $lstrEndRegex = '/(<w:bookmarkEnd.*?>)/i';

        $larrSplit = $this->getArrayByRegex($pstrXML, $lstrRegex);
        $lnumFind = 0;
        $lstrReturn = '';
        foreach ($larrSplit AS $lstrData) {
            if (preg_match($lstrEndRegex, $lstrData)) {
                $lnumFind = 0;
            }
            if ($lnumFind == 1) {
                $lstrData = $this->replaceBetween(
                    $lstrData, 'w:t', $pstrReplace
                );
            }
            if (preg_match($lstrStartRegex, $lstrData)) {
                $lnumFind = 1;
            }
            $lstrReturn .= $lstrData;
        }
        return $lstrReturn;
    }

    /**
     * Ersetzt einen Teil zwischen zwei Punkten
     *
     * @param $pstrXML
     * @param $pstrRegex
     * @param $pstrReplace
     *
     * @return string
     */
    public function replaceBetween($pstrXML, $pstrRegex, $pstrReplace)
    {
        $lstrSplit = '(<'.$pstrRegex.'.*?>|<\/'.$pstrRegex.'>)';
        $larrSplit = preg_split($lstrSplit, $pstrXML);
        $larrSplit[1] = '<'.$pstrRegex.'>'.$pstrReplace.'</'.$pstrRegex.'>';
        return implode('', $larrSplit);
    }

    /**
     * Gibt den Teil zwischen den Regex zurück
     *
     * @param $pstrXML
     * @param $pstrRegex
     *
     * @return mixed
     */
    public function getBetween($pstrXML, $pstrRegex)
    {
        $lstrSplit = '(<'.$pstrRegex.'.*?>|<\/'.$pstrRegex.'>)';
        $larrSplit = preg_split($lstrSplit, $pstrXML);
        return $larrSplit[1];
    }

    /**
     * Gibt ein Array zurück gemäss dem Regex
     *
     * @param $pstrXML
     * @param $pstrRegex
     *
     * @return array
     */
    public function getArrayByRegex($pstrXML, $pstrRegex)
    {
        $larrSplit = preg_split(
            $pstrRegex,
            $pstrXML,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );
        $larrReturn = array();
        foreach ($larrSplit AS $larrValue) {
            if ($larrValue != '') {
                array_push($larrReturn, $larrValue);
            }
        }
        return $larrReturn;
    }

    public function getArrayByRegexTag($pstrXML, $pstrRegex)
    {
        $lstrSplit = '/(<'.$pstrRegex.'.*?>|<\/'.$pstrRegex.'.*?>)/i';
        $larrSplit = preg_split(
            $lstrSplit,
            $pstrXML,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );
        $larrReturn = array();
        foreach ($larrSplit AS $larrValue) {
            if ($larrValue != '') {
                array_push($larrReturn, $larrValue);
            }
        }
        return $larrReturn;
    }

    /**
     * Gibt den zu Kopierenden Teil zurück
     *
     * @param $pstrXML
     * @param $pstrName
     *
     * @return string
     */
    public function getCopyData($pstrXML, $pstrName)
    {
        $lstrStartBookmark = '(<w:bookmarkStart.*?w:name="start_loop_';
        $lstrStartBookmark .= $pstrName.'".*?>)';
        $lstrEndBookmark = '(<w:bookmarkStart.*?w:name="end_loop_';
        $lstrEndBookmark .= $pstrName.'".*?>)';
        $larrSplit = $this->getArrayByRegexTag($pstrXML, 'w:p');
        $lnumFind = 0;
        $lstrCopyData = '';
        $lstrLastData = '';
        foreach ($larrSplit AS $lstrData) {
            if (preg_match('/'.$lstrStartBookmark.'/', $lstrData)) {
                $lnumFind = 1;
            }
            if (preg_match('/'.$lstrEndBookmark.'/', $lstrData)) {
                $lstrLastData = '';
                $lnumFind = 3;
            }
            if ($lnumFind == 2) {
                if ($lstrLastData != '') {
                    $lstrCopyData .= $lstrLastData;
                }
                $lstrLastData = $lstrData;
            }
            if ($lnumFind == 1 AND preg_match('/<\/w:p>/', $lstrData)) {
                $lnumFind = 2;
            }
        }
        $lstrCopyData .= $lstrLastData;
        return $lstrCopyData;
    }

    /**
     * Loopt das Template durch
     *
     * @param $pstrXML
     * @param $parrData
     * @param $pstrName
     *
     * @return mixed
     */
    public function loopTemplate($pstrXML, $parrData, $pstrName)
    {

        $lstrXMLBody = $this->getBetween($pstrXML, 'w:body');

        // Zu Kopierende Daten
        $lstrXMLToCopy = $this->getCopyData($lstrXMLBody, $pstrName);

        $lstrReplace = '';
        foreach ($parrData AS $lstrLooper) {
            foreach ($lstrLooper AS $lstrKey => $lstrValue) {
                $lstrReplace .= $this->replaceBookmarkValue(
                    $lstrXMLToCopy, $lstrKey, $lstrValue
                );
            }
        }

        $lstrXML = str_replace($lstrXMLToCopy, $lstrReplace, $pstrXML);
        return $lstrXML;
    }

    /**
     * Fült die Daten ban
     *
     * @param $pstrName
     * @param $parrData
     *
     * @return bool
     */
    public function fillData($pstrName, $parrData)
    {
        $lstrXML = $this->getDocumentXML();
        if (LIBValid::isArray($parrData)) {
            $lstrXML = $this->loopTemplate($lstrXML, $parrData, $pstrName);
        } else {
            $lstrXML = $this->replaceBookmarkValue(
                $lstrXML, $pstrName, $parrData
            );
        }
        $lstrXML = $this->removeBookmarkByName($lstrXML, 'example');
        $this->setDocumentXML($lstrXML);
        return true;
    }

    /**
     * Ersetzt alle Bookmarks
     *
     */
    public function removeAllBookmarks()
    {
        $lstrXML = $this->getDocumentXML();
        $lstrStartBookmark = '/(<w:bookmarkStart.*?>|';
        $lstrEndBookmark = '<w:bookmarkEnd.*?)/';
        $lstrXML = preg_replace(
            $lstrStartBookmark.$lstrEndBookmark, '', $lstrXML
        );
        $this->setDocumentXML($lstrXML);
    }

    public function removeBookmarkByName($pstrXML, $pstrName)
    {
        $lstrBookmark = '/(<w:bookmarkStart.*?w:name="start_loop_';
        $lstrBookmark .= $pstrName.'".*?';
        $lstrBookmark .= '<w:bookmarkStart.*?w:name="end_loop_';
        $lstrBookmark .= $pstrName.'".*?>)/';
        return preg_replace($lstrBookmark, '', $pstrXML);
    }

}
