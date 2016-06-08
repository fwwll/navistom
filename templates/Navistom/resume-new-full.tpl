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

        <form id="global-search" method="get" action="/work/resume/search">
            <input placeholder="Поиск в разделе РЕЗЮМЕ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
        </form>

        <div class="item">

        {% if resume.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/work/resume/edit-{{ resume.work_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if r.flag == 1 %}
                        <a href="/work/resume/flag-{{ resume.work_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                            Скрыть</a>
                    {% else %}
                        <a href="/work/resume/flag-{{ resume.work_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                            Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/work/resume/delete-{{ resume.work_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if resume.flag_moder > 0 %}
                                        <a href="/work/resume/flag_moder-{{ resume.work_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/work/resume/flag_moder-{{ resume.work_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/work/resume/send-message-{{ resume.work_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-work-work_id-{{ resume.work_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-6-{{ resume.work_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if resume.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif resume.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if  (user_info.info.group_id == 10 and resume.user_id == user_info.info.user_id and "now"|datediff(resume.date_add) > 13) or ( resume.user_id == user_info.info.user_id and "now"|datediff(resume.date_add) > 29) %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-work-work_id-{{ resume.work_id }}"><i
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
                {{ resume.date_add|timeago }}&nbsp; | &nbsp;
                {{ resume.views }} <i class="a-icon-eye-open a-icon-gray"></i>
                &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>
        </noindex>
    </div>
    <h1 class='full-title'>
        {% if resume.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
        {{ resume.user_surname }} {{ resume.user_name }} {{ resume.user_firstname }}  {{ resume.categs|join(', ')|lower }}
        , г.{{ resume.city_name }}

    </h1>
    <span class="resume-user-descr">{{ resume.categs|join(', ') }}</span>
    <div class="a-clear">
    <div class="col-1">
        <div class='img_top '>
            {% if resume.image != '' %}
                <img title="{{ resume.user_surname }} {{ resume.user_name }} {{ resume.user_firstname }}"
                     src="/uploads/images/work/full/{{ resume.image }}" class='max'/>
            {% elseif resume.avatar != '' and resume.avatar != 'none.jpg' %}
                <img src="/uploads/users/avatars/full/{{ resume.avatar }}" class='max'/>
            {% else %}
                <img src="/uploads/images/160x200.jpg"/>
            {% endif %}
        </div>
    </div>
    <div class="col-2">


        <dl class="resume-info-list">
            <!--dt>Дата рождения:</dt>
            <dd>{{ resume.user_brith|rusDate }} &nbsp;<span class="a-color-gray">({{ resume.years }} {{ resume.years|getNameYears }})</span></dd-->
            <dt>Желаемый город работы:</dt>
            <dd>
                {{ resume.city_name }}
                {% if resume.leave_type > 0 %}
                    &nbsp;<span class="a-color-gray">(готов к переезду)</span>
                {% endif %}
            </dd>
            <dt>Занятость:</dt>
            <dd>
                {% if resume.employment_type == 1 %}
                    полная занятость
                {% elseif resume.employment_type == 2 %}
                    неполная занятость
                {% elseif resume.employment_type == 3 %}
                    удаленная работа
                {% else %}
                    посменно
                {% endif %}
            </dd>
            <dt>Зарплата:</dt>
            <dd>
                {% if resume.price > 0 %}
                    <span class="price"><b>от {{ resume.price|number_format(0, '', ' ') }} {{ resume.currency_name }}</b></span>
                {% else %}
                    не указана
                {% endif %}
            </dd>
        </dl>
    </div>

    <div class="a-clear"></div>


    <dl class="resume-data-list a-clear">
        <dt>
            <span class='f18 bold roboto'>Контактная информация:</span>
        </dt>
        <dd>
            <dl class="resume-info-list">
                <dt>Телефон:</dt>
                <dd>
                    {% for key, value in resume.phones %}
                        <span class='color727272'>{{ value }}</span> <br/>
                    {% endfor %}

                </dd>
                <dt>Город проживания:</dt>
                <dd>{{ resume.user_city }}</dd>
            </dl>

        </dd>
        {% if employment %}
            <dt>
                <span class='f18 bold roboto'>Опыт работы: </span>
            </dt>
            <dd>
                {% for e in employment %}
                    <p>
                        <b>{{ e.position }}</b>
                        {{ e.company_name }} &nbsp;<span class="a-color-gray">({{ e.activity }})</span> <br/>
                        c {{ e.date_start|rusDate }} по {{ e.date_end|rusDate }}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if education %}
            <dt>
                Образование
            </dt>
            <dd>
                {% for e in education %}
                    <p>
                        <b>
                            {% if e.type == 1 %}
                                Высшее
                            {% elseif e.type == 2 %}
                                Неоконченное высшее
                            {% elseif e.type == 3 %}
                                Среднее специальное
                            {% else %}
                                Среднее
                            {% endif %}
                        </b>
                        c {{ e.date_start|rusDate }} по {{ e.date_end|rusDate }} <br/>
                        {{ e.institution }},&nbsp; {{ e.faculty }},&nbsp; {{ e.location }}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if traning %}
            <dt>
                <span class='f18 bold roboto'>Дополнительное образование:</span>
            </dt>
            <dd>
                {% for t in traning %}
                    <p>
                        <b>{{ t.name }}</b>
                        {{ t.description }}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if langs %}
            <dt>
                Владение языками
            </dt>
            <dd>
                {% for l in langs %}
                    <p>
                        <b>{{ l.name }}</b>
                        Уровень:
                        {% if l.level == 1 %}
                            Начинающий
                        {% elseif l.level == 2 %}
                            Средний
                        {% else %}
                            Эксперт
                        {% endif %}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if resume.content %}
            <dt>
                <span class='f18 bold roboto'>Дополнительно:</span>
            </dt>
            <dd>
                <p>{{ resume.content|raw|nl2br }}</p>
            </dd>
        {% endif %}
    </dl>

    {% if gallery and resume.video_link %}
    <div id="idTabs">

        {% endif %}

        {% if gallery %}
            <div id="" class="ad-gallery">

                <div class="ad-nav">
                    <div class="ad-thumbs">

                        {% for g in gallery %}
                            <div class='foto'>

                                <img alt="{{ g.description }}" title="{{ g.description }}"
                                     src="/uploads/images/work/full/{{ g.url_full }}"/>

                            </div>
                        {% endfor %}

                    </div>
                </div>
            </div>

        {% endif %}

        {% if resume.video_link %}
            <div id="ad-video">
                <iframe width="100%" height="394" src="//www.youtube.com/embed/{{ resume.video_link }}" frameborder="0"
                        allowfullscreen></iframe>
            </div>
        {% endif %}

        {% if gallery and resume.video_link %}
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
                                                                                     id="send-error-mess-link" href="#"
                                                                                     class='color727272'>
                        <i class="alert-new-iconz"></i> Пожаловаться
                    </a></div>
            </noindex>

        </div>


        {% if user_info %}
            <div class="a-clear">
                <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                      action="/index.ajax.php?route=/work/send-message-{{ resume.work_id }}">
                    <div class='zayvka'>Написать автору объявления</div>
                    <!--textarea class="autosize" placeholder="Написать автору резюме..." name="message">Здравствуйте, меня интересует резюме {{ resume.user_surname }} {{ resume.user_name }} {{ resume.user_firstname }} {{ resume.categs|join(', ') }}
			 
			</textarea-->
                    <textarea class="autosize" autofocus='autofocus' placeholder="Написать автору резюме..."
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
                    <input type="hidden" name="user_id" value="{{ resume.user_id }}"/>

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
        </noindex>
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

                        <a href="/work/resume/{{ v.work_id }}-{{ v.categs|translit }}">
                            {% if v.image %}
                                <img title="{{ v.categs }}" alt="{{ v.categs }}"
                                     src="/uploads/images/work/full/{{ v.image }}"/>
                            {% else %}
                                <img title="{{ v.categs }}" alt="{{ v.categs }}" src="/uploads/images/100x80.jpg"/>
                            {% endif %}
                        </a>

                        <div class='filter_l'>
                            {{ v.categs }}
                        </div>
                        {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic vp_kon '>
                            <a title="{{ v.categs }}" target="_blank"
                               href="/work/resume/{{ v.work_id }}-{{ v.categs|translit }}"
                               class="modal-window-link"></a>
                        </div>

                        <div class="name_ta vp_kon">

                            <a title="{{ v.categs }}" target="_blank"
                               href="/work/resume/{{ v.work_id }}-{{ v.categs|translit }}" class="">
                                Резюме, {{ v.categs }}, г. {{ v.city_name }}
                            </a>

                        </div>

                        <div class="a-row a-offset-0 offer-footer  vp_kon">
                            <div class="a-cols-2">
                                <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price {% if v.stock_flag %}price-stock{% endif %}">
                                    {% if v.price > 0 %}
                                        {{ v.price|number_format(0, '', ' ') }} {{ v.currency_name }} гри
                                    {% endif %}
                                </div>
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