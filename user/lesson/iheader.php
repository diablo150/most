<!-- Шапка -->
<div class="uitemc <?=(!$user_right?'uitemc_ud':'') ?>">
   <? if ($site_set['utop_bk']): ?>
      <a class="uitemc_bk" href="/user/<?=$site_set['utop_bk']?>">
         <div class=""><i class="fal fa-long-arrow-left"></i></div>
         <span>Артқа</span>
      </a>
   <? endif ?>
   <? if ($user_right): ?>
      <div class="uitemc_um">
         <? if ($pod_menu_name): ?>
            <div class="uitemc_umi uitemc_umi2"><?=$cours['lesson_'.$lang]?></div>
         <? endif ?>
         <a class="uitemc_umi <?=(!$pod_menu_name?'uitemc_umi_act':'')?>" href="/user/lesson/?id=<?=$lesson_id?>">Сабақ</a>
         <a class="uitemc_umi" href="/user/lesson/edit/?id=<?=$lesson_id?>">Өңдеу</a>
         <div class="uitemc_umi" data-id="<?=$lesson_id?>">Архивтеу</div>
      </div>
   <? endif ?>
</div>