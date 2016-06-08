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

 <div class="navi-row-2">
    <div class="col-1">
    	<div id="navi-filter" class="default">
            <div class="navi-ads-filter navi-edu-filter">
                <form method="post" action="">
                    <select class="select-2 select-as-link" id="category" name="category">
                        <option value="/labs">Все виды зуботехнических услуг</option>
                        {% for key, value in categs %}
                            <option {% if key == route.values.categ_id %} selected="selected" {% endif %} value="/labs/categ-{{key}}-{{value|translit}}">{{value}}</option>
                        {% endfor %}
                    </select>
                    <select class="select-2 region select-as-link" id="region" name="region">
                        <option value="0">Все регионы</option>
                        {% for key, value in regions %}
                            {% if route.values.categ_id > 0%}
                                <option {% if key == route.values.region_id %} selected="selected" {% endif %} value="/labs/categ-{{route.values.categ_id}}/region-{{key}}-{{value|translit}}">{{value}}</option>
                            {% else %}
                                <option {% if key == route.values.region_id %} selected="selected" {% endif %} value="/labs/region-{{key}}-{{value|translit}}">{{value}}</option>
                            {% endif %}
                        {% endfor %}
                    </select>
                </form>
            </div>
        </div>
        <div id="pagination-container">
        {% for l in labs %}
        	<div class="n-ad a-clear pagination-block">
                <div class="col-1">
                    {% if l.image != '' %}
                        <img src="/uploads/images/labs/80x100/{{l.image}}" />
                    {% else %}
                        <img src="/uploads/images/products/80x100/none.jpg" />
                    {% endif %}
                </div>
                <div class="col-2">
                    <div class="n-ad-top-info">
                        <div class="col-1">
                        {% for key, value in l.categs %}
                        	<a href="/labs/categ-{{key}}-{{value|translit}}">{{value}}</a>
                        {% endfor %}
                        </div>
                        <div class="col-2">
                            {{l.date_add|rusDate}}
                        </div>
                    </div>
                    
                    <a href="/lab/{{l.lab_id}}-{{l.categs|join(' ')|translit}}" class="n-ad-title-price ajax-link">
                        {{l.user_name|capitalize|raw}}
                    </a>
                    {% if l.user_id == user_info.info.user_id %}
                    <ul class="n-user-options a-clear">
                        <li>
                            <a class="ajax-link" href="/lab/edit-{{l.lab_id}}"><i class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if l.flag == 1%}
                            <a href="/lab/flag-{{l.lab_id}}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                            <a href="/lab/flag-{{l.lab_id}}-1"><i class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/lab/delete-{{l.lab_id}}"><i class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        <li class="satus">
                            {% if l.flag_moder == 0 %}
                            <span class="yellow">На модерации</span>
                            {% elseif l.flag == 0 %}
                            <span class="gray">Скрыто</span>
                            {% else %}
                            <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                    {% else %}
                    <div class="n-ad-info n-ad-info-address">
                        <div class="col-1">
                            <!--<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{l.phones.0}}-->
                        </div>
                        <div class="col-2">
                            <a class="user-name" data-user_id="{{l.user_id}}" href="/labs/user-{{l.user_id}}-{{l.user_name|translit}}">
                                <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{l.user_name}}
                            </a>
                        </div>
                        <div class="col-3">
                            <a href="#">
                                <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> {{l.region_name}}
                                {% if l.address %}, {{l.address}} {% endif%}
                            </a>
                        </div>
                    </div>
                    {% endif %}
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
                        <a href="/labs/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/labs/page-{{ pagination.prev_page }}/region-{{route.values.region_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/labs/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}/region-{{route.values.region_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/labs/page-{{ pagination.prev_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">«</a>
                    {% else %}
                        <a href="/labs/page-{{ pagination.prev_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
                {% if route.values.categ_id > 0 %}
                    <a href="/labs/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/labs/{{p.url}}/tag-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/labs/{{p.url}}/user-{{route.values.user_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% else %}
                    <a href="/labs/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/labs/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/labs/page-{{ pagination.next_page }}/region-{{route.values.region_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.region_id > 0 and route.values.categ_id > 0 %}
                        <a href="/labs/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/labs/page-{{ pagination.next_page }}/user_id-{{route.values.user_id}}-{{route.values.translit}}">»</a>
                    {% else %}
                        <a href="/labs/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
        
    </div>
    
    <div class="col-2">
        <a class="navi-btn-orange ajax-link" href="{% if user_info %}/lab/add{% else %}/registration{% endif %}">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить услугу
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