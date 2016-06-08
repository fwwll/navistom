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
                <a href="/subscribe/delete-11-0-0">
                    <span class="s-bg-red subscribe-icon"></span> &nbsp;
                    Отключить подписку на новости раздела
                </a>
            {% else %}
                <a href="/subscribe/add-11-0-0">
                    <span class="s-bg-green subscribe-icon"></span>
                    &nbsp;Включить подписку на новости раздела
                </a>
            {% endif %}
        </div>
    {% endif %}

    <div id="left">

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе СПРОС" type="text" value="{{ route.values.search }}" name="q"
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
            {% for d in demand %}
                <div class="item pagination-block {% if is_admin and d.flag_moder_view == 0 %}no-moder-view{% endif %} {% if d.light_flag %}light{% endif %}
	{% if d.color_yellow %} color_yellow{% endif %} ">

                    <div class="a-row a-offset-0">
                        <div class="a-cols-2 a-font-small a-color-gray-2">

                            {% if d.light_flag %}<span class='l_top'><span>топ</span></span> {% endif %}
                            &nbsp;
                        </div>
                        <div style="font-size:10px"
                             class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                            {{ d.date_add|timeago }}
                        </div>
                    </div>

                    <div class="offer clear">
                        {% if d.url_full != '' %}
                            <a title="{{ d.name }}" href="/demand/{{ d.demand_id }}-{{ d.name|translit }}"
                               class="ajax-link n-ad-title-price"><img title="{{ d.name }}" alt="{{ d.name }}"
                                                                       src="/uploads/images/demand/80x100/{{ d.url_full }}"/></a>
                        {% else %}
                            <a title="{{ d.name }}" href="/demand/{{ d.demand_id }}-{{ d.name|translit }}"
                               class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                        {% endif %}
                        <div class="offer-content">
                            {% if d.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                            <a title="{{ d.name }}" target="_blank"
                               href="/demand/{{ d.demand_id }}-{{ d.name|translit }}" class="modal-window-link"></a>
                            <a title="{{ d.name }}" href="/demand/{{ d.demand_id }}-{{ d.name|translit }}"
                               class="ajax-link n-ad-title-price">
                                {{ d.name|raw }}
                            </a>

                            <div class="a-row a-offset-0 offer-footer">
                                <div class="a-cols-2">
                                    <a title="{{ d.user_name }}" class="user-name" data-user_id="{{ d.user_id }}"
                                       href="/demand/user-{{ d.user_id }}-{{ d.user_name|translit }}">
                                        <i class="a-icon-user a-icon-gray"></i> {{ d.user_name }}
                                    </a>
                                </div>
                                <div class="a-cols-2 a-align-right">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    {% if d.user_id == user_info.info.user_id or is_admin %}
                        <ul class="options clear">
                            <li>
                                <a class="ajax-link" href="/demand/edit-{{ d.demand_id }}"><i
                                            class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                            </li>
                            <li>
                                {% if d.flag == 1 %}
                                    <a href="/demand/flag-{{ d.demand_id }}-0"><i
                                                class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                                {% else %}
                                    <a href="/demand/flag-{{ d.demand_id }}-1"><i
                                                class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                                {% endif %}
                            </li>
                            <li>
                                <a class="delete-link" href="/demand/delete-{{ d.demand_id }}"><i
                                            class="a-icon-trash a-icon-gray"></i> Удалить</a>
                            </li>
                            {% if is_admin %}
                                <li>
                                    <div class="dropdown">
                                        <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                            Дополнительно</a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                            <li>
                                                {% if d.flag_moder > 0 %}
                                                    <a href="/demand/flag_moder-{{ d.demand_id }}-0"><i
                                                                class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                                {% else %}
                                                    <a href="/demand/flag_moder-{{ d.demand_id }}-1"><i
                                                                class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                                {% endif %}
                                            </li>
                                            <li>
                                                <a class="ajax-link" href="/demand/send-message-{{ d.demand_id }}"><i
                                                            class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                            </li>
                                            <li>
                                                <a href="/update-date-add-demand-demand_id-{{ d.demand_id }}"><i
                                                            class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                            </li>

                                            <li>
                                                <a class="ajax-link" href="/add-to-top-main-11-{{ d.demand_id }}"><i
                                                            class="a-icon-thumbs-up a-icon-gray"></i>ТОП настройки</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            {% endif %}
                            <li class="satus">
                                {% if d.flag_moder == 0 %}
                                    <span class="yellow">На модерации</span>
                                {% elseif d.flag == 0 %}
                                    <span class="gray">Скрыто</span>
                                {% else %}
                                    <span class="green">Опубликовано</span>
                                {% endif %}
                            </li>
                            {% if d.user_id == user_info.info.user_id and "now"|datediff(d.date_add) > 13 %}
                                <li>
                                    <div class="update-date">
                                        <a href="/update-date-add-demand-demand_id-{{ d.demand_id }}"><i
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

                    {#% if d.user_id == user_info.info.user_id and d.light_flag == 0 and d.flag_vip_add == 0 %#}
                    {% if d.user_id == user_info.info.user_id %}
                        <ul class="options vip-options-user clear">
                            <li>
                                <!--a class="ajax-link vip-request-link" href="/vip-request-11-{{ d.demand_id }}?name={{ d.name }}&link=http://navistom.com/demand/{{ d.demand_id }}-{{ d.name|translit }}">Заказать VIP - размещение</a-->

                                <a class=" vip-request-link " href="/success-{{ d.demand_id }}-11-top">Рекламировать
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
                    {% if route.values.search %}
                        <a href="/demand/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/demand/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                    {% endif %}
                </li>
                <li>
                    {% if pagination.prev_page > 1 %}
                        {% if route.values.search %}
                            <a href="/demand/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                        {% else %}
                            <a href="/demand/page-{{ pagination.prev_page }}">«</a>
                        {% endif %}
                    {% endif %}
                </li>
                {% for p in pagination.pages %}

                    <li {% if route.values.page == p.name %} class="active" {% endif %}>
                        {% if route.values.search %}
                            <a href="/demand/{{ p.url }}/categ-{{ route.values.search }}">{{ p.name }}</a>
                        {% else %}
                            <a href="/demand/{{ p.url }}">{{ p.name }}</a>
                        {% endif %}
                    </li>
                {% endfor %}
                <li class="next-posts">
                    {% if pagination.next_page %}
                        {% if route.values.search %}
                            <a href="/demand/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                        {% else %}
                            <a href="/demand/page-{{ pagination.next_page }}">»</a>
                        {% endif %}
                    {% endif %}
                </li>
                <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                    {% if route.values.search %}
                        <a href="/demand/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                    {% else %}
                        <a href="/demand/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                    {% endif %}
                </li>
            </ul>
        {% endif %}

    </div>

    <div id="right" class="padding">
        <a title="Добавить спрос" href="/demand/add" class="add-btn "><b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить спрос</a>

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