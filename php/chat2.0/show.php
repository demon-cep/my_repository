<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/class/composer.php');
if (isset($_GET['ID_MESAGE'])) {
	$mesage=new ShowMesage($_GET['ID_MESAGE']);
}


?>