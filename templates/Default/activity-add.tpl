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
    <span>Добавить новое мероприятие</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>
{% if is_add_access %}
<form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/activity/add_ajax">
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id" name="categ_id[]">
            {% for key, value in categs %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Регион проведения мероприятия</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Город проведения мероприятия</label>
        <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
            <option value></option>
        </select>
    </div>
    <div class="a-row">
        <label> Дата проведения мероприятия</label>
        
        <input placeholder="Дата начала" type="text" name="date_start" id="date_start" class="datepicker-start" />
        <i class="a-icon-calendar"></i>
        <input placeholder="Дата окончания" type="text" name="date_end" id="date_end" class="datepicker-end" />
        <i class="a-icon-calendar"></i>
    </div>
    <div class="a-row">
        <label>Дата по согласованию</label>
        
        <input type="checkbox" name="flag_agreed" id="flag_agreed" value="1" />
    </div>
    <div class="a-row">
    	<div class="form-separator">
        	<span><i class="a-icon-user"></i> Информация о лекторах</span>
        </div>
    </div>
   	<div class="lectors-add">
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
    </div>
   	
    <div class="a-row">
        <a class="a-btn a-float-right" id="add-lector" href="javascript:void(0)"><i class="a-icon-plus"></i> Добавить лектора</a>
    </div>

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
                    Начинайте с темы или названия мероприятия, исключайте из заголовка: ДАТУ, АДРЕС, ИМЯ ЛЕКТОРА, КОВЫЧКИ и т.д.
                </div>
            </div>
            <div class="a-clear">
                <div class="col-1">
                    <span class="n-circle">2</span>
                </div>
                <div class="col-2">
                    Используйте ключевые слова: СТОМАТОЛОГИЧЕСКИЙ, ДЛЯ СТОМАТОЛОГОВ/ЗУБНЫХ ТЕХНИКОВ, ПРАКТИЧЕСКИЕ КУРСЫ, СЕМИНАР и т.д.
                </div>
            </div>
            <div class="a-clear">
                <div class="col-1">
                    <span class="n-circle">3</span>
                </div>
                <div class="col-2">
                   Обязательно указывайте регион: город, населенный пункт
                </div>
            </div>
            
            <p>
               <b>Неправильно:</b> <br /> 
               14.04.2013. Москва, Гостинный двор. Иванов Андрей. 
               Практические курсы "Металлокерамические и цельнокерамические протезы"
            </p>
            
            <b>Правильно:</b> <br /> 
            Металлокерамические  и  цельнокерамические зубные протезы. 
            Двухдневные практические курсы для зубных техников в Москве
        </div>
    </div>
    
    <div class="a-row">
        <label>Описание мероприятия</label>
        <textarea class="autosize" maxlength="3000" name="content"></textarea>
    </div>
    <div class="a-row">
        <label>Вложение <span class="a-form-descr">программа мероприятия DOC или PDF</span></label>
        <input type="file" name="attachment" id="attachment" />
    </div>
    <div class="a-row">
        <label>Ссылка на сайт мероприятия</label>
        <input type="text" name="link" id="link" />
    </div>
    <div class="a-row">
        <label>Логотип или фото организатора</label>
        <input type="file" name="image" id="image" />
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