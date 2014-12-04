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
      
  Attribution Notice: Mercent_Export_Model_Export_Adapter_Tab 
                                    is an original concrete instance of the Mage_ImportExport_Model_Export_Adapter_Abstract
                                    abstract class developed by Magento
*********************************************************/

/**
 * Export adapter tab (txt).
 *
 * @category    Mercent
 * @package     Mercent_Export
 * @author       Tara Goshi and Kathy Farah  email questions to: support@mercent.com
 */
class Mercent_Export_Model_Export_Adapter_Tab extends Mage_ImportExport_Model_Export_Adapter_Abstract
{
    /**
            * Field delimiter.
            *
            * @var string
            */
    protected $_delimiter = "\t";

    /**
            * Field enclosure character.
            *
            * @var string
            */
    protected $_enclosure = '"';

    /**
            * Source file handler.
            *
            * @var resource
            */
    protected $_fileHandler;

    /**
            * Object destructor.
            *
            * @return void
            */
    public function __destruct()
    {
        if (is_resource($this->_fileHandler)) {
            fclose($this->_fileHandler);
        }
    }

    /**
            * Optional
            * used as a lookup table between internal and external field names 
            */
    protected $_columnLookup=null;
    /**
            * Method called as last step of object instance creation. Can be overrided in child classes.
            *
            * @return Mage_ImportExport_Model_Export_Adapter_Abstract
            */
    protected function _init()
    {
        $this->_fileHandler = fopen($this->_destination, 'w');
        return $this;
    }

    /**
            * MIME-type for 'Content-Type' header.
            *
            * @return string
            */
    public function getContentType()
    {
        return 'text/csv';
    }

    /**
            * Return file extension for downloading.
            *
            * @return string
            */
    public function getFileExtension()
    {
        return 'txt';
    }

    /**
            * Set column names.
            *
            * @param array $colNames -- hash used to specify a look-up table for internal field name to output column header name
            *                                              --  key->field name, value->output name                   
            * @throws Exception
            * @return Mage_ImportExport_Model_Export_Adapter_Abstract
            */
    public function setHeaderColsWithLookup(array $colNames)
    {
        if (null !== $this->_headerCols) {
            Mage::throwException(Mage::helper('importexport')->__('Header column names already set'));
        }
        if (null === $colNames) {
            Mage::throwException(Mage::helper('importexport')->__('No data to use to set header columns'));
        }
        
        $this->_columnLookup = array();
        foreach (array_keys($colNames) as $colName) {
            $this->_columnLookup[$colName] = $colNames[$colName];
            $this->_headerCols[$colName] = false;
        }
        fputcsv($this->_fileHandler, $this->_columnLookup, $this->_delimiter, $this->_enclosure);

        return $this;
    }
    /**
            * Write row data to source file.
            *
            * @param array $rowData
            * @throws Exception
            * @return Mage_ImportExport_Model_Export_Adapter_Abstract
            */
    public function writeRow(array $rowData)
    {
        if (null === $this->_headerCols) {
            $this->setHeaderCols(array_keys($rowData));
        }

        if($this->_columnLookup !== null)
        {
            $dataLookup = array();
            foreach(array_keys($this->_columnLookup) as $colName)
            {
                if(array_key_exists($colName,$rowData))
                {
                    $dataLookup[$colName] = $rowData[$colName];
                }else{
                    $dataLookup[$colName] = null;
                }
            }
            fputcsv(
                $this->_fileHandler,
                $dataLookup,
                $this->_delimiter,
                $this->_enclosure
            );
        }else{
            fputcsv(
                $this->_fileHandler,
                    array_merge($this->_headerCols, array_intersect_key($rowData, $this->_headerCols)),
                    $this->_delimiter,
                    $this->_enclosure
            );
        }
        return $this;
    }
}
