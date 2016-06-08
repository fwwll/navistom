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

        {% if activity.flag_delete > 0 %}
            <script>
                {% for key, value in activity.categs %}
                window.location.href = "/activity/categ-{{key}}-{{value|translit}}#404";
                {% endfor %}
            </script>
        {% endif %}

    {% else %}

        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе Анонсы мероприятий" type="text" value="{{ route.values.search }}"
                   name="q" id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
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
                {% if  (user_info.info.group_id == 10 and activity.user_id == user_info.info.user_id and "now"|datediff(activity.date_add) > 13 )or (activity.user_id == user_info.info.user_id and "now"|datediff(activity.date_add) > 29 ) %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-activity-activity_id-{{ activity.activity_id }}"><i
                                        class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>

                            <div>
                            </div>
                        </div>
                    </li>
                {% endif %}
            </ul>

        {% endif %}

    {% endif %}
<div class='full_padding' itemscope itemtype="http://schema.org/Event">
    <div class="a-row a-offset-0">
        <noindex>
            <div class="a-cols-2 a-font-small a-color-gray">

                {{ activity.date_add|timeago }} &nbsp; | &nbsp; {{ activity.views }} <i
                        class="a-icon-eye-open a-icon-gray"></i>
                &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>
        </noindex>
    </div>

    <h1 class="full-title">
        {% if activity.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
        {% if activity.flag_agreed > 0 or activity.date_start == '0000-00-00' %}
        Дата по согласованию
    {% else %}
        <time itemprop="startDate" datetime="{{ activity.date_start }}"> {{ activity.date_start|rusFormat }} </time>

        {% if activity.date_end != '0000-00-00' and activity.date_end != activity.date_start %}
            -
            <time itemprop="endDate"
                  datetime="{{ activity.date_end }}">{{ activity.date_end|rusFormat }}</time>{% endif %}{% endif %}
        , {{ activity.city_name }}
        <br/>
        <span itemprop="name">{{ activity.name|raw }}</span>
    </h1>

    <div class="n-ad-full a-clear">

        <div class="col-1">
            <div class='img_top'>
                {% if activity.img_l %}
                    <img title="{{ activity.name }}" src="/uploads/images/activity/full/{{ activity.img_l }}"/>
                {% elseif activity.image %}
                    {% if isBigImg(activity.image,'activity','160x200') %}
                        <img title="{{ activity.name }}" src="/uploads/images/activity/160x200/{{ activity.image }}"/>
                    {% else %}
                        <img title="{{ activity.name }}" src="/uploads/images/activity/full/{{ activity.image }}"/>
                    {% endif %}

                {% else %}
                    <img src="/uploads/images/160x200.jpg"/>
                {% endif %}
            </div>
            <ul id="ad-info-list">

                <li>
                    <a href="/activity/user-{{ activity.user_id }}-{{ activity.user_name|translit }}"
                       class='color727272'><i class="user-new-iconz"></i>&nbsp;  {{ activity.user_name|raw }}</a>

                </li>
                {% for key, value in activity.phones %}
                    {% if value != '' %}
                        <li>
                            <i class="tel-new-iconz"></i>&nbsp; <span class='color727272'>{{ value }}</span>
                        </li>
                    {% endif %}
                {% endfor %}
                <li>
                    <i class="globe-new-iconz"></i><span class='color727272'> г. {{ activity.city_name }}</span>
                </li>
                {% if activity.address %}
                    <li>
                        <i class="home-new-iconz "></i> <span class='color727272'>{{ activity.address|raw }}</span>
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
                        <noindex>
                            <i class="link-new-iconz"></i>
                            <a title="Ссылка на сайт мероприятия" target="_blank" href="{{ activity.link }}">
				 <span class='color727272'>
				 Ссылка на сайт мероприятия
				 </span>
                            </a>
                        </noindex>
                    </li>
                {% endif %}

            </ul>


        </div>
        <div class="col-2">
            {% for l in lectors %}
                <div class="n-lectors-list">

                    <img title="{{ l.name }}" alt="{{ l.name }}" src="/uploads/images/activity/lectors/{{ l.image }}"/>

                    <div style="clear:both"></div>

                    <b>{{ l.name }}</b><br/>
                    {{ l.description|raw|nl2br }}

                </div>
            {% endfor %}

            <span itemprop="description"> {{ activity.content|raw|nl2br }} </span>


        </div>

        <div class='asd'>
            {% if gallery %}
                <div id="" class="ad-gallery">

                    <div class="ad-nav">


                        {% for g in gallery %}
                            <div class='foto'>

                                <img alt="{{ g.description }}" title="{{ g.description }}"
                                     src="/uploads/images/activity/full/{{ g.url_full }}"/>

                            </div>
                        {% endfor %}


                    </div>
                </div>

            {% endif %}

        </div>


        <div class="a-clear"></div>

        {% if activity.video_link %}
            <iframe width="100%" height="394" src="//www.youtube.com/embed/{{ activity.video_link }}" frameborder="0"
                    allowfullscreen></iframe>
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
                          action="/index.ajax.php?route=/activity/send-message-{{ activity.activity_id }}">
                        <div class='zayvka'>Написать автору объявления</div>
                        <!--textarea class="autosize" placeholder="Написать автору анонса..." name="message">Здравствуйте, меня интересует объявление {% if activity.flag_agreed > 0 or activity.date_start == '0000-00-00' %} Дата по согласованию{% else %}{{ activity.date_start|rusDate }}{% if activity.date_end != '0000-00-00' and activity.date_end != activity.date_start %} - {{ activity.date_end|rusDate }}{% endif %}{% endif %},{{ activity.city_name }} {{ activity.name|raw }}
			</textarea-->
                        <textarea autofocus='autofocus' class="autosize" placeholder="Написать автору анонса..."
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
                    <center>
                        <i class="a-icon-envelope a-icon-gray"></i>
                        Чтобы написать автору, нужно <a title="Вход" href="/login"> войти</a> или <a title="Регистрация"
                                                                                                     href="/registration">зарегистрироваться</a><br/>
                    </center>
                </div>

            {% endif %}
        </div>
    </div>
    <noindex>
        {% if vip %}
            <div class="vip-ads-full clear">
                {% for v in vip %}
                    <div class="item pagination-block  light {% if v.color_yellow >1 %} color_yellow {% endif %}">

                        {% if v.show_top > 0 %}
                            <span class="l_top kon"> <span>топ</span></span>
                        {% endif %}


                        <div class='offer clear concurent'>
                            <div style="" class="a-align-right a-float-right">
                                <span class='data_vip'>{{ v.date_add|timeago }}</span>
                            </div>
                            <div class="lector-image">
                                {% if v.image %}
                                    <img title="{{ v.image }}" alt="{{ v.image }}"
                                         src="/uploads/images/activity/lectors/{{ v.image }}"/>
                                {% else %}
                                    <img title="{{ v.image }}" alt="{{ v.image }}" src="/uploads/images/80x100.jpg"/>
                                {% endif %}
                            </div>

                            <div class="name_ta t"><i class="a-icon-calendar a-icon-gray"></i>
                                {% if v.flag_agreed > 0 or v.date_start == '0000-00-00' %}
                                    по согласованию
                                {% else %}
                                    {{ v.date_start|rusFormat }}

                                    {% if v.date_end != '0000-00-00'  and v.date_end != v.date_start %}
                                        - {{ v.date_end|rusFormat }}
                                    {% endif %}
                                {% endif %}
                            </div>
                            {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                            <div class='ic vp_kon '>
                                <a title="{{ v.product_name }}" target="_blank"
                                   href="/activity/{{ v.activity_id }}-{{ v.name|translit }}"
                                   class="modal-window-link"></a>
                            </div>
                            <div class="name_ta vp_kon"><a
                                        href="/activity/{{ v.activity_id }}-{{ v.name|translit }}">{{ v.name }}
                                    г. {{ v.city_name }}</a></div>

                            <div class="a-row a-offset-0 offer-footer bat">
                                <div class="a-cols-2 u_vip">
                                    <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                                </div>
                                <div class="a-cols-2 a-align-right b">

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