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

class Diazwatson_LogViewer_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Returns path to Magento Log dir
     *
     * @return string
     */
    public function getLogsPath()
    {
        return Mage::getBaseDir('log');
    }
}