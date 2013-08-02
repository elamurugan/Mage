<?php


set_time_limit(0);
error_reporting(E_ALL | E_STRICT);

require_once 'app/Mage.php';
umask(0);
Mage::app('default');
Mage::getSingleton('core/session', array('name' => 'adminhtml'));
$websiteId = 1;
$storeId   = Mage::app()->getStore();

$mail = Mage::getModel('core/email');
$mail->setToName('Your Name');
$mail->setToEmail('support@brandzzz.com');
$mail->setBody('Mail Text / Mail Content');
$mail->setSubject('Mail Subject');
$mail->setFromEmail('support@brandzzz.com');
$mail->setFromName("Msg to Show on Subject");
$mail->setType('html');

try {
	$mail->send();
	echo('Your request has been sent');
	// $this->_redirect('');
}
catch (Exception $e) {
	echo('Unable to send.');
	// $this->_redirect('');
}