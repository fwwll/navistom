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
        <div style="width:700px">
    {% else %}

        <form id="global-search" method="get" action="/work/resume/search">
            <input placeholder="Поиск в разделе РЕЗЮМЕ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit">Искать</button>
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
                {% if resume.user_id == user_info.info.user_id and "now"|datediff(resume.date_add) > 13 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-work-work_id-{{ resume.work_id }}"><i
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
            {{ resume.date_add|timeago }}&nbsp; | &nbsp;
            {{ resume.views }} <i class="a-icon-eye-open a-icon-gray"></i>
            &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
        </div>
        <!--div class="a-cols-2 a-font-small a-color-gray a-align-right">
    	<a href="/">NaviStom Украина</a> -
    	<a href="/work/resume/city-{{ resume.city_id }}-{{ resume.city_name|translit }}">Резюме {{ resume.city_name }}</a> -
        {% for key, value in resume.categs %}
        	<a title="{{ value }}" href="/work/resume/categ-{{ key }}-{{ value|translit }}">{{ value }}</a>&nbsp;&nbsp;
        {% endfor %}
    </div-->
    </div>

    <div class="n-ad-full a-clear">
    <div class="col-1">
        {% if resume.image != '' %}
            <img title="{{ resume.user_surname }} {{ resume.user_name }} {{ resume.user_firstname }}"
                 src="/uploads/images/work/160x200/{{ resume.image }}"/>
        {% elseif resume.avatar != '' and resume.avatar != 'none.jpg' %}
            <img src="/uploads/users/avatars/full/{{ resume.avatar }}"/>
        {% else %}
            <img src="/uploads/images/160x200.jpg"/>
        {% endif %}
    </div>
    <div class="col-2">
        <h1>
            {% if resume.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
            {{ resume.user_surname }} {{ resume.user_name }} {{ resume.user_firstname }}

        </h1>
        <span class="resume-user-descr">{{ resume.categs|join(', ') }}</span>

        <dl class="resume-info-list">
            <dt>Дата рождения:</dt>
            <dd>{{ resume.user_brith|rusDate }} &nbsp;<span
                        class="a-color-gray">({{ resume.years }} {{ resume.years|getNameYears }})</span></dd>
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
    <p>&nbsp;</p>

    <dl class="resume-data-list a-clear">
        <dt>
            Контактная информация
        </dt>
        <dd>
            <dl class="resume-info-list">
                <dt>Телефон:</dt>
                <dd>{{ resume.phones|join("<br />")|raw }}</dd>
                <dt>Город проживания:</dt>
                <dd>{{ resume.user_city }}</dd>
            </dl>
            <!--<p>Телефон: <strong>{{ resume.contact_phones }}</strong><br /></p>
            <p>Город проживания:</p>-->
        </dd>
        {% if employment %}
            <dt>
                Опыт работы
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
                Дополнительное образование
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
                Дополнительно
            </dt>
            <dd>
                <p>{{ resume.content|raw|nl2br }}</p>
            </dd>
        {% endif %}
    </dl>

    {% if gallery and resume.video_link %}
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
                                    <a href="/uploads/images/work/full/{{ g.url_full }}">
                                        <img alt="{{ g.description }}" title="{{ g.description }}"
                                             src="/uploads/images/work/80x100/{{ g.url_full }}"/>
                                    </a>
                                </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
        {% endif %}

        {% if resume.video_link %}
            <div id="ad-video">
                <iframe width="700" height="394" src="//www.youtube.com/embed/{{ resume.video_link }}" frameborder="0"
                        allowfullscreen></iframe>
            </div>
        {% endif %}

        {% if gallery and resume.video_link %}
    </div>
    {% endif %}
    <noindex>
        <a target="_blank" href="/work/resume/{{ resume.work_id }}-{{ resume.categs|join('-')|translit }}?print"><i
                    class="a-icon-print a-icon-gray"></i> На печать</a>
    </noindex>
    {% if vip %}
        <div class="vip-ads-full">
            <div class="a-row">
                {% for v in vip %}
                    <div class="a-cols-2">
                        <a href="/work/resume/{{ v.work_id }}-{{ v.categs|translit }}">
                            {% if v.image %}
                                <img title="{{ v.categs }}" alt="{{ v.categs }}"
                                     src="/uploads/images/work/80x100/{{ v.image }}"/>
                            {% else %}
                                <img title="{{ v.categs }}" alt="{{ v.categs }}" src="/uploads/images/100x80.jpg"/>
                            {% endif %}
                            <b>Резюме, {{ v.categs }}, г. {{ v.city_name }}</b>
                        </a>
                    </div>
                {% endfor %}
            </div>
        </div>
    {% endif %}

    {% if user_info %}
        <div class="a-clear">
            <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                  action="/index.ajax.php?route=/work/send-message-{{ resume.work_id }}">
                <textarea class="autosize" placeholder="Написать автору резюме..." name="message"></textarea>

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