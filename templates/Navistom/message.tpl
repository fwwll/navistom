{% extends "index.tpl" %}

{% block title %}
    {{ meta.meta_title }}
{% endblock %}

{% block meta_description %}
    {{ meta.meta_description }}
{% endblock %}

{% block meta_keys %}
    {{ meta.meta_keys }}
{% endblock %}

{% block content %}

    <form id="global-search" method="get" action="/search">
        <input placeholder="Поиск по сайту..." type="text" name="q" id="global-search-input"/>
        <button id="search-submit" type="submit">Искать</button>
    </form>

    <div class="{{ class }}">
        {{ message }}
    </div>

{% endblock %}