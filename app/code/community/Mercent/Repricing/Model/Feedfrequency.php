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
            
*********************************************************/

/**
 * FeedFrequency
 *
 * @category    Mercent
 * @package     Mercent_Repricing
 * @author       Tara Goshi and Kathy Farah  email questions to: support@mercent.com
 */
class Mercent_Repricing_Model_FeedFrequency
{
    // This function sets up the options for the cron job that is running the price optimizer feed
    public function toOptionArray()
    {
        return array(
            array('value'=>24, 'label'=>Mage::helper('repricing')->__('Daily')),
            array('value'=>4, 'label'=>Mage::helper('repricing')->__('4 Hours')),
            array('value'=>6, 'label'=>Mage::helper('repricing')->__('6 Hours')),
            array('value'=>12, 'label'=>Mage::helper('repricing')->__('12 Hours')),
        );
    }
}
