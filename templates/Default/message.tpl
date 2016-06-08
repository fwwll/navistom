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
    <form class="navi-search" method="get" action="">
        <input name="q" placeholder="Поиск..." type="text" />
        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
    </form>
{% endblock %}

{% block content %}

<div class="{{class}}">
	{{message}}
</div>

{% endblock %}