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
    <span>Добавить акцию к товару</span>
    <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
</h1>

<form style="width:700px" id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data" action="/index.ajax.php?route=/product/add_stock_ajax-{{product_new_id}}">
	<div class="a-row">
        <label><font class="a-red">*</font>Акционная цена</label>
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
        <label>Описание акции</label>
        <textarea class="autosize" maxlength="300" name="content">{{data.content}}</textarea>
    </div>
    <div class="a-row">
        <label><font class="a-red">*</font> Период действия акции</label>
        
        <input value="{{data.date_start}}" placeholder="Дата начала" type="text" name="date_start" id="date_start" class="datepicker-start validate[required]]" />
        <i class="a-icon-calendar"></i>
        <input value="{{data.date_end}}" placeholder="Дата окончания" type="text" name="date_end" id="date_end" class="datepicker-end validate[required]" />
        <i class="a-icon-calendar"></i>
    </div>
	<div class="a-row">
    	<label>&nbsp;</label>
        <input class="a-btn-green" type="submit" value="Сохранить"  />
    </div>
</form>

{% endblock %}