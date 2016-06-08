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
    <form class="navi-search" method="get" action="/products/search">
        <input value="{% if route.values.search != ''%}{{route.values.search}}{% endif %}" id="search-input" name="q" placeholder="Поиск по товарам..." type="text" />
        <a class="navi-search-btn" href="#"><i class="a-icon-search a-icon-gray"></i></a>
    </form>
{% endblock %}

{% block content %}

 <div class="navi-row-2">
    <div class="col-1">
        <div class="navi-ads-filter navi-new-filter">
            <form enctype="multipart/form-data" method="post" action="">
                <select class="select-2 category select-as-link" id="category" name="category">
                    <option value="/products">Все рубрики</option>
                    {% for c in categs %}
                    	<option {% if c.categ_id == parent_id %} selected="selected" {% endif %} value="/products/categ-{{c.categ_id}}-{{c.name|translit}}">{{c.name}}</option>
                    {% endfor %}
                </select>
                <select class="select-2 sub_category select-as-link" id="sub_category" name="sub_category">
                    <option value="/products/categ-{{parent_id}}">Все разделы</option>
                    {% for key, value in sub_categs %}
                    	<option {% if key == route.values.sub_categ_id %} selected="selected" {% endif %} value="/products/sub_categ-{{key}}-{{value|translit}}">{{value}}</option>
                    {% endfor %}
                </select>
                <select class="select-2 firms select-as-link" id="firms" name="firms">
                    <option value="0">Все производители</option>
                    {% for key, value in producers %}
                    	<option {% if key == parent_producer %} selected="selected" {% endif %} value="/products/firm-{{key}}-{{value|translit}}">{{value|raw}}</option>
                    {% endfor %}
                </select>
                <select class="select-2 products select-as-link" id="products" name="products">
                    <option value="0">Все товары</option>
                    {% for key, value in products %}
                    	<option {% if key == route.values.product_id %} selected="selected" {% endif %} value="/products/product-{{key}}-{{value|translit}}">{{value|raw}}</option>
                    {% endfor %}
                </select>
                
                <a href="/products/filter-stocks" class="a-btn-red {% if route.values.filter == 'stocks'%}active{%endif%}">Акционные</a>
            </form>
        </div>
        <div id="pagination-container">
        {% for p in products_new %}
        
        <div class="n-ad a-clear pagination-block">
            <div class="n-ad-top-info a-clear">
                <div class="col-1">
                    <a href="/products/sub_categ-{{p.sub_categ_id}}-{{p.categ_name|translit}}">{{p.categ_name}}</a>
                </div>
                <div class="col-2">
                    {{ p.date_add|rusDate }}
                </div>
            </div>
            
            <div class="col-1">
                <img src="/uploads/images/products/80x100/{% if p.image != '' %}{{p.image}}{% else %}none.jpg{% endif %}" />
            </div>
            <div class="col-2">
                <a href="/product/{{p.product_new_id}}-{{p.product_name|translit}}" class="n-ad-title-price n-ad-title-new ajax-link">
                    {% if p.stock_flag %} <span class="navi-stock-marker">Акция</span> {% endif %}
                    {{p.product_name}}
                    <div class="n-ad-description">
                    	{{p.description}}
                    </div>
                </a>
                
                <div class="n-ad-price">{{p.price}} {{p.currency_name}}</div>
                
                {% if p.user_id == user_info.info.user_id %}
                <ul class="n-user-options a-clear">
                    <li>
                        <a class="ajax-link" href="/product/edit-{{p.product_new_id}}"><i class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                    </li>
                    <li>
                        {% if p.flag == 1%}
                        <a href="/product/flag-{{p.product_new_id}}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                        {% else %}
                        <a href="/product/flag-{{p.product_new_id}}-1"><i class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                        {% endif %}
                    </li>
                    <li>
                        <a class="delete-link" href="/product/delete-{{p.product_new_id}}"><i class="a-icon-trash a-icon-gray"></i> Удалить</a>
                    </li>
                    {% if is_stock_add_access %}
                    <li>
                    	{% if p.stock_flag %}
                         <a class="ajax-link" href="/product/edit_stock-{{p.product_new_id}}"><i class="a-icon-star a-icon-gray"></i> Редактировать акцию к товару</a>
                        {% else %}
                        <a class="ajax-link" href="/product/add_stock-{{p.product_new_id}}"><i class="a-icon-star a-icon-gray"></i> Добавить акцию к товару</a>
                    	{% endif %}
                    </li>
                    <li class="satus">
                    	{% if p.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                        {% elseif p.flag == 0 %}
                        <span class="gray">Скрыто</span>
                        {% else %}
                        <span class="green">Опубликовано</span>
                        {% endif %}
                    </li>
                    {% endif %}
                </ul>
                {% else %}
                <div class="n-ad-info">
                    <div class="col-1">
                    	<a class="user-name" data-user_id="{{p.user_id}}" href="/products/user-{{p.user_id}}-{{p.user_name|translit}}">
                            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{p.user_name}}
                        </a>
                        <!--<a class="mtip" title="{{p.phones|join('<br />')}}" href="#">
                        	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{p.phones.0}}
                    	</a>-->
                    </div>
                    <div class="col-2">
                        
                    </div>
                    <div class="col-3">
                    	
                        <a class="ajax-link" href="/product/send-message-{{p.product_new_id}}">
                            <span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
                        </a>
                        
                    </div>
                </div>
                {% endif %}
            </div>
        </div>
        {% else %}
        
        {% endfor %}
        </div>
        {% if pagination.pages %}
        
        <ul class="a-pagination">
            <li>
                {% if pagination.prev_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/products/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.sub_categ_id > 0 %}
                        <a href="/products/page-{{ pagination.prev_page }}/sub_categ-{{route.values.tag_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/products/page-{{ pagination.prev_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.producer_id > 0 %}
                    	<a href="/products/page-{{ pagination.prev_page }}/firm-{{route.values.producer_id}}-{{route.values.translit}}">«</a>
                    {% elseif route.values.product_id > 0 %}
                    	<a href="/products/page-{{ pagination.prev_page }}/product-{{route.values.product_id}}-{{route.values.translit}}">«</a>
                    {% else %}
                        <a href="/products/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
                {% if route.values.categ_id > 0 %}
                    <a href="/products/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.sub_categ_id > 0 %}
                    <a href="/products/{{p.url}}/sub_categ-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/products/{{p.url}}/user-{{route.values.user_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.producer_id > 0 %}
                    <a href="/products/{{p.url}}/firm-{{route.values.producer_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.product_id > 0 %}
                    <a href="/products/{{p.url}}/product-{{route.values.product_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% else %}
                    <a href="/products/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/products/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.sub_categ_id > 0 %}
                        <a href="/products/page-{{ pagination.next_page }}/sub_categ-{{route.values.tag_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.user_id > 0 %}
                    	<a href="/products/page-{{ pagination.next_page }}/user-{{route.values.user_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.producer_id > 0 %}
                    	<a href="/products/page-{{ pagination.next_page }}/firm-{{route.values.producer_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.product_id > 0 %}
                    	<a href="/products/page-{{ pagination.next_page }}/product-{{route.values.product_id}}-{{route.values.translit}}">»</a>
                    {% else %}
                        <a href="/products/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
        
    </div>
    
    <div class="col-2">
        <a class="navi-btn-orange ajax-link" href="{% if user_info %}/product/add{% else %}/registration{% endif %}">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить товар
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