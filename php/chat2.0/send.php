<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/class/composer.php');
// отправка сообщения

$send=new SendMesage($_POST['sender'],$_POST['message']);
print_r($_POST);
if (isset($_POST['ID_MESAGE']) && (int)$_POST['ID_MESAGE']>0) {
	$send->set_id_element($_POST['ID_MESAGE']);
}
if (isset($_POST['ID_ELMENT_MESAGE'])  && (int)$_POST['ID_ELMENT_MESAGE']>0) {

	$send->set_id_element(AllFunction::get_mesage_to_element_id($_POST['ID_ELMENT_MESAGE']));
	$send->set_element($_POST['ID_ELMENT_MESAGE']);
}

$send->send();
?>

