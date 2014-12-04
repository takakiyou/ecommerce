<?php
class ETeng_Address_ActionController extends Mage_Core_Controller_Front_Action {
  // Add address action
  public function addAction() {
    // setting websiteId
    $websiteId = Mage::app()->getWebsite()->getId();
    // setting soteId
    // $store = Mage::app()->getStore();

    // $addressData =  array (
    //                 'firstname' => 'test',
    //                 'middlename' => 'test',
    //                 'lastname' => 'test',
    //                 'suffix' => 'tes',
    //                 'company' => 'test',
    //                 'street' => '34',
    //                 'city' => '234',
    //                 'country_id' => 'US',
    //                 'region' => 'Alabama',
    //                 'region_id' => 1,
    //                 'postcode' => 234,
    //                 'telephone' => 234,
    //                 'fax' => 234,
    //                 'is_default_billing' => 1,
    //             );

    $customer = Mage::getSingleton('customer/customer');
    $customer->setWebsiteId($websiteId);
    $customer->loadByEmail('lisi@gmail.com');
    $address = Mage::getModel('customer/address');
    // $address->addData($addressData);
    // $customer->addAddress($address);

    $_custom_address = array (
      'firstname' => 'li',
      'lastname' => 'si',
      'street' => array (
          '0' => 'Guan Yin Tang No.172',
          '1' => '',
      ),
      'city' => 'Beijing',
      'region_id' => '',
      'region' => '',
      'postcode' => '31000',
      'country_id' => 'CN', /* Croatia */
      'telephone' => '18922334455',
    );
    $address->setData($_custom_address)
                ->setCustomerId($customer->getId())
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');
    try {
        $address->save();
    }
    catch (Exception $ex) {
        Zend_Debug::dump($ex->getMessage());
    }
  }
}