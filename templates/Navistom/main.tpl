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

    {% if error %}
        <div class="error404">
            <div class="title">404</div>
            Запрашиваемая страница не найдена
        </div>
    {% endif %}

    {% if route.values.user_id == 0 and route.values.flag_no_view == 0 and route.values.flag_no_moder == 0 and route.values.flag_vip_add == 0 and route.values.flag_no_show ==0 %}
        <section class="n-main-nav">
            <div class="left">
                <form id="global-search" method="get" action="/search">
                    <input placeholder="Поиск по сайту..." type="text" name="q" id="global-search-input"/>
                    <button id="search-submit" type="submit">Искать</button>
                </form>

                <nav id="main-navigation">
                    <ul class="clear">
                        {% for i in 0..9 %}
                            <li>
                                <a title="{{ sections[i].name }}" target="{{ sections[i].target }}"
                                   href="{{ sections[i].link }}">
                                    {{ sections[i].icon|raw }}

                                    <div>
                                        {% if sections[i].section_id == 9 %}
                                            Сервис <br/>Запчасти
                                        {% else %}
                                            {{ sections[i].name }}
                                        {% endif %}
                                    </div>
                                </a>
                            </li>
                        {% endfor %}
                    </ul>
                </nav>
            </div>
            <div class="right">
                <div id="main-scroller" class="clear">
                    <div class="scroller-items">
                        {% for item in articles %}
                            <div>
                                <div class="scroller-item">
                                    <a title="{{ item.name }}" class="ajax-link"
                                       href="/article/{{ item.article_id }}-{{ item.name|translit }}"><img
                                                alt="{{ item.name }}" title="{{ item.name }}"
                                                src="/uploads/images/articles/100x150/{{ item.url_full }}"/>
                                        {{ item.name }}</a>
                                </div>
                            </div>
                        {% endfor %}
                    </div>
                </div>
                <div class="scroller-navi">
                    <a href="/articles" title="Все статьи" class="all">Все статьи</a>
                    <a class="prev"><i class="a-icon-chevron-left a-icon-gray"></i></a>
                    <a class="next"><i class="a-icon-chevron-right a-icon-gray"></i></a>
                </div>
            </div>
        </section>

        <!-- Top providers list -->
        {% if topProviders %}
            <noindex>
                <div class="a-row n-top-providers">

                    {% for item in topProviders %}
                        <a class="a-cols-5" target="{{ item.target }}" title="{{ item.name }}"
                           href="/provider-{{ item.provider_id }}-{{ item.link|replace({'/': '|'}) }}">
                            <img title="{{ item.name }}" alt="{{ item.name }}"
                                 src="/uploads/providers/{{ item.image }}"/>
                        </a>
                    {% endfor %}
                </div>
            </noindex>
        {% endif %}
        <!-- End top providers list -->

        <h1 style="margin:30px 0; font-size: 20px; font-family: 'PT Sans', sans-serif;"
            class="title align-center">{{ title }}</h1>
    {% endif %}

    <!-- News -->
    <div id="left">


    <div id="pagination-container-main">

    {% if banner_listing.link %}
        <noindex>
            <a class="listing-banner pagination-block" href="{{ banner_listing.link }}"
               target="{{ banner_listing.target }}" rel="nofollow">
                <img src="/uploads/banners/{{ banner_listing.image }}"/>
            </a>
        </noindex>
    {% endif %}

    <div class='home_t_lab'>
        ТОП-объявления:
    </div>
    <div class='link'>
        <a href='http://navistom.com/article/1414-tolko-top-obyyavleniya-publikuyutsya-na-glavnoy-stranitse'>
            Как сюда попасть?
        </a>
    </div>
    {% for n in news %}
        {% if n.type == 'products_new' %}

            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %}  {% if n.color_yellow %} color_yellow{% endif %}  ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/products">Продам новое</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {% if n.flag_stock %}
                            <span class="flag-stock">Акция</span>
                        {% else %}
                            <span style="font-size:10px">{{ n.date_add|timeago }}</span>
                        {% endif %}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ p.product_name }}" href="/product/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="{{ n.name }}"
                                                  src="/uploads/images/products/80x100/{{ n.image }}"></a>
                    {% else %}
                        <a title="{{ p.product_name }}" href="/product/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="{{ n.name }}" src="/uploads/images/100x80.jpg"></a>
                    {% endif %}
                    <div class="offer-content">

                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ p.product_name }}"
                           href="/product/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        <a title="{{ p.product_name }}" href="/product/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link">{{ n.name }}</a>

                        <div class="a-font-small a-color-gray">{{ n.description }}</div>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}"><i
                                            class="a-icon-user a-icon-gray"></i> {{ n.user_name }}</a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price {% if n.flag_stock %}price-stock{% endif %}">
                                    {{ n.price|getExchangeRates(n.currency_id, n.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/product/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/product/flag-{{ n.content_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/product/flag-{{ n.content_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/product/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin == 0 %}
                            <li>
                                {% if n.flag_stock %}
                                    <a class="ajax-link" href="/product/edit_stock-{{ n.content_id }}"><i
                                                class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                                {% else %}
                                    <a class="ajax-link" href="/product/add_stock-{{ n.content_id }}"><i
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
                                            {% if n.flag_stock %}
                                                <a class="ajax-link" href="/product/edit_stock-{{ n.content_id }}"><i
                                                            class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                                            {% else %}
                                                <a class="ajax-link" href="/product/add_stock-{{ n.content_id }}"><i
                                                            class="a-icon-star a-icon-gray"></i> Добавить акцию</a>
                                            {% endif %}
                                        </li>

                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/product/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/product/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/product/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/product/transfer-to-ads-{{ n.content_id }}"><i
                                                        class="a-icon-share-alt a-icon-gray"></i> Перенести в Б/У</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-products_new-product_new_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>
                                        {% if n.top_main_flag == 0 %}
                                            <!--li>
                                                        <a class="ajax-link" href="/light-content-3-{{ n.content_id }}">
                                                            <i class="a-icon-tint a-icon-gray"></i> Выделить цветом
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="ajax-link" href="/add-to-top-categ-3-{{ n.content_id }}">
                                                            <i class="a-icon-arrow-up a-icon-gray"></i>
                                                            {% if n.top_category_flag %} Отменить {% else %} Назначить {% endif %} VIP велик
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="ajax-link" href="/add-to-top-3-{{ n.content_id }}">
                                                            <i class="a-icon-circle-arrow-up a-icon-gray"></i>
                                                            {% if n.top_section_flag %} Отменить {% else %} Назначить {% endif %} VIP авто
                                                        </a>
                                                    </li-->
                                        {% endif %}
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-3-{{ n.content_id }}">
                                                <i class="a-icon-thumbs-up a-icon-gray"></i>
                                                ТОП настройки
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag_show == 0 %}
                                <span class="gray">Неактивно</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'ads' %}

            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %}   {% if n.color_yellow %} color_yellow{% endif %}    ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/ads">Продам Б/У</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != 'products/80x100/' %}
                        <a title="{{ n.name }}, Б/У" href="/ads/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="{{ n.name }}, Б/У" alt="{{ n.name }}, Б/У"
                                                  src="/uploads/images/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}, Б/У" href="/ads/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}, Б/У"
                           href="/ads/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        <a title="{{ n.name }}, Б/У" href="/ads/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"> {{ n.name }}, Б/У</a>

                        <div class="a-font-small a-color-gray">{{ n.description }}</div>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">

                                <a title="{{ n.user_name }}" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}"><i
                                            class="a-icon-user a-icon-gray"></i> {{ n.user_name }}</a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price">

                                    {{ n.price|getExchangeRates(n.currency_id, n.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/ads/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/ads/flag-{{ n.content_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                                    Скрыть</a>
                            {% else %}
                                <a href="/ads/flag-{{ n.content_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/ads/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/ads/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/ads/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/ads/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/ads/transfer-to-products-{{ n.content_id }}"><i
                                                        class="a-icon-share-alt a-icon-gray"></i> Перенести в новое</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-ads-ads_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-4-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                {% if n.pay %}
                                    <span class="green">Опубликовано</span>
                                {% else %}
                                    <div class='nopay'><span class='red2'> не оплачено | <a
                                                    href='/success-{{ a.ads_id }}-4-top'>оплатить</a></span></div>
                                {% endif %}
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'articles' %}
            <div class="item pagination-block {% if n.light_flag %}light{% endif %} ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/articles">Статьи</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="article clear">
                    <h2>
                        <a target="_blank" title="{{ n.name }}" href="/article/{{ n.content_id }}-{{ n.name|translit }}"
                           class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/article/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link">{{ n.name }}</a></h2>
                    <img title="{{ n.name }}" alt="{{ n.name }}" src="/uploads/images/articles/100x150/{{ n.image }}">

                    <div class="article-descr">
                        {{ n.description|raw }}
                    </div>
                </div>
                {% if is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/article/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            <a class="delete-link" href="/article/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/article/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/article/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>


                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-16-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 or n.date_add == '0000-00-00 00:00:00' %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'activity' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/activity">Мероприятия</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image %}
                        <div class="lector-image">
                            <a title="{{ n.name }}" href="/activity/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                      src="/uploads/images/activity/{{ n.image }}"/></a>
                        </div>
                    {% else %}
                        <a title="{{ n.name }}" href="/activity/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div style="color:#333" class="a-font-small"><i class="a-icon-calendar a-icon-gray"></i>
                            {% if n.description == '0000-00-00' %}
                                по согласованию
                            {% else %}
                                {{ n.description|rusDate }}

                                {% if n.price != '0000-00-00'  and n.description != n.price %}
                                    - {{ n.price|rusDate }}
                                {% endif %}
                            {% endif %}
                        </div>
                        <a target="_blank" title="{{ n.name }}"
                           href="/activity/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/activity/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link">{{ n.name|raw }}</a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}"><i
                                            class="a-icon-user a-icon-gray"></i> {{ n.user_name }}</a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                г. {{ n.price_description }}
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/activity/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/activity/flag-{{ n.content_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/activity/flag-{{ n.content_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/activity/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/activity/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/activity/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/activity/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-activity-activity_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-5-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'services' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/services">Сервис/Запчасти</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/service/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title="{{ n.name }}"
                                                                   src="/uploads/images/services/80x100/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/service/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}" href="/service/{{ n.content_id }}-{{ n.name|translit }}"
                           class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/service/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price">
                            {{ n.name }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" class="user-name" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ n.user_name|raw }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div title="{{ n.price_description }}" class="price">
                                    {% if n.price > 0 %}
                                        {{ n.price|getExchangeRates(n.currency_id, n.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                        {% if n.price_description %}
                                            <i class="a-icon-info-sign a-icon-gray"></i>
                                        {% endif %}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/service/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/service/flag-{{ n.content_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/service/flag-{{ n.content_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/service/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/service/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/service/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/service/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-services-service_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-9-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'demand' %}

            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/demand">Спрос/Куплю</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/demand/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                                   src="/uploads/images/demand/80x100/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/demand/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}" href="/demand/{{ n.content_id }}-{{ n.name|translit }}"
                           class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/demand/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price">
                            {{ n.name|raw }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" class="user-name" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ n.user_name }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/demand/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/demand/flag-{{ n.content_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                                    Скрыть</a>
                            {% else %}
                                <a href="/demand/flag-{{ n.content_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/demand/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/demand/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/demand/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/demand/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-demand-demand_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-11-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i>ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'labs' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}  ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/labs">З/Т лаборатории</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                                   src="/uploads/images/labs/80x100/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                           class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price">
                            {{ n.name|raw }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user }}" class="user-name" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ n.user_name|raw }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">

                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/lab/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/lab/flag-{{ n.content_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                                    Скрыть</a>
                            {% else %}
                                <a href="/lab/flag-{{ n.content_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/lab/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/lab/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/lab/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/lab/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-labs-lab_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-7-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'realty' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %} ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/realty">Недвижимость</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/realty/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                                   src="/uploads/images/realty/80x100/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/realty/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}" href="/realty/{{ n.content_id }}-{{ n.name|translit }}"
                           class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/realty/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price">
                            {{ n.name|raw }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" class="user-name" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ n.user_name }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div title="{{ n.price_description }}" class="price">
                                    {% if n.price > 0 %}
                                        {{ n.price|getExchangeRates(n.currency_id, n.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                        {% if n.price_description %}
                                            <i class="a-icon-info-sign a-icon-gray"></i>
                                        {% endif %}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/realty/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/realty/flag-{{ n.content_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                                    Скрыть</a>
                            {% else %}
                                <a href="/realty/flag-{{ n.content_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/realty/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/realty/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/realty/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/realty/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-realty-realty_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-8-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'diagnostic' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow {% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/diagnostic">Диагностика</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/diagnostic/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title=" {{ n.name }}" alt=" {{ n.name }}"
                                                                   src="/uploads/images/diagnostic/80x100/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/diagnostic/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}"
                           href="/diagnostic/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/diagnostic/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price">
                            {{ n.name|raw }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" class="user-name" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ n.user_name|raw }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                г. {{ n.price_description }}
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/diagnostic/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/diagnostic/flag-{{ n.content_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/diagnostic/flag-{{ n.content_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/diagnostic/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/diagnostic/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/diagnostic/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/diagnostic/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-diagnostic-diagnostic_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-10-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'resume' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %} ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">{% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/work/resume">Резюме</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image %}
                        <a title="{{ n.name }}" href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="n.name" src="/uploads/images/work/80x100/{{ n.image }}"/></a>
                    {% elseif n.description %}
                        <a title="{{ n.name }}" href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="n.name" src="/uploads/users/avatars/full/{{ n.description }}"></a>
                    {% else %}
                        <img src="/uploads/images/100x80.jpg">
                    {% endif %}

                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}"
                           href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link">
                            Резюме {{ n.name }}, г. {{ n.price_description }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" class="user-name" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ n.user_name }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price">
                                    {% if n.price > 0 %}
                                        {{ n.price|number_format(0, '', ' ') }} {{ n.currency_name }}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/work/resume/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/work/resume/flag-{{ n.content_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/work/resume/flag-{{ n.content_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/work/resume/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/work/resume/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/work/resume/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/work/resume/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-work-work_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-6-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'vacancies' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %} ">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">{% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/work/vacancy">Вакансии</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                  src="/uploads/images/work/80x100/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img src="/uploads/images/100x80.jpg"></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a target="_blank" title="{{ n.name }}"
                           href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        <a title="{{ n.name }}" href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link">
                            Требуется {{ n.name }}
                        </a>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" class="user-name" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}">
                                    <i class="a-icon-user a-icon-gray"></i> {{ n.user_name|raw }}
                                </a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price">
                                    {% if n.price > 0 %}
                                        {{ n.price|number_format(0, '', ' ') }} {{ default_currency }}
                                    {% endif %}
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if n.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/work/vacancy/edit-{{ n.content_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if n.flag == 1 %}
                                <a href="/work/vacancy/flag-{{ n.content_id }}-0"><i
                                            class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/work/vacancy/flag-{{ n.content_id }}-1"><i
                                            class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/work/vacancy/delete-{{ n.content_id }}"><i
                                        class="a-icon-trash a-icon-gray"></i> Удалить</a>
                        </li>
                        {% if is_admin %}
                            <li>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i>
                                        Дополнительно</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>
                                            {% if n.flag_moder > 0 %}
                                                <a href="/work/vacancy/flag_moder-{{ n.content_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/work/vacancy/flag_moder-{{ n.content_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/work/vacancy/send-message-{{ n.content_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-vacancies-vacancy_id-{{ n.content_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/light-content-15-{{ n.content_id }}"><i
                                                        class="a-icon-tint a-icon-gray"></i> Выделить цветом</a>
                                        </li>

                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-15-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        {% endif %}
                        <li class="satus">
                            {% if n.flag_moder == 0 %}
                                <span class="yellow">На модерации</span>
                            {% elseif n.flag == 0 %}
                                <span class="gray">Скрыто</span>
                            {% else %}
                                <span class="green">Опубликовано</span>
                            {% endif %}
                        </li>
                    </ul>
                {% endif %}

                {% if route.values.flag_vip_add and is_admin %}
                    <ul style="margin-top:20px" class="options vip-options clear">
                        <li>
                            Тип VIP - размещения:

                            {% if n.vip %}
                                <b>
                                    {% if n.vip.type == 1 %}
                                        500 грн.
                                    {% elseif n.vip.type == 2 %}
                                        250 грн.
                                    {% else %}
                                        100 грн.
                                    {% endif %}
                                </b>
                                , отправлено: {{ n.vip.date|timeago }}
                            {% else %}
                                <b>уточнить</b>
                            {% endif %}
                        </li>
                        <li style="float:right; margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% endif %}

    {% endfor %}

    </div>

    {% if pagination.pages %}
        <ul class="a-pagination">
            <li>
                {% if pagination.prev_page > 0 %}
                    {% if route.values.user_id %}
                        <a href="/main/user-{{ route.values.user_id }}-{{ route.values.translit }}/page-{{ pagination.prev_page }}">«</a>
                    {% else %}
                        <a href="/main/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page == p.name %} class="active" {% endif %}>
                    {% if route.values.user_id %}
                        <a href="/main/user-{{ route.values.user_id }}-{{ route.values.translit }}/{{ p.url }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/main/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}

                    {% if route.values.user_id %}
                        <a href="/main/user-{{ route.values.user_id }}-{{ route.values.translit }}/page-{{ pagination.next_page }}">»</a>
                    {% elseif route.values.flag_no_moder %}
                        <a href="/main/no-moder-1/page-{{ pagination.next_page }}">»</a>
                    {% elseif route.values.flag_no_show %}
                        <a href="/main/no-show-1/page-{{ pagination.next_page }}">»</a>
                    {% else %}
                        <a href="/main/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
        </ul>
    {% endif %}
    </div>
    <div id="right">
        <div style="margin: 0 0 30px -9px" class="social-parent">
            <a title="Вконтакте" href="http://vk.com/id95980050" target="_blank" class="social-link vk"></a>
            <a title="Facebook" href="https://www.facebook.com/navistom" target="_blank"
               class="social-link facebook"></a>
            <a title="Twitter" href="https://twitter.com/navistom" class="social-link twitter" target="_blank"></a>
            <a title="Google+" href="https://plus.google.com/u/0/107274476270243980702/posts" target="_blank"
               class="social-link google"></a>
            <a title="Обратная связь" href="/feedback" class="social-link feedback ajax-link"></a>
        </div>
        {% if banner.link or banner.code %}
            <div id="fixed-banner">
                {% if banner.code %}
                    {{ banner.code|raw }}
                {% else %}
                    <a href="{{ banner.link }}" target="{{ banner.target }}">
                        <img src="/uploads/banners/{{ banner.image }}"/>
                    </a>
                {% endif %}
            </div>
        {% endif %}
    </div>
    {% if is_admin %}
        <div class='is_admin'></div>
    {% else %}
        <style>

            /*  .listing-banner{
              height:1px;
              width:0px;
              box-shadow:none;
              border: none;
              margin:none;
             }
             */
            item + listing-banner {
                height: 1px;
                width: 0px;
                box-shadow: none;
                border: none;
                margin: none;
            }

            .listing-banner: first-child {
                display: block;
            }
        </style>
    {% endif %}
    <!-- End News -->

{% endblock %}

