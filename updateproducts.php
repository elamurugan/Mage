<?php
	set_time_limit(0);
	ini_set('memory_limit','1024M');
	ini_set('display_errors', 1);
	error_reporting(1);
	require_once 'app/Mage.php';
	 
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('admin');
	Mage::register('isSecureArea', 1);
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
if (function_exists('d') === false) {
		function d($data, $die = 0, $z = 1, $msg = 1) {
			echo"<br/><pre style='padding:2px 5px;background: none repeat scroll 0 0 #E04E19;clear: both;color: #FFFFFF;float: left;font-family: Times New Roman;font-style: italic;font-weight: bold;text-align: left;'>";
			if ($z == 1)
				Zend_Debug::dump($data);
			else if ($z == 2)
				var_dump($data);
			else
				print_r($data);
			echo"</pre>";
			if ($d == 1)
				die();
		}
}
$products = Mage::getModel('catalog/product')->getCollection()->addFieldToFilter("type_id",Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);
// echo'<pre>'; print_r($products->getData()); exit;
foreach($products->getData() as $val){
	$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$val['sku']);
	// $product = Mage::getModel('catalog/product')->load($val['entity_id']);
	if ($product) 
		{	
			$product->setData('status',1);
			$product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
			try{
				$product->save();
				// echo 's';
			}
			catch(Exception $e){
				d($e);
			}
			$productId = $val['entity_id'];	
			
			$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
			$stockItemId = $stockItem->getId();
			$stockItem->setData('manage_stock', 1);
			$stockItem->setData('qty', 9999);
			$stockItem->save(); 
			// Mage::dispatchEvent(
				// 'catalog_product_save_after',
				// array('product' => $product)
			// );
		}
	// echo'<pre>'; print_r($product->getData()); exit;
	// die();
}
