<?php

set_time_limit(0);
error_reporting(E_ALL | E_STRICT);
error_reporting(1);
ini_set('display_errors', 1);

require_once '../../app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
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
	
$installer =  new Mage_Sales_Model_Mysql4_Setup;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
$setup->addAttribute('catalog_category', 'category_review', array(
	'group' 			=> 'General Information',
	'input' 			=> 'textarea',
	'type'  			=> 'text',
	'label' 			=> 'Review',
	'backend'  			=> '',
	'visible'  			=> 1,
	'required' 			=> 0,
	'user_defined' 		=> 1,
	'global' 			=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$installer->updateAttribute('catalog_category', 'category_review', 'is_wysiwyg_enabled', 1);
$installer->updateAttribute('catalog_category', 'category_review', 'is_html_allowed_on_front', 1);
$installer->endSetup();