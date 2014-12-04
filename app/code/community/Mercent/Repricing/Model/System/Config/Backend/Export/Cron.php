<?php
/****************************************************
  Copyright (c) Mercent Corporation.  All rights reserved.
  THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
  KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
  IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
  PARTICULAR PURPOSE.
  @author Tara Goshi and Kathy Farah 
  Date: 2012-04-10
  Summary: Integrates Magento Sites with Mercent Retail Price Optimizer
  SCOPE OF LICENSE
  The software is licensed, not sold. This agreement only gives you some rights to use the software. Mercent reserves all other rights.
  You may not 
      * reverse engineer, decompile or disassemble the software, except and only to the extent that applicable law expressly permits, despite this limitation;
      * publish the software for others to copy;
      * rent, lease or lend the software;
      * transfer the software or this agreement to any third party; or
      * use the software for commercial software hosting services.
            
  Attribution Notice: Mercent_Repricing_Model_System_Config_Backend_Export_Cron 
                                    is an extension of the Mage_Core_Model_Config_Data class 
                                    developed by Magento
                                    Specifically Mercent overrode the _afterSave function
*********************************************************/

/**
 * Cron 
 *
 * @category    Mercent
 * @package     Mercent_Repricing
 * @author       Tara Goshi and Kathy Farah  email questions to: support@mercent.com
 */
class Mercent_Repricing_Model_System_Config_Backend_Export_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH  = 'crontab/jobs/mercent_repricing_export/schedule/cron_expr';
    const CRON_MODEL_PATH   = 'crontab/jobs/mercent_repricing_export/run/model';
 
   /**
    * Set Cron settings after save
    *
    * @return Mage_Adminhtml_Model_System_Config_Backend_Log_Cron
    */
    protected function _afterSave()
    {
        $enabled    = $this->getData('groups/repricingschedule_group/fields/repricingschedule_active/value');
        $time       = $this->getData('groups/repricingschedule_group/fields/repricingschedule_time/value');
        $hour       = intval($time[0]);
        $minute     = intval($time[1]);
        $frequency  = intval($this->getData('groups/repricingschedule_group/fields/repricingschedule_frequency/value'));

        if (empty($frequency))
        {
            $frequency = '24'; //if not set, use daily
        }
 
        $hour = $hour % $frequency; //get the base hour to run
        $hoursCronExp = '';

        while ($hour < 24)
        {
            $hoursCronExp .= ($hoursCronExp=='' ? '' : ',').$hour;
            $hour += $frequency;
        }

        if ($enabled) {
            $cronDayOfWeek = date('N');
            $cronExprArray = array(
                $minute,                                                    # Minute
                $hoursCronExp,                                              # Hour
                '*',                                                        # Day of the Month
                '*',                                                        # Month of the Year
                '*',                                                        # Day of the Week
            );
            $cronExprString = join(' ', $cronExprArray);
        }
        else {
            $cronExprString = '';
        }
 
        try {
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();
 
            Mage::getModel('core/config_data')
                ->load(self::CRON_MODEL_PATH, 'path')
                ->setValue((string) Mage::getConfig()->getNode(self::CRON_MODEL_PATH))
                ->setPath(self::CRON_MODEL_PATH)
                ->save();
        }
        catch (Exception $e) {
            Mage::throwException('Unable to save the cron expression.');
        }
    }
}