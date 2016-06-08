<!DOCTYPE HTML>
<html>
<head>
    {% if json_breadcrumb %}
        <script type="application/ld+json">

	{% autoescape false %}
            {{ json_breadcrumb }}
            {% endautoescape %}


        </script>
    {% endif %}
    <title>{{ meta_title }}</title>

    {% for name, content in meta_tags %}
        {% if content != '-' %}
            <meta name="{{ name }}" content="{{ content }}"/>
        {% endif %}
    {% endfor %}

    {% for property, content in social_meta_tags %}
        <meta property="{{ property }}" content="{{ content }}"/>
    {% endfor %}

    {% if error %}
        <meta name="robots" content="noindex, follow"/>
    {% else %}

    {% endif %}

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta http-equiv="content-language" content="ru"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-35822023-6']);
        _gaq.push(['_trackPageview']);

        (function () {


            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);

        })();
    </script>


    {% if route.values.search or q %}
        <script>
            jQuery(document).ready(function (e) {
                jQuery(".item").highlight(("{{route.values.search|default(q)}}").split(' '), {
                    element: 'span',
                    className: 'high'
                });
            });
        </script>
    {% endif %}

    <link rel="icon" href="/{{ tpl_dir }}/images/navi-favicon.png" type="image/x-icon">

    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700|PT+Sans+Narrow|Roboto:100,300&subset=latin,cyrillic'
          rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/templates/complete/all_new.min.css?v={{ md5css }}"/>

    {% if is_admin == 2492 %}
        <script type="text/javascript" src="/{{ tpl_dir }}/debug/assets/codemirror/codemirror.js"></script>
        <script type="text/javascript" src="/{{ tpl_dir }}/debug/assets/codemirror/mode/sql/sql.js"></script>

        <link rel="stylesheet" href="/{{ tpl_dir }}/debug/assets/codemirror/codemirror.css"/>
        <link rel="stylesheet" href="/{{ tpl_dir }}/debug/assets/codemirror/ambiance.css?t={{ md5time }}"/>

        <link rel="stylesheet" href="/{{ tpl_dir }}/styles/debug.css?t={{ md5time }}"/>
    {% endif %}

    <style>
        .subscribe-icon {
            display: inline-block;
            width: 28px;
            height: 28px;
            vertical-align: middle;
            background-image: url("/templates/Navistom/images/subscribe-icon.png");
            background-position: center center;
            background-repeat: no-repeat;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            -o-border-radius: 50%;
            -ms-border-radius: 50%;
            border-radius: 50%;
        }
    </style>
</head>
<body>

{% if banner_bg.link %}
    <a href="{{ banner_bg.link }}" target="{{ banner_bg.target }}"
       style="background-image:URL('/uploads/banners/{{ banner_bg.image }}'); background-color: #000"
       id="banner-bg"></a>
{% endif %}

<noindex>
    <div id="fixed-right-btns">
        <a title="Обратная связь" href="/feedback" class="social-link feedback ajax-link"></a>

        <a title="Сообщить об ошибке" id="send-error-mess-link" href="#">
            <i class="a-icon-bug a-icon-white"></i>
        </a>

        <a title="Статистика портала" id="global-statistic-link" class="ajax-link" href="/statistic">
            <i class="a-icon-signal a-icon-white"></i>
        </a>
    </div>
</noindex>

<div id="fixed">
<header>
        <span id="logo">
		{% if url !='/' %}

            <a href='/'><img title="NaviStom" alt="NaviStom" src="/{{ tpl_dir }}/images/logo.png" alt="logotype"
                             style='cursor:pointer'></a>
		{% else %}
            <img title="NaviStom" alt="NaviStom" src="/{{ tpl_dir }}/images/logo.png" alt="logotype">
        {% endif %}
        </span>

        <!-------------------------------------------menu-------------------------------------------------->
<span class="menu">
			<img src="/{{ tpl_dir }}/images/menu.png"/>
		</span>

        <div class='menu_new'>
            <div class='men1'>
                <!----------------------------------------------------------->
                <div class='breadcrumbs_sub'>

                    {% if route.controller !='main' %}
                        <span class='bread s'><a href='/'>Главная </a></span>
                    {% endif %}
                    {% for bred in breadcrumb %}

                        {% if bred.url!='/main' %}
                            {% if loop.index == loop.length %}
                                ><span class='bread s{{ loop.index }}'> {{ bred.name }}</span>
                            {% else %}
                                ><span class='bread s{{ loop.index }}'> <a
                                        href="{{ bred.url }}"> {{ bred.name }}</a></span>
                            {% endif %}
                        {% else %}
                            <span class='bread s1'></span>
                            <span class='bread s2'></span>
                            <span class='bread s3'></span>
                        {% endif %}
                    {% endfor %}
                </div>
                <!----------------------------------------------------------->
                <ul class='catig list-as-select' id="selection-categs">

                    {% for i in 0..9 %}
                        <li>
                            <a title="{{ sections_min[i].name }}" target="{{ sections_min[i].target }}"
                               href="{{ sections_min[i].link }}">
                                <div class='ic'>
                                    {{ sections_min[i].icon|raw }}
                                </div>
                                <div class='lss'>
                                    {{ sections_min[i].name }}
                                    {% if sections_min[i].section_id == 6 %}
                                        <span class='count_r'>{{ contents_count[6]+contents_count[15] }} </span>
                                    {% else %}
                                        <span class='count_r'>{{ contents_count[sections_min[i].section_id] }}</span>
                                    {% endif %}
                                </div>
                            </a>
                        </li>
                    {% endfor %}
                    <li>
                        <a title="Статьи" href="/articles">
                            <div class='ic'>
                                <i class="min-navi-icon-articles"></i>
                            </div>
                            <div class='lss'>
                                Статьи <span class='count_r'>{{ countArticles }}</span>
                            </div>
                        </a>
                    </li>
                </ul>

                <ul class='catig list-as-select' id="selection-sub-categs">

                </ul>
                <ul id="selection-producers-d" class=" catig list-as-select">

                </ul>
                <div class='panael_sub'>
                    Свернуть
                </div>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                //$('body,html').css({'overflow':'hidden'});
                var wi = $(window).width()
                var heig = $(window).height();
                $('.menu').on('click', function (e) {
                    e.stopPropagation();
                    $('.menu_new, .panael_sub').toggleClass("show")
                    $('#fixed').css('z-index', '9');


                    if ($('.menu_new').hasClass("show")) {
                        $('body,html').css({'overflow': 'hidden'});
                    } else {
                        $('body,html').css({'overflow': 'auto'});
                    }


                });


                $('.panael_sub').click(function () {
                    $('.menu').click();
                })

                function menu_height() {
                    $('.men1 .list-as-select').height((heig / 2) + 'px');
                    var wi = $(window).width()


                    if (wi > 800) {
                        wi = 800;
                    }
                    $('.menu_new > div').width(wi + 'px');


                }

                menu_height()


                if (window.onorientationchange) {
                    window.onorientationchange = function () {
                        menu_height();
                    }
                }
                else {

                    window.onresize = function () {
                        menu_height();
                    }
                }
            })

        </script>
        <!-----------------------------------------menu end---------------------------------------------------->

        <a title="Статьи" id="articles-link" href="/articles">Статьи</a>
        <a title="Объявления" id="offers-link" href="#">Объявления</a>
        {#-------------------------------------------------------------------#}
        <noindex>
            <div class=' all_bottom'>
                <div class='add_oby'>
                    <span>+</span> ПОДАТЬ ОБЪЯВЛЕНИЕ
                </div>
                <div class='menu_top_right'>
                    <div class='l_m_a'>
                        <span>Выберите раздел:</span>
                    </div>
                    <ul>

                        <li>
                            &gt;<a href="/product/add" target="_self" class='ajax-link' title="Продам новое"> Продам
                                новое</a>
                        </li>
                        <li>
                            &gt;<a href="/ads/add" target="_self" class='ajax-link' title="Продам Б/У"> Продам Б/У</a>
                        </li>
                        <li>
                            &gt;<a href="/service/add" target="_self" class='ajax-link' title="Сервис/Запчасти">
                                Сервис/Запчасти</a>
                        </li>
                        <li>
                            &gt;<a href="/demand/add" target="_self" class='ajax-link' title="Спрос"> Спрос</a>
                        </li>
                        <li>
                            &gt;<a href="/activity/add" target="_self" class='ajax-link' title="Мероприятия">
                                Мероприятия</a>
                        </li>
                        <li>
                            &gt;<a href="/lab/add" target="_self" class='ajax-link' title="З/Т лаборатории"> З/Т
                                лаборатории</a>
                        </li>
                        <li>
                            &gt;<a href="/realty/add" target="_self" class='ajax-link' title="Недвижимость">
                                Недвижимость</a>
                        </li>
                        <!--li>
                                           &gt;<a href="/diagnostic/add" target="_self" class='ajax-link' title="Диагностика"> Диагностика</a>
                                        </li-->
                        <li>
                            &gt;<a href="/work/resume/add" target="_self" class='ajax-link' title="Резюме"> Резюме</a>
                        </li>
                        <li>
                            &gt;<a href="/work/vacancy/add" target="_self" class='ajax-link' title="Вакансии">
                                Вакансии</a>
                        </li>
                    </ul>
                    <div class='close'>
                        <div class='plus '><i class="a-icon-remove"></i></div>

                    </div>
                </div>
            </div>

        </noindex>
        {#-------------------------------------------------------------------#}
        <ul id="auth-menu">
            {% if user_info %}
                {% if user_info.info.group_id == 4 %}
                    <li>
                        <a class="a-color-gray a-font-small" href="/admin">Панель управления</a>
                    </li>
                    <li class="a-color-gray">|</li>
                    <li>
                        <a class="a-color-gray a-font-small" href="/exit">Выход</a>
                    </li>
                {% else %}
                    <li>
                        <a class="a-color-gray a-font-small user-name-link"
                           href="/cabinet">{{ user_info.info.name }}</a>
                    </li>
                    <li class="a-color-gray">|</li>
                    <li>
                        <a class="a-color-gray a-font-small" href="/exit">Выход</a>
                    </li>

                {% endif %}
            {% else %}
                <li>
                    <a title="Вход" class="a-color-gray a-font-small ajax-link" href="/login">Вход</a>
                </li>
                <li class="a-color-gray">|</li>
                <li>
                    <a title="Регистрация" class="a-color-gray a-font-small ajax-link"
                       href="/registration">Регистрация</a>
                </li>
            {% endif %}
        </ul>


</header>
</div>


<nav id="offers-menu" class="fixed-menu">
    <div>
        <ul class="clear">
            {% for i in 0..9 %}
                <li>
                    <a title="{{ sections[i].name }}" target="{{ sections[i].target }}" href="{{ sections[i].link }}">
                        {{ sections[i].icon|raw }}
                        <div>
                            {{ sections[i].name }}
                            <div class="descr">
                                {% if sections[i].section_id == 6 %}
                                    {{ contents_count[6]+contents_count[15] }} предложений
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


<div id="parent" class="clear">
    <div id="main" class="clear">

        <div style="margin:-30px 0 30px 0; text-align:center" id='reklama'>
            {% if banner_top.link or banner_top.code %}
                {% if banner_top.code %}
                    <div style="background:#fff;">
                        {{ banner_top.code|raw }}
                    </div>
                {% else %}
                    <a href="{{ banner_top.link }}" target="{{ banner_top.target }}">
                        <img src="/uploads/banners/{{ banner_top.image }}"/>
                    </a>
                {% endif %}
            {% endif %}
        </div>
        <!----------------------------------------------------------->

        <div class='breadcrumbs_sub bott_bred'>
            {% if route.controller !='main' %}
                <span class='bread s'><a href='/'>Главная </a></span>
            {% endif %}
            {% for bred in breadcrumb %}

                {% if bred.url!='/main' %}
                    {% if loop.index == loop.length %}
                        ><span class='bread s{{ loop.index }}'> {{ bred.name }}</span>
                    {% else %}
                        ><span class='bread s{{ loop.index }}'> <a href="{{ bred.url }}"> {{ bred.name }}</a></span>
                    {% endif %}
                {% else %}
                    <span class='bread s1'></span>
                    <span class='bread s2'></span>
                    <span class='bread s3'></span>
                {% endif %}
            {% endfor %}
        </div>
        <!----------------------------------------------------------->

        {% if user_info %}
            {% if user_info.info.contact_phones == '' %}
                <div class="a-mess-yellow" style="margin-bottom:30px">
                    <div class="warning-icon"></div>
                    <b>Внимание!</b>

                    <p>
                        Мы обнаружили, что в Вашем профиле отсутствует контактный телефон.<br>
                        Пожалуйста, просмотрите свой профиль и заполните нужные поля.
                        <a class="default-link" href="/cabinet/profile/edit">Перейти к редактированию профиля</a>
                    </p>
                </div>
            {% endif %}

            {% if user_info.info.city_id == 0 %}
                <div class="a-mess-yellow" style="margin-bottom:30px">
                    <div class="warning-icon"></div>
                    <b>Внимание!</b>

                    <p>
                        Мы обнаружили, что в Вашем профиле отсутствует населенный пункт.<br>
                        Пожалуйста, просмотрите свой профиль и заполните нужные поля.
                        <a class="default-link" href="/cabinet/profile/edit">Перейти к редактированию профиля</a>
                    </p>
                </div>
            {% endif %}
        {% endif %}
        {% if title != '' and route.controller != 'main' %}
            <h1 class="title">{{ title }}</h1>
        {% endif %}

        {% block content %} {% endblock %}
    </div>
</div>

{% if is_admin %}
    <div id="admin-info-panel">
        <a href="/main/no-view-1">Новые материалы на сайте &nbsp;&nbsp; <span class="a-count-red"
                                                                              style="float: right">{{ new_materials_count }}</span></a>
        <a href="/main/no-moder-1">Материалы на модерации &nbsp;&nbsp; <span class="a-count-red"
                                                                             style="float: right">{{ moder_materials_count }}</span></a>
        <!--a href="/main/vip-items-1">Заявки на VIP размещение &nbsp;&nbsp; <span class="a-count-red" style="float: right">{{ vip_materials_count }}</span></a-->
        <a href="/main/no-show-1">Скрытые объявления &nbsp;&nbsp; <span class="a-count-red"
                                                                        style="float: right">{{ hide_materials_count }}</span></a>
        <a href="/main/no-show-1">Окончании периода ТОП <span class="a-count-red"
                                                              style="float: right">{{ hide_materials_count }}</span></a>

        <div class="admin-select-country">
            <p>Показывать баннера для:</p>
            <a {% if adv_country < 2 %} class="current" {% endif %} href="/adv-country-1">Украины</a>
            <a {% if adv_country > 1 %} class="current" {% endif %} href="/adv-country-2">Других стран</a>
        </div>
    </div>
{% endif %}

<footer>
    <div id="footer-content">
        <div id="bigmir">
            <ul class='menu_footer'>
                <li>
                    <a href='/statistic'>
                        <span class='stat' class='ajax-link'></span>
                        <span class='t_x'> Статистика </span>
                    </a>
                </li>
                <li>
                    <a href='/feedback' class='ajax-link'>
                        <span class='ms'></span>
                        <span class='t_x'> Обратная связь</span>
                    </a>
                </li>
                <li>
                    <a href='/advertising' target='_blank'>
                        <span class='raceta'></span>
                        <span class='t_x'> Реклама на NaviStom</span>
                    </a>
                </li>
            </ul>


            <!-- Yandex.Metrika counter -->
            <script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
            <script type="text/javascript">
                try {
                    var yaCounter130638 = new Ya.Metrika({
                        id: 130638,
                        webvisor: true,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true, type: 1
                    });
                } catch (e) {
                }
            </script>
            <noscript>
                <div><img src="//mc.yandex.ru/watch/130638?cnt-class=1" style="position:absolute; left:-9999px;"
                          alt=""/></div>
            </noscript>
            <!-- /Yandex.Metrika counter -->
        </div>

        <div class='icon_foot'>

            <ul class='soc'>
                <li>
                    <a href='https://www.facebook.com/navistom'>
                        <div class='f_soc'></div>
                    </a>
                </li>
                <li>
                    <a href='https://twitter.com/navistom'>
                        <div class='t_soc'></div>
                    </a>
                </li>
                <li>
                    <a href='http://vk.com/id95980050'>
                        <div class='v_soc'></div>
                    </a>
                </li>
                <li>
                    <a href='https://plus.google.com/u/0/107274476270243980702/posts'>
                        <div class='q_soc'></div>
                    </a>
                </li>

            </ul>
        </div>


    </div>
</footer>

<script type="text/javascript" src="/templates/complete/all_new.min.js?v={{ md5js }}"></script>

{% block assets %} {% endblock %}

{% if route.values.search or q %}
    <script type="text/javascript" src="/assets/highlight/jquery.highlight.js"></script>
{% endif %}

<!-- BEGIN JIVOSITE CODE {literal} -->
<!--script type='text/javascript'>
    (function(){ var widget_id = 'JyoSGWG0jo';
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script-->
<!-- {/literal} END JIVOSITE CODE -->

</body>
</html>