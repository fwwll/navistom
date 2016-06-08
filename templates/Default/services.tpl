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
    <form class="navi-search" method="get" action="/services/search">
        <input value="{% if route.values.search != ''%}{{route.values.search}}{% endif %}" id="search-input" name="q" placeholder="Поиск сервиса..." type="text" />
        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
    </form>
{% endblock %}

{% block content %}

 <div class="navi-row-2">
    <div class="col-1">
    	<div id="navi-filter" class="default">
            <div class="navi-ads-filter navi-edu-filter">
                <form method="post" action="">
                    <select class="select-2 select-as-link" id="category" name="category">
                        <option value="/services">Все рубрики</option>
                        {% for key, value in categs %}
                            <option {% if key == route.values.categ_id %} selected="selected" {% endif %} value="/services/categ-{{key}}-{{value|translit}}">{{value}}</option>
                        {% endfor %}
                    </select>
                    <select class="select-2 region select-as-link" id="region" name="region">
                        <option value="0">Все города</option>
                        {% for key, value in cities %}
                            {% if route.values.categ_id > 0%}
                                <option {% if key == route.values.city_id %} selected="selected" {% endif %} value="/services/categ-{{route.values.categ_id}}/city-{{key}}-{{value|translit}}">{{value}}</option>
                            {% else %}
                                <option {% if key == route.values.city_id %} selected="selected" {% endif %} value="/services/city-{{key}}-{{value|translit}}">{{value}}</option>
                            {% endif %}
                        {% endfor %}
                    </select>
                </form>
            </div>
        </div>
        <div id="pagination-container">
        {% for s in services %}
        	<div class="n-ad a-clear pagination-block">
                <div class="col-1">
                    {% if s.url_full != '' %}
                        <img src="/uploads/images/services/80x100/{{s.url_full}}" />
                    {% else %}
                        <img src="/uploads/images/products/80x100/none.jpg" />
                    {% endif %}
                </div>
                <div class="col-2">
                    <div class="n-ad-top-info">
                        <div class="col-1">
                        {% for key, value in s.categs %}
                        	<a href="/services/categ-{{key}}-{{value|translit}}">{{value}}</a>
                        {% endfor %}
                        </div>
                        <div class="col-2">
                            {{s.date_add|rusDate}}
                        </div>
                    </div>
                    
                    <a href="/service/{{s.service_id}}-{{s.name|translit}}" class="n-ad-title-price ajax-link">
                        {{s.name}}
                    </a>
                    
                    {% if s.user_id == user_info.info.user_id %}
                    <ul class="n-user-options a-clear">
                        <li>
                            <a class="ajax-link" href="/service/edit-{{s.service_id}}"><i class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                           {% if s.flag == 1%}
                            <a href="/service/flag-{{s.service_id}}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                            <a href="/service/flag-{{s.service_id}}-1"><i class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/service/delete-{{s.service_id}}"><i class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        <li class="satus">
                            {% if s.flag_moder == 0 %}
                            <span class="yellow">На модерации</span>
                            {% elseif s.flag == 0 %}
                            <span class="gray">Скрыто</span>
                            {% else %}
                            <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                    {% else %}
                    <div class="n-ad-info n-ad-info-address">
                        <div class="col-1">
                            <!--<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{s.phones.0}}-->
                        </div>
                        <div class="col-2">
                            <a class="user-name" data-user_id="{{s.user_id}}" href="/services/user-{{s.user_id}}-{{s.user_name|translit}}">
                                <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{s.user_name}}
                            </a>
                        </div>
                        <div class="col-3">
                            <a href="#">
                                <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> г. {{s.city_name}}{% if s.address %}, {{s.address}}{% endif %}
                            </a>
                        </div>
                    </div>
                    {%endif%}
                </div>
            </div>
        {% else %}
            <div class="a-mess-yeloww">Нет контента для отображения</div>
        {% endfor %}
        </div>
        
        {% if pagination.pages %}
        
        <ul class="a-pagination">
            <li>
                {% if pagination.prev_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/services/page-{{ pagination.prev_page }}/city-{{route.values.city_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/services/page-{{ pagination.prev_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">«</a>
                    {% else %}
                        <a href="/services/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
                {% if route.values.categ_id > 0 %}
                    <a href="/services/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/services/{{p.url}}/tag-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                % elseif route.values.user_id > 0 %}
                    <a href="/services/{{p.url}}/user-{{route.values.user_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% else %}
                    <a href="/services/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/services/page-{{ pagination.next_page }}/city-{{route.values.city_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/services/page-{{ pagination.next_page }}/user_id-{{route.values.user_id}}-{{route.values.translit}}">»</a>
                    {% else %}
                        <a href="/services/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
        
    </div>
    
    <div class="col-2">
        <a class="navi-btn-orange ajax-link" href="{% if user_info %}/service/add{% else %}/registration{% endif %}">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить сервис
        </a>
        
        <hr />
        
         {% if banner %}
        <a target="{{banner.target}}" href="{{banner.link}}">
            <img src="/uploads/banners/{{banner.image}}" />
        </a>
        {% endif %}
    </div>
</div>

{% endblock %}