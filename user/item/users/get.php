<? include "../../../config/core.php";

   // time
	$ins_dt = $datetime;
	$end_dt = date('Y-m-d H:i:s', strtotime("$ins_dt +2 month"));

	// add user
	if(isset($_GET['add_user'])) {
		$phone = strip_tags($_POST['phone']);
		$cours_id = strip_tags($_POST['cours_id']);
		
		$cours = fun::cours($cours_id);
		$cours_name = $cours['name_'.$lang];
		$days = $cours['access'];
		$end_dt = date('Y-m-d H:i:s', strtotime("$ins_dt +$days day"));
		$mess = "Cізге $cours_name курсына доступ ашылды.\nCіздің нөміріңіз: $phone, Тексеру коды: 123456.\nСілтеме: https://dos.kz/?c=$cours_id";

		$user = db::query("SELECT * FROM `user` WHERE phone = '$phone'");
		if (mysqli_num_rows($user) != 0) {
			$user_d = mysqli_fetch_assoc($user);
			$user_id = $user_d['id'];
			$code = $user_d['code'];
			$sub = db::query("SELECT * FROM `c_sub` WHERE user_id = '$user_id' and cours_id = '$cours_id'");
			if (mysqli_num_rows($sub) == 0) {
				$mess = "Cізге $cours_name курсына доступ ашылды.\nCіздің нөміріңіз: $phone, Тексеру коды: 123456.\nСілтеме: https://dos.kz/?c=$cours_id";
				$sms_send = list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $mess, 0, 0, 0, 0, false);
				if ($sms_send[1] <= 4) {
					$ins = db::query("INSERT INTO `c_sub`(`cours_id`, `user_id`, `ins_dt`, `end_dt`) VALUES ('$cours_id', '$user_id', '$ins_dt', '$end_dt')");
					if ($ins) echo 'add'; else echo 'error2';
				} else echo 'error';
			} else echo 'yes';
		} else {
			$user_ins = db::query("INSERT INTO `user`(`phone`, `code`, `sms`, `ins_dt`) VALUES ('$phone', '$code', 1, '$ins_dt')");
			if ($user_ins) {
				$user_d = mysqli_fetch_assoc(db::query("SELECT * FROM `user` WHERE phone = '$phone'"));
				$user_id = $user_d['id'];
				$sms_send = list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $mess, 0, 0, 0, 0, false);
				if ($sms_send[1] <= 4) {
					$ins = db::query("INSERT INTO `c_sub`(`cours_id`, `user_id`, `ins_dt`, `end_dt`) VALUES ('$cours_id', '$user_id', '$ins_dt', '$end_dt')");
					if ($ins) echo 'add'; else echo 'error2';
				} else echo 'error';
			} else echo 'error';
		}
		exit();
	}

	// add user mail
	if(isset($_GET['add_umail'])) {
		$mail = strip_tags($_POST['mail']);
		$cours_id = strip_tags($_POST['cours_id']);
		
		$cours = fun::cours($cours_id);
		$cours_name = $cours['name_'.$lang];
		$days = $cours['access'];
		$end_dt = date('Y-m-d H:i:s', strtotime("$datetime +$days day"));

		$mess = "Cізге «$cours_name.» курсына доступ ашылды. \nСілтеме: https://dos.kz/?cm=$cours_id&mail. \nҚайырлы білім болсын!";
      $mess2 = "Cізге «$cours_name.» курсына доступ ашылды. \nСайтқа $mail почтаңыз арқылы кіріңіз. \nСілтеме: https://dos.kz/?cml=$cours_id&mail. \nҚайырлы білім болсын!";

		$user = db::query("SELECT * FROM `user` WHERE mail = '$mail'");
		if (mysqli_num_rows($user) != 0) {

			$user_d = mysqli_fetch_assoc($user);
			$user_id = $user_d['id'];
			$code = $user_d['code'];

			$sub = db::query("SELECT * FROM `c_sub` WHERE user_id = '$user_id' and pack_id = '$pack_id'");
			if (mysqli_num_rows($sub) == 0) {
				db::query("INSERT INTO `c_sub`(`cours_id`, `pack_id`, `user_id`, `curator_id`, `ins_dt`, `end_dt`) VALUES ('$cours_id', '$pack_id', '$user_id', '$curator', '$ins_date', '$end_date')");
				fun::send_mail($mail, $mess);
				echo 'add';
			} else echo 'yes';
		} else {
			$sql = db::query("INSERT INTO `user`(`mail`, `ins_dt`) VALUES ('$mail','$date')");
			if ($sql) {
				$user_d = mysqli_fetch_assoc(db::query("SELECT * FROM `user` WHERE mail = '$mail'"));
				$user_id = $user_d['id'];
				db::query("INSERT INTO `c_sub`(`cours_id`, `pack_id`, `user_id`, `curator_id`, `ins_dt`, `end_dt`) VALUES ('$cours_id', '$pack_id', '$user_id', '$curator', '$ins_date', '$end_date')");
				fun::send_mail($mail, $mess2);
				echo 'add';
			}
		}
		exit();
	}


	// user delete
	if(isset($_GET['user_del'])) {
		$id = strip_tags($_POST['id']);
		$sub = db::query("delete FROM `c_sub` WHERE id = '$id'");
		echo 'yes';
		exit();
	}

	// user_access_on
	if(isset($_GET['user_access_on'])) {
		$id = strip_tags($_POST['id']);
		$sub = db::query("UPDATE `c_sub` SET `status_id`='1' WHERE id = '$id'");
		echo 'yes';
		exit();
	}

	// sms_send
	if(isset($_GET['sms_send'])) {

		$cours_id = strip_tags($_POST['cours_id']);
		$user_id = strip_tags($_POST['user_id']);

		$sub = db::query("SELECT * FROM `c_sub` WHERE cours_id = '$cours_id' and user_id = '$user_id");
		$user = db::query("SELECT * FROM `user` WHERE id = '$user_id'");
		$user_date = mysqli_fetch_assoc($user);
		$phone = $user_date['phone'];
		$code = $user_date['code'];

		$mess = "Иммунити курсы.\nТексеру коды: $code\nСілтеме: https://dos.kz/l/?c=$cours_id";
		list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $mess, 0, 0, 0, 0, false);

		echo 'yes';
		exit();
	}


	// sms_send_all
	// if(isset($_GET['sms_send_all'])) {

	// 	$cours_id = strip_tags($_POST['cours_id']);
	// 	$sub = db::query("SELECT * FROM `cours_sub` WHERE cours_id = '$cours_id'");
	// 	while ($sub_date = mysqli_fetch_assoc($sub)) {

	// 		$user_id = $sub_date['user_id'];
	// 		$user = db::query("SELECT * FROM `user` WHERE id = '$user_id'");
	// 		$user_date = mysqli_fetch_assoc($user);
	// 		$phone = $user_date['phone'];
	// 		$code = $user_date['code'];
	
	// 		$mess = "Иммунити курсы.\nТексеру коды: $code\nСілтеме: https://dos.kz/?c=$cours_id";
	// 		list($sms_id, $sms_cnt, $cost, $balance) = send_sms($phone, $mess, 0, 0, 0, 0, false);

	// 	}
	// 	echo 'yes';

	// 	exit();
	// }