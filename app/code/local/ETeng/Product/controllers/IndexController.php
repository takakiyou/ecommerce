<?php
// IndexController.php
class ETeng_Product_IndexController extends Mage_Core_Controller_Front_Action {
  //$product详情
  public function indexAction() {
    $params = $this->getRequest()->getParams();
    $id = $params['id'];
    $product = Mage::getModel('catalog/product')->load($id); 
    echo $product->getStockItem()->getQty();
    echo $product->getCacheTag;

    // echo $product->is_in_stock;
   $str='_cacheTag,_eventPrefix,_eventObject,_canAffectOptions,_typeInstance,_typeInstanceSingleton,_linkInstance,_customOptions,_urlModel,_errors,_optionInstance,_product,_options,_valueInstance,_values,_eventPrefix,_eventObject,_resourceName,_resource,_resourceCollectionName,_cacheTag,_dataSaveAllowed,_isObjectNew,_data,_hasDataChanges,_origData,_idFieldName,_isDeleted,_oldFieldsMap,_syncFieldsMap,_options,_reservedAttributes,_isDuplicable,_calculatePrice,_defaultValues,url_path,_storeValuesFlags,url_path,_lockedAttributes,_isDeleteable,_isReadonly,_resourceName,_resource,_resourceCollectionName,_dataSaveAllowed,_isObjectNew,_data,entity_id,entity_type_id,attribute_set_id,type_id,sku,has_options,required_options,created_at,updated_at,name,meta_title,meta_description,image,small_image,thumbnail,url_key,url_path,custom_design,page_layout,options_container,image_label,small_image_label,thumbnail_label,country_of_manufacture,msrp_enabled,msrp_display_actual_price_type,gift_message_available,marketplace_state,status,visibility,tax_class_id,seller_id,color_3,size_3,price,special_price,msrp,special_from_date,special_to_date,news_from_date,news_to_date,custom_design_from,custom_design_to,description,short_description,meta_keyword,custom_layout_update,delivery_time,shipping_charges,group_price,group_price_changed,media_gallery,images,0,value_id,file,label,position,disabled,label_default,position_default,disabled_default,1,value_id,file,label,position,disabled,label_default,position_default,disabled_default,2,value_id,file,label,position,disabled,label_default,position_default,disabled_default,values,tier_price,tier_price_changed,stock_item,_minSaleQtyCache,_eventObject,_productInstance,_customerGroupId,_processIndexEvents,_resourceName,_resource,_resourceCollectionName,_cacheTag,_dataSaveAllowed,_isObjectNew,_data,item_id,product_id,stock_id,qty,min_qty,use_config_min_qty,is_qty_decimal,backorders,use_config_backorders,min_sale_qty,use_config_min_sale_qty,max_sale_qty,use_config_max_sale_qty,is_in_stock,low_stock_date,notify_stock_qty,use_config_notify_stock_qty,manage_stock,use_config_manage_stock,stock_status_changed_auto,use_config_qty_increments,qty_increments,use_config_enable_qty_inc,enable_qty_increments,is_decimal_divided,type_id,stock_status_changed_automatically,use_config_enable_qty_increments,product_name,store_id,product_type_id,product_status_changed,product_changed_websites,_hasDataChanges,_origData,item_id,product_id,stock_id,qty,min_qty,use_config_min_qty,is_qty_decimal,backorders,use_config_backorders,min_sale_qty,use_config_min_sale_qty,max_sale_qty,use_config_max_sale_qty,is_in_stock,low_stock_date,notify_stock_qty,use_config_notify_stock_qty,manage_stock,use_config_manage_stock,stock_status_changed_auto,use_config_qty_increments,qty_increments,use_config_enable_qty_inc,enable_qty_increments,is_decimal_divided,type_id,stock_status_changed_automatically,use_config_enable_qty_increments,_idFieldName,_isDeleted,_oldFieldsMap,stock_status_changed_automatically,use_config_enable_qty_increments,_syncFieldsMap,stock_status_changed_automatically,use_config_enable_qty_increments,stock_status_changed_auto,use_config_enable_qty_inc,is_in_stock,is_salable,_hasDataChanges,_origData,_idFieldName,_isDeleted,_oldFieldsMap,_syncFieldsMap';
    $arr = explode(",", $str); 
  
    //print_r($arr);
    for ($i=0; $i <count($arr); $i++) { 
      echo '方法名：'.$arr[$i].'值：'.$product->$arr[$i].'<br />';
    }
   /* //是否有货
    echo $product->getIsInStock();echo '<br />';
    //商品类型
    echo $product->getTypeId();echo '<br />';
    echo '<br />';  
   // print_r(get_class_methods('Mage_Catalog_Model_Resource_Eav_Attribute'));echo '<br />';  
    //var_dump($product->getAttributes());echo '<br />';  
    echo '<br />';
    print_r($product->getSmallImageUrl());echo '<br />';
    print_r($product->getImageUrl());echo '<br />';
    echo $product->getQty();
    echo '<br />';*/

    //print_r(get_class_methods('Mage_Catalog_Model_Product'));
  }
  public function homeAction() {
   /* Mage::log(Mage::getResourceModel('weblog/blogpost'),Zend_Log::DEBUG);*/
  $params = $this->getRequest()->getParams();
  $id = $params['id'];
  $product = Mage::getModel('catalog/product')->load($id);
  $str= "getStoreId(),getResourceCollection(),getUrlModel(),validate(),getName(),getPrice(),setPriceCalculation(),getTypeId(),getStatus(),getTypeInstance(),setTypeInstance(),getLinkInstance(),getIdBySku(),getCategoryId(),getCategory(),setCategoryIds(),getCategoryIds(),getCategoryCollection(),getWebsiteIds(),getStoreIds(),getAttributes(),canAffectOptions(),cleanCache(),getPriceModel(),getGroupPrice(),getTierPrice(),getTierPriceCount(),getFormatedTierPrice(),getFormatedPrice(),setFinalPrice(),getFinalPrice(),getCalculatedFinalPrice(),getMinimalPrice(),getSpecialPrice(),getSpecialFromDate(),getSpecialToDate(),getRelatedProducts(),getRelatedProductIds(),getRelatedProductCollection(),getRelatedLinkCollection(),getUpSellProducts(),getUpSellProductIds(),getUpSellProductCollection(),getUpSellLinkCollection(),getCrossSellProducts(),getCrossSellProductIds(),getCrossSellProductCollection(),getCrossSellLinkCollection(),getGroupedLinkCollection(),getMediaAttributes(),getMediaGalleryImages(),addImageToMediaGallery(),getMediaConfig(),duplicate(),isSuperGroup(),isSuperConfig(),isGrouped(),isConfigurable(),isSuper(),getVisibleInCatalogStatuses(),getVisibleStatuses(),isVisibleInCatalog(),getVisibleInSiteVisibilities(),isVisibleInSiteVisibility(),isDuplicable(),setIsDuplicable(),isSalable(),isAvailable(),getIsSalable(),isVirtual(),isRecurring(),isSaleable(),isInStock(),getAttributeText(),getCustomDesignDate(),getProductUrl(),getUrlInStore(),formatUrlKey(),getUrlPath(),addAttributeUpdate(),toArray(),fromArray(),loadParentProductIds(),delete(),getRequestPath(),getGiftMessageAvailable(),getRatingSummary(),isComposite(),canConfigure(),getSku(),getWeight(),getOptionInstance(),getProductOptionsCollection(),addOption(),getOptionById(),getOptions(),getIsVirtual(),addCustomOption(),setCustomOptions(),getCustomOptions(),getCustomOption(),hasCustomOptions(),canBeShowInCategory(),getAvailableInCategories(),getDefaultAttributeSetId(),getImageUrl(),getSmallImageUrl(),getThumbnailUrl(),getReservedAttributes(),isReservedAttribute(),setOrigData(),reset(),getCacheIdTags(),isProductsHasSku(),processBuyRequest(),getPreconfiguredValues(),prepareCustomOptions(),getProductEntitiesInfo(),isDisabled(),lockAttribute(),unlockAttribute(),unlockAttributes(),getLockedAttributes(),hasLockedAttributes(),isLockedAttribute(),setData(),unsetData(),loadByAttribute(),getStore(),getWebsiteStoreIds(),setAttributeDefaultValue(),getAttributeDefaultValue(),setExistsStoreValueFlag(),getExistsStoreValueFlag(),isDeleteable(),setIsDeleteable(),isReadonly(),setIsReadonly(),getIdFieldName(),getId(),setId(),getResourceName(),getCollection(),load(),afterLoad(),save(),afterCommitCallback(),isObjectNew(),getCacheTags(),cleanModelCache(),getResource(),getEntityId(),clearInstance(),__construct(),isDeleted(),hasDataChanges(),setIdFieldName(),addData(),unsetOldData(),getData(),setDataUsingMethod(),getDataUsingMethod(),getDataSetDefault(),hasData(),__toArray(),toXml(),toJson(),toString(),__call(),__get(),__set(),isEmpty(),serialize(),getOrigData(),dataHasChangedFor(),setDataChanges(),debug(),offsetSet(),offsetExists(),offsetUnset(),offsetGet(),isDirty(),flagDirty()";  
  print_r(count($product->media_gallery["images"]));//[4]["file"]
  /*$arr = explode(",", $str); 
  //print_r($arr);
  for ($i=0; $i <count($arr); $i++) { 

    echo '方法名：'.$arr[$i].'值：'.$product->getImageUrl().'<br />';
  }*/
    // echo $product->is_in_stock;
  /* $str='_cacheTag,_eventPrefix,_eventObject,_canAffectOptions,_typeInstance,_typeInstanceSingleton,_linkInstance,_customOptions,_urlModel,_errors,_optionInstance,_product,_options,_valueInstance,_values,_eventPrefix,_eventObject,_resourceName,_resource,_resourceCollectionName,_cacheTag,_dataSaveAllowed,_isObjectNew,_data,_hasDataChanges,_origData,_idFieldName,_isDeleted,_oldFieldsMap,_syncFieldsMap,_options,_reservedAttributes,_isDuplicable,_calculatePrice,_defaultValues,url_path,_storeValuesFlags,url_path,_lockedAttributes,_isDeleteable,_isReadonly,_resourceName,_resource,_resourceCollectionName,_dataSaveAllowed,_isObjectNew,_data,entity_id,entity_type_id,attribute_set_id,type_id,sku,has_options,required_options,created_at,updated_at,name,meta_title,meta_description,image,small_image,thumbnail,url_key,url_path,custom_design,page_layout,options_container,image_label,small_image_label,thumbnail_label,country_of_manufacture,msrp_enabled,msrp_display_actual_price_type,gift_message_available,marketplace_state,status,visibility,tax_class_id,seller_id,color_3,size_3,price,special_price,msrp,special_from_date,special_to_date,news_from_date,news_to_date,custom_design_from,custom_design_to,description,short_description,meta_keyword,custom_layout_update,delivery_time,shipping_charges,group_price,group_price_changed,media_gallery,images,0,value_id,file,label,position,disabled,label_default,position_default,disabled_default,1,value_id,file,label,position,disabled,label_default,position_default,disabled_default,2,value_id,file,label,position,disabled,label_default,position_default,disabled_default,values,tier_price,tier_price_changed,stock_item,_minSaleQtyCache,_eventObject,_productInstance,_customerGroupId,_processIndexEvents,_resourceName,_resource,_resourceCollectionName,_cacheTag,_dataSaveAllowed,_isObjectNew,_data,item_id,product_id,stock_id,qty,min_qty,use_config_min_qty,is_qty_decimal,backorders,use_config_backorders,min_sale_qty,use_config_min_sale_qty,max_sale_qty,use_config_max_sale_qty,is_in_stock,low_stock_date,notify_stock_qty,use_config_notify_stock_qty,manage_stock,use_config_manage_stock,stock_status_changed_auto,use_config_qty_increments,qty_increments,use_config_enable_qty_inc,enable_qty_increments,is_decimal_divided,type_id,stock_status_changed_automatically,use_config_enable_qty_increments,product_name,store_id,product_type_id,product_status_changed,product_changed_websites,_hasDataChanges,_origData,item_id,product_id,stock_id,qty,min_qty,use_config_min_qty,is_qty_decimal,backorders,use_config_backorders,min_sale_qty,use_config_min_sale_qty,max_sale_qty,use_config_max_sale_qty,is_in_stock,low_stock_date,notify_stock_qty,use_config_notify_stock_qty,manage_stock,use_config_manage_stock,stock_status_changed_auto,use_config_qty_increments,qty_increments,use_config_enable_qty_inc,enable_qty_increments,is_decimal_divided,type_id,stock_status_changed_automatically,use_config_enable_qty_increments,_idFieldName,_isDeleted,_oldFieldsMap,stock_status_changed_automatically,use_config_enable_qty_increments,_syncFieldsMap,stock_status_changed_automatically,use_config_enable_qty_increments,stock_status_changed_auto,use_config_enable_qty_inc,is_in_stock,is_salable,_hasDataChanges,_origData,_idFieldName,_isDeleted,_oldFieldsMap,_syncFieldsMap';
    $arr = explode(",", $str); 
  
    //print_r($arr);
    for ($i=0; $i <count($arr); $i++) { 
      echo '方法名：'.$arr[$i].'值：'.$product->$arr[$i].'<br />';
    }
*/
       }
  public function paramsAction() {
   // Let's load the category Model and grab the product collection of that category

$product_collection = Mage::getModel('catalog/category')->load(3)->getProductCollection();

// Now let's loop through the product collection and print the ID of every product 
foreach($product_collection as $product) {
  // Get the product ID

$product_id = $product->getId();

  // Load the full product model based on the product ID

$full_product = Mage::getModel('catalog/product')->load($product_id);

  // Now that we loaded the full product model, let's access all of it's data

  // Let's get the Product Name

  $product_name = $full_product->getName();

  // Let's get the Product URL path

  $product_url = $full_product->getProductUrl();

  // Let's get the Product Image URL

  $product_image_url = $full_product->getImageUrl();

  // Let's print the product information we gathered and continue onto the next one

 echo $product_name;

  echo $product_image_url;
}
  }

  public function testModelAction() {
   $entityTypeId = Mage::getModel('eav/entity')
                ->setType('catalog_product')
                ->getTypeId();
$attributeSetName   = 'AttributeSet3';
$attributeSetId     = Mage::getModel('eav/entity_attribute_set')
                    ->getCollection()
                    ->setEntityTypeFilter($entityTypeId)
                    ->addFieldToFilter('attribute_set_name', $attributeSetName)
                    ->getFirstItem()
                    ->getAttributeSetId();
  echo $attributeSetId;
  }
  public function teAction(){
    for ($i=0; $i <5 ; $i++) {
    $e = $i; 
    echo $e;
      # code...
    }
    $_product=Mage::getModel('catalog/product')->load(4);
    echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).
        'catalog/product';
   //gets the product url
    echo '<br />';
  }
 public function getProductByIdAction (){
  
  $params = $this->getRequest()->getParams();
  $id = $params['id'];
  $_product=Mage::getModel('catalog/product')->load($id);
  $product_data["id"]=$_product->getId();
  $product_data["name"]=$_product->getName();
  $product_data["is_in_stock"]=$_product->getStockItem()->getQty();
  $product_data["description"]=$_product->getShortDescription();
  $product_data["shotdescription"]=$_product->getDescription();
  $product_data["price"]=$_product->getPrice();
  $product_data["special price"]=$_product->getFinalPrice();
  for ($i=0; $i <count($_product->media_gallery["images"]) ; $i++) { 
    $pro["src"]=  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).
        'catalog/product'.$_product->media_gallery["images"][$i]["file"];
    $pro["sort"]= $i;
    $pro["isActive"]= "true" ;
    $arr[$i] = $pro;
  }
  $product_data["imgPath"]=$arr;
  $product_data["model"]=$_product->getSku();
  $product_data["color"]=$_product->getColor();
  
  $s = json_encode($product_data);
  $s = preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $s);  
  print_r($s);
}
 public function initAction(){
    $params = $this->getRequest()->getParams();
    $id = $params['id'];
    $product = Mage::getModel('catalog/product')->load($id)->toArray(); 
    //print_r($product);
    $_reviews = Mage::getModel('review/review')->getResourceCollection()->toArray();  
    /*$_reviews->addStoreFilter( Mage::app()->getStore()->getId())  
      ->addEntityFilter('product', $product->getId())  
      ->addStatusFilter( Mage_Review_Model_Review::STATUS_APPROVED )  
      ->setDateOrder()  
      ->addRateVotes();  */
      for ($i=0; $i <count($_reviews["items"]) ; $i++) { 
        if($_reviews["items"][$i]["entity_pk_value"]==$id && $_reviews["items"][$i]["status_id"] == 1){
          $rating["title"] = $_reviews["items"][$i]["title"];
          $rating["detail"] = $_reviews["items"][$i]["detail"];
          $rating["nickname"] = $_reviews["items"][$i]["nickname"];          
        };
      }
      print_r($rating);
      //echo $_reviews["items"][0]["entity_pk_value"];
    $avg = 0;  
    $ratings = array(); 
    print_r($_reviews);
 }
}