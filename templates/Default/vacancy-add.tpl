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
    <span>Добавить вакансию</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>
{% if is_add_access %}
<form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/work/vacancy_add_ajax">
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
        <input type="file" name="image" id="image" />
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
    
    {% if company_info %}
    	<input type="hidden" name="company_id" value="{{company_info.company_id}}" />
    {% endif %}
	<div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-user"></i> Требования к соискателю</span>
        </div>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id" name="categ_id[]">
            {% for key, value in categories %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Регион размещения вакансии</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Город размещения вакансии</label>
        <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
            <option value></option>
        </select>
    </div>
    <div class="a-row">
        <label>Зарплата, от</label>
        <input class="n-price-input validate[min[1]]" type="text" name="price" id="price" />
        
        <select class="n-currensy-input" name="currency_id" id="currency_id">
            <option value="1">Гривен</option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Вид занятости</label>
        <select class="validate[required]" id="type" name="type">
            <option value="1">полная занятость</option>
            <option value="2">неполная занятость</option>
            <option value="3">удаленная работа</option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Опыт работы</label>
        <select class="validate[required]" id="experience_type" name="experience_type">
            <option value="0">не имеет значения</option>
            <option value="1">от 1 года</option>
            <option value="2">от 2 лет</option>
            <option value="3">от 5 лет</option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Требуемый уровень образования</label>
        <select class="validate[required]" id="education_type" name="education_type">
            <option value="0">не имеет значения</option>
            <option value="1">высшее</option>
            <option value="2">неоконченное высшее</option>
            <option value="3">среднее специальное</option>
            <option value="4">среднее</option>
        </select>
    </div>
    
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-file"></i> Дополнительно</span>
        </div>
    </div>
    <div class="a-row">
        <label>Описание вакансии</label>
        <textarea class="autosize" maxlength="3000" name="content"></textarea>
    </div>
    <div class="a-row">
        <label>Ссылка на видео с YouTube</label>
        <input type="text" name="video_link" id="video_link" />
    </div>
    
    <div class="a-row">
        <div class="n-form-add-btns a-clear">
            <div class="col-1">
                <div class="n-title-orange">VIP размещение</div>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut odio neque.
                <div>
                    <input name="vip" class="n-add-btn-orange input-submit" type="submit" value="VIP Размещение" />
                </div>
            </div>
            <div class="col-2">
                <div class="n-title-gray">Обычное размещение</div>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ut odio neque.
                <div>
                    <input name="default" class="n-add-btn-gray input-submit" type="submit" value="Добавить" />
                </div>
            </div>
        </div>
    </div>
</form>
{% else %}
	<div style="width:700px" class="a-mess-yellow">
    	У Вас нет доступа к размещению мероприятий. <br />
        Для получения дополнительной информации обратитесь к администратору портала.
    </div>
{% endif %}
{% endblock %}