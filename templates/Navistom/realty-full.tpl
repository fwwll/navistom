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
    {% else %}

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе НЕДВИЖИМОСТЬ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit">Искать</button>
        </form>

        <div class="item">

        {% if realty.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/realty/edit-{{ realty.realty_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if realty.flag == 1 %}
                        <a href="/realty/flag-{{ realty.realty_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                            Скрыть</a>
                    {% else %}
                        <a href="/realty/flag-{{ realty.realty_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                            Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/realty/delete-{{ realty.realty_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if realty.flag_moder > 0 %}
                                        <a href="/realty/flag_moder-{{ realty.realty_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/realty/flag_moder-{{ realty.realty_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/realty/send-message-{{ realty.realty_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-realty-realty_id-{{ realty.realty_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-8-{{ realty.realty_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if realty.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif realty.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if realty.user_id == user_info.info.user_id and "now"|datediff(realty.date_add) > 13 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-realty-realty_id-{{ realty.realty_id }}"><i
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
            {{ realty.date_add|timeago }}&nbsp; | &nbsp;
            {{ realty.views }} <i class="a-icon-eye-open a-icon-gray"></i> &nbsp;|&nbsp; <span class="raceta2 cor_rac"
                                                                                               onclick='raceta()'
                                                                                               title='рекламировать'></span>
        </div>
        <!--div class="a-cols-2 a-font-small a-color-gray a-align-right">
    	<a href="/">NaviStom Украина</a> -
    	<a href="/realty/city-{{ realty.city_id }}-{{ realty.city_name|translit }}">Недвижимость {{ realty.city_name }}</a> -
        <a title="{{ realty.categ_name }}" href="/realty/categ-{{ realty.categ_id }}-{{ realty.categ_name|translit }}">{{ realty.categ_name }}</a>
    </div-->
    </div>

    <h1 class="full-title">
        {% if realty.urgently %}
            <span class="srochno big">Cрочно!</span>
        {% endif %}
        {{ realty.name|raw }}, г. {{ realty.city_name }}
    </h1>

    <div class="n-ad-full n-realty-full a-clear">

    <div class="col-1">
        {% if realty.url_full %}
            <img title="{{ realty.name }}" src="/uploads/images/realty/160x200/{{ realty.url_full }}"/>
        {% else %}
            <img src="/uploads/images/160x200.jpg"/>
        {% endif %}

        <ul id="ad-info-list">
            {% if realty.price > 0 %}
                <li>
            	<span class="price-full">
                	{{ price|number_format(2, '.', ' ') }} {{ currency }}
                </span>

                    <div style="margin-top:5px">
                        {% for p in prices %}
                            <div>{{ p.val|number_format(2, '.', ' ') }} {{ p.name }}</div>
                        {% endfor %}
                    </div>

                    {% if realty.price_description %}
                        <div class="a-font-small a-color-gray">{{ realty.price_description }}</div>
                    {% endif %}
                </li>
            {% endif %}

            <li>
                <a href="/realty/user-{{ realty.user_id }}-{{ realty.user_name|translit }}">
                    <i class="a-icon-user a-icon-gray"></i>&nbsp; {{ realty.user_name|raw }}
                </a>
            </li>
            {% for key, value in realty.phones %}
                {% if value != '' %}
                    <li>
                        <i class="a-icon-phone a-icon-gray"></i>&nbsp; {{ value }}
                    </li>
                {% endif %}
            {% endfor %}
            <li>
                <i class="a-icon-globe a-icon-gray"></i>
                г. {{ realty.city_name }}{% if realty.address %}, {{ realty.address|raw }}{% endif %}
            </li>
            <li class="print-btn">
                <noindex>
                    <a target="_blank" href="/realty/{{ realty.realty_id }}-{{ realty.name|translit }}?print"><i
                                class="a-icon-print a-icon-gray"></i> На печать</a>
                </noindex>
            </li>
            <li>
                <script type="text/javascript">(function () {
                        if (window.pluso)if (typeof window.pluso.start == "function") return;
                        if (window.ifpluso == undefined) {
                            window.ifpluso = 1;
                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                            s.type = 'text/javascript';
                            s.charset = 'UTF-8';
                            s.async = true;
                            s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                            var h = d[g]('body')[0];
                            h.appendChild(s);
                        }
                    })();</script>
                <div class="pluso" data-background="transparent"
                     data-options="big,round,line,horizontal,nocounter,theme=04"
                     data-services="facebook,google,vkontakte,odnoklassniki,twitter"></div>
            </li>

        </ul>
    </div>
    <div class="col-2">
        {{ realty.content|raw|nl2br }}
    </div>

    <div class="a-clear"></div>

    <div id="idTabs">
        <ul class="ad-tabs">
            {% if gallery %}
                <li>
                    <a href="#ad-gallery-700">Фото</a>
                </li>
            {% endif %}

            {% if realty.video_link %}
                <li>
                    <a href="#ad-video">Видео</a>
                </li>
            {% endif %}

            {% if realty.address %}
                <li>
                    <a href="#n-ya-map">Карта</a>
                </li>
            {% endif %}
        </ul>

        {% if realty.address %}
            <div style="width:700px; height:400px;" id="n-ya-map"></div>
            <script type="text/javascript">
                var R = '{{realty.city_name}}, {{realty.address}}';
                window.my_map = function (ymaps) {
                    var myGeocoder = ymaps.geocode(R);
                    myGeocoder.then(function (res) {
                        var map = new ymaps.Map("n-ya-map", {
                            center: res.geoObjects.get(0).geometry.getCoordinates(),
                            zoom: 17,
                            type: "yandex#map"
                        });
                        map.controls
                                .add("zoomControl")
                                .add("mapTools")
                                .add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
                        map.geoObjects.add(res.geoObjects);
                    });
                };
            </script>

            <script type="text/javascript"
                    src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=my_map"></script>
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
                                    <a href="/uploads/images/realty/full/{{ g.url_full }}">
                                        <img alt="{{ g.description }}" title="{{ g.description }}"
                                             src="/uploads/images/realty/80x100/{{ g.url_full }}"/>
                                    </a>
                                </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        {% endif %}

        {% if realty.video_link %}
            <div id="ad-video">
                <iframe width="700" height="394" src="//www.youtube.com/embed/{{ realty.video_link }}" frameborder="0"
                        allowfullscreen></iframe>
            </div>
        {% endif %}

    </div>

    {% if vip %}
        <div class="vip-ads-full">
            {% for v in vip %}
                <div class="vip-item clear">
                    <a href="/realty/{{ v.realty_id }}-{{ v.name|translit }}">
                        {% if v.image %}
                            <img title="{{ v.name }}" alt="{{ v.name }}"
                                 src="/uploads/images/realty/80x100/{{ v.image }}"/>
                        {% else %}
                            <img title="{{ v.name }}" alt="{{ v.name }}" src="/uploads/images/100x80.jpg"/>
                        {% endif %}
                        <b>{{ v.name }}, г. {{ v.city_name }}</b>

                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                            </div>
                            <div class="a-cols-2 a-align-right">
                                {% if v.price != '0.00' %}
                                    <div class="price">
                                        {{ v.price|getExchangeRates(v.currency_id, v.user_id)|number_format(2, '.', ' ') }} {{ currency }}
                                    </div>
                                {% endif %}
                            </div>
                        </div>
                    </a>
                </div>
            {% endfor %}
        </div>
    {% endif %}

    {% if user_info %}
        <div class="a-clear">
            <form id="send-user-mess" method="post" class="a-clear"
                  action="/index.ajax.php?route=/realty/send-message-{{ realty.realty_id }}">
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
                <input type="hidden" name="user_id" value="{{ realty.user_id }}"/>

                <div class="form-loader display-none">
                    <i class="load"></i>
                    Загрузка...
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