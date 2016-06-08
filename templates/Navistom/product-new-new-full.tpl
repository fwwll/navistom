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
        <div style="width:777px;max-width:777px">
        {% if product.flag_delete > 0 %}
            <script>window.location.href = "/products/sub_categ-{{product.sub_categ_id}}-{{product.categ_name|translit}}#404";</script>
        {% endif %}
    {% else %}
        {% if product.stock_flag %}
            <form id="global-search" method="get" action="/{{ route.controller }}/filter-stocks/search">
                <input placeholder="Поиск в разделе АКЦИИ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit"></button>
            </form>
        {% else %}
            <form id="global-search" method="get" action="/{{ route.controller }}/search">
                <input placeholder="Поиск в разделе ПРОДАМ НОВОЕ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit"></button>
            </form>
        {% endif %}
        <div class="item" itemtype="http://schema.org/Product">
        {% set CUR_VAl={ 'долл':'USD', 'евро':EUR ,'грн':'UAH'} %}
        {% if product.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/product/edit-{{ product.product_new_id }}"><i
                                class="a-icon-pencil a-icon-gray"></i> Редактировать</a>
                </li>
                <li>
                    {% if product.flag == 1 %}
                        <a href="/product/flag-{{ product.product_new_id }}-0"><i
                                    class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                    {% else %}
                        <a href="/product/flag-{{ product.product_new_id }}-1"><i
                                    class="a-icon-eye-open a-icon-gray"></i> Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/product/delete-{{ product.product_new_id }}"><i
                                class="a-icon-trash a-icon-gray"></i> Удалить</a>
                </li>
                {% if is_admin == 0 %}
                    <li>
                        {% if product.stock_flag %}
                            <a class="ajax-link" href="/product/edit_stock-{{ product.product_new_id }}"><i
                                        class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                        {% else %}
                            <a class="ajax-link" href="/product/add_stock-{{ product.product_new_id }}"><i
                                        class="a-icon-star a-icon-gray"></i> Добавить акцию</a>
                        {% endif %}
                    </li>
                {% endif %}
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if product.stock_flag %}
                                        <a class="ajax-link" href="/product/edit_stock-{{ product.product_new_id }}"><i
                                                    class="a-icon-star a-icon-gray"></i> Редактировать акцию</a>
                                    {% else %}
                                        <a class="ajax-link" href="/product/add_stock-{{ product.product_new_id }}"><i
                                                    class="a-icon-star a-icon-gray"></i> Добавить акцию</a>
                                    {% endif %}
                                </li>
                                <li>
                                    {% if product.flag_moder > 0 %}
                                        <a href="/product/flag_moder-{{ product.product_new_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/product/flag_moder-{{ product.product_new_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/product/send-message-{{ product.product_new_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/product/transfer-to-ads-{{ product.product_new_id }}"><i
                                                class="a-icon-share-alt a-icon-gray"></i> Перенести в Б/У</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-products_new-product_new_id-{{ product.product_new_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>
                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-3-{{ product.product_new_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if (product.pay and product.time_show > 0)  or product.group_id == 10 %}
                        {% if product.flag_moder == 0 %}
                            <span class="yellow">На модерации</span>
                        {% elseif product.flag == 0 %}
                            <span class="gray">Скрыто</span>
                        {% else %}


                            <span class="green {% if product.time_show <9 and  product.group_id!=10 %}red2 {% endif %}">
					{% if(product.group_id !=10) %}
                        Осталось {{ product.time_show }} дней публикации
                        {% if product.time_show <9 %}
                            | <a href='/success-{{ product.product_new_id }}-3-extend' class='success_link'>продлить</a>
                        {% endif %}
                    {% else %}
                        Опубликовано
                    {% endif %}
				 </span>
                        {% endif %}
                    {% else %}
                        <div class='nopay'><span class='red2'> не оплачено | <a
                                        href='/success-{{ product.product_new_id }}-3-top'>оплатить</a></span></div>
                    {% endif %}

                    <!--span class="green">Опубликовано</span-->
                </li>
                {% if product.user_id == user_info.info.user_id and "now"|datediff(product.date_add) > 13 and  product.group_id ==10 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-products_new-product_new_id-{{ product.product_new_id }}"><i
                                        class="a-icon-arrow-up a-icon-white"></i> Поднять вверх</a>
                            <!--div>
                            Вы можете поднимать свое объявление раз в 2 недели, <br />
                            тем самым Вы подтверждаете его актуальность
                            </div-->
                        </div>
                    </li>
                {% endif %}
            </ul>
        {% endif %}
    {% endif %}
    <div class='full_padding'>
        <div class="a-row a-offset-0">
            <div class="a-cols-2 a-font-small a-color-gray">
                {% if product.stock_flag %} {{ product.stock_date|timeago }} {% else %} {{ product.date_add|timeago }} {% endif %}
                &nbsp; | &nbsp;
                {{ product.views }} <i class="a-icon-eye-open a-icon-gray"></i>&nbsp; | &nbsp;<span
                        class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>
        </div>
        <h1 class="full-title">
            {% if product.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
            {% if product.stock_flag %} <span class="navi-stock-marker">Акция!</span> {% endif %}
            <span itemprop="name">{{ product.product_name }}</span>
        </h1>

        <div class="a-font-small a-color-gray" itemprop="description">{{ product.description|raw }}</div>
    </div>
    <div class="n-ad-full a-clear">
    <div class="col-1">
        <div class='img_top full_padding'>
            {% if product.image and product.image != 'none.jpg' %}
                <img title="{{ product.product_name }}" alt="{{ product.product_name }}" class='{{ product.class }}'
                     src="{{ product.big_img }}{{ product.image }}" itemprop="image"/>
            {% else %}
                <img title="{{ product.product_name }}" alt="{{ product.product_name }}"
                     src="/uploads/images/160x200.jpg"/>
            {% endif %}
        </div>
        {% if product.stock_content %}
            <div class="stock-content">
                {% if product.stock_flag %}
                    <span class="price-old">
{{ price|number_format(2, '.', ' ') }} {{ currency }}
</span><br/>
                    <span class="stock-price-full">
{{ stock_price|number_format(2, '.', ' ') }} {{ currency }}
</span>
                    <div class='row_evro'>
                        {% for p in prices %}
                            <div class='evro2'>{{ p.val|number_format(2, '.', ' ') }} {{ p.name }}</div>
                        {% endfor %}
                    </div>
                {% endif %}

                {% if product.price_description %}
                    <div class='row_evro'>{{ product.price_description }}</div>
                {% endif %}

                <div class='stock_content'>
                    {{ product.stock_content|raw|nl2br }}
                </div>
                <div class='timers' stop='{{ product.action_end }}' stamp='{{ product.end_stamp }}'>
                    <div class='h2'>До конца акции осталось</div>
                    {#{product.date_end|rusDate}#}
                    <div class='times'>
                        <div class='clock'>
                            <span id='d'>00</span>
                            <span class='c'></span>

                            <div class='cl_k'>Дней</div>
                        </div>
                        <div class='clock'>
                            <span id='h'>00</span>
                            <span class='c'></span>

                            <div class='cl_k'>Часов</div>
                        </div>
                        <div class='clock'>
                            <span id='i'>00</span>
                            <span class='c'></span>

                            <div class='cl_k'>Минут</div>
                        </div>
                        <div class='clock'>
                            <span id='s'>00</span>

                            <div class='cl_k'>Секунд</div>
                        </div>
                    </div>
                </div>
            </div>
        {% endif %}
        <div class='full_padding'>

            {% if (product.pay and product.time_show ) or product.group_id == 10 %}
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
<meta itemprop="price" content="{{ price|number_format(2, '.', ' ')|replace({" ":""}) }}">
 <meta itemprop="priceCurrency" content="{{ CUR_VAl[currency] }}">
 <meta itemprop="itemCondition" itemtype="http://schema.org/OfferItemCondition"
       content="http://schema.org/NewCondition"/>
 <meta itemprop="availability" content="http://schema.org/InStock"/>

<ul id="ad-info-list">
    <li>
        <div>
            {% if product.stock_flag %}
            {% else %}
                <span class="price-full">

   
{{ price|number_format(2, '.', ' ') }} {{ currency }}

</span>
            {% endif %}
            {% if product.stock_content %}
            {% else %}
                <div class='cenik'>
                    {% for p in prices %}
                        <div class='evro'>{{ p.val|number_format(2, '.', ' ') }} {{ p.name }}</div>
                    {% endfor %}
                </div>
            {% endif %}
            {% if product.price_description and product.stock_flag %}
            {% endif %}
        </div>
    </li>
    <li>
        <a href="/products/user-{{ product.user_id }}-{{ product.user_name|translit }}" class='color727272'>
            <i class="user-new-iconz"></i> <span itemprop="seller">{{ product.user_name }}</span>
        </a>
    </li>
    {% for key, value in product.phones %}
        {% if value != '' %}
            <li>
                <div>
                    <i class="tel-new-iconz"></i>&nbsp; <span class='color727272'>{{ value }}</span>
                </div>
            </li>
        {% endif %}
    {% endfor %}
    <li>
        <i class="globe-new-iconz"></i>
        <span class='color727272'>г.{{ product.city }}</span>
    </li>
    {% if product.site %}
        <li>
            <i class="link-new-iconz"></i>&nbsp;
            <a target="_blank" href="{{ product.site }}" class='color727272'>Веб сайт</a>
        </li>
    {% endif %}
    {% if product.icq %}
        <li>
            <span class="a-color-gray">ICQ:</span><span class='color727272'> {{ product.icq }} </span>
        </li>
    {% endif %}
    {% if product.skype %}
        <li>
            <i class="skype-new-iconz"></i><span class='color727272'> {{ product.skype }}</span>
        </li>
    {% endif %}
</ul>
</span>

            {% else %}
                {% if product.user_id==user_info.info.user_id %}
                    <div class='informer'>
                        Это объявление видите только Вы, как автор.
                        Посетители увидят его после <a onclick="raceta()">оплаты продвижения</a>
                        <span>Минимальная оплата 14 грн, максимальная - на ваше усмотрение.</span>
                    </div>
                {% else %}
                    <div class='links'>
                        <div class='title'>Объявление не активно — найдите похожие объявления в разделах:</div>
                        <ul>
                            {% for item,result in links %}
                                <li><a href="{{ result.url }}">{{ result.name }}</a></li>
                            {% endfor %}
                        </ul>
                    </div>
                {% endif %}

            {% endif %}


        </div>
    </div>
    <div class="col-2">
        <div class='full_padding'>
            {% if product.content %}
                {{ product.content|raw|nl2br }}
            {% else %}
                {{ product.description }}
            {% endif %}
        </div>
    </div>
    <div class="clear"></div>
    <div class='full_padding'>
        {% if gallery or product.video_link %}
        <div class="idTabs">
            {% endif %}
            {% if gallery %}
                <div id="" class="ad-gallery">
                    <div class="ad-nav">
                        <div class="ad-thumbs">
                            {% for g in gallery %}
                                <div class='foto'>
                                    <img title="{{ g.description }}" alt="{{ g.description }}"
                                         src="/uploads/images/products/full/{{ g.url_full }}"/>
                                </div>
                            {% endfor %}
                        </div>
                    </div>
                </div>
                <p>&nbsp;</p>
            {% endif %}
            {% if product.video_link %}
                <div id="">
                    <iframe width="100%" height="394" src="//www.youtube.com/embed/{{ product.video_link }}"
                            frameborder="0" allowfullscreen></iframe>
                </div>
            {% endif %}
            {% if gallery or product.video_link %}
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
                                                                                         id="send-error-mess-link"
                                                                                         href="#" class='color727272'>
                            <i class="alert-new-iconz"></i> Пожаловаться
                        </a></div>
                </noindex>

            </div>
            {% if user_info %}
            <div class="a-clear">
                <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                      action="/index.ajax.php?route=/product/send-message-{{ product.product_new_id }}">
                    <div class='zayvka'>Написать автору объявления</div>
                    <textarea autofocus="autofocus" class="autosize"
                              placeholder="Уточняйте характеристики | Наличие | Предлагайте свою цену"
                              name="message"></textarea>

                    <div class="a-row a-row-full">
                        <label>Ваш e-mail</label>
                        <input type="text" name="user_email" value="{{ user_info.info.email }}"/>
                    </div>
                    <div class="a-row a-row-full">
                        <label>Ваши телефоны</label>
                        <input type="text" name="user_phones" value="{{ user_info.info.contact_phones }}"
                               class="phones-input"/>
                    </div>
                    <input type="hidden" name="user_id" value="{{ product.user_id }}"/>

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
                    Чтобы написать автору, нужно <a title="Вход" href="/login">войти </a>или <a title="Регистрация"
                                                                                                href="/registration">зарегистрироваться </a><br/>
                </center>
                {% endif %}
            </div>
        </div>
        {% if vip %}
            <div class="vip-ads-full">
                {% for v in vip %}
                    <div class="item pagination-block light {% if v.color_yellow >1 %} color_yellow {% endif %}">
                        {% if v.show_top > 0 %}
                            <span class="l_top kon"> <span>топ</span></span>
                        {% endif %}
                        <div class='offer clear concurent vp_kon'>
                            <div style="" class="a-align-right a-float-right">
                                <span class='data_vip'>{{ v.date_add|timeago }}</span>
                            </div>
                            <a href="/product/{{ v.product_new_id }}-{{ v.product_name|translit }}">
                                {% if product.image %}
                                    {% if isBigImg(v.image) %}
                                        <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                             src="/uploads/images/products/full/{{ v.image }}"/>
                                    {% else %}
                                        <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                             src="/uploads/images/products/80x100/{{ v.image }}"/>
                                    {% endif %}
                                {% else %}
                                    <div class='none_img'>
                                        <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                             src="/uploads/images/80x100.jpg"/>
                                    </div>
                                {% endif %}
                            </a>

                            <div class='filter_l'>
                                {{ v.categ_name }}
                            </div>
                            {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                            <div class='ic vp_kon'>
                                <a target="_blank" href="/product/{{ v.product_new_id }}-{{ v.product_name|translit }}"
                                   class="modal-window-link"></a>
                            </div>
                            <div class="name_ta vp_kon">
                                {% if v.stock_flag %}
                                    <span style="color:#C2185B"> Акция!</span>
                                {% endif %}
                                <a href="/product/{{ v.product_new_id }}-{{ v.product_name|translit }}">
                                    {{ v.product_name }}
                                </a>
                            </div>
                            <div class="name_ta">
                                <div class="a-font-small a-color-gray">{{ v.description }}</div>
                            </div>
                            <div class="a-row a-offset-0 offer-footer vp_kon">
                                <div class="a-cols-2 vp_kon">
                                    <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                                </div>
                                <div class="a-cols-2 a-align-right">
                                    <div class="price {% if v.stock_flag %}price-stock{% endif %}">
                                        {% if v.stock_flag %}
                                            {{ v.stock_price|getExchangeRates(v.stock_currency_id, v.user_id)|number_format(2, '.', ' ') }} {{ currency }}
                                        {% else %}
                                            {{ v.price|getExchangeRates(v.currency_id, v.user_id)|number_format(2, '.', ' ') }} {{ currency }}
                                        {% endif %}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {% endfor %}
            </div>
        {% endif %}
        {% if banner_footer_content.link or banner_footer_content.code %}
            <noindex>
                {% if banner_footer_content.code %}
                    <div style="margin:30px 0 0 -15px;text-align:center">
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
        {% if product.stock_content %}
        {% if ajax %}
        {% else %}
            <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        {% endif %}
            <script>/*<![CDATA[*/
                jQuery(document).ready(function (c) {
                    clearInterval(document.fix);
                    var a = c(".timers").attr("stop");

                    function b(l) {
                        var f = new Date();
                        Year = new Date(a);
                        var d = (Year.getTime() - f.getTime());
                        if (d > 1) {
                            var k = (parseInt(d / 1000));
                            var i = (parseInt(k / (24 * 60 * 60)));
                            var g = k - i * 24 * 3600;
                            var e = (parseInt(g / 3600));
                            if (e < 10) {
                                e = "0" + e
                            }
                            var m = g - e * 3600;
                            var n = (parseInt(m / 60));
                            if (n < 10) {
                                n = "0" + n
                            }
                            var h = m - n * 60;
                            if (h < 10) {
                                h = "0" + h
                            }
                            var j = "" + i + ":" + e + ":" + n + ":" + h;
                            if (i <= 9) {
                                i = "0" + i
                            }
                            c("#d").text(i);
                            c("#h").text(e);
                            c("#i").text(n);
                            c("#s").text(h)
                        } else {
                            c("#d").text("00");
                            c("#h").text("00");
                            c("#i").text("00");
                            c("#s").text("00")
                        }
                    }

                    document.fix = setInterval(function () {
                        b(stop)
                    }, 1000)
                });
                /*]]>*/</script>
        {% endif %}
    </div>
    </div>
{% endblock %}