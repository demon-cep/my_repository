<?
include_once('CMesage.php');
/**
* 
*/
class ShowMesage extends CMesage
{
	private $id_mesage;
	private $mesage;
	function __construct($id_mesage)
	{
		$this->id_mesage=(int)$id_mesage;
		$this->get_mesage();
		$this->paint_mesage();
	}

	private function get_mesage()
	{
		CModule::IncludeModule("iblock");
		GLOBAL $USER;
		$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 24, 'ID'=>$this->id_mesage ,array(
		        "LOGIC" => "OR",
		        array("PROPERTY_FROM_USER_ID" => $USER->GetID()),
		         array("PROPERTY_TO_USER_ID" => $USER->GetID()),
		    ),
		    
		), array("NAME",'PROPERTY_SHOW','ID','PREVIEW_TEXT'));
		if ($enum_fieldss = $dbRes->GetNext()) {
				$this->mesage=explode("\n", $enum_fieldss['PREVIEW_TEXT']);	
		}
	}
	// отрисовка
	private function paint_mesage()
	{
		GLOBAL $USER;
		foreach ($this->mesage as $key => $value) {
			// echo $value;

		 $my_arr=$this->json_decode($value);
		 			$my_arr['message'] = iconv ( 'UTF-8' , 'windows-1251' , $my_arr['message']);
		 			if ($my_arr['message']) {
		 				
		 			
						if ($USER->GetID()==(int)$my_arr['sender']) {
							?>
								<div class="my_mesage mesage">
									<p class="name"><?=AllFunction::get_user_name($my_arr['sender'])?></p>
									<p class="name time"><?=$my_arr['time']?></p>
									<p class="descript"><?=$my_arr['message']?></p>
								</div>
							<?
						}else{
							?>
								<div class="mesage all_mesage">
									<p class="name"><?=AllFunction::get_user_name($my_arr['sender'])?></p>
									<p class="descript"><?=$my_arr['message']?></p>
								</div>
								<?
						}
					}
		}
	}
	private function json_decode($sting)
	{
		return json_decode(htmlspecialchars_decode($sting), true);
	}
}
?>