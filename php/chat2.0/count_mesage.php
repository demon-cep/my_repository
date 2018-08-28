<?include_once($_SERVER['DOCUMENT_ROOT'].'/class/composer.php');
 global $USER;
 if ( AllFunction::count_no_show_mesage()<0) {
 	echo 0;
 }else{
	echo AllFunction::count_no_show_mesage();
}
// print_r(AllFunction::get_arr_user());
 ?>