$(document).ready(function() {


	// СМС
	$('.disb_zab').click(function(){
		$('.fr').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.zabr_back').click(function(){
		$('.fr').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})

	$('.orderSend').on('click', function() {
		var name = $(this).parent().siblings().children('.name')
		var phone = $(this).parent().siblings().children('.phone')
		if (name.attr('data-pr') != 1 || phone.attr('data-pr') != 1) { mess('Введите свой данный') }
		else {
			$.ajax({
				url: "/send/?mess",
				type: "POST",
				dataType: "html",
				data: ({name: name.val(), phone: phone.val()}),
				success: function(data){
					if (data == 'yes') { 
						mess('Сәтті жіберілді')
					} else {
						mess('Қайта кіріуіңізді сұраймын!')
					}
				},
				beforeSend: function(){ mess('Отправка..') },
				error: function(data){ mess('Ошибка..') }
			})
		}
	})
	




	// 
	$('.fb_zap').on('click', function() {

		if ($(this).attr('data-btn') == 1) {
			f_phone = $(this).parent().siblings().children('.f_phone')
			f_name 	= $(this).parent().siblings().children('.f_name')
		} else {
			f_phone = $(this).parent().siblings('.form_mn').children().children('.f_phone')
			f_name 	= $(this).parent().siblings('.form_mn').children().children('.f_name')
		}
		
		f_ph = f_phone.val().replace(/ /g, '').replace(/\+/g, '').replace(/\)/g, '').replace(/\(/g, '').replace(/-/g, '').replace(/_/g, '')

		if ((f_ph.length != f_phone.attr('data-lenght')) || (f_name.length && f_name.val().length <= f_name.attr('data-lenght'))) {

			if (f_ph.length != f_phone.attr('data-lenght')) {
				f_phone.parent().addClass('form_pr_nm')
				f_phone.parent().addClass('form_pr_n')
			}
			if (f_name.length && f_name.val().length <= f_name.attr('data-lenght')) {
				f_name.parent().addClass('form_pr_nm')
				f_name.parent().addClass('form_pr_n')
			}

		} else {

			if (!f_name.length) {
				f_nm = 0
			} else {
				f_nm = f_name.val()
			}

			$.ajax({
				url: "/send/?mess",
				type: "POST",
				dataType: "html",
				data: ({f_phone: f_phone.val(), f_name: f_nm}),
				success: function(data){
					if (data == 'yes') {
						mess(bl_yes)
						f_phone.parent().removeClass('form_pr_y')
						f_phone.val('')
						f_name.parent().removeClass('form_pr_y')
						f_name.val('')
					}
				},
				beforeSend: function(){
					mess('Жіберуде..')
				},
				error: function(data){
					mess('Қате..')
				}
			})
		}
	})








	


   $('.progress_ring_c').each(function() {
      radius = $(this).attr('r');
      precent = $(this).attr('data-precent')
      circumference = 2 * Math.PI * radius;
      $(this).css('strokeDasharray', circumference + ' ' + circumference)
      $(this).css('strokeDashoffset', circumference - precent / 100 * circumference)
   });



	var swiper = new Swiper(".swiper_catalog", {
		slidesPerView: "auto",
		navigation: {
		  nextEl: ".swiper-button-next1",
		  prevEl: ".swiper-button-prev1",
		},
	});
	var swiper = new Swiper(".swiper_catalog2", {
		slidesPerView: "auto",
		navigation: {
		  nextEl: ".swiper-button-next2",
		  prevEl: ".swiper-button-prev2",
		},
	});













	

	// var slide_min = new Swiper('.slide_min', {
 //      	slidesPerView: 'auto',
 //      	pagination: {
	//         el: '.slide_min_pag',
	//         type: 'progressbar',
	// 	},
 //    });
 //    var slide_max = new Swiper('.slide_max', {
 //      	slidesPerView: 'auto',
 //      	pagination: {
	//         el: '.slide_max_pag',
	//         type: 'progressbar',
	// 	},
	// 	navigation: {
	// 		nextEl: '.slide_max_next',
	// 		prevEl: '.slide_max_prev',
	// 	},
 //    });
 //    var home_cours_cat_c = new Swiper('.home_cours_cat_c', {
 //      	slidesPerView: 'auto',
 //      	pagination: {
	//         el: '.home_cours_cat_c_pag',
	//         type: 'progressbar',
	// 	},
 //    });



	// var galleryThumbs = new Swiper('.gallery-thumbs', {
	//     slidesPerView: 'auto',
	//     freeMode: true,
	//     watchSlidesVisibility: true,
	//     watchSlidesProgress: true,
 //    });
 //    var galleryTop = new Swiper('.gallery-top', {
 //    	autoHeight: true,
	// 	loop:true,
 //      	thumbs: { swiper: galleryThumbs }
 //    });


    // кaрусел
	// var galleryThumbs = new Swiper('.gallery-thumbs', {
	// 	loop:true,
	// 	slidesPerView: 3,
	// 	allowTouchMove: false,
	// 	freeMode: true,
	// 	watchSlidesVisibility: true,
	// 	watchSlidesProgress: true,
	// 	breakpoints: {
	//         360: {
	//           	slidesPerView: 2,
	// 			allowTouchMove: true,
	//         },
	//     }
	// })
	// var galleryTop = new Swiper('.gallery-top', {
	// 	autoplay: {
	//     	delay: 5000,
	//   	},
	//   	speed: 500,
	// 	loop:true,
	// 	navigation: {
	// 		nextEl: '.swiper-button-next',
	// 		prevEl: '.swiper-button-prev',
	// 	},
	// 	thumbs: {
	// 		swiper: galleryThumbs,
	// 	},
	// })





	$('.bq_cipcni').on('click', function () { 
		$(this).siblings('.bq_cipcni').removeClass('bq_cipcni_act');
		$(this).addClass('bq_cipcni_act');
		$(this).parent().siblings('p').html($(this).attr('data-price'))
	})

	$('.btn_ukl').click(function (e) { 
      e.preventDefault();
      $('.oko').addClass('oko_act')
   });
   $('.oko_close').click(function (e) { 
      e.preventDefault();
      $('.oko').removeClass('oko_act')
   });






})