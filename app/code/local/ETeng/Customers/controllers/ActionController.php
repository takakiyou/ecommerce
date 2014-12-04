<?php
class ETeng_Customers_ActionController extends Mage_Core_Controller_Front_Action {

  /**
   * Customer register action
   */
  public function createAction() 
  {
    $login = $this->getRequest()->getPost();

    // echo $login;

    $websiteId = Mage::app()->getWebsite()->getId();
    $store = Mage::app()->getStore();
   
    $customer = Mage::getModel("customer/customer");

    $customer   ->setWebsiteId($websiteId)
                ->setStore($store)
                ->setFirstname($login['firstname'])
                ->setLastname($login['lastname'])
                ->setEmail($login['email'])
                ->setPassword($login['password']);
   
    try{
        $customer->save();
    }
    catch (Exception $e) {
        Zend_Debug::dump($e->getMessage());
    }
  }

  /*
   * Customer login action
   */
  public function loginAction() {

    $username = $this->getRequest()->getParam('username');
    $password = $this->getRequest()->getParam('password');

    $websiteId = Mage::app()->getWebsite()->getId();
    $store = Mage::app()->getStore();

    $customer = Mage::getModel("customer/customer");

    $customer->setWebsiteId($websiteId)
             ->setStore($store);

    if($customer->authenticate($username,$password)){
      echo 'yoo';
    }
    else{
      echo 'wuu';
    }
  }

}