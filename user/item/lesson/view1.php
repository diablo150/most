<? include "../../../config/core.php";

   // 
   if (!$user_id) header('location: /user/');

   if (isset($_GET['id']) || $_GET['id'] != '') {
      $lesson_id = $_GET['id'];
      $lesson = db::query("select * from c_lesson where id = '$lesson_id'");
      if (mysqli_num_rows($lesson)) {
         $lesson = mysqli_fetch_assoc($lesson);
         $block_id = $lesson['block_id'];
         $cours_id = fun::cours_block($block_id);

         $cours_d = fun::cours($cours_id);
         if ($cours_d['private']) header('location: /user/'.$cours_d['private_link'].'/lesson.php?id='.$lesson_id);
         if ($lesson['site']) header('location: /user/'.$lesson['site'].'?id='.$lesson_id);

         
         $sub = db::query("select * from c_sub where cours_id = '$cours_id' and user_id = '$user_id'");
         if (mysqli_num_rows($sub) == 1 && !$user_right) $sub_i = mysqli_fetch_array($sub); else $sub_i = 0;

         // 
         $sub_info = db::query("select * from c_sub_lesson where lesson_id = '$lesson_id' and user_id = '$user_id'");
         if (mysqli_num_rows($sub_info) != 0) {
            $sub_info_d = mysqli_fetch_array($sub_info);
            db::query("UPDATE `c_sub_lesson` SET `upd_date` = '$date', `lesson_view` = 1 where lesson_id = '$lesson_id' and user_id = '$user_id'");
            if (!$sub_info_d['lesson_stage']) $sub_info_d['lesson_stage'] = 1;
         } else { 
            db::query("INSERT INTO `c_sub_lesson`(`lesson_id`, `user_id`, `lesson_view`, `ins_date`, `upd_date`) VALUES ('$lesson_id', '$user_id', 1, '$date', '$date')");
            $sub_info_d['lesson_stage'] = 1;
         }

         $ls = db::query("select * from c_lesson where block_id = '$block_id'");
         $number_prev = $lesson['number'] - 1;
         $number_next = $lesson['number'] + 1;
         while ($ls_d = mysqli_fetch_assoc($ls)) {
            $result = (strtotime(date("d.m.Y")) - strtotime($sub_i['ins_date'])) / (60*60*24*7);
            $weeks = floor($result);
            if (($ls_d['number']==$number_prev && $user_right) || ($ls_d['number']==$number_prev && $ls_d['open'] == 1)) $lesson_prev_id = $ls_d['id'];
            if (($ls_d['number']==$number_next && $user_right) || ($ls_d['number']==$number_next && $ls_d['open'] == 1 && $weeks >= $number_next)) $lesson_next_id = $ls_d['id'];
         }

      } else header('location: /user/cours/');
   } else header('location: /user/cours/');


   // site setting
	$menu_name = 'lesson';
   $site_set['footer'] = false;
   $site_set['utop'] = $lesson['number'].'. '.$lesson['name_'.$lang];
   $site_set['utop_bk'] = 'item/?id='.$cours_id;
   $site_set['plyr'] = true;
   $css = ['user', 'lesson', 'lesson2'];
   $js = ['user', 'lesson'];
?>
<? include "../../../block/header.php"; ?>

   <div class="les">

      <div class="ulesson">

         <div class="ls2_name">
            <h2><?=$lesson['name_'.$lang]?></h2>
         </div>

         <div class="ls2_vs">
            <h6 class="lsb_ih">Бұл сабақтан не аласың?</h6>
            <div class="prd_txt"><?=$lesson['v1_txt1']?></div>
         </div>

         <? $info = db::query("select * from c_lesson_item where lesson_id = '$lesson_id' order by number asc"); ?>
         <? if (mysqli_num_rows($info)): ?>
            <div class="" data-lesson-id="<?=$lesson['id']?>">
               <? while ($sql = mysqli_fetch_assoc($info)): ?>
                  <? if ($sql['type'] == 'video'): ?>
                     <div class="lsb_i " data-number="<?=$sql['number']?>" data-type="<?=$sql['type']?>">
                        <div class="lsbi_video">
                           <div class="container">
                              <div class="player_<?=$sql['id']?>" data-plyr-provider="<?=$sql['type_video']?>" data-plyr-embed-id="<?=$sql['txt']?>"></div>
                           </div>
                        </div>
                        <script> const player_<?=$sql['id']?> = new Plyr(".player_<?=$sql['id']?>", { fullscreen: {iosNative: true}, controls: ['play-large', 'play', 'mute', 'volume', 'progress', 'current-time',  'fullscreen'] }); </script>
                     </div>
                  <? endif ?>
               <? endwhile ?>
            </div>
         <? endif ?>

         <? $info = db::query("select * from c_lesson_item where lesson_id = '$lesson_id' order by number asc"); ?>
         <? if (mysqli_num_rows($info)): ?>
            <div class="" data-lesson-id="<?=$lesson['id']?>">
               <h6 class="lsb_ih">Қосымша материалдар</h6>
               <? while ($sql = mysqli_fetch_assoc($info)): ?>
                  <? if ($sql['type'] == 'mat'): ?>

                     <div class="ls2_oo5">
                        <div class="">● <?=$sql['txt']?></div>
                        <div class="ls2_oo5c">
                           <a class="btn btn_back" href="/assets/uploads/lesson/<?=$sql['txt']?>" target="_blank">
                              <i class="fal fa-folder-open"></i>
                              <span>Ашу</span>
                           </a>
                           <a class="btn btn_back" href="/assets/uploads/lesson/<?=$sql['txt']?>" download>
                              <i class="fal fa-cloud-download"></i>
                              <span>Жүктеп алу</span>
                           </a>
                        </div>
                     </div>

                  <? elseif ($sql['type'] == 'link'): ?>

                     <div class="ls2_oo5">
                        <div class="">● <?=$sql['txt']?></div>
                        <div class="ls2_oo5c">
                           <a class="btn btn_back" href="<?=$sql['txt']?>" target="_blank">
                              <i class="fal fa-folder-open"></i>
                              <span>Ашу</span>
                           </a>
                        </div>
                     </div>

                  <? endif ?>

                  <? $data_number = $sql['number']; ?>
               <? endwhile ?>
            </div>
         <? endif ?>

         <br><br><br>

         <div class="" data-lesson-id="<?=$lesson['id']?>">
            <h6 class="lsb_ih">Тапсырма</h6>
            <div class=""><?=$lesson['v1_home1']?></div>
         </div>

         <br><br><br>

         <div class="" data-lesson-id="<?=$lesson['id']?>">
            <h6 class="lsb_ih">Тапсырманы жүктеу орны</h6>
            <div class=""></div>
         </div>

         <br><br><br>
   
         <div class="ulesson_btn">
            <div class="ulesson_btn_c">
               <? if ($lesson_prev_id): ?>
                  <a href="?id=<?=$lesson_prev_id?>" class="btn_prev">
                     <div class="btn btn_back">
                        <i class="fal fa-long-arrow-left"></i>
                        <span>Алдыңғы сабаққа</span>
                     </div>
                  </a>
               <? endif ?>
               <a href="../?id=<?=$cours_d['id']?>" class="btn_end">
                  <div class="btn btn_back">
                     <i class="far fa-times"></i>
                     <span>Сабақты аяқтау</span>
                  </div>
               </a>
               <? if ($lesson_next_id): ?>
                  <a href="?id=<?=$lesson_next_id?>" class="btn_next">
                     <div class="btn btn_back">
                        <span>Келесі сабаққа</span>
                        <i class="fal fa-long-arrow-right"></i>
                     </div>
                  </a>
               <? endif ?>
            </div>
         </div>
      </div>

      <div class="les_r"></div>
   </div>

<? include "../../../block/footer.php"; ?>




<div class="lsb_i">
   <div class="lsbi_video">
      <div class="container">
         <div class="player_<?=$sql['id']?>" data-plyr-provider="youtube" data-plyr-embed-id="13zrr2WdSD4"></div>
         <script> const player_<?=$sql['id']?> = new Plyr(".player_<?=$sql['id']?>", { fullscreen: {iosNative: true}, controls: ['play-large', 'play', 'current-time', 'progress', 'fullscreen'] }); </script>
      </div>
   </div>
</div>