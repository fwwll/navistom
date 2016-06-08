{% extends "index_new2.tpl" %}
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

    <noindex>
    <div id="left">
    <div class='background_filter' {% if route.values.filter == 'stocks' %} style="height:200px" {% else %} style="height:230px" {% endif %} >
    {% if route.values.filter == 'stocks' %}
        <h4><i class="navs-icon-actziya"></i>Акции</h4>
        <div class="akz"><a title="Добавить Акцию" href="/products/stock"><i class="a-icon-actionss a-icon-gray"></i>
                Добавить акцию</a></div>


    {% else %}
        <h4><i class="navs-icon-new"></i>Продам НОВОЕ</h4>
    {% endif %}
    <div class='row_filter clear'>
        {% if route.values.filter == 'stocks' %}
            <form id="global-search" method="get" action="/{{ route.controller }}/filter-stocks/search">
                <input placeholder="Поиск в разделе АКЦИИ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit"></button>
            </form>
        {% else %}
            <form id="global-search" method="get" action="/{{ route.controller }}/search">
                <input placeholder="Поиск в разделе ПРОДАМ НОВОЕ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit"></button>
            </form>
        {% endif %}

    </div>
    <div></div>
    <div class='category_select'>
        <div class='category_select_left clear'>
            <select class="select-2 filter-select select-as-link" id="category" name="category">
                {% if route.values.filter %}
                    <option value="/products/filter-stocks">Все рубрики в разделе
                        АКЦИИ{% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                {% else %}
                    <option {% if route.values.translit %} {% if route.values.user_id %} value="/products/user-{{ route.values.user_id }}-{{ route.values.translit }}" {% else %} value="/products" {% endif %} {% else %}value="/products" {% endif %}>
                        Все рубрики в ПРОДАМ
                        НОВОЕ {% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                {% endif %}
                {% for c in categs %}
                    {% if route.values.filter %}
                        {% if route.values.user_id %}
                            <option {% if c.categ_id==parent_id %} selected="selected" {% endif %}
                                    value="/products/filter-stocks/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                                - {{ products_new[0].user_name }} ({{ c.count }})
                            </option>
                        {% else %}
                            <option {% if c.categ_id==parent_id %} selected="selected" {% endif %}
                                    value="/products/filter-stocks/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                                ({{ c.count }})
                            </option>
                        {% endif %}
                    {% elseif route.values.is_updates %}
                        <option {% if c.categ_id==parent_id %} selected="selected" {% endif %}
                                value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/updates-1/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                            - {{ products_new[0].user_name }} ({{ c.count }})
                        </option>
                    {% elseif route.values.user_id %}
                        <option {% if c.categ_id==parent_id %} selected="selected" {% endif %}
                                value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                            - {{ products_new[0].user_name }} ({{ c.count }})
                        </option>
                    {% else %}
                        <option {% if c.count==0 %}disabled="disabled"{% endif %} {% if c.categ_id==parent_id %} selected="selected" {% endif %}
                                value="/products/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                            ({{ c.count }})
                        </option>
                    {% endif %}
                {% endfor %}
            </select>
            <select {% if sub_categs==null %} disabled="disabled" {% endif %}
                    class="select-2 filter-select select-as-link" id="sub_category" name="sub_category">
                {% if sub_categs %}
                    {% if route.values.filter %}
                        <option value="/products/filter-stocks/categ-{{ parent_id }}-parent">Все
                            подрубрики {% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                    {% else %}
                        <option value="/products/categ-{{ parent_id }}-parent">Все
                            подрубрики {% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                    {% endif %}
                {% endif %}
                {% for c in sub_categs %}
                    {% if route.values.is_updates %}
                        <option {% if c.categ_id==route.values.sub_categ_id %} selected="selected" {% endif %}
                                value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/updates-1/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                            - {{ products_new[0].user_name }} ({{ c.count }})
                        </option>
                    {% elseif route.values.user_id %}
                        <option {% if c.categ_id==route.values.sub_categ_id %} selected="selected" {% endif %}
                                value="/products{% if route.values.filter %}/filter-stocks{% endif %}/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                            - {{ products_new[0].user_name }} ({{ c.count }})
                        </option>
                    {% else %}
                        {% if route.values.filter %}
                            <option {% if c.categ_id==route.values.sub_categ_id %} selected="selected" {% endif %}
                                    value="/products/filter-stocks/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                                ({{ c.count }})
                            </option>
                        {% else %}
                            <option {% if c.categ_id==route.values.sub_categ_id %} selected="selected" {% endif %}
                                    value="/products/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                                ({{ c.count }})
                            </option>
                        {% endif %}
                    {% endif %}
                {% endfor %}
            </select>
        </div>
        <div class='delimiter'></div>
        <div class='category_select_right clear'>
            <select class="select-2 filter-select select-as-link" id="firms" name="firms">
                {% if route.values.filter %}
                    <option value="/products/filter-stocks">Все производители в разделе
                        АКЦИИ {% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                {% else %}
                    <option value="/products">Все производители в ПРОДАМ
                        НОВОЕ{% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                {% endif %}
                {% for p in producers %}
                    {% if route.values.filter %}
                        {% if route.values.user_id %}
                            {% if route.values.categ_id %}
                                <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/categ-{{ route.values.categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    - {{ products_new[0].user_name }} ({{ p.count }})
                                </option>
                            {% elseif route.values.sub_categ_id %}
                                <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    - {{ products_new[0].user_name }} ({{ p.count }})
                                </option>
                            {% else %}
                                <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    - {{ products_new[0].user_name }} ({{ p.count }})
                                </option>
                            {% endif %}
                        {% else %}
                            {% if route.values.categ_id %}
                                <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/categ-{{ route.values.categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    ({{ p.count }})
                                </option>
                            {% elseif route.values.sub_categ_id %}
                                <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    ({{ p.count }})
                                </option>
                            {% else %}
                                <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    ({{ p.count }})
                                </option>
                            {% endif %}
                        {% endif %}
                    {% elseif route.values.user_id %}
                        {% if route.values.categ_id %}
                            <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                    value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/categ-{{ route.values.categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                - {{ products_new[0].user_name }} ({{ p.count }})
                            </option>
                        {% elseif route.values.sub_categ_id %}
                            <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                    value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                - {{ products_new[0].user_name }} ({{ p.count }})
                            </option>
                        {% else %}
                            <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                    value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                - {{ products_new[0].user_name }} ({{ p.count }})
                            </option>
                        {% endif %}
                    {% else %}
                        {% if route.values.categ_id %}
                            <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                    value="/products/categ-{{ route.values.categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                ({{ p.count }})
                            </option>
                        {% elseif route.values.sub_categ_id %}
                            <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                    value="/products/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                ({{ p.count }})
                            </option>
                        {% else %}
                            <option {% if p.producer_id==parent_producer %} selected="selected" {% endif %}
                                    value="/products/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                ({{ p.count }})
                            </option>
                        {% endif %}
                    {% endif %}
                {% endfor %}
            </select>
            <select {% if products==null %} disabled="disabled" {% endif %}
                    class="select-2 filter-select select-as-link" id="products" name="products">
                {% if products %}
                    {% if route.values.filter %}
                        <option value="/products/filter-stocks">Все
                            товары {% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                    {% else %}
                        <option value="/products">Все
                            товары {% if route.values.user_id %} - {{ products_new[0].user_name }} {% endif %}</option>
                    {% endif %}
                {% endif %}
                {% for p in products %}
                    {% if route.values.filter %}
                        {% if route.values.user_id %}
                            {% if route.values.categ_id %}
                                <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/categ-{{ route.values.categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    - {{ products_new[0].user_name }} ({{ p.count }})
                                </option>
                            {% elseif route.values.sub_categ_id %}
                                <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    - {{ products_new[0].user_name }} ({{ p.count }})
                                </option>
                            {% else %}
                                <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    - {{ products_new[0].user_name }} ({{ p.count }})
                                </option>
                            {% endif %}
                        {% else %}
                            {% if route.values.categ_id %}
                                <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/categ-{{ route.values.categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    ({{ p.count }})
                                </option>
                            {% elseif route.values.sub_categ_id %}
                                <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/sub_categ-{{ route.values.sub_categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    ({{ p.count }})
                                </option>
                            {% else %}
                                <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                        value="/products/filter-stocks/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                    ({{ p.count }})
                                </option>
                            {% endif %}
                        {% endif %}
                    {% elseif route.values.user_id %}
                        {% if route.values.categ_id %}
                            <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                    value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/categ-{{ route.values.categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                - {{ products_new[0].user_name }} ({{ p.count }})
                            </option>
                        {% elseif route.values.sub_categ_id %}
                            <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                    value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                - {{ products_new[0].user_name }} ({{ p.count }})
                            </option>
                        {% else %}
                            <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                    value="/products/user-{{ route.values.user_id }}-{{ products_new[0].user_name|translit }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                ({{ p.count }})
                            </option>
                        {% endif %}
                    {% else %}
                        {% if route.values.categ_id %}
                            <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                    value="/products/categ-{{ route.values.categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                ({{ p.count }})
                            </option>
                        {% elseif route.values.sub_categ_id %}
                            <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                    value="/products/sub_categ-{{ route.values.sub_categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                ({{ p.count }})
                            </option>
                        {% else %}
                            <option {% if p.product_id==route.values.product_id %} selected="selected" {% endif %}
                                    value="/products/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                                ({{ p.count }})
                            </option>
                        {% endif %}
                    {% endif %}
                {% endfor %}
            </select>
        </div>
    </div>
    <div class='float_left'>
        {% if route.values.filter == 'stocks' %}
            {% set retex='/products/filter-stocks' %}
        {% else %}
            {% set retex='/products/' %}
        {% endif %}
        <a href='{{ retex }}'>
            <span class='remove_filter'></span> <span class='re_tex'>Сбросить фильтры</span>
        </a>
    </div>


    <div class='alias_pr' style="float:right">
        {% if ads_url and route.values.filter != 'stocks' %}
            <a href='/{{ ads_url }}'> Перейти в Продам Б/У {{ prefix }}</a> <span class='s'>></span>
        {% endif %}
    </div>

    <div class="a-row roboto18">
        {% if route.values.filter != 'stocks' %}
            <div class="a-cols-6">
                <a title="Рубрики" class="ajax-link" href="/products/all-categories"><i
                            class="a-icon-list-alt a-icon-gray"></i> Рубрики</a>
            </div>
            <div class="a-cols-4">
                <a title="Быстрый выбор" class="ajax-link" href="/products/quick-selection"><i
                            class="a-icon-quick-selection a-icon-gray"></i> Быстрый выбор</a>
            </div>
            <div class="a-cols-4">
                <a title=" Производители" class="ajax-link" href="/products/all-producers"><i
                            class="a-icon-barcode a-icon-gray"></i> Производители</a>
            </div>
            <div class="a-cols-6">
                <a title="Продавцы" class="ajax-link" href="/products/all-salespeople"><i
                            class="a-icon-user a-icon-gray"></i> Продавцы</a>
            </div>
            <div class="a-cols-6 text-right">
                <a title="Акций" href="/products/filter-stocks"><i class="a-icon-actionss a-icon-gray"></i> Акции</a>
            </div>
        {% else %}

        {% endif %}
    </div>
    </div>
    <div id="pagination-container">
        {% if banner_listing.link %}
            <div class="listing-banner pagination-block redirect" href="{{ banner_listing.link }}"
                 target="{{ banner_listing.target }}">
                <img src="/uploads/banners/{{ banner_listing.image }}"/>
            </div>
        {% endif %}
        {% for p in products_new %}
            <div class="item pagination-block {% if is_admin and p.flag_moder_view == 0 %}no-moder-view{% endif %} {% if p.light_flag %}light{% endif %} {% if p.color_yellow %} color_yellow {% endif %}">
                {% if p.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                <div class=" clear">
                    <a title="{{ p.product_name }}" href="/product/{{ p.product_new_id }}-{{ p.product_name|translit }}"
                       class="ajax-link image_pad">
                        <img title="{{ p.product_name }}"
                             src="/uploads/images/{% if p.image != '' %}{% if isBigImg(p.image) %}products/142x195/{{ p.image }}{% else %}products/80x100/{{ p.image }}{% endif %}{% else %}100x80.jpg{% endif %}">
                    </a>

                    <div class="offer-content">
                        <div style="position:absolute;right:0">
                            {% if p.stock_flag %}
                                <!--span class="flag-stock">Акция</span-->
                                <span>{{ p.date_add|timeago }}</span>
                            {% else %}
                                <span>{{ p.date_add|timeago }}</span>
                            {% endif %}
                        </div>
                        <div class="filter_l">
                            {% if route.values.filter %}
                                <a title="{{ p.categ_name }}"
                                   href="/products/filter-stocks/sub_categ-{{ p.sub_categ_id }}-{{ p.categ_name|translit }}">{{ p.categ_name }}</a>
                            {% else %}
                                <a title="{{ p.categ_name }}"
                                   href="/products/sub_categ-{{ p.sub_categ_id }}-{{ p.categ_name|translit }}">{{ p.categ_name }}</a>
                            {% endif %}
                        </div>
                        {% if p.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a title="{{ p.product_name }}" target="_blank"
                               href="/product/{{ p.product_new_id }}-{{ p.product_name|translit }}"
                               class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ p.product_name }}"
                               href="/product/{{ p.product_new_id }}-{{ p.product_name|translit }}"
                               class="ajax-link">{% if p.stock_flag %} Акция! {% endif %}  {{ p.product_name }}</a>
                        </div>
                        <div class='name_ta'>
                            <div class="a-font-small a-color-gray">{{ p.description|raw }}</div>
                        </div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                {% if route.values.filter %}
                                    <a title="{{ p.user_name }}" data-user_id="{{ p.user_id }}"
                                       href="/products/filter-stocks/user-{{ p.user_id }}-{{ p.user_name|translit }}"><i
                                                class="a-icon-user a-icon-gray"></i> {{ p.user_name }}</a>
                                {% else %}
                                    <a title="{{ p.user_name }}" data-user_id="{{ p.user_id }}"
                                       href="/products/user-{{ p.user_id }}-{{ p.user_name|translit }}"><i
                                                class="a-icon-user a-icon-gray"></i> {{ p.user_name }}</a>
                                {% endif %}
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price {% if p.stock_flag %}price-stock{% endif %}">
                                    {% if p.stock_flag %}
                                        {{ p.stock_price|getExchangeRates(p.stock_currency_id, p.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                    {% else %}
                                        {{ p.price|getExchangeRates(p.currency_id, p.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if p.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/product/edit-{{ p.product_new_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if p.flag == 1 %}
                                <a href="/product/flag-{{ p.product_new_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/product/flag-{{ p.product_new_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/product/delete-{{ p.product_new_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin == 0 %}
                            <li>
                                {% if p.stock_flag %}
                                    <a class="ajax-link" href="/product/edit_stock-{{ p.product_new_id }}"><i
                                                class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                                {% else %}
                                    <a class="ajax-link" href="/product/add_stock-{{ p.product_new_id }}"><i
                                                class="a-icon-star a-icon-gray"></i> Добавить акцию</a>
                                {% endif %}
                            </li>
                        {% endif %}
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if p.stock_flag %}
                                                <a class="ajax-link"
                                                   href="/product/edit_stock-{{ p.product_new_id }}"><i
                                                            class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                                            {% else %}
                                                <a class="ajax-link" href="/product/add_stock-{{ p.product_new_id }}"><i
                                                            class="a-icon-star a-icon-gray"></i> Добавить акцию</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            {% if p.flag_moder > 0 %}
                                                <a href="/product/flag_moder-{{ p.product_new_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/product/flag_moder-{{ p.product_new_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/product/send-message-{{ p.product_new_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/product/transfer-to-ads-{{ p.product_new_id }}"><i
                                                        class="a-icon-share-alt a-icon-gray"></i> Перенести в Б/У</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-products_new-product_new_id-{{ p.product_new_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-3-{{ p.product_new_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">


                            {% if (p.pay and p.time_show > 0) or p.group_id ==10 %}

                                {% if p.flag_moder == 0 %}
                                    <span class="yellow">На модерации</span>
                                {% elseif p.flag_show == 0 %}
                                    <span class="gray">Неактивно</span>
                                {% elseif p.flag == 0 %}
                                    <span class="gray">Скрыто</span>
                                {% else %}



                                    <span class="green  {% if p.time_show <9 and  p.group_id!=10 %}red2 {% endif %}">
			{% if(p.group_id !=10) %}
                Осталось {{ p.time_show }} дней публикации
                {% if p.time_show <9 and  p.group_id!=10 %}
                    | <a href='/success-{{ p.product_new_id }}-3-extend' class='success_link'>продлить</a>
                {% endif %}

            {% else %}
                Опубликовано
            {% endif %}
		   </span>
                                {% endif %}

                            {% else %}
                                <div class='nopay'><span class='red2'> не оплачено | <a
                                                href='/success-{{ p.product_new_id }}-3-top'>оплатить</a></span></div>
                            {% endif %}


                            <!--span class="green">Опубликовано</span-->

                        </li>
                        {% if p.user_id == user_info.info.user_id and "now"|datediff(p.date_add) > 13 %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-products_new-product_new_id-{{ p.product_new_id }}"><i
                                                class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                                    <div>

                                    </div>
                                </div>
                            </li>
                        {% endif %}
                    </ul>
                {% endif %}
                {#% if p.user_id == user_info.info.user_id and p.light_flag == 0 and p.flag_vip_add == 0 %#}
                {% if p.user_id == user_info.info.user_id %}
                    <ul class="options vip-options-user clear">
                        <li>
                            <a class="vip-request-link" href="/success-{{ p.product_new_id }}-3-top">Рекламировать
                                объявление</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% else %}
            <div class="a-mess-yellow">По Вашему запросу ничего не найдено</div>
            {% if user_info %}
                <div class="item">
                    {% include 'demand-add-search.tpl' %}
                </div>
            {% endif %}
        {% endfor %}
        {% if products_new and banner_listing_2.link %}
            <div class="listing-banner pagination-block redirect" href="{{ banner_listing_2.link }}"
                 target="{{ banner_listing_2.target }}">
                <img src="/uploads/banners/{{ banner_listing_2.image }}"/>
            </div>
        {% endif %}
    </div>
    {% if pagination.pages %}
        <ul class="a-pagination">
            <li class="first-page {% if route.values.page == 1 or route.values.page == 0 %}active{% endif %}">
                {% if route.values.user_id > 0 %}
                    <a href="/products/{{ pagination.first.url }}{{ url }}">{{ pagination.first.name }}</a>
                {% elseif route.values.categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/products/{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/products/{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/products/{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% endif %}
                {% elseif route.values.sub_categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/products/{{ pagination.first.url }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/products/{{ pagination.first.url }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/products/{{ pagination.first.url }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% endif %}
                {% elseif route.values.user_id > 0 %}
                    <a href="/products/{{ pagination.first.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.producer_id > 0 %}
                    <a href="/products/{{ pagination.first.url }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.product_id > 0 %}
                    <a href="/products/{{ pagination.first.url }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.search %}
                    <a href="/products/{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/products/{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page %}
                    {% if route.values.user_id > 0 %}
                        <a href="/products/{{ pagination.prev_page }}{{ url }}">«</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">«</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">«</a>
                        {% else %}
                            <a href="/products/{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ pagination.prev_page }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">«</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ pagination.prev_page }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">«</a>
                        {% else %}
                            <a href="/products/{{ pagination.prev_page }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">«</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/products/{{ pagination.prev_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/products/{{ pagination.prev_page }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/products/{{ pagination.prev_page }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.search %}
                        <a href="/products/{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                    {% else %}
                        <a href="/products/{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page==p.name or (route.values.page==0 and p.name==1) %} class="active" {% endif %}>
                    {% if route.values.user_id > 0 %}
                        <a href="/products/{{ p.url }}{{ url }}">{{ p.name }}</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ p.url }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ p.url }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% else %}
                            <a href="/products/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ p.url }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ p.url }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% else %}
                            <a href="/products/{{ p.url }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/products/{{ p.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/products/{{ p.url }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/products/{{ p.url }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/products/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/products/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page %}
                    {% if route.values.user_id > 0 %}
                        <a href="/products/{{ pagination.next_page }}{{ url }}">»</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">»</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">»</a>
                        {% else %}
                            <a href="/products/{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ pagination.next_page }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">»</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ pagination.next_page }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">»</a>
                        {% else %}
                            <a href="/products/{{ pagination.next_page }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">»</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/products/{{ pagination.next_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/products/{{ pagination.next_page }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/products/{{ pagination.next_page }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/products/{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% else %}
                        <a href="/products/{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            {% if pagination.last.url %}
                <li class="last-page">
                    {% if route.values.user_id > 0 %}
                        <a href="/products/{{ pagination.last.url }}{{ url }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                        {% else %}
                            <a href="/products/{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/products/{{ pagination.last.url }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/products/{{ pagination.last.url }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                        {% else %}
                            <a href="/products/{{ pagination.last.url }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/products/{{ pagination.last.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/products/{{ pagination.last.url }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/products/{{ pagination.last.url }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/products/{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                    {% else %}
                        <a href="/products/{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                    {% endif %}
                </li>
            {% endif %}
        </ul>
    {% endif %}
    </div>
    </div>
    </noindex>
{% endblock %}