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
<div style="width:700px">
<h1 class="n-form-title">
    <span>Редактировать зуботехническую услугу</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/lab/edit_ajax-{{data.lab_id}}">
	<div class="a-row">
        <label><font class="a-red">*</font> Название лаборатории или Ваше Ф.И.О.</label>
        <input value="{{data.user_name}}" type="text" class="validate[required]" name="user_name" id="user_name" />
    </div>
    
    <div class="a-row">
        <label><font class="a-red">*</font> Виды работ</label>
        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id" name="categ_id[]">
            {% for key, value in categs %}
            	<option {% if key in data.categ_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Регион предоставления услуг</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option {% if key == data.region_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Населенный пункт</label>
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
        <label>Описание предоставляемых услуг</label>
        <textarea class="autosize" maxlength="3000" name="content">{{data.content|raw}}</textarea>
    </div>
     <div class="a-row">
        <label>Вложение <span class="a-form-descr">прайс-лист DOC или PDF</span></label>
        <input type="file" title="{{data.attach}}" name="attachment" id="attachment" />
        <input type="hidden" name="attach" value="{{data.attach}}" />
    </div>
    <div class="a-row">
        <label>Фотографии</label>
        
        <ul class="uploader" id="uploader">
        	{% for i in images%}
            <li class="image-added">
            	<input type="hidden" value="{{i.image_id}}" name="images[]"/>
            	<img src="/uploads/images/labs/80x100/{{i.url_full}}" alt="{{i.image_id}}"/>
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
</div>
{% endblock %}