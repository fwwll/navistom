{#% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %#}
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
        <div id="article-full">
    {% else %}
        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск разделе СТАТЬИ" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
        </form>
        <div class="item" style="padding:50px 100px">
        {% if is_admin %}
            <ul style="margin:-50px -100px 20px -100px" class="options full clear">
                <li>
                    <a class="ajax-link" href="/article/edit-{{ article.article_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if article.flag == 1 %}
                        <a href="/article/flag-{{ article.article_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i>
                            Скрыть</a>
                    {% else %}
                        <a href="/article/flag-{{ article.article_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                            Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    {% if article.flag_moder > 0 %}
                        <a href="/article/flag_moder-{{ article.article_id }}-0"><i
                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                    {% else %}
                        <a href="/article/flag_moder-{{ article.article_id }}-1"><i class="a-icon-ok a-icon-gray"></i>
                            Одобрить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/article/delete-{{ article.article_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                <li class="satus">
                    {% if article.flag_moder == 0 or a.date_public == '0000-00-00 00:00:00' %}
                        <span class="red">На модерации</span>
                    {% elseif article.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
            </ul>
        {% endif %}
    {% endif %}
    <div class="navi-article-info a-clear a-font-smal">
        <div class="a-float-left a-color-gray a-font-small">
            {{ article.date_public|timeago }}&nbsp; | &nbsp;
            {{ comm_count }}<i class="a-icon-comment a-icon-gray"></i>&nbsp; | &nbsp;
            {{ article.views }} <i class="a-icon-eye-open a-icon-gray"></i>
        </div>
    </div>
    <h1 class="article-title">{{ article.name|raw }}</h1>
    {% if article.author %}
        <div class="n-article-author">
            <p>
                <i class="a-icon-user a-icon-gray"></i> {{ article.author }}
                <br><br>
        </div>



    {% endif %}
    <div style="float:right;">
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
        <div class="pluso" style="margin-right:15px" data-background="transparent"
             data-options="big,round,line,horizontal,nocounter,theme=04"
             data-services="facebook,google,vkontakte,odnoklassniki,twitter" data-user="321746015"></div>
    </div>
    <p>&nbsp;</p>
    <div id="article-content">
        {{ article.content|raw }}
    </div>
    {% if gallery and article.video_link %}
<div class="idTabs">

    {% endif %}
    {% if gallery %}
        <div id="" class="ad-gallery">
            <div class="ad-nav">
                <div class="ad-thumbs">
                    {% for g in gallery %}
                        <div class='foto'>
                            <img title="{{ g.description }}" alt="{{ g.description }}"
                                 src="/uploads/images/articles/full/{{ g.url_full }}"/>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </div>
        <p class='clear'>&nbsp;</p>
    {% endif %}
    {% if article.video_link %}
        <div id="ad-video">
            <iframe width="600" height="394" src="//www.youtube.com/embed/{{ article.video_link }}" frameborder="0"
                    allowfullscreen></iframe>
        </div>
    {% endif %}
    {% if gallery and article.video_link %}
</div>
    {% endif %}
    <p class='clear'>&nbsp;</p>
    <div class="categ_clear">
        <i class='book-new-iconz'></i>
        {% for key, value in article.categs %}
            &nbsp;&nbsp;<a title="{{ value }}" href="/articles/categ-{{ key }}-{{ value|translit }}"
                           class="a-color-gray">{{ value }}</a>
        {% endfor %}
    </div>
    <div class="a-color-gray">
        <i class="a-icon-tags"></i>&nbsp;
        {% for key, value in article.tags %}
            <a title="{{ value }}" href="/articles/tag-{{ key }}-{{ value|translit }}">{{ value }}</a>&nbsp;&nbsp;
        {% endfor %}
        <p class='clear'>&nbsp;</p>
    </div>
    <div class="a-color-gray">
        <noindex>
            <i class="link-new-iconz"></i>&nbsp;
            <a title="{{ article.source_name }}" target="_blank" href="{{ article.source_link }}">
                {{ article.source_name }}
            </a>
        </noindex>
        <p class='clear'>&nbsp;</p>
    </div>
    <p class='clear'>&nbsp;</p>
    {% if interview %}
        <div class="n-title">
            Опрос
        </div>
        <b>{{ interview.vote.name }}</b>
        {% if is_user_vote or is_user == 0 %}
            <div class="n-interview-result a-clear">
                {% for v in interview.versions %}
                    <div class="n-interview-desc">{{ v.name }}</div>
                    <div class="n-inerview-bg">
                        <div style="width:{{ '%.0f'|format((v.count * 100) / interview.sum) }}%"
                             class="n-inerview-res"></div>
                        <span>{{ v.count }}</span>
                    </div>
                    <div class="n-interview-right">{{ '%.0f'|format( (v.count * 100) / interview.sum ) }}%</div>
                {% endfor %}
            </div>
        {% else %}
            <div id="article-votes-list">
                <form id="article-vote-add" name="article-vote" method="post"
                      action="/index.ajax.php?route=/article/vote_result_add">
                    <input type="hidden" name="vote_id" value="{{ interview.vote.vote_id }}"/>
                    {% for key, value in interview.versions %}
                        <div class="a-row">
                            <input type="radio" name="version_id" value="{{ key }}" id="version_{{ key }}"/>
                            <label for="version_{{ key }}">{{ value }}</label>
                        </div>
                    {% endfor %}
                </form>
            </div>
        {% endif %}
        <p><br/></p>
    {% endif %}
    <div class="n-comments a-clear">
        {% if comments %}
            <div class="title">
                Комментарии
            </div>
            <div id="comment-list">
                {% for c in comments %}
                    <div class="n-comment a-clear">
                        <div class="col-1">
                            <img title="{{ c.name }}" alt="{{ c.name }}"
                                 src="/uploads/users/avatars/tumb2/{{ c.avatar }}"/>
                        </div>
                        <div class="col-2">
                            <div class="a-row">
                                <div class="a-cols-2">
                                    <span class="a-font-small a-color-gray" href="#"><i
                                                class="a-icon-user a-icon-gray"></i> {{ c.name }}</span>
                                </div>
                                <div class="a-cols-2">
                                    <div class="a-float-right a-font-small a-color-gray">{{ c.date_add|rusDate }}</div>
                                </div>
                            </div>
                            {{ c.comment|raw }}
                        </div>
                    </div>
                {% else %}
                {% endfor %}
            </div>

        {% endif %}
        {% if is_user %}
        <form id="send-user-mess" class="n-comment-add a-clear ajax-form" method="post"
              action="/index.ajax.php?route=/article/comment_add">
            <div class='zayvka'>Добавить комментарий</div>
            <textarea class="autosize" placeholder="Начните вводить текст..." name="comment"></textarea>
            <input type="hidden" name="article_id" value="{{ article.article_id }}"/>

            <div class="a-float-right">
                <input class="a-btn-green" type="submit" value="Отправить"/>
            </div>
        </form>
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
            {% else %}
                <div>

                    <div class="print-mess">


                        <noindex>
                            <form method='post' name='p'>
                                <input type='hidden' name='print' value='1'>
                            </form>
                            <div style="float:left" target="_blank" class='color727272 print'><i
                                        class="print-new-iconz"></i> На печать
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
                    <div class="pluso" style="width: 295px" data-background="transparent"
                         data-options="big,round,line,horizontal,nocounter,theme=04"
                         data-services="facebook,google,vkontakte,odnoklassniki,twitter"></div>
                    <div class="bugr-mess">


                        <noindex>
                            <div style="float:left" target="_blank" class='color727272 print'><a title="Пожаловаться "
                                                                                                 id="send-error-mess-link"
                                                                                                 href="#"
                                                                                                 class='color727272'>
                                    <i class="alert-new-iconz"></i> Пожаловаться
                                </a></div>
                        </noindex>

                    </div>

                    <br>
                    <center>
                        <div class="a-mess-yellow">
                            <i class="a-icon-comment a-icon-gray"></i>
                            Чтобы оставить комментарий, нужно <a title="Вход" href="/login">войти</a> или <a
                                    title="Регистрация" href="/registration">зарегистрироваться</a>
                    </center>
                </div>
                </center>
            {% endif %}
            {% if banner_footer_content.link or banner_footer_content.code %}
                <noindex>
                    {% if banner_footer_content.code %}
                        <div style="margin:30px 0 -30px -60px;text-align:center">
                            {{ banner_footer_content.code|raw }}
                        </div>
                    {% else %}
                        <a {% if ajax %} style="margin-bottom:30px" {% else %} style="margin:30px -50px; text-align:center" {% endif %}
                                id="footer-content-banner" href="{{ banner_footer_content.link }}"
                                target="{{ banner_footer_content.target }}">
                            <img src="/uploads/banners/{{ banner_footer_content.image }}"/>
                        </a>
                    {% endif %}
                </noindex>
            {% endif %}
            <noindex>
                <section class="article-offers-list">
                    {% for offer in last_offers %}
                        <div class="offer clear">
                            {% if offer.image == '/uploads/images/work/142x195/' or offer.image == '/uploads/images/products/142x195/' %}
                                {% set image = '' %}
                            {% else %}
                                {% set image = offer.image %}
                            {% endif %}
                            {% if offer.section_id == 5 %}
                                <div class="lector-image">
                                    <img alt="{{ offer.name }}" title="{{ offer.name }}"
                                         src="{{ image|default('/uploads/images/100x80.jpg') }}">
                                </div>
                            {% else %}
                                <img alt="{{ offer.name }}" title="{{ offer.name }}"
                                     src="{{ image|default('/uploads/images/100x80.jpg') }}">

                            {% endif %}
                            <div class="content">
                                {% if offer.section_id == 5 %}
                                    <div style="color:#333" class=""><i class="a-icon-calendar a-icon-gray"></i>
                                        {% if offer.description == '0000-00-00' %}
                                            по согласованию
                                        {% else %}
                                            {{ offer.description|rusDate }}
                                            {% if offer.price != '0000-00-00' and offer.description != offer.price %}
                                                - {{ offer.price|rusDate }}
                                            {% endif %}
                                        {% endif %}
                                    </div>
                                {% endif %}
                                {% if offer.section_id == 3 or offer.section_id == 2 %}
                                    <a title="" href="/product/{{ offer.content_id }}-{{ offer.name|translit }}"
                                       class="title">{{ offer.name }}</a>
                                {% elseif offer.section_id == 9 %}
                                    <a title="" href="/service/{{ offer.content_id }}-{{ offer.name|translit }}"
                                       class="title">{{ offer.name }}</a>
                                {% elseif offer.section_id == 7 %}
                                    <a title="" href="/lab/{{ offer.content_id }}-{{ offer.name|translit }}"
                                       class="title">{{ offer.name }}</a>
                                {% else %}
                                    <a title="" href="{{ offer.link }}/{{ offer.content_id }}-{{ offer.name|translit }}"
                                       class="title">{{ offer.name }}</a>
                                {% endif %}
                                {% if offer.section_id == 3 or offer.section_id == 4 or offer.section_id == 2 %}
                                    <div class="a-font-small a-color-gray">{{ offer.description }}</div>
                                {% endif %}
                                <div class="bottom">
                                    {% if offer.price and offer.currency_name and offer.currency_id %}
                                        {% if offer.section_id == 2 %}
                                            <div class="price {% if offer.section_id == 2 %}price-stock{% endif %}">{{ offer.price|getExchangeRates(offer.currency_id, offer.user_id)|number_format(2, '.', ' ') }}
                                                грн
                                            </div>
                                        {% else %}
                                            <div class="price {% if offer.section_id == 2 %}price-stock{% endif %}">{{ offer.price|getExchangeRates(offer.currency_id, offer.user_id)|number_format(2, '.', ' ') }}
                                                грн
                                            </div>
                                        {% endif %}
                                    {% endif %}
                                </div>
                                <a class="section" href="{{ offer.link }}">
                                    {{ offer.icon|raw }}
                                    <div>{{ offer.section_name }}</div>
                                </a>
                            </div>
                        </div>
                    {% endfor %}
                </section>
            </noindex>
        </div>
    </div>
{% endblock %}