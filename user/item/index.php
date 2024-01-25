<? include "../../config/core.php";

	// Қолданушыны тексеру
	if (!$user_id) header('location: /user/');

	// Курс деректері
	if (isset($_GET['id']) || $_GET['id'] != '') {
		$cours_id = $_GET['id'];
		$cours = db::query("select * from cours where id = '$cours_id'");
		if (mysqli_num_rows($cours)) {
			$cours_d = mysqli_fetch_assoc($cours);			
			if ($cours_d['info']) $cours_d = array_merge($cours_d, fun::cours_info($cours_d['id']));
		} else header('location: /user/cours/');
	} else header('location: /user/cours/');

	// Жазылымды тексеру
	$buy = db::query("select * from c_sub where cours_id = '$cours_id' and user_id = '$user_id'");
	if (mysqli_num_rows($buy) || $user_right) {
		$sub_i = mysqli_fetch_assoc($buy);
	} else $buy = 0;


	// 
	$cblock = db::query("select * from c_block where cours_id = '$cours_id' order by number asc");

	
	// Сайттың баптаулары
	$menu_name = 'item';
	$site_set['utop_bk'] = 'cours';
	$site_set['utop'] = $cours_d['name_'.$lang];
	$site_set['autosize'] = true;
	$css = ['user', 'item'];
	$js = ['user']; if ($user_right) $js = ['user', 'admin'];
?>
<? include "../../block/header.php"; ?>

	<div class="uitem">

		<div class="uitem_c <?=(mysqli_num_rows($cblock) == 0?'uitem_c2':'')?>">
			<!-- Инфо -->
			<div class="uitemc_l">

				<? if ($user_right): ?>
					<div class="uitemc_um">
						<a class="uitemc_umi <?=(!$pod_menu_name?'uitemc_umi_act':'')?>" href="/user/item/?id=<?=$cours_id?>">Сабақтар</a>
						<a class="uitemc_umi <?=($pod_menu_name=='users'?'uitemc_umi_act':'')?>" href="/user/item/users/?id=<?=$cours_id?>">Оқушылар</a>
						<div class="uitemc_umid">
							<div class="uitemc_umi uitemc_umidl">Қосымша</div>
							<div class="menu_c uitemc_umidc">
								<? if (!$cours_d['setting']): ?>
									<div class="menu_ci cours_edit_pop">
										<div class="menu_cin"><i class="fal fa-pen"></i></div>
										<div class="menu_cih">Өңдеу</div>
									</div>
								<? endif ?>
								<div class="menu_ci cours_copy" data-id="<?=$cours_id?>">
									<div class="menu_cin"><i class="fal fa-copy"></i></div>
									<div class="menu_cih">Көшіру</div>
								</div>
								<div class="menu_ci cours_arh" data-id="<?=$cours_id?>">
									<? if (!$cours_d['arh']): ?>
										<div class="menu_cin"><i class="fal fa-archive"></i></div>
										<div class="menu_cih">Архивке салу</div>
									<? else: ?>
										<div class="menu_cin"><i class="fal fa-box-up"></i></div>
										<div class="menu_cih">Архивтен шығару</div>
									<? endif ?>
								</div>
								<? if ($cours_d['arh']): ?>
									<div class="menu_ci cours_del" data-id="<?=$cours_id?>">
										<div class="menu_cin"><i class="fal fa-trash"></i></div>
										<div class="menu_cih">Жою</div>
									</div>
								<? endif ?>
							</div>
						</div>
					</div>
				<? endif ?>

				<div class="uitemci_ck">
					<div class="uitemci_ckt">
						<div class="uitemci_cktl"><h1 class="uitemci_h"><?=$cours_d['name_'.$lang]?></h1></div>
						<div class="uitemci_cktr"><div class="lazy_img" data-src="/assets/uploads/course/<?=$cours_d['img']?>"></div></div>
					</div>

					<div class="uitemci_ckb">
						<? if ($sub_i['view']) $precent = round(100 / ($cours_d['item'] / $sub_i['view'])); ?>
						<div class="uitemci_ckb2">
							<div class="itemci_ls">
								<? if ($cours_d['arh']): ?> <div class="itemci_lsi itemci_lsi_arh">Курс архивте</div> <? endif ?>
								<? if ($cours_d['item']): ?> <div class="itemci_lsi"><?=($sub_i['view']?$sub_i['view'].'/':'')?><?=$cours_d['item']?> сабақ</div> <? endif ?>
								<? if ($cours_d['test']): ?> <div class="itemci_lsi"><?=$cours_d['test']?> тест</div> <? endif ?>
								<? if ($cours_d['assig']): ?> <div class="itemci_lsi"><?=$cours_d['assig']?> тапсырма</div> <? endif ?>
							</div>
							<? if ($sub_i['view']): ?> <div class=""><?=$precent?>%</div> <? endif ?>
						</div>
						<? if ($sub_i['view']): ?>
							<div class="uitemci_time_b">
								<div class="uitemci_time_b2" style="width:<?=$precent?>%"></div>
							</div>
						<? endif ?>
					</div>

					<? if (!$user_right && $sub_i != 0): ?>
						<div class="uitemci_tt">
							<span>Доступ:</span>
							<? if ($sub_i['ins_dt'] != null && $sub_i['end_dt'] != null):?>
								<? $result = (strtotime($sub_i['end_dt']) - strtotime(date("d.m.Y"))) / (60*60*24); ?>
								<? $result2 = (strtotime($sub_i['end_dt']) - strtotime($sub_i['ins_dt'])) / (60*60*24); ?>
								<?	if ($result > 0) $precent = round(100 / ($result2 / ($result2 - $result))); else $precent = 100; ?>
							<? endif ?>
							<div class="uitemci_time">
								<div class="uitemci_time_t">
									<div class="">Басталды: <?=date("d-m-Y", strtotime($sub_i['ins_dt']))?></div>
									<div class="">Соңы: <?=date("d-m-Y", strtotime($sub_i['end_dt']))?></div>
								</div>
								<div class="uitemci_time_t">
									<div class="">
										<? if ($result > 0): ?> Аяқталуына: <?=$result?> күн бар
										<? else: ?> Аяқталғанына: <?=abs($result)?> күн болды <? endif ?>
									</div>
									<div class=""><?=$precent?>%</div>
								</div>
								<div class="uitemci_time_b"><div class="uitemci_time_b2" style="width:<?=$precent?>%"></div></div>
							</div>
						</div>
					<? endif ?>
				</div>

				<!--  -->
				<div class="">
					
				</div>
			</div>

			<!-- lesson -->
			<div class="uc_list">
				<? if (mysqli_num_rows($cblock) != 0): ?>
					<span>Сабақтардың тізімі:</span>
					<div class="cours_ls">
						<? while ($block = mysqli_fetch_assoc($cblock)): ?>
							<?	$block_id = $block['id']; ?>
							<?	$item_d = db::query("select * from c_lesson where block_id = '$block_id' order by number asc"); ?>
							<div class="coursls_cn">
								<? if (mysqli_num_rows($cblock) != 1): ?>
									<div class="coursls_i coursls_b">
										<div class="coursls_ic">
											<div class="coursls_in"><?=$block['number']?>. <?=$block['name_'.$lang]?></div>
											<div class="coursls_ip">
												<? if ($block['item']): ?> <div class="coursls_ipi"><?=$block['item']?> сабақ</div> <? endif ?>
												<? if ($block['test']): ?> <div class="coursls_ipi"><?=$block['test']?> тест</div> <? endif ?>
												<? if ($block['assig']): ?> <div class="coursls_ipi"><?=$block['assig']?> тапсырма</div> <? endif ?>
											</div>
										</div>
										<div class="coursls_il2"><i class="fal fa-angle-down"></i></div>
									</div>
								<? endif ?>
								<div class="">
									<? if (mysqli_num_rows($item_d) != 0): ?>
										<? while ($item = mysqli_fetch_assoc($item_d)): ?>
											<? if ($user_right): ?>
												<div class="coursls_i clc_lesson_b" data-id="<?=$item['id']?>">
													<div class="coursls_ic">
														<div class="coursls_in"><?=(mysqli_num_rows($cblock)!=1?$block['number'].'.':'')?><?=$item['number']?>-сабақ. <?=$item['name_'.$lang]?></div>
													</div>
													<!-- <a class="coursls_il" href="lesson.php?id=<?=$item['id']?>"><i class="far fa-play"></i></a> -->
													<div class="coursls_il coursls_il2 clc_lesson_b" data-id="<?=$item['id']?>"><i class="far fa-ellipsis-v"></i></div>
												</div>
											<? else: ?>
												<a class="coursls_i" <?=($item['open']?'href="lesson.php?id='.$item['id'].'"':'')?>>
													<div class="coursls_ic">
														<div class="coursls_in"><?=(mysqli_num_rows($cblock)!=1?$block['number'].'.':'')?><?=$item['number']?>-сабақ. <?=$item['name_'.$lang]?></div>
													</div>
													<? if ($item['open']): ?><div class="coursls_il"><i class="far fa-play"></i></div>
													<? else: ?><div class="coursls_il coursls_il_lock"><i class="far fa-lock"></i></div><? endif ?>
												</a>
											<? endif ?>
										<? endwhile ?>
									<? endif ?>
								</div>
								<? if ($user_right): ?>
									<div class="coursls_i_rg">
										<div class="btn btn_k add_lesson_b" data-block-id="<?=$block_id?>">
											<i class="far fa-layer-plus"></i>
											<span>Сабақ қосу</span>
										</div>
									</div>
								<? endif ?>
							</div>
						<? endwhile ?>
					</div>
					<? if ($user_right): ?>
						<div class="coursls_i_rg">
							<div class="btn btn_k add_block_b">
								<i class="far fa-layer-plus"></i>
								<? if (mysqli_num_rows($cblock) == 1): ?> <span>Бөлімдерге бөлу</span>
								<? else: ?> <span>Басқа бөлім қосу</span> <? endif ?>
							</div>
						</div>
					<? endif ?>
				<? else: ?>
					<? if ($user_right): ?>
						<div class="coursls_i_rg">
							<div class="btn btn_k add_lesson_b">
								<i class="far fa-layer-plus"></i>
								<span>1-cабақты қосу</span>
							</div>
						</div>
					<? endif ?>
				<? endif ?>
			</div>

		</div>
	</div>

<? include "../../block/footer.php"; ?>


<? if ($user_right): ?>



	<!-- block add -->
	<div class="pop_bl pop_bl2 block_add">
		<div class="pop_bl_a block_add_back"></div>
		<div class="pop_bl_c">
			<div class="head_c txt_c">
				<h5>Бөлім қосу</h5>
				<div class="btn btn_dd2 block_add_back"><i class="fal fa-times"></i></div>
			</div>
			<div class="pop_bl_cl">
				<div class="form_c">
					<div class="form_im">
						<div class="form_span">Бөлімнің атауы:</div>
						<input type="text" class="form_txt block_name" placeholder="Атауын жазыңыз" data-lenght="2">
						<i class="far fa-text form_icon"></i>
					</div>

					<div class="form_im form_im_toggle">
						<div class="form_span">Информация жазу:</div>
						<input type="checkbox" class="info_inp" data-val="" />
						<div class="form_im_toggle_btn number1_clc"></div>
					</div>
					<div class="number1_block">
						<div class="form_im">
							<div class="form_span">Сабақ саны:</div>
							<input type="tel" class="form_im_txt fr_number block_item" placeholder="12" data-lenght="1" />
							<i class="fal fa-play form_icon"></i>
						</div>
						<div class="form_im">
							<div class="form_span">Тапсырма саны:</div>
							<input type="tel" class="form_im_txt fr_number block_assig" placeholder="3" data-lenght="1" />
							<i class="fal fa-scroll-old form_icon"></i>
						</div>
					</div>

					<div class="form_im form_im_bn"><div class="btn btn_block_add" data-cours-id="<?=$cours_id?>"><span>Қосу</span></div></div>
				</div>
			</div>
		</div>
	</div>


	<!-- cours edit -->
	<div class="pop_bl pop_bl2 cours_edit_block">
		<div class="pop_bl_a cours_edit_back"></div>
		<div class="pop_bl_c">
			<div class="head_c">
				<h5>Курс өзгерту</h5>
				<div class="btn btn_dd2 cours_edit_back"><i class="fal fa-times"></i></div>
			</div>
			<div class="pop_bl_cl lazy_c">
				<div class="form_c">
					<div class="form_im">
						<div class="form_span">Курстың атауы:</div>
                  <input type="text" class="form_txt cours_name" placeholder="Атауын жазыңыз" data-lenght="2" value="<?=$cours_d['name_kz']?>" />
						<i class="fal fa-text form_icon"></i>
               </div>
					<div class="form_im">
						<div class="form_span">Доступ уақыты:</div>
                  <input type="tel" class="form_txt fr_days cours_access" placeholder="60 күн" data-lenght="1" value="<?=$cours_d['access']?>" />
						<i class="fal fa-calendar-alt form_icon"></i>
               </div>
					<div class="form_im">
						<div class="form_span">Автор:</div>
						<input type="text" class="form_txt cours_autor" placeholder="Авторды жазыңыз" data-lenght="2" value="<?=$cours_d['autor']?>" />
						<i class="fal fa-user-graduate form_icon"></i>
					</div>

					<div class="form_im">
						<div class="form_span">Курс фотосы:</div>
						<input type="file" class="cours_img file dsp_n" accept=".png, .jpeg, .jpg">
						<div class="form_im_img cours_img_add <?=($cours_d['img']?'form_im_img2':'')?>" <?=($cours_d['img']?'style="background-image: url(/assets/img/cours/'.$cours_d['img'].')"':'')?> data-txt="Фотоны жаңарту">Құрылғыдан таңдау</div>
					</div>

					<div class="form_im form_im_toggle">
						<div class="form_span">Бағасын жазу:</div>
						<input type="checkbox" class="price_inp" data-val="" />
						<div class="form_im_toggle_btn price1_clc <?=($cours_d['price']||$cours_d['price_sole']?'form_im_toggle_act':'')?>"></div>
					</div>
					<div class="price1_block <?=($cours_d['price']||$cours_d['price_sole']?'price1_block_act':'')?>">
						<div class="form_im">
							<div class="form_span">Бағасы:</div>
							<input type="tel" class="form_im_txt fr_price cours_price" placeholder="10.000 тг" data-lenght="1" value="<?=$cours_d['price']?>" />
							<i class="fal fa-tenge form_icon"></i>
						</div>
						<div class="form_im">
							<div class="form_span">Жіңілдік бағасы:</div>
							<input type="tel" class="form_im_txt fr_price cours_price_sole" placeholder="10.000 тг" data-lenght="1" value="<?=$cours_d['price_sole']?>" />
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
							<input type="tel" class="form_im_txt fr_number cours_item" placeholder="12" data-lenght="1" value="<?=$cours_d['item']?>" />
							<i class="fal fa-play form_icon"></i>
						</div>
						<div class="form_im">
							<div class="form_span">Тапсырма саны:</div>
							<input type="tel" class="form_im_txt fr_number cours_assig" placeholder="3" data-lenght="1" value="<?=$cours_d['assig']?>" />
							<i class="fal fa-scroll-old form_icon"></i>
						</div>
					</div>

					<div class="form_im form_im_bn"><div class="btn btn_cours_edit" data-cours-id="<?=$cours_id?>"><i class="far fa-check"></i><span>Сақтау</span></div></div>
				</div>
			</div>
		</div>
	</div>




	<!-- lesson add -->
	<div class="pop_bl pop_bl2 lesson_add">
		<div class="pop_bl_a lesson_add_back"></div>
		<div class="pop_bl_c">
			<div class="head_c txt_c">
				<h5>Cабақты қосу</h5>
				<div class="btn btn_dd2 lesson_add_back"><i class="fal fa-times"></i></div>
			</div>
			<div class="pop_bl_cl">
				<div class="form_c">
					<div class="form_im">
						<div class="form_span">Cабақтың атауы:</div>
						<input type="text" class="form_txt lesson_name" placeholder="Атауын жазыңыз" data-lenght="2">
						<i class="far fa-text form_icon"></i>
					</div>
					<div class="form_im form_im_toggle">
						<div class="form_span">Сабақты ашып қою:</div>
						<input type="checkbox" class="lesson_open" data-val="1" />
						<div class="form_im_toggle_btn form_im_toggle_act"></div>
					</div>

					<!-- <div class="form_span">Сабақты толықтыру:</div> -->
					<div class="form_im">
						<div class="form_span">Видеосы: (Yotube)</div>
						<input type="url" class="form_txt youtube lesson_youtube" placeholder="Сілтемесін салыңыз" data-lenght="1" />
						<i class="fal fa-play form_icon"></i>
					</div>
					<div class="form_im">
						<div class="form_span">Мәтіні:</div>
						<textarea type="text" class="form_im_comment_aut lesson_txt" rows="5" autocomplete="off" autocorrect="off" aria-label="Мәтінді жазыңыз .." placeholder="Мәтінді жазыңыз .." ></textarea>
						<script>autosize(document.querySelectorAll('.form_im_comment_aut'));</script>
					</div>
					<div class="form_im">
						<div class="form_span">Сілтеме:</div>
						<input type="url" class="form_txt lesson_url" placeholder="Сілтемені салыңыз" data-lenght="1" />
						<i class="fal fa-link form_icon"></i>
					</div>

					<div class="form_im">
						<div class="form_span">Файл:</div>
						<input type="file" class="lfile_add file lesson_mat dsp_n" accept=".png, .jpeg, .jpg, .pdf">
						<div class="form_im_img lazy_img lesson_file_add" data-txt="Файлды жаңарту">Құрылғыдан таңдау</div>
					</div>

					<div class="form_im form_im_bn"><div class="btn btn_lesson_add" data-cours-id="<?=$cours_id?>"><span>Қосу</span></div></div>
				</div>
			</div>
		</div>
    </div>




	<!-- block add -->
	<div class="pop_bl pop_bl2 lesson_edit">
		<div class="pop_bl_a lesson_edit_back"></div>
		<div class="pop_bl_c">
			<div class="head_c txt_c">
				<h5>Cабақты өңдеу</h5>
				<div class="btn btn_dd2 lesson_edit_back"><i class="fal fa-times"></i></div>
			</div>
			<div class="pop_bl_cl">
				<div class="lesson_edit_89">
					<div class="menu_c lesson_clc_menu" data-id="">
						<a class="menu_ci lesson_clc_viewm" href="" target="_blank">
							<div class="menu_cin"><i class="fal fa-external-link"></i></div>
							<div class="menu_cih">Ашу</div>
						</a>
						<div class="menu_ci edit_lesson_b">
							<div class="menu_cin"><i class="fal fa-pen"></i></div>
							<div class="menu_cih">Өңдеу</div>
						</div>
						<div class="menu_ci cours_copy" data-id="<?=$cours_id?>">
							<div class="menu_cin"><i class="fal fa-copy"></i></div>
							<div class="menu_cih">Көшіру</div>
						</div>
						<div class="menu_ci del_lesson_b">
							<div class="menu_cin"><i class="fal fa-trash"></i></div>
							<div class="menu_cih">Жою</div>
						</div>
					</div>
				</div>
				<div class="lesson_edit_99"></div>
			</div>
		</div>
	</div>


<? endif ?>