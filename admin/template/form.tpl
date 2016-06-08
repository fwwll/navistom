{% extends "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	{% if form.title %}
    	<h1 class="ad-title">
            <b>{{form.title}}</b>
            <span>{{form.description|default('<font class="a-req">*</font> поля, обязательные для заполнения')|raw}}</span>
        </h1>
    {% endif %}
    
	{{form.content|raw}}
{% endblock %}

{% block right %}
	<div class="a-group-btn a-margin-right">
        <input name="form-save" id="form-save" class="a-btn-green form-submit" type="submit" value="Сохранить" />
        <input name="form-apply" id="form-apply" class="a-btn-green form-submit" type="submit" value="Применить" />
    </div>
    
    <input name="form-cancel" id="form-cancel" type="submit" class="a-btn" value="Отмена" />
{% endblock %}