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
            
  Attribution Notice: Mercent_Export_Model_Export_Entity_Product
                                    extends the Mage_ImportExport_Model_Export_Entity_Product
                                    developed by Magento
                                    Specifically Mercent has added the following function: mercentRepricingExport
*********************************************************/

/**
 * Export Product
 *
 * @category    Mercent
 * @package     Mercent_Export
 * @author       Tara Goshi and Kathy Farah  email questions to: support@mercent.com
 */
class Mercent_Export_Model_Export_Entity_Product extends Mage_ImportExport_Model_Export_Entity_Product
{
    // This function generates the price optimizer feed for a given store's products
    // Parameters:
    // $storeId => the id for the store we are generating the feed for
    public function mercentRepricingExport($storeId)
    {
        // field look up table for the price optimizer feed
         $attributeParams = array('sku'   => 'SKU', 'brand' => 'Brand','manufacturer' => 'Manufacturer',
                                  'name'  => 'Title','msrp' => 'MSRP',
                                  'price' => 'StandardPrice','cost' => 'COGS',
                                  'map' => 'MAP','merchant_category' => 'MerchantCategory', 
                                  'qty'=>'Quantity','is_in_stock' => 'InStock', 'amazon_sku' => 'AmazonSKU');
        //Execution time may be very long
        set_time_limit(0);

        /** @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection */
        $validAttrCodes  = $this->_getExportAttrCodes();
        $writer          = $this->getWriter();

        // limit valid attributes to the ones  used for the repricing feed
        // looping through to preserver order
        $temp = array();
        $i=0;
        foreach ($validAttrCodes as &$attrCode)
        {
            if(array_key_exists($attrCode,$attributeParams))
            {
                $temp[$i]=$attrCode;
                $i++;
            }
        }
        $validAttrCodes = $temp;

        $memoryLimit = trim(ini_get('memory_limit'));
        $lastMemoryLimitLetter = strtolower($memoryLimit[strlen($memoryLimit)-1]);
        switch($lastMemoryLimitLetter) {
            case 'g':
                $memoryLimit *= 1024;
            case 'm':
                $memoryLimit *= 1024;
            case 'k':
                $memoryLimit *= 1024;
                break;
            default:
                // minimum memory required by Magento
                $memoryLimit = 250000000;
        }

        // Tested one product to have up to such size
        $memoryPerProduct = 100000;
        // Decrease memory limit to have supply
        $memoryUsagePercent = 0.8;
        // Minimum Products limit
        $minProductsLimit = 500;

        $limitProducts = intval(($memoryLimit  * $memoryUsagePercent - memory_get_usage(true)) / $memoryPerProduct);
        if ($limitProducts < $minProductsLimit) {
            $limitProducts = $minProductsLimit;
        }
        $offsetProducts = 0;
        $col_category = 'merchant_category';
        $col_type = 'product_type';

        while (true)
        {
            ++$offsetProducts;

            $dataRows        = array();
            $rowCategories   = array();

            $collection = $this->_prepareEntityCollection(Mage::getResourceModel('catalog/product_collection'))->addStoreFilter($storeId);
            $collection
                ->setStoreId($storeId)
                ->setPage($offsetProducts, $limitProducts);
            if ($collection->getCurPage() < $offsetProducts) {
                break;
            }
            $collection->load();

            if ($collection->count() == 0) {
                break;
            }

            $collection->addCategoryIds()->addWebsiteNamesToResult();

            foreach ($collection as $itemId => $item) { // go through all products
                $rowIsEmpty = true; // row is empty by default

                foreach ($validAttrCodes as &$attrCode) { // go through all valid attribute codes
                    $attrValue = $item->getData($attrCode);

                    if (!empty($this->_attributeValues[$attrCode])) {
                        if (isset($this->_attributeValues[$attrCode][$attrValue])) {
                            $attrValue = $this->_attributeValues[$attrCode][$attrValue];
                        } else {
                            $attrValue = null;
                        }
                    }
                    if (is_scalar($attrValue)) {
                        $dataRows[$itemId][$storeId][$attrCode] = $attrValue;
                        $rowIsEmpty = false; // mark row as not empty
                    }
                }
                if ($rowIsEmpty) { // remove empty rows
                    unset($dataRows[$itemId][$storeId]);
                } else {
                    $dataRows[$itemId][$storeId][$col_type] = $item->getTypeId();
                    $rowCategories[$itemId] = $item->getCategoryIds();
                }
                $item = null;
            }
            $collection->clear();

            if ($collection->getCurPage() < $offsetProducts) {
                break;
            }

            // remove root categories
            foreach ($rowCategories as $productId => &$categories) {
                $categories = array_intersect($categories, array_keys($this->_categories));
            }
            // prepare catalog inventory information
            $productIds = array_keys($dataRows);
            $stockItemRows = $this->_prepareCatalogInventory($productIds);

            if ($offsetProducts == 1) {
                // set up column headers
                $writer->setHeaderColsWithLookup($attributeParams);
            }
            //write out data
            foreach ($dataRows as $productId => &$productData) {
                foreach ($productData as $storeId => &$dataRow) {
                    //add inventory
                    $dataRow += $stockItemRows[$productId];
                    // select the longest category to send
                    if ($rowCategories[$productId]) {
                        $catCount=count($rowCategories[$productId]);
                        $longestCatLength=0;
                        $longestCat = "";
                        for ($i = 0; $i < $catCount; $i++)
                        {
                            $currCat=$this->_categories[$rowCategories[$productId][$i]];
                            if(strlen($currCat) > $longestCatLength)
                            {
                                $longestCat=$currCat;
                                $longestCatLength=strlen($currCat);
                            }
                       }
                       $dataRow[$col_category]=$longestCat;
                    }
                    $writer->writeRow($dataRow);
                }
            }
        }
        return $writer->getContents();
    }
}