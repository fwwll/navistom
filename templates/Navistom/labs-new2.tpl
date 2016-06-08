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
    <div class='background_filter' style='min-height:227px'>
        <h4><i class="navs-icon-lab"></i>Зуботехнические лаборатории</h4>

        <div class='row_filter clear'>
            <form id="global-search" method="get" action="/labs/search">
                <input placeholder="Поиск в разделе  З/Т Лаборатории" type="text" value="{{ route.values.search }}"
                       name="q" id="global-search-input"/>
                <button id="search-submit" type="submit"></button>
            </form>
            <div class='float_left'>
            </div>
        </div>
        <div></div>
        <div class='category_select'>
            <div class='category_select_left clear'>
                <ul class='ul_filter1'>
                    <li>
                        <a href="/labs">
                            <span class=""></span> <span class="re_tex">Все рубрики</span>
                        </a>
                    </li>
                    {% for c in categs|slice(0, 3) %}
                        <li {% if c.categ_id==route.values.categ_id %}class="active"{% endif %}>
                            {% if c.count > 0 %}
                                <a title="{{ c.name }}"
                                   href="/labs/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }}
                                    )</a>
                            {% else %}
                                <a class="disabled" href="javascript:return false">{{ c.name }} ({{ c.count }})</a>
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
                                {% for ci in cities %}
                                    <li {% if ci.city_id==route.values.city_id %}class="active"{% endif %}>
                                        {% if route.values.categ_id > 0 %}
                                            <a title="{{ ci.name }}" {% if ci.city_id==route.values.city_id %} class="active" {% endif %}
                                               href="/labs/categ-{{ route.values.categ_id }}/city-{{ ci.city_id }}-{{ ci.name|translit }}">{{ ci.name }}
                                                ({{ ci.count }})</a>
                                        {% else %}
                                            <a title="{{ ci.name }}" {% if ci.city_id==route.values.city_id %} class="active" {% endif %}
                                               href="/labs/city-{{ ci.city_id }}-{{ ci.name|translit }}">{{ ci.name }}
                                                ({{ ci.count }})</a>
                                        {% endif %}
                                    </li>
                                {% endfor %}
                            </ul>
                        </div>
                    </li>
                    {% for c in categs|slice(3) %}
                        <li {% if c.categ_id==route.values.categ_id %}class="active"{% endif %}>
                            {% if c.count > 0 %}
                                <a title="{{ c.name }}"
                                   href="/labs/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }}
                                    )</a>
                            {% else %}
                                <a class="disabled" href="javascript:return false">{{ c.name }} ({{ c.count }})</a>
                            {% endif %}
                        </li>
                    {% endfor %}
                </ul>
            </div>
        </div>
    </div>
    <div id="pagination-container">
        {% if banner_listing.link %}
            <noindex>
                <div class="listing-banner pagination-block redirect" href="{{ banner_listing.link }}"
                     target="{{ banner_listing.target }}">
                    <img src="/uploads/banners/{{ banner_listing.image }}"/>
                </div>
            </noindex>
        {% endif %}
        {% for l in labs %}
            <div class="item pagination-block {% if is_admin and l.flag_moder_view == 0 %}no-moder-view{% endif %} {% if l.light_flag %}light{% endif %} {% if l.color_yellow %} color_yellow{% endif %}">
                {% if l.light_flag %}<span class='l_top'><span>топ</span></span> {% endif %}
                <div class=" clear">
                    {% if l.image != '' %}
                        <a title="{{ l.name }}" href="/lab/{{ l.lab_id }}-{{ l.name|join(' ')|translit }}"
                           class="ajax-link n-ad-title-price image_pad"><img title="{{ l.name }}" alt="{{ l.name }}"
                                                                             src="/uploads/images/labs/142x195/{{ l.image }}"/></a>
                    {% else %}
                        <a title="{{ l.name }}" href="/lab/{{ l.lab_id }}-{{ l.name|join(' ')|translit }}"
                           class="ajax-link n-ad-title-price image_pad"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        <div style="position:absolute;right:0">
                            {{ l.date_add|timeago }}
                        </div>
                        <div class='filter_l'>
                            {% for key, value in l.categs %}
                                <a title="{{ value }}" href="/labs/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>
                            {% endfor %}
                        </div>
                        {% if l.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a title="{{ l.name }}" target="_blank" href="/lab/{{ l.lab_id }}-{{ l.name|translit }}"
                               class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ l.name }}" href="/lab/{{ l.lab_id }}-{{ l.name|join(' ')|translit }}"
                               class="ajax-link n-ad-title-price">
                                {{ l.name|raw }} г. {{ l.city_name }}
                            </a>
                        </div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ l.user }}" class="user-name" data-user_id="{{ l.user_id }}"
                                   href="/labs/user-{{ l.user_id }}-{{ l.user|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ l.user|raw }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">

                            </div>
                        </div>
                    </div>
                </div>
                {% if l.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/lab/edit-{{ l.lab_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if l.flag == 1 %}
                                <a href="/lab/flag-{{ l.lab_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/lab/flag-{{ l.lab_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/lab/delete-{{ l.lab_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if l.flag_moder > 0 %}
                                                <a href="/lab/flag_moder-{{ l.lab_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/lab/flag_moder-{{ l.lab_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/lab/send-message-{{ l.lab_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-labs-lab_id-{{ l.lab_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-7-{{ l.lab_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if l.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif l.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                        {% if l.user_id == user_info.info.user_id and "now"|datediff(l.date_add) > 29 %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-labs-lab_id-{{ l.lab_id }}"><i
                                                class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                                    <div>
                                    </div>
                                </div>
                            </li>
                        {% endif %}
                    </ul>
                {% endif %}
                {#% if l.user_id == user_info.info.user_id and l.light_flag == 0 and l.flag_vip_add == 0 %#}
                {% if l.user_id == user_info.info.user_id %}
                    <ul class="options vip-options-user clear">
                        <li>
                            <a class="vip-request-link" href="/success-{{ l.lab_id }}-7-top">Рекламировать
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
                    <a href="/labs/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/labs/page-{{ pagination.first.url }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/labs/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/labs/page-{{ pagination.first.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.search %}
                    <a href="/labs/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/labs/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/labs/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/labs/page-{{ pagination.prev_page }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/labs/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/labs/page-{{ pagination.prev_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.search %}
                        <a href="/labs/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                    {% else %}
                        <a href="/labs/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page==p.name or (route.values.page==0 and p.name==1) %} class="active" {% endif %}>
                    {% if route.values.categ_id > 0 %}
                        <a href="/labs/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/labs/{{ p.url }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/labs/{{ p.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/labs/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/labs/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/labs/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/labs/page-{{ pagination.next_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.region_id > 0 and route.values.categ_id > 0 %}
                        <a href="/labs/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/labs/page-{{ pagination.next_page }}/user_id-{{ route.values.user_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/labs/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% else %}
                        <a href="/labs/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/labs/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/labs/page-{{ pagination.last.url }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/labs/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/region-{{ route.values.region_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/labs/page-{{ pagination.last.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.search %}
                    <a href="/labs/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/labs/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>
    {% endif %}
    </div>
    <noindex>
{% endblock %}