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
            
  Attribution Notice: Mercent_Export_Model_Export
                                    extends the Mage_ImportExport_Model_Export
                                    developed by Magento
                                    Specifically Mercent has added the following function: mercentRepricingExport
*********************************************************/

/**
 * Export 
 *
 * @category    Mercent
 * @package     Mercent_Export
 * @author       Tara Goshi and Kathy Farah  email questions to: support@mercent.com
 */
class Mercent_Export_Model_Export extends Mage_ImportExport_Model_Export
{
    // This function calls the export that generates the price optimizer feed from the products for a given store
    // Parameters:
    // storeId => This is the id for the store  for which we are generating the price optimizer feed
    public function mercentRepricingExport($storeId)
    { 
        if (isset($this->_data[self::FILTER_ELEMENT_GROUP])) {
            $this->addLogComment(Mage::helper('importexport')->__('Begin export of %s', $this->getEntity()));
            $result = $this->_getEntityAdapter()
                ->setWriter($this->_getWriter())
                ->mercentRepricingExport($storeId);
            $countRows = substr_count(trim($result), "\n");
            if (!$countRows) {
                Mage::throwException(
                    Mage::helper('importexport')->__('There is no data for export')
                );
            }
            if ($result) {
                $this->addLogComment(array(
                    Mage::helper('importexport')->__('Exported %s rows.', $countRows),
                    Mage::helper('importexport')->__('Export has been done.')
                ));
            }
            return $result;
        } else {
            Mage::throwException(
                Mage::helper('importexport')->__('No filter data provided')
            );
        }
    }
}