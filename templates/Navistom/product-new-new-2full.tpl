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
        <div style="width:700px; max-width:700px;">
        {% if product.flag_delete > 0 %}
            <script>
                window.location.href = "/products/sub_categ-{{product.sub_categ_id}}-{{product.categ_name|translit}}#404";
            </script>
        {% endif %}
    {% else %}
        {% if product.stock_flag %}
            <form id="global-search" method="get" action="/{{ route.controller }}/filter-stocks/search">
                <input placeholder="Поиск в разделе АКЦИИ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit">Искать</button>
            </form>
        {% else %}
            <form id="global-search" method="get" action="/{{ route.controller }}/search">
                <input placeholder="Поиск в разделе ПРОДАМ НОВОЕ" type="text" value="{{ route.values.search }}" name="q"
                       id="global-search-input"/>
                <button id="search-submit" type="submit">Искать</button>
            </form>
        {% endif %}
        <div class="item " >
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
                    {% if product.flag_moder == 0 %}
                        <span class="yellow">На модерации</span>
                    {% elseif product.flag == 0 %}
                        <span class="gray">Скрыто</span>
                    {% else %}
                        <span class="green">Опубликовано</span>
                    {% endif %}
                </li>
                {% if product.user_id == user_info.info.user_id and "now"|datediff(product.date_add) > 13 %}
                    <li>
                        <div class="update-date">
                            <a href="/update-date-add-products_new-product_new_id-{{ product.product_new_id }}"><i
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
            <div class="a-cols-4 a-font-small a-color-gray">
                {% if product.stock_flag %} {{ product.stock_date|timeago }} {% else %} {{ product.date_add|timeago }} {% endif %}
                &nbsp; | &nbsp;
                {{ product.views }} <i class="a-icon-eye-open a-icon-gray"></i>&nbsp; | &nbsp;<span
                        class="raceta2 cor_rac" onclick='raceta()' title='рекламировать'></span>
            </div>
        </div>
        <h1 class="full-title">
            {% if product.urgently %}<span class="srochno big">Cрочно!</span> {% endif %}
            {% if product.stock_flag %} <span class="navi-stock-marker">Акция!</span> {% endif %}
            {{ product.product_name }}
        </h1>

        <div class="a-font-small a-color-gray">{{ product.description|raw }}</div>
    </div>
    <div class="n-ad-full a-clear">
    <div class="col-1">
        <div class='img_top full_padding'>
            {% if product.image and  product.image != 'none.jpg' %}
                <img title="{{ product.product_name }}" alt="{{ product.product_name }}" class='{{ product.class }}'
                     src="{{ product.big_img }}{{ product.image }}"/>
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
                    {#{product.price_description}#}<br/>
                {% endif %}
                <div class='stock_content'>
                    {{ product.stock_content|raw|nl2br }}
                </div>
                <div class='timers' stop='{{ product.date_end }}' stamp='{{ product.end_stamp }}'>
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
            <ul id="ad-info-list">
                <li>
                    <noindex>
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
                    </noindex>
                </li>
                <li>
                    <a href="/products/user-{{ product.user_id }}-{{ product.user_name|translit }}" class='color727272'>
                        <i class="user-new-iconz "></i> {{ product.user_name }}
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
                        <noindex>
                            <i class="link-new-iconz"></i>&nbsp;
                            <a target="_blank" href="{{ product.site }}" class='color727272'>Веб сайт</a>
                        </noindex>
                    </li>
                {% endif %}
                {% if product.icq %}
                    <li>
                        <span class="a-color-gray">ICQ:</span><span class='color727272'> {{ product.icq }} </span>
                    </li>
                {% endif %}
                {% if product.skype %}
                    <li>
                        <i class="skype-new-iconz"></i><span class='color727272'>  {{ product.skype }}</span>
                    </li>
                {% endif %}
            </ul>
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
                    <a target="_blank"
                       href="/product/{{ product.product_new_id }}-{{ product.product_name|translit }}?print"
                       class='color727272'><i class=" print-new-iconz"></i> На печать</a>
                </noindex>
            </div>
            <div class="print-mess right">
                <noindex>
                    <a title="Пожаловаться" id="send-error-mess-link" href="#" class='color727272'>
                        <i class="alert-new-iconz"></i> Пожаловаться
                    </a>
                </noindex>
            </div>
        </div>
        {% if user_info %}
            <div class="a-clear">
                <form id="send-user-mess" class="n-comment-add a-clear" method="post"
                      action="/index.ajax.php?route=/product/send-message-{{ product.product_new_id }}">
                    <div class='zayvka'>Ваша заявка</div>
                    <textarea class="autosize" placeholder="Написать автору объявления..." name="message">Меня
                        интересует объявление {{ product.product_name }}

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
                <i class="a-icon-envelope a-icon-gray"></i>
                Написать автору могут только зарегистрированные пользователи. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a title="Вход" href="/login"> <i class="a-icon-check a-icon-gray"></i> Вход</a> &nbsp;&nbsp;&nbsp;&nbsp;
                <a title="Регистрация" href="/registration"><i class="a-icon-plus-sign a-icon-gray"></i> Регистрация</a>
            </div>
        {% endif %}
    </div>
    </div>
    {% if vip %}
<noindex>
    <div class="vip-ads-full">
        {% for v in vip %}
            <div class="item pagination-block  light {% if v.color_yellow >1 %} color_yellow {% endif %}">
                <div class='a-row a-offset-0'>
                    <div class='a-cols-2 a-font-small a-color-gray-2'>
                        {% if v.show_top > 0 %}
                            <span class="l_top kon"> <span>топ</span></span>
                        {% endif %}
                        <a href='/products'>Продам новое</a>
                    </div>
                    <div style="font-size:10px"
                         class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                        <span style="font-size:10px">{{ v.date_add|timeago }}</span>
                    </div>
                </div>
                <div class='offer clear concurent'>
                    <a href="/product/{{ v.product_new_id }}-{{ v.product_name|translit }}">
                        {% if product.image %}
                            <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                 src="/uploads/images/products/80x100/{{ v.image }}"/>
                        {% else %}
                            <div class='none_img'>
                                <img title="{{ v.product_name }}" alt="{{ v.product_name }}"
                                     src="/uploads/images/80x100.jpg"/>
                            </div>
                        {% endif %}
                    </a>
                    {% if v.urgently %} <span class="srochno">Cрочно!</span>{% endif %}
                    <b>{{ v.product_name }}</b>

                    <div class="a-font-small a-color-gray">{{ v.description }}</div>
                    <div class="a-row a-offset-0 offer-footer">
                        <div class="a-cols-2">
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
    <noindex>
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
    {% if product.stock_content %}
        <script>
            jQuery(document).ready(function ($) {
                clearInterval(document.fix);
                var stamp = $('.timers').attr('stop');

                function fulltime(stop) {
                    var time = new Date();
                    Year = new Date(stamp);
                    var totalRemains = (Year.getTime() - time.getTime());
                    if (totalRemains > 1) {
                        var RemainsSec = (parseInt(totalRemains / 1000));//сколько всего осталось секунд
                        var RemainsFullDays = (parseInt(RemainsSec / (24 * 60 * 60)));//осталось дней
                        var secInLastDay = RemainsSec - RemainsFullDays * 24 * 3600; //осталось секунд в неполном дне
                        var RemainsFullHours = (parseInt(secInLastDay / 3600));//осталось часов в неполном дне
                        if (RemainsFullHours < 10) {
                            RemainsFullHours = "0" + RemainsFullHours
                        }
                        ;
                        var secInLastHour = secInLastDay - RemainsFullHours * 3600;//осталось секунд в неполном часе
                        var RemainsMinutes = (parseInt(secInLastHour / 60));//осталось минут в неполном часе
                        if (RemainsMinutes < 10) {
                            RemainsMinutes = "0" + RemainsMinutes
                        }
                        ;
                        var lastSec = secInLastHour - RemainsMinutes * 60;//осталось секунд
                        if (lastSec < 10) {
                            lastSec = "0" + lastSec
                        }
                        ;

                        var strdata = '' + RemainsFullDays + ':' + '' + RemainsFullHours + ':' + RemainsMinutes + '' + ':' + lastSec;
                        if (RemainsFullDays <= 9) {
                            RemainsFullDays = '0' + '' + RemainsFullDays;
                        }
                        $('#d').text(RemainsFullDays);
                        $('#h').text(RemainsFullHours);
                        $('#i').text(RemainsMinutes);
                        $('#s').text(lastSec);
                    } else {
                        $('#d').text('00');
                        $('#h').text('00');
                        $('#i').text('00');
                        $('#s').text('00');
                    }
                }

                document.fix = setInterval(function () {
                    fulltime(stop)
                }, 1000);
            })
        </script>
    {% endif %}
{% endblock %}