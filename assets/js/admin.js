// start jquery
$(document).ready(function() {




	// menu sk
	$('.uitemc_umidl').on('click', function () {
		$('.uitemc_umid').toggleClass('menu_act')
	})

















	
	











   // cours add block
	$('.cours_add_pop').click(function(){
		$('.cours_add_block').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.cours_add_back').click(function(){
		$('.cours_add_block').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})

	// 
	$('.cours_img_add').click(function(){ $(this).siblings('.cours_img').click() })
	$(".cours_img").change(function(){
		tfile = $(this)
		if (window.FormData === undefined) mess('Бұл формат келмейді')
		else {
			var formData = new FormData();
			formData.append('file', $(this)[0].files[0]);
			$.ajax({
				type: "POST",
				url: "/user/item/get.php?add_item_photo",
				cache: false, contentType: false,
				processData: false, dataType: 'json',
				data: formData,
				success: function(msg){
					if (msg.error == '') {
						tfile_n = 'url(/assets/uploads/course/' + msg.file + ')'
						tfile.attr('data-val', msg.file)
						tfile.siblings('.cours_img_add').addClass('form_im_img2')
						tfile.siblings('.cours_img_add').css('background-image', tfile_n)
					} else mess(msg.error)
				},
				beforeSend: function(){ },
				error: function(msg){ console.log(msg) }
			});
		}
	});

	// 
	$('.price1_clc').click(function() { $('.price1_block').toggleClass('price1_block_act') });
	$('.number1_clc').click(function() { $('.number1_block').toggleClass('number1_block_act') });

	// 
	$('.btn_item_add').click(function () { 
		if ($('.cours_name').attr('data-sel') != 1) mess('Форманы толтырыңыз')
		else {
			$.ajax({
				url: "/user/item/get.php?item_add",
				type: "POST",
				dataType: "html",
				data: ({
					name: $('.cours_name').attr('data-val'), access: $('.cours_access').data('val'),
					autor: $('.cours_autor').data('val'), img: $('.cours_img').attr('data-val'),
					price: $('.cours_price').data('val'), price_sole: $('.cours_price_sole').data('val'),
					item: $('.cours_item').data('val'), assig: $('.cours_assig').data('val'),
				}),
				success: function(data){
					if (data == 'plus') location.reload();
					else console.log(data)
				},
				beforeSend: function(){ },
				error: function(data){ console.log(data) }
			})
		}
	})
	
	// cours add block
	$('.cours_edit_pop').click(function(){
		$('.cours_edit_block').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.cours_edit_back').click(function(){
		$('.cours_edit_block').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})
	$('.btn_cours_edit').click(function () { 
		$.ajax({
			url: "/user/item/get.php?item_edit",
			type: "POST",
			dataType: "html",
			data: ({
				id: $('.btn_cours_edit').data('cours-id'),
				name: $('.cours_name').data('val'), access: $('.cours_access').attr('data-val'),
				autor: $('.cours_autor').data('val'), img: $('.cours_img').data('val'),
				price: $('.cours_price').data('val'), price_sole: $('.cours_price_sole').data('val'),
				item: $('.cours_item').data('val'), assig: $('.cours_assig').data('val'),
			}),
			success: function(data){
				if (data == 'plus') location.reload();
				else console.log(data)
			},
			beforeSend: function(){ },
			error: function(data){ console.log(data) }
		})
	})


	// 
	$('.cours_copy').click(function () {
		$.ajax({
			url: "/user/item/get.php?cours_copy",
			type: "POST",
			dataType: "html",
			data: ({ id: $('.cours_copy').data('id'), }),
			beforeSend: function(){ },
			error: function(data){ console.log(data) },
			success: function(data){
				if (data == 'yes') location.url('/user/cours/');
				else console.log(data)
			},
		})
	})


	// 
	$('.cours_arh').click(function () {
		$.ajax({
			url: "/user/item/get.php?cours_arh",
			type: "POST",
			dataType: "html",
			data: ({ id: $('.cours_arh').data('id'), }),
			success: function(data){
				if (data == 'yes') location.reload();
				else console.log(data)
			},
			beforeSend: function(){ },
			error: function(data){ console.log(data) }
		})
	})
	// 
	$('.cours_del').click(function () {
		$.ajax({
			url: "/user/item/get.php?cours_del",
			type: "POST",
			dataType: "html",
			data: ({ id: $('.cours_del').data('id'), }),
			success: function(data){
				if (data == 'yes') $(location).attr('href', '/user/cours/');
				else console.log(data)
			},
			beforeSend: function(){ },
			error: function(data){ console.log(data) }
		})
	})




	// add_block_b
	$('.add_block_b').click(function(){
		$('.block_add').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.block_add_back').click(function(){
		$('.block_add').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})
	$('.btn_block_add').on('click', function(){
		if ($('.block_name').attr('data-sel') != 1) mess('Тақырыпты жазыңыз')
		else {
			$.ajax({
				url: "/user/item/get.php?block_add",
				type: "POST",
				dataType: "html",
				data: ({
					name: $('.block_name').attr('data-val'),
					cours_id: $('.btn_block_add').data('cours-id'),
					item: $('.block_item').data('val'), assig: $('.block_assig').data('val'),
				}),
				success: function(data){
					if (data == 'yes') location.reload();
					else console.log(data)
				},
				beforeSend: function(){},
				error: function(data){console.log(data)}
			})
		} 
	})


	// add_lesson_b
	$('.add_lesson_b').click(function(){
		$('.lesson_add').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
		if ($(this).attr('data-block-id')) $('.btn_lesson_add').attr('data-block-id', $(this).attr('data-block-id'))
	})
	$('.lesson_add_back').click(function(){
		$('.lesson_add').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
		$('.btn_lesson_add').attr('data-block-id', '')
	})
	$('.lesson1_clc').click(function() { $('.lesson1_block').toggleClass('lesson1_block_act') });

	$('.btn_lesson_add').on('click', function(){
		if ($('.lesson_name').attr('data-sel') != 1) mess('Тақырыпты жазыңыз')
		else {
			$.ajax({
				url: "/user/item/get.php?lesson_add",
				type: "POST",
				dataType: "html",
				data: ({
					name: $('.lesson_name').attr('data-val'),
					cours_id: $('.btn_lesson_add').data('cours-id'),
					block_id: $('.btn_lesson_add').data('block-id'),
					open: $('.lesson_open').attr('data-val'),
					youtube: $('.lesson_youtube').attr('data-val'),
					txt: $('.lesson_txt').val(),
					url: $('.lesson_url').attr('data-val'),
					mat: $('.lesson_mat').attr('data-val'),
				}),
				success: function(data){
					if (data == 'yes') location.reload();
					else console.log(data)
				},
				beforeSend: function(){},
				error: function(data){console.log(data)}
			})
		}
	})


	// 
	$('html').on('click', '.lesson_file_add', function(){ $(this).siblings('.lfile_add').click() })
	$('html').on('change', ".lfile_add", function(){
		tfile = $(this)
		if (window.FormData === undefined) mess('Бұл формат келмейді')
		else {
			var formData = new FormData();
			formData.append('file', $(this)[0].files[0]);
			$.ajax({
				type: "POST",
				url: "/user/item/get.php?add_file",
				cache: false, contentType: false,
				processData: false, dataType: 'json',
				data: formData,
				beforeSend: function(){ },
				error: function(msg){ console.log(msg) },
				success: function(msg){
					if (msg.error == '') {
						tfile_n = 'url(/assets/uploads/lesson/' + msg.file + ')'
						tfile.attr('data-val', msg.file)
						tfile.siblings('.lesson_file_add').addClass('form_im_img2')
						tfile.siblings('.lesson_file_add').css('background-image', tfile_n)
					} else mess(msg.error)
				},
			});
		}
	});














	// lesson iddd
	$('.btn_lesson_clc').on('click', function(){
		if ($('.block_name').attr('data-sel') != 1) mess('Тақырыпты жазыңыз')
		else {
			$.ajax({
				url: "/user/item/get.php?lesson_clc",
				type: "POST",
				dataType: "html",
				data: ({
					name: $('.block_name').attr('data-val'),
					cours_id: $('.btn_lesson_clc').data('cours-id'),
					item: $('.block_item').data('val'), assig: $('.block_assig').data('val'),
				}),
				success: function(data){
					if (data == 'yes') location.reload();
					else console.log(data)
				},
				beforeSend: function(){},
				error: function(data){console.log(data)}
			})
		} 
	})



	// clc_lesson_b
	$('.clc_lesson_b').click(function(){
		$('.lesson_edit').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');

		id = $(this).attr('data-id')
		// if ($(this).attr('data-block-id')) $('.btn_lesson_edit').attr('data-block-id', id)
		$('.lesson_clc_menu').attr('data-id', id)		
		$('.lesson_clc_viewm').attr('href', 'lesson.php?id=' + id)

		$.ajax({
			url: "/user/item/get_view.php?view",
			type: "POST",
			dataType: "html",
			data: ({ id: id, }),
			beforeSend: function(){ },
			error: function(data){ console.log(data) },
			success: function(data){
				if (data) $('.lesson_edit_99').html(data)
				else console.log(data)
			},
		})

	})


	$('.lesson_edit_back').click(function(){
		$('.lesson_edit').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
		$('.btn_lesson_edit').attr('data-block-id', '')
	})
	$('.lesson1_clc').click(function() { $('.lesson1_block').toggleClass('lesson1_block_act') });


	$('html').on('click', '.btn_lesson_edit', function(){
		id = $(this).attr('data-id')
		$.ajax({
			url: "/user/item/get.php?lesson_edit",
			type: "POST",
			dataType: "html",
			data: ({
				id: id,
				name: $('.lesson_edt_name').attr('data-val'),
				v1_txt1: $('.v1_txt1').val(),
				v1_home1: $('.v1_home1').val(),
				// open: $('.lesson_edt_open').attr('data-val'),
				youtube: $('.lesson_edt_youtube').attr('data-val'),
				youtube_id: $('.lesson_edt_youtube').attr('data-id'),
				txt: $('.lesson_edt_txt').val(),
				txt_id: $('.lesson_edt_txt').attr('data-id'),
				url: $('.lesson_edt_url').attr('data-val'),
				mat: $('.lesson_edt_mat').attr('data-val'),

				youtube_new: $('.lesson_ss_youtube').attr('data-val'),
				txt_new: $('.lesson_ss_txt').val(),
				url_new: $('.lesson_ss_url').attr('data-val'),
				mat_new: $('.lesson_ss_mats').attr('data-val'),
			}),
			beforeSend: function(){ },
			error: function(data){ console.log(data) },
			success: function(data){
				if (data == 'yes') location.reload();
				else console.log(data)
			},
		})
	})

	

	// 
	$('html').on('click', '.del_lesson_b', function(){
		id = $('.lesson_clc_menu').attr('data-id')
		$.ajax({
			url: "/user/item/get.php?lesson_del",
			type: "POST",
			dataType: "html",
			data: ({ id: id, }),
			beforeSend: function(){ },
			error: function(data){ console.log(data) },
			success: function(data){
				if (data == 'yes') location.reload();
				else console.log(data)
			},
		})
	})

	
















	// cours add block
	$('.company_edit_pop').click(function(){
		$('.company_edit_block').addClass('pop_bl_act');
		$('#html').addClass('ovr_h');
	})
	$('.company_edit_back').click(function(){
		$('.company_edit_block').removeClass('pop_bl_act');
		$('#html').removeClass('ovr_h');
	})
	$('.btn_company_edit').click(function () {
		if ($('.company_name').val().length <= 2) mess('Атыңызды толтырыңыз')
		else {
			$.ajax({
				url: "/user/admin/get.php?company_edit",
				type: "POST",
				dataType: "html",
				data: ({
					name: $('.company_name').attr('data-val'),
					phone: $('.company_phone').attr('data-val'), phone_alt: $('.company_phone').val(),
					whatsapp: $('.company_whatsapp').attr('data-val'), whatsapp_alt: $('.company_whatsapp').val(),
					instagram: $('.company_instagram').attr('data-val'), telegram: $('.company_telegram').attr('data-val'), youtube: $('.company_youtube').attr('data-val'), 
					metrika: $('.company_metrika').attr('data-val'), pixel: $('.company_pixel').attr('data-val'),
				}),
				success: function(data){
					if (data == 'yes') {
						mess('Cәтті сақталды!')
						$('.company_edit_block').removeClass('pop_bl_act');
						setTimeout(function() { location.reload(); }, 500);
					} else console.log(data);
				},
				beforeSend: function(){ },
				error: function(data){ console.log(data) }
			})
		}
	})
		
		
































}) // end jquery