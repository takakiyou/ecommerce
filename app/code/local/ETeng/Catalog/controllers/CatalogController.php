<?php
class ETeng_Catalog_CatalogController extends Mage_Core_Controller_Front_Action{
	public function indexAction(){
		echo 'ccccc';
	}

	public function listCatalogAction(){
		//$this->getRequest()->getPost();
		//根据分类Id查询商品
		//$this->getRequest()->getParam("cId");
		$catalog=Mage::getModel("catalog/category");
		//$e=$catalog->getUrlRewrite();
		//$connection=$catalog->getProductCollection();
		//$e=$catalog->getDefaultAttributeSetId();
		$e=$catalog->load(3);
		var_dump($e->entity_id);
	}

	public function queryProAction(){
		$catalog=Mage::getModel("catalog/product");
		$ids="1,2,4,5";
		$sku="";
		// $set=$catalog->setCategoryIds($ids);
		// $get=$catalog->getCategoryIds();
		//获取分类集合
		//$set=$catalog->getCategoryCollection();
		// $ids=explode('.',$ids);
		//$e=$catalog->getIdBySku($sku)
		
		$set=$catalog->load(4)->toArray();
		$url=$catalog->getImageUrl();
		//var_dump($set['media_gallery']["images"]);
		// $this->_redirect('/mul/catalog');
		var_dump($url);
	}
	//根据商品Id查询商品详情
	public function productAction(){
		$id=$this->getRequest()->getParam("id");
		$catalog=Mage::getModel("catalog/product");
		$product=$catalog->load($id)->toArray();
		var_dump($product);
	}
	//根据分类id查询所有的商品 且（①：可排序 ②：可筛选 ③：可分页 ④：可配置）
	public function categroyAction(){
		$categroyId=$this->getRequest()->getParam('id');
		echo '1111111----------<br />';
		//$categroys=Mage::getModel("catalog/categroy")->load(3);
		$categroys=Mage::getModel("catalog/categroy")->load($id)->toArray();
		var_dump($categroys);
	}


	public function listCategoryProductAction(){
		$categroyId=$this->getRequest()->getParam("id");
		$page=$this->getRequest()->getParam("page");

		$ids=Mage::app()->getStore()->getId();
		//var_dump($ids);
		$categroy=Mage::getModel('catalog/category')->setStoreId($ids)->load($categroyId);
		$e=$categroy->getProductCollection();
		foreach ($e as $product) {
			var_dump($product->toArray());
			echo '<br />'."$ids".'.................'."<br />";
		}
	}
}