<?php
// IndexController.php
class Magentotutorial_Weblog_IndexController extends Mage_Core_Controller_Front_Action {
  
  public function indexAction() {
    // echo("nothing to do");
  }

  public function testModelAction() {
    $params = $this->getRequest()->getParams();
    $blogpost = Mage::getModel('weblog/blogpost');
    echo("loading...".$params['id']);
    echo('<dt>');
    $blogpost->load($params['id']);
    $data = $blogpost->getData();
    var_dump($data);
  }

  public function createNewPostAction() {
    $blogpost = Mage::getModel('weblog/blogpost');
    $blogpost->setTitle('Code Post!');
    $blogpost->setPost('This post was created from code, again!');
    $blogpost->setDate('2014-11-28 00:00:00');
    $blogpost->save();
    echo 'post with ID ' . $blogpost->getId() . ' created';
  }

  public function editFirstPostAction() {
    $blogpost = Mage::getModel('weblog/blogpost');
    $blogpost->load(1);
    $blogpost->setTitle("The First post!");
    $blogpost->save();
    echo 'post edited';
  }

  public function deleteFirstPostAction() {
    $blogpost = Mage::getModel('weblog/blogpost');
    $blogpost->load(1);
    $blogpost->delete();
    echo 'post removed';
  }

  // model collection
  public function showAllBlogPostsAction() {
    $posts = Mage::getModel('weblog/blogpost')->getCollection();
    foreach($posts as $blogpost){
        echo '<h3>'.$blogpost->getTitle().'</h3>';
        echo nl2br($blogpost->getPost());
    }
  }

}