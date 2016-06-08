{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

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
        <div style="width:777px">
    {% else %}

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе НЕДВИЖИМОСТЬ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
        </form>

        <div class="item">

        <script>
            //jQuery('link[href*="all.min.css"]').attr('href','/templates/complete/all_new.min.css');


        </script>


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
                {% if(user_info.info.group_id == 10 and realty.user_id == user_info.info.user_id and "now"|datediff(realty.date_add) > 13) or ( realty.user_id == user_info.info.user_id and "now"|datediff(realty.date_add) > 29) %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-realty-realty_id-{{ realty.realty_id }}"><i
                                        class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                            <div>
                            </div>
                        </div>
                    </li>
                {% endif %}
            </ul>
        {% endif %}

    {% endif %}
<div class='full_padding'>
    <div class="a-row a-offset-0">
        <noindex>
            <div class="a-cols-2 a-font-small a-color-gray">
                {{ realty.date_add|timeago }}&nbsp; | &nbsp;
                {{ realty.views }} <i class="a-icon-eye-open a-icon-gray"></i> &nbsp;|&nbsp; <span
                        class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>
        </noindex>
    </div>


    <h1 class="full-title">
        {% if realty.urgently %}
            <span class="srochno big">Cрочно!</span>
        {% endif %}
        {{ realty.name|raw }}  г. {{ realty.city_name }}
    </h1>

    <div class=" n-realty-full a-clear">

        <div class="col-1">
            <div class='img_top'>
                {% if realty.url_full %}
                    <img title="{{ realty.name }}" src="/uploads/images/realty/full/{{ realty.url_full }}"/>
                {% else %}
                    <div class="none_img"><img src="/uploads/images/160x200.jpg"/></div>
                {% endif %}
            </div>
            <ul id="ad-info-list">
                {% if realty.price > 0 %}
                    <li>
            	<span class="price-full">
                	{{ price|number_format(2, '.', ' ') }} {{ currency }}
                </span>

                        <div style="cenik">
                            {% for p in prices %}
                                <span class='evro'>{{ p.val|number_format(2, '.', ' ') }} {{ p.name }}</span>
                            {% endfor %}
                            {% if realty.price_description %}
                                <div style='clear:both'>{{ realty.price_description }}</div>
                            {% endif %}
                        </div>

                        {% if realty.price_description %}
                            <!--div class="a-font-small a-color-gray">{{ realty.price_description }}</div-->
                        {% endif %}
                    </li>
                {% endif %}

                <li>
                    <a href="/realty/user-{{ realty.user_id }}-{{ realty.user_name|translit }}" class='color727272'>
                        <i class="user-new-iconz"></i>&nbsp; {{ realty.user_name|raw }}
                    </a>
                </li>
                {% for key, value in realty.phones %}
                    {% if value != '' %}
                        <li>
                            <i class="tel-new-iconz "></i>&nbsp; <span class='color727272'>{{ value }}</span>
                        </li>
                    {% endif %}
                {% endfor %}
                <li>
                    <i class="globe-new-iconz"></i>
                    <span class='color727272'> г. {{ realty.city_name }}{% if realty.address %}, {{ realty.address|raw }}{% endif %}</span>
                </li>

            </ul>
        </div>
        <div class="col-2">
            {{ realty.content|raw|nl2br }}
        </div>

        <div class="a-clear"></div>

        <div id="idTabs">

            {% if gallery %}

            {% endif %}

            {% if realty.video_link %}

            {% endif %}

            {% if realty.address %}

            {% endif %}




            {% if gallery %}
                <div id="" class="ad-gallery">

                    <div class="ad-nav">
                        <div class="ad-thumbs">

                            {% for g in gallery %}

                                <div class='foto'>
                                    <img alt="{{ g.description }}" title="{{ g.description }}"
                                         src="/uploads/images/realty/full/{{ g.url_full }}"/>
                                </div>

                            {% endfor %}

                        </div>
                    </div>
                </div>
                <p>&nbsp;</p>
            {% endif %}
            {% if realty.address %}
                <div style="width:100%; height:400px;" id="n-ya-map"></div>
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

            {% if realty.video_link %}
                <div id="ad-video">
                    <iframe width="700" height="394" src="//www.youtube.com/embed/{{ realty.video_link }}"
                            frameborder="0" allowfullscreen></iframe>
                </div>
            {% endif %}

        </div>
        <div>

            <div class="print-mess">


                <noindex>
                    <form method='post' name='p'>
                        <input type='hidden' name='print' value='1'>
                    </form>
                    <div style="float:left" target="_blank" class='color727272 print'><i class="print-new-iconz"></i> На
                        печать
                    </div>
                    <div style="float:left">
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
                    </div>
                </noindex>


            </div>
            <div class="pluso" style="width: 290px" data-background="transparent"
                 data-options="big,round,line,horizontal,nocounter,theme=04"
                 data-services="facebook,google,vkontakte,odnoklassniki,twitter" data-user="321746015"></div>
            <div class="bugr-mess">


                <noindex>
                    <div style="float:left" target="_blank" class='color727272 print'><a title="Пожаловаться "
                                                                                         id="send-error-mess-link"
                                                                                         href="#" class='color727272'>
                            <i class="alert-new-iconz"></i> Пожаловаться
                        </a></div>
                </noindex>

            </div>


            {% if user_info %}
                <div class="a-clear">
                    <form id="send-user-mess" method="post" class="a-clear"
                          action="/index.ajax.php?route=/realty/send-message-{{ realty.realty_id }}">
                        <div class='zayvka'>Написать автору объявления</div>
                        <textarea autofocus='autofocus' class="autosize" placeholder="Написать автору объявления..."
                                  name="message">

                        </textarea>

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
                    <center>
                        <i class="a-icon-envelope a-icon-gray"></i>
                        Чтобы написать автору, нужно <a title="Вход" href="/login"> войти</a> или <a title="Регистрация"
                                                                                                     href="/registration">зарегистрироваться</a><br/>
                    </center>
                </div>

            {% endif %}

        </div>
    </div>
    </noindex>
    {% if vip %}
        <div class="vip-ads-full">
            {% for v in vip %}
                <div class="item pagination-block  light {% if v.color_yellow >1 %} color_yellow {% endif %}">
                    {% if v.show_top > 0 %}
                        <span class="l_top kon"> <span>топ</span></span>
                    {% endif %}
                    <!--div class='a-row a-offset-0'-->
                    <!--div class='a-cols-2 a-font-small a-color-gray-2'>

                        <a href='/realty'>Недвижимость</a>
                    </div-->

                    <!--/div-->
                    <div class='offer clear concurent vp_kon'>
                        <div style="" class="a-align-right a-float-right">
                            <span class='data_vip'>{{ v.date_add|timeago }}</span>
                        </div>
                        <a href="/realty/{{ v.realty_id }}-{{ v.name|translit }}">
                            {% if v.image %}
                                <img title="{{ v.name }}" alt="{{ v.name }}"
                                     src="/uploads/images/realty/full/{{ v.image }}"/>
                            {% else %}
                                <img title="{{ v.name }}" alt="{{ v.name }}" src="/uploads/images/100x80.jpg"/>
                            {% endif %}
                        </a>

                        <div class='filter_l'>
                            {{ v.categ_name }}
                        </div>
                        {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic vp_kon '>
                            <a target="_blank" href="/realty/{{ v.realty_id }}-{{ v.name|translit }}"
                               class="modal-window-link"></a>
                        </div>

                        <div class="name_ta"><a href="/realty/{{ v.realty_id }}-{{ v.name|translit }}">{{ v.name }},
                                г. {{ v.city_name }}</a></div>

                        <div class="a-row a-offset-0 offer-footer vp_kon">
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
                </div>
            {% endfor %}
        </div>
    {% endif %}
    </noindex>
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