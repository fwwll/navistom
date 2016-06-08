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

        {% if demand.flag_delete > 0 %}
            <script>
                window.location.href = "/demand#404";
            </script>
        {% endif %}

    {% else %}

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе СПРОС" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
        </form>

        <div class="item">

        {% if demand.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/demand/edit-{{ demand.demand_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if demand.flag == 1 %}
                        <a href="/demand/flag-{{ demand.demand_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                            Скрыть</a>
                    {% else %}
                        <a href="/demand/flag-{{ demand.demand_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                            Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/demand/delete-{{ demand.demand_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if demand.flag_moder > 0 %}
                                        <a href="/demand/flag_moder-{{ demand.demand_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/demand/flag_moder-{{ demand.demand_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/demand/send-message-{{ demand.demand_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-11-{{ demand.demand_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if demand.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif demand.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if (user_info.info.group_id == 10 and demand.user_id == user_info.info.user_id and "now"|datediff(demand.date_add) > 13 ) or ( demand.user_id == user_info.info.user_id and "now"|datediff(demand.date_add) > 29 ) %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-demand-demand_id-{{ demand.demand_id }}"><i
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
                {{ demand.date_add|timeago }} &nbsp; | &nbsp;
                {{ demand.views }} <i class="a-icon-eye-open a-icon-gray"></i>
                &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>
            <div class="a-cols-2 a-font-small a-color-gray a-align-right">
                &nbsp;
            </div>
        </noindex>
    </div>

    <h1 class="full-title">
        {% if demand.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
        {{ demand.name|raw }}
    </h1>

    <div class="n-ad-full n-realty-full a-clear">
        <div class="col-1">
            <div class='img_top'>
                {% if demand.url_full %}
                    <img title="{{ demand.name }}" src="/uploads/images/demand/full/{{ demand.url_full }}"/>
                {% else %}
                    <div class='none_img'><img src="/uploads/images/160x200.jpg"/></div>
                {% endif %}
            </div>
            <ul id="ad-info-list">
                <li>
                    <a href="/demand/user-{{ demand.user_id }}-{{ demand.user_name|translit }}" class='color727272'>
                        <i class="user-new-iconz "></i>&nbsp; {{ demand.user_name }}
                    </a>
                </li>
                {% for key, value in demand.phones %}
                    {% if value != '' %}
                        <li>
                            <i class="tel-new-iconz"></i>&nbsp; <span class='color727272'>{{ value }}</span>
                        </li>
                    {% endif %}
                {% endfor %}

            </ul>
        </div>
        <div class="col-2">
            {{ demand.content|raw|nl2br }}
        </div>

        <div class="a-clear"></div>

        {% if gallery or demand.video_link or demand.address %}

        <div id="idTabs">

            {% if gallery %}

            {% endif %}
            {% if demand.video_link %}

            {% endif %}
            {% if demand.address %}

            {% endif %}


            {% endif %}

            {% if realty.demand %}
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

            {% if gallery %}
                <div id="" class="ad-gallery">
                    <div class="ad-nav">
                        <div class="ad-thumbs">
                            {% for g in gallery %}
                                <div class='foto'>
                                    <a href="/uploads/images/demand/full/{{ g.url_full }}">
                                        <!--img alt="{{ g.description }}" title="{{ g.description }}" src="/uploads/images/demand/80x100/{{ g.url_full }}" /-->
                                        <img alt="{{ g.description }}" title="{{ g.description }}"
                                             src="/uploads/images/demand/full/{{ g.url_full }}"/>
                                    </a>
                                </div>
                            {% endfor %}

                        </div>
                    </div>
                </div>
                <p>&nbsp;</p>
            {% endif %}

            {% if demand.video_link %}
                <div id="video">
                    <iframe width="100%" height="394" src="//www.youtube.com/embed/{{ demand.video_link }}"
                            frameborder="0" allowfullscreen></iframe>
                </div>
            {% endif %}

            {% if gallery or demand.video_link or demand.address %}
        </div>
        {% endif %}

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
                    <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                          action="/index.ajax.php?route=/demand/send-message-{{ demand.demand_id }}">
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
                        <input type="hidden" name="user_id" value="{{ demand.user_id }}"/>

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

    </div>

    {% if vip %}
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

                            <a href="/demand/{{ v.demand_id }}-{{ v.name|translit }}">
                                {% if v.image %}
                                    <img title="{{ v.name }}" alt="{{ v.name }}"
                                         src="/uploads/images/demand/full/{{ v.image }}"/>
                                {% else %}
                                    <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                         src="/uploads/images/100x80.jpg"/>
                                {% endif %}
                            </a>

                            <div style="width:100%; height:20px;"></div>

                            {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                            <div class='ic vp_kon '>
                                <a title="{{ v.product_name }}" target="_blank"
                                   href="/demand/{{ v.demand_id }}-{{ v.name|translit }}" class="modal-window-link"></a>
                            </div>
                            <div class="name_ta vp_kon">
                                <a title="{{ v.product_name }}"
                                   href="/demand/{{ v.demand_id }}-{{ v.name|translit }}">{{ v.description }} </a>
                            </div>

                            <div class="a-row a-offset-0 offer-footer vp_kon">
                                <div class="a-cols-2">
                                    <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                                </div>
                                <div class="a-cols-2 a-align-right">
                                    <div class="price">

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                {% endfor %}
            </div>
        </noindex>
    {% endif %}






    <!--<div class="a-modal-footer">
        <div class="addthis_toolbox addthis_default_style ">
            <a class="addthis_button_vk"></a>
            <a class="addthis_button_facebook"></a>
            <a class="addthis_button_odnoklassniki_ru"></a>
            <a class="addthis_button_twitter"></a>
            <a class="addthis_button_google_plusone_share"></a>
            <a class="addthis_button_compact"></a>
        </div>
        <script type="text/javascript">
            var addthis_config = {
                "data_track_addressbar":false,
                "pubid" : "ra-4f7d770f68a3c8a2"
            };

            var addthis_share = {
               "url" : "http://navistom.com/demand/{{ demand.demand_id }}-{{ demand.name|translit }}",
			   "title": "{{ demand.name }}"
            }
            
        </script>
        <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f7d770f68a3c8a2"></script>
    </div>-->
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