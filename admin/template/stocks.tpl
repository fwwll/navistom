{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
{% endblock %}

{% block right %}
	<a href="/admin/stock/add" class="a-btn-green">Добавить акцию</a>
{% endblock %}