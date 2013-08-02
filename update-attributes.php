<?php

	set_time_limit(0);
	error_reporting(E_ALL | E_STRICT);
	require_once 'app/Mage.php';

	umask(0);
	Mage::app('default');
	Mage::getSingleton('core/session', array('name' => 'frontend'));
	
	require_once 'lib/File_CSV_DataSource.php';

	if (function_exists('d') === false) {
		function d($data, $die=0, $z=1, $msg=1) {
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
	
	$csv 	    = new File_CSV_DataSource();  
	$csvfile 	= "attributes.csv"; 
	$csvfile = Mage::getBaseDir() . "/".$csvfile;
	$csv->load($csvfile);
	$result  = $csv->getRawArray();
	// d($result);
	// die();
	unset($result[0]);
	$recordno = 0; 
	foreach ($result as $row) {
		$_simple_sku 			= trim($row[0]);
		$_configurable_sku 		= trim($row[1]);
		$_attribute_id 			= trim($row[6]);
		if($_simple_sku !=""){
			$_product   = Mage::getModel('catalog/product')->loadByAttribute('sku', $_simple_sku); 		
			if($_product){
				$_product->setData("brand",$_attribute_id)->save();
			}
		}
		if($_configurable_sku !=""){
			$_configurable_sku = "C-".$_configurable_sku ;
			$_product   = Mage::getModel('catalog/product')->loadByAttribute('sku', $_configurable_sku); 		
			if($_product){
				$_product->setData("brand",$_attribute_id)->save();
			}
		}
		// d($row);
		$recordno++;
	}
	die('done'); 