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

{% block content %}

<div class="navi-row-2">
    <div class="col-1">
        <div class="navi-ads-filter navi-new-filter">
        	<div class="n-work-btns a-group-btn">
            	<a class="a-btn active" href="/work/resume">
                	<b><i class="a-icon-file"></i></b> Резюме
                </a>
                <a class="a-btn" href="/work/vacancy">
                	<b><i class="a-icon-briefcase"></i></b> Вакансии
                </a>
            </div>
            <form method="post" action="">
                <select class="select-2 category select-as-link" id="category" name="category">
                    <option value="0">Все рубрики</option>
                    {% for key, value in categories %}
                    	<option {% if key == route.values.categ_id %} selected="selected" {% endif %} value="/work/resume/categ-{{key}}-{{value|translit}}">{{value}}</option>
                    {% endfor %}
                </select>

                <select class="select-2 region select-as-link" id="region" name="region">
                    <option value="0">Все города</option>
                    {% for key, value in cities %}
                    	{% if route.values.categ_id > 0 %}
                        <option {% if key == route.values.city_id %} selected="selected" {% endif %} value="/work/resume/categ-{{route.values.categ_id}}/city-{{key}}-{{value|translit}}">{{value}}</option>
                        {% else %}
                    	<option {% if key == route.values.city_id %} selected="selected" {% endif %} value="/work/resume/city-{{key}}-{{value|translit}}">{{value}}</option>
                        {% endif %}
                    {% endfor %}
                </select>
                
                <select class="select-2 select-as-link" id="price" name="price">
                    <option value="0">Зарплата</option>
                    <option {% if route.values.max == 1500 %} selected="selected" {% endif %} value="/work/resume/price-0-1500">До 1500 грн.</option>
                    <option {% if route.values.max == 3500 %} selected="selected" {% endif %} value="/work/resume/price-1501-3500">От 1501 до 3500 грн.</option>
                    <option {% if route.values.max == 7000 %} selected="selected" {% endif %} value="/work/resume/price-3501-7000">От 3501 до 7000 грн.</option>
                    <option {% if route.values.max == 15000 %} selected="selected" {% endif %} value="/work/resume/price-7001-15000">От 7001 до 15000 грн.</option>
                    <option {% if route.values.min == 15000 %} selected="selected" {% endif %} value="/work/resume/price-15000-0">Свыше 15000 грн.</option>
                    <option value="/work/resume/price-0-0">Без указания</option>
                </select>
            </form>
        </div>
        
        <div id="pagination-container">
        {% for r in resume %}
        <div class="n-ad a-clear pagination-block">
            <div class="col-1">
            	{% if r.image != '' %}
                	<img src="/uploads/images/work/80x100/{{r.image}}" />
                {% elseif r.avatar != ''%}
                	<img src="/uploads/users/avatars/tumb1/{{r.avatar}}" />
                {% else %}
                
                {% endif %}
            </div>
            <div class="col-2">
                <div class="n-ad-top-info">
                    <div class="col-1">
                        {% for key, value in r.categs %}
                        	<a href="/work/resume/categ-{{key}}-{{value|translit}}">{{value}}</a>&nbsp;&nbsp;
                        {% endfor %}
                    </div>
                    <div class="col-2">
                        {{r.date_add|rusDate}}
                    </div>
                </div>
                
                <a href="/work/resume/{{r.work_id}}-{{r.categs|join('-')|translit}}" class="n-ad-title-price ajax-link">
                	{{r.categs|join(', ')}}
                </a>
                
                <div class="n-ad-price">
                	{% if r.price > 0 %}
                    	{{r.price|number_format(0, '', ' ')}} {{r.currency_name}}
                    {% endif %}
                </div>
                
                {% if r.user_id == user_info.info.user_id %}
                <ul class="n-user-options a-clear">
                    <li>
                        <a class="ajax-link" href="/work/resume/edit-{{r.work_id}}"><i class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                    </li>
                    <li>
                        {% if r.flag == 1%}
                        <a href="/work/resume/flag-{{r.work_id}}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                        {% else %}
                        <a href="/work/resume/flag-{{r.work_id}}-1"><i class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                        {% endif %}
                    </li>
                    <li>
                        <a class="delete-link" href="/work/resume/delete-{{r.work_id}}"><i class="a-icon-trash a-icon-gray"></i> Удалить</a>
                    </li>
                    <li class="satus">
                        {% if r.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                        {% elseif r.flag == 0 %}
                        <span class="gray">Скрыто</span>
                        {% else %}
                        <span class="green">Опубликовано</span>
                        {% endif %}
                    </li>
                </ul>
                {% else %}
                <div class="n-ad-info n-ad-info-region">
                    <div class="col-1">
                        <!--<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{r.phones.0}}-->
                    </div>
                    <div class="col-2">
                        <a class="user-name" data-user_id="{{r.user_id}}" href="/work/resume/user-{{r.user_id}}-{{r.user_name|translit}}">
                            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{r.user_name}} {{r.user_surname}}
                        </a>
                    </div>
                    <div class="col-3">
                        <span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> 
                        {% if route.values.categ_id > 0 %}
                        	<a href="/work/resume/categ-{{route.values.categ_id}}/city-{{r.city_id}}-{{r.city_name|translit}}">{{r.city_name}}</a>
                        {% else %}
                        	<a href="/work/resume/city-{{r.city_id}}-{{r.city_name|translit}}">г. {{r.city_name}}</a>
                        {% endif %}
                    </div>
                </div>
                {% endif %}
            </div>
        </div>
        
        {% else %}
        	<div class="a-mess-yeloww">Резюме нет</div>
        {% endfor %}
        </div>
        
        {% if pagination.pages %}
        
        <ul class="a-pagination">
            <li>
                {% if pagination.prev_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/city-{{route.values.city_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/work/resume/page-{{ pagination.prev_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">«</a>
                    {% else %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
                {% if route.values.categ_id > 0 %}
                    <a href="/work/resume/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/work/resume/{{p.url}}/tag-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.user_id > 0 %}
                    	<a href="/work/resume/{{p.url}}/user-{{route.values.user_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% else %}
                    <a href="/work/resume/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/city-{{route.values.city_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}/city-{{route.values.city_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/work/resume/page-{{ pagination.next_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">»</a>
                    {% else %}
                        <a href="/work/resume/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
        
    </div>
    
    <div class="col-2">
        <a class="navi-btn-orange ajax-link" href="/work/resume/add">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Разместить резюме
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