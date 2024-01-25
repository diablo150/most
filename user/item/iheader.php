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
         <a class="uitemc_umi <?=(!$pod_menu_name?'uitemc_umi_act':'')?>" href="/user/item/?id=<?=$cours_id?>">Сабақтар</a>
         <a class="uitemc_umi <?=($pod_menu_name=='users'?'uitemc_umi_act':'')?>" href="/user/item/users/?id=<?=$cours_id?>">Оқушылар</a>
         <div class="uitemc_umid">
            <div class="uitemc_umi uitemc_umidl">Қосымша</div>
            <div class="uitemc_umidc">
               <? if (!$cours['setting']): ?> <div class="uitemc_umi cours_edit_pop"><div><i class="fal fa-pen"></i></div><span>Өңдеу</span></div> <? endif ?>
               <div class="uitemc_umi cours_arh" data-id="<?=$cours_id?>">
                  <? if (!$cours['arh']): ?> <div><i class="fal fa-archive"></i></div><span>Архивке салу</span>
                  <? else: ?> <div><i class="fal fa-box-up"></i></div><span>Архивтен шығару</span> <? endif ?>
               </div>
               <? if ($cours['arh']): ?> <div class="uitemc_umi cours_del" data-id="<?=$cours_id?>"><div><i class="fal fa-trash"></i></div><span>Жою</span></div> <? endif ?>
            </div>
         </div>
      </div>
   <? endif ?>
</div>