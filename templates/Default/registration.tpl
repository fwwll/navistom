{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block content %}

<h1 class="n-form-title">
    <span>Регистрация</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form id="registration-form" class="n-add-form validation" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/registration_ajax">
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-user"></i> Основная информация</span>
        </div>
    </div>
    <div class="a-row">
        <label for="user_name"><font class="a-red">*</font> Ваше имя или ник</label>
        <input class="validate[required, minSize[4]]" type="text" name="user_name" id="user_name" />
    </div>
    <div class="a-row">
        <label for="user_email"><font class="a-red">*</font> Действующий E-mail адрес</label>
        <input class="validate[required, custom[email], ajax[ajaxUserCall]]" type="text" name="user_email" id="user_email" />
    </div>
    <div class="a-row">
        <label for="user_passw"><font class="a-red">*</font> Пароль</label>
        <input class="validate[required, minSize[6]]" type="password" name="user_passw" id="user_passw" />
    </div>
    <div class="a-row">
        <label for="user_passw_2"><font class="a-red">*</font> Повторите пароль</label>
        <input class="validate[required, equals[user_passw]]" type="password" name="user_passw_2" id="user_passw_2" />
    </div>
    <div class="a-row">
        <label for="user_country"><font class="a-red">*</font> Страна</label>
        <select class="validate[required]" name="user_country" id="user_country">
        	{% for key, value in countries %}
            <option {% if default_country == key %} selected="selected" {% endif %} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label for="user_region"><font class="a-red">*</font> Регион</label>
        <select class="select-2 validate[required]" name="user_region" id="user_region">
        	<option value="0">Выберите из списка</option>
        	{% for key, value in regions %}
            <option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label for="user_city"><font class="a-red">*</font> Населенный пункт</label>
        <select class="select-2 validate[required]" name="user_city" id="user_city">
        	<option value="0">Выберите регион...</option>
        </select>
    </div>
    <div class="a-row">
        <label for="user_avatar">Аватар, фото или логотип</label>
        <input type="file" name="user_avatar" id="user_avatar" />
    </div>
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-edit"></i> Ваши контакты</span>
        </div>
    </div>
    <div class="a-mess-yellow">
    	Контактная информация будет отображатся возле ваших объявлений, акций, анонсов и т.д.
    </div>
    <div class="a-row">
        <label for="user_contact_phone_1"><font class="a-red">*</font> Контактный телефон 1</label>
        <input class="validate[required, custom[phone]]" type="text" name="user_contact_phone[]" id="user_contact_phone_1" />
    </div>
    <div class="a-row">
        <label for="user_contact_phone_2">Контактный телефон 2</label>
        <input type="text" name="user_contact_phone[]" id="user_contact_phone_2" />
    </div>
    <div class="a-row">
        <label for="user_contact_phone_3">Контактный телефон 3</label>
        <input type="text" name="user_contact_phone[]" id="user_contact_phone_3" />
    </div>
    <div class="a-row">
        <label for="user_icq">ICQ</label>
        <input type="text" name="user_icq" id="user_icq" />
    </div>
    <div class="a-row">
        <label for="user_skype">Skype</label>
        <input type="text" name="user_skype" id="user_skype" />
    </div>
    <div class="a-row">
        <label>&nbsp;</label>
        <div class="qaptcha"></div>
    </div>
    <div class="a-row a-row-bottom">
    	<div class="form-loader display-none">
        	<i class="load"></i>
            Загрузка...
        </div>
        
        <input value="Регистрация" type="submit" class="a-btn-green a-float-right" />
    </div>
</form>
        
{% endblock %}
