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
    <span>Редактировать товар Б/У</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>
<form id="product-add-form" class="n-add-form a-clear validation" method="post" action="/index.ajax.php?route=/ads/edit_ajax-{{data.ads_id}}">
    <div class="a-row">
        <label><font class="a-red">*</font> Рубрика</label>
        <select placeholder="Выберите из списка" class="select-2 validate[required]" id="categ_id" name="categ_id">
            <option value></option>
            {% for key, value in categs %}
            	<option {%if key == data.categ_id%}selected="selected"{% endif %} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Раздел</label>
        <select placeholder="Выберите рубрику" class="select-2 validate[required]" id="sub_categ_id" name="sub_categ_id">
            {% for key, value in sub_categs %}
            	<option {%if key == data.sub_categ_id%}selected="selected"{% endif %} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Производитель</label>
        <select placeholder="Выберите из списка" class="select-2 validate[groupRequired[producer]]" id="producer_id" name="producer_id">
            <option value></option>
            {% for key, value in producers %}
            	<option {%if key == data.producer_id%}selected="selected"{% endif %} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Товар</label>
        <select placeholder="Выберите производителя" class="select-2 validate[groupRequired[product]]" id="product_id" name="product_id">
            <option value></option>
            {% for key, value in products %}
            	<option {%if key == data.product_id%}selected="selected"{% endif %} value="{{key}}">{{value}}</option>
            {% endfor %}
        </select>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font>Стоимость</label>
        <input value="{{data.price}}" class="n-price-input validate[required, min[1]]" type="text" name="price" id="price" />
        
        <select class="n-currensy-input" name="currency_id" id="currency_id">
            <option value="1">Гривен</option>
        </select>
    </div>
    
    <div class="a-row">
        <label>Описание цены</label>
        <input value="{{data.price_description}}" type="text" name="price_description" id="price_description" />
    </div>
    
    <div class="a-row">
        <label>Описание товара</label>
        <textarea class="autosize" maxlength="3000" name="content">{{data.content}}</textarea>
    </div>
    <div class="a-row">
        <label>Фотографии</label>
        
        <ul class="uploader" id="uploader">
        	{% for i in images%}
            <li class="image-added">
            	<input type="hidden" value="{{i.image_id}}" name="images[]"/>
            	<img src="/uploads/images/ads/80x100/{{i.url_full}}" alt="{{i.image_id}}"/>
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