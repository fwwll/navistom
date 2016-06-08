{% extends "cabinet-index.tpl" %}

{% block tabs_menu %}
    <ul class="cabinet-tabs-menu a-clear">
        <li>
            <a href="/cabinet">Мой аккаунт</a>
        </li>
        <li>
            <a href="/cabinet/profile/edit">Редактировать профиль</a>
        </li>
        <li class="active">
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
    <form class="n-edit-form validation" method="post" action="">
        <div class="a-row">
            <label for="passw"><font class="a-red">*</font> Текущий пароль</label>
            <input class="validate[require, minSize[6]]" type="password" name="passw" id="passw"/>
        </div>
        <div class="a-row">
            <label for="new_passw"><font class="a-red">*</font> Новый пароль</label>
            <input class="validate[require, minSize[6]]" type="password" name="new_passw" id="new_passw"/>
        </div>
        <div class="a-row">
            <label for="new_passw_2"><font class="a-red">*</font> Повторите новый пароль</label>
            <input class="validate[require, equals[new_passw]]" type="password" name="new_passw_2" id="new_passw_2"/>
        </div>
        <div class="a-row">
            <label>&nbsp;</label>
            <input class="a-btn-green" type="submit" value="Сохранить"/>
        </div>
    </form>
{% endblock %}