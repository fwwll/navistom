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
            <input placeholder="Поиск в разделе  Сервис/Запчасти " type="text" value="{{ route.values.search }}"
                   name="q" id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
        </form>

        <div class="item">

        {% if service.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/service/edit-{{ service.service_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if service.flag == 1 %}
                        <a href="/service/flag-{{ service.service_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                            Скрыть</a>
                    {% else %}
                        <a href="/service/flag-{{ service.service_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                            Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/service/delete-{{ service.service_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if service.flag_moder > 0 %}
                                        <a href="/service/flag_moder-{{ service.service_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/service/flag_moder-{{ service.service_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/service/send-message-{{ service.service_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-services-service_id-{{ service.service_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-9-{{ service.service_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if service.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif service.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if  (user_info.info.group_id == 10 and service.user_id == user_info.info.user_id and "now"|datediff(service.date_add) > 13 ) or ( service.user_id == user_info.info.user_id and "now"|datediff(service.date_add) > 29) %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-services-service_id-{{ service.service_id }}"><i
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
    <noindex>
        <div class="a-row a-offset-0">
            <div class="a-cols-2 a-font-small a-color-gray">
                {{ service.date_add|timeago }}&nbsp; | &nbsp;
                {{ service.views }} <i class="a-icon-eye-open a-icon-gray"></i>
                &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>

        </div>
    </noindex>
    <h1 class="full-title">
        {% if service.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
        {{ service.name|raw }}
    </h1>

    <div class="n-ad-full n-lab-full a-clear">
        <div class="col-1">
            <div class='img_top'>
                {% if service.url_full %}
                    <img title="{{ service.name }}" src="/uploads/images/services/full/{{ service.url_full }}"/>
                {% else %}
                    <img src="/uploads/images/160x200.jpg"/>
                {% endif %}
            </div>
            <ul id="ad-info-list">

                {% if price >0 %}
                    <li>
                <span class="price-full">
                	{{ price|number_format(2, '.', ' ') }} {{ currency }}
                </span>

                        <div style="cenik">
                            {% for p in prices %}
                                <div class='evro'>{{ p.val|number_format(2, '.', ' ') }} {{ p.name }}</div>
                            {% endfor %}
                        </div>
                    </li>
                {% endif %}

                <li>

                    <a href="/services/user-{{ service.user_id }}-{{ service.user_name|translit }}" class='color727272'>
                        <i class="user-new-iconz "></i>&nbsp; {{ service.user_name|raw }}
                    </a>


                </li>



                {% for key, value in service.phones %}
                    {% if value != '' %}
                        <li>

                            <i class="tel-new-iconz "></i>&nbsp; <span class='color727272'>{{ value }}</span>
                        </li>
                    {% endif %}
                {% endfor %}

                <li>
                    <i class="globe-new-iconz"></i>
				  <span class='color727272'>  {{ service.region_name }}
                      , г. {{ service.city_name }}{% if service.address %}, {{ service.address|raw }}{% endif %}
				  </span>
                </li>

                {% if service.site %}
                    <li>
                        <noindex>
                            <i class="link-new-iconz"></i>&nbsp;
                            <a target="_blank" href="{{ service.site }}"><span class='color727272'>Веб сайт</span></a>
                        </noindex>
                    </li>
                {% endif %}


                {% if service.attach %}
                    <li>
                        <i class="a-icon-file a-icon-gray"></i>
                        <a title="Прайс-лист" target="_blank"
                           href="http://navistom.com/uploads/docs/{{ service.attach }}">Прайс-лист</a>
                    </li>
                {% endif %}

                {% if service.icq %}
                    <li>
                        <span class="a-color-gray">ICQ:</span> {{ service.icq }}
                    </li>
                {% endif %}
                {% if service.skype %}
                    <li>
                        <i class="a-icon-skype a-icon-gray"></i> {{ service.skype }}
                    </li>
                {% endif %}

            </ul>
        </div>
        <div class="col-2">
            {{ service.content|raw|nl2br }}
        </div>

        <div class="a-clear"></div>

        <div id="idTabs">

            {% if gallery %}

            {% endif %}

            {% if service.video_link %}

            {% endif %}

            {% if service.address %}

            {% endif %}


            {% if service.address %}
                <div style="width:100%; height:400px;" id="n-ya-map"></div>
                <script type="text/javascript">
                    var R = '{{service.city_name}}, {{service.address}}';
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
                <div id="" class="ad-gallery">

                    <div class="ad-nav">
                        <div class="ad-thumbs">
                            {% for g in gallery %}
                                <div class='foto'>

                                    <img title="{{ g.description }}" alt="{{ g.description }}"
                                         src="/uploads/images/services/full/{{ g.url_full }}"/>

                                </div>
                            {% endfor %}

                        </div>
                    </div>
                </div>
                <p>&nbsp;</p>
            {% endif %}

            {% if service.video_link %}
                <div id="video">
                    <iframe width="100%" height="394" src="//www.youtube.com/embed/{{ service.video_link }}"
                            frameborder="0" allowfullscreen></iframe>
                </div>
            {% endif %}

        </div>
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
                                                                                     id="send-error-mess-link" href="#"
                                                                                     class='color727272'>
                        <i class="alert-new-iconz"></i> Пожаловаться
                    </a></div>
            </noindex>

        </div>

        {% if user_info %}
            <div class="a-clear">
                <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                      action="/index.ajax.php?route=/service/send-message-{{ service.service_id }}">
                    <div class='zayvka'>Написать автору объявления</div>
                    <textarea autofocus="autofocus" class="autosize" placeholder="Написать автору объявления..."
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
                    <input type="hidden" name="user_id" value="{{ service.user_id }}"/>

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
                <center>
                    <i class="a-icon-envelope a-icon-gray"></i>
                    Чтобы написать автору, нужно <a title="Вход" href="/login"> войти</a> или <a title="Регистрация"
                                                                                                 href="/registration">зарегистрироваться</a><br/>

                </center>
            </div>

        {% endif %}
    </div>

    {% if vip and vip[0].date_add %}
        <noindex>
            <div class="vip-ads-full">
                {% for v in vip %}
                    <div class="item pagination-block  light {% if v.color_yellow >1 %} color_yellow {% endif %}">
                        {% if v.show_top > 0 %}
                            <span class="l_top"> <span>топ</span></span>
                        {% endif %}

                        <div class='offer clear concurent vp_kon'>

                            <div style="" class=" a-align-right a-float-right">
                                <span style="">{{ v.date_add|timeago }}</span>
                            </div>
                            <a href="/service/{{ v.service_id }}-{{ v.name|translit }}">
                                {% if v.image %}
                                    <img title="{{ v.image }}" alt="{{ v.image }}"
                                         src="/uploads/images/services/full/{{ v.image }}"/>
                                {% else %}
                                    <img title="{{ v.image }}" alt="{{ v.image }}" src="/uploads/images/100x80.jpg"/>
                                {% endif %}
                            </a>

                            <div class='filter_l'>
                                {% for key, value in v.categs %}
                                    {{ value }}
                                {% endfor %}
                            </div>

                            {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}

                            <div class='ic vp_kon '>
                                <a title="{{ v.product_name }}, Б/У" target="_blank"
                                   href="/service/{{ v.service_id }}-{{ v.name|translit }}"
                                   class="modal-window-link"></a>
                            </div>
                            <div class="name_ta vp_kon">
                                <a href="/service/{{ v.service_id }}-{{ v.name|translit }}"> {{ v.name }}</a>
                            </div>
                            <div class="name_ta">
                            </div>

                            <div class="a-row a-offset-0 offer-footer  vp_kon">
                                <div class="a-cols-2">
                                    <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                                </div>
                                <div class="a-cols-2 a-align-right">


                                    {% if v.price > 0 %}
                                        <div class="price"> {{ v.price }} {{ default_currency }} </div>
                                    {% else %}
                                        <div class="price"></div>
                                    {% endif %}


                                </div>
                            </div>

                        </div>
                    </div>
                {% endfor %}
            </div>
        </noindex>
    {% endif %}





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