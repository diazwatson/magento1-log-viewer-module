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

class Diazwatson_LogViewer_Block_Adminhtml_Logs extends Mage_Core_Block_Template
{

    /**
     * Generate log select
     *
     * @return string
     */
    public function getLogFilesSelect()
    {

        $logPath = $this->_getLogPath();
        $logFiles = array();

        if (file_exists($logPath)) {
            foreach (new DirectoryIterator($logPath) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }

                if (preg_match('/[(.log)(.logs)]$/', $fileInfo->getFilename())) {
                    $logFiles [] = array('file' => $fileInfo->getPathname(), 'filename' => $fileInfo->getFilename());
                }
            }
        }

        if (empty($logFiles)) {
            return $this->__('No log files found');
        }

        $html = '<label for="rl-log-switcher">' . $this->__('Please, choose a file:') . '</label><select id="rl-log-switcher" name="rl-log-switcher"><option value=""></option>';

        foreach ($logFiles as $l) {
            $html .= '<option value="' . $this->getTailUrl(array('file' => $l['filename'])) . '">' . $l['filename'] . '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Returns path to Magento Log dir
     *
     * @return string
     */
    private function _getLogPath()
    {
        return Mage::helper('logviewer')->getLogsPath();
    }

    /**
     * Get tail url
     *
     * @param array $params
     *
     * @return string
     */
    public function getTailUrl(array $params = array())
    {
        $params ['_secure'] = true;

        return $this->helper('adminhtml')->getUrl('adminhtml/log_viewer/tail', $params);
    }
}