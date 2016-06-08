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
    	<ul class="a-menu a-menu-ver">
            <li>
                <a href="/cabinet">
                    <b><i class="a-icon-list-alt"></i></b> 
                    Новости Navistom.net
                </a>
            </li>
            <li>
                <a href="/cabinet/profile">
                    <b><i class="a-icon-user"></i></b> 
                    Мой профиль
                </a>
            </li>
            <li>
                <a href="/cabinet/messages">
                    <b><span class="a-count">0</span></b>
                    Мои сообщения
                </a>
            </li>
            <li>
                <a href="/cabinet/articles">
                    <b><span class="a-count-green">0</span></b>
                    Мои статьи
                </a>
            </li>
            <li>
                <a href="/cabinet/products">
                    <b><span class="a-count-green">0</span> </b>
                    Мои товары
                </a>
            </li>
            <li>
                <a href="/cabinet/ads">
                    <b><span class="a-count-green">0</span> </b>
                    Мои объявления
                </a>
            </li>
            <li>
                <a href="/cabinet/activity">
                    <b><span class="a-count-green">0</span> </b>
                    Мои мероприятия
                </a>
            </li>
            <li>
                <a href="/cabinet/faq">
                    <b><i class="a-icon-question-sign"></i></b> 
                    Помощь
                </a>
            </li>
        </ul>
    </div>
    <div class="col-1">
        1231231231
    </div>
</div>

{% endblock %}