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
        {% if ads.flag_delete > 0 %}
            <script>window.location.href = "/ads/sub_categ-{{ads.sub_categ_id}}-{{ads.categ_name|translit}}#404";</script>
        {% endif %}
    {% else %}
        <form id="global-search" method="get" action="/{{ route.controller }}/search">
            <input placeholder="Поиск в разделе ПРОДАМ Б/У" type="text" value="{{ route.values.search }}" name="q"
                   id="global-search-input"/>
            <button id="search-submit" type="submit"></button>
        </form>
        <div class="item">
        {% if ads.user_id == user_info.info.user_id or is_admin %}
            <ul class="options full clear">
                <li>
                    <a class="ajax-link" href="/ads/edit-{{ ads.ads_id }}"><i class="a-icon-pencil a-icon-gray"></i>
                        Редактировать</a>
                </li>
                <li>
                    {% if ads.flag == 1 %}
                        <a href="/ads/flag-{{ ads.ads_id }}-0"><i class="a-icon-eye-close a-icon-gray"></i> Скрыть</a>
                    {% else %}
                        <a href="/ads/flag-{{ ads.ads_id }}-1"><i class="a-icon-eye-open a-icon-gray"></i>
                            Отобразить</a>
                    {% endif %}
                </li>
                <li>
                    <a class="delete-link" href="/ads/delete-{{ ads.ads_id }}"><i class="a-icon-trash a-icon-gray"></i>
                        Удалить</a>
                </li>
                {% if is_admin %}
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="a-icon-cog a-icon-gray"></i> Дополнительно</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li>
                                    {% if ads.flag_moder > 0 %}
                                        <a href="/ads/flag_moder-{{ ads.ads_id }}-0"><i
                                                    class="a-icon-remove a-icon-gray"></i> Запретить</a>
                                    {% else %}
                                        <a href="/ads/flag_moder-{{ ads.ads_id }}-1"><i
                                                    class="a-icon-ok a-icon-gray"></i> Одобрить</a>
                                    {% endif %}
                                </li>
                                <li>
                                    <a class="ajax-link" href="/ads/send-message-{{ ads.ads_id }}"><i
                                                class="a-icon-envelope a-icon-gray"></i> Написать письмо</a>
                                </li>
                                <li>
                                    <a href="/ads/transfer-to-products-{{ ads.ads_id }}"><i
                                                class="a-icon-share-alt a-icon-gray"></i> Перенести в новое</a>
                                </li>
                                <li>
                                    <a href="/update-date-add-ads-ads_id-{{ ads.ads_id }}"><i
                                                class="a-icon-refresh a-icon-gray"></i> Обновить дату</a>
                                </li>
                                <li>
                                    <a class="ajax-link" href="/add-to-top-main-4-{{ ads.ads_id }}"><i
                                                class="a-icon-thumbs-up a-icon-gray"></i> ТОП настройки</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                {% endif %}
                <li class="satus">
                    {% if ads.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif ads.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}

                        {% if (ads.pay and ads.time_show > 0)  or ads.group_id == 10 %}
                            <span class="green {% if ads.time_show <9 and  ads.group_id!=10 %}red2 {% endif %}">
	{% if(ads.group_id !=10) %}
        Осталось {{ ads.time_show }} дней публикации
        {% if ads.time_show <9 %}
            | <a href='/success-{{ ads.ads_id }}-4-extend' class='success_link'>продлить</a>
        {% endif %}
    {% else %}
        Опубликовано
    {% endif %}
 </span>
                        {% else %}
                            <div class='nopay'><span class='red2'> не оплачено | <a
                                            href='/success-{{ ads.ads_id }}-4-top'>оплатить</a></span></div>
                        {% endif %}

                    {% endif %}
                </li>

                {#% if ( user_info.info.group_id == 10  and ads.user_id == user_info.info.user_id and "now"|datediff(ads.date_add) > 13 ) or(  ads.user_id == user_info.info.user_id and "now"|datediff(ads.date_add) > 29 ) %#}

                {% if( user_info.info.group_id == 10  and ads.user_id == user_info.info.user_id and "now"|datediff(ads.date_add) > 13 ) %}

                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-ads-ads_id-{{ ads.ads_id }}"><i
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
        <div class="a-cols-2 a-font-small a-color-gray">
            <noindex>
                {{ ads.date_add|timeago }} &nbsp; | &nbsp;
                {{ ads.views }} <i class="a-icon-eye-open a-icon-gray"></i>
                &nbsp; | &nbsp;<span class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </noindex>
        </div>
    </div>
    <!----------------->
    <div itemscope itemtype="http://schema.org/Product">
        <!----------------->
        <h1 class="full-title">

            {% if ads.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
            <span itemprop="name">{{ ads.product_name }}</span>, Б/У
        </h1>
        <meta itemprop="sku" content='{{ ads.ads_id }}'/>
        <div class="a-font-small a-color-gray" itemprop="description">{{ ads.description }}</div>
        <div class="n-ad-full a-clear">
            <div class="col-1">


                <div class='img_top'>
                    {% if ads.url_full %}
                        <img title="{{ ads.product_name }}, Б/У" class='{{ ads.class }}'
                             src="{{ ads.big_img }}{{ ads.url_full }}" itemprop="image"/>
                    {% elseif ads.image and ads.image != 'none.jpg' %}
                        <img title="{{ ads.product_name }}, Б/У" src="/uploads/images/products/80x100/{{ ads.image }}"
                             itemprop="image"/>
                    {% else %}
                        <div class='none_img'><img src="/uploads/images/160x200.jpg"/></div>
                    {% endif %}
                </div>
                {% if (ads.pay and ads.time_show ) or ads.group_id == 10 %}

                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        {% set CUR_VAl={ 'долл':'USD', 'евро':EUR ,'грн':'UAH'} %}
                        <ul id="ad-info-list">
                            <li>
<span class="price-full">
{{ price|number_format(2, '.', ' ') }} {{ currency }}

    <meta itemprop="price" content="{{ price|number_format(2, '.', ' ')|replace({" ":""}) }}">
 <meta itemprop="priceCurrency" content="{{ CUR_VAl[currency] }}">
 <meta itemprop="itemCondition" itemtype="http://schema.org/OfferItemCondition"
       content="http://schema.org/UsedCondition"/>
 <meta itemprop="availability" content="http://schema.org/InStock"/>


</span>

                                <div class="cenik">
                                    {% for p in prices %}
                                        <div class='evro'>{{ p.val|number_format(2, '.', ' ') }} {{ p.name|trim('.') }}</div>
                                    {% endfor %}
                                </div>
                            </li>
                            <li>
                                <a href="/ads/user-{{ ads.user_id }}-{{ ads.user_name|translit }}" class='color727272'>
                                    <i class="user-new-iconz"></i>&nbsp;<span
                                            itemprop="seller"> {{ ads.user_name }}</span>
                                </a>
                            </li>
                            {% for key, value in ads.phones %}
                                {% if value != '' %}
                                    <li>
                                        <i class="tel-new-iconz"></i>&nbsp; <span class='color727272'>{{ value }}</span>
                                    </li>
                                {% endif %}
                            {% endfor %}
                            <li>
                                <i class="globe-new-iconz"></i>
                                <span class='color727272'> г. {{ ads.city }}</span>
                            </li>
                        </ul>

                    </div>


                {% else %}
                    {% if ads.user_id==user_info.info.user_id %}
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
            <div class="col-2">
                {% if ads.content %}
                    {{ ads.content|raw|nl2br }}
                {% else %}
                    {{ ads.description }}
                {% endif %}
            </div>
            <div class="clear"></div>
            {% if gallery or ads.video_link %}
            <div class="idTabs">
                {% if gallery %}
                {% endif %}
                {% if ads.video_link %}
                {% endif %}
                {% endif %}
                {% if gallery %}
                    <div id="" class="ad-gallery">
                        <div class="ad-nav">
                            <div class="ad-thumbs">
                                {% for g in gallery %}
                                    <div class='foto'>
                                        <img title="{{ g.description }}" alt="{{ g.description }}"
                                             src="/uploads/images/offers/full/{{ g.url_full }}"/>
                                    </div>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    <p>&nbsp;</p>
                {% endif %}
                {% if ads.video_link %}
                    <div id="video">
                        <iframe width="100%" height="394" src="//www.youtube.com/embed/{{ ads.video_link }}"
                                frameborder="0" allowfullscreen></iframe>
                    </div>
                {% endif %}
                {% if gallery or ads.video_link %}
            </div>
            {% endif %}
            <!----------------->
        </div>
    </div>
    <!----------------->
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
                      action="/index.ajax.php?route=/ads/send-message-{{ ads.ads_id }}">
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
                    <input type="hidden" name="user_id" value="{{ ads.user_id }}"/>

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
                    Чтобы написать автору, нужно <a title="Вход" href="/login">войти </a> или <a title="Регистрация"
                                                                                                 href="/registration">зарегистрироваться</a><br/>
                </center>
            </div>
        {% endif %}
    </div>
    </div>
    {% if vip %}
        <noindex>
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
                        <a href="/ads/{{ v.ads_id }}-{{ v.product_name|translit }}">
                            {% if v.image %}
                                <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                     src="/uploads/images/offers/full/{{ v.image }}"/>
                            {% else %}
                                <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                     src="/uploads/images/80x100.jpg"/>
                            {% endif %}
                        </a>

                        <div class='filter_l'>
                            {{ v.categ_name }}
                        </div>
                        {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                        <div class='ic vp_kon'>
                            <a title="{{ v.product_name }}, Б/У" target="_blank"
                               href="/ads/{{ v.ads_id }}-{{ v.product_name|translit }}" class="modal-window-link"></a>
                        </div>
                        <div class="name_ta vp_kon">
                            <a href="/ads/{{ v.ads_id }}-{{ v.product_name|translit }}">{{ v.product_name }}</a>
                        </div>
                        <div class="name_ta">
                            <div class="a-font-small a-color-gray">{{ v.description }}</div>
                        </div>
                        <div class="a-row a-offset-0 offer-footer vp_kon">
                            <div class="a-cols-2">
                                <i class="a-icon-user a-icon-gray"></i> {{ v.user_name }}
                            </div>
                            <div class="a-cols-2 a-align-right">
                                <div class="price {% if v.stock_flag %}price-stock{% endif %}">
                                    {% if v.stock_flag %}
                                        {{ v.stock_price|getExchangeRates(v.stock_currency_id, v.user_id)|number_format(2, '.', ' ') }} {{ currency|trim('.') }}
                                    {% else %}
                                        {{ v.price|getExchangeRates(v.currency_id, v.user_id)|number_format(2, '.', ' ') }} {{ currency|trim('.') }}
                                    {% endif %}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div></noindex>
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
{% endblock %}