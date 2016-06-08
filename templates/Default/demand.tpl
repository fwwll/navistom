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
    <form class="navi-search" method="get" action="/demand/search">
        <input value="{% if route.values.search != ''%}{{route.values.search}}{% endif %}" id="search-input" name="q" placeholder="Поиск по товарам..." type="text" />
        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
    </form>
{% endblock %}

{% block content %}

 <div class="navi-row-2">
    <div class="col-1">
        <div id="pagination-container">
        {% for d in demand %}
        	<div class="n-ad a-clear pagination-block">
                <div class="col-1">
                    {% if d.url_full != '' %}
                        <img src="/uploads/images/demand/80x100/{{d.url_full}}" />
                    {% else %}
                        <img src="/uploads/images/products/80x100/none.jpg" />
                    {% endif %}
                </div>
                <div class="col-2">
                    <div class="n-ad-top-info">
                        <div class="col-1">
                        &nbsp;
                        </div>
                        <div class="col-2">
                            {{d.date_add|rusDate}}
                        </div>
                    </div>
                    
                    <a href="/demand/{{d.demand_id}}-{{d.name|translit}}" class="n-ad-title-price ajax-link">
                        {{d.name|capitalize|raw}}
                    </a>
                    
                    {% if d.user_id == user_info.info.user_id %}
                    <ul class="n-user-options a-clear">
                        <li>
                            <a class="ajax-link" href="/demand/edit-{{d.demand_id}}"><i class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if d.flag == 1%}
                            <a href="/demand/flag-{{d.demand_id}}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                            <a href="/demand/flag-{{d.demand_id}}-1"><i class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/demand/delete-{{d.demand_id}}"><i class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        <li class="satus">
                            {% if d.flag_moder == 0 %}
                            <span class="yellow">На модерации</span>
                            {% elseif d.flag == 0 %}
                            <span class="gray">Скрыто</span>
                            {% else %}
                            <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                    {% else %}
                    <div class="n-ad-info">
                        <div class="col-1">
                            <!--<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{d.phones.0}}-->
                        </div>
                        <div class="col-2">
                            <a class="user-name" data-user_id="{{d.user_id}}" href="/demand/user-{{d.user_id}}-{{d.user_name|translit}}">
                                <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{d.user_name}}
                            </a>
                        </div>
                        <div class="col-3">
                            <a class="ajax-link" href="/demand/send-message-{{d.demand_id}}">
                                <span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
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
                {% if route.values.categ_id > 0 %}
                    <a href="/demand/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/demand/page-{{ pagination.prev_page }}/tag-{{route.values.tag_id}}-{{route.values.translit}}">«</a>
                {% else %}
                    <a href="/demand/page-{{ pagination.prev_page }}">»</a>
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
                {% if route.values.categ_id > 0 %}
                    <a href="/demand/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/demand/{{p.url}}/tag-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% else %}
                    <a href="/demand/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/demand/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/demand/page-{{ pagination.next_page }}/tag-{{route.values.tag_id}}-{{route.values.translit}}">»</a>
                    {% else %}
                        <a href="/demand/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
        
    </div>
    
    <div class="col-2">
        <a class="navi-btn-orange ajax-link" href="{% if user_info %}/demand/add{% else %}/registration{% endif %}">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить заявку
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