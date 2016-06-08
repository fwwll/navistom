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
    <noindex>
        <div class='background_filter' style='min-height:268px'>
            <h4><i class="navs-icon-realty"></i>Продам / Сдам</h4>

            <div class='row_filter clear'>
                <form id="global-search" method="get" action="/realty/search">
                    <input placeholder="Поиск в разделе Аренда/Продажа" type="text" value="{{ route.values.search }}"
                           name="q" id="global-search-input"/>
                    <button id="search-submit" type="submit"></button>
                </form>

                <div class='float_right'>
                </div>
            </div>
            <div></div>
            <div class='category_select'>
                <div class='category_select_left clear'>
                    <ul class='ul_filter1'>
                        <li>
                            <a href="/realty">
                                <span class=""></span> <span class="re_tex">Все рубрики</span>
                            </a>
                        </li>
                        {% for c in categs|slice(0,4) %}
                            <li {% if c.categ_id==route.values.categ_id %}class="active"{% endif %}>
                                {% if c.count > 0 %}
                                    <a title="{{ c.name }}" href="/realty/categ-{{ c.categ_id }}-{{ c.name|translit }}">
                                        {{ c.name }} ({{ c.count }})
                                    </a>
                                {% else %}
                                    <a class="disabled" href="javascript:return false">{{ c.name }}</a>
                                {% endif %}
                            </li>
                        {% endfor %}
                    </ul>
                </div>
                <div class='category_select_right clear'>
                    <ul class='ul_filter2'>
                        <li style='list-style:none'> &nbsp;
                            <div class='filter-city'>
                                <span class='re_tex goroda'><i
                                            class="globe-new-iconz"></i>{% if cityName %} <b>г.{{ cityName }}</b> {% else %}Все города{% endif %}
                                    <span class='strela_min'>&#9660;</span></span>
                                <ul class='submenu'>
                                    {% if cityName %}
                                        <li>
                                            <a href='/{{ route.controller }}/{% if    route.values.categ_id %}categ-{{ route.values.categ_id }}/{% endif %}'>Все
                                                города </a>
                                        </li>
                                    {% endif %}
                                    {% for c in cities %}
                                        <li {% if c.city_id==route.values.citi_id %}class="active"{% endif %}>
                                            {% if route.values.categ_id > 0 %}
                                                <a title="{{ c.name }}"
                                                   href="/realty/categ-{{ route.values.categ_id }}/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }}
                                                    ({{ c.count }})</a>
                                            {% else %}
                                                <a title="{{ c.name }}"
                                                   href="/realty/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }}
                                                    ({{ c.count }})</a>
                                            {% endif %}
                                        </li>
                                    {% endfor %}
                                </ul>
                            </div>
                        </li>
                        {% for c in categs|slice(4) %}
                            <li {% if c.categ_id==route.values.categ_id %}class="active"{% endif %}>
                                {% if c.count > 0 %}
                                    <a title="{{ c.name }}" href="/realty/categ-{{ c.categ_id }}-{{ c.name|translit }}">
                                        {{ c.name }} ({{ c.count }})
                                    </a>
                                {% else %}
                                    <a class="disabled" href="javascript:return false">{{ c.name }}</a>
                                {% endif %}
                            </li>
                        {% endfor %}
                    </ul>
                </div>
            </div>
        </div>
    </noindex>
    <div id="pagination-container">
        {% if banner_listing.link %}
            <noindex>
                <div class="listing-banner pagination-block redirect" href="{{ banner_listing.link }}"
                     target="{{ banner_listing.target }}">
                    <img src="/uploads/banners/{{ banner_listing.image }}"/>
                </div>
            </noindex>
        {% endif %}
        {% for r in realty %}
            <div class="item pagination-block {% if is_admin and r.flag_moder_view == 0 %}no-moder-view{% endif %} {% if r.light_flag %}light{% endif %} {% if r.color_yellow %} color_yellow{% endif %}">
                {% if r.light_flag %}<span class='l_top'><span>топ</span></span> {% endif %}
                <div class=" clear">
                    {% if r.url_full != '' %}
                        <a title="{{ r.name }}" href="/realty/{{ r.realty_id }}-{{ r.name|translit }}"
                           class="ajax-link n-ad-title-price image_pad">
                            <img title="{{ r.name }}" alt="{{ r.name }}"
                                 src="/uploads/images/realty/142x195/{{ r.url_full }}"/>
                        </a>
                    {% else %}
                        <a title="{{ r.name }}" href="/realty/{{ r.realty_id }}-{{ r.name|translit }}"
                           class="ajax-link n-ad-title-price image_pad"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        <div style="position:absolute;right:0">
                            {{ r.date_add|timeago }}
                        </div>
                        <div class='filter_l'>
                            <a href="/realty/categ-{{ r.categ_id }}-{{ r.categ_name|translit }}">{{ r.categ_name }}</a>
                        </div>
                        {% if r.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a title="{{ r.name }}" target="_blank"
                               href="/realty/{{ r.realty_id }}-{{ r.name|translit }}" class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ r.name }}" href="/realty/{{ r.realty_id }}-{{ r.name|translit }}"
                               class="ajax-link n-ad-title-price">
                                {{ r.name|raw }}, г. {{ r.city_name }}
                            </a>
                        </div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ r.user_name }} {{ r.user_surname }}" class="user-name"
                                   data-user_id="{{ r.user_id }}"
                                   href="/realty/user-{{ r.user_id }}-{{ r.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ r.user_name }} {{ r.user_surname }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div title="{{ r.price_description }}" class="price">
                                    {% if r.price > 0 %}
                                        {{ r.price|getExchangeRates(r.currency_id, r.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                        {% if r.price_description %}
                                            <i class="a-icon-info-sign a-icon-gray"></i>
                                        {% endif %}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if r.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/realty/edit-{{ r.realty_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if r.flag == 1 %}
                                <a href="/realty/flag-{{ r.realty_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                                    Скрыть</a>
                            {% else %}
                                <a href="/realty/flag-{{ r.realty_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/realty/delete-{{ r.realty_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if r.flag_moder > 0 %}
                                                <a href="/realty/flag_moder-{{ r.realty_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/realty/flag_moder-{{ r.realty_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/realty/send-message-{{ r.realty_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-realty-realty_id-{{ r.realty_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-8-{{ r.realty_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if r.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif r.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                        {% if r.user_id == user_info.info.user_id and "now"|datediff(r.date_add) > 29 %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-realty-realty_id-{{ r.realty_id }}"><i
                                                class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                                    <div>
                                    </div>
                                </div>
                            </li>
                        {% endif %}
                    </ul>
                {% endif %}
                {#% if r.user_id == user_info.info.user_id and r.light_flag == 0 and r.flag_vip_add == 0 %#}
                {% if r.user_id == user_info.info.user_id %}
                    <ul class="options vip-options-user clear">
                        <li>
                            <a class="vip-request-link" href="/success-{{ r.realty_id }}-8-top">Рекламировать
                                объявление</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% else %}
            <div class="a-mess-yellow">По Вашему запросу ничего не найдено</div>
            <div class="item">
                {% include 'demand-add-search.tpl' %}
            </div>
        {% endfor %}
        {% if banner_listing_2.link %}
            <noindex>
                <div class="listing-banner pagination-block redirect" href="{{ banner_listing.link }}"
                     target="{{ banner_listing.target }}">
                    <img src="/uploads/banners/{{ banner_listing.image }}"/>
                </div>
            </noindex>
        {% endif %}
    </div>
    {% if pagination.pages %}
        <ul class="a-pagination">
            <li class="first-page {% if route.values.page == 1 or route.values.page == 0 %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/realty/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/realty/page-{{ pagination.first.url }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/realty/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/realty/page-{{ pagination.first.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.search %}
                    <a href="/realty/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/realty/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/realty/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/realty/page-{{ pagination.prev_page }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/realty/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/realty/page-{{ pagination.prev_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.search %}
                        <a href="/realty/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                    {% else %}
                        <a href="/realty/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page==p.name or (route.values.page==0 and p.name==1) %} class="active" {% endif %}>

                    {% if route.values.categ_id > 0  and route.values.city_id > 0 %}
                        <a href="/realty/{{ p.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ p.name }}</a>

                    {% elseif route.values.categ_id > 0 %}
                        <a href="/realty/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/realty/{{ p.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/realty/{{ p.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/realty/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/realty/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 and route.values.city_id == 0 %}
                        <a href="/realty/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/realty/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/realty/page-{{ pagination.next_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/realty/page-{{ pagination.next_page }}/user_id-{{ route.values.user_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/realty/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% else %}
                        <a href="/realty/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/realty/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/realty/page-{{ pagination.last.url }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/realty/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/realty/page-{{ pagination.last.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.search %}
                    <a href="/realty/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/realty/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>
    {% endif %}
    </div>
    </noindex>
{% endblock %}