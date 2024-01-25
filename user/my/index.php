<?php include "../../config/core.php";

	// Қолданушыны тексеру
	if (!$user_id) header('location: /user/');


	// 
	$cours = db::query("select * from cours ORDER BY ins_dt DESC");

	
	// Сайттың баптаулары
	$menu_name = 'cours';
	$site_set = [
		'footer' => 'false',
		'utop_nm' => 'Курстар',
	];
	$css = ['user', 'ucours'];
	$js = ['user'];
?>
<?php include "../../block/header.php"; ?>

	<div class="ucours">

		<div class="head_c">
			<h4>Менің курстарым</h4>
		</div>

		<div class="uc_d">
			<? if ($user_right): ?>
				<a class="uc_di bq3_ci_rg" href="/user/item/add.php">
					<div class="bq_ci_s">
						<i class="far fa-plus"></i>
						<span>Курс қосу</span>
					</div>
				</a>
			<? endif ?>

			<? while($cours_d = mysqli_fetch_array($cours)): ?>
				<? $cours_id = $cours_d['id']; ?>
				<?	$sub = db::query("select * from c_sub where cours_id = '$cours_id' and user_id = '$user_id'"); ?>
				<? if (mysqli_num_rows($sub) || $user_right) : ?>
					<a class="uc_di" href="/user/cours/item/?id=<?=$cours_d['id']?>">
						<div class="bq_ci_img"><div class="lazy_img" data-src="/assets/img/cours/<?=$cours_d['img']?>"></div></div>
						<div class="bq_ci_info">
							<div class="bq_cih"><?=$cours_d['name']?></div>
						</div>
						<div class="bq_ci_btn"><div class="btn btn_cm btn_dd"><i class="fal fa-long-arrow-right"></i></div></div>
					</a>
				<? endif ?>
			<? endwhile ?>
		</div>

	</div>

<?php include "../../block/footer.php"; ?>