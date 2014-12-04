<?php
class ETeng_Orders_ActionController extends Mage_Core_Controller_Front_Action {
  // Order create acton
  public function addAction() {
    $id=1; // get Customer Id
    $customer = Mage::getModel('customer/customer')->load($id);

    $transaction = Mage::getModel('core/resource_transaction');
    $storeId = $customer->getStoreId();
    $reservedOrderId = Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId($storeId);

    $order = Mage::getModel('sales/order')
      ->setIncrementId($reservedOrderId)
      ->setStoreId($storeId)
      ->setQuoteId(0)
      ->setGlobal_currency_code('USD')
      ->setBase_currency_code('USD')
      ->setStore_currency_code('USD')
      ->setOrder_currency_code('USD');

    // set Customer data
    $order->setCustomer_email($customer->getEmail())
      ->setCustomerFirstname($customer->getFirstname())
      ->setCustomerLastname($customer->getLastname())
      ->setCustomerGroupId($customer->getGroupId())
      ->setCustomer_is_guest(0)
      ->setCustomer($customer);

    // set Billing Address
    $billing = $customer->getDefaultBillingAddress();
    $billingAddress = Mage::getModel('sales/order_address')
      ->setStoreId($storeId)
      ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_BILLING)
      ->setCustomerId($customer->getId())
      ->setCustomerAddressId($customer->getDefaultBilling())
      ->setCustomer_address_id($billing->getEntityId())
      ->setPrefix($billing->getPrefix())
      ->setFirstname($billing->getFirstname())
      ->setMiddlename($billing->getMiddlename())
      ->setLastname($billing->getLastname())
      ->setSuffix($billing->getSuffix())
      ->setCompany($billing->getCompany())
      ->setStreet($billing->getStreet())
      ->setCity($billing->getCity())
      ->setCountry_id($billing->getCountryId())
      ->setRegion($billing->getRegion())
      ->setRegion_id($billing->getRegionId())
      ->setPostcode($billing->getPostcode())
      ->setTelephone($billing->getTelephone())
      ->setFax($billing->getFax());
    $order->setBillingAddress($billingAddress);

    $shipping = $customer->getDefaultShippingAddress();
    $shippingAddress = Mage::getModel('sales/order_address')
      ->setStoreId($storeId)
      ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
      ->setCustomerId($customer->getId())
      ->setCustomerAddressId($customer->getDefaultShipping())
      ->setCustomer_address_id($shipping->getEntityId())
      ->setPrefix($shipping->getPrefix())
      ->setFirstname($shipping->getFirstname())
      ->setMiddlename($shipping->getMiddlename())
      ->setLastname($shipping->getLastname())
      ->setSuffix($shipping->getSuffix())
      ->setCompany($shipping->getCompany())
      ->setStreet($shipping->getStreet())
      ->setCity($shipping->getCity())
      ->setCountry_id($shipping->getCountryId())
      ->setRegion($shipping->getRegion())
      ->setRegion_id($shipping->getRegionId())
      ->setPostcode($shipping->getPostcode())
      ->setTelephone($shipping->getTelephone())
    ->setFax($shipping->getFax());

    $order->setShippingAddress($shippingAddress)
      ->setShipping_method('flatrate_flatrate')
      ->setShippingDescription('its gift be care!');

    $orderPayment = Mage::getModel('sales/order_payment')
      ->setStoreId($storeId)
      ->setCustomerPaymentId(0)
      ->setMethod('purchaseorder')
      ->setPo_number(' - ');
    $order->setPayment($orderPayment);

    // let say, we have 2 products
    $subTotal = 0;
    $products = array(
      '2' => array(
        'qty' => 1
      ),
      '3' =>array(
        'qty' => 3
      ),
    );
    foreach ($products as $productId=>$product) {
      $_product = Mage::getModel('catalog/product')->load($productId);
      $rowTotal = $_product->getPrice() * $product['qty'];
      $orderItem = Mage::getModel('sales/order_item')
        ->setStoreId($storeId)
        ->setQuoteItemId(0)
        ->setQuoteParentItemId(NULL)
        ->setProductId($productId)
        ->setProductType($_product->getTypeId())
        ->setQtyBackordered(NULL)
        ->setTotalQtyOrdered($product['qty'])
        ->setQtyOrdered($product['qty'])
        ->setName($_product->getName())
        ->setSku($_product->getSku())
        ->setPrice($_product->getPrice())
        ->setBasePrice($_product->getPrice())
        ->setOriginalPrice($_product->getPrice())
        ->setRowTotal($rowTotal)
        ->setBaseRowTotal($rowTotal);

      $subTotal += $rowTotal;
      $order->addItem($orderItem);
    }

    $order->setSubtotal($subTotal)
      ->setBaseSubtotal($subTotal)
      ->setGrandTotal($subTotal)
      ->setBaseGrandTotal($subTotal);

    $transaction->addObject($order);
    $transaction->addCommitCallback(array($order, 'place'));
    $transaction->addCommitCallback(array($order, 'save'));
    $transaction->save();
  }
}