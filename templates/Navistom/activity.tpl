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
                <a href="/subscribe/delete-5-{{ route.values.categ_id|default(0) }}-{{ route.values.city_id|default(0) }}">
                    <span class="s-bg-red subscribe-icon"></span> &nbsp;
                    Отключить подписку на новости раздела
                </a>
            {% else %}
                <a href="/subscribe/add-5-{{ route.values.categ_id|default(0) }}-{{ route.values.city_id|default(0) }}">
                    <span class="s-bg-green subscribe-icon"></span>
                    &nbsp;Включить подписку на новости раздела
                </a>
            {% endif %}
        </div>
    {% endif %}

    <div id="left">

    <form id="global-search" method="get" action="/{{ route.controller }}/search">
        <input placeholder="Поиск в разделе МЕРОПРИЯТИЯ" type="text" value="{{ route.values.search }}" name="q"
               id="global-search-input"/>
        <button id="search-submit" type="submit">Искать</button>
    </form>

    <div id="pagination-container">

        <div class="sort-links">
            <a title="Новые" class="{% if route.values.sort_by == null %}active{% endif %}"
               href="/activity{% if route.values.categ_id > 0 and route.values.city_id == null %}/categ-{{ route.values.categ_id }}-{{ route.values.translit }} {% elseif route.values.categ_id == null and route.values.city_id > 0 %}/city-{{ route.values.city_id }}-{{ route.values.translit }}{% elseif route.values.categ_id >0 and route.values.city_id > 0 %}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}{% endif %}">Новые</a>
            <a title="Популярные" class="{% if route.values.sort_by == 'popular' %}active{% endif %}"
               href="/activity/sort-by-popular{% if route.values.categ_id > 0 and route.values.city_id == null %}/categ-{{ route.values.categ_id }}-{{ route.values.translit }} {% elseif route.values.categ_id == null and route.values.city_id > 0 %}/city-{{ route.values.city_id }}-{{ route.values.translit }}{% elseif route.values.categ_id >0 and route.values.city_id > 0 %}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}{% endif %}">Популярные</a>
            <a title="Ближайшие" class="{% if route.values.sort_by == 'coming' %}active{% endif %}"
               href="/activity/sort-by-coming{% if route.values.categ_id > 0 and route.values.city_id == null %}/categ-{{ route.values.categ_id }}-{{ route.values.translit }} {% elseif route.values.categ_id == null and route.values.city_id > 0 %}/city-{{ route.values.city_id }}-{{ route.values.translit }}{% elseif route.values.categ_id >0 and route.values.city_id > 0 %}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}{% endif %}">Ближайшие</a>
        </div>
        {% if banner_listing.link %}
            <a class="listing-banner pagination-block" href="{{ banner_listing.link }}"
               target="{{ banner_listing.target }}">
                <img src="/uploads/banners/{{ banner_listing.image }}"/>
            </a>
        {% endif %}

        {% for a in activity %}
            <div class="item pagination-block {% if is_admin and a.flag_moder_view == 0 %}no-moder-view{% endif %} {% if a.light_flag %}light{% endif %} {% if a.color_yellow %} color_yellow{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if a.light_flag %}<span class='l_top'><span>топ</span></span> {% endif %}
                        {% for key, value in a.categs %}
                            <a title="{{ value }}"
                               href="/activity/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>&nbsp;&nbsp;
                        {% endfor %}
                    </div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ a.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">

                    {% if a.lector_image %}

                        <div class="lector-image">
                            <a title="{{ a.name }}" href="/activity/{{ a.activity_id }}-{{ a.name|translit }}"
                               class="ajax-link"><img title="{{ a.name }}" alt="{{ a.name }}"
                                                      src="/uploads/images/activity/lectors/{{ a.lector_image }}"/></a>
                        </div>

                    {% elseif a.img_l %}
                        <a title="{{ a.name }}" href="/activity/{{ a.activity_id }}-{{ a.name|translit }}"
                           class="ajax-link"><img title="{{ a.name }}" alt="{{ a.name }}"
                                                  src="/uploads/images/activity/80x100/{{ a.img_l }}"/></a>
                    {% elseif a.image %}
                        <a title="{{ a.name }}" href="/activity/{{ a.activity_id }}-{{ a.name|translit }}"
                           class="ajax-link"><img title="{{ a.name }}" alt="{{ a.name }}"
                                                  src="/uploads/images/activity/80x100/{{ a.image }}"/></a>
                    {% else %}
                        <a title="{{ a.name }}" href="/activity/{{ a.activity_id }}-{{ a.name|translit }}"
                           class="ajax-link"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if a.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div style="color:#333" class="a-font-small"><i class="a-icon-calendar a-icon-gray"></i>
                            {% if a.flag_agreed > 0 or a.date_start == '0000-00-00' %}
                                по согласованию
                            {% else %}
                                {{ a.date_start|rusFormat }}

                                {% if a.date_end != '0000-00-00'  and a.date_end != a.date_start %}
                                    - {{ a.date_end|rusFormat }}
                                {% endif %}
                            {% endif %}
                        </div>
                        <a title="{{ a.name }}" target="_blank"
                           href="/activity/{{ a.activity_id }}-{{ a.name|translit }}" class="modal-window-link"></a>
                        <a title="{{ a.name }}" href="/activity/{{ a.activity_id }}-{{ a.name|translit }}"
                           class="ajax-link">{{ a.name|raw }}</a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ a.user_name }}" data-user_id="{{ a.user_id }}"
                                   href="/activity/user-{{ a.user_id }}-{{ a.user_name|translit }}"><i
                                            class="a-icon-user a-icon-gray"></i> {{ a.user_name }}</a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                г. {{ a.city_name }}
                            </div>
                        </div>
                    </div>
                </div>
                {% if a.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/activity/edit-{{ a.activity_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if a.flag == 1 %}
                                <a href="/activity/flag-{{ a.activity_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/activity/flag-{{ a.activity_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/activity/delete-{{ a.activity_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if a.flag_moder > 0 %}
                                                <a href="/activity/flag_moder-{{ a.activity_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/activity/flag_moder-{{ a.activity_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/activity/send-message-{{ a.activity_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-activity-activity_id-{{ a.activity_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-5-{{ a.activity_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if a.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif a.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                        {% if a.user_id == user_info.info.user_id and "now"|datediff(a.date_add) > 13 %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-activity-activity_id-{{ a.activity_id }}"><i
                                                class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                                    <div>
                                        Вы можете поднимать свое объявление раз в 2 недели, <br/>
                                        тем самым Вы подтверждаете его актуальность
                                    </div>
                                </div>
                            </li>
                        {% endif %}
                    </ul>
                    {#% if a.user_id == user_info.info.user_id and a.light_flag == 0 and a.flag_vip_add == 0 %#}
                    {% if a.user_id == user_info.info.user_id %}
                        <ul class="options vip-options-user clear">
                            <!--li>
            	<a class="ajax-link vip-request-link" href="/vip-request-5-{{ a.activity_id }}?name={{ a.name }}&link=http://navistom.com/activity/{{ a.activity_id }}-{{ a.name|translit }}">Заказать VIP - размещение</a>
            </li-->
                            <li>
                                <a class=" vip-request-link " href="/success-{{ a.activity_id }}-5-top">Рекламировать
                                    объявление</a>
                            </li>
                        </ul>
                    {% endif %}

                {% endif %}
            </div>
        {% else %}
            <div class="a-mess-yellow">По Вашему запросу ничего не найдено</div>
            <div class="item">
                {% include 'demand-add-search.tpl' %}
            </div>
        {% endfor %}

        {% if banner_listing_2.link %}
            <a class="listing-banner pagination-block" href="{{ banner_listing_2.link }}"
               target="{{ banner_listing_2.target }}">
                <img src="/uploads/banners/{{ banner_listing_2.image }}"/>
            </a>
        {% endif %}

    </div>

    {% if pagination %}
        <ul class="a-pagination">
            <li class="first-page {% if route.values.page == 1 or route.values.page == 0 %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/activity/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/activity/page-{{ pagination.first.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/activity/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/activity/page-{{ pagination.first.url }}/user_id-{{ route.values.user_id }}">{{ pagination.first.name }}</a>
                {% elseif route.values.search %}
                    <a href="/activity/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                {% elseif route.values.sort_by %}
                    <a href="/activity/page-{{ pagination.first.url }}/sort-by-{{ route.values.sort_by }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/activity/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/activity/page-{{ pagination.prev_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/activity/page-{{ pagination.prev_page }}/user_id-{{ route.values.user_id }}">«</a>
                    {% elseif route.values.search %}
                        <a href="/activity/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                    {% elseif route.values.sort_by %}
                        <a href="/activity/page-{{ pagination.prev_page }}/sort-by-{{ route.values.sort_by }}">«</a>
                    {% else %}
                        <a href="/activity/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page == p.name or (route.values.page == 0 and p.name == 1) %} class="active" {% endif %}>
                    {% if route.values.categ_id > 0 %}
                        <a href="/activity/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/activity/{{ p.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/activity/{{ p.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/activity/{{ p.url }}/user_id-{{ route.values.user_id }}">{{ p.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/activity/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                    {% elseif route.values.sort_by %}
                        <a href="/activity/{{ p.url }}/sort-by-{{ route.values.sort_by }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/activity/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/activity/page-{{ pagination.next_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/activity/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/activity/page-{{ pagination.next_page }}/user_id-{{ route.values.user_id }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/activity/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% elseif route.values.sort_by %}
                        <a href="/activity/page-{{ pagination.next_page }}/sort-by-{{ route.values.sort_by }}">»</a>
                    {% else %}
                        <a href="/activity/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/activity/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/activity/page-{{ pagination.last.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/activity/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.search %}
                    <a href="/activity/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                {% elseif route.values.sort_by %}
                    <a href="/activity/page-{{ pagination.last.url }}/sort-by-{{ route.values.sort_by }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/activity/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>
    {% endif %}

    </div>

    <div id="right" class="padding">
        <a title="Добавить мероприятие" href="/activity/add" class="add-btn "><b><i
                        class="a-icon-plus a-icon-white"></i></b> Добавить мероприятие</a>

        <div id="filter-right">
            <a class="active" href="#">Рубрики</a>
            <ul>
                {% for c in categs %}
                    <li {% if c.categ_id == route.values.categ_id %}class="active"{% endif %}>
                        {% if c.count > 0 %}
                            <a title="{{ c.name }}"
                               href="/activity/sort-by-coming/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                                ({{ c.count }})</a>
                        {% else %}
                            <a title="{{ c.name }}" class="disabled" href="javascript:return false;">{{ c.name }}</a>
                        {% endif %}
                    </li>
                {% endfor %}
            </ul>
            <a href="#">Населенные пункты</a>
            <ul>
                {% for c in cities %}
                    <li {% if c.city_id == route.values.city_id %}class="active"{% endif %}>
                        <a title="{{ c.name }}"
                           href="/activity/sort-by-coming/{% if route.values.categ_id == null %}city-{{ c.city_id }}-{{ c.name|translit }}{% elseif route.values.categ_id > 0 %}categ-{{ route.values.categ_id }}/city-{{ c.city_id }}-{{ c.name|translit }}{% endif %}">{{ c.name }}
                            ({{ c.count }})</a>
                    </li>
                {% endfor %}
            </ul>
        </div>

        <hr class="line">

        <br/>

        <a title="Все организаторы" class="ajax-link" href="/activity/all-users"><i class="a-icon-user a-icon-gray"></i>
            Все организаторы</a>

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