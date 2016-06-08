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
    <span>Добавить товар Б/У</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>
<form id="product-add-form" class="n-add-form a-clear validation" method="post" action="/index.ajax.php?route=/ads/add_ajax">
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="categ_id" name="categ_id">
            <option value></option>
            {% for key, value in categs %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Раздел</label>
        <select placeholder="Выберите рубрику" class="select-2 validate[required]" id="sub_categ_id" name="sub_categ_id">
            <option value></option>
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Производитель</label>
        <select placeholder="Выберите из списка" class="select-2 validate[groupRequired[producer]]" id="producer_id" name="producer_id">
            <option value></option>
            {% for key, value in producers %}
            	<option value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
        <div class="a-float-right">
        	<a id="new-producer-add" class="a-color-gray a-font-smal" href="#">Не нашли нужного производителя?</a>
        </div>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Товар</label>
        <select placeholder="Выберите производителя" class="select-2 validate[groupRequired[product]]" id="product_id" name="product_id">
            <option value></option>
        </select>
        <div class="a-float-right">
        	<a id="new-product-add" class="a-color-gray a-font-smal" href="#">Не нашли нужный товар?</a>
        </div>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font>Стоимость</label>
        <input class="n-price-input validate[required, min[1]]" type="text" name="price" id="price" />
        
        <select class="n-currensy-input" name="currency_id" id="currency_id">
            <option value="1">Гривен</option>
        </select>
    </div>
    
    <div class="a-row">
        <label>Описание цены</label>
        <input type="text" name="price_description" id="price_description" />
    </div>
    
    <div class="a-row">
        <label>Описание товара</label>
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
</div>
{% endblock %}