{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block content %}

<div style="width:700px">

<h1 class="n-form-title">
    <span>Обратная связь</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form id="registration-form" class="n-add-form validation" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/feedback_ajax">
    <div class="a-row">
        <label for="user_name"><font class="a-red">*</font> Ваше имя</label>
        <input class="validate[required, minSize[4]]" type="text" name="user_name" id="user_name" />
    </div>
    <div class="a-row">
        <label for="user_email"><font class="a-red">*</font>E-mail адрес</label>
        <input class="validate[required, custom[email]]" type="text" name="user_email" id="user_email" />
    </div>
    <div class="a-row">
        <label for="user_passw"><font class="a-red">*</font> Текст сообщения</label>
        <textarea name="message" class="validate[required, minSize[6]]"></textarea>
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
        
        <input value="Отправить" type="submit" class="a-btn-green a-float-right" />
    </div>
</form>
</div>
        
{% endblock %}
