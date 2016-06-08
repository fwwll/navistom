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
    <div class='background_filter' style='height:193px'>
        <h4><i class="navs-icon-service"></i>Сервис / Запчасти</h4>

        <div class='row_filter clear'>
            <form id="global-search" method="get" action="/{{ route.controller }}/search">
                <input placeholder="Поиск" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit"></button>
            </form>
            <div class='float_right'>
                <div class='filter-right'>
                </div>
            </div>
        </div>
        <div></div>
        <div class='category_select'>
            <div class='category_select_left clear'>
                <ul class='ul_filter1'>
                    <li class="">
                        <a href="/services">
                            <span class=""></span><span class="re_tex">Все рубрики</span>
                        </a>
                    </li>
                    <li {% if categs[0].categ_id==route.values.categ_id %}class="active"{% endif %}>
                        <a title="{{ categs[0].name }}"
                           href="/services/categ-{{ categs[0].categ_id }}-{{ categs[0].name|translit }}">{{ categs[0].name }}
                            ({{ categs[0].count }})</a>
                    </li>
                    <li {% if categs[1].categ_id==route.values.categ_id %}class="active"{% endif %}>
                        <a title="{{ categs[1].name }}"
                           href="/services/categ-{{ categs[1].categ_id }}-{{ categs[1].name|translit }}">{{ categs[1].name }}
                            ({{ categs[1].count }})</a>
                    </li>
                </ul>
            </div>
            <div class='category_select_right clear'>
                <ul class='ul_filter2'>&nbsp;
                    <li style='list-style:none'>
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
                                    <li {% if c.city_id==route.values.city_id %}class="active"{% endif %}>
                                        {% if route.values.categ_id > 0 %}
                                            <a title="{{ c.name }}" {% if c.city_id==route.values.city_id %} class="active" {% endif %}
                                               href="/services/categ-{{ route.values.categ_id }}/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }}
                                                ({{ c.count }})</a>
                                        {% else %}
                                            <a title="{{ c.name }}" {% if c.city_id==route.values.city_id %} class="active" {% endif %}
                                               href="/services/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }}
                                                ({{ c.count }})</a>
                                        {% endif %}
                                    </li>
                                {% endfor %}
                            </ul>
                        </div>
                    </li>
                    <li {% if categs[2].categ_id==route.values.categ_id %}class="active"{% endif %}>
                        <a title="{{ categs[2].name }}"
                           href="/services/categ-{{ categs[2].categ_id }}-{{ categs[2].name|translit }}">{{ categs[2].name }}
                            ({{ categs[2].count }})</a>
                    </li>
                    <li {% if categs[3].categ_id==route.values.categ_id %}class="active"{% endif %}>
                        <a title="{{ categs[3].name }}"
                           href="/services/categ-{{ categs[3].categ_id }}-{{ categs[3].name|translit }}">{{ categs[3].name }}
                            ({{ categs[3].count }})</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="pagination-container">
        {% if banner_listing.link %}
            <noindex>
                <a class="listing-banner pagination-block" href="{{ banner_listing.link }}"
                   target="{{ banner_listing.target }}" rel='nofollow'>
                    <img src="/uploads/banners/{{ banner_listing.image }}"/>
                </a>
            </noindex>
        {% endif %}
        {% for s in services %}
            <div class="item pagination-block {% if is_admin and s.flag_moder_view == 0 %}no-moder-view{% endif %} {% if s.light_flag %}light{% endif %} {% if s.color_yellow %} color_yellow{% endif %}">
                {% if s.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                <div class=" clear">
                    {% if s.url_full != '' %}

                        <a title="{{ s.name }}" href="/service/{{ s.service_id }}-{{ s.name|translit }}"
                           class="ajax-link n-ad-title-price image_pad"><img title="{{ s.name }}"
                                                                             src="/uploads/images/services/142x195/{{ s.url_full }}"/></a>
                    {% else %}
                        <a title="{{ s.name }}" href="/service/{{ s.service_id }}-{{ s.name|translit }}"
                           class="ajax-link n-ad-title-price image_pad"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        <div style="position:absolute;right:0">
                            {{ s.date_add|timeago }}
                        </div>
                        {% for key, value in s.categs %}
                            <div class='filter_l'>
                                <a title="{{ value }}"
                                   href="/services/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>
                            </div>
                        {% endfor %}
                        {% if s.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a title="{{ s.name }}" target="_blank"
                               href="/service/{{ s.service_id }}-{{ s.name|translit }}" class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ s.name }}" href="/service/{{ s.service_id }}-{{ s.name|translit }}"
                               class="ajax-link n-ad-title-price">
                                {{ s.name }}
                            </a>
                        </div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ s.user_name }}" class="user-name" data-user_id="{{ s.user_id }}"
                                   href="/services/user-{{ s.user_id }}-{{ s.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ s.user_name|raw }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                {% if s.price > 0 %}
                                    <div class="price"> {{ s.price|getExchangeRates(s.currency_id, s.user_id)|number_format(2, '.', ' ') }} {{ default_currency }} </div>
                                {% else %}
                                    <div class="price"></div>
                                {% endif %}
                            </div>
                        </div>
                    </div>
                </div>
                {% if s.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/service/edit-{{ s.service_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if s.flag == 1 %}
                                <a href="/service/flag-{{ s.service_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/service/flag-{{ s.service_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/service/delete-{{ s.service_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if s.flag_moder > 0 %}
                                                <a href="/service/flag_moder-{{ s.service_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/service/flag_moder-{{ s.service_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/service/send-message-{{ s.service_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-services-service_id-{{ s.service_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-9-{{ s.service_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if s.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif s.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                        {% if (s.group_id ==10 and s.user_id == user_info.info.user_id and "now"|datediff(s.date_add) > 13)  or ( s.user_id == user_info.info.user_id and "now"|datediff(s.date_add) > 29) %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-services-service_id-{{ s.service_id }}"><i
                                                class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                                    <div>
                                    </div>
                                </div>
                            </li>
                        {% endif %}
                    </ul>
                {% endif %}
                {#% if s.user_id == user_info.info.user_id and s.light_flag == 0 and s.flag_vip_add == 0 %#}
                {% if s.user_id == user_info.info.user_id %}
                    <ul class="options vip-options-user clear">
                        <li>
                            <a class="vip-request-link" href="/success-{{ s.service_id }}-9-top">Рекламировать
                                объявление</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% else %}
            <div class="a-mess-yellow">По Вашему запросу ничего не найдено</div>
        {% endfor %}
        {% if banner_listing_2.link %}
            <noindex>
                <a class="listing-banner pagination-block" href="{{ banner_listing_2.link }}"
                   target="{{ banner_listing_2.target }}" rel='nofollow'>
                    <img src="/uploads/banners/{{ banner_listing_2.image }}"/>
                </a>
            </noindex>
        {% endif %}
    </div>
    {% if pagination.pages %}
        <ul class="a-pagination">
            <li class="first-page {% if route.values.page == 1 or route.values.page == 0 %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/services/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/services/page-{{ pagination.first.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/services/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/services/page-{{ pagination.first.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.search %}
                    <a href="/services/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/services/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/services/page-{{ pagination.prev_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/services/page-{{ pagination.prev_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.search %}
                        <a href="/services/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                    {% else %}
                        <a href="/services/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page==p.name or (route.values.page==0 and p.name==1) %} class="active" {% endif %}>
                    {% if route.values.categ_id > 0 %}
                        <a href="/services/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/services/{{ p.url }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        % elseif route.values.user_id > 0 %}
                        <a href="/services/{{ p.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/services/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/services/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/services/page-{{ pagination.next_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/services/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/services/page-{{ pagination.next_page }}/user_id-{{ route.values.user_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/services/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% else %}
                        <a href="/services/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/services/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/services/page-{{ pagination.last.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/services/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/services/page-{{ pagination.last.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.search %}
                    <a href="/services/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/services/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>
    {% endif %}
    </div>
    </noindex>
{% endblock %}