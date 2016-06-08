{% extends "cabinet-index.tpl" %}

{% block tabs_menu %}
    <ul class="cabinet-tabs-menu a-clear">
        <li>
            <a href="/cabinet">Мой аккаунт</a>
        </li>
        <li class="active">
            <a href="/cabinet/profile/edit">Редактировать профиль</a>
        </li>
        <li>
            <a href="/cabinet/profile/passw">Сменить пароль</a>
        </li>
        <li>
            <a href="/cabinet/profile/exchanges">Мой курс валют</a>
        </li>
        {% if turnSubscribe %}
            <li>
                <a href="/cabinet/profile/subscribe">Управление подпиской</a>
            </li>
        {% endif %}
    </ul>
{% endblock %}

{% block cabinet_content %}

    {% if complete.message %}
        {% if complete.succes %}
            <div class="a-mess-green">
                {{ complete.message }}
            </div>
        {% else %}
            <div class="a-mess-yellow">
                {{ complete.message }}
            </div>
        {% endif %}
    {% endif %}


    <form class="n-edit-form validation" method="post" enctype="multipart/form-data" action="">
        <div class="a-row">
            <label for="user_name"><font class="a-red">*</font> Ваше имя или ник</label>
            <input value="{{ data.name }}" class="validate[required, minSize[4]]" type="text" name="user_name"
                   id="user_name"/>
        </div>
        <div class="a-row">
            <label for="user_email"><font class="a-red">*</font> Действующий E-mail адрес</label>
            <input value="{{ data.email }}" class="validate[required, custom[email]]" type="text" name="user_email"
                   id="user_email"/>
        </div>
        <div class="a-row">
            <label for="user_region"><font class="a-red">*</font> Область</label>
            <select class="select-2 validate[required]" name="user_region" id="user_region">
                <option value="0">Выберите из списка</option>
                {% for key, value in regions %}
                    <option {% if key == data.region_id %} selected="selected" {% endif %}
                            value="{{ key }}">{{ value }}</option>
                {% endfor %}
            </select>
        </div>
        <div class="a-row">
            <label for="user_city"><font class="a-red">*</font> Населенный пункт</label>
            <select class="select-2 validate[required]" name="user_city" id="user_city">
                <option value="0">Выберите область...</option>
                {% for key, value in cities %}
                    <option {% if key == data.city_id %} selected="selected" {% endif %}
                            value="{{ key }}">{{ value }}</option>
                {% endfor %}
            </select>
        </div>
        <div class="a-row">
            <label for="user_avatar">Аватар, фото или логотип</label>
            <input title="{{ data.avatar_full }}" type="file" name="user_avatar" id="user_avatar"/>
            <input type="hidden" name="this_avatar" value="{{ data.avatar }}"/>
        </div>
        <div class="a-row">
            <label for="user_contact_phone_1"><font class="a-red">*</font> Контактные телефоны</label>
            <input type="text" name="user_contact_phone" value="{{ data.contact_phones }}"
                   class="phones-input validate[required]"/>
        </div>
        <div class="a-row">
            <label for="site">Ссылка на Ваш сайт</label>
            <input value="{{ data.site }}" type="text" name="site" id="site"/>
        </div>
        <div class="a-row">
            <label for="user_icq">ICQ</label>
            <input value="{{ data.icq }}" type="text" name="user_icq" id="user_icq"/>
        </div>
        <div class="a-row">
            <label for="user_skype">Skype</label>
            <input value="{{ data.skype }}" type="text" name="user_skype" id="user_skype"/>
        </div>
        <div class="a-row">
            <label>&nbsp;</label>
            <input class="a-btn-green" type="submit" value="Сохранить"/>
        </div>
    </form>
{% endblock %}