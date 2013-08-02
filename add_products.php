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

	if(function_exists('d') === false) {
		function d($data,$die=0,$ty=1,$msg=1){
			echo"<br/><pre style='padding:2px 5px;background: none repeat scroll 0 0 #E04E19;clear: both;color: #FFFFFF;float: left;font-family: Times New Roman;font-weight: bold;text-align: left;'>";
				if($ty==1)
					print_r($data);
				else
					var_dump($data);
			echo"</pre>";
			if($die==1)
				die();
		}
	}
	 
	/***************** UTILITY FUNCTIONS ********************/
	function _getConnection($type = 'core_read'){
		return Mage::getSingleton('core/resource')->getConnection($type);
	}
	 
	function _getTableName($tableName){
		return Mage::getSingleton('core/resource')->getTableName($tableName);
	}
	
	function sanitize($string, $force_lowercase = true, $anal = false) {
		$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
					   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
					   "—", "–", ",", "<", ".", ">", "/", "?");
		$clean = trim(str_replace($strip, "", strip_tags($string)));
		$clean = preg_replace('/\s+/', "-", $clean);
		$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
		return ($force_lowercase) ?
			(function_exists('mb_strtolower')) ?
				mb_strtolower($clean, 'UTF-8') :
				strtolower($clean) :
			$clean;
	}
	 
	function addOptionValues($attribute,$option_title)
    {
    	if($option_title=="")
			return false;
		$model_att = Mage::getModel('eav/entity_attribute') ; // We get the model handling eav_attribute    
		$code_att= $model_att->getIdByCode('catalog_product',$attribute); // We get the id of the attribute in eav_attribute
		$att = $model_att->load($code_att); // We load the infos of this attribute
		       
		$value['option_3']  = array('0' => $option_title, '1' => $option_title);
		$order['option_3']  =  '1';
		$delete['option_3'] =  '';
		
		$results = array('value' => $value, 'order' => $order, 'delete' => $delete);
		$att->setData('option',$results);
		$att->save(); 
        return $values; 
    } 
	
	
	function attributeValueExists($arg_attribute, $arg_value)
	{
		$attribute_model 	     = Mage::getModel('eav/entity_attribute');
		$attribute_options_model = Mage::getModel('eav/entity_attribute_source_table') ;
		$attribute_code  = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
		$attribute       = $attribute_model->load($attribute_code);
		$attribute_table = $attribute_options_model->setAttribute($attribute);
		$options 		 = $attribute_options_model->getAllOptions(false);
		
		foreach($options as $option)
		{
			if ($option['label'] == $arg_value)
			{
				return $option['value'];
			}
		}
		// die();
		addOptionValues($arg_attribute,$arg_value);
		return false;
	}
	
	// $_simple_product = Mage::getModel('catalog/product')->load(1);
	// d($_simple_product->getData());die();
	function add_simple_items($product,$item_no)
	{ 
		$_simple_product = Mage::getModel('catalog/product');
		
		$image = $product['image'];
		unset($product['image']);
		$_simple_product->setData($product);
		$_simple_product->setTypeId('simple')->setAttributeSetId(4); 
		$_simple_product->setWeight(500.0);
		$_simple_product->setTaxClassId(0); // taxable goods
		$_simple_product->setVisibility(4); // catalog, search
		$_simple_product->setStatus(1); // enabled
		$_simple_product->setWebsiteIds(array(1));
		
		$_simple_product->setCategoryIds("3"); // need to look these up
		
		$stockData        = $_simple_product->getStockData();
		$stockData['qty'] = 1000;
		$stockData['is_in_stock']  = 1;
		$stockData['manage_stock'] = 1;
		$stockData['use_config_manage_stock'] = 0;
		$_simple_product->setStockData($stockData); 
		 
		$_simple_product->setIsMassupdate(true);
		$_simple_product->setExcludeUrlRewrite(true);
		 
		try{
			$image  = Mage::getBaseDir('media') . DS . 'import' . DS .$image;
			// d($image);
			$mode = array("thumbnail", "small_image", "image");
			$_simple_product->addImageToMediaGallery($image, $mode, false, false);
			// d($_simple_product->getData());
			// die();
			$_simple_product->save();
			return $_simple_product->getId(); 
		}
		catch (Exception $e){ 		
			d( "Simple product  not added - $e<br/>");
			// d($_simple_product->getData());
			die();
			return false;
		} 
	}
	
	function reindexing($redindexid = 0) {
		if($redindexid != 0)
		{
			$process = Mage::getModel('index/process')->load($redindexid);
			$process->reindexAll();
		}
		else{
			for ($i = 1; $i <= 9; $i++) {
				$process = Mage::getModel('index/process')->load($i);
				$process->reindexAll();
			}
		}
	}
	
	$products[] = array(
			"name" => "Gold Jewel ",
			"image" => "gj1.jpg",
			"metal" => "4",
			"purity" => "7",
			"metal_weight" => "2.12",
			"number_of_pieces" => "1",
			"glod_making_charge_class" => "9",
			"making_charge" => "1245",
			"wastage_class" => "12",
			"wastage" => "0.13",
			"other_charge" => "0",
			"diamond_charge" => "0",
			"stone_price" => "0",
			"vat_class" => "15",
			"vat" => "1023",
			"price" => "40000",
			"weight" => "1000",
			"tax_class_id" => "0"
			);
	$products[] = array("name" => "Gold Jewel ","image" => "gj2.jpg","metal" => "4","purity" => "7","metal_weight" => "8.12","number_of_pieces" => "1","glod_making_charge_class" => "9","making_charge" => "1245","wastage_class" => "12","wastage" => "0.83","other_charge" => "0","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "167000","weight" => "1000","tax_class_id" => "0");		
	$products[] = array("name" => "Gold Jewel ","image" => "gj3.jpg","metal" => "4","purity" => "7","metal_weight" => "2.22","number_of_pieces" => "2","glod_making_charge_class" => "9","making_charge" => "43543","wastage_class" => "12","wastage" => "0.13","other_charge" => "0","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "34000","weight" => "1000","tax_class_id" => "0");		
	$products[] = array("name" => "Gold Jewel ","image" => "gj4.jpg","metal" => "4","purity" => "7","metal_weight" => "4.55","number_of_pieces" => "2","glod_making_charge_class" => "9","making_charge" => "23456","wastage_class" => "12","wastage" => "0.24","other_charge" => "1560","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "32332","weight" => "1000","tax_class_id" => "0");	
    $products[] = array("name" => "Gold Jewel ","image" => "gj5.jpg","metal" => "4","purity" => "7","metal_weight" => "66.89","number_of_pieces" => "1","glod_making_charge_class" => "9","making_charge" => "443432","wastage_class" => "12","wastage" => "2.13","other_charge" => "124110","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "324324","weight" => "1000","tax_class_id" => "0");			
	$products[] = array("name" => "Gold Jewel ","image" => "gj6.jpg","metal" => "4","purity" => "7","metal_weight" => "4.98","number_of_pieces" => "2","glod_making_charge_class" => "9","making_charge" => "54534","wastage_class" => "12","wastage" => "1.13","other_charge" => "0","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "34000","weight" => "1000","tax_class_id" => "0");		
	$products[] = array("name" => "Gold Jewel ","image" => "gj7.jpg","metal" => "4","purity" => "7","metal_weight" => "45.87","number_of_pieces" => "1","glod_making_charge_class" => "9","making_charge" => "65767","wastage_class" => "12","wastage" => "1.13","other_charge" => "0","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "34000","weight" => "1000","tax_class_id" => "0");		
	$products[] = array("name" => "Gold Jewel ","image" => "gj8.jpg","metal" => "4","purity" => "7","metal_weight" => "76.00","number_of_pieces" => "1","glod_making_charge_class" => "9","making_charge" => "23232","wastage_class" => "12","wastage" => "1.13","other_charge" => "0","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "34000","weight" => "1000","tax_class_id" => "0");		
	$products[] = array("name" => "Gold Jewel ","image" => "gj9.jpg","metal" => "4","purity" => "7","metal_weight" => "12.45","number_of_pieces" => "2","glod_making_charge_class" => "9","making_charge" => "435435","wastage_class" => "12","wastage" => "2.13","other_charge" => "0","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "34000","weight" => "1000","tax_class_id" => "0");		
	$products[] = array("name" => "Gold Jewel ","image" => "gj10.jpg","metal" => "4","purity" => "7","metal_weight" => "23.87","number_of_pieces" => "1","glod_making_charge_class" => "9","making_charge" => "54654","wastage_class" => "12","wastage" => "1.13","other_charge" => "0","diamond_charge" => "0","stone_price" => "0","vat_class" => "15","vat" => "1023","price" => "34000","weight" => "1000","tax_class_id" => "0");		
	
	$collection = Mage::getModel('catalog/product')->getCollection();
	// d($collection->count());
	$sku_start_number = ($collection->count() + 12);
	// d($sku_start_number);die();
	for($i=0;$i < @$_GET['loop'];$i++){
		foreach($products as $key => $product){
			$name = $product["name"];
			$product["name"] = $name." ".$sku_start_number;
			$product["description"] = $name." ".$sku_start_number;
			$product["short_description"] = $name." ".$sku_start_number;
			$product["sku"] = "gj".$sku_start_number;
			$return_id 			= add_simple_items($product,$sku_start_number);
			$sku_start_number++;
			if($return_id)
				d(" Added  $return_id <br/>");
		}
	}
	reindexing(0);