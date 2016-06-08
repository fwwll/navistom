{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}
	{{meta.meta_title}}
{% endblock %}

{% block meta_description %}
	{{meta.meta_description}}
{% endblock %}

{% block meta_keys %}
	{{meta.meta_keys}}
{% endblock %}

{% block content %}
<!-- dentist@dentist.ua uqJ0T8rz -->
<!-- navistom@navistom.net 2asqK6xN -->
<h1 class="n-form-title">
    <span>Редактировать резюме</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/work/resume/edit_ajax-{{data.work_id}}">
	<div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-user"></i> Личные данные</span>
        </div>
    </div>
    <div class="a-row">
    	<label><font class="a-red">*</font> Фамилия</label>
        <input value="{{data.user_surname}}" class="validate[required]" type="text" name="user_surname" id="user_surname" />
    </div>
	<div class="a-row">
    	<label><font class="a-red">*</font> Имя</label>
        <input value="{{data.user_name}}" class="validate[required]" type="text" name="user_name" id="user_name" />
    </div>
    <div class="a-row">
    	<label> Отчество</label>
        <input value="{{data.user_firstname}}" type="text" name="user_firstname" id="user_firstname" />
    </div>
    <div class="a-row">
    	<label><font class="a-red">*</font> Дата рождения</label>
        <select class="date_day validate[required]" name="birth_date_day" id="birth_date_day">
			<option value="">день</option>
            {% for key, value in days %}
            <option {% if value == data.brith.2 %}selected="selected"{%endif%} value="{{value}}">{{value}}</option>
            {% endfor %}
		</select>
        <select class="date_month validate[required]" name="birth_date_month" id="birth_date_month">
            <option value="">месяц</option>
            {% for key, value in months %}
            <option {% if key == data.brith.1 %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
        <select class="date_year validate[required]" name="birth_date_year" id="birth_date_year">
            <option value="">год</option>
            {% for key, value in years %}
            <option {% if value == data.brith.0 %}selected="selected"{%endif%} value="{{value}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-file"></i> Общая информация</span>
        </div>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id" name="categ_id[]">
            {% for key, value in categories %}
            	<option {% if key in data.categ_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Желаемый регион работы</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option {% if key == data.region_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Желаемый город работы</label>
        <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
            <option value></option>
            {% for key, value in cities %}
            	<option {% if key == data.city_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label>Желаемая зарплата</label>
        <input value="{{data.price}}" class="n-price-input validate[min[1]]" type="text" name="price" id="price" />
        
        <select class="n-currensy-input" name="currency_id" id="currency_id">
            <option value="1">Гривен</option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Вид занятости</label>
        <select class="validate[required]" id="employment_type" name="employment_type">
            <option {% if data.employment_type == 1 %}selected="selected"{%endif%} value="1">полная занятость</option>
            <option {% if data.employment_type == 2 %}selected="selected"{%endif%} value="2">неполная занятость</option>
            <option {% if data.employment_type == 3 %}selected="selected"{%endif%} value="3">удаленная работа</option>
        </select>
    </div>
    
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-briefcase"></i> Опыт работы</span>
        </div>
    </div>
    <div id="resume-work">
        <div class="a-row a-form-mess {% if employments|length > 0%}display-none{%endif%}">
            <i class="a-icon-info-sign"></i>
            Заполняя информацию об опыте работы, постарайтесь раскрыть для работодателя ваши положительные стороны, которые в наибольшей мере соответствуют заголовку вашего резюме. Опишите ваши обязанности, но не останавливайтесь только на них. Не забудьте упомянуть о ваших знаниях, умениях и о положительном опыте применения их в работе.
            <p>
                <a href="#" id="resume-add-work" class="a-btn-green a-float-right"> Добавить место работы</a>
            </p>
        </div>
        {% for e in employments %}
        <div class="resume-add-work-parent" id="resume-add-work-{{loop.index - 1}}">
			<a href="javascript:void(0)" class="delete-resume-added">Удалить</a>
			<div class="a-row">
				<label><font class="a-red">*</font> Компания</label>
				<input value="{{e.company_name}}" class="validate[required]" type="text" name="work[company_name][]" id="company_name_{{loop.index - 1}}" />
			</div>
			<div class="a-row">
				<label><font class="a-red">*</font> Должность</label>
				<input value="{{e.position}}" class="validate[required]" type="text" name="work[position][]" id="position_{{loop.index - 1}}" />
			</div>
			<div class="a-row">
				<label><font class="a-red">*</font> Выполняемые обязаности</label>
				<input value="{{e.activity}}" class="validate[required]" type="text" name="work[activity][]" id="activity_{{loop.index - 1}}" />
			</div>
			<div class="a-row">
				<label><font class="a-red">*</font> Период работы</label>
				<input value="{{e.date_start}}" placeholder="Дата начала" type="text" class="month-picker month-picker-start validate[required]" name="work[date_start][]" id="work_date_{{loop.index - 1}}" />
				<i class="a-icon-calendar"></i>
				<input value="{{e.date_end}}" placeholder="Дата окончания" type="text" class="month-picker validate[required]" name="work[date_end][]" id="work_date_{{loop.index - 1}}" />
				<i class="a-icon-calendar"></i>
			</div>
			<hr class="hr-min" />
		</div>
        {% endfor %}
    </div>
    {% if employments|length > 0%}
    <div class="a-row">
    	<a class="a-btn a-float-right" id="resume-add-work-next" href="javascript:void(0)">
        	<i class="a-icon-plus"></i>Добавить место работы
        </a>
    </div>
    {%endif%}
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-book"></i> Образование</span>
        </div>
    </div>
    
    <div id="resume-experience">
        <div class="a-row a-form-mess {% if educations|length > 0%}display-none{%endif%}">
            <i class="a-icon-info-sign"></i>
            Во время редактирования этого блока, вы можете добавить в резюме образование, которое вы получили или о которой хотите упомянуть в соответствии с целями данного резюме.
            <p>
                <a href="#" id="resume-add-experience" class="a-btn-green a-float-right"> Добавить место учебы</a>
            </p>
        </div>
        
        {% for e in educations %}
        <div style="position:relative;" class="resume-add-experience-parent" id="resume-experience-{{loop.index - 1}}">
            <a href="javascript:void(0)" class="delete-resume-added">Удалить</a>
            <div class="a-row">
                <label><font class="a-red">*</font> Уровень образования</label>
                <select class="validate[required]" name="education[type][]" id="type_rid_{{loop.index - 1}}">
                    <option value=""> - выбрать - </option>
                    <option {% if e.type == 1 %}selected="selected"{%endif%} value="1">высшее</option>
                    <option {% if e.type == 2 %}selected="selected"{%endif%} value="2">неоконченное высшее</option>
                    <option {% if e.type == 3 %}selected="selected"{%endif%} value="3">среднее специальное</option>
                    <option {% if e.type == 4 %}selected="selected"{%endif%} value="4">среднее</option>						
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Учебное заведение</label>
                <input value="{{e.institution}}" class="validate[required]" type="text" name="education[institution][]" id="institution_{{loop.index - 1}}" />
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Факультет, специальность</label>
                <input value="{{e.faculty}}" class="validate[required]" type="text" name="education[faculty][]" id="faculty_{{loop.index - 1}}" />
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Город</label>
                <input value="{{e.location}}" class="validate[required]" type="text" name="education[location][]" id="location_{{loop.index - 1}}" />
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Период обучения</label>
                <input value="{{e.date_start}}" placeholder="Дата начала" type="text" class="month-picker month-picker-start validate[required]" name="education[date_start][]" id="education_date_start_{{loop.index - 1}}" />
                <i class="a-icon-calendar"></i>
                <input value="{{e.date_end}}" placeholder="Дата окончания" type="text" class="month-picker validate[required]" name="education[date_end][]" id="education_date_end_{{loop.index - 1}}" />
                <i class="a-icon-calendar"></i>
            </div>
            <hr class="hr-min" />
        </div>
        {% endfor %}
    </div>
    {% if educations|length > 0%}
    <div class="a-row">
    	<a class="a-btn a-float-right" id="resume-add-experience-next" href="javascript:void(0)">
        	<i class="a-icon-plus"></i>Добавить место учебы
        </a>
    </div>
    {%endif%}
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-book"></i> Дополнительное образование</span>
        </div>
    </div>
    
    <div id="resume-traning">
        <div class="a-row a-form-mess {% if tranings|length > 0%}display-none{%endif%}">
            <i class="a-icon-info-sign"></i>
            Если вы окончили какие-либо курсы, принимали участие в тренингах, семинарах, т. е. повышали свой профессиональный уровень, добавьте такую информацию в этом блоке.
            <p>
                <a href="#" id="resume-add-traning" class="a-btn-green a-float-right"> Добавить курс или тренинг</a>
            </p>
        </div>
        
        {% for t in tranings %}
        <div style="position:relative;" class="resume-add-traning-parent" id="resuma-traning-{{loop.index - 1}}">
            <a href="javascript:void(0)" class="delete-resume-added">Удалить</a>
            <div class="a-row">
                <label><font class="a-red">*</font> Название учебного заведения (курсов)</label>
                <input value="{{t.name}}" class="validate[required]" type="text" name="traning[name][]" id="traning_name_{{loop.index - 1}}" />
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Год, длительность</label>
                <input value="{{t.description}}" class="validate[required]" type="text" name="traning[description][]" id="traning_year_{{loop.index - 1}}" />
            </div>
            <hr class="hr-min" />
        </div>
        {% endfor %}
    </div>
    {% if tranings|length > 0%}
    <div class="a-row">
    	<a class="a-btn a-float-right" id="resume-add-traning-next" href="javascript:void(0)">
        	<i class="a-icon-plus"></i>Добавить курс или тренинг
        </a>
    </div>
    {%endif%}
    
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-globe"></i> Владение языками</span>
        </div>
    </div>
    
    <div id="resume-langs">
        <div class="a-row a-form-mess {% if langs|length > 0%}display-none{%endif%}">
            <i class="a-icon-info-sign"></i>
            Используйте этот блок резюме, чтобы указать, какие языки вы знаете, и оценить степень владения ими. Даже если знание языков напрямую и не требуется в вакансиях, на которые вы претендуете, эта информация будет полезна как дополнительная.
            <p>
                <a href="#" id="resume-add-lang" class="a-btn-green a-float-right"> Добавить язык</a>
            </p>
        </div>
        
        {% for l in langs %}
        <div style="position:relative;" class="resume-add-lang-parent" id="resume-lang-{{loop.index - 1}}">
            <a href="javascript:void(0)" class="delete-resume-added">Удалить</a>
            <div class="a-row">
                <label><font class="a-red">*</font> Язык</label>
                <input value="{{l.name}}" class="validate[required]" type="text" name="langs[name][]" id="lang_name_{{loop.index - 1}}" />
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Уровень</label>
                <select class="validate[required]" name="langs[level][]" id="lang_level_{{loop.index - 1}}">
                    <option value=""> - выбрать - </option>
                    <option {% if l.level == 1 %}selected="selected"{%endif%} value="1">Начинающий</option>
                    <option {% if l.level == 2 %}selected="selected"{%endif%} value="2">Средний</option>
                    <option {% if l.level == 3 %}selected="selected"{%endif%} value="3">Эксперт</option>		
                </select>
            </div>
            <hr class="hr-min" />
        </div>
        {% endfor %}
    </div>
    {% if langs|length > 0%}
    <div class="a-row">
    	<a class="a-btn a-float-right" id="resume-add-lang-next" href="javascript:void(0)">
        	<i class="a-icon-plus"></i>Добавить язык
        </a>
    </div>
    {%endif%}
    
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-edit"></i> Дополнительно</span>
        </div>
    </div>
    
    <div class="a-row">
        <label>Описание Ваших навыков</label>
        <textarea class="autosize" maxlength="3000" name="content">{{data.content}}</textarea>
    </div>
    <div class="a-row">
        <label>Фото и фотографии работ</label>
        
        <ul class="uploader" id="uploader">
        	{% for i in images%}
            <li class="image-added">
            	<input type="hidden" value="{{i.image_id}}" name="images[]"/>
            	<img src="/uploads/images/work/80x100/{{i.url_full}}" alt="{{i.image_id}}"/>
            </li>
            {% endfor %}
            
            {% for i in 0..images_count%}
            <li></li>
            {% endfor %}
        </ul>
    </div>
    <div class="a-row">
        <label>Ссылка на видео с YouTube</label>
        <input value="{{data.video_link}}" type="text" name="video_link" id="video_link" />
    </div>
    <div class="a-row">
    	<label>&nbsp;</label>
        <input class="a-btn-green" type="submit" value="Сохранить"  />
    </div>
</form>
{% endblock %}