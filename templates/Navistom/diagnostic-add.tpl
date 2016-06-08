{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

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
    <div style="width:700px">
        <h1 class="n-form-title">
            <span>Добавить диагностический центр</span>
            {% if user_info %}
                <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
            {% endif %}
        </h1>
        {% if is_add_access %}
            <form id="activity-add-form" class="n-add-form a-clear all_f" method="post" enctype="multipart/form-data"
                  action="/index.ajax.php?route=/diagnostic/add_ajax">
                <div class="a-row">
                    <label><font class="a-red">*</font> Название диагностического центра</label>
                    <input type="text" class="validate[required]" name="user_name" id="user_name"/>
                </div>
                <div class="a-row">
                    <label><font class="a-red">*</font> Контактные телефоны</label>
                    <input type="text" value="{{ user_info.info.contact_phones }}" name="contact_phones"
                           class="phones-input validate[required]"/>
                </div>
                <div class="a-row">
                    <label><font class="a-red">*</font> Регион</label>
                    <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id"
                            name="region_id">
                        <option value></option>
                        {% for key, value in regions %}
                            <option value="{{ key }}">{{ value }}</option>
                        {% endfor %}
                    </select>
                </div>
                <div class="a-row">
                    <label><font class="a-red">*</font> Город</label>
                    <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id"
                            name="city_id">
                        <option value></option>
                    </select>
                </div>
                <div class="a-row">
                    <label>Адрес</label>
                    <input type="text" name="address" id="address"/>
                </div>
                <div class="a-row n-title-description">
                    <label><font class="a-red">*</font> Заголовок</label>
                    <input maxlength="70" class="validate[required]" type="text" name="name" id="name"/>

                    <div class="n-ad-add-desc a-clear">
                        <h5>Делайте заголовок эффективным!</h5>

                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">1</span>
                            </div>
                            <div class="col-2">
                                Начинайте с ключевого слова: ДИАГНОСТИКА, РЕНТГЕН, КОМПЬЮТЕРНАЯ ТОМОГРАФИЯ и т.д.
                            </div>
                        </div>
                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">2</span>
                            </div>
                            <div class="col-2">
                                Используйте ключевые слова: ДИАГНОСТИКА ЗУБОВ, ЦЕНТР ЧЕЛЮСТНО-ЛИЦЕВОЙ ДИАГНОСТИКИ, 3D
                                ДИАГНОСТИКА ДЛЯ СТОМАТОЛОГОВ
                            </div>
                        </div>
                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">3</span>
                            </div>
                            <div class="col-2">
                                Обязательно указывайте регион: город, населенный пункт
                            </div>
                        </div>

                        <p>
                            <b>Неправильно:</b> <br/>
                            Диагностика от 250 грн.
                        </p>

                        <b>Правильно:</b> <br/>
                        3D диагностика зубов в Житомире от 250 грн.
                    </div>
                </div>

                <div class="a-row">
                    <label>Описание предоставляемых услуг</label>
                    <textarea class="autosize" maxlength="3000" name="content"></textarea>
                </div>
                <div class="a-row">
                    <label>Вложение <span class="a-form-descr">прайс-лист DOC или PDF</span></label>
                    <input type="file" name="attachment" id="attachment"/>
                </div>
                <div class="a-row">
                    <label>Веб сайт</label>
                    <input placeholder="Образец: http://my-site.com" value="{{ user_info.info.site }}" type="text"
                           name="link" id="link"/>
                </div>
                <div class="a-row">
                    <label>Фотографии</label>

                    <ul class="uploader" id="uploader">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li class="last"></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li class="last"></li>
                    </ul>
                </div>
                <div class="a-row">
                    <label>Ссылка на видео с YouTube</label>
                    <input type="text" name="video_link" id="video_link"/>
                </div>
                <div class="a-row">
                    <div style="text-align:center">
                        <b class="vip_h1">
                            Заказывайте</br>
                            ТОП-размещение объявления
                        </b></br>
                        <div style='margin :0 auto; text-align:center; font-size:16px;padding-bottom:5px;'>
                            ТОП-объявление отображается вверху на Главной, в Разделе, Рубрике, Подрубрике.
                        </div>
                        <div class="count_vip">
                            Сейчас на NaviStom <strong class='red'>{{ count|number_format(0, '', ' ') }}</strong>
                            объявлений
                        </div>
                    </div>
                    {% include 'price_inc.tpl' %}


                    <div id='all_payments'>
                        <div id='port' class='payments active'>
                            <div>
                                <img src='/templates/Navistom/images/vip/port.png'/>
                            </div>
                            <div class='i_put'>
                                <input type="radio" noUniform='true' id='p1' class='plat-radio' name="plat" value="port"
                                       checked/>
                                <label for='p1'>
                                </label>
                                <span>Банковская карта</span>
                            </div>
                        </div>

                        <div id='liq' class='payments'>
                            <div>
                                <img src='/templates/Navistom/images/vip/priv.png'/>
                            </div>
                            <div class='i_put'>
                                <input type="radio" noUniform='true' id='p2' class='plat-radio' name="plat"
                                       value="liq"/>
                                <label for='p2'>
                                </label>
				<span>
				  Приват24 
				</span>
                            </div>
                        </div>
                        <div class='clear'></div>
                    </div>
                    <div class='clear'></div>
                    <div class='vip_center'>
                        <div class="a-row">
                            <!--a href='/' class=" input-submit-green" target='_black'> ОПЛАТИТЬ И ОПУБЛИКОВАТЬ</a-->

                            <div style='margin:0 auto; text-align: center;'>
                                <button type="submit" class="input-submit input-submit-green" name='vip' value='1'>
                                    Добавить в ТОП
                                </button>
                            </div>
                        </div>

                        <div class='liqpay'>

                        </div>
                    </div>

                    <!--div style='margin :0 auto; text-align:center; font-size:16px;padding-bottom:20px;'>ТОП-объявление отображается вверху на Главной, в Разделе, Рубрике, Подрубрике.</div-->
                    <div class='vip_center'>
                        <!--div  class="count_vip"  >
				Сейчас на NaviStom <strong>{{ count|number_format(0, '', ' ') }}</strong> объявлений
			</div-->
                        <b class='b_vip'>Что дает ТОП-размещение:</b>
                        <ul>
                            <li><span class='p_ka'> </span>Больше просмотров</li>
                            <li><span class='p_ka'> </span>Больше откликов</li>
                            <li><span class='p_ka'></span>Объявления заметнее</li>
                        </ul>

                        <div class="a-row">

                            <div class="">

                            </div>

                        </div>
                    </div>

                    {% include 'placement_rules.tpl' %}


            </form>
        {% else %}
            {% if user_info %}
                {% include 'access-denied.tpl' with {'sectionId': 10} %}
            {% else %}
                {% include 'user-no-auth-mess.tpl' %}
            {% endif %}
        {% endif %}
    </div>
{% endblock %}