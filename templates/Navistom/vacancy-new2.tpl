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
    <div class='background_filter' style='height:390px'>
        <h4><i class="navs-icon-vacancy"></i>Вакансии</h4>

        <div class='row_filter clear'>
            <form id="global-search" method="get" action="/work/vacancy/search">
                <input placeholder="Поиск в разделе ВАКАНСИИ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit"></button>
            </form>
            <div class='float_right'>
            </div>
        </div>
        <div class='cl_cen'></div>
        <div class='category_select'>
            <div class='category_select_left clear'>
                <ul class='ul_filter1'>
                    <li>
                        <a href="/work/vacancy/">
                            <span class=""></span> <span class="re_tex">Все специализации</span>
                        </a>
                    </li>
                    {% for c in categories|slice(0, 7) %}
                        <li {% if c.categ_id==route.values.categ_id %}class="active"{% endif %}>
                            {% if c.count > 0 %}
                                <a title="{{ c.name }}"
                                   href="/work/vacancy/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                                    ({{ c.count }})</a>
                            {% else %}
                                <a title="{{ c.name }}" class="disabled" href="javascript:return false">{{ c.name }}</a>
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
                                        <a href='/{{ route.controller }}/{% if    route.values.categ_id %}vacancy/categ-{{ route.values.categ_id }}/{% endif %}'>Все
                                            города </a>
                                    </li>
                                {% endif %}
                                {% for c in cities %}
                                    <li {% if c.city_id==route.values.city_id %}class="active"{% endif %}>
                                        <a title="{{ c.name }}"
                                           href="/work/vacancy/{% if route.values.categ_id == null %}city-{{ c.city_id }}-{{ c.name|translit }}{% elseif route.values.categ_id > 0 %}categ-{{ route.values.categ_id }}/city-{{ c.city_id }}-{{ c.name|translit }}{% endif %}">{{ c.name }}
                                            ({{ c.count }})</a>
                                    </li>
                                {% endfor %}
                            </ul>
                        </div>
                    </li>
                    {% for c in categories|slice(7) %}
                        <li {% if c.categ_id==route.values.categ_id %}class="active"{% endif %}>
                            {% if c.count > 0 %}
                                <a title="{{ c.name }}"
                                   href="/work/vacancy/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                                    ({{ c.count }})</a>
                            {% else %}
                                <a title="{{ c.name }}" class="disabled" href="javascript:return false">{{ c.name }}</a>
                            {% endif %}
                        </li>
                    {% endfor %}
                </ul>
            </div>
        </div>
        <div class='link_right'><br>
            <a href='/work/resume' target='_self'> Перейти в РЕЗЮМЕ ></a>
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
        {% for v in vacancy %}
            <div class="item pagination-block {% if is_admin and v.flag_moder_view == 0 %}no-moder-view{% endif %} {% if v.light_flag %}light{% endif %}{% if v.color_yellow %} color_yellow{% endif %}">
                {% if v.light_flag %}<span class='l_top'><span>топ</span></span> {% endif %}
                <div class=" clear">
                    {% if v.image != '' %}
                        <a title="{{ v.categs|join(', ') }}"
                           href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|join('-')|translit }}"
                           class="ajax-link image_pad"><img title="{{ v.categs|join(', ') }}"
                                                            alt="{{ v.categs|join(', ') }}"
                                                            src={% if isBigImg(v.image,'work') %}"/uploads/images/work/full/{{ v.image }}"{% else %} "/uploads/images/work/80x100/{{ v.image }}"{% endif %}
                            /></a>
                    {% elseif v.logotype != '' %}
                        <a title="{{ v.categs|join(', ') }}"
                           href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|join('-')|translit }}"
                           class="ajax-link image_pad"><img title="{{ v.categs|join(', ') }}"
                                                            alt="{{ v.categs|join(', ') }}"
                                                            src={% if isBigImg(v.image,'work') %}"/uploads/images/work/142x195/{{ v.logotype }}"{% else %} "/uploads/images/work/80x100/{{ v.logotype }}"{% endif %}
                            /></a>
                    {% else %}
                        <a title="{{ v.categs|join(', ') }}"
                           href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|join('-')|translit }}"
                           class="ajax-link"><img src="/uploads/images/100x80.jpg"></a>
                    {% endif %}
                    <div class="offer-content">
                        <div style="position:absolute;right:0">
                            {{ v.date_add|timeago }}
                        </div>
                        <div class='filter_l min'>
                            {% for key, value in v.categs %}
                                <a title="{{ value }}"
                                   href="/work/vacancy/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>&nbsp;&nbsp;
                            {% endfor %}
                        </div>
                        {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a title="{{ v.categs|join(', ') }}" target="_blank"
                               href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|join('-')|translit }}"
                               class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ v.categs|join(', ') }}"
                               href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|join('-')|translit }}"
                               class="ajax-link">
                                Требуется {{ v.categs|join(', ')|lower }}, г. {{ v.city_name }}
                            </a>
                        </div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ v.company_name }}" class="user-name" data-user_id="{{ v.user_id }}"
                                   href="/work/vacancy/user-{{ v.user_id }}-{{ v.company_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ v.company_name|raw }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price">
                                    {% if v.price > 0 %}
                                        {{ v.price|number_format(0, '', ' ') }} {{ v.currency_name }}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if v.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/work/vacancy/edit-{{ v.vacancy_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if v.flag == 1 %}
                                <a href="/work/vacancy/flag-{{ v.vacancy_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/work/vacancy/flag-{{ v.vacancy_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/work/vacancy/delete-{{ v.vacancy_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if v.flag_moder > 0 %}
                                                <a href="/work/vacancy/flag_moder-{{ v.vacancy_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/work/vacancy/flag_moder-{{ v.vacancy_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/work/vacancy/send-message-{{ v.vacancy_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-vacancies-vacancy_id-{{ v.vacancy_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-15-{{ v.vacancy_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if v.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif v.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                        {% if v.user_id == user_info.info.user_id and "now"|datediff(v.date_add) > 29 %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-vacancies-vacancy_id-{{ v.vacancy_id }}"><i
                                                class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                                    <div>
                                    </div>
                                </div>
                            </li>
                        {% endif %}
                    </ul>
                {% endif %}
                {% if v.user_id == user_info.info.user_id and v.light_flag == 0 and v.flag_vip_add == 0 %}
                    <ul class="options vip-options-user clear">
                        <li>
                            <a class="vip-request-link" href="/success-{{ v.vacancy_id }}-15-top">Рекламировать
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
                    <a href="/work/vacancy/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/work/vacancy/page-{{ pagination.first.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/work/vacancy/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/work/vacancy/page-{{ pagination.first.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.search %}
                    <a href="/work/vacancy/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/work/vacancy/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.prev_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.prev_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.search %}
                        <a href="/work/vacancy/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                    {% else %}
                        <a href="/work/vacancy/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page==p.name or (route.values.page==0 and p.name==1) %} class="active" {% endif %}>
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/vacancy/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/work/vacancy/{{ p.url }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/work/vacancy/{{ p.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/work/vacancy/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/work/vacancy/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.next_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.next_page }}/user_id-{{ route.values.user_id }}">»</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/work/vacancy/page-{{ pagination.next_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/work/vacancy/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% else %}
                        <a href="/work/vacancy/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/work/vacancy/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/work/vacancy/page-{{ pagination.last.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/work/vacancy/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/work/vacancy/page-{{ pagination.last.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.search %}
                    <a href="/work/vacancy/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/work/vacancy/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>
    {% endif %}
    </div>
    </noindex>
{% endblock %}