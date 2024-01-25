// start jquery
$(document).ready(function() {

	// sign in
	$('.btn_sign_in').on('click', function() {
		btn = $(this)
		phone = $('.phone')
		code = $('.code')
		password = $('.password')

		if (password.attr('data-sel') != 1 || phone.attr('data-sel') != 1) {
			if (phone.attr('data-sel') != 1) mess('Cіз телефон нөміріңізді жазбапсыз')
			else mess('Cіз кодты жазбапсыз')
		} else {
			$.ajax({
				url: "/user/get.php?login",
				type: "POST",
				dataType: "html",
				data: ({
					phone: phone.attr('data-val'),
					password: password.attr('data-val')
				}),
				success: function(data){
					if (data == 'yes') location.reload();
					else if (data == 'none') mess('Сіз базадан таппадым, админге жазып көріңіз')
					// else if (data == 'code') mess('Cіздің жазған кодыңыз қате, қайта жазып көріңіз')
					// else if (data == 'code_add') mess('Cізге код жібердім, кодты қайта жазып көріңіз')
					console.log(data);
				},
				beforeSend: function(){ },
				error: function(data){ console.log(data) }
			})
		}
	});


	// reset pass
	$('.btn_pass_reset').on('click', function() {
		phone = $('.phone')
		if (phone.attr('data-sel') != 1) mess('Cіз телефон нөміріңізді жазбапсыз')
		else {
			$.ajax({
				url: "/user/get.php?pass_reset",
				type: "POST",
				dataType: "html",
				data: ({ phone: phone.attr('data-val') }),
				success: function(data){
					if (data == 'none') mess('Сіз базадан таппадым, админге жазып көріңіз')
					if (data == 'yes') mess('Cізге код жібердім, кодты қайта жазып көріңіз')
					if (data == 'error') mess('Cзге смс кетпеді, админмен байланысып көріңіз')
					console.log(data);
				},
				beforeSend: function(){ },
				error: function(data){ console.log(data) }
			})
		}
	});
	
	
	// phone sms
	$('.phone_inp').on('input', function() {
		phone = $(this)
		if (phone.attr('data-sel') == 1) {
			$.ajax({
				url: "/user/get.php?phone_sms",
				type: "POST",
				dataType: "html",
				data: ({ phone: phone.attr('data-val') }),
				success: function(data){
					if (data == 'none') mess('Сіз базадан таппадым, мүмкін нөмірді қате жазған боларсыз')
					else if (data == 'yes') mess('Cізге код жібердім, кодты төменге жазып көріңіз')
					console.log(data);
				},
				beforeSend: function(){ },
				error: function(data){ console.log(data) }
			})
		}
	});




	// ubdate user
	$('.user_edit_pop').click(function(){
		$('.user_edit_block').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.user_edit_back').click(function(){
		$('.user_edit_block').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})

	// 
	$('.user_img_add').click(function(){ $(this).siblings('.user_img').click() })
	$(".user_img").change(function(){
		tfile = $(this)
		if (window.FormData === undefined) mess('Бұл формат келмейді')
		else {
			var formData = new FormData();
			formData.append('file', $(this)[0].files[0]);
			$.ajax({
				type: "POST",
				url: "/user/get.php?add_user_img",
				cache: false, contentType: false,
				processData: false, dataType: 'json',
				data: formData,
				success: function(msg){
					if (msg.error == '') {
						tfile_n = 'url(/assets/uploads/users/' + msg.file + ')'
						tfile.attr('data-val', msg.file)
						tfile.siblings('.user_img_add').addClass('form_im_img2')
						tfile.siblings('.user_img_add').css('background-image', tfile_n)
					} else mess(msg.error)
				},
				beforeSend: function(){ },
				error: function(msg){ console.log(msg) }
			});
		}
	});

	$('.btn_user_edit').click(function () {
		if ($('.user_name').val().length <= 2 || $('.user_code').val().length != 4) {
			if ($('.user_name').val().length <= 2) mess('Атыңызды толтырыңыз')
			if ($('.user_code').val().length != 4) mess('Код бос болмауы керек!')
		} else {
			$.ajax({
				url: "/user/get.php?user_edit",
				type: "POST",
				dataType: "html",
				data: ({
					name: $('.user_name').attr('data-val'), surname: $('.user_surname').attr('data-val'),
					age: $('.user_age').attr('data-val'), img: $('.user_img').attr('data-val'),
					code: $('.user_code').attr('data-val')
				}),
				success: function(data){
					if (data == 'yes') {
						mess('Cәтті сақталды!')
						$('.user_edit_block').removeClass('pop_bl_act');
						setTimeout(function() { location.reload(); }, 500);
					}
					console.log(data);
				},
				beforeSend: function(){ },
				error: function(data){ console.log(data) }
			})
		}
	})
	
	

	// // ubdate user phone
	// $('.user_ph_pop').click(function(){
	// 	$('.user_ph_block').addClass('pop_bl_act');
	// 	$('#html').addClass('ovr_h');
	// })
	// $('.user_ph_back').click(function(){
	// 	$('.user_ph_block').removeClass('pop_bl_act');
	// 	$('#html').removeClass('ovr_h');
	// })
	// $('.btn_user_ph').click(function () {
	// 	if ($('.user_phone').attr('data-sel') != 1) mess('Форманы толтырыңыз')
	// 	else {
	// 		$.ajax({
	// 			url: "/user/get.php?user_ph",
	// 			type: "POST",
	// 			dataType: "html",
	// 			data: ({
	// 				phone: $('.user_phone').attr('data-val'),
	// 				code: $('.new_code').attr('data-val')
	// 			}),
	// 			success: function(data){
	// 				if (data == 'yes') mess('Cәтті сақталды!'); $('.user_ph_block').removeClass('pop_bl_act');
	// 				console.log(data);
	// 			},
	// 			beforeSend: function(){ },
	// 			error: function(data){ console.log(data) }
	// 		})
	// 	}
	// })












	// Че за ? 
	$('.phone').on('input', function() {
		if ($('.btn_fdal').parent().attr('data-type') == 'reg_info') {
			$('.btn_fdal').children('span').html($('.btn_fdal').attr('data-aut'))
			$('.btn_fdal').parent().attr('data-type', 'aut')
			$('.lg_top_head > *').each(function() {$(this).html($(this).attr('data-lg'))})
		}
	})





	// 
	$('.btn_lc_log').on('click', function() {

		phone = $(this).parent().siblings().children('.phone');
		form_sms = $(this).parent().siblings().children('.form_sms');
		num = '';
		$('.form_cn_code2 input').each(function() {
			num += $(this).attr('data-val')
		});
		
		if (phone.attr('data-sel') != 1 || num.length != 4) {
			phone.parent().addClass('form_pust')
			form_sms.html(form_sms.attr('data-code-pust'))
			form_sms.parent().removeClass('dsp_n')
		} else {
			$.ajax({
				url: "/user/get.php?ls_aut",
				type: "POST",
				dataType: "html",
				data: ({phone: phone.attr('data-val'), code: num}),
				success: function(data){
					if (data == 'yes') {
						location.reload();
					} else if (data == 'none') {
						form_sms.parent().removeClass('dsp_n')
						form_sms.html(form_sms.attr('data-code'))
					} else {console.log(data)}
				},
				beforeSend: function(){},
				error: function(data){console.log(data)}
			})
		}

	});


















	























}) // end jquery