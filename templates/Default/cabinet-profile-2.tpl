{% extends "index.tpl" %}

{% block search %}
    <form class="navi-search" method="get" action="/articles/search">
        <input value="{% if route.values.search != ''%}{{route.values.search}}{% endif %}" id="search-input" name="q" placeholder="Поиск по статьям..." type="text" />
        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
    </form>
{% endblock %}

{% block content %}

<div class="a-row a-offset-1">
    <div class="a-cols-4">
    	{{menu|raw}}
    </div>
    <div class="a-cols-1">
        <!--<h1 class="n-form-title">
            <span>Редактирование личной информации</span>
            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        </h1>-->
        
        <ul class="idTabs a-clear">
            <li>
                <a href="#cabinet-default">ОСНОВНАЯ ИНФОРМАЦИЯ</a>
            </li>
            <li>
                <a href="#cabinet-contacts">ВАШИ КОНТАКТЫ</a>
            </li>
            <li>
                <a href="#cabinet-passw">Сменить пароль</a>
            </li>
        </ul>
        <form class="n-edit-form validation" method="post" enctype="multipart/form-data" action="">
        <div id="cabinet-default">
        	<div class="a-row">
                <label for="user_name"><font class="a-red">*</font> Ваше имя или ник</label>
                <input value="{{data.name}}" class="validate[required, minSize[4]]" type="text" name="user_name" id="user_name" />
            </div>
            <div class="a-row">
                <label for="user_email"><font class="a-red">*</font> Действующий E-mail адрес</label>
                <input value="{{data.email}}" class="validate[required, custom[email]]" type="text" name="user_email" id="user_email" />
            </div>
            <div class="a-row">
                <label for="user_region"><font class="a-red">*</font> Регион</label>
                <select class="select-2 validate[required]" name="user_region" id="user_region">
                    <option value="0">Выберите из списка</option>
                    {% for key, value in regions %}
                    <option {% if key == data.region_id %} selected="selected" {% endif %} value="{{key}}">{{value}}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label for="user_city"><font class="a-red">*</font> Населенный пункт</label>
                <select class="select-2 validate[required]" name="user_city" id="user_city">
                    <option value="0">Выберите регион...</option>
                    {% for key, value in cities %}
                    <option {% if key == data.city_id %} selected="selected" {% endif %} value="{{key}}">{{value}}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label for="user_avatar">Аватар, фото или логотип</label>
                <input title="{{data.avatar_full}}" type="file" name="user_avatar" id="user_avatar" />
                <input type="hidden" name="this_avatar" value="{{data.avatar}}"  />
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Сохранить" />
            </div>
        </div>
        <div id="cabinet-contacts">
        	<div class="a-row">
                <label for="user_contact_phone_1"><font class="a-red">*</font> Контактный телефон 1</label>
                <input value="{{data.phones.0}}" class="validate[required, custom[phone]]" type="text" name="user_contact_phone[]" id="user_contact_phone_1" />
            </div>
            <div class="a-row">
                <label for="user_contact_phone_2">Контактный телефон 2</label>
                <input value="{{data.phones.1}}" type="text" name="user_contact_phone[]" id="user_contact_phone_2" />
            </div>
            <div class="a-row">
                <label for="user_contact_phone_3">Контактный телефон 3</label>
                <input value="{{data.phones.2}}" type="text" name="user_contact_phone[]" id="user_contact_phone_3" />
            </div>
            <div class="a-row">
                <label for="user_icq">ICQ</label>
                <input value="{{data.icq}}" type="text" name="user_icq" id="user_icq" />
            </div>
            <div class="a-row">
                <label for="user_skype">Skype</label>
                <input value="{{data.skype}}" type="text" name="user_skype" id="user_skype" />
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Сохранить" />
            </div>
        </div>
        </form>
        
        <div id="cabinet-passw">
        	<form class="n-edit-form validation" method="post" action="" >
            	<div class="a-row">
                    <label for="passw"><font class="a-red">*</font> Текущий пароль</label>
                    <input class="validate[require, minSize[6]]" type="password" name="passw" id="passw" />
                </div>
                <div class="a-row">
                    <label for="new_passw"><font class="a-red">*</font> Новый пароль</label>
                    <input class="validate[require, minSize[6]]" type="password" name="new_passw" id="new_passw" />
                </div>
                <div class="a-row">
                    <label for="new_passw_2"><font class="a-red">*</font> Повторите новый пароль</label>
                    <input class="validate[require, equals[new_passw]]" type="password" name="new_passw_2" id="new_passw_2" />
                </div>
                <div class="a-row">
                    <label>&nbsp;</label>
                    <input class="a-btn-green" type="submit" value="Сохранить" />
                </div>
            </form>
        </div>
    </div>
</div>

{% endblock %}