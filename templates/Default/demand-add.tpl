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
    <span>Добавить заявку в спрос</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>
{% if is_add_access %}
<form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/demand/add_ajax">
    <div class="a-row n-title-description">
        <label><font class="a-red">*</font> Заголовок</label>
        <input maxlength="70" class="validate[required]" type="text" name="name" id="name" />
        
        <div class="n-ad-add-desc a-clear">
            <h5>Делайте заголовок эффективным!</h5>
			
            <div class="a-clear">
                <div class="col-1">
                    <span class="n-circle">1</span>
                </div>
                <div class="col-2">
                    Начинайте с ключевого слова: КУПЛЮ, ИЩУ, ИНТЕРЕСУЮСЬ и т.д.
                </div>
            </div>
            <div class="a-clear">
                <div class="col-1">
                    <span class="n-circle">2</span>
                </div>
                <div class="col-2">
                    Используйте ключевые слова: СТОМАТОЛОГИЧЕСКИЙ/ЗУБОТЕХНИЧЕСКИЙ, ДЛЯ СТОМАТОЛОГИИ/ЗУБОТЕХНИКИ и т.д.
                </div>
            </div>
            
            <p>
               <b>Неправильно:</b> <br /> 
               Автоклав б/у на 20 л.
            </p>
            
            <b>Правильно:</b> <br /> 
           	Куплю б/у автоклав для стоматологии объемом 20 л.
        </div>
    </div>
    
    <div class="a-row">
        <label>Описание Вашей заявки</label>
        <textarea class="autosize" maxlength="3000" name="content"></textarea>
    </div>
    <div class="a-row">
        <label>Фотографии</label>
        
        <ul class="uploader" id="uploader">
        	<li></li>
            <li></li>
            <li></li>
            <li class="last"></li>
            <li></li>
            <li></li>
            <li></li>
            <li class="last"></li>
        </ul>
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