{% extends "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	{% if form.title %}
    	<h1 class="ad-title">
            <b>{{form.title}}</b>
            <span>{{form.description|default('<font class="a-req">*</font> поля, обязательные для заполнения')|raw}}</span>
        </h1>
    {% endif %}
    <form method="post" name="ad-form" action="" class="ad-form a-clear validation">
    {% for c_key, value in countries %}
    	<h3>Курс валют {{value}}</h3>
        
        {% for key, value in currency[c_key] %}
        <div class="a-row">
        	<label>1 {{value.name}}</label>
            <input value="{{exchanges_default[c_key][value.currency_id]}}" class="currency-input" type="text" name="rate[{{c_key}}][{{value.currency_id}}]" />
            <span class="currency-name">{{currency_default[c_key][0].name_min}}</span>
        </div>
        {% endfor %}
    {% endfor %}
    </form>
{% endblock %}

{% block right %}
	<div class="a-group-btn a-margin-right">
        <input name="form-save" id="form-save" class="a-btn-green form-submit" type="submit" value="Сохранить" />
        <input name="form-apply" id="form-apply" class="a-btn-green form-submit" type="submit" value="Применить" />
    </div>
    
    <input name="form-cancel" id="form-cancel" type="submit" class="a-btn" value="Отмена" />
{% endblock %}