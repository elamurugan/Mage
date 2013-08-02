<?php
	// set_time_limit(0);
	ini_set('memory_limit','1024M');
	// ini_set('display_errors', 1);
	error_reporting(0);
	require_once 'app/Mage.php';
	
	// umask(0);
	// Mage::app('default');
	// Mage::getSingleton('core/session', array('name' => 'adminhtml'));
	
	
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
	$orderIds = Mage::getSingleton('core/session')->getDeleteOrders();
	// d($orderIds);
	$i 		    = 0;
	$orderIds = Mage::app()->getRequest()->getPost('order_ids');
	if(count($orderIds)){
		// Mage::getSingleton('core/session')->setDeleteOrders($orderIds);
		// $orderIds = Mage::getSingleton('core/session')->getDeleteOrders();
		// d($orderIds);
		// die();
		$flag   = false;
		$write  = Mage::getSingleton('core/resource')->getConnection('core_write');	
		$query	= "show tables";
		$rsc_table=$write->fetchCol($query);	
		
		$table_sales_flat_order = Mage::getSingleton('core/resource')->getTableName('sales_flat_order');						
		$table_sales_flat_creditmemo_comment= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo_comment');
		$table_sales_flat_creditmemo_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo_item');
		$table_sales_flat_creditmemo= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo');
		$table_sales_flat_creditmemo_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo_grid');
		$table_sales_flat_invoice_comment= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice_comment');
		$table_sales_flat_invoice_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice_item');
		$table_sales_flat_invoice= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice');
		$table_sales_flat_invoice_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice_grid');
		$table_sales_flat_quote_address_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_address_item');
		$table_sales_flat_quote_item_option= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_item_option');
		$table_sales_flat_quote= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote');
		$table_sales_flat_quote_address= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_address');
		$table_sales_flat_quote_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_item');
		$table_sales_flat_quote_payment= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_payment');
		$table_sales_flat_shipment_comment= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_comment');
		$table_sales_flat_shipment_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_item');
		$table_sales_flat_shipment_track= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_track');
		$table_sales_flat_shipment= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment');
		$table_sales_flat_shipment_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_grid');		
		$table_sales_flat_order_address= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_address');
		$table_sales_flat_order_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_item');
		$table_sales_flat_order_payment= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_payment');
		$table_sales_flat_order_status_history= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_status_history');					
		$table_sales_flat_order_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_grid');						
		$table_log_quote= Mage::getSingleton('core/resource')->getTableName('log_quote');				
		$quoteId='';		
		if (!empty($orderIds)) {
			foreach ($orderIds as $orderId) {
					$query=null;
					$order = Mage::getModel('sales/order')->load($orderId);					
					if($order->increment_id){
						/*$query="show tables like 'sales_flat_order'";
						$rs=$write->fetchAll($query);*/						
						$incId=$order->increment_id;
						if(in_array($table_sales_flat_order,$rsc_table)){
							$query='SELECT entity_id   FROM  '.$table_sales_flat_order.'    WHERE increment_id="'.mysql_escape_string($incId).'"';
							
							$rs=$write->fetchAll($query);												
						
							$query='SELECT quote_id    FROM   '.$table_sales_flat_order.'        WHERE entity_id="'.mysql_escape_string($orderId).'"';
							$rs1=$write->fetchAll($query);
							$quoteId=$rs1[0]['quote_id'];							
						}		
						
						$query='SET FOREIGN_KEY_CHECKS=1';
						$rs3=$write->query($query);
						//print_r($rsc_table);
						//echo $table_sales_flat_creditmemo_comment;
						if(in_array($table_sales_flat_creditmemo_comment,$rsc_table)){
						//echo "DELETE FROM ".$table_sales_flat_creditmemo_comment."    WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_creditmemo." WHERE order_id=".$orderId.")";
						//die;
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo_comment."    WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_creditmemo." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						//die;
						
						
						if(in_array('sales_flat_creditmemo_item',$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo_item."       WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_creditmemo." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_creditmemo,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo."            WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						
						
						if(in_array($table_sales_flat_creditmemo_grid,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo_grid."        WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						
						if(in_array($table_sales_flat_invoice_comment,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice_comment." WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_invoice." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						if(in_array($table_sales_flat_invoice_item,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice_item."     WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_invoice." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_invoice,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice."         WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_invoice_grid,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice_grid."     WHERE order_id='".mysql_escape_string($orderId)."'");
						}	
						
						if($quoteId){						
							if(in_array($table_sales_flat_quote_address_item,$rsc_table)){							
							$write->query("DELETE FROM ".$table_sales_flat_quote_address_item."     WHERE parent_item_id  IN (SELECT address_id FROM ".$table_sales_flat_quote_address." WHERE quote_id=".$quoteId.")");
							}
							
							$table_sales_flat_quote_shipping_rate= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_shipping_rate');
							if(in_array($table_sales_flat_quote_shipping_rate,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_shipping_rate."    WHERE address_id IN (SELECT address_id FROM ".$table_sales_flat_quote_address." WHERE quote_id=".$quoteId.")");
							}
							
							if(in_array($table_sales_flat_quote_item_option,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_item_option."     WHERE item_id IN (SELECT item_id FROM ".$table_sales_flat_quote_item." WHERE quote_id=".$quoteId.")");
							}
						
							
							if(in_array($table_sales_flat_quote,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote."                 WHERE entity_id=".$quoteId);
							}
							
							if(in_array($table_sales_flat_quote_address,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_address."         WHERE quote_id=".$quoteId);
							}
							
							if(in_array($table_sales_flat_quote_item,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_item."             WHERE quote_id=".$quoteId);
							}
							
							if(in_array('sales_flat_quote_payment',$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_payment."         WHERE quote_id=".$quoteId);
							}
							
						}
						
						
						if(in_array($table_sales_flat_shipment_comment,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_shipment_comment."    WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_shipment." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						if(in_array($table_sales_flat_shipment_item,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_shipment_item."         WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_shipment." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_shipment_track,$rsc_table)){						
						$write->query("DELETE FROM ".$table_sales_flat_shipment_track."         WHERE order_id  IN (SELECT entity_id FROM ".$table_sales_flat_shipment." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_shipment,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_shipment."             WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						
						if(in_array($table_sales_flat_shipment_grid,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_shipment_grid."         WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_order,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order."                     WHERE entity_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_order_address,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order_address."            WHERE parent_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_order_item,$rsc_table)){						
						$write->query("DELETE FROM ".$table_sales_flat_order_item."                 WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						if(in_array($table_sales_flat_order_payment,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order_payment."             WHERE parent_id='".mysql_escape_string($orderId)."'");
						}
						if(in_array($table_sales_flat_order_status_history,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order_status_history."     WHERE parent_id='".mysql_escape_string($orderId)."'");
						}
						if($incId&&in_array($table_sales_flat_order_grid,$rsc_table)){						
							$write->query("DELETE FROM ".$table_sales_flat_order_grid."                 WHERE increment_id='".mysql_escape_string($incId)."'");

						}
						
						$query="show tables like '%".$table_log_quote."'";
						$rsc_table_l=$write->fetchCol($query);	
						if($quoteId&&$rsc_table_l){						
								$write->query("DELETE FROM ".$table_log_quote." WHERE quote_id=".$quoteId);							
						}
						$write->query("SET FOREIGN_KEY_CHECKS=1");						
					}	
				echo "<br/>".$i++ . ": Deleteted - ".$orderId;				
			}	
			
		}else{
			// echo "<br/> Error";
		}
	}
	
	$condition  = array("from" =>100000001 , "to" => 300000110);
	$collection = Mage::getResourceModel('sales/order_collection')  
                    ->addAttributeToSelect('*')                  
                    ->addAttributeToFilter('increment_id', $condition);
 


	 $orderIds = array(100000001 ,100000002,	100000003   );
	?>
	<form name="orders" method="POST" action="">
		<?php
			// foreach($orderIds as $orderId){ 
				// $order   = Mage::getModel('sales/order')->loadByIncrementId($orderId);
			foreach ($collection as $_order) {		
				$order   = Mage::getModel('sales/order')->load($_order->getData('entity_id'));		
				// Zend_debug::dump($order->getData('canceled'));die();
				echo " Delete Order ".$order->getData('increment_id')." from user  ".$order->getData('customer_firstname')." ".$order->getData('customer_lastname')."( ".$order->getData('customer_email').") in ".$order->getData('status')." status <input type='checkbox' name='order_ids[]'  checked='checked' value='".$order->getData('entity_id')."'/><br/>";
			}
		?>
		<input type='submit' value="delete all"/>
	</form>