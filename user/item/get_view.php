<? include "../../config/core.php"; ?>

   <!--  -->
   <? if (isset($_GET['view'])): ?>
		<? $id = strip_tags($_POST['id']); ?>
		<? $lesson_d = fun::lesson($id); ?>
		<? $course_d = fun::cours($lesson_d['cours_id']); ?>

         <div class="form_c">
            <div class="form_im">
               <div class="form_span">Cабақтың атауы:</div>
               <input type="text" class="form_txt lesson_edt_name" placeholder="Атауын жазыңыз" data-lenght="2" value="<?=$lesson_d['name_kz']?>">
               <i class="far fa-text form_icon"></i>
            </div>
            <div class="form_im form_im_toggle">
               <div class="form_span">Сабақты ашып қою:</div>
               <input type="checkbox" class="lesson_edt_open" data-val="1" />
               <div class="form_im_toggle_btn form_im_toggle_act"></div>
            </div>

            <? if ($course_d['view'] == 1): ?>
               <div class="form_im">
                  <div class="form_span">Бұл сабақтан не аласың?</div>
                  <textarea type="text" class="form_im_comment_aut v1_txt1" rows="5" autocomplete="off" autocorrect="off" aria-label="Мәтінді жазыңыз .." placeholder="Мәтінді жазыңыз .." >
                     <?=$lesson_d['v1_txt1']?>
                  </textarea>
               </div>
               <div class="form_im">
                  <div class="form_span">Тапсырма:</div>
                  <textarea type="text" class="form_im_comment_aut v1_home1" rows="5" autocomplete="off" autocorrect="off" aria-label="Мәтінді жазыңыз .." placeholder="Мәтінді жазыңыз .." >
                     <?=$lesson_d['v1_home1']?>
                  </textarea>
               </div>
            <? endif ?>

            <!-- <div class="form_span">Сабақты толықтыру:</div> -->

            <? $li = db::query("select * from c_lesson_item where lesson_id = '$id' order by ins_dt desc"); ?>
            <? while ($li_d = mysqli_fetch_assoc($li)): ?>
               
               <? if ($li_d['type'] == 'video'): ?>
                  <div class="form_im">
                     <div class="form_span">Видеосы (<?=($li_d['number'])?>): (Yotube)</div>
                     <input type="url" class="form_txt lesson_edt_youtube" data-id="<?=$li_d['id']?>" placeholder="Сілтемесін салыңыз" data-lenght="1" value="<?=$li_d['txt']?>" />
                     <i class="fal fa-play form_icon"></i>
                  </div>
               <? elseif ($li_d['type'] == 'txt'): ?>
                  <div class="form_im">
                     <div class="form_span">Мәтіні (<?=($li_d['number'])?>):</div>
                     <textarea type="text" class="form_im_comment_aut lesson_edt_txt" data-id="<?=$li_d['id']?>" rows="5" autocomplete="off" autocorrect="off" aria-label="Мәтінді жазыңыз .." placeholder="Мәтінді жазыңыз .." >
                        <?=$li_d['txt']?>
                     </textarea>
                  </div>
               <? endif ?>

            <? endwhile ?>

            <div class="form_im">
               <div class="form_span">Видеосы: (Yotube)</div>
               <input type="url" class="form_txt youtube lesson_ss_youtube" placeholder="Сілтемесін салыңыз" data-lenght="1" />
               <i class="fal fa-play form_icon"></i>
            </div>
            <div class="form_im">
               <div class="form_span">Мәтіні:</div>
               <textarea type="text" class="form_im_comment_aut lesson_ss_txt" rows="5" autocomplete="off" autocorrect="off" aria-label="Мәтінді жазыңыз .." placeholder="Мәтінді жазыңыз .." ></textarea>
            </div>
            <div class="form_im">
               <div class="form_span">Сілтеме:</div>
               <input type="url" class="form_txt lesson_ss_url" placeholder="Сілтемені салыңыз" data-lenght="1" />
               <i class="fal fa-link form_icon"></i>
            </div>

            <div class="form_im">
               <div class="form_span">Файл:</div>
               <input type="file" class="lfile_add file lesson_ss_mats dsp_n" accept=".png, .jpeg, .jpg, .pdf">
               <div class="form_im_img lazy_img lesson_file_add" data-txt="Файлды жаңарту">Құрылғыдан таңдау</div>
            </div>

            <!-- <div class="form_im form_im_toggle">
               <div class="btn btn_cl btn_li_add" data-id="<?=$id?>">
                  <i class="fal fa-plus"></i>
                  <span>Қосу</span>   
               </div>
            </div> -->

            <div class="form_im form_im_bn">
               <div class="btn btn_lesson_edit" data-id="<?=$id?>">Сақтау</div>
            </div>

            <script>autosize(document.querySelectorAll('.form_im_comment_aut'));</script>
         </div>


		<? exit(); ?>
	<? endif ?>