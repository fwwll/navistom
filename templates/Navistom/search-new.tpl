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

    <!-- Search -->

    <h2 class="main-title">Поиск по сайту</h2>

    <form id="global-search" method="get" action="/search">
        <input placeholder="Поиск..." type="text" name="q" value="{{ q }}" id="global-search-input"/>
        <button id="search-submit" type="submit"></button>
        <a href="#" id="search-category">Все разделы <i
                    class="a-icon-chevron-up a-icon-chevron-down a-icon-gray"></i></a>

        <nav id="search-menu">
            <div>
                <ul class="clear">
                    {% for i in 0..9 %}
                        <li>
                            <a title="{{ sections[i].name }}" target="{{ sections[i].target }}"
                               href="{{ sections[i].link }}/search-{{ q }}">
                                {{ sections[i].icon|raw }}

                                <div>
                                    {{ sections[i].name }}
                                    <div class="descr">
                                        {% if sections[i].section_id == 6 %}
                                            {{ contents_count[6]+contents_count[15] }} предложений
                                        {% else %}
                                            {{ contents_count[sections[i].section_id] }} предложений
                                        {% endif %}
                                    </div>
                                </div>
                            </a>
                        </li>
                    {% endfor %}
                </ul>
            </div>
        </nav>
    </form>

    <!-- End Search -->

    <div id="left">
    {% if categs %}
        <div class="search-categs">
            <b>Возможно, Вы искали:</b>
            <ul>
                {% for c in categs %}
                    {% if c.ctrl == 'work' %}
                        <li>
                            <a href="/work/resume/categ-{{ c.categ_id }}-{{ c.name|translit }}">Резюме
                                - {{ c.name }}</a>
                        </li>
                        <li>
                            <a href="/work/vacancy/categ-{{ c.categ_id }}-{{ c.name|translit }}">Вакансии
                                - {{ c.name }}</a>
                        </li>
                    {% elseif c.ctrl == 'products' %}
                        <li>
                            <a href="/products/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}</a>
                        </li>
                        {% if c.flag_no_ads == 0 %}
                            <li>
                                <a href="/ads/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }} Б/У</a>
                            </li>
                        {% endif %}
                    {% elseif c.ctrl == 'sections' %}
                        <li>
                            <a href="{{ c.categ_id }}">{{ c.name }}</a>
                        </li>
                    {% else %}
                        <li>
                            <a href="/{{ c.ctrl }}/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}</a>
                        </li>
                    {% endif %}
                {% endfor %}
            </ul>
        </div>
    {% endif %}
    <div id="pagination-container">
    {% for n in search_result %}

        {% if n.type == 'products_new' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ p.product_name }}" href="/product/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="{{ n.name }}"
                                                  src="/uploads/images/products/142x195/{{ n.image }}"></a>
                    {% else %}
                        <a title="{{ p.product_name }}" href="/product/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="{{ n.name }}" src="/uploads/images/100x80.jpg"></a>
                    {% endif %}
                    <div class="offer-content">
                        <div class="a-align-right a-float-right">
                            {% if n.flag_stock %}
                                <div class='action_label'><span class="flag-stock">Акция</span></div>
                            {% else %}
                                <span>{{ n.date_add|timeago }}</span>
                            {% endif %}
                        </div>
                        <div class='link_label'>
                            <a href="/products">Продам новое</a>
                        </div>
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a target="_blank" title="{{ p.product_name }}"
                               href="/product/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ p.product_name }}" href="/product/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link">{{ n.name }}</a>
                        </div>
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
                        <li style="float:right;margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'ads' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
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
                        <div style="position:absolute;right:0">
                            {{ n.date_add|timeago }}
                        </div>
                        <div class='link_label'><a href="/ads">Продам Б/У</a></div>
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a target="_blank" title="{{ n.name }}, Б/У"
                               href="/ads/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ n.name }}, Б/У" href="/ads/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link"> {{ n.name }}, Б/У</a>
                        </div>
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
                                {% if (n.pay and n.time_show > 0) or n.group_id==10 %}
                                    <span class="green {% if n.time_show <9 and  n.group_id!=10 %}red2 {% endif %}">
  {% if(n.group_id !=10) %}
      Осталось {{ n.time_show }} дней публикации
      {% if n.time_show <9 %}
          | <a href='/success-{{ n.content_id }}-4-extend' class='success_link'>продлить</a>
      {% endif %}
  {% else %}
      Опубликовано
  {% endif %}
  
  </span>
                                {% else %}
                                    <div class='nopay'><span class='red2'> не оплачено | <a
                                                    href='/success-{{ n.content_id }}-4-top'>оплатить</a></span></div>
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
                        <li style="float:right;margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'articles' %}
            <div class="item pagination-block {% if n.light_flag %}light{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2"><a href="/articles">Статьи</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="article clear">
                    <h2><a title="{{ n.name }}" href="/article/{{ n.content_id }}-{{ n.name|translit }}"
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
                                            <a class="ajax-link" href="/light-content-16-{{ n.content_id }}"><i
                                                        class="a-icon-tint a-icon-gray"></i> Выделить цветом</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-16-{{ n.content_id }}"><i
                                                        class="a-icon-circle-arrow-up a-icon-gray"></i> VIP авто</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-16-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> VIP самолет</a>
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
            </div>
        {% elseif n.type == 'activity' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
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
                    <div class="offer-content gs">
                        <div class="a-align-right a-float-right gs">
                            {{ n.date_add|timeago }}
                        </div>
                        <div class='link_label'><a href="/activity">Мероприятия</a></div>
                        <div style="color:#333" class="a-font-small">
                            <i class="a-icon-calendar a-icon-gray"></i>
                            {% if n.description == '0000-00-00' %}
                                по согласованию
                            {% else %}
                                {{ n.description|rusFormat }}
                                {% if n.price != '0000-00-00' and n.description != n.price %}
                                    - {{ n.price|rusFormat }}
                                {% endif %}
                            {% endif %}
                        </div>
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a target="_blank" title="{{ n.name }}"
                               href="/activity/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ n.name }}" href="/activity/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link">{{ n.name|raw }}</a>
                        </div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ n.user_name }}" data-user_id="{{ n.user_id }}"
                                   href="/main/user-{{ n.user_id }}-{{ n.user_name|translit }}"><i
                                            class="a-icon-user a-icon-gray"></i> {{ n.user_name }}</a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="city">
                                    г. {{ n.price_description }}
                                </div>
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
                        <li style="float:right;margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'services' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2"><a href="/services">Сервис</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <img title="{{ n.name }}" src="/uploads/images/services/80x100/{{ n.image }}"/>
                    {% else %}
                        <img src="/uploads/images/100x80.jpg"/>
                    {% endif %}
                    <div class="offer-content">
                        <a title="{{ n.name }}" href="/service/{{ n.content_id }}-{{ n.name|translit }}"
                           class="n-ad-title-price ajax-link">
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
                                            <a class="ajax-link" href="/light-content-9-{{ n.content_id }}"><i
                                                        class="a-icon-tint a-icon-gray"></i> Выделить цветом</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-9-{{ n.content_id }}"><i
                                                        class="a-icon-circle-arrow-up a-icon-gray"></i> VIP авто</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-9-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> VIP самолет</a>
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
            </div>
        {% elseif n.type == 'demand' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %}">
                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2"><a href="/demand">Спрос</a></div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ n.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">
                    {% if n.image != '' %}
                        <img title="{{ n.name }}" alt="{{ n.name }}" src="/uploads/images/demand/80x100/{{ n.image }}"/>
                    {% else %}
                        <img src="/uploads/images/100x80.jpg"/>
                    {% endif %}
                    <div class="offer-content">
                        <a title="{{ n.name }}" href="/demand/{{ n.content_id }}-{{ n.name|translit }}"
                           class="n-ad-title-price ajax-link">
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
                                            <a class="ajax-link" href="/light-content-11-{{ n.content_id }}"><i
                                                        class="a-icon-tint a-icon-gray"></i> Выделить цветом</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-11-{{ n.content_id }}"><i
                                                        class="a-icon-circle-arrow-up a-icon-gray"></i> VIP авто</a>
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-11-{{ n.content_id }}"><i
                                                        class="a-icon-thumbs-up a-icon-gray"></i> VIP самолет</a>
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
            </div>
        {% elseif n.type == 'labs' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                                   src="/uploads/images/labs/142x195/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        <div class="a-align-right a-float-right">
                            {{ n.date_add|timeago }}
                        </div>
                        <div class='link_label'><a href="/labs">З/Т лаборатории</a></div>
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a target="_blank" title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                               class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ n.name }}" href="/lab/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link n-ad-title-price">
                                {{ n.name|raw }}
                            </a>
                        </div>
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
                        <li style="float:right;margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'realty' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/realty/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                                   src="/uploads/images/realty/142x195/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/realty/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link n-ad-title-price"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        <div class="a-align-right a-float-right">
                            {{ n.date_add|timeago }}
                        </div>
                        <div class='link_label'><a href="/realty">Аренда/Продажа</a></div>
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a target="_blank" title="{{ n.name }}"
                               href="/realty/{{ n.content_id }}-{{ n.name|translit }}" class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ n.name }}" href="/realty/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link n-ad-title-price">
                                {{ n.name|raw }}
                            </a>
                        </div>
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
                        <li style="float:right;margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>

        {% elseif n.type == 'resume' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                <div class="offer clear">
                    {% if n.image %}
                        <a title="{{ n.name }}" href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="n.name" src="/uploads/images/work/160x200/{{ n.image }}"/></a>
                    {% elseif n.description %}
                        <a title="{{ n.name }}" href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="n.name" src="/uploads/users/avatars/full/{{ n.description }}"></a>
                    {% else %}
                        <img src="/uploads/images/100x80.jpg">
                    {% endif %}
                    <div class="offer-content">
                        <div class="a-align-right a-float-right">
                            {{ n.date_add|timeago }}
                        </div>
                        <div class='link_label'>
                            <a href="/work/resume">Резюме</a>
                        </div>
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a target="_blank" title="{{ n.name }}"
                               href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}"
                               class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ n.name }}" href="/work/resume/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link">
                                Резюме {{ n.name }}, г. {{ n.price_description }}
                            </a>
                        </div>
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
                        <li style="float:right;margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% elseif n.type == 'vacancies' %}
            <div class="item pagination-block {% if is_admin and n.flag_moder_view == 0 %}no-moder-view{% endif %} {% if n.light_flag %}light{% endif %} {% if n.color_yellow %} color_yellow{% endif %}">
                {% if n.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                <div class="offer clear">
                    {% if n.image != '' %}
                        <a title="{{ n.name }}" href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img title="{{ n.name }}" alt="{{ n.name }}"
                                                  src="/uploads/images/work/full/{{ n.image }}"/></a>
                    {% else %}
                        <a title="{{ n.name }}" href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}"
                           class="ajax-link"><img src="/uploads/images/100x80.jpg"></a>
                    {% endif %}
                    <div class="offer-content">
                        <div class="a-align-right a-float-right">
                            {{ n.date_add|timeago }}
                        </div>
                        <div class='link_label'><a href="/work/vacancy">Вакансии</a></div>
                        {% if n.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic'>
                            <a target="_blank" title="{{ n.name }}"
                               href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}"
                               class="modal-window-link"></a>
                        </div>
                        <div class='name_ta'>
                            <a title="{{ n.name }}" href="/work/vacancy/{{ n.content_id }}-{{ n.name|translit }}"
                               class="ajax-link">
                                Требуется {{ n.name }}
                            </a>
                        </div>
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
                        <li style="float:right;margin-right:0">
                            <a href="/vip-request-delete-{{ n.section_id }}-{{ n.content_id }}"><i
                                        class="a-icon-remove a-icon-gray"></i> Удалить заявку</a>
                        </li>
                    </ul>
                {% endif %}
            </div>
        {% endif %}

    {% else %}
        <div class="a-mess-yellow">По Вашему запросу ничего не найдено</div>
        <div class="item">
            {% include 'demand-add-search.tpl' %}
        </div>
        <!--<div class="a-mess-yellow">По Вашему запросу ничего не найдено</div>-->
    {% endfor %}

    {% if banner_listing.link %}
        <a class="listing-banner pagination-block" href="{{ banner_listing.link }}"
           target="{{ banner_listing.target }}">
            <img src="/uploads/banners/{{ banner_listing.image }}"/>
        </a>
    {% endif %}

    </div>

    {% if pagination.pages %}
        <ul class="a-pagination">
            <li class="first-page {% if route.values.page == 1 or route.values.page == 0 %}active{% endif %}">
                {% if route.values.user_id %}
                    <a href="/search-{{ q }}/user-{{ route.values.user_id }}-{{ route.values.translit }}/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/search-{{ q }}/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.user_id %}
                        <a href="/search-{{ q }}/user-{{ route.values.user_id }}-{{ route.values.translit }}/page-{{ pagination.prev_page }}">«</a>
                    {% else %}
                        <a href="/search-{{ q }}/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page == p.name or (route.values.page == 0 and p.name == 1) %} class="active" {% endif %}>
                    {% if route.values.user_id %}
                        <a href="/search-{{ q }}/user-{{ route.values.user_id }}-{{ route.values.translit }}/{{ p.url }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/search-{{ q }}/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}

                    {% if route.values.user_id %}
                        <a href="/user-{{ route.values.user_id }}-{{ route.values.translit }}/page-{{ pagination.next_page }}">»</a>
                    {% elseif route.values.q %}
                        <a href="/search-{{ q }}/page-{{ pagination.next_page }}">»</a>
                    {% else %}
                        <a href="/search-{{ q }}/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.user_id %}
                    <a href="/search-{{ q }}/user-{{ route.values.user_id }}-{{ route.values.translit }}/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/search-{{ q }}/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>
    {% endif %}
    </div>
    <div id="right">
        {% if banner.link %}
            <a id="fixed-banner" href="{{ banner.link }}" target="{{ banner.target }}">
                <img src="/uploads/banners/{{ banner.image }}"/>
            </a>
        {% endif %}
    </div>

    <!-- End News -->


{% endblock %}