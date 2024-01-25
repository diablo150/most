<? include "../../config/core.php";

	// Қолданушыны тексеру
	if (!$user_id) header('location: /user/');
	if (!$user['open']) $ubd = db::query("UPDATE `user` SET `open` = 1 WHERE id = '$user_id'");


	// Cours 
	$cours = db::query("select * from cours where arh is null ORDER BY ins_dt DESC");
	if ($user_right) $cours = db::query("select * from cours ORDER BY ins_dt DESC");


	// Сайттың баптаулары
	$menu_name = 'cours';
	if (!$user_right) $menu_name = 'my_cours';
	$css = ['user', 'cours'];
	$js = ['user']; if ($user_right) $js = ['user', 'admin'];
?>
<? include "../../block/header.php"; ?>

	<div class="ucours">

		<div class="head_c">
			<? if (!$user_right): ?><h4>Менің курстарым</h4>
			<? else: ?><h4>Курстар</h4><? endif ?>
		</div>

		<div class="uc_d">
			<? if ($user_right): ?>
				<div class="uc_di bq3_ci_rg cours_add_pop">
					<i class="fal fa-plus"></i>
					<span>Курс қосу</span>
				</div>
			<? endif ?>

			<? while($cours_d = mysqli_fetch_assoc($cours)): ?>
				<? $cours_id = $cours_d['id']; ?>
				<? if ($cours_d['info']) $cours_d = array_merge($cours_d, fun::cours_info($cours_d['id'])); ?>
				<?	$sub = db::query("select * from c_sub where cours_id = '$cours_id' and user_id = '$user_id'"); ?>
				<? if (mysqli_num_rows($sub) || $user_right) : ?>
					<? if (mysqli_num_rows($sub) && !$user_right) $sub_i = mysqli_fetch_assoc($sub); else $sub_i = null; ?>
					<a class="uc_di" href="/user/item/?id=<?=$cours_id?>">
						<div class="uc_dit">
							<div class="bq_ci_info"><div class="bq_cih"><?=$cours_d['name_'.$lang]?></div></div>
							<div class="bq_ci_img"><div class="lazy_img" data-src="/assets/uploads/course/<?=$cours_d['img']?>"></div></div>
						</div>
						<div class="uc_dib">
							<? if ($sub_i['view']) $precent = round(100 / ($cours_d['item'] / $sub_i['view'])); ?>
							<div class="uc_dib_ckb">
								<div class="uc_dib_ckb2">
									<div class="itemci_ls">
										<? if ($cours_d['arh']): ?> <div class="itemci_lsi itemci_lsi_arh">Курс архивте</div> <? endif ?>
										<? if ($cours_d['item']): ?> <div class="itemci_lsi"><?=($sub_i['view']?$sub_i['view'].'/':'')?><?=$cours_d['item']?> сабақ</div> <? endif ?>
										<? if ($cours_d['test']): ?> <div class="itemci_lsi"><?=$cours_d['test']?> тест</div> <? endif ?>
										<? if ($cours_d['assig']): ?> <div class="itemci_lsi"><?=$cours_d['assig']?> тапсырма</div> <? endif ?>
									</div>
									<? if ($sub_i['view']): ?> <div class="itemci_lsr"><?=$precent?>%</div> <? endif ?>
								</div>
								<? if ($sub_i['view']): ?>
									<div class="uitemci_time_b">
										<div class="uitemci_time_b2" style="width:<?=$precent?>%"></div>
									</div>
								<? endif ?>
							</div>
							<? if (!$sub_i['view']): ?> <div class="bq_ci_btn"><div class="btn btn_gr btn_dd"><i class="fal fa-long-arrow-right"></i></div></div> <? endif ?>
						</div>
					</a>
				<? endif ?>
			<? endwhile ?>
		</div>

	</div>

<? include "../../block/footer.php"; ?>


<? if ($user_right): ?>
	<!-- cours add -->
	<div class="pop_bl pop_bl2 cours_add_block">
		<div class="pop_bl_a cours_add_back"></div>
		<div class="pop_bl_c">
			<div class="head_c">
				<h5>Cабақты қосу</h5>
				<div class="btn btn_dd2 cours_add_back"><i class="fal fa-times"></i></div>
			</div>
			<div class="pop_bl_cl">
				<div class="form_c">
					<div class="form_im">
						<div class="form_span">Курстың атауы:</div>
                  <input type="text" class="form_txt cours_name" placeholder="Атауын жазыңыз" data-lenght="2" />
						<i class="fal fa-text form_icon"></i>
               </div>
					<div class="form_im">
						<div class="form_span">Доступ уақыты:</div>
                  <input type="tel" class="form_txt fr_days cours_access" placeholder="60 күн" data-lenght="1" />
						<i class="fal fa-calendar-alt form_icon"></i>
               </div>
					<div class="form_im">
						<div class="form_span">Автор:</div>
						<input type="text" class="form_txt cours_autor" placeholder="Авторды жазыңыз" data-lenght="2" />
						<i class="fal fa-user-graduate form_icon"></i>
					</div>

					<div class="form_im">
						<div class="form_span">Курс фотосы:</div>
						<input type="file" class="cours_img file dsp_n" accept=".png, .jpeg, .jpg">
						<div class="form_im_img lazy_img cours_img_add" data-txt="Фотоны жаңарту">Құрылғыдан таңдау</div>
					</div>

					<div class="form_im form_im_toggle">
						<div class="form_span">Бағасын жазу:</div>
						<input type="checkbox" class="price_inp" data-val="" />
						<div class="form_im_toggle_btn price1_clc"></div>
					</div>
					<div class="price1_block">
						<div class="form_im">
							<div class="form_span">Бағасы:</div>
							<input type="tel" class="form_im_txt fr_price cours_price" placeholder="10.000 тг" data-lenght="1" />
							<i class="fal fa-tenge form_icon"></i>
						</div>
						<div class="form_im">
							<div class="form_span">Жіңілдік бағасы:</div>
							<input type="tel" class="form_im_txt fr_price cours_price_sole" placeholder="10.000 тг" data-lenght="1" />
							<i class="fal fa-tenge form_icon"></i>
						</div>
					</div>

					<div class="form_im form_im_toggle">
						<div class="form_span">Информация жазу:</div>
						<input type="checkbox" class="info_inp" data-val="" />
						<div class="form_im_toggle_btn number1_clc"></div>
					</div>
					<div class="number1_block">
						<div class="form_im">
							<div class="form_span">Сабақ саны:</div>
							<input type="tel" class="form_im_txt fr_number cours_item" placeholder="12" data-lenght="1" />
							<i class="fal fa-play form_icon"></i>
						</div>
						<div class="form_im">
							<div class="form_span">Тапсырма саны:</div>
							<input type="tel" class="form_im_txt fr_number cours_assig" placeholder="3" data-lenght="1" />
							<i class="fal fa-scroll-old form_icon"></i>
						</div>
					</div>

					<div class="form_im form_im_bn">
						<div class="btn btn_item_add"><i class="far fa-check"></i><span>Сақтау</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<? endif ?>