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

        {% if activity.flag_delete > 0 %}
            <script>
                {% for key, value in activity.categs %}
                window.location.href = "/activity/categ-{{key}}-{{value|translit}}#404";
                {% endfor %}
            </script>
        {% endif %}

    {% else %}

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе МЕРОПРИЯТИЯ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit">Искать</button>
        </form>

        <div class="item">

        {% if activity.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/activity/edit-{{ activity.activity_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if activity.flag == 1 %}
                        <a href="/activity/flag-{{ activity.activity_id }}-0"><i
                                    class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                    {% else %}
                        <a href="/activity/flag-{{ activity.activity_id }}-1"><i
                                    class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/activity/delete-{{ activity.activity_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if activity.flag_moder > 0 %}
                                        <a href="/activity/flag_moder-{{ activity.activity_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/activity/flag_moder-{{ activity.activity_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/activity/send-message-{{ activity.activity_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-activity-activity_id-{{ activity.activity_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-5-{{ activity.activity_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if activity.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif activity.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if activity.user_id == user_info.info.user_id and "now"|datediff(activity.date_add) > 13 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-activity-activity_id-{{ activity.activity_id }}"><i
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

            {{ activity.date_add|timeago }} &nbsp; | &nbsp; {{ activity.views }} <i
                    class="a-icon-eye-open a-icon-gray"></i>
            &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
        </div>
        <!--div class="a-cols-2 a-font-small a-color-gray a-align-right">
    	<a href="/">NaviStom Украина</a> -
    	<a href="/activity/city-{{ activity.city_id }}-{{ activity.city_name|translit }}">Мероприятия {{ activity.city_name }}</a> -
        {% for key, value in activity.categs %}
        	<a title="{{ value }}" href="/activity/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>&nbsp;&nbsp;
        {% endfor %}
    </div-->
    </div>

    <h1 class="full-title">
        {% if activity.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
        {% if activity.flag_agreed > 0 or activity.date_start == '0000-00-00' %}
            Дата по согласованию
        {% else %}
            {{ activity.date_start|rusDate }}

            {% if activity.date_end != '0000-00-00' and activity.date_end != activity.date_start %}
                - {{ activity.date_end|rusDate }}
            {% endif %}
        {% endif %}
        , {{ activity.city_name }}
        <br/>
        {{ activity.name|raw }}
    </h1>

<div class="n-ad-full a-clear">

    <div class="col-1">
        {% if activity.img_l %}
            <img title="{{ activity.name }}" src="/uploads/images/activity/160x200/{{ activity.img_l }}"/>
        {% elseif activity.image %}
            <img title="{{ activity.name }}" src="/uploads/images/activity/160x200/{{ activity.image }}"/>
        {% else %}
            <img src="/uploads/images/160x200.jpg"/>
        {% endif %}
        <ul id="ad-info-list">
            <li>
                <i class="a-icon-calendar a-icon-gray"></i>
                {% if activity.flag_agreed > 0 or activity.date_start == '0000-00-00' %}
                    по согласованию
                {% else %}
                    {{ activity.date_start|rusDate }}

                    {% if activity.date_end != '0000-00-00' and activity.date_end != activity.date_start %}
                        - {{ activity.date_end|rusDate }}
                    {% endif %}
                {% endif %}
            </li>
            <li>
                <a href="/activity/user-{{ activity.user_id }}-{{ activity.user_name|translit }}"><i
                            class="a-icon-user a-icon-gray"></i>&nbsp;  {{ activity.user_name|raw }}</a>
            </li>
            {% for key, value in activity.phones %}
                {% if value != '' %}
                    <li>
                        <i class="a-icon-phone a-icon-gray"></i>&nbsp; {{ value }}
                    </li>
                {% endif %}
            {% endfor %}
            <li>
                <i class="a-icon-globe a-icon-gray"></i> г. {{ activity.city_name }}
            </li>
            {% if activity.address %}
                <li>
                    <i class="a-icon-home a-icon-gray"></i> {{ activity.address|raw }}
                </li>
            {% endif %}
            {% if activity.attachment %}
                <li>
                    <i class="a-icon-file a-icon-gray"></i>
                    <a title="Полная программа" target="_blank"
                       href="http://navistom.com/uploads/docs/{{ activity.attachment }}">Полная программа</a>
                </li>
            {% endif %}
            {% if activity.link %}
                <li>
                    <i class="a-icon-link a-icon-gray"></i>
                    <a title="Ссылка на сайт мероприятия" target="_blank" href="{{ activity.link }}">Ссылка на сайт
                        мероприятия</a>
                </li>
            {% endif %}
            <li class="print-btn">
                <noindex>
                    <a target="_blank" href="/activity/{{ activity.activity_id }}-{{ activity.name|translit }}?print"><i
                                class="a-icon-print a-icon-gray"></i> На печать</a>
                </noindex>
            </li>
        </ul>


    </div>
    <div class="col-2">
        {% for l in lectors %}
            <div class="n-lectors-list">
                <div class="col-1">
                    <img title="{{ l.name }}" alt="{{ l.name }}" src="/uploads/images/activity/lectors/{{ l.image }}"/>
                </div>
                <div class="col-2">
                    <b>{{ l.name }}</b><br/>
                    {{ l.description|raw|nl2br }}
                </div>
            </div>
        {% endfor %}

        {{ activity.content|raw|nl2br }}


    </div>

    <div class='asd'>
        {% if gallery %}
            <div id="ad-gallery-700" class="ad-gallery">
                <div class="ad-image-wrapper">
                </div>
                <div class="ad-nav">
                    <div class="ad-thumbs">
                        <ul class="ad-thumb-list">
                            {% for g in gallery %}
                                <li>
                                    <a href="/uploads/images/activity/full/{{ g.url_full }}">
                                        <img alt="{{ g.description }}" title="{{ g.description }}"
                                             src="/uploads/images/activity/80x100/{{ g.url_full }}"/>
                                    </a>
                                </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        {% endif %}

    </div>




    <div class="a-clear"></div>

    <p><br/></p>

    {% if activity.video_link %}
        <iframe width="700" height="394" src="//www.youtube.com/embed/{{ activity.video_link }}" frameborder="0"
                allowfullscreen></iframe>
    {% endif %}

    {% if vip %}
    <div class="vip-ads-full clear">
        {% for v in vip %}
        <div class="vip-item clear">
            <a href="/activity/{{ v.activity_id }}-{{ v.name|translit }}">
                <div class="lector-image">
                    {% if v.image %}
                        <img title="{{ v.image }}" alt="{{ v.image }}"
                             src="/uploads/images/activity/lectors/{{ v.image }}"/>
                    {% else %}
                        <img title="{{ v.image }}" alt="{{ v.image }}" src="/uploads/images/80x100.jpg"/>
                    {% endif %}
                </div>
                <div style="color:#333" class="a-font-small"><i class="a-icon-calendar a-icon-gray"></i>
                    {% if v.flag_agreed > 0 or v.date_start == '0000-00-00' %}
                        по согласованию
                    {% else %}
                        {{ v.date_start|rusDate }}

                        {% if v.date_end != '0000-00-00'  and v.date_end != a.date_start %}
                            - {{ v.date_end|rusDate }}
                        {% endif %}
                    {% endif %}
                </div>
                <b>{{ v.name }}</b>

                <div class="a-row a-offset-0 offer-footer">
                    <div class="a-cols-2">
                        <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}</a>
        </div>
        <div class="a-cols-2 a-align-right">
            г. {{ v.city_name }}
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
                  action="/index.ajax.php?route=/activity/send-message-{{ activity.activity_id }}">
                <textarea class="autosize" placeholder="Написать автору анонса..." name="message"></textarea>

                <div class="a-row a-row-full">
                    <label>Ваш e-mail</label>
                    <input type="text" name="user_email" value="{{ user_info.info.email }}"/>
                </div>
                <div class="a-row a-row-full">
                    <label>Ваши телефоны</label>
                    <input type="text" name="user_phones" value="{{ user_info.info.contact_phones }}"
                           class="phones-input"/>
                </div>
                <input type="hidden" name="user_id" value="{{ activity.user_id }}"/>

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