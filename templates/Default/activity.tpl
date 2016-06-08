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
        <input name="q" placeholder="Поиск по мероприятиям..." type="text" />
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
                    <option value="/activity">Все рубрики</option>
                    {% for key, value in categs %}
                    	<option {% if key == route.values.categ_id %} selected="selected" {% endif %} value="/activity/categ-{{key}}-{{value|translit}}">{{value}}</option>
                    {% endfor %}
                </select>
                <select class="select-2 region select-as-link" id="region" name="region">
                    <option value="0">Место проведения</option>
                    {% for c in cities%}
                    	<option {% if c.city_id == route.values.city_id %} selected="selected" {% endif %} value="/activity/{% if route.values.categ_id == null %}city-{{c.city_id}}-{{c.name|translit}}{% elseif route.values.categ_id > 0 %}categ-{{route.values.categ_id}}/city-{{c.city_id}}-{{c.name|translit}}{% endif %}">{{c.name}} ({{c.count}})</option>
                    {% endfor %}
                </select>
                
                <div class="a-group-btn">
                    <a class="a-btn {% if route.values.sort_by == null %}active{% endif %}" href="/activity{% if route.values.categ_id > 0 and route.values.city_id == null %}/categ-{{route.values.categ_id}}-{{route.values.translit}} {% elseif route.values.categ_id == null and route.values.city_id > 0 %}/city-{{route.values.city_id}}-{{route.values.translit}}{% elseif route.values.categ_id >0 and route.values.city_id > 0 %}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}{% endif %}">Новые</a>
                    <a class="a-btn {% if route.values.sort_by == 'popular' %}active{% endif %}" href="/activity/sort-by-popular{% if route.values.categ_id > 0 and route.values.city_id == null %}/categ-{{route.values.categ_id}}-{{route.values.translit}} {% elseif route.values.categ_id == null and route.values.city_id > 0 %}/city-{{route.values.city_id}}-{{route.values.translit}}{% elseif route.values.categ_id >0 and route.values.city_id > 0 %}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}{% endif %}">Популярные</a>
                    <a class="a-btn {% if route.values.sort_by == 'coming' %}active{% endif %}" href="/activity/sort-by-coming{% if route.values.categ_id > 0 and route.values.city_id == null %}/categ-{{route.values.categ_id}}-{{route.values.translit}} {% elseif route.values.categ_id == null and route.values.city_id > 0 %}/city-{{route.values.city_id}}-{{route.values.translit}}{% elseif route.values.categ_id >0 and route.values.city_id > 0 %}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}{% endif %}">Ближайшие</a>
                </div>
            </form>
        </div>
        </div>
        <div id="pagination-container">
        {% for a in activity %}
        	<div class="n-ad a-clear pagination-block">
                <div class="n-ad-top-info a-clear">
                    <div class="col-1">
                        {% for key, value in a.categs %}
                        	<a href="/activity/categ-{{key}}-{{value|translit}}">{{value}}</a>&nbsp;&nbsp;
                        {% endfor %}
                    </div>
                    <div class="col-2">
                        {{a.date_add|rusDate}}
                    </div>
                </div>
                
                <div class="col-1">
                    <img src="/uploads/images/activity/80x100/{{a.image}}" />
                </div>
                <div class="col-2">
                    <div class="n-ad-date">
                        <span class="navi-bg-gray"><i class="a-icon-calendar a-icon-white"></i></span>
                        {% if a.flag_agreed > 0 %}
                        	по согласованию
                        {% else %}
                        	{{a.date_start|rusDate}}
                            
                            {% if a.date_end != '0000-00-00'%}
                            	- {{a.date_end|rusDate}}
                            {% endif %}
                        {% endif %}
                    </div>
                    <a href="/activity/{{a.activity_id}}-{{a.name|translit}}" class="n-ad-title n-ad-title-edu ajax-link">
                        {{a.name}}
                    </a>
                    
                    {% if a.user_id == user_info.info.user_id %}
                    <ul class="n-user-options a-clear">
                        <li>
                            <a class="ajax-link" href="/activity/edit-{{a.activity_id}}"><i class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if a.flag == 1%}
                            <a href="/activity/flag-{{a.activity_id}}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                            <a href="/activity/flag-{{a.activity_id}}-1"><i class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/activity/delete-{{a.activity_id}}"><i class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        <li class="satus">
                            {% if a.flag_moder == 0 %}
                            <span class="yellow">На модерации</span>
                            {% elseif a.flag == 0 %}
                            <span class="gray">Скрыто</span>
                            {% else %}
                            <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                    {% else %}
                    <div class="n-ad-info-region">
                        <div class="col-1">
                            <!--<a class="mtip" title="{{a.phones|join('<br />')}}" href="#">
                                <span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{a.phones.0}}
                            </a>-->
                        </div>
                        <div class="col-2">
                            <a class="user-name" data-user_id="{{a.user_id}}" href="/activity/user-{{a.user_id}}-{{a.user_name|translit}}">
                                <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{a.user_name}}
                            </a>
                        </div>
                        <div class="col-3">
                            <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> 
                            <a href="/activity/city-{{a.city_id}}-{{a.city_name|translit}}">г. {{a.city_name}}</a>
                        </div>
                    </div>
                    {% endif %}
                </div>
            </div>
        {% else %}
            <div class="a-mess-yeloww">Мероприятий нет</div>
        {% endfor %}
        </div>
        
        {% if pagination.pages %}
        
        <ul class="a-pagination">
            <li>
                {% if pagination.prev_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/activity/page-{{ pagination.prev_page }}/city-{{route.values.city_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}">«</a>
                    {% else %}
                        <a href="/activity/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
                {% if route.values.categ_id > 0 %}
                    <a href="/activity/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/activity/{{p.url}}/tag-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% else %}
                    <a href="/activity/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/activity/page-{{ pagination.next_page }}/city-{{route.values.city_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/activity/page-{{ pagination.next_page }}/user_id-{{route.values.user_id}}">»</a>
                    {% else %}
                        <a href="/activity/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
        
    </div>
    
    <div class="col-2">
        <a class="navi-btn-orange ajax-link" href="{% if user_info %}/activity/add{% else %}/registration{% endif %}">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить мероприятие
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