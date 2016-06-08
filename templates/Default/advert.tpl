{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block content %}

<div class="n-content">

<h1 class="n-form-title">
    <span>Реклама на Navistom.com</span>
</h1>
<p></p>

<div class="a-mess-yellow">
    По вопросам размещения рекламы, цен и условий сотрудничества <br />
    Вы можете связаться с нами по тел.: <b>+38-044-573-97-73</b> или оправить сообщение, заполнив предлагаемую форму для обратной связи.
</div>

<p>&nbsp;</p>

<h1 class="n-form-title">
    <span>Обратная связь</span>
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
