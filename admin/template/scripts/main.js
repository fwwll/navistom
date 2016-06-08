var _options = {
    addressValue: null,
    addressNoLoad: false
}

function initMoment() {
    $('span.moment').each(function(index, element) {
        _date = $(this).text();
        _date_new = moment(_date, 'YYYY-MM-DD HH:mm:ss', 'ru').fromNow();

        $(this).text(_date_new);
    });
}

function modalWindow(content) {
    return '<div class="a-modal-bg"></div>' +
    '<div class="a-modal-parent a-modal-admin">' +
    '<div class="a-modal">' +
    '<a class="a-modal-closer" href="#"></a>' +
    '<div class="a-modal-table">' +
    '<div class="a-modal-content">' +
    content +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';
}

function uploader(elem) {
    _module = window.location.href.split('/')[4];

    elem.fineUploader({
        request: {
            endpoint: '/admin/index.ajax.php?route=' + _module + '/upload_image'
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

        $('.uploader > li').each(function(index, element) {
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

            if($('.uploader > li.image-added').length < 8) {
                $('.uploader > li').each(function(index, element) {
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
            else {
                _html = '<li class="image-added">'+
                '<input id="iamge_' + responseJSON.image_id + '" type="hidden" name="images[]" value="' + responseJSON.image_id + '" />' +
                '<img src="' + responseJSON.uploadName + '" alt="' + responseJSON.id_image + '">' +
                '<div class="options">' +
                '<a class="add-description" title="Добавить описание" href="' + responseJSON.image_id + '">'+
                '<i class="a-icon-pencil a-icon-white"></i></a>' +
                '<a class="delete-image" title="Удалить" href="' + responseJSON.image_id + '"><i class="a-icon-trash a-icon-white"></i></a>' +
                '</div></li>';

                $('.uploader').append(_html).addClass('image-added');
            }
        }

        return true;
    });
}

$(document).ready(function(e) {
    $(".tablesorter").tablesorter();

    $(".datatables").dataTable({
        "oLanguage": {
            "sUrl": "/assets/dataTables/ru.txt"
        },
        "bSort"			: false,
        "bDeferRender"	: true
    });

    $('.chart').knob({
        readOnly	: true
    });

    $('.ad-left').mCustomScrollbar({
        scrollInertia: 0,

        advanced:{
            updateOnContentResize: true,
            autoScrollOnFocus: false,
            updateOnBrowserResize: true
        }
    });

    if($(".code-editor").length > 0) {
        $(".code-editor").each(function(i, el) {
            CodeMirror.fromTextArea(el, {
                lineNumbers: true,
                tabSize: 4,
                indentUnit: 4,
                indentWithTabs: true,
                viewportMargin: 4,
                mode: 'text/html'
            });
        });
    }

    $('#calendar').fullCalendar({
        firstDay: 1,
        height: 200,
        timeFormat: 'H:mm',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв.','Фев.','Март','Апр.','Май','Июнь','Июль','Авг.','Сент.','Окт.','Ноя.','Дек.'],
        dayNames: ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
        dayNamesShort: ["ВС","ПН","ВТ","СР","ЧТ","ПТ","СБ"],
        buttonText: {
            prev: "&nbsp;&#9668;&nbsp;",
            next: "&nbsp;&#9658;&nbsp;",
            prevYear: "&nbsp;&lt;&lt;&nbsp;",
            nextYear: "&nbsp;&gt;&gt;&nbsp;",
            today: "Сегодня",
            month: "Месяц",
            week: "Неделя",
            day: "День"
        },
        eventSources: [{
            url: '/admin/index.ajax.php',
            type: 'GET',
            data: {
                route: 'articles/calendar_ajax'
            },
            error: function() {
                alert('Ошибка соединения с источником данных!');
            }
        }]
    });

    $('ul.tabs').delegate('li:not(.current)', 'click', function() {
        $(this).addClass('current').siblings().removeClass('current')
            .parents('div.section').find('div.box').eq($(this).index()).attr('class', 'box tab-show')
            .siblings('div.box').attr('class', 'box tab-hide')
    });

    $('.editor').redactor({
        lang: 'ru',
        buttons: ['bold', 'italic', 'deleted', '|', 'alignleft', 'aligncenter', 'alignright', '|',
            'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
            'image', 'video', 'file', 'table', 'link', '|',
            'fontcolor', 'backcolor', '|', 'horizontalrule', '|' ,'html'],

        imageUpload: '/admin/upload/upload_editor_image',
        convertImageLinks: true,
        convertVideoLinks: true,
        minHeight: 200,
        plugins: ['codemirror']
    });

    $(".spinner").spinner({
        min: 0
    });

    $(".spinner:disabled").spinner("disable");

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        nextText: '',
        prevText: ''
    });

    $('.datetimepicker').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: "HH:mm"
    });

    $('.timepicker').timepicker();

    $( ".datepicker-from" ).datepicker({
        dateFormat: 'yy-mm-dd',
        onClose: function( selectedDate ) {
            $( ".datepicker-to" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $( ".datepicker-to" ).datepicker({
        dateFormat: 'yy-mm-dd',
        onClose: function( selectedDate ) {
            $( ".datepicker-from" ).datepicker( "option", "maxDate", selectedDate );
        }
    });

    $('.pass-generate').pGenerator({
        'bind': 'click',
        'passwordElement': '#passw',
        'displayElement': null,
        'passwordLength': 8,
        'uppercase': true,
        'lowercase': true,
        'numbers':   true,
        'specialChars': false,
        'onPasswordGenerated': function(generatedPassword) { }
    });

    $(".select-2").select2({
        placeholder: 'Выберите из списка'
    });

    $(".validation").validationEngine({
        promptPosition: "topRight",
        scrollOffset: 60
    });

    $('.tags-input').tagsInput({
        width: '65%',
        height: '200',
        defaultText: 'Добавить вариант'
    });

    $(window).resize(function(){
        $(".validation").validationEngine("updatePromptsPosition")
    });

    $('[maxlength]').inputlimiter({
        limit: $(this).attr('maxlength'),
        remText: '%n <i class="a-icon-pencil"></i>',
        limitText: ''
    });

    $('[mask]').mask();

    $("input[type=radio], input[type=checkbox], input[type=file]").not(".uploadify, .switch-checkbox, .icheck").uniform();

    $("input[type=file]").each(function() {
        _filename = $(this).attr('title');

        if(_filename != undefined) {
            $(this).mTip({
                content: '<img src="../' + _filename + '" alt="../' + _filename + '" />',
                align: 'bottom'
            }).next().text(_filename);
        }

    });

    $('.icheck').each(function(){
        var self = $(this),
            label = self.next(),
            label_text = label.text();

        label.remove();
        self.iCheck({
            checkboxClass: 'icheck-checkbox',
            radioClass: 'icheck-radio',
            insert: label_text
        });
    });

    /* Moment JS */

    initMoment();

    /**/

    /* activity */

    if($('#flag_agreed').attr('checked') == 'checked') {
        $('#start_date_range, #end_date_range').attr('disabled', 'disabled');
    }

    $('#flag_agreed').on('change', function(){
        if($(this).attr('checked') == 'checked') {
            $('#start_date_range, #end_date_range').val('').attr('disabled', 'disabled');
        }
        else {
            $('#start_date_range, #end_date_range').removeAttr('disabled');
        }
    });

    /* end activity */

    $('.icheck').bind('is.Checked', function() {
        var _name = $(this).attr('name');

        $('.icheck[name=' + _name + ']').removeAttr('checked');
        $(this).attr('checked', 'checked');
    });

    $('.a-row > textarea').autoResize();

    $("#country_id").on('change', function(){
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

                    $("#region_id").html(_html).select2('val', 'All');
                }
            });
        }
    });

    $('#region_id').on('change', function(){
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

                    $("#city_id").html(_html).select2('val', 'All');
                }
            });
        }
    });

    $('.ad-left-menu > li > a').click(function(){
        _href 	= $(this).attr('href');
        _child 	= $(this).parent().find('ul');
        _parent	= $(this).parent();

        if(_href == '#') {
            if(_child.index() == 1 ) {
                _parent.toggleClass('active');
                _child.slideToggle(200, function(){
                    if(_child.css('display') == 'block')
                        $.cookie('menu_open_' + _parent.index(), 1, {path: '/admin/'});
                    else
                        $.cookie('menu_open_' + _parent.index(), 0, {path: '/admin/'});
                });
            }

            return false;
        }
    });

    $('input.form-submit').click(function(){
        if($(".validation").validationEngine('validate'))
            $('form.ad-form').append('<input type="hidden" name="' + $(this).attr('name') + '" value="' + $(this).val() + '" />').submit();
    });

    $('#form-cancel').click(function(){
        _url = window.location.pathname.split('/')[2];
        window.location.assign('/admin/' + _url);

        return false;
    });

    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    $( ".sortable tbody" ).sortable({
        helper: fixHelper,
        axis: 'y',
        delay: 100,
        items: "> tr",
        update: function() {
            $.ajax({
                type: "GET",
                url: '/admin/index.ajax.php',
                cache: false,
                data: {
                    'route': '/' + $(this).attr('id') + '/sorted',
                    'data': $(".sortable tbody").sortable("serialize")
                }
            });
        }
    });

    function sendSortedInfo(data) {
        $.ajax({
            type: "GET",
            url: '/admin/index.ajax.php',
            cache: false,
            data: {
                'route': '/products/category/sorted',
                'data': data
            }
        });
    }

    $( ".sortable-list" ).sortable({
        axis: 'y',
        delay: 100,
        items: "> li",
        containment: "parent",
        update: function(e, data) {
            sendSortedInfo(data.item.parent().sortable("serialize"));
        }
    });

    $( ".sortable-list > li > ul").sortable({
        axis: 'y',
        delay: 100,
        containment: "parent",
        connectWith: '.sortable-list > li > ul',
        update: function(e, data) {
            sendSortedInfo(data.item.parent().sortable("serialize"));
        }
    });

    $('.a-modal-closer, .a-modal-close').live('click', function(){
        $('.a-modal-bg').fadeOut(200, function(){
            $('.a-modal-bg').remove();
        });

        $('.a-modal-parent').fadeOut(100, function(){
            $('.a-modal-parent').remove();
        });

        _options.addressNoLoad = true;

        if ( window.history && window.history.pushState ) {
            window.history.pushState('', '', window.location.pathname)
        } else {
            window.location.href = window.location.href.replace(/#.*$/, '#');
        }

        $.address.value('');

        _options.addressNoLoad = false;

        return false;
    });

    $('input.user_access_check').change(function(){
        if($(this).is(':checked')) {
            _parent = $(this).parent().parent();
            _elems 	= _parent.nextAll().slice(0, 3);

            _elems.css({
                'height': 0,
                'overflow': 'hidden',
                opacity: 0
            }).removeClass('a-row-hide');

            _elems.animate({
                'height': 32,
                opacity: 1
            }, 200);
        }
        else {
            _parent = $(this).parent().parent();
            _elems 	= _parent.nextAll().slice(0, 3);

            _elems.animate({
                'height': 0,
                opacity: 0
            }, 200);

            _elems.find('input').val('');
        }
    });

    $('input.user_access_check').each(function() {
        if($(this).is(':checked')) {
            $(this).parent().nextAll().slice(0, 4).removeClass('a-row-hide');
        }
    });

    /* Uplupload init */

    uploader($('.uploader li').not('.image-added'));

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

    $('.delete-image').live('click', function(){
        _image_id = $(this).attr('href');
        _parent = $(this).parent().parent();

        if(_image_id > 0) {
            $.ajax({
                type: "GET",
                url: '/admin/index.ajax.php',
                data: {
                    'route': _module + '/delete_image-' + _image_id
                },
                dataType:"json",
                cache: false,
                success: function(data) {
                    if(data.success) {
                        _parent.removeClass('image-added').find('img, input[type=hidden], .options').remove();
                        //_parent.find('.qq-uploader').show();
                        _parent.find('i').attr('class', 'a-icon-plus a-icon-gray');

                        if(_parent.find('.qq-uploader').index() == -1) {
                            uploader(_parent);
                        }
                        else {
                            _parent.find('.qq-uploader').show();
                        }
                    }
                }
            });
        }

        return false;
    });

    $('.add-description').live('click', function(){
        _id = $(this).attr('href');

        if($('#descr_' + _id).index() != -1) {
            _val = $('#descr_' + _id).val();
        }
        else {
            _val = '';
        }

        _content = '<h1 class="ad-title">' +
        '<b>Добавить описание к изображению</b>' +
        '</h1>' +
        '<textarea class="image_description" name="image_description[' + _id + ']">' + _val + '</textarea>'+
        '<div class="a-clear"><br /><a class="a-btn-green a-float-right image-description-save" href="#">Сохранить</a></div>';

        _modal = modalWindow(_content);

        $('body').prepend(_modal).css('overflow', 'hidden');
        $('.a-modal-closer').height($('.a-modal').height())

        return false;
    });

    $('.image-description-save').live('click', function(){
        _textarea	= $(this).parent().parent().find('textarea');
        _val 		= _textarea.val();
        _name		= _textarea.attr('name');

        if(_val != '') {

            if($('#descr_' + _id).index() != -1) {
                $('#descr_' + _id).remove();
            }

            $('.uploader').append('<input id="descr_'+ _id +'" type="hidden" name="' + _name + '" value="' + _val + '" />')
        }

        $('.a-modal-closer').click();

        return false;
    });
});

$(function () {

    $.address.init(function(event) {
        $('a.ajax-link').address();
        _options.addressValue = window.location.pathname;

    }).change(function(event) {

        if(!_options.addressNoLoad) {
            //$.address.state('/admin');

            if(event.path != '/') {
                $.ajax({
                    type: "GET",
                    url: '/admin/index.ajax.php',
                    data: {
                        'route': event.path
                    },
                    dataType:"html",
                    cache: false,
                    success: function(data) {
                        $('body').prepend(data).css('overflow', 'hidden');
                        $('.a-modal-closer').height($('.a-modal').height())

                        initMoment();
                    }
                });
            }
        }
    });
});

$(window).load(function(){

    $('.ad-left-menu > li > a').each(function(index){
        _child 	= $(this).parent().find('ul');

        if(_child.index() == 1) {
            if($.cookie('menu_open_' + index) == 1) {
                _child.slideDown(200);
                $(this).parent().attr('class', 'active');
            }
        }
    });

    $('.switch-checkbox').iphoneStyle({
        checkedLabel: 'Да',
        uncheckedLabel: 'Нет'
    });

    $('.user-send-mess form').on('submit', function(e) {
        e.preventDefault();

        _submit = $(this).find('input[type=submit]');
        _form 	= $(this);
        _parent	= _form.parent();

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
    });

    $(document).on('click', '.delete-link', function(e){
        e.preventDefault();
        var elem = $(this),
            href = elem.attr('href') || elem.data('href'),
            title = elem.attr('title');

        $.confirm({
            'title'		: 'Удаление материала',
            'message'	: title + '<br /><br /> Вы действительно хотите удалить этот материал?',
            'buttons'	: {
                'Удалить'	: {
                    'class'	: 'a-btn-red',
                    'action': function(){
                        window.location = href;
                    }
                },
                'Отмена'	: {
                    'class'	: 'a-btn',
                    'action': function() { }
                }
            }
        });

        //$(this).parent().parent().parent().parent().remove();
    });
	
	
	
	
	 $(document).on('click', '.update-price-admin', function(e){
        e.preventDefault();
        var  elem = $(this);
        var  tr =elem.parents('tr');    
        var  name =tr.find('.name').text(); 
		var  tops =tr.find('.top').text();
		var  input =tr.find('input[type="text"]');
		var  checked =tr.find('input[type="radio"]:checked').val() || tr.find('input[type="checkbox"]:checked').val()  || 0  ;

		var  p = input.val();
		var  nameA =input.attr('name');
		var section=  input.attr('section');
		var $img=  tr.find('img');
		// alert(nameA);
        $.confirm({
            'title'		: 'Обновить цену',
            'message'	:name+ '<br />'+tops+' : '+p+'грн'+'<br /> Алексей Николаевич  разрешил ?',
            'buttons'	: {
                'Беру ответственность'	: {
                    'class'	: 'a-btn-red',
                    'action': function(){
						 $.post('/admin/price/update' ,{'section_id':section, 'name':nameA ,'pric':p, 'checked':checked}, function(d){
                                      $img.attr('src','/admin/template/images/ok_send.png');

						 } ); 
                       
                    }
                },
                'Если страшно'	: {
                    'class'	: 'a-btn',
                    'action': function() { 
					
					}
                }
            }
        });

  
    });
	
	
	
    $('.ad-form').on('change', '#type', function() {
        _val = $(this).val();

        if(["2", "5"].indexOf(_val) !== -1) {
            $('#code').val('').attr("disabled", true);
        }
        else {
            $('#code').attr("disabled", false);
        }
    });

    /*$(window).resize(function() {
     $("body").scroller("reset");
     });*/

    /*$('.ad-right').jScrollPane({
     autoReinitialise: true
     });*/

    /*$("body").mCustomScrollbar({
     scrollInertia: 0,

     advanced:{
     updateOnContentResize: true,
     autoScrollOnFocus: false,
     updateOnBrowserResize: true
     }
     });*/
});

if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.codemirror = {
    init: function() {

    },
    toggle: function() {
        var html;
        if (this.opts.visual)
        {

            var height = null;
            if (this.opts.iframe)
            {
                height = this.$frame.height();
                if (this.opts.fullpage) this.$editor.removeAttr('contenteditable');
                this.$frame.hide();
            }
            else
            {
                height = this.$editor.innerHeight();
                this.$editor.hide();
            }

            html = this.$source.val();
            this.modified = html;

            this.$source.height(height).show().focus();

            this.opts.source_code = CodeMirror.fromTextArea(document.getElementById(this.$source.attr('id')), {
                lineNumbers: true,
                tabSize: 4,
                indentUnit: 4,
                indentWithTabs: true,
                gutter: true,
                lineWrapping: true,
                mode: 'text/html'
            });

            // textarea indenting
            this.$source.on('keydown.redactor-textarea', function (e)
            {
                if (e.keyCode === 9)
                {
                    var $el = $(this);
                    var start = $el.get(0).selectionStart;
                    $el.val($el.val().substring(0, start) + "\t" + $el.val().substring($el.get(0).selectionEnd));
                    $el.get(0).selectionStart = $el.get(0).selectionEnd = start + 1;
                    return false;
                }
            });

            this.buttonInactiveVisual();
            this.buttonActive('html');
            this.opts.visual = false;

        }
        else
        {
            this.opts.source_code.toTextArea();
            html = this.$source.hide().val();

            if (typeof this.modified !== 'undefined')
            {
                this.modified = this.cleanRemoveSpaces(this.modified, false) !== this.cleanRemoveSpaces(html, false);
            }

            if (this.modified)
            {
                // don't remove the iframe even if cleared all.
                if (this.opts.fullpage && html === '') this.setFullpageOnInit(html);
                else
                {
                    this.set(html);
                    if (this.opts.fullpage) this.buildBindKeyboard();
                }
            }

            if (this.opts.iframe) this.$frame.show();
            else this.$editor.show();

            if (this.opts.fullpage ) this.$editor.attr('contenteditable', true );

            this.$source.off('keydown.redactor-textarea');

            this.$editor.focus();
            this.selectionRestore();

            this.observeStart();
            this.buttonActiveVisual();
            this.buttonInactive('html');
            this.opts.visual = true;
        }
    }
};

