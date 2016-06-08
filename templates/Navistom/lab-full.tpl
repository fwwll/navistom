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
            <input placeholder="Поиск в разделе ЗУБОТЕХНИЧЕСКИЕ ЛАБОРАТОРИИ" type="text"
                   value="{{ route.values.search }}" name="q" id="global-search-input"/>
            <button id="search-submit" type="submit">Искать</button>
        </form>

        <div class="item">

        {% if lab.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/lab/edit-{{ lab.lab_id }}"><i class="a-icon-pencil a-icon-gray"></i>
                        Редактировать</a>
                </li>
                <li>
                    {% if lab.flag == 1 %}
                        <a href="/lab/flag-{{ lab.lab_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                    {% else %}
                        <a href="/lab/flag-{{ lab.lab_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                            Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/lab/delete-{{ lab.lab_id }}"><i class="a-icon-trash a-icon-gray"></i>
                        Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if lab.flag_moder > 0 %}
                                        <a href="/lab/flag_moder-{{ lab.lab_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/lab/flag_moder-{{ lab.lab_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/lab/send-message-{{ lab.lab_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-labs-lab_id-{{ lab.lab_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-7-{{ lab.lab_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if lab.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif lab.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if lab.user_id == user_info.info.user_id and "now"|datediff(lab.date_add) > 13 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-labs-lab_id-{{ lab.lab_id }}"><i
                                        class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                            <div>

                            </div>
                        </div>
                    </li>
                {% endif %}
            </ul>
        {% endif %}

    {% endif %}

    <div class="a-row a-offset-0">
        <div class="a-cols-4 a-font-small a-color-gray">
            {{ lab.date_add|timeago }} &nbsp; | &nbsp;

            {{ lab.views }} <i class="a-icon-eye-open a-icon-gray"></i> &nbsp;|&nbsp; <span class="raceta2 cor_rac"
                                                                                            onclick='raceta()'
                                                                                            title='рекламировать'></span>
        </div>
        <!--div class="a-cols-2 a-font-small a-color-gray a-align-right">
    	<a href="/">NaviStom Украина</a> -
    	<a href="/labs/city-{{ lab.city_id }}-{{ lab.city_name|translit }}">Зуботехнические лаборатории {{ lab.city_name }}</a> -
        {% for key, value in lab.categs %}
            <a title="{{ value }}" href="/labs/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>&nbsp;&nbsp;
        {% endfor %}
    </div-->
    </div>

    <h1 class="full-title">
        {% if lab.urgently %}
            <span class="srochno big">Cрочно!</span>
        {% endif %}
        {{ lab.name|raw }}, г.{{ lab.city_name }}
    </h1>

    <div class="n-ad-full n-lab-full a-clear">

        <div class="col-1">
            {% if lab.image %}
                <img title="{{ lab.name }}" src="/uploads/images/labs/160x200/{{ lab.image }}"/>
            {% else %}
                <img src="/uploads/images/160x200.jpg"/>
            {% endif %}

            <ul id="ad-info-list">
                <li>
                    <a href="/labs/user-{{ lab.user_id }}-{{ lab.user|translit }}">
                        <i class="a-icon-user a-icon-gray"></i>&nbsp; {{ lab.user|raw }}
                    </a>
                </li>
                {% for key, value in lab.phones %}
                    {% if value != '' %}
                        <li>
                            <i class="a-icon-phone a-icon-gray"></i>&nbsp; {{ value }}
                        </li>
                    {% endif %}
                {% endfor %}
                <li>
                    <i class="a-icon-globe a-icon-gray"></i> {{ lab.region_name }}, г. {{ lab.city_name }}
                    <br/> {{ lab.address|raw }}
                </li>
                {% if lab.attach %}
                    <li>
                        <i class="a-icon-file a-icon-gray"></i>
                        <a title="Прайс-лист" target="_blank" href="http://navistom.com/uploads/docs/{{ lab.attach }}">Прайс-лист</a>
                    </li>
                {% endif %}
                {% if lab.link %}
                    <li>
                        <i class="a-icon-link a-icon-gray"></i>
                        <a title="Веб сайт" target="_blank" href="{{ lab.link }}">Веб сайт</a>
                    </li>
                {% endif %}
                <li class="print-btn">
                    <noindex>
                        <a target="_blank" href="/lab/{{ lab.lab_id }}-{{ lab.name|translit }}?print"><i
                                    class="a-icon-print a-icon-gray"></i> На печать</a>
                    </noindex>
                </li>
            </ul>
        </div>
        <div class="col-2">
            {{ lab.content|raw|nl2br }}
        </div>

        <div class="a-clear"></div>

        {% if gallery and lab.video_link %}
        <div id="idTabs">
            <ul class="idTabs idTabsLeft a-clear">
                <li>
                    <a href="#ad-gallery-700">Фото</a>
                </li>
                <li>
                    <a href="#ad-video">Видео</a>
                </li>
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
                                        <a href="/uploads/images/labs/full/{{ g.url_full }}">
                                            <img title="{{ g.description }}" alt="{{ g.description }}"
                                                 src="/uploads/images/labs/80x100/{{ g.url_full }}"/>
                                        </a>
                                    </li>
                                {% endfor %}
                            </ul>
                        </div>
                    </div>
                </div>
                <p>&nbsp;</p>
            {% endif %}

            {% if ads.video_link %}
                <div id="ad-video">
                    <iframe width="700" height="394" src="//www.youtube.com/embed/{{ labs.video_link }}" frameborder="0"
                            allowfullscreen></iframe>
                </div>
            {% endif %}

            {% if gallery and ads.video_link %}
        </div>
        {% endif %}

        {% if vip %}
            <div class="vip-ads-full">
                {% for v in vip %}
                    <div class="vip-item clear">
                        <a href="/lab/{{ v.lab_id }}-{{ v.name|translit }}">
                            {% if v.image %}
                                <img title="{{ v.image }}" alt="{{ v.image }}"
                                     src="/uploads/images/labs/80x100/{{ v.image }}"/>
                            {% else %}
                                <img title="{{ v.image }}" alt="{{ v.image }}" src="/uploads/images/100x80.jpg"/>
                            {% endif %}
                            <b>{{ v.name }}</b>

                            <div class="a-row a-offset-0 offer-footer">
                                <div class="a-cols-2">
                                    <i class="a-icon-user a-icon-gray"></i> {{ v.user }}
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
                      action="/index.ajax.php?route=/lab/send-message-{{ lab.lab_id }}">
                    <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>

                    <div class="a-row a-row-full">
                        <label>Ваш e-mail</label>
                        <input type="text" name="user_email" value="{{ user_info.info.email }}"/>
                    </div>
                    <div class="a-row a-row-full">
                        <label>Ваши телефоны</label>
                        <input type="text" name="user_phones" value="{{ user_info.info.contact_phones }}"
                               class="phones-input"/>
                    </div>
                    <input type="hidden" name="user_id" value="{{ lab.user_id }}"/>

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