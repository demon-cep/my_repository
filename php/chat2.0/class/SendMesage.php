<?
include_once('CMesage.php');
/**
* 
*/
class SendMesage extends CMesage
{
	private $arr_mesage;
	private $mesage_json;
	private $id_mesage;
	private $id_element;
	private $from_user_id;
	function __construct($sender,$message)
	{
		$this->arr_mesage=array(
		'sender'=>$sender,
		'message'=>$message,
		'time'=>date('d.m.Y h:i'),
		);
		$this->jsone_encode();
	
	}
	public function set_element($id)
	{
		$this->id_element=(int)$id;
	}
	public function set_id_element($id)
	{
		$this->id_mesage=(int)$id;
		echo $this->id_mesage;
	}
	public function send()
	{
		if ($this->id_mesage && $this->id_mesage>0) {
			$this->update_element();	

			echo "old";
		}else{	
			$this->add_element();	
			echo "new";
		}
		$this->user_prop();
		$this->send_mesag();

	}
	private function send_mesag()
	{
		global $USER;
		$event = new CEvent;
		$email=AllFunction::get_user_email($this->from_user_id);
		echo $email;
		// $email='sa@e1media.ru';
		$arEventFields = array(
		    "EMAIL"            => $email,

		    );
		// $arrSITE =  CAdvContract::GetSiteArray(31);
		$event->SendImmediate("NEW_MESSAG", 's1', $arEventFields);
	}
	private function user_prop()
	{
		AllFunction::user_show_property($this->id_mesage);
		// echo "zzz";
		print_r(AllFunction::user_del_property($this->id_mesage,$this->from_user_id));
		// echo "vvv";
	}

	private function get_all_prop()
	{
		CModule::IncludeModule("iblock");
		GLOBAL $USER;
		$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => AllFunction::IBLOCK_ID, 'ID'=>$this->id_mesage		    
		), array("NAME",'PROPERTY_FROM_USER_ID','PREVIEW_TEXT','PROPERTY_ELEMENT_ID','PROPERTY_TO_USER_ID'));
		if ($enum_fieldss = $dbRes->GetNext()) {
			$PROP = array(
				'ELEMENT_ID'=>$enum_fieldss['PROPERTY_ELEMENT_ID_VALUE'],
				'TO_USER_ID'=>$enum_fieldss['PROPERTY_TO_USER_ID_VALUE'],
				'FROM_USER_ID'=>$enum_fieldss['PROPERTY_FROM_USER_ID_VALUE'],
			);

			if ($enum_fieldss['PROPERTY_FROM_USER_ID_VALUE']!=$USER->GetID()) {
				$this->from_user_id=$enum_fieldss['PROPERTY_FROM_USER_ID_VALUE'];
			}else{
				$this->from_user_id=$enum_fieldss['PROPERTY_TO_USER_ID_VALUE'];
			}
			// print_r(htmlspecialcharsBack($enum_fieldss['PREVIEW_TEXT']));
			$previev=htmlspecialcharsBack($enum_fieldss['PREVIEW_TEXT'])."".$this->mesage_json;
			$arLoadProductArray = Array(
			"IBLOCK_ID"      => AllFunction::IBLOCK_ID,
			"ACTIVE"         => "Y" ,
			"DATE_ACTIVE_FROM"=>date('d.m.Y'),
			"PROPERTY_VALUES"=> $PROP, 
			"NAME"           => $enum_fieldss['NAME'], 
			'PREVIEW_TEXT'	 => $previev,    
			);
			return $arLoadProductArray;
		}
	}
	private function update_element()
	{
		CModule::IncludeModule("iblock");
		$el = new CIBlockElement;
		$arLoadProductArray=$this->get_all_prop($this->id_mesage);
		$res = $el->Update($this->id_mesage, $arLoadProductArray);
        return 'Обнавлен элемент: '.$id_mesage;
	}
	private function add_element()
	{
		CModule::IncludeModule("iblock");
		global $USER;		
		$this->from_user_id=AllFunction::creater_user_element($this->id_element);
		$el = new CIBlockElement;
		$PROP = array(
			'ELEMENT_ID'=>$this->id_element,
			'TO_USER_ID'=>$USER->GetID(),
			'FROM_USER_ID'=>$this->from_user_id,
		);
		$arLoadProductArray = Array(
		"IBLOCK_ID"      => AllFunction::IBLOCK_ID,
		"ACTIVE"         => "Y" ,
		"DATE_ACTIVE_FROM"=>date('d.m.Y'),
		"PROPERTY_VALUES"=> $PROP, 
		"NAME"           => AllFunction::get_NAME($this->id_element), 
		'PREVIEW_TEXT'	 => $this->mesage_json      
		);
	
		if($PRODUCT_ID = $el->Add($arLoadProductArray))
		$this->id_mesage=$PRODUCT_ID;
		else
		echo "Error: ".$el->LAST_ERROR;
	}
	private function jsone_encode()
	{
		$this->mesage_json=json_encode($this->arr_mesage)."\n";
	}
	private function get_mesage_id()
	{
		global $USER;
		CModule::IncludeModule("iblock");
		if ($name=AllFunction::get_NAME((int)$this->id_element)) {
			$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 24,'NAME'=>$name
			), array('ID'));
			if ($enum_fieldss = $dbRes->GetNext()) {	
				return $enum_fieldss['ID'];
			}
		}
	}
}
?>