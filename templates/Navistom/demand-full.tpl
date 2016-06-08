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

        {% if demand.flag_delete > 0 %}
            <script>
                window.location.href = "/demand#404";
            </script>
        {% endif %}

    {% else %}

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе СПРОС" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit">Искать</button>
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
                {% if demand.user_id == user_info.info.user_id and "now"|datediff(demand.date_add) > 13 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-demand-demand_id-{{ demand.demand_id }}"><i
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
            {{ demand.date_add|timeago }} &nbsp; | &nbsp;
            {{ demand.views }} <i class="a-icon-eye-open a-icon-gray"></i>
            &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
        </div>
        <div class="a-cols-2 a-font-small a-color-gray a-align-right">
            &nbsp;
        </div>
    </div>

    <h1 class="full-title">
        {% if demand.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
        {{ demand.name|raw }}
    </h1>

    <div class="n-ad-full n-realty-full a-clear">
    <div class="col-1">
        {% if demand.url_full %}
            <img title="{{ demand.name }}" src="/uploads/images/demand/160x200/{{ demand.url_full }}"/>
        {% else %}
            <img src="/uploads/images/160x200.jpg"/>
        {% endif %}
        <ul id="ad-info-list">
            <li>
                <a href="/demand/user-{{ demand.user_id }}-{{ demand.user_name|translit }}">
                    <i class="a-icon-user a-icon-gray"></i>&nbsp; {{ demand.user_name }}
                </a>
            </li>
            {% for key, value in demand.phones %}
                {% if value != '' %}
                    <li>
                        <i class="a-icon-phone a-icon-gray"></i>&nbsp; {{ value }}
                    </li>
                {% endif %}
            {% endfor %}
            <li class="print-btn">
                <noindex>
                    <a target="_blank" href="/demand/{{ demand.demand_id }}-{{ demand.name|translit }}?print"><i
                                class="a-icon-print a-icon-gray"></i> На печать</a>
                </noindex>
            </li>
        </ul>
    </div>
    <div class="col-2">
        {{ demand.content|raw|nl2br }}
    </div>

    <div class="a-clear"></div>

    {% if gallery or demand.video_link or demand.address %}

    <div id="idTabs">
        <ul class="idTabs idTabsLeft a-clear">
            {% if gallery %}
                <li>
                    <a href="#ad-gallery-700">Фото</a>
                </li>
            {% endif %}
            {% if demand.video_link %}
                <li>
                    <a href="#ad-video">Видео</a>
                </li>
            {% endif %}
            {% if demand.address %}
                <li>
                    <a href="#n-ya-map">Карта</a>
                </li>
            {% endif %}
        </ul>

        {% endif %}

        {% if realty.demand %}
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
                                    <a href="/uploads/images/demand/full/{{ g.url_full }}">
                                        <img alt="{{ g.description }}" title="{{ g.description }}"
                                             src="/uploads/images/demand/80x100/{{ g.url_full }}"/>
                                    </a>
                                </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        {% endif %}

        {% if demand.video_link %}
            <div id="ad-video">
                <iframe width="700" height="394" src="//www.youtube.com/embed/{{ demand.video_link }}" frameborder="0"
                        allowfullscreen></iframe>
            </div>
        {% endif %}

        {% if gallery or demand.video_link or demand.address %}
    </div>
    {% endif %}

    <p><br/></p>

    {% if user_info %}
        <div class="a-clear">
            <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                  action="/index.ajax.php?route=/demand/send-message-{{ demand.demand_id }}">
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
            <i class="a-icon-envelope a-icon-gray"></i>
            Написать автору могут только зарегистрированные пользователи. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="/login"> <i class="a-icon-check a-icon-gray"></i> Вход</a> &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="/registration"><i class="a-icon-plus-sign a-icon-gray"></i> Регистрация</a>
        </div>

    {% endif %}

    {#% if vip %#}

    <!--div class="vip-ads-full">
    	<div class="a-row">
    	{% for v in vip %}
            <div class="a-cols-2">
                <a href="/demand/{{ v.demand_id }}-{{ v.name|translit }}">
                {% if v.image %}
                <img title="{{ v.image }}" alt="{{ v.image }}" src="/uploads/images/demand/80x100/{{ v.image }}" />
                {% else %}
                <img title="{{ v.image }}" alt="{{ v.image }}" src="/uploads/images/100x80.jpg" />
                {% endif %}
                <b>{{ v.name }}</b>
                </a>
            </div>
        {% endfor %}
        </div>
    </div-->
    {#% endif %#}


    {% if vip %}

        <div class="vip-ads-full">

            {% for v in vip %}
                <div class="vip-item clear">
                    <a href="/demand/{{ v.demand_id }}-{{ v.name|translit }}">
                        {% if v.image %}
                            <img title="{{ v.name }}" alt="{{ v.name }}"
                                 src="/uploads/images/demand/80x100/{{ v.image }}"/>
                        {% else %}
                            <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                 src="/uploads/images/100x80.jpg"/>
                        {% endif %}
                        <b>{{ v.product_name }}</b>

                        <div class="a-font-small a-color-gray"> {{ v.description }}  </div>
                        <div class="a-row a-offset-0 offer-footer">
                            <div class="a-cols-2">
                                <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price">

                                </div>
                            </div>
                        </div>

                    </a>
                </div>
            {% endfor %}
        </div>
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