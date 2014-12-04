<?php
// Blogpost.php
// 与数据库交互的所有基本模型都必须继承 “Mage_Core_Model_Abstract”类

class Magentotutorial_Weblog_Model_Blogpost extends Mage_Core_Model_Abstract {
  protected function _construct() {
    $this->_init('weblog/blogpost');
  }
}