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
                <a href="/subscribe/delete-6-{{ route.values.categ_id|default(0) }}-{{ route.values.city_id|default(0) }}">
                    <span class="s-bg-red subscribe-icon"></span> &nbsp;
                    Отключить подписку на новости раздела
                </a>
            {% else %}
                <a href="/subscribe/add-6-{{ route.values.categ_id|default(0) }}-{{ route.values.city_id|default(0) }}">
                    <span class="s-bg-green subscribe-icon"></span>
                    &nbsp;Включить подписку на новости раздела
                </a>
            {% endif %}
        </div>
    {% endif %}

    <div id="left">

    <form id="global-search" method="get" action="/work/resume/search">
        <input placeholder="Поиск в разделе РЕЗЮМЕ" type="text" value="{{ route.values.search }}" name="q"
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
        {% for r in resume %}

            <div class="item pagination-block {% if is_admin and r.flag_moder_view == 0 %}no-moder-view{% endif %} {% if r.light_flag %}light{% endif %} {% if r.color_yellow %} color_yellow{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">

                        {% if r.light_flag %}<span class='l_top'><span>топ</span></span> {% endif %}
                        {% for key, value in r.categs %}
                            <a title="{{ value }}"
                               href="/work/resume/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>&nbsp;&nbsp;
                        {% endfor %}
                    </div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ r.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if r.image != '' %}
                        <a title="{{ r.categs|join(', ') }}"
                           href="/work/resume/{{ r.work_id }}-{{ r.categs|join('-')|translit }}" class="ajax-link"><img
                                    title="{{ r.categs|join(', ') }}" src="/uploads/images/work/80x100/{{ r.image }}"/></a>
                    {% elseif r.avatar != '' and r.avatar != 'none.jpg' %}
                        <a title="{{ r.categs|join(', ') }}"
                           href="/work/resume/{{ r.work_id }}-{{ r.categs|join('-')|translit }}" class="ajax-link"><img
                                    title="{{ r.user_name }} {{ r.user_surname }}"
                                    src="/uploads/users/avatars/full/{{ r.avatar }}"/></a>
                    {% else %}
                        <a title="{{ r.categs|join(', ') }}"
                           href="/work/resume/{{ r.work_id }}-{{ r.categs|join('-')|translit }}" class="ajax-link"><img
                                    src="/uploads/images/100x80.jpg"></a>
                    {% endif %}

                    <div class="offer-content">
                        {% if r.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a title="{{ r.categs|join(', ') }}" target="_blank"
                           href="/work/resume/{{ r.work_id }}-{{ r.categs|join('-')|translit }}"
                           class="modal-window-link"></a>
                        <a title="{{ r.categs|join(', ') }}"
                           href="/work/resume/{{ r.work_id }}-{{ r.categs|join('-')|translit }}" class="ajax-link">
                            Резюме {{ r.categs|join(', ')|lower }}, г. {{ r.city_name }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ r.user_name }} {{ r.user_surname }}" class="user-name"
                                   data-user_id="{{ r.user_id }}"
                                   href="/work/resume/user-{{ r.user_id }}-{{ r.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ r.user_name }} {{ r.user_surname }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price">
                                    {% if r.price > 0 %}
                                        {{ r.price|number_format(0, '', ' ') }} {{ r.currency_name }}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if r.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/work/resume/edit-{{ r.work_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if r.flag == 1 %}
                                <a href="/work/resume/flag-{{ r.work_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/work/resume/flag-{{ r.work_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/work/resume/delete-{{ r.work_id }}"><i
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
                                                <a href="/work/resume/flag_moder-{{ r.work_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/work/resume/flag_moder-{{ r.work_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/work/resume/send-message-{{ r.work_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-work-work_id-{{ r.work_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-6-{{ r.work_id }}"><i
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
                        {% if r.user_id == user_info.info.user_id and "now"|datediff(r.date_add) > 13 %}
                            <li>
                                <div class="update-date">
                                    <a href="/update-date-add-work-work_id-{{ r.work_id }}"><i
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

                {#% if r.user_id == user_info.info.user_id and r.light_flag == 0 and r.flag_vip_add == 0 %#}
                {% if r.user_id == user_info.info.user_id %}
                    <ul class="options vip-options-user clear">
                        <li>
                            <!--a class="ajax-link vip-request-link" href="/vip-request-6-{{ r.work_id }}?name=Резюме {{ r.categs|join(', ')|lower }}, г. {{ r.city_name }}&link=http://navistom.com/work/resume/{{ r.work_id }}-{{ r.categs|join('-')|translit }}">Заказать VIP - размещение</a-->
                            <a class=" vip-request-link " href="/success-{{ r.work_id }}-6-top">Рекламировать
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
                    <a href="/work/resume/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/work/resume/page-{{ pagination.first.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/work/resume/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/work/resume/page-{{ pagination.first.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.search %}
                    <a href="/work/resume/page-{{ pagination.first.url }}/search-{{ route.values.search }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/work/resume/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.search %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}/search-{{ route.values.search }}">«</a>
                    {% else %}
                        <a href="/work/resume/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page == p.name or (route.values.page == 0 and p.name == 1) %} class="active" {% endif %}>
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/resume/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.tag_id > 0 %}
                        <a href="/work/resume/{{ p.url }}/tag-{{ route.values.tag_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/work/resume/{{ p.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.search %}
                        <a href="/work/resume/{{ p.url }}/search-{{ route.values.search }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/work/resume/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.user_id > 0 %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/work/resume/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% else %}
                        <a href="/work/resume/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.categ_id > 0 %}
                    <a href="/work/resume/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 %}
                    <a href="/work/resume/page-{{ pagination.last.url }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.city_id > 0 and route.values.categ_id > 0 %}
                    <a href="/work/resume/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/city-{{ route.values.city_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.user_id > 0 %}
                    <a href="/work/resume/page-{{ pagination.last.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.search %}
                    <a href="/work/resume/page-{{ pagination.last.url }}/search-{{ route.values.search }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/work/resume/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>
    {% endif %}

    </div>

    <div id="right" class="padding">
        <a title="Добавить резюме" href="/work/resume/add" class="add-btn "><b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить резюме</a>

        <hr class="line">
        <br/>

        <div class="n-work-btns">
            {% if route.values.search %}
                <a title="Резюме" class="active" href="/work/resume/search-{{ route.values.search }}">
                    Резюме
                </a>
                <a title="Вакансии" href="/work/vacancy/search-{{ route.values.search }}">
                    Вакансии
                </a>
            {% else %}
                <a title="Резюме" class="active" href="/work/resume">
                    Резюме
                </a>
                <a title="Вакансии" href="/work/vacancy">
                    Вакансии
                </a>
            {% endif %}
        </div>

        <hr class="line">

        <select class="select-2 filter-select select-as-link" id="category" name="category">
            <option value="0">Все специализации</option>
            {% for c in categories %}
                <option {% if c.categ_id == route.values.categ_id %} selected="selected" {% endif %}
                        value="/work/resume/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }})
                </option>
            {% endfor %}
        </select>

        <hr class="line">

        <select class="select-2 filter-select select-as-link" id="region" name="region">
            <option value="0">Все города</option>
            {% for c in cities %}
                {% if route.values.categ_id > 0 %}
                    <option {% if c.city_id == route.values.city_id %} selected="selected" {% endif %}
                            value="/work/resume/categ-{{ route.values.categ_id }}/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }}
                        ({{ c.count }})
                    </option>
                {% else %}
                    <option {% if c.city_id == route.values.city_id %} selected="selected" {% endif %}
                            value="/work/resume/city-{{ c.city_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }})
                    </option>
                {% endif %}
            {% endfor %}
        </select>

        <hr class="line">

        <select class="select-2 filter-select select-as-link" id="price" name="price">
            <option value="0">Зарплата</option>
            <option {% if route.values.max == 1500 %} selected="selected" {% endif %} value="/work/resume/price-0-1500">
                До 1500 грн.
            </option>
            <option {% if route.values.max == 3500 %} selected="selected" {% endif %}
                    value="/work/resume/price-1501-3500">От 1501 до 3500 грн.
            </option>
            <option {% if route.values.max == 7000 %} selected="selected" {% endif %}
                    value="/work/resume/price-3501-7000">От 3501 до 7000 грн.
            </option>
            <option {% if route.values.max == 15000 %} selected="selected" {% endif %}
                    value="/work/resume/price-7001-15000">От 7001 до 15000 грн.
            </option>
            <option {% if route.values.min == 15000 %} selected="selected" {% endif %}
                    value="/work/resume/price-15000-0">Свыше 15000 грн.
            </option>
            <option value="/work/resume/price-0-0">Без указания</option>
        </select>

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