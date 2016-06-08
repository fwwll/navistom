{% extends "index.tpl" %}

{% block title %}
    {{ meta.meta_title }}
{% endblock %}

{% block meta_description %}
    {{ meta.meta_description }}
{% endblock %}

{% block meta_keys %}
    {{ meta.meta_keys }}
{% endblock %}

{% block content %}

    <div class="{{ class }}">
        {{ message }}
    </div>

    <div id="mess-auth-form">
        <form name="n-aut-form" style="width:400px" class="n-aut-form" action="/login" method="post">
            <h1 class="n-form-title">
                <span>Вход в личный кабинет</span>
            </h1>

            <div class="a-row">
                <span><i class="a-icon-envelope"></i></span>
                <input placeholder="Введите e-mail..." type="text" name="user_email"/>
            </div>
            <div class="a-row">
                <span><i class="a-icon-key"></i></span>
                <input placeholder="Пароль..." type="password" name="user_passw"/>
            </div>
            <div class="a-row a-row-bottom">
                <input value="Вход" type="submit" class="a-btn-green"/>
            </div>
        </form>
    </div>

{% endblock %}