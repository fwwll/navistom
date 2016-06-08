{% extends "index.tpl" %}

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

    {% if user_info and turnSubscribe %}
        <div class="subscribe-options">
            {% if subscribe_status %}
            <a href="/subscribe/delete-9-{{ route.values.categ_id|default(0) }}-{{ route.values.city_id|default(0) }}">
                <span class="s-bg-red subscribe-icon"></span> &nbsp;
                Отключить подписку на новости раздела
            </a>
            {% else %}
            {% if subscribe_categ %}
            <a href="/subscribe/add-9-{{ subscribe_categ }}-{{ route.values.city_id|default(0) }}">
                {% else %}
                <a href="/subscribe/add-9-{{ route.values.categ_id|default(0) }}-{{ route.values.city_id|default(0) }}">
                    {% endif %}

                    <span class="s-bg-green subscribe-icon"></span>
                    &nbsp;Включить подписку на новости раздела
                </a>
                {% endif %}
        </div>
    {% endif %}

    <div id="left">
    <form id="global-search" method="get" action="/{{ route.controller }}/search">
        <input placeholder="Поиск в разделе СЕРВИС" type="text" value="{{ route.values.search }}" name="q"
               id="global-search-input"/>
        <button id="search-submit" type="submit">Искать</button>
    </form>

    <div id="pagination-container">
        {% if banner_listing.link %}
            <a class="listing-banner pagination-block" href="{{ banner_listing.link }}"
               target="{{ banner_listing.target }}">
                <img src="/uploads/banners/{{ banner_listing.image }}"/>
            </a>
        {% endif %}
        {% for s in services %}
            <div class="item pagination-block {% if is_admin and s.flag_moder_view == 0 %}no-moder-view{% endif %} {% if s.light_flag %}light{% endif %}  {% if s.color_yellow %} color_yellow{% endif %}   ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if s.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}

                        {% for key, value in s.categs %}
                            <a title="{{ value }}" href="/services/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>
                        {% endfor %}
                    </div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ s.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">

                    {% if s.url_full != '' %}
                        <a title="{{ s.name }}" href="/service/{{ s.service_id }}-{{ s.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title="{{ s.name }}"
                                                                   src="/uploads/images/services/80x100/{{ s.url_full }}"/></a>
                    {% else %}
                        <a title="{{ s.name }}" href="/service/{{ s.service_id }}-{{ s.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if s.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a title="{{ s.name }}" target="_blank" href="/service/{{ s.service_id }}-{{ s.name|translit }}"
                           class="modal-window-link"></a>
                        <a title="{{ s.name }}" href="/service/{{ s.service_id }}-{{ s.name|translit }}"
                           class="ajax-link n-ad-title-price">
                            {{ s.name }}
                        </a>

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
                                    г. {{ s.city_name }}
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
                        {% if s.user_id == user_info.info.user_id and "now"|datediff(s.date_add) > 13 %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-services-service_id-{{ s.service_id }}"><i
                                                class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                                    <div>
                                        Вы можете поднимать свое объявление раз в 2 недели, <br/>
                                        тем самым Вы подтверждаете его актуальность
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
                            <!--a class="ajax-link vip-request-link" href="/vip-request-9-{{ s.service_id }}?name={{ s.name }}&link=http://navistom.com/service/{{ s.service_id }}-{{ s.name|translit }}">Заказать VIP - размещение</a-->

                            <a class=" vip-request-link  " href="/success-{{ s.service_id }}-9-top">Рекламировать
                                объявление</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% else %}
            <div class="a-mess-yellow">По Вашему запросу ничего не найдено</div>
        {% endfor %}

        {% if banner_listing_2.link %}
            <a class="listing-banner pagination-block" href="{{ banner_listing_2.link }}"
               target="{{ banner_listing_2.target }}">
                <img src="/uploads/banners/{{ banner_listing_2.image }}"/>
            </a>
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
                <li {% if route.values.page == p.name or (route.values.page == 0 and p.name == 1) %} class="active" {% endif %}>
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

    <div id="right" class="padding">
        <a title="Добавить сервис" href="/service/add" class="add-btn "><b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить сервис</a>


        <div id="filter-right">
            <a class="active" href="#">Рубрики</a>
            <ul>
                {% for c in categs %}
                    <li {% if c.categ_id == route.values.categ_id %}class="active"{% endif %}>
                        <a title="{{ c.name }}"
                           href="/services/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }}
                            )</a>
                    </li>
                {% endfor %}
            </ul>
            <a href="#">Населенные пункты</a>
            <ul>
                {% for c in cities %}
                    <li {% if c.city_id == route.values.city_id %}class="active"{% endif %}>
                        {% if route.values.categ_id > 0 %}
                            <a title="{{ c.name }}" {% if c.city_id == route.values.city_id %} class="active" {% endif %}
                               href="/services/categ-{{ route.values.categ_id }}/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }}
                                ({{ c.count }})</a>
                        {% else %}
                            <a title="{{ c.name }}" {% if c.city_id == route.values.city_id %} class="active" {% endif %}
                               href="/services/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }}
                                )</a>
                        {% endif %}

                    </li>
                {% endfor %}
            </ul>
        </div>

        <hr class="line">

        <div class="social-parent">
            <a title="Вконтакте" href="http://vk.com/id95980050" target="_blank" class="social-link vk"></a>
            <a title="Facebook" href="https://www.facebook.com/navistom" target="_blank"
               class="social-link facebook"></a>
            <a title="Twitter" href="https://twitter.com/navistom" class="social-link twitter" target="_blank"></a>
            <a title="Google+" href="https://plus.google.com/u/0/107274476270243980702/posts" target="_blank"
               class="social-link google"></a>
            <a title="Обратная связь" href="/feedback" class="social-link feedback ajax-link"></a>
        </div>

        <p>&nbsp;</p>

        {% if banner.link or banner.code %}
            <noindex>
                <div id="fixed-banner">
                    {% if banner.code %}
                        {{ banner.code|raw }}
                    {% else %}
                        <a href="{{ banner.link }}" target="{{ banner.target }}">
                            <img src="/uploads/banners/{{ banner.image }}"/>
                        </a>
                    {% endif %}
                </div>
            </noindex>
        {% endif %}
    </div>

{% endblock %}