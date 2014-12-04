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
 * Observer
 *
 * @category    Mercent
 * @package     Mercent_Export
 * @author       Tara Goshi and Kathy Farah  email questions to: support@mercent.com
 */
class Mercent_Repricing_Model_Observer
{
    // This function runs the price optimizer feed and ftp's it to Mercent, it sets up th environment and then calls the other functions necessary to generate and 
    // ftp the feed
    public static function runRepricingFeed()
    {
        $hasAnyFeedRun = 0;
        //Feed name and folder options
        $fileName = 'MercentRepricingFeed.txt';
        $fileDir = sprintf('%s/mercent_repricing', Mage::getBaseDir('media'));
        $file = sprintf('%s/%s', $fileDir, $fileName);
        $logFile = preg_replace('"\.txt$"', '_log.txt', $file);

        //Create output directory if not exists
        if(!file_exists($fileDir))
        {
            mkdir($fileDir);
            chmod($fileDir, 0777);
        }

        //Retrieve default mercent repricing settings
        //where Current Configuration Scope is Default Config
        //no storeId passed to getStoreConfig function
        $active = Mage::getStoreConfig('repricingconfig/repricing_group/repricing_active');
        $merchantId = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_account'));
        $host = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_ftpserver'));
        $folder = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_ftpfolder'));
        //Include MerchantID in folder path
        $folder = str_replace('{{merchant_id}}', $merchantId, $folder);
        //Include '/' at the end of folder name if not already included
        $folder = ($folder[strlen($folder)-1] == '/') ? $folder : $folder.'/';
        $password = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_ftppassword'));
        $port = 21;

        file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." Repricing Feed Starting\n");//, FILE_APPEND);

        if ($active && !empty($merchantId) && !empty($host) && !empty($password))
        {
            $hasAnyFeedRun = 1;
            file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." Run repricing feed for default (no store id specified)\n", FILE_APPEND);
            self::getAndFtpFeed($host, $port, $merchantId, $password, $folder, $file, $fileName, Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID, $logFile);
        }

        //Loop through each store and run if enabled and if MerchantID is different than default config
        foreach (Mage::app()->getStores() as $store)
        {
            $storeId = $store->getId();
            $activeStore = Mage::getStoreConfig('repricingconfig/repricing_group/repricing_active', $storeId);
            $merchantIdStore = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_account', $storeId));
            $hostStore = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_ftpserver', $storeId));
            $folderStore = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_ftpfolder', $storeId));
            //Include MerchantID in folder path
            $folderStore = str_replace('{{merchant_id}}', $merchantIdStore, $folderStore);
            //Include '/' at the end of folder name if not already included
            $folderStore = ($folderStore[strlen($folderStore)-1] == '/') ? $folderStore : $folderStore.'/';
            $passwordStore = trim(Mage::getStoreConfig('repricingconfig/repricing_group/repricing_ftppassword', $storeId));

            //If the store is active but the default is not active then run for the store
            //If the store is active and the default is active, run only if merchantId is different
            if ($activeStore && (!$active || $merchantId != $merchantIdStore) && !empty($merchantId)  && !empty($hostStore) && !empty($passwordStore))
            {
                $hasAnyFeedRun = 1;
                file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time())).' Run repricing feed for StoreID: ' . $storeId . "\n", FILE_APPEND);
                self::getAndFtpFeed($hostStore, $port, $merchantIdStore, $passwordStore, $folderStore, $file, $fileName, $storeId, $logFile);
            }
        }

        if (!$hasAnyFeedRun)
        {
            file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." Schedule Enabled but no feed enabled or ftp settings not provided\n", FILE_APPEND);
        }

        file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." Repricing Feed Complete\n\n", FILE_APPEND);
    }

    // This is the function that calls getFeed to generate the feed and then calls ftpFeed to FTP the feed to Mercent. It also prints log messages for success and failure.
    // Parameters:
    // $host => Mercent Server
    // $port => FTP port
    // $username => FTP user name
    // $passaword => FTP password
    // $folder => folder on FTP server where feed will be delivered
    // $file => price optimizer feed
    // $storeId => the store id  for the store that the feed was generated from
    // $logFile => log file
    private static function getAndFtpFeed($host, $port, $username, $password, $folder, $file, $fileName, $storeId, $logFile)
    {
        if (self::getFeed($file, $storeId) == 1)
        {
            file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." File ".$file." has been generated\n", FILE_APPEND);

            if (self::ftpFeed($host, $port, $username, $password, $folder, $file, $fileName))
            {
                file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." FTP of ".$fileName." to ".$host." complete\n", FILE_APPEND);
            }
            else
            {
                file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." FTP of ".$fileName." to ".$host." UNSUCCESSFUL.  See system.log for details.\n", FILE_APPEND);
            }
        }
        else
        {
            file_put_contents($logFile, date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(time()))." File ".$file." has FAILED to generate\n", FILE_APPEND);
        }

        if(file_exists($file))
        {
            unlink($file);
        }
    }
    
    // This is the function that calls the export function to generate the feed for a given store with a given file name
    // Parameters:
    // $file => name of file to write the feed data too
    // $storeID => store id for the store we are generating the feed from
    //
    // Note: Disabled products are excluded from the feed
    private static function getFeed($file, $storeId)
    {
        try
        {
            // get model for export and set export parameters
            $model = Mage::getModel('importexport/export'); 
            $model->setEntity('catalog_product');
            $exportData = array
            (
                'entity' => 'catalog_product',
                'file_format' => 'mtab',
                'export_filter' => array
                    (
                        'status' => '1'
                    )
            );

            $model->setData($exportData); 

            // open the file for the feed for writing and write the export data to it
            if ($f = fopen($file, 'w'))
            {
                $result = $model->mercentRepricingExport($storeId);
                fwrite($f, $result);
                fclose($f);
            }
            else
            {
                throw new Exception("Could not open: ".$file." for writing.");
            }

        }
        catch (Exception $e)
        {
            Mage::logException($e);
            return 0;
        }
        return 1;
    }

    // This is the function that ftp's the feed to Mercent
    //  Parameters:
    // $host => Mercent Server
    // $port => FTP port
    // $username => FTP user name
    // $passaword => FTP password
    // $folder => folder on FTP server where feed will be delivered
    // $file => price optimizer feed file
    // $fileName => name of the price optimizer feed file 
    private static function ftpFeed($host, $port, $username, $password, $folder, $file, $fileName)
    {
        if($connection = ftp_connect($host, $port))
        {
            $login = ftp_login($connection, $username, $password);
            ftp_pasv($connection, true);
            return ftp_put($connection, $folder.$fileName, $file, FTP_BINARY);
        }
    }
}