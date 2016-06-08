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

        <form id="global-search" method="get" action="/work/vacancy/search">
            <input placeholder="Поиск в разделе ВАКАНСИИ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
        </form>

        <div class="item">

        {% if vacancy.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/work/vacancy/edit-{{ vacancy.vacancy_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if vacancy.flag == 1 %}
                        <a href="/work/vacancy/flag-{{ vacancy.vacancy_id }}-0"><i
                                    class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                    {% else %}
                        <a href="/work/vacancy/flag-{{ vacancy.vacancy_id }}-1"><i
                                    class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/work/vacancy/delete-{{ vacancy.vacancy_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if vacancy.flag_moder > 0 %}
                                        <a href="/work/vacancy/flag_moder-{{ vacancy.vacancy_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/work/vacancy/flag_moder-{{ vacancy.vacancy_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/work/vacancy/send-message-{{ vacancy.vacancy_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-vacancies-vacancy_id-{{ vacancy.vacancy_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>

                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-15-{{ vacancy.vacancy_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if vacancy.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif vacancy.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if (user_info.info.group_id == 10 and vacancy.user_id == user_info.info.user_id and "now"|datediff(vacancy.date_add) > 13) or ( vacancy.user_id == user_info.info.user_id and "now"|datediff(vacancy.date_add) > 29) %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-vacancies-vacancy_id-{{ vacancy.vacancy_id }}"><i
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
                {{ vacancy.date_add|timeago }}&nbsp; | &nbsp;
                {{ vacancy.views }} <i class="a-icon-eye-open a-icon-gray"></i>
                &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>
        </noindex>
    </div>
    <h1 class="full-title">

        {% if vacancy.urgently %}
            <span class="srochno big">Cрочно!</span>
        {% endif %}
        Требуется {{ vacancy.categs|join(', ')|lower }}, г. {{ vacancy.city_name }}
    </h1>

    <div class=" a-clear">

    <div class="col-1">
        <div class='img_top'>
            {% if vacancy.image != '' %}
                <img title="{{ vacancy.company_name }}" src="/uploads/images/work/full/{{ vacancy.image }}"/>

            {% elseif  vacancy.logotype != '' %}
                <img title="{{ vacancy.company_name }}" src="/uploads/images/work/80x100/{{ vacancy.logotype }} "
                     class='min'/>
            {% else %}
                <div class='border'><img src="/uploads/images/160x200.jpg"/></div>
            {% endif %}
        </div>
    </div>
    <div class="col-2">


        <dl class="resume-info-list">
            <dt>Работодатель:</dt>
            <dd><span class="resume-user-descr">{{ vacancy.company_name|raw }}</span></dd>
            <dt>Город:</dt>
            <dd>{{ vacancy.city_name }}</dd>
            <dt>Вид занятости:</dt>
            <dd>
                {% if vacancy.type_id == 1 %}
                    полная занятость
                {% elseif vacancy.type_id == 2 %}
                    неполная занятость
                {% elseif vacancy.type_id == 3 %}
                    удаленная работа
                {% else %}
                    посменно
                {% endif %}
            </dd>
            {% if vacancy.price > 0 %}
                <dt>Зарплата:</dt>
                <dd>
                    <span class="price"><b>от {{ vacancy.price|number_format(0, '', ' ') }} {{ vacancy.currency_name }}</b></span>
                </dd>
            {% endif %}
        </dl>
    </div>

    <div class="a-clear"><p>&nbsp;</p></div>
    <div class="a-clear"><p>&nbsp;</p></div>

    <dl class="resume-data-list a-clear">
        <dt>
            <span class='f18 bold roboto'>Контактная информация:</span>
        </dt>
        <dd>
            <dl class="resume-info-list">
                <dt>Телефоны:</dt>
                <dd>
                    {% for key, value in vacancy.phones %}
                        <span class='color727272'>{{ value }}</span> <br/>
                    {% endfor %}
                </dd>
                <dt>Контактное лицо:</dt>
                <dd>{{ vacancy.user_name }}</dd>
                {% if vacancy.site and vacancy.site != 'http://' %}
                    <dt>Веб-сайт:</dt>
                    <dd><a title="Веб-сайт" target="_blank" href="{{ vacancy.site }}">{{ vacancy.site }}</a></dd>
                {% endif %}
            </dl>
        </dd>
        <dt>
            <span class='f18 bold roboto'>Требования к соискателю:</span>
        </dt>
        <dd>
            <dl class="resume-info-list">
                <dt>Опыт работы:</dt>
                <dd>
                    {% if vacancy.experience_type == 3 %}
                        от 5 лет
                    {% elseif vacancy.experience_type == 2 %}
                        от 2 лет
                    {% elseif vacancy.experience_type == 1 %}
                        от 1 года
                    {% else %}
                        не имеет значения
                    {% endif %}
                </dd>
                <dt>Уровень образования:</dt>
                <dd>
                    {% if vacancy.education_type == 4 %}
                        среднее
                    {% elseif vacancy.education_type == 3 %}
                        среднее специальное
                    {% elseif vacancy.education_type == 2 %}
                        неоконченное высшее
                    {% elseif vacancy.education_type == 1 %}
                        высшее
                    {% else %}
                        не имеет значения
                    {% endif %}
                </dd>
            </dl>
        </dd>
        {% if vacancy.content %}
            <dt>
                <span class='f18 bold roboto '>Описание вакансии:</span>
            </dt>
            <dd>
                {{ vacancy.content|raw|nl2br }}
            </dd>
        {% endif %}
        <dt>
            <span class='f18 bold roboto mar-bottom10'>О работодателе:</span>
        </dt>
        <dd>
            {{ vacancy.description|raw|nl2br }}
        </dd>
    </dl>


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
        <p>&nbsp;</p>
    {% endif %}


    {% if vacancy.video_link %}
        <p><br/></p>
        <iframe width="100%" height="394" src="//www.youtube.com/embed/{{ vacancy.video_link }}" frameborder="0"
                allowfullscreen></iframe>
        <p><br/></p>
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
                      action="/index.ajax.php?route=/work/vacancy/send-message-{{ vacancy.vacancy_id }}">
                    <div class='zayvka'>Написать автору объявления</div>
                    <!--textarea class="autosize" placeholder="Написать автору вакансии...." name="message">Здравствуйте, меня интересует вакансия, {{ vacancy.categs|join(', ')|lower }}, г. {{ vacancy.city_name }}
			
			</textarea-->
                    <textarea autofocus='autofocus' class="autosize" placeholder="Написать автору вакансии...."
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
                    <input type="hidden" name="user_id" value="{{ vacancy.user_id }}"/>

                    <div class="form-loader display-none">
                        <i class="load"></i>
                        Загрузка...
                    </div>
                    <div class="a-float-left">
                        {% if isUserResume > 0 %}
                            <a id="send-my-resume" href="work/vacancy/send-my-resume-{{ vacancy.vacancy_id }}">
                                <b><i class="a-icon-share a-icon-gray"></i></b>
                                Отправить мое резюме
                            </a>

                        {% else %}
                            <input style="display:none" type="file" name="attach" id="attach-input"/>
                            <a id="add-atach" href="#"><i class="a-icon-plus a-icon-gray"></i> Добавить вложение</a>
                        {% endif %}
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
        <div class="vip-ads-full">
            <noindex>
                {% for v in vip %}
                    <div class="item pagination-block  light {% if v.color_yellow >1 %} color_yellow {% endif %}">
                        {% if v.show_top > 0 %}
                            <span class="l_top kon"> <span>топ</span></span>
                        {% endif %}
                        <div class='offer clear concurent vp_kon'>
                            <div style="" class=" a-align-right a-float-right">
                                <span style="">{{ v.date_add|timeago }}</span>
                            </div>

                            <a href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|translit }}">
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
                                   href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|translit }}"
                                   class="modal-window-link"></a>
                            </div>

                            <div class="name_ta vp_kon">
                                <a title="{{ v.categs }}" target="_blank"
                                   href="/work/vacancy/{{ v.vacancy_id }}-{{ v.categs|translit }}">Требуется {{ v.categs|lower }}
                                    , г. {{ v.city_name }}</a>
                            </div>


                            <div class="a-row a-offset-0 offer-footer ">
                                <div class="a-cols-2 use">
                                    <i class="a-icon-user a-icon-gray"></i> {{ v.company_name }}
                                </div>
                                <div class="a-cols-2 a-align-right">
                                    <div class="price {% if v.stock_flag %}price-stock{% endif %}">
                                        {% if v.price > 0 %}
                                            {{ v.price|number_format(0, '', ' ') }} {{ v.currency_name }} грн
                                        {% endif %}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                {% endfor %}
            </noindex>
        </div>
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