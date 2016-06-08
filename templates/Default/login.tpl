{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block content %}

<h1 class="n-form-title">
    <span>Вход в личный кабинет</span>
</h1>

<div id="ajax-response" class="a-mess-yellow display-none"></div>

<form id="n-aut-form" name="n-aut-form" style="width:400px" class="n-aut-form" action="/index.ajax.php?route=/login_ajax" method="post">
    <div class="a-row">
        <span><i class="a-icon-envelope"></i></span>
        <input placeholder="Введите e-mail..." type="text" name="user_email" />
    </div>
    <div class="a-row">
        <span><i class="a-icon-key"></i></span>
        <input placeholder="Пароль..." type="password" name="user_passw" />
        <div class="a-float-right">
        	<a id="passw-recovery" class="a-color-gray a-font-smal" href="#">Забыли пароль?</a>
        </div>
    </div>
    <div class="a-row a-row-bottom">
    	<div class="form-loader display-none">
        	<i class="load"></i>
            Загрузка...
        </div>
       
        <input value="Вход" type="submit" class="a-btn-green" />
    </div>
</form>
        
{% endblock %}
