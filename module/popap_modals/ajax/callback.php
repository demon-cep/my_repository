<?

$json = array(
    'done'=>false
);

$message="";

if (count($_REQUEST)>0) {
	$name =  $_REQUEST['name'];
	$phone = $_REQUEST['phone'];
	$time  = $_REQUEST['time'];
	$text  = $_REQUEST['text'];
	if($name=="") $message.="Введите имя<br>";
	if($phone=="") $message.="Введите номер телефона<br>";
	//если нет ошибок то отправляем сообщение
	if ($message=="") {
		$body="";
		$body.="Имя: $name\n";
		$body.="Телефон: $phone\n";
		$body.="Время звонка: $time\n";
		$body.="Текст: $text\n";
		

		$subject="Обратный звонок с сайта www.gaudi-trade.ru";
		$subject = iconv("utf-8", "windows-1251", $subject);
		$subject = '=?windows-1251?B?'.base64_encode($subject).'?=';
		$body = iconv("utf-8", "windows-1251", $body);

		$headers="From: mailer@gaudi-trade.ru\r\n\r\n";

		mail("gaudi-trade@yandex.ru",$subject,$body,$headers);
//		mail("roman@e1media.ru",$subject,$body,$headers);

		$post['done'] = $post['success'] = 1;                
		$post['message'] = "Ваша заявка отправлена.";
	} else {
		$post['message'] = $message;
	}
}
else $post = array();

$json = array_merge($json, $post);
echo json_encode($json);
?>