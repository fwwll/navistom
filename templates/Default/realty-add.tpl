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
    <span>Добавить предложение</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>
{% if is_add_access %}
<form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/realty/add_ajax">
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id" name="categ_id">
        	<option></option>
            {% for key, value in categs %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Регион</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id" name="region_id">
            <option value></option>
            {% for key, value in regions %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Город</label>
        <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
            <option value></option>
        </select>
    </div>
    <div class="a-row">
        <label>Адрес</label>
        <input type="text" name="address" id="address" />
    </div>
    <div class="a-row">
        <label>Стоимость</label>
        <input class="n-price-input" type="text" name="price" id="price" />
        
        <select class="n-currensy-input" name="currency_id" id="currency_id">
            <option value="1">Гривен</option>
        </select>
    </div>
    
    <div class="a-row">
        <label>Описание цены</label>
        <input type="text" name="price_description" id="price_description" />
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
                    Начинайте с ключевого слова: СДАМ/ВОЗЬМУ В АРЕНДУ, ПРОДАМ/КУПЛЮ и т.д.
                </div>
            </div>
            <div class="a-clear">
                <div class="col-1">
                    <span class="n-circle">2</span>
                </div>
                <div class="col-2">
                    Используйте ключевые слова: СТОМАТОЛОГИЧЕСКИЙ/ЗУБОТЕХНИЧЕСКИЙ, ДЛЯ СТОМАТОЛОГА/ЗУБНОГО ТЕХНИКА и т.д.
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
               Отдельный кабинет в центре города
            </p>
            
            <b>Правильно:</b> <br /> 
           	Сдам в аренду стоматологический кабинет в центре Киева
        </div>
    </div>
    
    <div class="a-row">
        <label><font class="a-red">*</font> Описание</label>
        <textarea class="autosize validate[required]" maxlength="3000" name="content"></textarea>
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