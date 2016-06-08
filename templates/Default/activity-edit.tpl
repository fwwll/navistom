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
    <span>Редактировать мероприятие</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form style="width:700px" id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/activity/edit_ajax-{{data.activity_id}}">
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id" name="categ_id[]">
            {% for key, value in categs %}
            	<option {% if key in data.categ_id %}selected="selected"{%endif%} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Регион проведения мероприятия</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option {%if key == data.region_id%}selected="selected"{% endif %} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Город проведения мероприятия</label>
        <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
            {% for key, value in cities %}
            	<option {%if key == data.city_id%}selected="selected"{% endif %} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label> Дата проведения мероприятия</label>
        
        <input value="{{data.date_start}}" placeholder="Дата начала" type="text" name="date_start" id="date_start" class="datepicker-start" />
        <i class="a-icon-calendar"></i>
        <input value="{{data.date_end}}" placeholder="Дата окончания" type="text" name="date_end" id="date_end" class="datepicker-end" />
        <i class="a-icon-calendar"></i>
    </div>
    <div class="a-row">
        <label>Дата по согласованию</label>
        
        <input {% if data.flag_agreed == 1%} checked="checked" {%endif%} type="checkbox" name="flag_agreed" id="flag_agreed" value="1" />
    </div>
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-user"></i> Информация о лекторах</span>
        </div>
    </div>
   	<div class="lectors-add">
    	{% for l in lectors %}
        
        <div class="a-row">
            <label><font class="a-red">*</font> Ф.И.О. лектора</label>
            <input value="{{l.name}}" class="validate[required]" type="text" name="lector_name[{{loop.index - 1}}]" id="lector_name_{{loop.index - 1}}" />
        </div>
        <div class="a-row">
            <label><font class="a-red">*</font> Фото лектора</label>
            <input title="uploads/images/activity/lectors/{{l.image}}" class="validate[groupRequired[images_lectors_{{loop.index - 1}}]]" type="file" name="lector_image[{{loop.index - 1}}]" id="lector_image_{{loop.index - 1}}" />
        	<input class="validate[groupRequired[images_lectors_{{loop.index - 1}}]]" type="hidden" name="this_lector_image[{{loop.index - 1}}]" value="{{l.image}}"  />
        </div>
        <div class="a-row">
            <label><font class="a-red">*</font> О лекторе</label>
            <textarea class="validate[required]" name="lector_description[{{loop.index - 1}}]" id="lector_description_{{loop.index - 1}}">{{l.description}}</textarea>
        </div>
        <hr class="hr-min" />
        {% else %}
        <div class="a-row">
            <label><font class="a-red">*</font> Ф.И.О. лектора</label>
            <input class="validate[required]" type="text" name="lector_name[0]" id="lector_name_0" />
        </div>
        <div class="a-row">
            <label><font class="a-red">*</font> Фото лектора</label>
            <input class="validate[required]" type="file" name="lector_image[0]" id="lector_image_0" />
        </div>
        <div class="a-row">
            <label><font class="a-red">*</font> О лекторе</label>
            <textarea class="validate[required]" name="lector_description[0]" id="lector_description_0"></textarea>
        </div>
        <hr class="hr-min" />
        {% endfor %}
    </div>
   	
    <div class="a-row">
        <a class="a-btn a-float-right" id="add-lector" href="javascript:void(0)"><i class="a-icon-plus"></i> Добавить лектора</a>
    </div>
    
    <div class="a-row">
        <label><font class="a-red">*</font> Заголовок</label>
        <input maxlength="70" value="{{data.name}}" class="validate[required]" type="text" name="name" id="name" />
    </div>
    
    <div class="a-row">
        <label>Описание мероприятия</label>
        <textarea class="autosize" maxlength="3000" name="content">{{data.content|raw}}</textarea>
    </div>
    <div class="a-row">
        <label>Вложение <span class="a-form-descr">программа мероприятия DOC или PDF</span></label>
        <input title="{{data.attach}}" type="file" name="attachment" id="attachment" />
        <input type="hidden" name="this_attachment" value="{{data.attach}}" />
    </div>
    <div class="a-row">
        <label>Ссылка на сайт мероприятия</label>
        <input value="{{data.link}}" type="text" name="link" id="link" />
    </div>
    <div class="a-row">
        <label>Логотип или фото организатора</label>
        <input title="uploads/images/activity/80x100/{{data.image}}" type="file" name="image" id="image" />
        <input type="hidden" name="this_image" value="{{data.image}}" />
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