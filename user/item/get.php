<? include "../../config/core.php";

	// 
	if(isset($_GET['add_item_photo'])) {
		$path = '../../assets/uploads/course/';
		$allow = array();
		$deny = array(
			'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
			'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
			'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi', 'exe'
		);
		$error = $success = '';
		$datetime = date('Ymd-His', time());

		if (!isset($_FILES['file'])) $error = 'Файлды жүктей алмады';
		else {
			$file = $_FILES['file'];
			if (!empty($file['error']) || empty($file['tmp_name'])) $error = 'Файлды жүктей алмады';
			elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) $error = 'Файлды жүктей алмады';
			else {
				$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
				$name = mb_eregi_replace($pattern, '-', $file['name']);
				$name = mb_ereg_replace('[-]+', '-', $name);
				$parts = pathinfo($name);
				$name = $datetime.'-'.$name;

				if (empty($name) || empty($parts['extension'])) $error = 'Файлды жүктей алмады';
				elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) $error = 'Файлды жүктей алмады';
				elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) $error = 'Файлды жүктей алмады';
				else {
					if (move_uploaded_file($file['tmp_name'], $path . $name)) $success = '<p style="color: green">Файл «' . $name . '» успешно загружен.</p>';
					else $error = 'Файлды жүктей алмады';
				}
			}
		}
		
		if (!empty($error)) $error = '<p style="color:red">'.$error.'</p>';  
		
		$data = array(
			'error'   => $error,
			'success' => $success,
			'file' => $name,
		);
		
		header('Content-Type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);

		exit();
	}



	
	// 
	if(isset($_GET['item_add'])) {
		$name = strip_tags($_POST['name']);
		$access = strip_tags($_POST['access']);
		$autor = strip_tags($_POST['autor']);
		$img = strip_tags($_POST['img']);
		$price = strip_tags($_POST['price']);
		$price_sole = strip_tags($_POST['price_sole']);
		$item = strip_tags($_POST['item']);
		$assig = strip_tags($_POST['assig']);
		$id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `cours`")))['max(id)'] + 1;

		$ins = db::query("INSERT INTO `cours`(`name_kz`, `name_ru`) VALUES ('$name', '$name')");

		if ($access) $upd = db::query("UPDATE `cours` SET `access`='$access' WHERE `id`='$id'");
		if ($autor) $upd = db::query("UPDATE `cours` SET `autor`='$autor' WHERE `id`='$id'");
		if ($img) $upd = db::query("UPDATE `cours` SET `img`='$img' WHERE `id`='$id'");
		if ($price) $upd = db::query("UPDATE `cours` SET `price`='$price' WHERE `id`='$id'");
		if ($price_sole) $upd = db::query("UPDATE `cours` SET `price_sole`='$price_sole' WHERE `id`='$id'");
		if ($item || $assig) {
			$upd = db::query("UPDATE `cours` SET `info`=1 WHERE `id`='$id'");
			$ins_info = db::query("INSERT INTO `cours_info`(`cours_id`) VALUES ('$id')");
			if ($item) $upd = db::query("UPDATE `cours_info` SET `item`='$item' WHERE `cours_id`='$id'");
			if ($assig) $upd = db::query("UPDATE `cours_info` SET `assig`='$assig' WHERE `cours_id`='$id'");
		}

		if ($ins) echo 'plus';
		exit();
	}

	// 
	if(isset($_GET['item_edit'])) {
		$id = strip_tags($_POST['id']);
		$name = strip_tags($_POST['name']);
		$access = strip_tags($_POST['access']);
		$autor = strip_tags($_POST['autor']);
		$img = strip_tags($_POST['img']);
		$price = strip_tags($_POST['price']);
		$price_sole = strip_tags($_POST['price_sole']);
		$item = strip_tags($_POST['item']);
		$assig = strip_tags($_POST['assig']);

		if ($name) $upd = db::query("UPDATE `cours` SET `name_kz`='$name', `name_ru`='$name', `upd_dt`='$datetime' WHERE `id`='$id'");
		if ($access) $upd = db::query("UPDATE `cours` SET `access`='$access', `upd_dt`='$datetime' WHERE `id`='$id'");
		if ($autor) $upd = db::query("UPDATE `cours` SET `autor`='$autor', `upd_dt`='$datetime' WHERE `id`='$id'");
		if ($img) $upd = db::query("UPDATE `cours` SET `img`='$img', `upd_dt`='$datetime' WHERE `id`='$id'");
		if ($price) $upd = db::query("UPDATE `cours` SET `price`='$price', `upd_dt`='$datetime' WHERE `id`='$id'");
		if ($price_sole) $upd = db::query("UPDATE `cours` SET `price_sole`='$price_sole', `upd_dt`='$datetime' WHERE `id`='$id'");
		if ($item || $assig) {
			$upd = db::query("UPDATE `cours` SET `info` = 1 WHERE `id` = '$id'");
			if (mysqli_num_rows(db::query("SELECT * FROM `cours_info` where cours_id = '$id'")) == 0) $ins_info = db::query("INSERT INTO `cours_info`(`cours_id`) VALUES ('$id')");
			if ($item) $upd = db::query("UPDATE `cours_info` SET `item`='$item' WHERE `cours_id`='$id'");
			if ($assig) $upd = db::query("UPDATE `cours_info` SET `assig`='$assig' WHERE `cours_id`='$id'");
		}

		echo 'plus';
		exit();
	}

	
	// 
	if(isset($_GET['cours_copy'])) {
		$course_id = strip_tags($_POST['id']);
		$course_d = fun::cours($course_id);

		// 
		$new_course_id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `cours`")))['max(id)'] + 1;
		$ncourse_number = (mysqli_fetch_assoc(db::query("SELECT max(number) FROM `cours`")))['max(number)'] + 1;
		$ncourse_name = $course_d['name_kz'].' - көшірме';
		$ncourse_access = $course_d['access'];
		$ncourse_price = $course_d['price'];
		$ncourse_price_sole = $course_d['price_sole'];
		$ncourse_img = $course_d['img'];
		// $ncourse_info = $course_d['info'];
		
		$ins_item = db::query("INSERT INTO `cours`(`id`, `number`, `name_kz`, `access`) VALUES ('$new_course_id', '$ncourse_number', '$ncourse_name', '$ncourse_access')");
		if ($ncourse_price) $upd_item = db::query("UPDATE `cours` SET `price` = '$ncourse_price' WHERE id = '$new_block_id'");
		if ($ncourse_price_sole) $upd_item = db::query("UPDATE `cours` SET `price_sole` = '$ncourse_price_sole' WHERE id = '$new_course_id'");
		if ($ncourse_img) $upd_item = db::query("UPDATE `cours` SET `img` = '$ncourse_img' WHERE id = '$new_course_id'");
		// if ($ncourse_info) $upd_item = db::query("UPDATE `cours` SET `info` = '$ncourse_info' WHERE id = '$new_course_id'");

		// if ($course_d['info']) $course_info = fun::course_info($course_d['id']);

		$block = db::query("select * from c_block where cours_id = '$course_id' order by number asc");
		if (mysqli_num_rows($block)) {
			while ($block_d = mysqli_fetch_assoc($block)) {
				$block_id = $block_d['id'];

				$new_block_id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `c_block`")))['max(id)'] + 1;
				$nblock_number = $block_d['number'];
				$nblock_name = $block_d['name_kz'];
				$nblock_item = $block_d['item'];
				// $nblock_question = $block_d['question'];

				$ins_item = db::query("INSERT INTO `c_block`(`id`, `cours_id`, `number`, `name_kz`) VALUES ('$new_block_id', '$new_course_id', '$nblock_number', '$nblock_name')");
				if ($nblock_item) $upd_item = db::query("UPDATE `c_block` SET `item` = '$nblock_item' WHERE id = '$new_block_id'");
				// if ($nblock_question) $upd_item = db::query("UPDATE `c_block` SET `question` = '$nblock_question' WHERE id = '$new_block_id'");

				$lesson = db::query("select * from c_lesson where block_id = '$block_id' order by number asc");
				if (mysqli_num_rows($lesson)) {
					while ($lesson_d = mysqli_fetch_assoc($lesson)) {
						$lesson_id = $lesson_d['id'];

						$new_lesson_id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `c_lesson`")))['max(id)'] + 1;
						$nlesson_number = $lesson_d['number'];
						$nlesson_name = $lesson_d['name_kz'];
						$nlesson_open = $lesson_d['open'];
						$nlesson_arh = $lesson_d['arh'];

						$ins_item = db::query("INSERT INTO `c_lesson`(`id`, `cours_id`, `block_id`, `number`, `name_kz`) VALUES ('$new_lesson_id', '$new_course_id', '$new_block_id', '$nlesson_number', '$nlesson_name')");
						if ($nlesson_open) $upd_item = db::query("UPDATE `c_lesson` SET `open` = '$nlesson_open' WHERE id = '$new_lesson_id'");
						if ($nlesson_arh) $upd_item = db::query("UPDATE `c_lesson` SET `arh` = '$nlesson_arh' WHERE id = '$new_lesson_id'");

						$item = db::query("select * from c_lesson_item where lesson_id = '$lesson_id' order by number asc");
						if (mysqli_num_rows($item)) {
							while ($item_d = mysqli_fetch_assoc($item)) {

								$new_item_id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `c_lesson_item`")))['max(id)'] + 1;
								$nitem_number = $item_d['number'];
								$nitem_type = $item_d['type'];
								$nitem_type_name = $item_d['type_name'];
								$nitem_type_video = $item_d['type_video'];
								$nitem_txt = $item_d['txt'];

								$ins_item = db::query("INSERT INTO `c_lesson_item`(`id`, `lesson_id`, `number`, `type`) VALUES ('$new_item_id', '$new_lesson_id', '$nitem_number', '$nitem_type')");
								if ($nitem_type_name) $upd_item = db::query("UPDATE `c_lesson_item` SET `type_name` = '$nitem_type_name' WHERE id = '$new_item_id'");
								if ($nitem_type_video) $upd_item = db::query("UPDATE `c_lesson_item` SET `type_video` = '$nitem_type_video' WHERE id = '$new_item_id'");
								if ($nitem_txt) $upd_item = db::query("UPDATE `c_lesson_item` SET `txt` = '$nitem_txt' WHERE id = '$new_item_id'");

							}
						}

					}
				}

			}
		}


		if ($upd) echo 'yes';
		exit();
	}
	

	
	// 
	if(isset($_GET['cours_arh'])) {
		$id = strip_tags($_POST['id']);
		$cours = fun::cours($id);

		if (!$cours['arh']) $upd = db::query("UPDATE `cours` SET `arh` = 1 WHERE `id` = '$id'");
		else $upd = db::query("UPDATE `cours` SET `arh` = null WHERE `id` = '$id'");

		if ($upd) echo 'yes';
		exit();
	}

	// 
	if(isset($_GET['cours_del'])) {
		$id = strip_tags($_POST['id']);
		$del = db::query("DELETE FROM `cours` WHERE `id` = '$id'");
		
		if ($del) echo 'yes';
		exit();
	}














	// 
	if(isset($_GET['block_add'])) {
		$name = strip_tags($_POST['name']);
		$cours_id = strip_tags($_POST['cours_id']);
		$item = strip_tags($_POST['item']);
		$assig = strip_tags($_POST['assig']);
		$number = fun::cblock_next_number($cours_id);
		$id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `c_block`")))['max(id)'] + 1;

		$ins = db::query("INSERT INTO `c_block`(`number`, `cours_id`, `name_kz`, `name_ru`) VALUES ('$number', '$cours_id', '$name', '$name')");

		if ($item || $assig) {
			if ($item) $upd = db::query("UPDATE `c_block` SET `item`='$item' WHERE `id`='$id'");
			if ($assig) $upd = db::query("UPDATE `c_block` SET `assig`='$assig' WHERE `id`='$id'");
		}

		if ($ins) echo 'yes';
		exit();
	}


	// 
	if(isset($_GET['lesson_add'])) {
		$name = strip_tags($_POST['name']);
		$cours_id = strip_tags($_POST['cours_id']);
		$block_id = strip_tags($_POST['block_id']);
		$open = strip_tags($_POST['open']);
		$youtube = strip_tags($_POST['youtube']);
		$txt = strip_tags($_POST['txt']);
		$url = strip_tags($_POST['url']);
		$mat = strip_tags($_POST['mat']);
		
		$cours = fun::cours($cours_id);
		$cours_name_kz = $cours['name_kz'];
		$cours_name_ru = $cours['name_ru'];

		if (!$block_id) {
			$block_id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `c_block`")))['max(id)'] + 1;
			$ins_block = db::query("INSERT INTO `c_block`(`number`, `cours_id`, `name_kz`, `name_ru`) VALUES (1, '$cours_id', '$cours_name_kz', '$cours_name_ru')");
		}
		
		$id = (mysqli_fetch_assoc(db::query("SELECT max(id) FROM `c_lesson`")))['max(id)'] + 1;
		$number = fun::lesson_next_number($block_id);
		$ins = db::query("INSERT INTO `c_lesson`(`cours_id`, `block_id`, `number`, `name_kz`, `name_ru`, `open`) VALUES ('$cours_id', '$block_id', '$number', '$name', '$name', '$open')");

		if ($youtube) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `type_name`, `type_video`, `txt`) VALUES ('$id', 1, 'video', 'Видео сабақ', 'youtube', '$youtube')");
		if ($txt) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `txt`) VALUES ('$id', 2, 'txt', '$txt')");
		if ($url) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `txt`) VALUES ('$id', 3, 'link', '$url')");
		if ($mat) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `txt`) VALUES ('$id', 4, 'mat', '$mat')");
		
		if ($ins) echo 'yes';
		exit();
	}




	// 
	if(isset($_GET['lesson_edit'])) {
		$id = strip_tags($_POST['id']);
		$name = strip_tags($_POST['name']);
		$v1_txt1 = strip_tags($_POST['v1_txt1']);
		$v1_home1 = strip_tags($_POST['v1_home1']);

		// $open = strip_tags($_POST['open']);
		$youtube = strip_tags($_POST['youtube']);
		$youtube_id = strip_tags($_POST['youtube_id']);
		$txt = strip_tags($_POST['txt']);
		$txt_id = strip_tags($_POST['txt_id']);
		$url = strip_tags($_POST['url']);
		$url_id = strip_tags($_POST['url_id']);
		$mat = strip_tags($_POST['mat']);
		$mat_id = strip_tags($_POST['mat_id']);

		if ($name) $ins_li = db::query("UPDATE `c_lesson` SET `name_kz` = '$name' WHERE id = '$id'");
		if ($v1_txt1) $ins_li = db::query("UPDATE `c_lesson` SET `v1_txt1` = '$v1_txt1' WHERE id = '$id'");
		if ($v1_home1) $ins_li = db::query("UPDATE `c_lesson` SET `v1_home1` = '$v1_home1' WHERE id = '$id'");
		if ($youtube) $ins_li = db::query("UPDATE `c_lesson_item` SET `txt` = '$youtube' WHERE id = '$youtube_id'");
		if ($txt) $ins_li = db::query("UPDATE `c_lesson_item` SET `txt` = '$txt' WHERE id = '$txt_id'");
		if ($url) $ins_li = db::query("UPDATE `c_lesson_item` SET `txt` = '$url' WHERE id = '$url_id'");
		if ($mat) $ins_li = db::query("UPDATE `c_lesson_item` SET `txt` = '$mat' WHERE id = '$mat_id'");


		$youtube_new = strip_tags($_POST['youtube_new']);
		$txt_new = strip_tags($_POST['txt_new']);
		$url_new = strip_tags($_POST['url_new']);
		$mat_new = strip_tags($_POST['mat_new']);

		if ($youtube_new) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `type_name`, `type_video`, `txt`) VALUES ('$id', 1, 'video', 'Видео сабақ', 'youtube', '$youtube_new')");
		if ($txt_new) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `txt`) VALUES ('$id', 2, 'txt', '$txt_new')");
		if ($url_new) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `txt`) VALUES ('$id', 3, 'link', '$url_new')");
		if ($mat_new) $ins_li = db::query("INSERT INTO `c_lesson_item`(`lesson_id`, `number`, `type`, `txt`) VALUES ('$id', 4, 'mat', '$mat_new')");

		echo 'yes';
		exit();
	}








	// 
	if(isset($_GET['lesson_del'])) {
		$id = strip_tags($_POST['id']);
		$del = db::query("DELETE FROM `c_lesson` WHERE `id` = '$id'");
		
		if ($del) echo 'yes';
		exit();
	}














	// 
	if(isset($_GET['add_file'])) {
		$path = '../../assets/uploads/lesson/';
		$allow = array();
		$deny = array(
			'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
			'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
			'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi', 'exe'
		);
		$error = $success = '';
		$datetime = date('Ymd-His', time());

		if (!isset($_FILES['file'])) $error = 'Файлды жүктей алмады';
		else {
			$file = $_FILES['file'];
			if (!empty($file['error']) || empty($file['tmp_name'])) $error = 'Файлды жүктей алмады';
			elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) $error = 'Файлды жүктей алмады';
			else {
				$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
				$name = mb_eregi_replace($pattern, '-', $file['name']);
				$name = mb_ereg_replace('[-]+', '-', $name);
				$parts = pathinfo($name);
				$name = $datetime.'-'.$name;

				if (empty($name) || empty($parts['extension'])) $error = 'Файлды жүктей алмады';
				elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) $error = 'Файлды жүктей алмады';
				elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) $error = 'Файлды жүктей алмады';
				else {
					if (move_uploaded_file($file['tmp_name'], $path . $name)) $success = '<p style="color: green">Файл «' . $name . '» успешно загружен.</p>';
					else $error = 'Файлды жүктей алмады';
				}
			}
		}
		
		if (!empty($error)) $error = '<p style="color:red">'.$error.'</p>';  
		
		$data = array(
			'error'   => $error,
			'success' => $success,
			'file' => $name,
		);
		
		header('Content-Type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);

		exit();
	}