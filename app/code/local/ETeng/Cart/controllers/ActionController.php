<?php 
// ActionController.php
class ETeng_Cart_ActionController extends Mage_Core_Controller_Front_Action {
 
  /*
   *get cart instance
   */
  protected function _getCart() {
    return Mage::getSingleton('checkout/cart');
  }

  /*
   *add cart
   */
  public function addAction() {
    $id = $this->getRequest()->getParam('id');
    $qty = $this->getRequest()->getParam('qty');

    echo $id;
    echo '<br>';
    echo $qty;

    $_product = Mage::getModel('catalog/product')->load($id);
    $cart = $this->_getCart();
    $cart->init();

    //simpale product
    $cart->addProduct($_product, array('qty'=>$qty));
    // configurable product
    // $cart->addProduct($product,  array('product_id' => $id, 'qty' => $qty, 'options' => array('$optionId' => $optionValue)));

    $cart->save();

    Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
  }

  /*
   *update cart
   */
  public function updateAction() {
    $cart = $this->_getCart();
    $id = $this->getRequest()->getParam('id');
    $qty = $this->getRequest()->getParam('qty');
    if (!empty($qty)) {
      $filter = new Zend_Filter_LocalizedToNormalized(
        array('locale' => Mage::app()->getLocale()->getLocaleCode())
      );
      $params['qty'] = $filter->filter($qty);
      $cart->updateItem($id, new Varien_Object($params));
      $cart->save();
    }

  }

  /*
   *remove cart
   */
  public function removeAction() {
    $cart = $this->_getCart();
    $cart->truncate();
  }
  /*
   *remove item
   */
  public function removeItemAction() {
    $item = $this->getRequest()->getParam('id');
    if(!empty($item)) {
      $cart = $this->_getCart();
      $cart->removeItem($item);
    }

  }

  /*
   *show cart
   */
  public function showAction() {
    // retrieve quote items collection
    $itemsCollection = Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection();
     
    // get array of all items what can be display directly
    $itemsVisible = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
     
    // retrieve quote items array
    $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
    
    // retrieve cart url
    // $_url = Mage::helper('checkout/cart')->getAddUrl($items);

    foreach($items as $item) {
        echo 'itemID: '.$item->getItemId().'<br />';
        echo 'ID: '.$item->getProductId().'<br />';
        echo 'Name: '.$item->getName().'<br />';
        echo 'Sku: '.$item->getSku().'<br />';
        echo 'Quantity: '.$item->getQty().'<br />';
        echo 'Price: '.$item->getPrice().'<br />';
        echo "<br />";           
    }

    $totalItems = $this->_getCart()->getQuote()->getItemsCount();
    $totalQuantity = $this->_getCart()->getQuote()->getItemsQty();
    echo 'totalItems:'.$totalItems;
    echo '<br>';
    echo 'totalQuantity:'.$totalQuantity;

    $subTotal = $this->_getCart()->getQuote()->getSubtotal();
    $grandTotal = $this->_getCart()->getQuote()->getGrandTotal();
    echo '<br>';
    echo 'subTotal:'.$subTotal;
    echo '<br>';
    echo 'grandTotal:'.$grandTotal;

  }

}