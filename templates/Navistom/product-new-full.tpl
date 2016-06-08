{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

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

    {% if ajax %}
        <div style="width:700px">

        {% if product.flag_delete > 0 %}
            <script>
                window.location.href = "/products/sub_categ-{{product.sub_categ_id}}-{{product.categ_name|translit}}#404";
            </script>
        {% endif %}

    {% else %}

        {% if product.stock_flag %}
            <form id="global-search" method="get" action="/{{ route.controller }}/filter-stocks/search">
                <input placeholder="Поиск в разделе АКЦИИ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit">Искать</button>
            </form>
        {% else %}
            <form id="global-search" method="get" action="/{{ route.controller }}/search">
                <input placeholder="Поиск в разделе ПРОДАМ НОВОЕ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit">Искать</button>
            </form>
        {% endif %}

        <div class="item">

        {% if product.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/product/edit-{{ product.product_new_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if product.flag == 1 %}
                        <a href="/product/flag-{{ product.product_new_id }}-0"><i
                                    class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                    {% else %}
                        <a href="/product/flag-{{ product.product_new_id }}-1"><i
                                    class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/product/delete-{{ product.product_new_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin == 0 %}
                    <li>
                        {% if product.stock_flag %}
                            <a class="ajax-link" href="/product/edit_stock-{{ product.product_new_id }}"><i
                                        class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                        {% else %}
                            <a class="ajax-link" href="/product/add_stock-{{ product.product_new_id }}"><i
                                        class="a-icon-star a-icon-gray"></i> Добавить акцию</a>
                        {% endif %}
                    </li>
                {% endif %}
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if product.stock_flag %}
                                        <a class="ajax-link" href="/product/edit_stock-{{ product.product_new_id }}"><i
                                                    class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                                    {% else %}
                                        <a class="ajax-link" href="/product/add_stock-{{ product.product_new_id }}"><i
                                                    class="a-icon-star a-icon-gray"></i> Добавить акцию</a>
                                    {% endif %}
                                </li>

                                <li>
                                    {% if product.flag_moder > 0 %}
                                        <a href="/product/flag_moder-{{ product.product_new_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/product/flag_moder-{{ product.product_new_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/product/send-message-{{ product.product_new_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/product/transfer-to-ads-{{ product.product_new_id }}"><i
                                                class="a-icon-share-alt a-icon-gray"></i> Перенести в Б/У</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-products_new-product_new_id-{{ product.product_new_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-3-{{ product.product_new_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if product.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif product.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if product.user_id == user_info.info.user_id and "now"|datediff(product.date_add) > 13 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-products_new-product_new_id-{{ product.product_new_id }}"><i
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

    {% endif %}

    <div class="a-row a-offset-0">
        <div class="a-cols-4 a-font-small a-color-gray">
            {% if product.stock_flag %} {{ product.stock_date|timeago }} {% else %} {{ product.date_add|timeago }} {% endif %}
            &nbsp; | &nbsp;
            {{ product.views }} <i class="a-icon-eye-open a-icon-gray"></i>&nbsp; | &nbsp;<span class="raceta2 cor_rac"
                                                                                                onclick='raceta()'
                                                                                                title='рекламировать'></span>
        </div>
        <!--div class="a-cols-2 a-font-small a-color-gray a-align-right">
    	<a href="/">NaviStom Украина</a> -
        <a href="/products">Продам новое</a> -
        <a title="{{ product.parent_categ }}" href="/products/categ-{{ product.categ_id }}-{{ product.parent_categ|translit }}">{{ product.parent_categ }}</a> -
        <a title="{{ product.categ_name }}" href="/products/sub_categ-{{ product.sub_categ_id }}-{{ product.categ_name|translit }}">{{ product.categ_name }}</a>
    </div-->
    </div>

    <h1 class="full-title">
        {% if product.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}

        {% if product.stock_flag %} <span class="navi-stock-marker">Акция</span> {% endif %}
        {{ product.product_name }}
    </h1>

    <div class="a-font-small a-color-gray">{{ product.description|raw }}</div>
    <div class="n-ad-full a-clear">
    <div class="col-1">
        {% if product.image and  product.image != 'none.jpg' %}
            <img title="{{ product.product_name }}" alt="{{ product.product_name }}"
                 src="/uploads/images/products/160x200/{{ product.image }}"/>
        {% else %}
            <img title="{{ product.product_name }}" alt="{{ product.product_name }}" src="/uploads/images/160x200.jpg"/>
        {% endif %}
        <ul id="ad-info-list">
            <li>
                {% if product.stock_flag %}
                    <p>
            	<span class="price-old">
                	{{ price|number_format(2, '.', ' ') }} {{ currency }}
                </span>
                    </p>
                    <span class="stock-price-full">
                	{{ stock_price|number_format(2, '.', ' ') }} {{ currency }}
                </span>

                {% else %}
                    <span class="price-full">
                	{{ price|number_format(2, '.', ' ') }} {{ currency }}
                </span>
                {% endif %}

                <div style="margin:5px 0">
                    {% for p in prices %}
                        <div>{{ p.val|number_format(2, '.', ' ') }} {{ p.name }}</div>
                    {% endfor %}
                </div>

                {% if product.price_description and product.stock_flag %}
                    <div class="a-font-small a-color-gray">{{ product.price_description|raw }}</div>
                {% endif %}
            </li>
            <li>
                <a href="/products/user-{{ product.user_id }}-{{ product.user_name|translit }}">
                    <i class="a-icon-user a-icon-gray"></i>&nbsp; {{ product.user_name }}
                </a>
            </li>
            {% for key, value in product.phones %}
                {% if value != '' %}
                    <li>
                        <i class="a-icon-phone a-icon-gray"></i>&nbsp; {{ value }}
                    </li>
                {% endif %}
            {% endfor %}
            {% if product.site %}
                <li>
                    <i class="a-icon-link a-icon-gray"></i>&nbsp;
                    <a target="_blank" href="{{ product.site }}">Веб сайт</a>
                </li>
            {% endif %}
            {% if product.icq %}
                <li>
                    <span class="a-color-gray">ICQ:</span> {{ product.icq }}
                </li>
            {% endif %}
            {% if product.skype %}
                <li>
                    <i class="a-icon-skype a-icon-gray"></i> {{ product.skype }}
                </li>
            {% endif %}

            <li class="print-btn">
                <noindex>
                    <a target="_blank"
                       href="/product/{{ product.product_new_id }}-{{ product.product_name|translit }}?print"><i
                                class="a-icon-print a-icon-gray"></i> На печать</a>
                </noindex>
            </li>
        </ul>
    </div>
    <div class="col-2">
        {% if product.stock_content %}
            <p class="stock-content">
                {{ product.stock_content|raw|nl2br }}<br/><br/>
                <b>{{ stock_price|number_format(2, '.', ' ') }} {{ currency }}</b><br/>
                {% if product.price_description %}
                    {{ product.price_description }}<br/>
                {% endif %}
                предложение действительно до {{ product.date_end|rusDate }}
            </p>
        {% endif %}

        {% if product.content %}
            {{ product.content|raw|nl2br }}
        {% else %}
            {{ product.description }}
        {% endif %}
    </div>

    <div class="clear"></div>

    {% if gallery or product.video_link %}

    <div class="idTabs">
        <ul class="idTabs idTabsLeft a-clear">
            {% if gallery %}
                <li>
                    <a href="#ad-gallery-700">Фото</a>
                </li>
            {% endif %}
            {% if product.video_link %}
                <li>
                    <a href="#ad-video">Видео</a>
                </li>
            {% endif %}
        </ul>

        {% endif %}

        {% if gallery %}
            <div id="ad-gallery-700" class="ad-gallery">
                <div class="ad-image-wrapper">
                </div>
                <div class="ad-nav">
                    <div class="ad-thumbs">
                        <ul class="ad-thumb-list">
                            {% for g in gallery %}
                                <li>
                                    <a href="/uploads/images/products/full/{{ g.url_full }}">
                                        <img title="{{ g.description }}" alt="{{ g.description }}"
                                             src="/uploads/images/products/80x100/{{ g.url_full }}"/>
                                    </a>
                                </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        {% endif %}

        {% if product.video_link %}
            <div id="ad-video">
                <iframe width="700" height="394" src="//www.youtube.com/embed/{{ product.video_link }}" frameborder="0"
                        allowfullscreen></iframe>
            </div>
        {% endif %}

        {% if gallery or product.video_link %}
    </div>
    {% endif %}

    {% if vip %}
        <div class="vip-ads-full">
            {% for v in vip %}
                <div class="vip-item clear">
                    <a href="/product/{{ v.product_new_id }}-{{ v.product_name|translit }}">
                        {% if product.image %}
                            <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                 src="/uploads/images/products/80x100/{{ v.image }}"/>
                        {% else %}
                            <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                 src="/uploads/images/80x100.jpg"/>
                        {% endif %}
                        <b>{{ v.product_name }}</b>

                        <div class="a-font-small a-color-gray">{{ v.description }}</div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price {% if v.stock_flag %}price-stock{% endif %}">
                                    {% if v.stock_flag %}
                                        {{ v.stock_price|getExchangeRates(v.stock_currency_id, v.user_id)|number_format(2, '.', ' ') }} {{ currency }}
                                    {% else %}
                                        {{ v.price|getExchangeRates(v.currency_id, v.user_id)|number_format(2, '.', ' ') }} {{ currency }}
                                    {% endif %}
                                </div>
                            </div>
                        </div>

                    </a>
                </div>
            {% endfor %}
        </div>
    {% endif %}

    {% if user_info %}
        <div class="a-clear">
            <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                  action="/index.ajax.php?route=/product/send-message-{{ product.product_new_id }}">
                <textarea class="autosize" placeholder="Написать автору объявления..." name="message"></textarea>

                <div class="a-row a-row-full">
                    <label>Ваш e-mail</label>
                    <input type="text" name="user_email" value="{{ user_info.info.email }}"/>
                </div>
                <div class="a-row a-row-full">
                    <label>Ваши телефоны</label>
                    <input type="text" name="user_phones" value="{{ user_info.info.contact_phones }}"
                           class="phones-input"/>
                </div>
                <input type="hidden" name="user_id" value="{{ product.user_id }}"/>

                <div class="form-loader display-none">
                    <i class="load"></i>
                    Загрузка...
                </div>
                <div class="a-float-left">
                    <input style="display:none" type="file" name="attach" id="attach-input"/>
                    <a id="add-atach" href="#"><i class="a-icon-plus a-icon-gray"></i> Добавить вложение</a>
                </div>
                <div class="a-float-right">
                    <input class="a-btn-green" type="submit" value="Отправить"/>
                </div>
            </form>
        </div>
    {% else %}

        <div class="a-mess-yellow">
            <i class="a-icon-envelope a-icon-gray"></i>
            Написать автору могут только зарегистрированные пользователи. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a title="Вход" href="/login"> <i class="a-icon-check a-icon-gray"></i> Вход</a> &nbsp;&nbsp;&nbsp;&nbsp;
            <a title="Регистрация" href="/registration"><i class="a-icon-plus-sign a-icon-gray"></i> Регистрация</a>
        </div>
    {% endif %}
    </div>

    {% if banner_footer_content.link or banner_footer_content.code %}
        <noindex>
            {% if banner_footer_content.code %}
                <div style="margin:30px 0 0 -15px; text-align:center">
                    {{ banner_footer_content.code|raw }}
                </div>
            {% else %}
                <a id="footer-content-banner" href="{{ banner_footer_content.link }}"
                   target="{{ banner_footer_content.target }}">
                    <img src="/uploads/banners/{{ banner_footer_content.image }}"/>
                </a>
            {% endif %}

        </noindex>
    {% endif %}


{% endblock %}