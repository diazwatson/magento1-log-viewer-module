<?php

/**
 * Diazwatson_LogViewer
 *
 * @category    Diazwatson
 * @package     Diazwatson_LogViewer
 * @Date        05/2016
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

class Diazwatson_Logviewer_Adminhtml_Log_ViewerController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Log Viewer
     */
    public function indexAction()
    {
        $this->loadLayout();

        $this->_setActiveMenu('system');

        $this->renderLayout();
    }

    /**
     * Tail log
     *
     * @return Zend_Controller_Response_Abstract
     */
    public function tailAction()
    {
        $r = $this->getRequest();

        if (!$r->getParam('file')) {
            $this->getResponse()
                ->setBody('<html><head><title></title></head><body><pre>' .
                    Mage::helper('logviewer')->__('Please choose a file.') . '</pre></body></html>');

            return;
        }

        $f = Mage::helper('logviewer')->getLogsPath() . DS . $r->getParam('file');

        $numberOfLines = 200;
        $handle = fopen($f, "r");
        $lineCounter = $numberOfLines;
        $pos = - 2;
        $beginning = false;
        $text = array();
        while ($lineCounter > 0) {
            $t = " ";
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == - 1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos --;
            }
            $lineCounter --;
            if ($beginning) {
                rewind($handle);
            }
            $text[$numberOfLines - $lineCounter - 1] = fgets($handle);
            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        $dlFile = '<a href="' . Mage::helper('adminhtml')->getUrl('adminhtml/log_viewer/downloadFile',
                array('f' => $r->getParam('file'))) . '">' . $this->__('Download file') . '</a>';

        return $this->getResponse()->setBody('<html>
                                                <head>
                                                    <title></title>
                                                    <!--<meta http-equiv="refresh" content="10">-->
                                                </head>
                                                <body>
                                                    <pre>' . $dlFile . "\r\n\n" . strip_tags(implode('', $text)) . '</pre>
                                                </body>
                                            </html>');

    }

    /**
     * Download log file
     */
    public function downloadFileAction()
    {
        $fileName = $this->getRequest()->getParam('f');
        if (is_null($fileName)) {
            return;
        }

        $file = Mage::helper('logviewer')->getLogsPath() . DS . $fileName;

        $this->_prepareDownloadResponse($fileName, file_get_contents($file), 'text/plain', filesize($file));
    }
}