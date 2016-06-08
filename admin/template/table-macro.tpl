{% extends "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	{% if table.title %}
    	<h1 class="ad-title">
            <b>{{table.title}}</b>
            <span>{{table.description}}</span>
        </h1>
    {% endif %}
    
    {% if table.content > 0 %}
    
		{{table.content|raw}}
        
    {% else %}
        <div class="a-mess-yellow">Нет контента для отображения</div>
    {% endif %}
    
{% endblock %}

{% block right %}
	
{% endblock %}
