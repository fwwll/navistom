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

<div class="navi-cabinet a-clear">
	{% block tabs_menu %}
    
    {% endblock %}
    <div class="a-clear"></div>
	<div class="cabinet-left">
    	<ul>
        	<li {% if active == 0 %} class="active" {% endif %}>
            	<a class="profile" href="/cabinet">
                	Профиль
                </a>
            </li>
            <li {% if active == 1 %} class="active" {% endif %}>
            	{% if mess_count > 0%}
            	<span class="mess">{{mess_count}}</span>
                {%endif%}
            	<a class="messages" href="/cabinet/messages">
                	Сообщения
                </a>
            </li>
            <li {% if active == 2 %} class="active" {% endif %}>
            	<a class="materials" href="/cabinet/materials">
                	Материалы
                </a>
            </li>
            <li {% if active == 3 %} class="active" {% endif %}>
            	<a class="faq" href="/cabinet/faq">
                	Помощь
                </a>
            </li>
        </ul>
    </div>
    <div class="cabinet-right">
    	{% block cabinet_content %}
        
        {% endblock %}
    </div>
</div>

{% endblock %}