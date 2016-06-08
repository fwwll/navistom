{% extends "index.tpl" %}

{% block title %}
	{{meta.meta_title}}
{% endblock %}

{% block meta_description %}
	{{meta.meta_description}}
{% endblock %}

{% block meta_keys %}
	{{meta.meta_keys}}
{% endblock %}

{% block search %}
    <form class="navi-search" method="get" action="/articles/search">
        <input value="{% if route.values.search != ''%}{{route.values.search}}{% endif %}" id="search-input" name="q" placeholder="Поиск по статьям..." type="text" />
        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
    </form>
{% endblock %}

{% block content %}

<div class="navi-row-2">
    <div id="pagination-container" class="col-2">
    	{{menu|raw}}
    </div>
    <div class="col-1">
        {% for d in dialogs %}
        	<a href="/cabinet/dialog-{{d.from_id}}-{{d.resource_id}}-{{d.section_id}}-{{d.status}}" class="ajax-link n-dialog a-clear {% if d.status == 0%}n-dialog-no-view{% endif%}">
            	<img src="/uploads/users/avatars/tumb2/{{d.avatar}}" />
                <div class="n-dialog-user">
                	<h5>{{d.name}}</h5>
                    {{d.message}}
                </div>
                <div class="n-dialog-info">
                	<span class="a-color-gray a-font-smal">{{d.section_name}}</span>
                	<div>{{d.info.name}}</div>
                    <span class="a-color-gray a-font-smal">{{d.info.description}}</span>
                </div>
            </a>
        {% else %}
        
        {% endfor %}
    </div>
</div>

{% endblock %}