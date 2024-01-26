<? include "./config/core.php";

	if (isset($_GET['c'])) header('location: /user/sign.php?id='.$_GET['c']);
	else header('location: /user/');