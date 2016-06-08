var Main = {
    options: {
        addressValue: null,
        addressNoLoad: false,
        addressHash: null,
        contactPhones: 1,
        validationStatus: false,
        submitName: null,
        is_open_modal: false
    },
    templates: {
        resume_add_work: function (i) {
            return String() +
            '<div class="resume-add-work-parent" id="resume-add-work-' + i + '">' +
            '<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Компания</label>' +
            '<input class="validate[required]" type="text" name="work[company_name][]" id="company_name_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Должность</label>' +
            '<input class="validate[required]" type="text" name="work[position][]" id="position_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Выполняемые обязаности</label>' +
            '<input class="validate[required]" type="text" name="work[activity][]" id="activity_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Период работы</label>' +
            '<input placeholder="Дата начала" type="text" class="month-picker month-picker-start validate[required]" name="work[date_start][]" id="work_date_' + i + '" />' +
            '<i class="a-icon-calendar"></i>' +
            '<input placeholder="Дата окончания" type="text" class="month-picker validate[required]" name="work[date_end][]" id="work_date_' + i + '" />' +
            '<i class="a-icon-calendar"></i>' +
            '</div>' +
            '<!--<div class="a-row">' +
            '<label>По сегодняшний день</label>' +
            '<input type="checkbox" class="is_working" name="work[flag_working][]" id="flag_working_' + i + '" value="1" />' +
            '</div>-->' +
            '<hr class="hr-min" />' +
            '</div>';
        },

        resume_add_experience: function (i) {
            return String() +
            '<div style="position:relative;" class="resume-add-experience-parent" id="resume-experience-' + i + '">' +
            '<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Уровень образования</label>' +
            '<select class="validate[required]" name="education[type][]" id="type_rid_' + i + '">' +
            '<option value=""> - выбрать - </option>' +
            '<option value="1">высшее</option>' +
            '<option value="2">неоконченное высшее</option>' +
            '<option value="3">среднее специальное</option>' +
            '<option value="4">среднее</option>	' +
            '</select>' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Учебное заведение</label>' +
            '<input class="validate[required]" type="text" name="education[institution][]" id="institution_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Факультет, специальность</label>' +
            '<input class="validate[required]" type="text" name="education[faculty][]" id="faculty_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Город</label>' +
            '<input class="validate[required]" type="text" name="education[location][]" id="location_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Период обучения</label>' +
            '<input placeholder="Дата начала" type="text" class="month-picker month-picker-start validate[required]" name="education[date_start][]" id="education_date_start_' + i + '" />' +
            '<i class="a-icon-calendar"></i>' +
            '<input placeholder="Дата окончания" type="text" class="month-picker validate[required]" name="education[date_end][]" id="education_date_end_' + i + '" />' +
            '<i class="a-icon-calendar"></i>' +
            '</div>' +
            '<hr class="hr-min" />' +
            '</div>';
        },

        resume_add_traning: function (i) {
            return String() +
            '<div style="position:relative;" class="resume-add-traning-parent" id="resuma-traning-' + i + '">' +
            '<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Название учебного заведения (курсов)</label>' +
            '<input class="validate[required]" type="text" name="traning[name][]" id="traning_name_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Год, длительность</label>' +
            '<input class="validate[required]" type="text" name="traning[description][]" id="traning_year_' + i + '" />' +
            '</div>' +
            '<hr class="hr-min" />' +
            '</div>';
        },

        resume_add_lang: function (i) {
            return String() +
            '<div style="position:relative;" class="resume-add-lang-parent" id="resume-lang-' + i + '">' +
            '<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Язык</label>' +
            '<input class="validate[required]" type="text" name="langs[name][]" id="lang_name_' + i + '" />' +
            '</div>' +
            '<div class="a-row">' +
            '<label><font class="a-red">*</font> Уровень</label>' +
            '<select class="validate[required]" name="langs[level][]" id="lang_level_' + i + '">' +
            '<option value=""> - выбрать - </option>' +
            '<option value="1">Начинающий</option>' +
            '<option value="2">Средний</option>' +
            '<option value="3">Эксперт</option>' +
            '</select>' +
            '</div>' +
            '<hr class="hr-min" />' +
            '</div>';
        },

        resume_add_button: function (text, id) {
            return String() +
            '<div class="a-row">' +
            '<a class="a-btn a-float-right" id="' + id + '" href="javascript:void(0)"><i class="a-icon-plus"></i>' + text + '</a>' +
            '</div>';
        },

        passw_recovery: function () {
            return String() +
            '<h1 class="n-form-title">' +
            '<span>Восстановление пароля</span>' +
            '</h1>' +
            '<div id="ajax-response" class="a-mess-red display-none" style="max-width: 400px"></div>' +
            '<form id="n-recovery-form" name="n-aut-form" style="width:400px" class="n-aut-form" action="/index.ajax.php?route=/passw_recovery" method="post">' +
            '<div class="a-row">' +
            '<span><i class="a-icon-envelope"></i></span>' +
            '<input placeholder="Введите e-mail..." type="text" name="user_email" />' +
            '</div>' +
            '<div class="a-row a-row-bottom">' +
            '<input value="Восстановить" type="submit" class="a-btn-green" />' +
            '</div>' +
            '</form>';
        },

        load_more_sections: function () {
            return String() +
            '<ul class="article-sections ads-sections scroll-sections">' +
            '<li>' +
            '<a title="Продам новое" class="article-sections-icons" href="/ua/products"></a>' +
            '</li>' +
            '<li>' +
            '<a title="Продам Б/У" class="article-sections-icons ads" href="/ua/ads"></a>' +
            '</li>' +
            '<li>' +
            '<a title="Акции" class="article-sections-icons stocks" href="/ua/products/filter-stocks"></a>' +
            '</li>' +
            '<li>' +
            '<a title="Сервис" class="article-sections-icons services" href="/ua/services"></a>' +
            '</li>' +
            '<li>' +
            '<a title="Спрос" class="article-sections-icons demand" href="/ua/demand"></a>' +
            '</li>' +
            '<li>' +
            '<a title="Мероприятия" class="article-sections-icons activity" href="/ua/activity"></a>' +
            '</li>' +
            '<li>' +

            '<a title="Работа" class="article-sections-icons work" href="/ua/work/resume"></a>' +
            '</li>' +
            '<li>' +
            '<a title="З/Т лаборатории" class="article-sections-icons labs" href="/ua/labs"></a>' +
            '</li>' +
            '<li>' +
            '<a title="Недвижимость" class="article-sections-icons realty" href="/ua/realty"></a>' +
            '</li>' +
            '<li>' +
            '<a title="Диагностика" class="article-sections-icons diagnostic" href="/ua/diagnostic"></a>' +
            '</li>' +
            '</ul>';
        }
    },
    init: function () {
        $('.datepicker').datepicker();
        $('.month-picker').monthpicker();

        $('[maxlength]').inputlimiter({
            limit: $(this).attr('maxlength'),
            remText: '%n <i class="a-icon-pencil"></i>',
            limitText: ''
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

        $('#producers-all-list').listnav();

        $('.qaptcha').QapTcha({
            PHPfile: '/index.ajax.php?route=is_user'
        });

        $(".datepicker-start").datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function (selectedDate) {
                $(".datepicker-end").datepicker("option", "minDate", selectedDate);
            }
        });

        $(".datepicker-end").datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function (selectedDate) {
                $(".datepicker-start").datepicker("option", "maxDate", selectedDate);
            }
        });

        $('.datetimepicker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: "HH:mm"
        });

        $("input[type=radio], input[type=checkbox], input[type=file]").not(".qq-uploader input[type=file], .checkbox > input").uniform();

        Main.uploader();

        $('.chart').knob({
            fgColor: '#F39130',
            bgColor: '#f0cb96',
            inputColor: '#F39130',
            readOnly: true
        });

        $('.chart-gray').trigger('configure', {
            fgColor: '#c2c2c2',
            bgColor: '#e4e4e4',
            inputColor: '#c2c2c2'
        });

        $('.tagsinput').remove();

        $('.phones-input').tagsInput({
            width: '65%',
            height: '200',
            defaultText: 'Добавить телефон',
            delimiter: ','
        });

        $('.tags-input').tagsInput({
            width: '65%',
            height: '200',
            defaultText: 'Добавить',
            delimiter: ';'
        });

        $(".idTabs ul, .ad-tabs").idTabs();

        $('.idTabs a').on('click', function () {
            Main.reloadBannerPosition();
        });

        $(document).on('mouseover', 'a[data-user_id]', function (event) {
            $(this).qtip({
                prerender: true,
                overwrite: false,
                content: {
                    text: function (event, api) {
                        _user_id = $(this).data('user_id');

                        $.ajax({url: '/get_user_info_ajax-' + _user_id})
                            .done(function (html) {
                                api.set('content.text', html)
                            })
                            .fail(function (xhr, status, error) {
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

        $('.editor').redactor({
            lang: 'ru',
            imageFloatMargin: '20px',
            linkAnchor: true,
            convertImageLinks: true,
            convertVideoLinks: true,
            buttons: ['bold', 'italic', 'deleted', '|',
                'alignleft', 'aligncenter', 'alignright', '|',
                'unorderedlist', 'orderedlist', '|',
                'link', 'image', 'video']
        });

        $('.admin-editor').redactor({
            lang: 'ru',
            imageFloatMargin: '20px',
            linkAnchor: true,
            buttons: ['bold', 'italic', 'deleted', '|', 'alignleft', 'aligncenter', 'alignright', '|',
                'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
                'image', 'video', 'file', 'table', 'link', '|',
                'fontcolor', 'backcolor', '|', 'horizontalrule', '|', 'html'],

            imageUpload: '/admin/upload/upload_editor_image',
            convertImageLinks: true,
            convertVideoLinks: true
        });

        $('.a-row > textarea, .autosize').not('.editor').autoResize();

        $(".select-2").select2("destroy");
        $(".select-2").select2();

        $("input[type=file]").each(function () {
            _filename = $(this).attr('title');

            if (_filename != undefined && _filename != '') {

                _ext = _filename.split('.').pop();

                if (_ext == 'jpg') {
                    _content = '<img src="/' + _filename + '" alt="/' + _filename + '" />';
                }
                else {
                    _content = '<a target="_blank" href="/uploads/docs/' + _filename + '">Просмотреть вложение</a>';
                }

                /*$(this).mTip({
                 content: _content,
                 align: 'bottom'
                 });*/

                $(this).next().text(_filename);
            }

        });
    },
    fineUploader: function (element) {
        element.fineUploader({
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
        }).on('upload', function (event, id, fileName) {

            $('#uploader > li').each(function (index, element) {
                if (!$(this).hasClass('image-added')) {
                    $(this).find('i').attr('class', 'load');

                    return false;
                }
            });

        }).on('complete', function (event, id, fileName, responseJSON) {
            if (responseJSON.success) {

                $('#uploader > li').each(function (index, element) {
                    if (!$(this).hasClass('image-added')) {
                        $(this).find('.qq-uploader').hide();

                        _html = '<input id="iamge_' + responseJSON.image_id + '" type="hidden" name="images[]" value="' + responseJSON.image_id + '" />' +
                        '<img src="' + responseJSON.uploadName + '" alt="' + responseJSON.id_image + '">' +
                        '<div class="options">' +
                        '<a class="add-description" title="Добавить описание" href="' + responseJSON.image_id + '">' +
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
    uploader: function () {

        $(".uploader").sortable({
            items: ' > li.image-added'
        });

        _module = window.location.href.replace('http://navistom.com', '').split('/');
        if (_module[1] == 'ua' || _module[1] == 'ru' || _module[1] == 'by') {
            _module = _module[2];
        }
        else {
            _module = _module[1];
        }

        _module = _module == undefined ? 'demand' : _module;

        $('.uploader li.image-added').each(function () {
            _id = $(this).find('img').attr('alt');
            _html =
                '<div class="options">' +
                '<a class="add-description" title="Добавить описание" href="' + _id + '"><i class="a-icon-pencil a-icon-white"></i></a>' +
                '<a class="delete-image" title="Удалить" href="' + _id + '"><i class="a-icon-trash a-icon-white"></i></a>' +
                '</div>';

            $(this).append(_html);
        });

        $('.delete-image').die('click').live('click', function () {
            _image_id = $(this).attr('href');
            _parent = $(this).parent().parent();

            if (_image_id > 0) {
                $.ajax({
                    type: "GET",
                    url: '/index.ajax.php',
                    data: {
                        'route': _module + '/delete_image-' + _image_id
                    },
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        if (data.success) {
                            _parent.removeClass('image-added').find('img, input[type=hidden], .options').remove();
                            if (_parent.find('.qq-uploader').length > 0) {
                                _parent.find('.qq-uploader').show();
                                _parent.find('i').attr('class', 'a-icon-plus a-icon-gray');
                            }
                            else {
                                Main.fineUploader(_parent);
                            }
                        }
                    }
                });
            }

            return false;
        });

        Main.fineUploader($('.uploader li').not('.image-added'));

    },
    sendPermissionRequest: function (type) {
        $.ajax({
            type: "GET",
            url: '/index.ajax.php',
            data: {
                'route': 'access-request',
                'type': type || 0,
                'url': window.location.href
            },
            dataType: "json",
            cache: false
        }).success(function (data) {
            $('.a-mess-orange').after('<div class="a-mess-green">' + data.message + '</div>').remove();
        });
    },

    toggleMenu: function () {
        var doc = $(document),
            menu = doc.find('#offers-menu'),
            link = doc.find('#offers-link');

        link.on('click', function (event) {
            event.preventDefault();

            menu.toggleClass('show');

            if (menu.hasClass('show')) {
                doc.on('click.menu', function (e) {
                    var target = $(e.target);

                    if (!target.hasClass('fixed-menu') && !target.parents('.fixed-menu').length && target[0].id != 'offers-link') {
                        menu.removeClass('show');
                        doc.unbind('click.menu');
                    }
                });
            }
        });
    },

    toggleExchange: function () {
        var checkbox = $('#exchange_default'),
            checkbox2 = $('#exchange_default_1'),
            inputs = $('.currency-input');

        inputs.attr('disabled', checkbox.prop('checked'));
        checkbox.on('change', function () {
            inputs.attr('disabled', true);
        });

        checkbox2.on('change', function () {
            inputs.attr('disabled', false);
        });

        return false;
    },

    site: function () {
        $('.navi-search, #global-search').submit(function () {
            _action = $(this).attr('action');
            _val = $('input[type=text]').val();

            if (_val.length > 1) {
                window.location = _action + '-' + _val;
            }

            return false;
        });

        $('#passw-recovery').live('click', function (e) {
            e.preventDefault();

            _parent = $(this).parent().parent().parent().parent();
            _parent.children().remove();

            _parent.append(Main.templates.passw_recovery());
        });

        /* products new JS */
        $(".select-as-link").on('change', function () {
            _val = $(this).val();

            if (_val != 0) window.location = _val;
        });

        // add new product JS

        $("#categ_id").on('change', function () {
            _val = $(this).val();

            if (_val > 0) {
                $.ajax({
                    type: "GET",
                    url: '/index.ajax.php',
                    data: {
                        'route': 'product/get_sub_categs-' + _val
                    },
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        _html = '';
                        $.each(data, function (index, value) {
                            _html += '<option value="' + index + '">' + value + '</option>';
                        });

                        $("#sub_categ_id").html(_html).select2('val', 'All');
                    }
                });
            }
        });

        $("#producer_id").live('change', function () {
            _val = $(this).val();

            if (_val > 0) {
                $.ajax({
                    type: "GET",
                    url: '/index.ajax.php',
                    data: {
                        'route': 'product/get_products-' + _val
                    },
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        _html = '';
                        $.each(data, function (index, value) {
                            _html += '<option value="' + index + '">' + value + '</option>';
                        });

                        $("#product_id").html(_html).select2('val', 'All');
                    }
                });
            }
        });

        $('#new-producer-add').toggle(function () {
            _html = '<div class="a-row">' +
            '<label><font class="a-red">*</font> Название нового производителя</label>' +
            '<input class="validate[groupRequired[producer], ajax[ajaxProducerSearch]]" type="text" name="new_producer_name" id="new_producer_name" />' +
            '</div>';

            $(this).text('Отмена').parent().after(_html);

        }, function () {
            $(this).text('Не нашли нужного производителя?');
            $('#new_producer_name').parent().remove();
        });

        $('#new-product-add').toggle(function () {
            if ($('#producer_id').val() > 0 || ($('#new_producer_name').val() != undefined && $('#new_producer_name').val() != '')) {

                if ($('#producer_id').val()) {
                    _ajaxValidation = ', ajax[ajaxProductSearch]'
                }
                else {
                    _ajaxValidation = '';
                }

                _html = '<div class="a-row">' +
                '<label><font class="a-red">*</font> Название нового товара</label>' +
                '<input class="validate[groupRequired[product]' + _ajaxValidation + ']" type="text" name="new_product_name" id="new_product_name" />' +
                '</div>' +
                '<div class="a-row">' +
                '<label><font class="a-red">*</font> Назначение товара' +
                '<span class="a-form-descr">Пример: "Установка стоматологическая", "Масса керамическая"</span>' +
                '</label>' +
                '<input type="text" class="validate[required]" name="new_product_description" id="new_product_description" />' +
                '</div>';

                $(this).text('Отмена').parent().after(_html);
            }
            else {
                alert('Сначала выберите производителя');
                $(this).click();
            }

            return false;
        }, function () {
            $(this).text('Не нашли нужный товар?');
            $('#new_product_name, #new_product_description').parent().remove();

            return false;
        });

        /* end products new */

        /* articles */

        $('#n-comment-add').on('submit', function () {
            $(this).ajaxSubmit({
                resetForm: true,
                success: function (data) {
                    $('#comment-list').append(data).find('.n-comment').last().slideDown(200);
                }
            });

            return false;
        });

        $("#article-vote-add input[type=radio]").on('change', function () {
            $('#article-vote-add').ajaxSubmit({
                success: function (data) {
                    $('#article-vote-add').slideUp(200, function () {
                        $('#article-votes-list').html(data).find('.n-interview-result').delay(100).slideDown(200);
                    });
                }
            });
        });

        $(".n-add-form, .n-edit-form").validationEngine('attach', {
            promptPosition: "topRight",
            prettySelect: true,
            usePrefix: 's2id_',
            autoPositionUpdate: true,
            onValidationComplete: function (form, status) {
                Main.options.validationStatus = status;

                return status;
            }
        });

        $('#registration-form').on('submit', function () {
            if (Main.options.validationStatus) {
                _submit = $(this).find('input[type=submit]');

                $('.form-loader').fadeIn(200);
                _submit.attr("disabled", "disabled");

                $(this).ajaxSubmit({
                    success: function (data) {
                        $('.form-loader').fadeOut(200);
                        _submit.removeAttr("disabled");

                        data = jQuery.parseJSON(data);

                        if (data.success) {
                            _parent = $('#registration-form').parent();
                            $('#registration-form').remove();

                            _html = '<div class="a-mess-green">' + data.message + '</div>';
                            _parent.append(_html);
                            $('.a-modal-closer').height($('.a-modal').height());
                        }
                        else {
                            $('#registration-form').remove();

                            _html = '<div class="a-mess-red">' + data.message + '</div>';
                            _parent.append(_html);
                            $('.a-modal-closer').height($('.a-modal').height());
                        }
                    }
                });
            }

            return false;
        });

        $(".n-add-form").not('#registration-form').on('submit', function () {
            if (Main.options.validationStatus) {
                _submit = $(this).find('[type=submit]');
                _submit.attr("disabled", "disabled");

                $(this).ajaxSubmit({
                    data: {
                        submit: Main.options.submitName,
                        vipStatus: Main.options.vipStatus
                    },
                    success: function (data) {

                        /* _submit.removeAttr("disabled");

                         data = jQuery.parseJSON(data);

                         if(data.success) {
                         _parent = $('.n-add-form').parent();
                         $('.n-add-form').remove();

                         _html = '<div style="width:100%;" class="a-mess-green">' + data.message + '</div>';

                         if($('.a-modal-content').length) {
                         $('.a-modal-content').append(_html);
                         $('.a-modal-closer').height($('.a-modal').height());
                         }
                         else {
                         _parent.append(_html);
                         }
                         }
                         else {
                         $('#article-add-form').remove();

                         _html = '<div style="width:700px;"  class="a-mess-red">' + data.message + '</div>';
                         $('.a-modal-content').append(_html);
                         $('.a-modal-closer').height($('.a-modal').height());
                         }
                         */
                        //--------------------------------------------------------
                        _submit.removeAttr("disabled");

                        t = jQuery.parseJSON(t);

                        try {
                            send_liqpay(t.send_data.data, t.send_data.signature, t.portmone);
                        } catch (e) {

                            t.success ? (_parent = $(".n-add-form").parent(),
                                $(".n-add-form").remove(),
                                _html = '<div style="width:100%;" class="a-mess-green">' + t.message + "</div>",
                                $(".a-modal-content").length ? ($(".a-modal-content").append(_html),
                                    $(".a-modal-closer").height($(".a-modal").height())) : _parent.append(_html)) : ($("#article-add-form").remove(),
                                _html = '<div style="width:700px;"  class="a-mess-red">' + t.message + "</div>",
                                $(".a-modal-content").append(_html),
                                $(".a-modal-closer").height($(".a-modal").height()))
                            $('#product-add-form input[type="text"]').val('');

                        }
                        //---------------------------------------------------

                    }
                });
            }

            return false;
        });

        $('.input-submit').on('click', function () {
            Main.options.submitName = $(this).attr('name');
            Main.options.vipStatus = $(this).val();
            $(".n-add-form").submit();

            return false;
        });

        /* end articles */


        /* send user message */
        $('.n-dialog-no-view').click(function () {
            $(this).removeClass('n-dialog-no-view');
        });

        $('.send-user-mess').submit(function () {
            _textarea = $(this).find('textarea');

            if (_textarea.val() != '') {
                $(this).ajaxSubmit({
                    success: function (data) {

                        data = $.parseJSON(data);

                        if (data.success) {
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

        $('.navi-search-btn').click(function () {
            $(this).parent().submit();

            return false;
        });

        /* end search*/

        $('#send-user-mess').on('submit', function (e) {
            e.preventDefault();

            _submit = $(this).find('input[type=submit]');
            _form = $(this);
            _parent = _form.parent();

            $('.form-loader').fadeIn(200);
            _submit.attr('disabled', true);

            $(this).ajaxSubmit({
                resetForm: true,
                success: function (data) {
                    data = JSON.parse(data);

                    if (data.success) {
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

        $(document).on('change', '.is_working', function (e) {

        });

        /* login form */

        $('#n-aut-form').on('submit', function () {
            $('.form-loader').fadeIn(200);

            $(this).ajaxSubmit({
                resetForm: true,
                success: function (data) {
                    data = JSON.parse(data);

                    if (data.success) {
                        window.location = Main.options.addresPrevValue || '/cabinet';
                    }
                    else {
                        $('#ajax-response').html(data.error).slideDown(200);
                    }

                    $('.form-loader').fadeOut(200);
                }
            });

            return false;
        });

        $('#n-recovery-form').die('submit').live('submit', function () {
            $(this).ajaxSubmit({
                resetForm: true,
                success: function (data) {
                    data = JSON.parse(data);

                    if (data.success) {
                        $('#n-recovery-form').html('<div class="a-mess-yellow">' + data.message + '</div>');
                    }
                    else {
                        $('#ajax-response').html(data.message).slideDown(200);
                    }
                }
            });

            return false;
        });

        /* end login */

        /* education JS */

        $('.a-modal-parent').scroll(function () {
            $('.datepicker-start, .datepicker-end').datepicker('hide');
            $('.limiterBox').hide();
            if ($('.month-picker').length > 0) {
                $('.month-picker').monthpicker('hide');
            }
        });

        if ($('#flag_agreed').attr('checked') == 'checked') {
            $('#start_date_range, #end_date_range').attr('disabled', 'disabled');
        }

        $('#flag_agreed').on('change', function () {
            if ($(this).attr('checked') == 'checked') {
                $('#date_start, #date_end').val('').attr('disabled', 'disabled');
            }
            else {
                $('#date_start, #date_end').removeAttr('disabled');
            }
        });

        _lectors = $('.lectors-add > div').size() - 1;

        $('#add-lector').on('click', function () {
            _lectors++;

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

            $("#lector-" + _lectors + " input[type=file]").uniform();
            $("#lector-" + _lectors + " .a-row > textarea").autoResize();

            return false;
        });

        $('.delete-lector').live('click', function () {
            $(this).parent().slideUp(200, function () {
                $(this).remove();
            });

            return false;
        });

        /* end education */

        $("#user_region, #region_id").on('change', function () {
            _val = $(this).val();

            if (_val > 0) {
                $.ajax({
                    type: "GET",
                    url: '/index.ajax.php',
                    data: {
                        'route': 'get_cities-' + _val
                    },
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        _html = '';
                        $.each(data, function (index, value) {
                            _html += '<option value="' + index + '">' + value + '</option>';
                        });

                        if ($("#city_id").index() > -1) {
                            $("#city_id").html(_html).select2('val', 'All');
                        }

                        $("#user_city").html(_html).select2('val', 'All');
                    }
                });
            }
        });

        /* end registration */

        /* resume add */


        $('#resume-add-work').on('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-work-parent').length;
            _html = Main.templates.resume_add_work(_i);
            _btn = Main.templates.resume_add_button('Добавить место работы', 'resume-add-work-next');

            $('#resume-work').find('.a-form-mess').hide();
            $('#resume-work').append(_html).after(_btn);

            $('.month-picker').monthpicker();
        });

        $('#resume-add-work-next').die('click').live('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-work-parent').length;
            _html = Main.templates.resume_add_work(_i);

            $('#resume-work').append(_html);

            $('.month-picker').monthpicker();
        });

        $('.delete-resume-added').die('click').live('click', function (e) {
            e.preventDefault();

            _parent = $(this).parent().parent();

            $(this).parent().remove();

            if (_parent.children().not('.a-form-mess').length == 0) {
                _parent.next().remove();
                _parent.find('.a-form-mess').show();
            }
        });

        $('#resume-add-experience').on('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-experience-parent').length;
            _html = Main.templates.resume_add_experience(_i);
            _btn = Main.templates.resume_add_button('Добавить место учебы', 'resume-add-experience-next');

            $('#resume-experience').find('.a-form-mess').hide();
            $('#resume-experience').append(_html).after(_btn);

            $('.month-picker').monthpicker();
        });

        $('#resume-add-experience-next').die('click').live('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-experience-parent').length;
            _html = Main.templates.resume_add_experience(_i);

            $('#resume-experience').append(_html);

            $('.month-picker').monthpicker();
        });

        $('#resume-add-traning').on('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-traning-parent').length;
            _html = Main.templates.resume_add_traning(_i);
            _btn = Main.templates.resume_add_button('Добавить курс или тренинг', 'resume-add-traning-next');

            $('#resume-traning').find('.a-form-mess').hide();
            $('#resume-traning').append(_html).after(_btn);

            $('.month-picker').monthpicker();
        });

        $('#resume-add-traning-next').die('click').live('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-traning-parent').length;
            _html = Main.templates.resume_add_traning(_i);

            $('#resume-traning').append(_html);

            $('.month-picker').monthpicker();
        });


        $('#resume-add-lang').on('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-lang-parent').length;
            _html = Main.templates.resume_add_lang(_i);
            _btn = Main.templates.resume_add_button('Добавить язык', 'resume-add-lang-next');

            $('#resume-langs').find('.a-form-mess').hide();
            $('#resume-langs').append(_html).after(_btn);

            $('.month-picker').monthpicker();
        });

        $('#resume-add-lang-next').die('click').live('click', function (e) {
            e.preventDefault();

            _i = $('.resume-add-lang-parent').length;
            _html = Main.templates.resume_add_lang(_i);

            $('#resume-langs').append(_html);

            $('.month-picker').monthpicker();
        });

        /* end resume */

        /* vacancy add*/

        $('#vacancy-company-info-edit').on('click', function (e) {
            e.preventDefault();


        });


        if ($('#activity_categ_id').index() >= 0) {
            _val = $('#activity_categ_id').val();

            if ($.inArray("19", _val) == 0) {
                elem = $('.lectors-add');

                elem.prev().hide();
                elem.next().hide();
                elem.hide();
            }
        }


        /* end vacancy */
    },
    reloadBannerPosition: function () {
        var banner = $('#fixed-banner');
        if (banner.length) {
            Main.topBannerPosition = banner.position().top - parseFloat(banner.css('marginTop').replace(/auto/, 0)) - 30;
        }
    },
    address: function () {
        $('a.ajax-link').live('click', function () {

            var origin = window.location.origin || window.location.protocol + "//" + window.location.hostname;

            if (window.history && window.history.pushState) {
                Main.options.addressHash = window.location.hash;
                Main.options.addressValue = window.location;
                Main.options.addresPrevValue = window.location.href.replace(origin, '');
                Main.options.addressElem = $(this);

                $.address.value($(this).attr('href'));

                return false;
            }
        });

        $('#left').on('click', '.a-pagination a', function (e) {
            if (window.history && window.history.pushState) {
                e.preventDefault();
                $.address.state('');
                $.address.value($(this).attr('href'));
            }
        });

        //$.address.state(window.location.href);
        $.address.init(function (event) {

        }).change(function (event) {
            if (event.path == '/' || event.path == '/ua' || event.path == '/ua/' || !window.history || !window.history.pushState) return false;

            if (!Main.options.addressNoLoad && event.value.indexOf('/page-') !== -1) {
                $.ajax({
                    method: "GET",
                    dataType: "html",
                    url: event.value
                }).success(function (data) {
                    //Main.options.addressNoLoad = false;
                    var html = $(data);
                    $('#pagination-container').html(html.find('#pagination-container').html());
                    $('.a-pagination').html(html.find('.a-pagination').html());
                    $(document).scrollTop(0);
                    $.address.state(window.location.href);
                }).error(function () {
                    $('.a-modal-bg, #ajax-loader').remove();
                });

                return false;
            }

            regExp = /page\/(\d+)/ig;
            if (!Main.options.addressNoLoad && event.path.match(regExp) == null && event.path != '/404') {
                $.address.state('');
                if (event.path != '/') {
                    $('body').prepend('<div class="a-modal-bg"></div><div id="ajax-loader">Загрузка...</div>');

                    $.ajax({
                        type: "GET",
                        url: '/index.ajax.php',
                        data: {
                            'route': event.path,
                            'query': event.parameters
                        },
                        dataType: "html",
                        cache: false,
                        success: function (data) {
                            $('.a-modal-bg, #ajax-loader').remove();
                            $('body').prepend(data);
                            $('body').css('overflow', 'hidden');

                            Main.init();
                            Main.site();

                            if ($('#global-search-input').val() && $('#global-search-input').val() != '') {
                                $(".a-modal-content").highlight($('#global-search-input').val().split(' '), {
                                    element: 'span',
                                    className: 'high'
                                });
                            }

                            if (window.addthis) {
                                window.addthis = null;
                                window._adr = null;
                                window._atc = null;
                                window._atd = null;
                                window._ate = null;
                                window._atr = null;
                                window._atw = null
                            }

                            if (!('ontouchstart' in window)) {
                                $('.a-modal-closer').height($('.a-modal').height());
                            }
                        },
                        error: function () {
                            $('.a-modal-bg, #ajax-loader').remove();
                        }
                    });
                }
            }
        });

        $('.a-modal-closer, .a-modal-close').unbind('click').live('click', function () {
            $('.a-modal-bg').last().fadeOut(200, function () {
                $('.a-modal-bg').last().remove();
            });

            $('.a-modal-parent').last().fadeOut(100, function () {
                $('.a-modal-parent').last().remove();
                $('body').css('overflow', 'scroll');
            });

            Main.options.addressNoLoad = true;

            if (!Main.options.is_open_modal) {
                if (window.history && window.history.pushState) {
                    window.history.pushState('', '', window.location.pathname)
                } else {
                    window.location.href = window.location.href.replace(/#.*$/, '#') + Main.options.addressHash;
                }

                if (Main.options.addressHash != "") {
                    $.address.value(Main.options.addressHash.replace('#', ''));
                }
                else {
                    //$.address.value('');
                    $.address.value(Main.options.addresPrevValue);
                }
            }

            Main.options.addressNoLoad = false;
            Main.options.is_open_modal = false;

            return false;
        });
    }
}

$(Main.address);

$(document).ready(function () {
    Main.init();
    Main.site();

    $(document).on('keyup', document, function (e) {
        if (e.keyCode == 27) {
            $('.a-modal-closer').click();
        }
    });

    if (window.location.hash == '#404') {
        $('#left').prepend('<div id="mess-404">Предложение удалено</div>');
    }

    var $menu = $("#navi-filter");

    $('body').on('click', '#filter-right > a', function (e) {
        e.preventDefault();
        _parent = $(this);
        _elem = $(this).next();

        _elem.slideToggle(300, function () {
            Main.reloadBannerPosition();
        });

        _parent.toggleClass('active');
    });

    $('#search a').on('click', function (e) {
        e.preventDefault();
        $('#search').submit();
    });

    $('#search').on('submit', function (e) {
        e.preventDefault();

        _action = $(this).attr('action');
        _val = $(this).find('input').val();

        if (_val.length > 3) {
            window.location = _action + '-' + _val;
        }
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100 && $menu.hasClass("default")) {
            $menu.removeClass("default").addClass("fixed");
        } else if ($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
            $menu.removeClass("fixed").addClass("default");
        }
    });//scroll

    $("#main-scroller").scrollable({
        items: '#main-scroller .scroller-items',
        speed: 200
    });

    var _lang = 'ua';
    var _controller = window.location.pathname.split('/')[1];

    $(document).on('click', '#send-error-mess-link', function (e) {
        e.preventDefault();

        if (!Main.options.is_open_modal) {
            _url = window.location.href;

            Main.options.is_open_modal = true;

            $.ajax({
                type: "GET",
                url: '/index.ajax.php',
                data: {
                    'route': 'send_user_error',
                    'url': _url
                },
                dataType: "html",
                cache: false,
                success: function (data) {
                    $('body').append(data).css('overflow', 'hidden');
                    $('.a-modal-bg').last().css('z-index', 999);

                    if ($('h1').index() != -1) {
                        _title = $('h1').text();
                    }
                    else {
                        _title = $('title').text();
                    }

                    $('#page-title').text(_title);

                    Main.init();
                    Main.site();

                    $('.a-modal-closer').height($('.a-modal').height());
                }
            });
        }
    });

    $(document).on('click', '#send-my-resume', function (e) {
        e.preventDefault();

        if (!Main.options.is_open_modal) {
            Main.options.is_open_modal = true;

            $.ajax({
                type: "GET",
                url: '/index.ajax.php',
                data: {
                    'route': $(this).attr('href')
                },
                dataType: "html",
                cache: false,
                success: function (data) {
                    $('body').append(data).css('overflow', 'hidden');
                    $('.a-modal-bg').last().css('z-index', 999);

                    if ($('h1').index() != -1) {
                        _title = $('h1').text();
                    }
                    else {
                        _title = $('title').text();
                    }

                    Main.init();
                    Main.site();

                    $('.a-modal-closer').height($('.a-modal').height());
                }
            });
        }
    });

    $(document).on('click', '#add-atach', function (e) {
        e.preventDefault();

        $('#attach-input').click();
    });

    $(document).on('change', '#attach-input', function (e) {
        _text = $(this).val().split('\\').pop();

        if (_text != '') {
            $('#add-atach').text(_text);
        }
    });

    $(document).on('click', '#selection-categs a', function (e) {
        e.preventDefault();
        var _id = $(this).attr('href');

        $(this).parent().parent().find('a').removeClass('active');
        $(this).addClass('active');

        if (_id > 0) {
            if (_controller == 'ads') {
                var _route = _controller + '/get_sub_categs-' + _id + '-flag_min-1' + '-flag_ads-1'
            }
            else {
                var _route = _controller + '/get_sub_categs-' + _id + '-flag_min-1'
            }

            $.ajax({
                type: "GET",
                url: '/index.ajax.php',
                data: {
                    'route': _route
                },
                dataType: "json",
                cache: false,
                success: function (data) {
                    _html = '';
                    $.each(data, function (index, value) {
                        _html += '<li><a href="' + index + '">' + value + '</a></li>';
                    });

                    $("#selection-sub-categs").html(_html);
                }
            });
        }
    });

    $(document).on('click', '#selection-sub-categs a', function (e) {
        e.preventDefault();

        _id = $(this).attr('href');

        $(this).parent().parent().find('a').removeClass('active');
        $(this).addClass('active');

        if (_id > 0) {
            $.ajax({
                type: "GET",
                url: '/index.ajax.php',
                data: {
                    'route': _controller + '/get_producers-' + _id
                },
                dataType: "json",
                cache: false,
                success: function (data) {
                    _html = '';

                    if (data.length != 0) {
                        $.each(data, function (index, value) {
                            _html += '<li><a href="' + value.producer_id + '">' + value.name + '</a></li>';
                        });

                        $("#selection-producers").html(_html);
                    }
                    else {
                        $("#selection-producers").html('<li>Ничего не найдено</li>');
                    }
                }
            });
        }
    });

    $(document).on('click', '#selection-producers a', function (e) {
        e.preventDefault();

        _id = $(this).attr('href');
        _sub_categ = $('#selection-sub-categs a.active').attr('href');

        window.location.href = '/' + _lang + '/' + _controller + '/sub_categ-' + _sub_categ + '/firm-' + _id + '-all';
    });

    $(document).on('click', '#new-tag-add', function (e) {
        e.preventDefault();

        _state = $(this).data('state');

        switch (_state) {
            case 1:
            case undefined:
                $(this).text('Отмена').parent().parent().after(
                    '<div class="a-row">' +
                    '<label>Название меток через запятую</label>' +
                    '<input type="text" name="new_tags" id="new_tags">' +
                    '</div>'
                );

                $('#new_tags').tagsInput({
                    width: '65%',
                    height: '200',
                    defaultText: 'Добавить метку',
                    delimiter: ','
                });

                $(this).data('state', 2);
                break;
            case 2:
                $(this).text('Добавить новые метки').parent().parent().next().remove();

                $(this).data('state', 1);
                break;
        }
    });

    $(document).on('change', '#activity_categ_id', function () {
        if ($.inArray("19", $(this).val()) == 0) {
            elem = $('.lectors-add');

            elem.prev().hide();
            elem.next().hide();
            elem.hide();
        }
        else {
            elem = $('.lectors-add');

            elem.prev().show();
            elem.next().show();
            elem.show();
        }
    });

    $(document).on('hover', 'div[title]', function (event) {
        _text = $(this).attr('title');

        if (_text != '') {
            $(this).qtip({
                prerender: true,
                overwrite: false,
                content: {
                    text: _text
                },
                position: {
                    my: 'top right',
                    at: 'bottom right',
                    adjust: {
                        y: 20
                    }
                },
                hide: {
                    fixed: true,
                    delay: 600
                },
                style: 'qtip'
            }, event);
        }
    });

    $(document).on('click', '#mess-country-edit .a-close', function (e) {
        e.preventDefault();
    });

    if ($('#pagination-container-main').length) {
        jQuery.ias({
            container: "#pagination-container-main",
            item: ".pagination-block",
            pagination: ".a-pagination li",
            next: "li.next-posts a",
            trigger: "Загрузить еще записи ",
            loader: '<div id="page-loader"></div>',
            beforePageChange: function (scrollOffset, nextPageUrl) {
                Main.options.addressNoLoad = true;
            },
            onLoadItems: function (items) {
                Main.options.addressNoLoad = false;

                $.ajax({
                    type: "GET",
                    url: '/index.ajax.php',
                    data: {
                        'route': 'get-banner-ajax'
                    },
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        var banner = $('#fixed-banner');

                        banner.find('img').attr('src', '/uploads/banners/' + data.image);
                        banner.find('a').attr({
                            'href': data.link,
                            'target': data.target
                        });

                        if ($('#global-search-input').val() != '') {
                            $(".item").highlight($('#global-search-input').val().split(' '), {
                                element: 'span',
                                className: 'high'
                            });
                        }
                    }
                });
            }
        });
    }

    Main.reloadBannerPosition();

    $(window).scroll(function (event) {
        var y = $(this).scrollTop();

        if (y >= Main.topBannerPosition) {
            $('#demand-add-btn').show();
            $('#fixed-banner').addClass('fixed');
        } else {
            $('#demand-add-btn').hide();
            $('#fixed-banner').removeClass('fixed');
        }
    });

    $(document).on('click', '.no-moder-view .options a.ajax-link', function () {
        $(this).parent().parent().parent().removeClass('no-moder-view');
    });

    $(document).on('click', '#remove-link', function (e) {
        e.preventDefault();

        _route = $(this).attr('href');

        _form = $('.n-add-form');
        _parent = _form.parent();

        if (_route != '') {
            $.ajax({
                type: "GET",
                url: '/index.ajax.php',
                data: {
                    'route': _route
                },
                dataType: "json",
                cache: false,
                success: function (data) {
                    if (data.success) {
                        _parent.prepend('<div class="a-mess-green">' + data.message + '</div>');
                        _form.remove();
                    }
                    else {
                        _form.prepend('<div class="a-mess-red">' + data.message + '</div>');
                    }
                }
            });
        }
    });

    $(document).on('change', '#mess_tpls', function () {
        mess_id = $(this).val();

        if (mess_id > 0) {
            $.ajax({
                type: "GET",
                url: '/index.ajax.php',
                data: {
                    'route': 'get-mess-tpl-' + mess_id
                },
                dataType: "json",
                cache: false,
                success: function (data) {
                    $('#send-user-mess textarea').val(data.message).autoResize();
                }
            });
        }
    });

    $(document).on('click', '.delete-link', function (e) {
        e.preventDefault();
        _href = $(this).attr('href');

        _title = $(this).parent().parent().parent().find('a.ajax-link:eq(0)').text();

        $.confirm({
            'title': 'Удаление материала',
            'message': _title + '<br /><br /> Вы действительно хотите удалить этот материал?',
            'buttons': {
                'Удалить': {
                    'class': 'a-btn-red',
                    'action': function () {
                        window.location = _href;
                    }
                },
                'Отмена': {
                    'class': 'a-btn',
                    'action': function () {

                    }
                }
            }
        });
    });

    if (window.location.href.split('?')[1] == 'print') {
        $('body').addClass('print');
        window.print();
    }

    $.scrollUp({
        scrollName: 'scrollUp', // Element ID
        topDistance: '300', // Distance from top before showing element (px)
        topSpeed: 300, // Speed back to top (ms)
        animation: 'fade', // Fade, slide, none
        animationInSpeed: 200, // Animation in speed (ms)
        animationOutSpeed: 200, // Animation out speed (ms)
        scrollText: '<i class="a-icon-arrow-up a-icon-white"></i>', // Text for element
        activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF'
    });

    $('.a-modal-bg, .a-modal-closer').bind('touchstart', function (event) {
        event.preventDefault();
        return;
    });

    var sendSubscribeForm = function () {
        setTimeout(function () {
            $('#subscribe-form').ajaxSubmit({
                resetForm: false,
                url: '/index.ajax.php?route=subscribe/subscribe_save_ajax',
                success: function (data) {

                }
            });
        }, 1000);
    }

    $("#subscribe-form").on('change', '#categs_3, #categs_2, #categs_4', function (event) {
        var _this = $(this),
            _categ_id = _this.val(),
            _ids = [],
            _select = _this.parent().next().find('select');

        if (_categ_id) {
            $.ajax({
                type: "POST",
                url: '/index.ajax.php?route=get-sub-categs-subsribe',
                data: {
                    'categs': _categ_id
                },
                dataType: "json",
                cache: false,
                success: function (data) {
                    _html = '';
                    $.each(data, function (index, value) {
                        _html += '<option value="' + index + '">' + value + '</option>';
                        _ids.push(index);
                    });

                    if (event.loadPageEvent) {
                        _select.html(_html).select2('val', 'All');
                        _values = _select.data('values').toString();

                        if (_values != undefined) {
                            if (_values.indexOf(',')) {
                                _select.select2('val', _values.split(','));
                            }
                        }
                    }
                    else {
                        _select.html(_html).select2('val', _ids);
                    }
                }
            });
        }
        else {
            _this.parent().next().find('select').html('').select2('val', 'All');
        }
    });

    $("#subscribe-form").on('change', '.select-2:not(#categs_3, #categs_2, #categs_4, .cities, .sub-categs)', function () {
        var _this = $(this),
            _cities = _this.parent().parent().find('select.cities'),
            _val = _this.val();

        if (_val && _cities.val() === null) {
            _cities.select2('val', [-1]);
        }
        else if (_val == null) {
            _cities.select2('val', "");
        }
    });

    $('#subscribe-form').on('change', '.cities, .one-cities, .regions', function (event) {
        var _val = $(this).val();

        if (event.added && event.added.id != -1) {
            if (_val.indexOf('-1') === 0) {
                _val.splice(0, 1);
            }

            $(this).select2("val", _val);
        }
        else if (event.added) {
            $(this).select2("val", ["-1"]);
        }
    });

    $(".city-search").select2({
        minimumInputLength: 1,
        query: function (query) {
            var data = {results: []}, i, j, s;
            for (i = 1; i < 5; i++) {
                s = "";
                for (j = 0; j < i; j++) {
                    s = s + query.term;
                }
                data.results.push({id: query.term + i, text: s});
            }
            query.callback(data);
        }
    });

    $('#subscribe-form').on('click', '.select-all', function (e) {
        e.preventDefault();

        var _isAll = $(this).data('isAll'),
            _parent = $(this).parent().parent(),
            _select = _parent.find('.select-2').not('.cities, .one-cities'),
            _radio = _parent.find('input[type="radio"]'),
            values = [];

        values = $.map(_select.find('option'), function (option) {
            return option.value;
        });

        _select.select2("val", values).change();

        if (!_select.length && _parent.find('.one-cities').length) {
            $('.one-cities').select2("val", ["-1"]);
        }

        sendSubscribeForm();
    });

    $('#subscribe-form').on('click', '.subscribe-section-reset', function (e) {
        e.preventDefault();

        $(this).parent().parent().find('.select-2').select2("val", []).change();

        sendSubscribeForm();
    });

    if ($("#subscribe-form").length) {
        $("#categs_3, #categs_2, #categs_4").trigger({
            type: 'change',
            loadPageEvent: 1
        });
    }

    $("#subscribe-form").on('change', '.select-2, input[type="radio"]', function (event) {
        if (!event.loadPageEvent && (event.added || event.removed || event.bubbles)) {
            sendSubscribeForm();
        }
    });

    $('#global-search').on('click', '#search-category', function (e) {
        e.preventDefault();

        $(this).find('i').toggleClass('a-icon-chevron-down');
        $('#search-menu').slideToggle(200);
    });

    $('.a-pagination').clone().insertBefore('#pagination-container');

    Main.toggleMenu();
    Main.toggleExchange();
    /* ----------------------------------------------------------------------------- */

    function send_liqpay(d, s, p) {

        var form = '';
        if ($('.plat-radio').prop('checked')) {
            form = '<form action="https://www.portmone.com.ua/gateway/" method="post" id="liqpay2"  >'
            p = JSON.parse(p)

            for (var k in p) {
                form += '<input type="hidden" name="' + k + '" value="' + p[k] + '">'
            }

        } else {
            form = '<form method="POST" id="liqpay2" action="https://www.liqpay.com/api/checkout" accept-charset="utf-8" >';
            form += '<input type="hidden" name="signature" value="' + s + '">'
            form += '<input type="hidden" name="data" value="' + d + '">'
        }

        form += '</form>';

        jQuery('body').append(form);
        jQuery('#liqpay2').submit();

    }

    function sum() {
        var sum = $(".vip-radio:checked").parent().find('.vip-box-price span').text()
        sum = parseInt(sum);
        $('.supplement input:checked').map(function () {
            sum += parseInt($(this).val())
        })

        $('.all_sum').text(sum);
    }

    $('body').on('click', '.add-form-vip-box input ', function (e) {

        var s = $(this).parents('.add-form-vip-box').find('.vip-box-price span').text();
        $("input[name='show_competitor']").val(s);
        $('.show_competitor').text(s);
    })

    $('body').on('click', '.add-form-vip-box', function (e) {
        e.stopPropagation()
        $(this).find('label').click()
        sum();

    })

    $('body').on('click', '.supplement', function (e) {
        sum();
    })

    $('body').on('click', 'label', function (e) {
        e.stopPropagation()
    })


    $('body').on('click', '.payments', function (e) {
        e.stopPropagation()
        $('.payments').removeClass('active')
        $(this).addClass('active').find('label').click()
    })

    function sum_succecc() {

        var price = 0;
        $(".menu_folding input[type='checkbox']:checked").map(function () {
            var curr = parseInt($(this).parents('li').find('.price').text());

            price += parseInt(curr);

            $('.suma_price').text(price);

        })


        if (price > 0) {
            $('#show').show();
        } else {
            $('#show').hide()
        }


        $('.suma_price').text(price);

    }

    $('.menu_folding ').on('change', 'select', function () {
        var suma = {1: 150, 2: 100, 3: 50};
        var p = $(this).val();
        $(this).parents('li').find('input').val(p)
        $(this).parents('li').find('.price').text(" " + suma[p] + " ");
        sum_succecc();
    })

    $('.menu_folding ').on('click', 'input', function (e) {

        sum_succecc();

    })

    if (location.pathname.search('success')) {
        sum_succecc();
    }


    $('#success_form').submit(function (e) {
        e.preventDefault();
        $.post('/form_success', $("form#success_form").not('select').serialize(), function (d) {
            d = jQuery.parseJSON(d);
            send_liqpay(d.send_data.data, d.send_data.signature, d.portmone);

        })
    })

    function success_edit(d) {
        d = d || {};

        var sec = location.pathname.split('/');

        var sections = {
            'product': 3,
            'ads': 4,
            'activity': 5,
            'lab': 7,
            'realty': 8,
            'service': 9,
            'diagnostic': 10,
            'demand': 11,
            'article': 16,
            'vacancy': 15,
            'resume': 6
        };

        if (sec.length > 3) {
            var test = [];
            test[0] = sec[0];
            test[1] = sec[2];
            test[2] = sec[3];
            sec = test;
        }
        ;
        if (location.pathname.search('edit') > 0) {
            var resurs_id = sec[2].split('-');
            sec = sections[sec[1]];
            var url = '/success-' + resurs_id[1];
            url += '-' + sec + '-up';
            if (sec != 16)
                location.pathname = url;

        } else {

            if (d.product_id && sec[1]) {
                sec = sections[sec[1]];
                var url = '/success-' + d.product_id;
                url += '-' + sec + '-add';
                location.pathname = url;
            }

        }


    }

    $('.add_oby').on('click', function (e) {
        e.stopPropagation();
        $('.menu_top_right').show();

    })

    $('.plus, .close').on('click', function () {
        $('.menu_top_right').hide();
    })
    $('html').on('click', function () {
        $('.menu_top_right').hide();
    })

    function redirect_success(t) {

        var tt = jQuery.parseJSON(t.portmone)
        var ob = tt.shop_order_number.split('~');

        var url = '/success-' + (ob[0]) + '-' + (+ob[1]) + '-add';
        var form = '<form method="POST" id="success2" action="' + url + '">';
        form += '<input type="hidden" name="description"  value="' + (tt.description ) + '"  />';
        form += '<input type="hidden" name="shop_order_number"  value="' + (tt.shop_order_number ) + '"  />';
        form += '</form>';
        jQuery('body').append(form);
        jQuery('#success2').submit();

    }


    var ss;
    /* -------------------------------------- */
});