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
    <span>Редактировать предложение</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/realty/edit_ajax-{{data.realty_id}}">
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id" name="categ_id">
        	<option></option>
            {% for key, value in categs %}
            	<option {%if key == data.categ_id%}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Регион</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option {% if key == data.region_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Город</label>
        <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
            <option value></option>
            {% for key, value in cities %}
            	<option {% if key == data.city_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label>Адрес</label>
        <input value="{{data.address}}" type="text" name="address" id="address" />
    </div>
    <div class="a-row">
        <label>Стоимость</label>
        <input value="{{data.price}}" class="n-price-input" type="text" name="price" id="price" />
        
        <select class="n-currensy-input" name="currency_id" id="currency_id">
            <option value="1">Гривен</option>
        </select>
    </div>
    
    <div class="a-row">
        <label>Описание цены</label>
        <input value="{{data.price_description}}" type="text" name="price_description" id="price_description" />
    </div>
    
    <div class="a-row">
        <label><font class="a-red">*</font> Заголовок</label>
        <input maxlength="70" value="{{data.name}}" class="validate[required]" type="text" name="name" id="name" />
	</div>
    
    <div class="a-row">
        <label><font class="a-red">*</font> Описание</label>
        <textarea class="autosize validate[required]" maxlength="3000" name="content">{{data.content}}</textarea>
    </div>
    <div class="a-row">
        <label>Фотографии</label>
        
        <ul class="uploader" id="uploader">
        	{% for i in images%}
            <li class="image-added">
            	<input type="hidden" value="{{i.image_id}}" name="images[]"/>
            	<img src="/uploads/images/realty/80x100/{{i.url_full}}" alt="{{i.image_id}}"/>
            </li>
            {% endfor %}
            
            {% for i in 0..images_count%}
            <li></li>
            {% endfor %}
        </ul>
    </div>
    <div class="a-row">
        <label>Ссылка на видео с YouTube</label>
        <input value="{{data.video_link}}" type="text" name="video_link" id="video_link" />
    </div>
    
    <div class="a-row">
    	<label>&nbsp;</label>
        <input class="a-btn-green" type="submit" value="Сохранить"  />
    </div>
</form>

{% endblock %}