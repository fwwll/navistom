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

<h1 class="n-form-title">
    <span>Редактировать вакансию</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form id="activity-add-form" class="n-add-form a-clear" style="width:700px" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/work/vacancy/edit_ajax-{{data.vacancy_id}}">
	<div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-briefcase"></i> Информации о работодателе</span>
        </div>
    </div>
    
    <div class="a-row">
        <label><font class="a-red">*</font> Название компании</label>
        <input type="text" value="{{company_info.name}}" name="company_name" id="company_name" />
    </div>
    <div class="a-row">
        <label>Веб-сайт</label>
        <input type="text" value="{{company_info.site}}" name="company_site" id="company_site" />
    </div>
    <div class="a-row">
        <label>Логотип компании</label>
        <input type="file" name="image" id="image" title="uploads/images/work/80x100/{{company_info.logotype}}" />
        <input type="hidden" name="logotype" value="{{company_info.logotype}}" />
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Описание компании</label>
        <textarea class="autosize"  maxlength="1000" name="company_description">{{company_info.description}}</textarea>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> ФИО контактного лица</label>
        <input type="text" value="{% if company_info %}{{company_info.user_name}}{% else %}{{user_name}}{% endif %}" name="user_name" id="user_name" />
    </div>
    <div class="a-row">
        <label>
        	<font class="a-red">*</font>
        	Телефон контактного лица
            <span class="a-form-descr">можете указать несколько телефонов через запятую</span>
        </label>
        <input type="text" value="{{contact_phones.0}}" name="contact_phones" id="contact_phones" />
    </div>
    <input type="hidden" name="company_id" value="{{company_info.company_id}}" />
	<div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-user"></i> Требования к соискателю</span>
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
        <label><font class="a-red">*</font> Регион размещения вакансии</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option {% if key == data.region_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Город размещения вакансии</label>
        <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
            {% for key, value in cities %}
            	<option {% if key == data.city_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label>Зарплата, от</label>
        <input value="{{data.price}}" class="n-price-input validate[min[1]]" type="text" name="price" id="price" />
        
        <select class="n-currensy-input" name="currency_id" id="currency_id">
            <option value="1">Гривен</option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Вид занятости</label>
        <select class="validate[required]" id="type" name="type">
            <option {% if data.type_id == 1 %}selected="selected"{%endif%} value="1">полная занятость</option>
            <option {% if data.type_id == 2 %}selected="selected"{%endif%} value="2">неполная занятость</option>
            <option {% if data.type_id == 3 %}selected="selected"{%endif%} value="3">удаленная работа</option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Опыт работы</label>
        <select class="validate[required]" id="experience_type" name="experience_type">
            <option {% if data.experience_type == 0 %}selected="selected"{%endif%} value="0">не имеет значения</option>
            <option {% if data.experience_type == 1 %}selected="selected"{%endif%} value="1">от 1 года</option>
            <option {% if data.experience_type == 2 %}selected="selected"{%endif%} value="2">от 2 лет</option>
            <option {% if data.experience_type == 3 %}selected="selected"{%endif%} value="3">от 5 лет</option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Требуемый уровень образования</label>
        <select class="validate[required]" id="education_type" name="education_type">
            <option {% if data.education_type == 0 %}selected="selected"{%endif%} value="0">не имеет значения</option>
            <option {% if data.education_type == 1 %}selected="selected"{%endif%} value="1">высшее</option>
            <option {% if data.education_type == 2 %}selected="selected"{%endif%} value="2">неоконченное высшее</option>
            <option {% if data.education_type == 3 %}selected="selected"{%endif%} value="3">среднее специальное</option>
            <option {% if data.education_type == 4 %}selected="selected"{%endif%} value="4">среднее</option>
        </select>
    </div>
    
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-file"></i> Дополнительно</span>
        </div>
    </div>
    <div class="a-row">
        <label>Описание вакансии</label>
        <textarea class="autosize" maxlength="3000" name="content">{{data.content}}</textarea>
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