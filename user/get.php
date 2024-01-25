<? include "../config/core.php";


	// login
	if(isset($_GET['login'])) {
		$phone = strip_tags($_POST['phone']);
		$m_code = strip_tags($_POST['code']);
		// $password = strip_tags($_POST['password']);
		$user = db::query("SELECT * FROM user WHERE phone = '$phone' and phone is not null");
		if (mysqli_num_rows($user)) {
			$user_d = mysqli_fetch_assoc($user);
			// if ($password == '000000') {
			// 	$_SESSION['uph'] = $phone;
			// 	$_SESSION['upc'] = $password;
			// 	setcookie('uph', $phone, time() + 3600*24*30*6);
			// 	setcookie('upc', $password, time() + 3600*24*30*6);
			// 	echo 'yes';
			// } else echo 'none';
			if ($user_d['sms']) {
			} else {
				$mess = " | Тексеру коды: $code";
				$sms_send = list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $mess, 0, 0, 0, 0, false);
				if ($sms_send[1] <= 4) {
					$ubd = db::query("UPDATE `user` SET `code`='$code', `sms`=1 WHERE phone = '$phone'");
					echo "code_add";
				} else echo "error";
			}
		} else echo 'none';
		exit();
	}


	// pass_reset
	if(isset($_GET['pass_reset'])) {
		$phone = strip_tags($_POST['phone']);
		$user = db::query("SELECT * FROM user WHERE phone = '$phone'");
		if (mysqli_num_rows($user)) {
			$mess = " | Тексеру коды: $code";
			$sms_send = list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $mess, 0, 0, 0, 0, false);
			if ($sms_send[1] == 1) {
				$ubd = db::query("UPDATE `user` SET `code`='$code', `sms`=1 WHERE phone = '$phone'");
				echo "yes";
			} else echo "error";
		} else echo 'none';
		exit();
	}


	// phone sms
	if(isset($_GET['phone_sms'])) {
		$phone = strip_tags($_POST['phone']);
		$user = db::query("SELECT * FROM user WHERE phone = '$phone'");
		if (mysqli_num_rows($user)) {
			$user_d = mysqli_fetch_assoc($user);
			if (!$user_d['sms']) {
				$mess = " | Тексеру коды: $code";
				$sms_send = list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $mess, 0, 0, 0, 0, false);
				if ($sms_send[1] <= 4) {
					$ubd = db::query("UPDATE `user` SET `code`='$code', `sms`=1 WHERE phone = '$phone'");
					echo "yes";
				} else echo "error";
			}
		} else echo 'none';
		exit();
	}





	// 
	if(isset($_GET['add_user_img'])) {
		$path = '../assets/uploads/users/';
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

	// user edit
	if(isset($_GET['user_edit'])) {
		$name = strip_tags($_POST['name']);
		$surname = strip_tags($_POST['surname']);
		$age = strip_tags($_POST['age']);
		$img = strip_tags($_POST['img']);
		$code = strip_tags($_POST['code']);
		
		if ($name) $upd = db::query("UPDATE `user` SET `name`='$name' WHERE id = '$user_id'");
		if ($surname) $upd = db::query("UPDATE `user` SET `surname`='$surname' WHERE id = '$user_id'");
		if ($age) $upd = db::query("UPDATE `user` SET `age`='$age' WHERE id = '$user_id'");
		if ($img) $upd = db::query("UPDATE `user` SET `img`='$img' WHERE id = '$user_id'");
		if ($code) $upd = db::query("UPDATE `user` SET `code`='$code' WHERE id = '$user_id'");

		echo "yes";
		exit();
	}


	// // user edit
	// if(isset($_GET['user_edit'])) {
	// 	$name = strip_tags($_POST['name']);
	// 	$surname = strip_tags($_POST['surname']);
	// 	$age = strip_tags($_POST['age']);
	// 	$code = strip_tags($_POST['code']);
		
	// 	$upd = db::query("UPDATE `user` SET `name`='$n_name', `surname`='$surname', `sex`='$sex', `age`='$age', `mail`='$mail', `phone`='$phone', `password`='$password', `upd_date`='$date' WHERE id = '$user_id'");

	// 	$_SESSION['uph'] = $phone;
	// 	$_SESSION['upm'] = $mail;
	// 	$_SESSION['ups'] = $password;
	// 	setcookie('uph', $phone, time() + 3600*24*30);
	// 	setcookie('upm', $mail, time() + 3600*24*30);
	// 	setcookie('ups', $password, time() + 3600*24*30);

	// 	echo "yes";
	// 	exit();
	// }