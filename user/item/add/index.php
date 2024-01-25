<?php include "../../config/core.php";
	
	// Қолданушыны тексеру
	if (!$user_id || !$user_right) header('location: /u/');
	
	// Беттің баптаулары
	$menu_name = 'item_add';
	$site_set = [
		'header' => 'user',
		'footer' => 'false',
		'ublock' => 'true',
		'utop_nm' => 'Курс қосу',
		'utop_bk' => 'cours/',
	];
	$css = ['user'];
	$js = ['user','admin'];
	
?>
<?php include "../../block/header.php"; ?>


   <div class="uacc">
      <div class="uacc_c">
         <div class="uacc_i">
            <div class="uacc_in">Басты фото:</div>
            <div class="uacc_ic">
               <div class="upl_logo">
                  <input type="file" class="item_file file" accept=".png, .jpeg, .jpg">
                  <div class="upl_logo_img upl_logo_img2 lazy_ava" data-src="/assets/img/icons/hot-beverage_2615.png"></div>
                  <div class="upl_logo_c item_ava_clc">Фото қою</div>
               </div>
            </div>
         </div>
         <div class="uacc_i">
            <div class="uacc_in">Атауы:</div>
            <div class="uacc_ic">
               <div class="form_im">
                  <input type="text" class="form_im_txt name" placeholder="Мысалы: Тыныш балам" data-lenght="3" data-sel="0" />
               </div>
            </div>
         </div>
         <div class="uacc_i">
            <div class="uacc_in">Бағасы:</div>
            <div class="uacc_ic">
               <div class="form_im">
                  <input type="tel" class="form_im_txt fr_price price" placeholder="10.000 тг" data-lenght="1" />
               </div>
            </div>
         </div>
         <div class="uacc_i">
            <div class="uacc_in">Жіңілдік бағасы:</div>
            <div class="uacc_ic">
               <div class="form_im">
                  <input type="tel" class="form_im_txt fr_price price_sole" placeholder="10.000 тг" data-lenght="1" />
               </div>
            </div>
         </div>
         <div class="uacc_i">
            <div class="uacc_in">Бағытын таңдаңыз:</div>
            <div class="uacc_ic">
               <div class="form_im form_sel">
                  <div class="form_im_txt sel_clc category" data-val="1">Курс</div>
                  <i class="fal fa-caret-down form_icon_sel"></i>
                  <div class="form_im_sel sel_clc_i">
                     <?php $cat = db::query("select * from category"); ?>
                     <?php while($cat_d = mysqli_fetch_array($cat)): ?>
                        <div class="form_im_seli" data-val="<?=$cat_d['id']?>"><?=$cat_d['name']?></div>
                     <?php endwhile ?>
                  </div>
               </div>
            </div>
         </div>
         <div class="uacc_i">
            <div class="uacc_in">Авторды таңдаңыз:</div>
            <div class="uacc_ic">
               <div class="form_im form_sel">
                  <div class="form_im_txt sel_clc autor" data-val="4">Ару Сағи</div>
                  <i class="fal fa-caret-down form_icon_sel"></i>
                  <div class="form_im_sel sel_clc_i">
                     <?php $autor = db::query("select * from user where autor = 1"); ?>
                     <?php while($autor_d = mysqli_fetch_array($autor)): ?>
                        <div class="form_im_seli" data-val="<?=$autor_d['id']?>"><?=$autor_d['name']?> <?=$autor_d['surname']?></div>
                     <?php endwhile ?>
                  </div>
               </div>
            </div>
         </div>
         <div class="uacc_i">
            <div class="uacc_in">Үй жұмысы:</div>
            <div class="uacc_ic">
               <div class="form_im form_im_toggle toggle_homework">
                  <input type="checkbox" class="homework" data-val="" />
                  <div class="form_im_toggle_btn"></div>
               </div>
            </div>
         </div>
      </div>
      <div class="uacc_b">
         <div class="btn btn_cl btn_item_add">
            <i class="far fa-check"></i>
            <span>Сақтау</span>
         </div>
      </div>
   </div>



<?php include "../../block/footer.php"; ?>