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
            <form enctype="multipart/form-data" method="post" action="">
                <select class="select-2 category select-as-link" id="category" name="category">
                    <option value="/ads">Все рубрики</option>
                    {% for key, value in categs %}
                    	<option {% if key == parent_id %} selected="selected" {% endif %} value="/ads/categ-{{key}}-{{value|translit}}">{{value}}</option>
                    {% endfor %}
                </select>
                <select class="select-2 sub_category select-as-link" id="sub_category" name="sub_category">
                    <option value="/products/categ-{{parent_id}}">Все разделы</option>
                    {% for key, value in sub_categs %}
                    	<option {% if key == route.values.sub_categ_id %} selected="selected" {% endif %} value="/ads/sub_categ-{{key}}-{{value|translit}}">{{value}}</option>
                    {% endfor %}
                </select>
                <select class="select-2 firms select-as-link" id="firms" name="firms">
                    <option value="0">Все производители</option>
                    {% for key, value in producers %}
                    	<option {% if key == parent_producer %} selected="selected" {% endif %} value="/ads/firm-{{key}}-{{value|translit}}">{{value|raw}}</option>
                    {% endfor %}
                </select>
                <select class="select-2 products select-as-link" id="products" name="products">
                    <option value="0">Все товары</option>
                    {% for key, value in products %}
                    	<option {% if key == route.values.product_id %} selected="selected" {% endif %} value="/ads/product-{{key}}-{{value|translit}}">{{value|raw}}</option>
                    {% endfor %}
                </select>
            </form>
        </div>
        <div id="pagination-container">
        {% for a in ads %}
        
        <div class="n-ad a-clear pagination-block">
            <div class="n-ad-top-info a-clear">
                <div class="col-1">
                    <a href="/ads/sub_categ-{{a.sub_categ_id}}-{{a.categ_name|translit}}">{{a.categ_name}}</a>
                </div>
                <div class="col-2">
                    {{ a.date_add|rusDate }}
                </div>
            </div>
            
            <div class="col-1">
                <img src="/uploads/images/ads/80x100/{% if a.image != '' %}{{a.image}}{% else %}none.jpg{% endif %}" />
            </div>
            <div class="col-2">
                <a href="/ads/{{a.ads_id}}-{{a.product_name|translit}}" class="n-ad-title-price n-ad-title-new ajax-link">
                    {{a.product_name}}
                    <div class="n-ad-description">
                    	{{a.description}}
                    </div>
                </a>
                
                <div class="n-ad-price">{{a.price}} {{a.currency_name}}</div>
                
                {% if a.user_id == user_info.info.user_id %}
                <ul class="n-user-options a-clear">
                    <li>
                        <a class="ajax-link" href="/ads/edit-{{a.ads_id}}"><i class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                    </li>
                    <li>
                        {% if a.flag == 1%}
                        <a href="/ads/flag-{{a.ads_id}}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                        {% else %}
                        <a href="/ads/flag-{{a.ads_id}}-1"><i class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                        {% endif %}
                    </li>
                    <li>
                        <a class="delete-link" href="/ads/delete-{{a.ads_id}}"><i class="a-icon-trash a-icon-gray"></i> Удалить</a>
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
                <div class="n-ad-info">
                    <div class="col-1">
                       <!-- <a class="mtip" title="{{a.phones|join('<br />')}}" href="#">
                        	<span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{a.phones.0}}
                    	</a>-->
                    </div>
                    <div class="col-2">
                        <a class="user-name" data-user_id="{{a.user_id}}" href="/ads/user-{{a.user_id}}-{{a.user_name|translit}}">
                            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{a.user_name}}
                        </a>
                    </div>
                    <div class="col-3">
                        <a class="ajax-link" href="/ads/send-message-{{a.ads_id}}">
                            <span class="navi-bg-blue"><i class="a-icon-envelope a-icon-white"></i></span> Написать автору
                        </a>
                    </div>
                </div>
                {% endif %}
            </div>
        </div>
        
        {% endfor %}
        </div>
        {% if pagination.pages %}
        
        <ul class="a-pagination">
            <li>
                {% if route.values.categ_id > 0 %}
                    <a href="/ads/page-{{ pagination.prev_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">«</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/ads/page-{{ pagination.prev_page }}/tag-{{route.values.tag_id}}-{{route.values.translit}}">«</a>
                {% else %}
                    <a href="/ads/page-{{ pagination.prev_page }}">»</a>
                {% endif %}
            </li>
            {% for p in pagination.pages %}
            <li {% if route.values.page == p.name %} class="active" {% endif %}>
                {% if route.values.categ_id > 0 %}
                    <a href="/ads/{{p.url}}/categ-{{route.values.categ_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% elseif route.values.tag_id > 0 %}
                    <a href="/ads/{{p.url}}/tag-{{route.values.tag_id}}-{{route.values.translit}}">{{p.name}}</a>
                {% else %}
                    <a href="/ads/{{p.url}}">{{p.name}}</a>
                {% endif %}
            </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/ads/page-{{ pagination.next_page }}/categ-{{route.values.categ_id}}-{{route.values.translit}}">»</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/ads/page-{{ pagination.next_page }}/tag-{{route.values.tag_id}}-{{route.values.translit}}">»</a>
                    {% else %}
                        <a href="/ads/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
        
        {% endif %}
        
    </div>
    
    <div class="col-2">
        <a class="navi-btn-orange ajax-link" href="{% if user_info %}/ads/add{% else %}/registration{% endif %}">
            <b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить Б/У товар
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