<?php
// <!-- MessageController.php -->
class Magentotutorial_Helloworld_MessageController extends Mage_Core_Controller_Front_Action {
  public function IndexAction() {
    echo 'bye index!';
  }
  public function goodbyeAction() {
    echo 'bye bye!';
  }
}