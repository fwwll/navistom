<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    {% if canonical %}
        {% if canonical== '/main' %}
            <link rel="canonical" href="http://navistom.com"/>
        {% else %}
            <link rel="canonical" href="http://navistom.com{{ canonical }}"/>
        {% endif %}
    {% endif %}
    <title>{{ meta_title }}</title>
    {% for name, content in meta_tags %}
        {% if content != '-' %}
            <meta name="{{ name }}" content="{{ content }}"/>
        {% endif %}
    {% endfor %}
    {% if json_breadcrumb %}
        <script type="application/ld+json">{% autoescape false %}{{ json_breadcrumb }}{% endautoescape %}</script>{% endif %}
    <link rel=icon href=/{{ tpl_dir }}/images/navi-favicon.png type=image/x-icon>
    {% for property, content in social_meta_tags %}
        <meta property={{ property }} content={{ content }} />
    {% endfor %}
    {% if error %}
        <meta name=robots content="noindex, follow"/>
    {% endif %}
    {% if route.values.page and route.values.page >1 %}
        <meta name=robots content="noindex, follow"/>
    {% endif %}
    {% if route.controller=="payment" %}
        <meta name=robots content="noindex, nofollow"/>
    {% endif %}
    <meta http-equiv=Content-Type content="text/html; charset=utf-8"/>
    <meta name=format-detection content="telephone=no"/>
    <meta http-equiv=content-language content=ru/>
    <!--meta name=viewport content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /-->
    <meta name="viewport" content="width=850">
    {% if is_admin == 2492 %}
        <script type=text/javascript src=/{{ tpl_dir }}/debug/assets/codemirror/codemirror.js></script>
        <script type=text/javascript src=/{{ tpl_dir }}/debug/assets/codemirror/mode/sql/sql.js></script>
        <link rel=stylesheet href=/{{ tpl_dir }}/debug/assets/codemirror/codemirror.css/>
        <link rel=stylesheet href="/{{ tpl_dir }}/debug/assets/codemirror/ambiance.css?t={{ md5time }}"/>
        <link rel=stylesheet href="/{{ tpl_dir }}/styles/debug.css?t={{ md5time }}"/>
    {% endif %}
    {{ renderCss('all_new2.min.css')|raw }}
</head>
<body>

{% if banner_bg.link %}
    <div href={{ banner_bg.link }} target={{ banner_bg.target }}
         style="background-image:URL('/uploads/banners/{{ banner_bg.image }}');
                 background-color:#000; " id=banner-bg class='redirect'></div>
{% endif %}

<div id=fixed>
    <header>
<span id=logo>
{% if url !='/' %}
    <a href=/><img title=NaviStom alt=NaviStom src=/{{ tpl_dir }}/images/logo.png alt=logotype style=cursor:pointer></a>
{% else %}
    <img title=NaviStom alt=NaviStom src=/{{ tpl_dir }}/images/logo.png alt=logotype>
{% endif %}
</span>
<span class=menu>
<img src="/{{ tpl_dir }}/images/menu1.png"/>
</span>

        <div class=menu_new>
            <div class=men1>
                {% if route.controller !='cabinet' and route.controller !='payment' and  route.action !='login' and route.action !='registration' %}
                    <div class=breadcrumbs_sub>
                        {% if route.controller !='main' %}
                            <span class='bread s'><a href=/>Главная </a></span>
                        {% endif %}
                        {% for bred in breadcrumb %}
                            {% if bred.url!='/main' %}
                                {% if loop.index == loop.length %}
                                    ><span class='bread s{{ loop.index }}'> {{ bred.name }}</span>
                                {% else %}
                                    ><span class='bread s{{ loop.index }}'> <a href={{ bred.url }}> {{ bred.name }}</a></span>
                                {% endif %}
                            {% else %}
                                <span class='bread s1'></span>
                                <span class='bread s2'></span>
                                <span class='bread s3'></span>
                            {% endif %}
                        {% endfor %}
                    </div>
                {% endif %}
                <ul class='catig list-as-select' id=selection-categs>
                    {% for i in 0..9 %}
                        <li>
                            <a title={{ sections_min[i].name }} target={{ sections_min[i].target }}
                               href={{ sections_min[i].link }}>
                                <div class=ic>
                                    {{ sections_min[i].icon|raw }}
                                </div>
                                <div class=lss>
                                    {{ sections_min[i].name }}
                                    {% if sections_min[i].section_id == 6 %}
                                        <span class=count_r>{{ contents_count[6]+contents_count[15] }} </span>
                                    {% else %}
                                        <span class=count_r>{{ contents_count[sections_min[i].section_id] }}</span>
                                    {% endif %}
                                </div>
                            </a>
                        </li>
                    {% endfor %}
                    <li>
                        <a title=Статьи href=/articles>
                            <div class=ic>
                                <i class=min-navi-icon-articles></i>
                            </div>
                            <div class=lss>
                                Статьи <span class=count_r>{{ countArticles }}</span>
                            </div>
                        </a>
                    </li>
                </ul>
                <ul class='catig list-as-select' id=selection-sub-categs>
                </ul>
                <ul id=selection-producers-d class="catig list-as-select">
                </ul>
                <div class=panael_sub>
                    <span class=hidden_m>Закрыть&nbsp;<i class=s-icon-no></i></span>&nbsp;|&nbsp;<span
                            class=send_menu><i class=s-icon-send></i>&nbsp;Перейти</span>
                </div>
            </div>
        </div>
        <a title=Статьи id=articles-link href=/articles>Статьи</a>
        <a title=Объявления id=offers-link href=#>Объявления</a>
        {#-------------------------------------------------------------------#}
        <noindex>
            <div class=all_bottom>
                <div class=add_oby>
                    <span>+</span> ПОДАТЬ ОБЪЯВЛЕНИЕ
                </div>
                <div class=menu_top_right>
                    <div class=l_m_a>
                        <span>Выберите раздел:</span>
                    </div>
                    <ul>
                        <li>
                            <span class=plus_m><i class='products-menu-iconz'></i></span><a href=/product/add
                                                                                            target=_self
                                                                                            title="Продам новое"> Продам
                                новое</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='ads-menu-iconz'></i></span><a href=/ads/add target=_self
                                                                                       title="Продам Б/У"> Продам
                                Б/У</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='stock-menu-iconz'></i></span><a href=/products/stock
                                                                                         target=_self
                                                                                         title="Добавить Акцию">
                                Добавить Акции</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='service-menu-iconz'></i></span><a href=/service/add
                                                                                           target=_self
                                                                                           title=Сервис/Запчасти>
                                Сервис/Запчасти</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='demand-menu-iconz'></i></span></span><a href=/demand/add
                                                                                                 target=_self
                                                                                                 title=Спрос>
                                Куплю/Спрос</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='activity-menu-iconz'></i></span><a href=/activity/add
                                                                                            target=_self
                                                                                            title="Анонс мероприятий">
                                Анонсы мероприятий</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='work-menu-iconz'></i></span><a href=/work/resume/add
                                                                                        target=_self title=Резюме>
                                Резюме</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='vacancy-menu-iconz'></i></span><a href=/work/vacancy/add
                                                                                           target=_self title=Вакансии>
                                Вакансии</a>
                        </li>
                        <li>
                            <span class=plus_m><i class='labs-menu-iconz'></i></span><a href=/lab/add target=_self
                                                                                        title="З/Т лаборатории"> З/Т
                                лаборатории</a>
                        </li>
                        <li>
                            <span class='plus_m step'><i class='realty-menu-iconz'></i></span><a href=/realty/add
                                                                                                 target=_self
                                                                                                 title=Недвижимость>
                                Продам / Сдам <br/><span
                                        style='margin-left:25px'> Клинику / Лабу</span></a>
                        </li>
                        <li>
                            <span class=plus_m><i class='article-menu-iconz'></i></span><a href=/article/add
                                                                                           target=_self title> Добавить
                                статью</a>
                        </li>
                    </ul>
                    <div class=close>
                        <div class=plus><i class=a-icon-remove></i></div>
                    </div>
                </div>
            </div>
        </noindex>
        {#-------------------------------------------------------------------#}
        <ul id=auth-menu>
            {% if user_info %}
                {% if user_info.info.group_id == 4 %}
                    <li>
                        <a class="a-color-gray a-font-small" href=/admin>Панель управления</a>
                    </li>
                    <li class=a-color-gray>|</li>
                    <li>
                        <a class="a-color-gray a-font-small" href=/exit>Выход</a>
                    </li>
                {% else %}
                    <li>
                        <a class="a-color-gray a-font-small user-name-link" href=/cabinet>{{ user_info.info.name }}</a>
                    </li>
                    <li class=a-color-gray>|</li>
                    <li>
                        <a class="a-color-gray a-font-small" href=/exit>Выход</a>
                    </li>
                {% endif %}
            {% else %}
                <li>
                    <a title=Вход class="a-color-gray a-font-small ajax-link" href=/login>Вход</a>
                </li>
                <li class=a-color-gray>|</li>
                <li>
                    <a title=Регистрация class="a-color-gray a-font-small ajax-link" href=/registration>Регистрация</a>
                </li>
            {% endif %}
        </ul>
    </header>
</div>
<nav id=offers-menu class=fixed-menu>
    <div>
        <ul class=clear>
            {% for i in 0..9 %}
                <li>
                    <a title={{ sections[i].name }} target={{ sections[i].target }} href={{ sections[i].link }}>
                        {{ sections[i].icon|raw }}
                        <div>
                            {{ sections[i].name }}
                            <div class=descr>
                                {% if sections[i].section_id == 6 %}
                                    {{ contents_count[6] }} предложений
                                {% else %}
                                    {{ contents_count[sections[i].section_id] }} предложений
                                {% endif %}
                            </div>
                        </div>
                    </a>
                </li>
            {% endfor %}
        </ul>
    </div>
</nav>
<div id=parent class=clear>
    <div id=main class=clear>
        <noindex>
            {% if route.action !='add' and route.action !='resumeAdd' and  route.action !='vacancyAdd' %}
                <div style="margin:-30px 0 30px 0;text-align:center" id=reklama>
                    {% if banner_top.link or banner_top.code %}
                        {% if banner_top.code %}
                            <div style=background:#fff>
                                {{ banner_top.code|raw }}
                            </div>
                        {% else %}
                            <div href={{ banner_top.link }} target={{ banner_top.target }} class='redirect'>
                                <img src=/uploads/banners/{{ banner_top.image }} />
                            </div>
                        {% endif %}
                    {% endif %}
                </div>
            {% endif %}

            {% if route.controller=='ads' and route.action =='add' %}
                <div style="margin:-30px 0 30px 0;text-align:center" id=reklama>
                    <img src="/templates/Navistom/images/anketa/topAn.png"/>
                </div>

            {% endif %}

            {% if route.controller=='products' and route.action =='add' %}
                <div style="margin:-30px 0 30px 0;text-align:center" id=reklama>
                    <img src="/templates/Navistom/images/anketa/topnew1.png"/>
                </div>

            {% endif %}

        </noindex>
        {% if title != '' and route.controller != 'main' and route.action !='add' and route.action !='resumeAdd' and  route.action !='vacancyAdd' and route.action != 'edit'and route.action != 'vacancyEdit' and route.action != 'resumeEdit' and route.action !="quickSelection"  and route.controller !="reclama" %}
            <h1 class=title>{{ title }}</h1>
        {% endif %}
        {% if route.controller !='cabinet' and route.controller !='payment' and route.action !='add'   and route.action !='resumeAdd' and  route.action !='vacancyAdd' and  route.controller !='all' and  route.action !='login' and route.action != 'edit' and route.action != 'vacancyEdit' and route.action != 'resumeEdit' and route.action !="registration" and route.action !="usersAgreement"  and route.action !="quickSelection" %}

            <div class='breadcrumbs_sub bott_bred'>
                {% if route.controller !='main' and  route.controller !='all' %}
                    <span class='bread s'><a href=/>Главная </a></span>
                {% endif %}
                {% for bred in breadcrumb %}
                    {% if bred.url!='/main' %}
                        {% if loop.index == loop.length %}
                            ><span class='bread s{{ loop.index }}'> {{ bred.name }}</span>
                        {% else %}
                            ><span class='bread s{{ loop.index }}'> <a href={{ bred.url }}> {{ bred.name }}</a></span>
                        {% endif %}
                    {% else %}
                        <span class='bread s1'></span>
                        <span class='bread s2'></span>
                        <span class='bread s3'></span>
                    {% endif %}
                {% endfor %}
            </div>
        {% endif %}
        {% if user_info %}
            {% if user_info.info.contact_phones == '' %}
                <div class=a-mess-yellow style=margin-bottom:30px>
                    <div class=warning-icon></div>
                    <b>Внимание!</b>

                    <p>
                        Мы обнаружили, что в Вашем профиле отсутствует контактный телефон.<br>
                        Пожалуйста, просмотрите свой профиль и заполните нужные поля.
                        <a class=default-link href=/cabinet/profile/edit>Перейти к редактированию профиля</a>
                    </p>
                </div>
            {% endif %}
            {% if user_info.info.city_id == 0 %}
                <div class=a-mess-yellow style=margin-bottom:30px>
                    <div class=warning-icon></div>
                    <b>Внимание!</b>

                    <p>
                        Мы обнаружили, что в Вашем профиле отсутствует населенный пункт.<br>
                        Пожалуйста, просмотрите свой профиль и заполните нужные поля.
                        <a class=default-link href=/cabinet/profile/edit>Перейти к редактированию профиля</a>
                    </p>
                </div>
            {% endif %}
        {% endif %}
        {% block content %} {% endblock %}
    </div>
</div>
{% if is_admin %}
    <div id=admin-info-panel>
        <a href=/main/no-view-1>Новые материалы на сайте &nbsp;&nbsp; <span class=a-count-red
                                                                            style=float:right>{{ new_materials_count }}</span></a>
        <a href=/main/no-moder-1>Материалы на модерации &nbsp;&nbsp; <span class=a-count-red
                                                                           style=float:right>{{ moder_materials_count }}</span></a>
        <a href=/main/no-show-1>Скрытые объявления &nbsp;&nbsp; <span class=a-count-red
                                                                      style=float:right>{{ hide_materials_count }}</span></a>
        <a href=/ads/no-pay>Неоплаченные б\у<span class=a-count-red style=float:right>{{ nopay_by }}</span></a>

        <a href=/products/nopay-1>Неоплаченные new<span class=a-count-red style=float:right>{{ nopay_new }}</span></a>

    </div>


{% endif %}

{% if alert_pay %}
    <!-------------------------------->
    <div id="slideout">
        {% if alert==0 %}
            <a href='/ads/no-pay-user-{{ user_info.user_id }}'><img src="/templates/Navistom/images/call/alert5.png"
                                                                    alt="Отправить отзыв"/></a>
        {% else %}
            <img src="/templates/Navistom/images/call/alert.png" alt="Отправить отзыв"/>
        {% endif %}
        <div id="slideout_inner">
            <a href='/ads/no-pay-user-{{ user_info.user_id }}'>
                Ваши НЕОПЛАЧЕННЫЕ объявления в Продам Б\У не отображаются!
                Для отображения необходимо внести оплату.
            </a><br/><br/>

            VISA \ MASTER CARD, Приват24, комиссия 0%<br/>
            +38-044-573-97-73, 067-460-86-78 пн-пт с 10-00 до 17-00

        </div>
    </div>
    <!------------------------------->
{% endif %}

<footer>
    <div id=footer-content>
        <div id=bigmir>
            <ul class=menu_footer>
                <li>
                    <a href=/statistic>
                        <span class=stat class=ajax-link></span>
                        <span class=t_x> Статистика </span>
                    </a>
                </li>
                <li>
                    <a href=/feedback class=ajax-link>
                        <span class=ms></span>
                        <span class=t_x> Обратная связь</span>
                    </a>
                </li>
                <li>
                    <a href=/advertising target=_blank>
                        <span class=raceta></span>
                        <span class=t_x> Реклама на NaviStom</span>
                    </a>
                </li>
            </ul>
            <script src=//mc.yandex.ru/metrika/watch.js type=text/javascript></script>
            <script type=text/javascript>try {
                    var yaCounter130638 = new Ya.Metrika({
                        id: 130638,
                        webvisor: true,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true,
                        type: 1
                    })
                } catch (e) {
                }</script>
            <noscript>
                <div><img src="//mc.yandex.ru/watch/130638?cnt-class=1" style=position:absolute;left:-9999px alt/></div>
            </noscript>
        </div>
        <div class=icon_foot>
            <ul class=soc>
                <li>
                    <a href='https://www.facebook.com/navistom' target=_blank>
                        <div class=f_soc></div>
                    </a>
                </li>
                <li>
                    <a href='https://twitter.com/navistom' target=_blank>
                        <div class=t_soc></div>
                    </a>
                </li>
                <li>
                    <a href=http://vk.com/id95980050 target=_blank>
                        <div class=v_soc></div>
                    </a>
                </li>
                <li>
                    <a href=https://plus.google.com/u/0/107274476270243980702/posts target=_blank>
                        <div class=q_soc></div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>
<script type=text/javascript>var _gaq = _gaq || [];
    _gaq.push(["_setAccount", "UA-35822023-6"]);
    _gaq.push(["_trackPageview"]);
    (function () {
        var b = document.createElement("script");
        b.type = "text/javascript";
        b.async = true;
        b.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
        var a = document.getElementsByTagName("script")[0];
        a.parentNode.insertBefore(b, a)
    })();</script>
<script src=//code.jquery.com/jquery-1.11.3.min.js></script>
<script src=//code.jquery.com/jquery-migrate-1.2.1.min.js></script>
<script>jQuery(document).ready(function (d) {
        var b = d(window).width();
        var c = d(window).height();
        d(".menu").on("click", function (f) {
            f.stopPropagation();
            d(".menu_new, .panael_sub").toggleClass("show");
            d("#fixed").css("z-index", "9");
            if (d(".menu_new").hasClass("show")) {
            } else {
                d("body,html").css({overflow: "auto"})
            }
        });
        d(".panael_sub .hidden_m").click(function () {
            d(".menu").click()
        });
        function a() {
            d(".men1 .list-as-select").height((c / 2) + "px");
            var f = d(window).width();
            if (f > 800) {
                f = 800
            }
            d(".menu_new > div").width(f + "px")
        }

        a();
        if (window.onorientationchange) {
            window.onorientationchange = function () {
                a()
            }
        } else {
            window.onresize = function () {
                a()
            }
        }
    });</script>
<script type=text/javascript src="/templates/complete/all_new.min.js?v={{ md5time }}"></script>
{#{renderCss('all_new.min.js','script')|raw}#}
{% block assets %} {% endblock %}
{% if route.values.search or q %}
    <script type=text/javascript src=/assets/highlight/jquery.highlight.js></script>
{% endif %}
{% if route.values.search or q %}
    <script>jQuery(document).ready(function (a) {
            jQuery(".item").highlight(("{{route.values.search|default(q)}}").split(" "), {
                element: "span",
                className: "high"
            })
        });</script>
{% endif %}

{% if alert==0 %}
    <style>
        #slideout_inner {
            left: 0;

        }

        #slideout {
            left: 734px;
        }
    </style>
{% endif %}
</body>
</html>