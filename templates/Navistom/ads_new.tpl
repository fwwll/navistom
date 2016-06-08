{% extends "index_new.tpl" %}
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
            <a href="/subscribe/delete-4-{{ route.values.categ_id|default(0) }}-{{ route.values.sub_categ_id|default(0) }}">
                <span class="s-bg-red subscribe-icon"></span> &nbsp;
                Отключить подписку на новости раздела
            </a>
            {% else %}
            {% if subscribe_categ %}
            <a href="/subscribe/add-4-{{ subscribe_categ }}-{{ route.values.sub_categ_id|default(0) }}">
                {% else %}
                <a href="/subscribe/add-4-{{ route.values.categ_id|default(0) }}-{{ route.values.sub_categ_id|default(0) }}">
                    {% endif %}

                    <span class="s-bg-green subscribe-icon"></span>
                    &nbsp;Включить подписку на новости раздела
                </a>
                {% endif %}
        </div>
    {% endif %}
    <noindex>
    <div id="left">

    <form id="global-search" method="get" action="/{{ route.controller }}/search">
        <input placeholder="Поиск в разделе ПРОДАМ Б/У" type="text" value="{{ route.values.search }}" name="q"
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
        {% for a in ads %}
            <div class="item pagination-block {% if is_admin and a.flag_moder_view == 0 %}no-moder-view{% endif %} {% if a.light_flag %}light{% endif %} {% if a.color_yellow %} color_yellow{% endif %}  ">

                <div class="a-row a-offset-0">
                    <div class="a-cols-2 a-font-small a-color-gray-2">
                        {% if a.light_flag %}<span class='l_top'> <span>топ</span></span> {% endif %}
                        <a href="/ads/sub_categ-{{ a.sub_categ_id }}-{{ a.categ_name|translit }}">{{ a.categ_name }}</a>
                    </div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        {{ a.date_add|timeago }}
                    </div>
                </div>

                <div class="offer clear">

                    {% if a.url_full %}
                        <a title="{{ a.product_name }}, Б/У" href="/ads/{{ a.ads_id }}-{{ a.product_name|translit }}"
                           class="ajax-link"><img title="{{ a.product_name }}, Б/У" alt="{{ a.product_name }}, Б/У"
                                                  src="/uploads/images/offers/80x100/{{ a.url_full }}"/></a>
                    {% elseif a.image %}
                        <a title="{{ a.product_name }}, Б/У" href="/ads/{{ a.ads_id }}-{{ a.product_name|translit }}"
                           class="ajax-link"><img title="{{ a.product_name }}, Б/У" alt="{{ a.product_name }}, Б/У"
                                                  src="/uploads/images/products/80x100/{{ a.image }}"/></a>
                    {% else %}
                        <a title="{{ a.product_name }}, Б/У" href="/ads/{{ a.ads_id }}-{{ a.product_name|translit }}"
                           class="ajax-link"><img src="/uploads/images/100x80.jpg"/></a>
                    {% endif %}
                    <div class="offer-content">
                        {% if a.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <a title="{{ a.product_name }}, Б/У" target="_blank"
                           href="/ads/{{ a.ads_id }}-{{ a.product_name|translit }}" class="modal-window-link"></a>
                        <a title="{{ a.product_name }}, Б/У" href="/ads/{{ a.ads_id }}-{{ a.product_name|translit }}"
                           class="ajax-link"> {{ a.product_name }}, Б/У</a>

                        <div class="a-font-small a-color-gray">{{ a.description|raw }}</div>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <a title="{{ a.user_name }}" data-user_id="{{ a.user_id }}"
                                   href="/ads/user-{{ a.user_id }}-{{ a.user_name|translit }}"><i
                                            class="a-icon-user a-icon-gray"></i> {{ a.user_name }}</a>
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price">
                                    {{ a.price|getExchangeRates(a.currency_id, a.user_id)|number_format(2, '.', ' ') }} {{ default_currency }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {% if a.user_id == user_info.info.user_id or is_admin %}
                    <ul class="options clear">
                        <li>
                            <a class="ajax-link" href="/ads/edit-{{ a.ads_id }}"><i
                                        class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                        </li>
                        <li>
                            {% if a.flag == 1 %}
                                <a href="/ads/flag-{{ a.ads_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                            {% else %}
                                <a href="/ads/flag-{{ a.ads_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                                    Отобразить</a>
                            {% endif %}
                        </li>
                        <li>
                            <a class="delete-link" href="/ads/delete-{{ a.ads_id }}"><i
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
                                                <a href="/ads/flag_moder-{{ a.ads_id }}-0"><i
                                                            class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                            {% else %}
                                                <a href="/ads/flag_moder-{{ a.ads_id }}-1"><i
                                                            class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                            {% endif %}
                                        </li>
                                        <li>
                                            <a class="ajax-link" href="/ads/send-message-{{ a.ads_id }}"><i
                                                        class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                        </li>
                                        <li>
                                            <a href="/ads/transfer-to-products-{{ a.ads_id }}"><i
                                                        class="a-icon-share-alt a-icon-gray"></i> Перенести в новое</a>
                                        </li>
                                        <li>
                                            <a href="/update-date-add-ads-ads_id-{{ a.ads_id }}"><i
                                                        class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                        </li>


                                        <li>
                                            <a class="ajax-link" href="/add-to-top-main-4-{{ a.ads_id }}"><i
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
                                    <a href="/update-date-add-ads-ads_id-{{ a.ads_id }}"><i
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
                {# if a.user_id == user_info.info.user_id and a.light_flag == 0 and a.flag_vip_add == 0 #}
                {% if a.user_id == user_info.info.user_id %}
                    <ul class="options vip-options-user clear">
                        <li>
                            <a class=" vip-request-link  " href="/success-{{ a.ads_id }}-4-top">Рекламировать
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
        <!------------------------------------------------------>
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
                {% if route.values.user_id > 0 %}
                    <a href="/ads/page-{{ pagination.first.url }}{{ url }}">{{ pagination.first.name }}</a>
                {% elseif route.values.categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/ads/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/ads/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/ads/page-{{ pagination.first.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% endif %}
                {% elseif route.values.sub_categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/ads/page-{{ pagination.first.url }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/ads/page-{{ pagination.first.url }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% else %}
                        <a href="/ads/page-{{ pagination.first.url }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                    {% endif %}
                {% elseif route.values.user_id > 0 %}
                    <a href="/ads/page-{{ pagination.first.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.producer_id > 0 %}
                    <a href="/ads/page-{{ pagination.first.url }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% elseif route.values.product_id > 0 %}
                    <a href="/ads/page-{{ pagination.first.url }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.first.name }}</a>
                {% else %}
                    <a href="/ads/page-{{ pagination.first.url }}">{{ pagination.first.name }}</a>
                {% endif %}
            </li>
            <li>
                {% if pagination.prev_page > 1 %}
                    {% if route.values.user_id > 0 %}
                        <a href="/ads/page-{{ pagination.prev_page }}{{ url }}">«</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/ads/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">«</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/ads/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">«</a>
                        {% else %}
                            <a href="/ads/page-{{ pagination.prev_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">«</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/ads/page-{{ pagination.prev_page }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">«</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/ads/page-{{ pagination.prev_page }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">«</a>
                        {% else %}
                            <a href="/ads/page-{{ pagination.prev_page }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">«</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/ads/page-{{ pagination.prev_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/ads/page-{{ pagination.prev_page }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">«</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/ads/page-{{ pagination.prev_page }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">«</a>
                    {% else %}
                        <a href="/ads/page-{{ pagination.prev_page }}">«</a>
                    {% endif %}
                {% endif %}
            </li>
            {% for p in pagination.pages %}
                <li {% if route.values.page == p.name or (route.values.page == 0 and p.name == 1) %} class="active" {% endif %}>
                    {% if route.values.user_id > 0 %}
                        <a href="/ads/{{ p.url }}{{ url }}">{{ p.name }}</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/ads/{{ p.url }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/ads/{{ p.url }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% else %}
                            <a href="/ads/{{ p.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/ads/{{ p.url }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/ads/{{ p.url }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% else %}
                            <a href="/ads/{{ p.url }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/ads/{{ p.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/ads/{{ p.url }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/ads/{{ p.url }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ p.name }}</a>
                    {% else %}
                        <a href="/ads/{{ p.url }}">{{ p.name }}</a>
                    {% endif %}
                </li>
            {% endfor %}
            <li class="next-posts">
                {% if pagination.next_page > 0 %}
                    {% if route.values.user_id > 0 %}
                        <a href="/ads/page-{{ pagination.next_page }}{{ url }}">»</a>
                    {% elseif route.values.categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/ads/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">»</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/ads/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">»</a>
                        {% else %}
                            <a href="/ads/page-{{ pagination.next_page }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">»</a>
                        {% endif %}
                    {% elseif route.values.sub_categ_id > 0 %}
                        {% if route.values.producer_id > 0 %}
                            <a href="/ads/page-{{ pagination.next_page }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">»</a>
                        {% elseif route.values.product_id > 0 %}
                            <a href="/ads/page-{{ pagination.next_page }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">»</a>
                        {% else %}
                            <a href="/ads/page-{{ pagination.next_page }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">»</a>
                        {% endif %}
                    {% elseif route.values.user_id > 0 %}
                        <a href="/ads/page-{{ pagination.next_page }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.producer_id > 0 %}
                        <a href="/ads/page-{{ pagination.next_page }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/ads/page-{{ pagination.next_page }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">»</a>
                    {% elseif route.values.search %}
                        <a href="/ads/page-{{ pagination.next_page }}/search-{{ route.values.search }}">»</a>
                    {% else %}
                        <a href="/ads/page-{{ pagination.next_page }}">»</a>
                    {% endif %}
                {% endif %}
            </li>
            <li class="last-page {% if route.values.page == pagination.last.url %}active{% endif %}">
                {% if route.values.user_id > 0 %}
                    <a href="/ads/page-{{ pagination.last.url }}{{ url }}">{{ pagination.last.name }}</a>
                {% elseif route.values.categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/ads/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/ads/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% else %}
                        <a href="/ads/page-{{ pagination.last.url }}/categ-{{ route.values.categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% endif %}
                {% elseif route.values.sub_categ_id > 0 %}
                    {% if route.values.producer_id > 0 %}
                        <a href="/ads/page-{{ pagination.last.url }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% elseif route.values.product_id > 0 %}
                        <a href="/ads/page-{{ pagination.last.url }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% else %}
                        <a href="/ads/page-{{ pagination.last.url }}/sub_categ-{{ route.values.sub_categ_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                    {% endif %}
                {% elseif route.values.user_id > 0 %}
                    <a href="/ads/page-{{ pagination.last.url }}/user-{{ route.values.user_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.producer_id > 0 %}
                    <a href="/ads/page-{{ pagination.last.url }}/firm-{{ route.values.producer_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% elseif route.values.product_id > 0 %}
                    <a href="/ads/page-{{ pagination.last.url }}/product-{{ route.values.product_id }}-{{ route.values.translit }}">{{ pagination.last.name }}</a>
                {% else %}
                    <a href="/ads/page-{{ pagination.last.url }}">{{ pagination.last.name }}</a>
                {% endif %}
            </li>
        </ul>

    {% endif %}

    </div>

    <div id="right" class="padding">
        <a title="Добавить б/у товар" href="/ads/add" class="add-btn "><b><i class="a-icon-plus a-icon-white"></i></b>
            Добавить б/у товар</a>

        <div class="filter-title">Выбрать в разделе ПРОДАМ Б/У:</div>

        {% if route.values.user_id %}
            <a title="Убрать фильтр по продавцу" class="n-filter-user"
               href="/{{ replace_user_url }}">{{ ads[0].user_name }} <i class="a-icon-remove a-icon-gray"></i></a>
        {% endif %}

        <select class="select-2 filter-select select-as-link" id="category" name="category">


            <!--option value="/ads">Все рубрики {% if route.values.user_id %} - {{ ads[0].user_name }} {% endif %}</option-->
				<span data-test="{{ route.values|json_encode }}" id='test'>
	  <script>
          console.log($('#test').data('test'));
          console.log('33333333333333');
      </script>
	</span>


            {% if route.values.filter %}
                <option value="/ads/filter-stocks">Все
                    рубрики {% if route.values.user_id %} - {{ ads[0].user_name }} {% endif %}</option>
            {% else %}
                <option  {% if route.values.translit %}
                    {% if route.values.user_id %} value="/ads/user-{{ route.values.user_id }}-{{ route.values.translit }}"  {% else %} value="/ads"   {% endif %}
                {% else %}value="/ads"  {% endif %} >Все
                    рубрики {% if route.values.user_id %} - {{ ads[0].user_name }} {% endif %}</option>
            {% endif %}




            {% for c in categs %}
                {% if route.values.user_id %}
                    <option  {% if c.categ_id == parent_id %} selected="selected" {% endif %}
                            value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                        - {{ ads[0].user_name }} ({{ c.count }})
                    </option>
                {% else %}
                    <option {% if c.categ_id == parent_id %} selected="selected" {% endif %}
                            value="/ads/categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }})
                    </option>
                {% endif %}
            {% endfor %}
        </select>

        <select {% if sub_categs == null %} disabled="disabled" {% endif %}
                class="select-2 filter-select select-as-link" id="sub_category" name="sub_category">
            <option value="/ads/categ-{{ parent_id }}">{% if sub_categs %}Все подрубрики {% if route.values.user_id %} - {{ ads[0].user_name }} {% endif %} {% endif %}</option>
            {% for c in sub_categs %}
                {% if route.values.user_id %}
                    <option  {% if c.categ_id == route.values.sub_categ_id %} selected="selected" {% endif %}
                            value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }}
                        - {{ ads[0].user_name }} ({{ c.count }})
                    </option>
                {% else %}
                    <option {% if c.categ_id == route.values.sub_categ_id %} selected="selected" {% endif %}
                            value="/ads/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}">{{ c.name }} ({{ c.count }})
                    </option>
                {% endif %}
            {% endfor %}
        </select>

        <hr class="line">

        <select class="select-2 filter-select select-as-link" id="firms" name="firms">
            <option value="0">Все
                производители {% if route.values.user_id %} - {{ ads[0].user_name }} {% endif %}</option>
            {% for p in producers %}
                {% if route.values.user_id %}
                    {% if route.values.categ_id > 0 %}
                        <option {% if p.producer_id == parent_producer %} selected="selected" {% endif %}
                                value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/categ-{{ route.values.categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            - {{ ads[0].user_name }} ({{ p.count }})
                        </option>
                    {% elseif route.values.sub_categ_id > 0 %}
                        <option {% if p.producer_id == parent_producer %} selected="selected" {% endif %}
                                value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            - {{ ads[0].user_name }} ({{ p.count }})
                        </option>
                    {% else %}
                        <option {% if p.producer_id == parent_producer %} selected="selected" {% endif %}
                                value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            - {{ ads[0].user_name }} ({{ p.count }})
                        </option>
                    {% endif %}
                {% else %}
                    {% if route.values.categ_id > 0 %}
                        <option {% if p.producer_id == parent_producer %} selected="selected" {% endif %}
                                value="/ads/categ-{{ route.values.categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            ({{ p.count }})
                        </option>
                    {% elseif route.values.sub_categ_id > 0 %}
                        <option {% if p.producer_id == parent_producer %} selected="selected" {% endif %}
                                value="/ads/sub_categ-{{ route.values.sub_categ_id }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            ({{ p.count }})
                        </option>
                    {% else %}
                        <option {% if p.producer_id == parent_producer %} selected="selected" {% endif %}
                                value="/ads/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            ({{ p.count }})
                        </option>
                    {% endif %}
                {% endif %}
            {% endfor %}
        </select>

        <select {% if products == null %} disabled="disabled" {% endif %} class="select-2 filter-select select-as-link"
                                                                          id="products" name="products">
            <option value="0"> {% if products %}Все товары {% if route.values.user_id %} - {{ ads[0].user_name }} {% endif %} {% endif %}</option>
            {% for p in products %}
                {% if route.values.user_id %}
                    {% if route.values.categ_id > 0 %}
                        <option {% if p.product_id == route.values.product_id %} selected="selected" {% endif %}
                                value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/categ-{{ route.values.categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            - {{ ads[0].user_name }} ({{ p.count }})
                        </option>
                    {% elseif route.values.sub_categ_id > 0 %}
                        <option {% if p.product_id == route.values.product_id %} selected="selected" {% endif %}
                                value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/sub_categ-{{ route.values.sub_categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            - {{ ads[0].user_name }} ({{ p.count }})
                        </option>
                    {% else %}
                        <option {% if p.product_id == route.values.product_id %} selected="selected" {% endif %}
                                value="/ads/user-{{ route.values.user_id }}-{{ ads[0].user_name|translit }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            - {{ ads[0].user_name }} ({{ p.count }})
                        </option>
                    {% endif %}
                {% else %}
                    {% if route.values.categ_id > 0 %}
                        <option {% if p.product_id == route.values.product_id %} selected="selected" {% endif %}
                                value="/ads/categ-{{ route.values.categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            ({{ p.count }})
                        </option>
                    {% elseif route.values.sub_categ_id > 0 %}
                        <option {% if p.product_id == route.values.product_id %} selected="selected" {% endif %}
                                value="/ads/sub_categ-{{ route.values.sub_categ_id }}/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            ({{ p.count }})
                        </option>
                    {% else %}
                        <option {% if p.product_id == route.values.product_id %} selected="selected" {% endif %}
                                value="/ads/product-{{ p.product_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                            ({{ p.count }})
                        </option>
                    {% endif %}
                {% endif %}
            {% endfor %}
        </select>
        {% if route.values.user_id == 0 %}
            <hr class="line">

            <br/>

            <div class="a-row a-offset-3">
                <div class="a-cols-2">
                    <a title="Быстрый выбор" class="ajax-link" href="/ads/quick-selection"><i
                                class="a-icon-quick-selection a-icon-gray"></i> Быстрый выбор</a>
                </div>
                <div class="a-cols-2">
                    <a title="Все рубрики" class="ajax-link" href="/ads/all-categories"><i
                                class="a-icon-list-alt a-icon-gray"></i> Все рубрики</a>
                </div>
            </div>

            <hr class="line">

            <br/>

            <div class="a-row a-offset-3">
                <div class="a-cols-2">
                    <a title="Все производители" class="ajax-link" href="/ads/all-producers"><i
                                class="a-icon-barcode a-icon-gray"></i> Производители</a>
                </div>
                <div class="a-cols-2">
                    <a title="Все продавцы" class="ajax-link" href="/ads/all-salespeople"><i
                                class="a-icon-user a-icon-gray"></i> Все продавцы</a>
                </div>
            </div>

        {% endif %}

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


    </noindex>
{% endblock %}