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

<div class="navi-row-3">
    <div class="col-1">
        <ul class="navi-categs-list">
            {% for c in categories %}
            <li {% if c.categ_id == route.values.categ_id %} class="active" {% endif %}>
                <a href="/articles/categ-{{c.categ_id}}-{{ c.name|translit }}">{{c.name}}</a>
            </li>
            {% endfor %}
        </ul>
        
        <hr />
            <font class="a-size-16">Метки</font>
        <hr />
        
        <ul class="navi-tags-list">
            {% for t in tags %}
            <li>
                <a href="/articles/tag-{{t.tag_id}}-{{ t.name|translit }}">{{t.name}}</a>
            </li>
            {% endfor %}
        </ul>
    </div>
    <div id="pagination-container" class="col-2">
    	{% if route.values.search %}
        <div class="a-mess">
        	По вашему запросу <b>"{{route.values.search}}"</b>
            {% if count > 0 %}
            	найдено {{count}} запесей
            {% else %}
            	ничего не найдено
            {% endif %}
        </div>
        <p>&nbsp;</p>
        {% endif %}
        
        {% for a in articles %}
        
        <div class="navi-article-box pagination-block">
            <a href="/article/{{a.article_id}}-{{a.name|translit}}" class="navi-article-title ajax-link">{{a.name}}</a>
            <div class="navi-article-info a-clear a-font-smal">
                <div class="a-float-left a-color-gray">
                    {{ a.date_public|rusDate }}&nbsp; | &nbsp;
                    {{a.comments}}<i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
                    {{ a.views }}  <i class="a-icon-eye-open a-icon-gray"></i>
                </div>
                <div class="a-float-right">
                    {% for key, value in a.categs %}
                    	<a href="/articles/categ-{{key}}-{{value|translit}}" class="a-color-gray">{{value}}</a>
                    {% endfor %}
                </div>
            </div>
            <div class="navi-article-row-2 a-clear">
                <div class="a-float-left">
                    <img src="/uploads/images/articles/100x150/{{a.url_full}}" />
                </div>
                <div class="a-float-right">
                    <div id="descr-{{a.article_id}}" class="n-article-descr editable">
                    	{{ a.content_min }}
                    </div>
                    <div class="navi-article-tags a-font-smal a-color-gray">
                        {% for key, value in a.tags %}
                            <a href="/articles/tag-{{key}}-{{value|translit}}">{{value}}</a>
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>
        
        {% else %}
        	<div class="a-mess-yellow">Нет статей</div>
        {% endfor %}
		
        {% if pagination %}
        
        <ul class="a-pagination">
            <li>
            	{% if route.values.categ_id > 0 %}
                	<a href="/articles/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                {% elseif route.values.tag_id > 0 %}
                	<a href="/articles/page-{{ pagination.prev_page }}/tag-{{route.values.tag_id}}-{{route.values.translit}}">«</a>
                {% elseif route.values.date %}
                	<a href="/articles/page-{{ pagination.prev_page }}/archive-{{route.values.date}}">«</a>
                {% elseif route.values.search %}
                	<a href="/articles/page-{{ pagination.prev_page }}/search-{{route.values.search}}">«</a>
                {% else %}
                    <a href="/articles/page-{{ pagination.prev_page }}">»</a>
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
            	{% if route.values.categ_id > 0 %}
                	<a href="/articles/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.tag_id > 0 %}
                	<a href="/articles/{{p.url}}/tag-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.date %}
                	<a href="/articles/{{p.url}}/archive-{{route.values.date}}">{{p.name}}</a>
                {% elseif route.values.search %}
                	<a href="/articles/{{p.url}}/search-{{route.values.search}}">{{p.name}}</a>
                {% else %}
                	<a href="/articles/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
            	{% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/articles/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/articles/page-{{ pagination.next_page }}/tag-{{route.values.tag_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.date %}
                    	<a href="/articles/page-{{ pagination.next_page }}/archive-{{route.values.date}}">»</a>
                    {% elseif route.values.search %}
                    	<a href="/articles/page-{{ pagination.next_page }}/search-{{route.values.search}}">»</a>
                    {% else %}
                        <a href="/articles/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
    </div>
    <div class="col-3">
    	<a class="navi-btn-orange ajax-link" href="{% if user_info %}/article/add{% else %}/registration{% endif %}">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить статью
        </a>
        
        <ul class="idTabs a-clear">
            <li>
                <a href="#articles-vip">VIP</a>
            </li>
            <li>
                <a href="#articles-video">Видео</a>
            </li>
            <li>
                <a href="#articles-archive">Архив</a>
            </li>
        </ul>
        <div id="articles-vip">
            <ul>
            	{% for v in vips %}
                <li class="a-clear">
                    <a class="ajax-link" href="/article/{{v.article_id}}-{{v.name|translit}}">
                        <img src="/uploads/images/articles/50x75/{{v.url_full}}" />
                        {{v.name}}
                    </a>
                </li>
                {% else %}
                    Нет статей
                {% endfor %}
            </ul>
        </div>
        <div id="articles-video">
            <ul>
                {% for v in videos %}
                <li>
                    <a class="ajax-link" href="/article/{{v.article_id}}-{{v.name|translit}}">
                        <div class="navi-video-marker"></div>
                        <img src="/uploads/images/articles/175x250/{{v.url_full}}" />
                        {{v.name}}
                    </a>
                </li>
                {% endfor %}
            </ul>
        </div>
        <div id="articles-archive">
            <ul>
                {% for a in archive %}
                <li>
                    <a href="/articles/archive-{{a.year}}-{{a.month}}">{{a.name}}</a>
                </li>
                {% endfor %}
            </ul>
        </div>
        
        <hr />
        
        {% if banner %}
        <a target="{{banner.target}}" href="{{banner.link}}">
            <img src="/uploads/banners/{{banner.image}}" />
        </a>
        {% endif %}
    </div>
</div>

{% endblock %}