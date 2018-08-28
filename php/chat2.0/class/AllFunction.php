<?

/**
* Просто набор функции
*/
class AllFunction 
{
	const IBLOCK_ID=24;
	public static function user_show_property($id)
	{

			global $USER;
			$rsUser = CUser::GetByID($USER->GetID());
			$arUser = $rsUser->Fetch();
			foreach ($arUser['UF_MESAGE'] as $value) {
				if ($id==$value) {
					$bool=true;	
				}
			}
			if (!$bool) {	
			$arUser['UF_MESAGE'][count($arUser['UF_MESAGE'])]=(int)$id;
			$user = new CUser;
			$fields = Array( 
			"UF_MESAGE" => $arUser['UF_MESAGE'], 
				); 
			$user->Update($USER->GetID(), $fields);

			}
			return $bool;
	}
		public static function user_del_property($id,$user_id)
	{

			global $USER;
			$rsUser = CUser::GetByID($user_id);
			$arUser = $rsUser->Fetch();
			// print_r($arUser['UF_MESAGE']);
			foreach ($arUser['UF_MESAGE'] as $key => $value) {
				if ($id==$value) {
					$bool=true;	
					echo $value;
						unset($arUser['UF_MESAGE'][$key]);
						echo "YES";
				}
			}
			if ($bool) {	
			// $arUser['UF_MESAGE'][0]=1111;
		
			$user = new CUser;
			$fields = Array( 
			"UF_MESAGE" => $arUser['UF_MESAGE'], 
				); 
			$user->Update($user_id, $fields);

			}
			echo "ID_USER ".$user_id;
			return $arUser['UF_MESAGE'];
	}
	public static function acsses($id)
	{
		CModule::IncludeModule("iblock");
		$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => self::IBLOCK_ID, 'ID'=>(int)$id, array(
		        "LOGIC" => "OR",
				array("PROPERTY_FROM_USER_ID" => $USER->GetID()),
		         array("PROPERTY_TO_USER_ID" => $USER->GetID()),
		    ),
		    
		), array("NAME",'PROPERTY_SHOW','ID','PREVIEW_TEXT'));
		if ($enum_fieldss = $dbRes->GetNext()) {
			return true;
		}

	}

	public static function get_user_name($id)
	 {
	 	global $USER;
	 	$rsUser = CUser::GetByID((int)$id);
	         $arUser = $rsUser->Fetch();
	         if ($arUser['ID']) {
				  if ($arUser["WORK_COMPANY"]=="") {
          	return $arUser["NAME"]." ".$arUser["LAST_NAME"];
          }else{
			return $arUser["WORK_COMPANY"];
          }
			}
	 }
	 	public static function get_user_email($id)
	 {
	 	global $USER;
	 	$rsUser = CUser::GetByID((int)$id);
	         $arUser = $rsUser->Fetch();
	         if ($arUser['ID']) {
			return $arUser["EMAIL"];
        
			}
	 }
	public static function count_no_show_mesage()
	{
		$sum=AllFunction::count_all_element()-AllFunction::no_show_mesage_user();
		return $sum;
	}
	public static function no_show_mesage_user()
	 {
	 	global $USER;
			$rsUser = CUser::GetByID($USER->GetID());
			$arUser = $rsUser->Fetch();
			foreach ($arUser['UF_MESAGE'] as $key => $value) {
				if (AllFunction::proverca($value)) {
					$i++;
					// echo AllFunction::get_NAME($value);
				}
			}
			return $i;
	 }
	public static function get_arr_user()
	 {
	 	global $USER;
			$rsUser = CUser::GetByID($USER->GetID());
			$arUser = $rsUser->Fetch();
			return $arUser['UF_MESAGE'];
	 }
	public static function proverca($id)
	 {
	 	global $USER;
		CModule::IncludeModule("iblock");
		$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => self::IBLOCK_ID, 'ID'=>$id, array(
	        "LOGIC" => "OR",
				 array("PROPERTY_FROM_USER_ID" => $USER->GetID()),
		         array("PROPERTY_TO_USER_ID" => $USER->GetID()),
	    ),
		    
		), array("ID"));
		if ($enum_fieldss = $dbRes->GetNext()) {
			return true;
		}
	 }
	public static function count_all_element()
	{
		global $USER;
		CModule::IncludeModule("iblock");
		$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => self::IBLOCK_ID, array(
	        "LOGIC" => "OR",
				 array("PROPERTY_FROM_USER_ID" => $USER->GetID()),
		         array("PROPERTY_TO_USER_ID" => $USER->GetID()),
	    ),
		    
		), array("ID"));
		while ($enum_fieldss = $dbRes->GetNext()) {
			$i++;
		}
		return (int)$i;
	}
	public static function show_name_mesage()
	{

		global $USER;
		CModule::IncludeModule("iblock");
		$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => self::IBLOCK_ID, array(
        "LOGIC" => "OR",
				array("PROPERTY_FROM_USER_ID" => $USER->GetID()),
		         array("PROPERTY_TO_USER_ID" => $USER->GetID()),
	    ), 
		), array("NAME",'PROPERTY_SHOW','ID','PROPERTY_FROM_USER_ID','PROPERTY_TO_USER_ID'));
		while ($enum_fieldss = $dbRes->GetNext()) {	
			$user_element_id=AllFunction::get_arr_user();
			$bool=false;
			// print_r($user_element_id);
			foreach ($user_element_id as  $value) {
				// echo $value;
				if ($value==$enum_fieldss['ID']) {
					$bool=true;
				}
			}
			
			if ($bool) {
				?>
					<a class="<?if($enum_fieldss['ID']==(int)$_GET['ID'])echo 'active'?>" href="?ID_MESAGE=<?=$enum_fieldss['ID']?>"><?=$enum_fieldss['NAME']?> <span class="creater_by"><?echo self::get_user_name(self::get_other_user($enum_fieldss['PROPERTY_FROM_USER_ID_VALUE'],$enum_fieldss['PROPERTY_TO_USER_ID_VALUE']))?></span></a>
				<?
			}else{
				?>
					<a class="no_read <?if($enum_fieldss['ID']==(int)$_GET['ID'])echo 'active'?>" href="?ID_MESAGE=<?=$enum_fieldss['ID']?>"><?=$enum_fieldss['NAME']?><span class="creater_by"><?echo self::get_user_name(self::get_other_user($enum_fieldss['PROPERTY_FROM_USER_ID_VALUE'],$enum_fieldss['PROPERTY_TO_USER_ID_VALUE']))?></span></a>
				<?
			}
		}
	}
	public static function get_other_user($from_id,$to_id)
	{
		// echo $from_id;
		global $USER;
		if ($USER->GetID()!=(int)$from_id) {
			return $from_id;
		}else{
			return $to_id;
		}
	}
	public static function get_NAME($id)
	{
		CModule::IncludeModule("iblock");
     $res = CIBlockElement::GetByID((int)$id);
		if($ar_res = $res->GetNext())
		  return $ar_res['NAME'];
    } 
    public function get_mesage_to_element_id($id)
    {
    	global $USER;
    	CModule::IncludeModule("iblock");
    	$dbRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => self::IBLOCK_ID, "PROPERTY_ELEMENT_ID" => (int)$id,array(
        "LOGIC" => "OR",
				array("PROPERTY_FROM_USER_ID" => $USER->GetID()),
		         array("PROPERTY_TO_USER_ID" => $USER->GetID()),
	    )),  array("ID"));
		if ($prop_fields = $dbRes->GetNext()){
			// print_r($prop_fields);
			// if ($prop_fields['PROPERTY_ELEMENT_ID_VALUE']==) {
				return $prop_fields['ID'];
			// }
			
		}
    }
    public static function creater_user_element($id)
    {
    	CModule::IncludeModule("iblock");
    	 $res = CIBlockElement::GetByID((int)$id);
		if($ar_res = $res->GetNext())
		{
			return $ar_res['CREATED_BY'];
		}
    	
    }

}
?>