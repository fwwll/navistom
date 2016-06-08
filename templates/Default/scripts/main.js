var Main = {
	options: {
		addressValue: 		null,
		addressNoLoad: 		false,
		addressHash:		null,
		contactPhones:		1,
		validationStatus: 	false,
		submitName:			null
	},
	templates: {
		resume_add_work: function(i) {
		return String() +
		'<div class="resume-add-work-parent" id="resume-add-work-' + i + '">'+
			'<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>'+
			'<div class="a-row">'+
				'<label><font class="a-red">*</font> Компания</label>'+
				'<input class="validate[required]" type="text" name="work[company_name][]" id="company_name_' + i + '" />'+
			'</div>'+
			'<div class="a-row">'+
				'<label><font class="a-red">*</font> Должность</label>'+
				'<input class="validate[required]" type="text" name="work[position][]" id="position_' + i + '" />'+
			'</div>'+
			'<div class="a-row">'+
				'<label><font class="a-red">*</font> Выполняемые обязаности</label>'+
				'<input class="validate[required]" type="text" name="work[activity][]" id="activity_' + i + '" />'+
			'</div>'+
			'<div class="a-row">'+
				'<label><font class="a-red">*</font> Период работы</label>'+
				'<input placeholder="Дата начала" type="text" class="month-picker month-picker-start validate[required]" name="work[date_start][]" id="work_date_' + i + '" />'+
				'<i class="a-icon-calendar"></i>'+
				'<input placeholder="Дата окончания" type="text" class="month-picker validate[required]" name="work[date_end][]" id="work_date_' + i + '" />'+
				'<i class="a-icon-calendar"></i>'+
			'</div>'+
			'<hr class="hr-min" />'+
		'</div>';
		},
		
		resume_add_experience: function(i) {
			return String() +
			'<div style="position:relative;" class="resume-add-experience-parent" id="resume-experience-' + i + '">'+
				'<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Уровень образования</label>'+
					'<select class="validate[required]" name="education[type][]" id="type_rid_' + i + '">'+
						'<option value=""> - выбрать - </option>'+
						'<option value="1">высшее</option>'+
						'<option value="2">неоконченное высшее</option>'+
						'<option value="3">среднее специальное</option>'+
						'<option value="4">среднее</option>	'+					
					'</select>'+
				'</div>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Учебное заведение</label>'+
					'<input class="validate[required]" type="text" name="education[institution][]" id="institution_' + i + '" />'+
				'</div>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Факультет, специальность</label>'+
					'<input class="validate[required]" type="text" name="education[faculty][]" id="faculty_' + i + '" />'+
				'</div>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Город</label>'+
					'<input class="validate[required]" type="text" name="education[location][]" id="location_' + i + '" />'+
				'</div>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Период обучения</label>'+
					'<input placeholder="Дата начала" type="text" class="month-picker month-picker-start validate[required]" name="education[date_start][]" id="education_date_start_' + i + '" />'+
					'<i class="a-icon-calendar"></i>'+
					'<input placeholder="Дата окончания" type="text" class="month-picker validate[required]" name="education[date_end][]" id="education_date_end_' + i + '" />'+
					'<i class="a-icon-calendar"></i>'+
				'</div>'+
				'<hr class="hr-min" />'+
			'</div>';
		},
		
		resume_add_traning: function(i) {
			return String() + 
			'<div style="position:relative;" class="resume-add-traning-parent" id="resuma-traning-' + i + '">'+
				'<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Название учебного заведения (курсов)</label>'+
					'<input class="validate[required]" type="text" name="traning[name][]" id="traning_name_' + i + '" />'+
				'</div>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Год, длительность</label>'+
					'<input class="validate[required]" type="text" name="traning[description][]" id="traning_year_' + i + '" />'+
				'</div>'+
				'<hr class="hr-min" />'+
			'</div>';
		},
		
		resume_add_lang: function(i) {
			return String() +
			'<div style="position:relative;" class="resume-add-lang-parent" id="resume-lang-' + i + '">'+
				'<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Язык</label>'+
					'<input class="validate[required]" type="text" name="langs[name][]" id="lang_name_' + i + '" />'+
				'</div>'+
				'<div class="a-row">'+
					'<label><font class="a-red">*</font> Уровень</label>'+
					'<select class="validate[required]" name="langs[level][]" id="lang_level_' + i + '">'+
						'<option value=""> - выбрать - </option>'+
						'<option value="1">Начинающий</option>'+
						'<option value="2">Средний</option>'+
						'<option value="3">Эксперт</option>'+					
					'</select>'+
				'</div>'+
				'<hr class="hr-min" />'+
			'</div>';
		},
		
		resume_add_button: function(text, id) {
			return String() +
			'<div class="a-row">'+
				'<a class="a-btn a-float-right" id="' + id + '" href="javascript:void(0)"><i class="a-icon-plus"></i>' + text + '</a>'+
			'</div>';
		},
		
		passw_recovery: function(){
			return String() + 
			'<h1 class="n-form-title">'+
				'<span>Восстановление пароля</span>'+
			'</h1>'+
			'<div id="ajax-response" class="a-mess-yellow display-none"></div>'+
			'<form id="n-recovery-form" name="n-aut-form" style="width:400px" class="n-aut-form" action="/index.ajax.php?route=/passw_recovery" method="post">'+
				'<div class="a-row">'+
					'<span><i class="a-icon-envelope"></i></span>'+
					'<input placeholder="Введите e-mail..." type="text" name="user_email" />'+
				'</div>'+
				'<div class="a-row a-row-bottom">'+
					'<input value="Восстановить" type="submit" class="a-btn-green" />'+
				'</div>'+
			'</form>';
		}
		
	},
	init: function() {
		$('.datepicker').datepicker();
		$('.month-picker').monthpicker();
		
		$('.a-toggle-down').aDropDownMenu();
		
		$('[maxlength]').inputlimiter({
			limit: $(this).attr('maxlength'),
			remText: '%n <i class="a-icon-pencil"></i>',
			limitText: ''
		});
		
		/*$('.mtip').mTip({
			align: 'top'
		}).live('click', function(){ return false; });*/
		
		$("input[type=radio], input[type=checkbox], input[type=file]").not(".uploadify").uniform();
		
		$('.editor').redactor({
			lang: 'ru',
			buttons: ['bold', 'italic', 'deleted', '|', 
				'alignleft', 'aligncenter', 'alignright', '|', 
				'unorderedlist', 'orderedlist', '|',
				'link', 'image', 'video']
		});
		
		$("#ad-gallery").adGallery({
			width: 600,
			height: 530,
			effect: 'fade',
			update_window_hash: false
		});
		
		$("#ad-gallery-700").adGallery({
			width: 700,
			height: 530,
			effect: 'fade',
			update_window_hash: false
		});
		
		$("#idTabs ul").idTabs(); 
		
		$('.qaptcha').QapTcha({
			PHPfile : '/index.ajax.php?route=is_user'
		});
		
		$('.a-row > textarea, .autosize').autoResize();
		
		$( ".datepicker-start" ).datepicker({
			dateFormat: 'yy-mm-dd',
			onClose: function( selectedDate ) {
				$( ".datepicker-end" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		$( ".datepicker-end" ).datepicker({
			dateFormat: 'yy-mm-dd',
			onClose: function( selectedDate ) {
				$( ".datepicker-start" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
		
		$("input[type=file]").each(function() {
			_filename = $(this).attr('title');
			
			if(_filename != undefined) {
				$(this).mTip({
					content: '<img src="/' + _filename + '" alt="/' + _filename + '" />',
					align: 'bottom'
				}).next().text(_filename);
			}
			
		});
		
		jQuery.ias({
			container 	: "#pagination-container",
			item		: ".pagination-block",
			pagination	: ".a-pagination li",
			next		: "li.next-posts a",
			trigger		: "Загрузить еще записи",
			loader		: '<img src="https://raw.github.com/webcreate/infinite-ajax-scroll/master/dist/images/loader.gif"/>',
			beforePageChange: function(scrollOffset, nextPageUrl) {
				console.log(nextPageUrl);
				Main.options.addressNoLoad = true;
			},
			onLoadItems: function(items) {
				Main.options.addressNoLoad = false;
				
			}
		});
		
		Main.uploader();
		
		$('.chart').knob({
			fgColor: '#F39130',
			bgColor : '#f0cb96',
			inputColor: '#F39130',
			readOnly: true
		});
		
		$(document).on('mouseover', '.user-name', function(event) {
			$(this).qtip({
				prerender: true,
				overwrite: false,
				content: {
					text: function(event, api) {
						_user_id = $(this).data('user_id');
						
						$.ajax({ url: '/get_user_info_ajax-'+ _user_id })
						.done(function(html) {
							api.set('content.text', html)
						})
						.fail(function(xhr, status, error) {
							api.set('content.text', status + ': ' + error)
						})
		
					return 'Загрузка...';
					}
				},
				position: {
					my: 'top left',
					at: 'bottom left',
					adjust: {
						y: 20
					}
				},
				hide: {
					fixed: true,
					delay: 600
				},
				style: 'qtip-wiki'
			}, event);
		});
		
		function select2_format(state){
			_descr = $(state.element).data('description');
			if(_descr) {
				return  state.text + '<div class="n-select-descr">' + _descr + '</div>';
			}
			else {
				return state.text
			}
		}
		
		$(".select-2").select2("destroy");
		$(".select-2").select2({
			formatResult: select2_format
		});
		
		$(".select-2-search").select2({
            placeholder: "Введите материал",
            minimumInputLength: 3,
            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                url: '/index.ajax.php?route=' + $('.select-2-search').data('link'),
                dataType: 'json',
				 data: function (term) {
                    return {
                        q: term
                    };
                },
                results: function (data) {
                    return {results: data};
                }
            },
			formatResult: function(state){
				return state.name;
			},
            formatSelection: function(state){
				return state.name;
			} 
        });
		
	},
	uploader: function() {
		
		_module = window.location.hash.split('/')[1];
		
		$(".uploader").sortable({
			items: ' > li.image-added'
		});
		
		$('.uploader li.image-added').each(function() {
			_id = $(this).find('img').attr('alt');
			_html = 
				'<div class="options">' +
					'<a class="add-description" title="Добавить описание" href="' + _id + '"><i class="a-icon-pencil a-icon-white"></i></a>' +
					'<a class="delete-image" title="Удалить" href="' + _id + '"><i class="a-icon-trash a-icon-white"></i></a>' +
				'</div>';
				
			$(this).append(_html);
		});
		
		$('.delete-image').die('click').live('click', function(){
			_image_id = $(this).attr('href');
			_parent = $(this).parent().parent();
			
			if(_image_id > 0) {
				$.ajax({
					type: "GET",
					url: '/index.ajax.php',
					data: {
						'route': _module + '/delete_image-' + _image_id
					},
					dataType:"json",
					cache: false,
					success: function(data) {
						if(data.success) {
							_parent.removeClass('image-added').find('img, input[type=hidden], .options').remove();
							_parent.find('.qq-uploader').show();
							_parent.find('i').attr('class', 'a-icon-plus a-icon-gray');
						}
					}
				});
			}
			
			return false;
		});

		$('.uploader li').not('.image-added').fineUploader({
		  request: {
			endpoint: '/index.ajax.php?route=' + _module + '/upload_image'
		  },
		  failedUploadTextDisplay: {
			maxChars: 40,
			responseProperty: 'error',
			enableTooltip: false
		  },
		  text: {
			uploadButton: '<i class="a-icon-plus a-icon-gray"></i>'
		  },
		  template: '<div class="qq-uploader">' +
					  '<pre class="qq-upload-drop-area span12"><span></span></pre>' +
					  '<div class="qq-upload-button btn btn-success"><i class="a-icon-plus a-icon-gray"></i></div>' +
					  '<span class="qq-drop-processing"><span></span><span class="qq-drop-processing-spinner"></span></span>' +
					  '<ul class="qq-upload-list" style="margin-top: 10px; text-align: center; display:none"></ul>' +
					'</div>'
		}).on('upload', function(event, id, fileName){
			
			$('#uploader > li').each(function(index, element) {
                if($(this).attr('class') != 'image-added') {
					$(this).find('i').attr('class', 'load');
					
					return false;
				}
            });
			
		}).on('progress', function(event, id, fileName, loaded, total){
			
			//_progress = Math.ceil((loaded * 100) / total);
			//$(this).find('i').removeClass('a-icon-plus a-icon-gray').text(_progress + '%');
			
		}).on('complete', function(event, id, fileName, responseJSON) {
			if (responseJSON.success) {
				
				$('#uploader > li').each(function(index, element) {
					if($(this).attr('class') != 'image-added') {
						$(this).find('.qq-uploader').hide();
						
						_html = '<input id="iamge_' + responseJSON.image_id + '" type="hidden" name="images[]" value="' + responseJSON.image_id + '" />' +
						'<img src="' + responseJSON.uploadName + '" alt="' + responseJSON.id_image + '">' +
						'<div class="options">' +
							'<a class="add-description" title="Добавить описание" href="' + responseJSON.image_id + '">'+
							'<i class="a-icon-pencil a-icon-white"></i></a>' +
							'<a class="delete-image" title="Удалить" href="' + responseJSON.image_id + '"><i class="a-icon-trash a-icon-white"></i></a>' +
						'</div>';
						$(this).append(_html).addClass('image-added');
						
						return false;
					}
				});
			}
		});
	},
	site: function() {
		
		$('.navi-search').submit(function(){
			_action = $(this).attr('action');
			_val	= $('#search-input').val();
			
			window.location = _action + '-' + _val;
			
			return false;
		});
		
		$('#passw-recovery').live('click', function(e){
			e.preventDefault();
			
			_parent = $(this).parent().parent().parent().parent();
			_parent.children().remove();
			
			_parent.append(Main.templates.passw_recovery());
		});
		
		/*$('.user-name').live('focus', function(e){
			e.preventDefault();
			
			console.log(1);
		});
		
		/*$('.editable').hover(function(){
			$(this).css('outline', '#333 1px dashed');
		}, function(){
			$(this).css('outline', 'none');
		});*/
		
		/* products new JS */
		$(".select-as-link").on('change', function(){
			_val = $(this).val();
			
			if(_val != 0) window.location = _val;
		});
	
			// add new product JS
	
			$("#categ_id").on('change', function(){
				_val = $(this).val();
				
				if(_val > 0) {
					$.ajax({
						type: "GET",
						url: '/index.ajax.php',
						data: {
							'route': 'product/get_sub_categs-' + _val
						},
						dataType:"json",
						cache: false,
						success: function(data) {
							_html = '';
							$.each(data, function(index, value) {
								_html += '<option value="' + index + '">' + value + '</option>';
							});
							
							$("#sub_categ_id").html(_html).select2('val', 'All');
						}
					});
				}
			});
			
			$("#producer_id").live('change', function(){
				_val = $(this).val();
				
				if(_val > 0) {
					$.ajax({
						type: "GET",
						url: '/index.ajax.php',
						data: {
							'route': 'product/get_products-' + _val
						},
						dataType:"json",
						cache: false,
						success: function(data) {
							_html = '';
							$.each(data, function(index, value) {
								_html += '<option value="' + index + '">' + value + '</option>';
							});
							
							$("#product_id").html(_html).select2('val', 'All');
						}
					});
				}
			});
			
		$('#new-producer-add').toggle(function(){
			_html = '<div class="a-row">' +
						'<label><font class="a-red">*</font> Название нового производителя</label>' +
						'<input class="validate[groupRequired[producer], ajax[ajaxProducerSearch]]" type="text" name="new_producer_name" id="new_producer_name" />' +
					'</div>';
			
			$(this).text('Отмена').parent().after(_html);
			
		}, function(){
			$(this).text('Не нашли нужного производителя?');
			$('#new_producer_name').parent().remove();
		});
		
		$('#new-product-add').toggle(function(){
			if($('#producer_id').val() > 0 || ($('#new_producer_name').val() != undefined && $('#new_producer_name').val() != '')) {
				
				if($('#producer_id').val()) {
					_ajaxValidation = ', ajax[ajaxProductSearch]'
				}
				else {
					_ajaxValidation = '';
				}
				
				_html = '<div class="a-row">' +
							'<label><font class="a-red">*</font> Название нового товара</label>' +
							'<input class="validate[groupRequired[product]' + _ajaxValidation + ']" type="text" name="new_product_name" id="new_product_name" />' +
						'</div>';
				
				$(this).text('Отмена').parent().after(_html);
			}
			else {
				alert('Сначала выберите производителя');
				$(this).click();
			}
			
			return false;
		}, function(){
			$(this).text('Не нашли нужный товар?');
			$('#new_product_name').parent().remove();
			
			return false;
		});
		
		/* end products new */
		
		/* articles */
		
		$('#n-comment-add').on('submit', function(){
			$(this).ajaxSubmit({
				resetForm: true,
				success: function(data){
					$('#comment-list').append(data).find('.n-comment').last().slideDown(200);
				}
			});
			
			return false;
		});
		
		$("#article-vote-add input[type=radio]").on('change', function(){
			$('#article-vote-add').ajaxSubmit({
				success: function(data){
					$('#article-vote-add').slideUp(200, function(){
						$('#article-votes-list').html(data).find('.n-interview-result').delay(100).slideDown(200);
					});
				}
			});
		});
		
		$(".n-add-form, .n-edit-form").validationEngine('attach',{
			promptPosition: "topRight",
			prettySelect: true, 
			usePrefix: 's2id_',
			autoPositionUpdate: true,
			onValidationComplete: function(form, status){
				Main.options.validationStatus = status;
				
				return status;
			}
		});
		
		$(".n-add-form").not('#registration-form').submit(function(){
			if(Main.options.validationStatus) {
				_submit = $(this).find('input[type=submit]');
				_submit.attr("disabled","disabled");
				
				$(this).ajaxSubmit({
					data: {
						submit: Main.options.submitName
					},
					success: function(data){
						_submit.removeAttr("disabled");
						
						data = jQuery.parseJSON(data);
						
						if(data.success) {
							$('.n-add-form').remove();
							
							_html = '<div style="width:700px;" class="a-mess-green">' + data.message + '</div>';
							$('.a-modal-content').append(_html);
							$('.a-modal-closer').height($('.a-modal').height());
						}
						else {
							$('#article-add-form').remove();
							
							_html = '<div style="width:700px;"  class="a-mess-red">' + data.message + '</div>';
							$('.a-modal-content').append(_html);
							$('.a-modal-closer').height($('.a-modal').height());
						}
					}
				});
			}
			
			return false;
		});
		
		$('.input-submit').on('click', function(){
			Main.options.submitName = $(this).attr('name');
			$(".n-add-form").submit();
			
			return false;
		});
		
		/* end articles */
		
		
		/* send user message */
		
		$('.n-dialog-no-view').click(function(){
			$(this).removeClass('n-dialog-no-view');
		});
		
		$('.send-user-mess').submit(function(){
			_textarea = $(this).find('textarea');
			
			if(_textarea.val() != '') {
				$(this).ajaxSubmit({
					success: function(data){
						
						data = $.parseJSON(data);
						
						if(data.success) {
							$('#n-dialog-mess-list').append(data.message).find('.n-dialog-full').last().slideDown(200);	
							_textarea.val('');
						}
					}
				});
			}
			
			return false;
		});
		
		/* end send user message*/
		
		
		/* search */
		
		$('.navi-search-btn').click(function(){
			$(this).parent().submit();
			
			return false;
		});
		
		/* end search*/
		
		$('#send-user-mess').on('submit', function(e) {
			e.preventDefault();
			
			_submit = $(this).find('input[type=submit]');
			_form 	= $(this);
			_parent	= _form.parent();
			
			$('.form-loader').fadeIn(200);
			_submit.attr('disabled', true);
			
			$(this).ajaxSubmit({
				resetForm: true,
				success: function(data){
					data = JSON.parse(data);
					
					if(data.success) {
						_form.remove();
						_parent.prepend('<div class="a-mess-green">' + data.message + '</div>');
					}
					else {
						_form.prepend('<div class="a-mess-red">' + data.message + '</div>');
					}
					
					$('.form-loader').fadeOut(200);
				}
			});
			
			return false;
		});
		
		/* login form */
		
		$('#n-aut-form').on('submit', function(){
			
			$('.form-loader').fadeIn(200);
			
			$(this).ajaxSubmit({
				resetForm: true,
				success: function(data){
					data = JSON.parse(data);
					
					if(data.success) {
						window.location = window.location.pathname;
					}
					else {
						$('#ajax-response').text(data.error).slideDown(200);
					}
					
					$('.form-loader').fadeOut(200);
				}
			});
			
			return false;
		});
		
		$('#n-recovery-form').die('submit').live('submit', function(){
			$(this).ajaxSubmit({
				resetForm: true,
				success: function(data){
					data = JSON.parse(data);
					
					if(data.success) {
						$('#n-recovery-form').html('<div class="a-mess-yellow">' + data.message + '</div>');
					}
					else {
						$('#ajax-response').text(data.message).slideDown(200);
					}
				}
			});
			
			return false;
		});
		
		/* end login */
		
		/* education JS */
		
		$('.a-modal-parent').scroll(function(){
			$('.datepicker-start, .datepicker-end').datepicker('hide');
			$('.limiterBox').hide();
			/*$('.month-picker').monthpicker('hide');*/
		});
		
		if($('#flag_agreed').attr('checked') == 'checked') {
			$('#start_date_range, #end_date_range').attr('disabled', 'disabled');
		}
		
		$('#flag_agreed').on('change', function(){
			if($(this).attr('checked') == 'checked') {
				$('#date_start, #date_end').val('').attr('disabled', 'disabled');
			}
			else {
				$('#date_start, #date_end').removeAttr('disabled');
			}
		});
		
		_lectors = 0;
		
		$('#add-lector').on('click', function(){
			_lectors ++;
			
			_html = '<div style="display:none;" class="lectors-added-list" id="lector-' + _lectors + '">' +
					'<a href="javascript:void(0)" class="delete-lector">Удалить лектора</a>' +
					'<div class="a-row">' +
            			'<label><font class="a-red">*</font> Ф.И.О. лектора</label>' +
            			'<input type="text" name="lector_name[' + _lectors + ']" id="lector_name_' + _lectors + '" />' +
        			'</div>' +
					'<div class="a-row">' +
						'<label><font class="a-red">*</font> Фото лектора</label>' +
						'<input type="file" name="lector_image[' + _lectors + ']" id="lector_image_' + _lectors + '" />' +
					'</div>' +
					'<div class="a-row">' +
						'<label><font class="a-red">*</font> О лекторе</label>' +
						'<textarea name="lector_description[' + _lectors + ']" id="lector_description_' + _lectors + '"></textarea>' +
					'</div>' +
					'<hr /></div>';
			
			$('.lectors-add').append(_html);
			$('#lector-' + _lectors).slideDown(200);
			
			Main.init();
			
			return false;
		});
		
		$('.delete-lector').live('click', function(){
			$(this).parent().slideUp(200, function(){
				$(this).remove();
			});
			
			if(_lectors > 0) {
				_lectors --;
			}
			
			return false;
		});
		
		/* end education */
		
		/* registration */
		
		$('#registration-form').die('click').on('submit', function(){
			if($('.n-add-form').validationEngine('validate')) {
				_submit = $(this).find('input[type=submit]');
				
				$('.form-loader').fadeIn(200);
				_submit.attr("disabled","disabled");
	
				$(this).ajaxSubmit({
					success: function(data){
						$('.form-loader').fadeOut(200);
						_submit.removeAttr("disabled");
						
						data = jQuery.parseJSON(data);
						
						if(data.success) {
							$('#registration-form').remove();
							
							_html = '<div class="a-mess-green">' + data.message + '</div>';
							$('.a-modal-content').append(_html);
							$('.a-modal-closer').height($('.a-modal').height());
						}
						else {
							$('#registration-form').remove();
							
							_html = '<div class="a-mess-red">' + data.message + '</div>';
							$('.a-modal-content').append(_html);
							$('.a-modal-closer').height($('.a-modal').height());
						}
					}
				});
			}
			
			return false;
		});
		
		$("#user_country").on('change', function(){
			_val = $(this).val();
			
			if(_val > 0) {
				$.ajax({
					type: "GET",
					url: '/index.ajax.php',
					data: {
						'route': 'get_regions-' + _val
					},
					dataType:"json",
					cache: false,
					success: function(data) {
						_html = '<option value="0">Выберите из списка</option>';
						$.each(data, function(index, value) {
							_html += '<option value="' + index + '">' + value + '</option>';
						});
						
						$("#user_region").html(_html).select2('val', 'All');
					}
				});
			}
		});
		
		$("#user_region, #region_id").on('change', function(){
			_val = $(this).val();
			
			if(_val > 0) {
				$.ajax({
					type: "GET",
					url: '/index.ajax.php',
					data: {
						'route': 'get_cities-' + _val
					},
					dataType:"json",
					cache: false,
					success: function(data) {
						_html = '';
						$.each(data, function(index, value) {
							_html += '<option value="' + index + '">' + value + '</option>';
						});

						if($("#city_id").index() > -1) {
							$("#city_id").html(_html).select2('val', 'All');
						}
						
						$("#user_city").html(_html).select2('val', 'All');
					}
				});
			}
		});
		
		/* end registration */
		
		/* resume add */
		
		
		$('#resume-add-work').on('click', function(e) {
			e.preventDefault();
			
			_i		= $('.resume-add-work-parent').length;
			_html 	= Main.templates.resume_add_work(_i);
			_btn	= Main.templates.resume_add_button('Добавить место работы', 'resume-add-work-next');
			
			$('#resume-work').find('.a-form-mess').hide();
			$('#resume-work').append(_html).after(_btn);
			
			$('.month-picker').monthpicker();
		});
		
		$('#resume-add-work-next').die('click').live('click', function(e){
			e.preventDefault();
			
			_i		= $('.resume-add-work-parent').length;
			_html 	= Main.templates.resume_add_work(_i);
			
			$('#resume-work').append(_html);
			
			$('.month-picker').monthpicker();
		});
		
		$('.delete-resume-added').die('click').live('click', function(e){
			e.preventDefault();
			
			_parent = $(this).parent().parent();
			
			$(this).parent().remove();
			
			if(_parent.children().not('.a-form-mess').length == 0) {
				_parent.next().remove();
				_parent.find('.a-form-mess').show();
			}
		});
		
		$('#resume-add-experience').on('click', function(e) {
			e.preventDefault();
			
			_i		= $('.resume-add-experience-parent').length;
			_html 	= Main.templates.resume_add_experience(_i);
			_btn	= Main.templates.resume_add_button('Добавить место учебы', 'resume-add-experience-next');
			
			$('#resume-experience').find('.a-form-mess').hide();
			$('#resume-experience').append(_html).after(_btn);
			
			$('.month-picker').monthpicker();
		});
		
		$('#resume-add-experience-next').die('click').live('click', function(e) {
			e.preventDefault();
			
			_i		= $('.resume-add-experience-parent').length;
			_html 	= Main.templates.resume_add_experience(_i);
			
			$('#resume-experience').append(_html);
			
			$('.month-picker').monthpicker();
		});
		
		$('#resume-add-traning').on('click', function(e) {
			e.preventDefault();
			
			_i		= $('.resume-add-traning-parent').length;
			_html 	= Main.templates.resume_add_traning(_i);
			_btn	= Main.templates.resume_add_button('Добавить курс или тренинг', 'resume-add-traning-next');
			
			$('#resume-traning').find('.a-form-mess').hide();
			$('#resume-traning').append(_html).after(_btn);
			
			$('.month-picker').monthpicker();
		});
		
		$('#resume-add-traning-next').die('click').live('click', function(e) {
			e.preventDefault();
			
			_i		= $('.resume-add-traning-parent').length;
			_html 	= Main.templates.resume_add_traning(_i);
			
			$('#resume-traning').append(_html);
			
			$('.month-picker').monthpicker();
		});
		
		$('#resume-add-lang').on('click', function(e) {
			e.preventDefault();
			
			_i		= $('.resume-add-lang-parent').length;
			_html 	= Main.templates.resume_add_lang(_i);
			_btn	= Main.templates.resume_add_button('Добавить язык', 'resume-add-lang-next');
			
			$('#resume-langs').find('.a-form-mess').hide();
			$('#resume-langs').append(_html).after(_btn);
			
			$('.month-picker').monthpicker();
		});
		
		$('#resume-add-lang-next').die('click').live('click', function(e) {
			e.preventDefault();
			
			_i		= $('.resume-add-lang-parent').length;
			_html 	= Main.templates.resume_add_lang(_i);
			
			$('#resume-langs').append(_html);
			
			$('.month-picker').monthpicker();
		});
		
		/* end resume */
		
		/* vacancy add*/
		
		$('#vacancy-company-info-edit').on('click', function(e){
			e.preventDefault();
			
			
		});
		
		/* end vacancy */
		
		$('.delete-link').on('click', function(e){
			e.preventDefault();
			_href = $(this).attr('href');
			
			$.confirm({
				'title'		: 'Удаление материала',
				'message'	: 'Вы действительно хотите удалить этот материал?',
				'buttons'	: {
					'Удалить'	: {
						'class'	: 'a-btn-red',
						'action': function(){
							window.location = _href;
						}
					},
					'Отмена'	: {
						'class'	: 'a-btn',
						'action': function(){
							
						}
					}
				}
			});
			
			//$(this).parent().parent().parent().parent().remove();
		});
	},
	address: function() {
		
		$('a.ajax-link').live('click', function(){
			Main.options.addressHash	= window.location.hash
			Main.options.addressValue 	= window.location;
			
			$.address.value($(this).attr('href'));
			
			return false;
		});
		
		//.crawlable(true)
		$.address.init(function(event) {
			
		}).change(function(event) {
			regExp = /page\/(\d+)/ig;
			if(!Main.options.addressNoLoad && event.path.match(regExp) == null) {
				if(event.path != '/') {
					$('body').prepend('<div class="a-modal-bg"></div><div id="ajax-loader">Загрузка...</div>');
					
					$.ajax({
						type: "GET",
						url: '/index.ajax.php',
						data: {
							'route': event.path
						},
						dataType:"html",
						cache: false,
						success: function(data) {
							$('.a-modal-bg, #ajax-loader').remove();
							$('body').prepend(data).css('overflow', 'hidden');
							
							Main.site();
							Main.init();
							
							var addthis_url = "http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f7d770f68a3c8a2";
							if (window.addthis) {
								window.addthis = null;
								window._adr = null;
								window._atc = null;
								window._atd = null;
								window._ate = null;
								window._atr = null;
								window._atw = null
							}
							$.getScript(addthis_url);
							
							//$.address.title();
							
							$('.a-modal-closer').height($('.a-modal').height());
						}
					});
				}
			}
		});
		
		$('.a-modal-closer, .a-modal-close').unbind('click').live('click', function(){
			$('.a-modal-bg').fadeOut(200, function(){
				$('.a-modal-bg').remove();
			});
			
			$('.a-modal-parent').fadeOut(100, function(){
				$('.a-modal-parent').remove();
				$('body').css('overflow', 'auto');
			});
			
			Main.options.addressNoLoad = true;
			
			if ( window.history && window.history.pushState ) { 
				window.history.pushState('', '', window.location.pathname) 
			} else { 
				window.location.href = window.location.href.replace(/#.*$/, '#') + Main.options.addressHash; 
			}
			
			if(Main.options.addressHash != null) {
				$.address.value(Main.options.addressHash.replace('#', ''));
			}
			
			Main.options.addressNoLoad = false;
			
			return false;
		});
	}
}

$(Main.address);

$(document).ready(function(){
	Main.init();
	Main.site();
	
	var $menu = $("#navi-filter");
 
	$(window).scroll(function(){
		if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
			$menu.removeClass("default").addClass("fixed");
		} else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
			$menu.removeClass("fixed").addClass("default");
		}
	});//scroll
	
	$.scrollUp({
		scrollName: 'scrollUp', // Element ID
		topDistance: '300', // Distance from top before showing element (px)
		topSpeed: 300, // Speed back to top (ms)
		animation: 'fade', // Fade, slide, none
		animationInSpeed: 200, // Animation in speed (ms)
		animationOutSpeed: 200, // Animation out speed (ms)
		scrollText: '<i class="a-icon-arrow-up a-icon-white"></i>', // Text for element
		activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	});
});