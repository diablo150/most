<?php include dirname(__FILE__)."/../../config/core.php";
	
	// Қолданушыны тексеру
	if (!$user_right) header('location: /user/');

	// Курс деректері
	if (isset($_GET['id']) || $_GET['id'] != '') {
		$cours_id = $_GET['id'];
		$cours_d = fun::cours($cours_id);
		if (!$cours_d) header('location: /user/cours/');
	} else header('location: /user/cours/');

	// Сайттың баптаулары
	$menu_name = 'item';
	$site_set['footer'] = false;
	$site_set['utop_bk'] = 'cours';
	$site_set['utop_nm'] = $cours['name_'.$lang];
	$css = ['user', 'item'];
	$js = ['user', 'admin'];