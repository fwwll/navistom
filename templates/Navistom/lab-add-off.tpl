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
            <span>Добавить зуботехническую услугу</span>
            {% if user_info %}
                <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
            {% endif %}
        </h1>
        {% if is_add_access %}
            <form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data"
                  action="/index.ajax.php?route=/lab/add_ajax">
                <div class="a-row">
                    <label><font class="a-red">*</font> Виды работ</label>
                    <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]"
                            id="activity_categ_id" name="categ_id[]">
                        {% for c in categs %}
                            <option value="{{ c.categ_id }}">{{ c.name }}</option>
                        {% endfor %}
                    </select>
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
                    <label><font class="a-red">*</font> Населенный пункт</label>
                    <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id"
                            name="city_id">
                        <option value></option>
                    </select>
                </div>
                <div class="a-row">
                    <label>Адрес лаборатории</label>
                    <input type="text" name="address" id="address"/>
                </div>
                <div class="a-row">
                    <label><font class="a-red">*</font> Контактные телефоны</label>
                    <input type="text" value="{{ user_info.info.contact_phones }}" name="contact_phones"
                           class="phones-input validate[required]"/>
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
                                Начинайте с ключевого слова: ПРЕДЛАГАЮ/ВЫПОЛНЯЮ, ИЗГОТОВЛЕНИЕ и т.д.
                            </div>
                        </div>
                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">2</span>
                            </div>
                            <div class="col-2">
                                Используйте ключевые слова: ЗУБОТЕХНИЧЕСКИЕ РАБОТЫ, УСЛУГИ ЗУБНОГО
                                ТЕХНИКА/ЗУБОТЕХНИЧЕСКОЙ ЛАБОРАТОРИИ и т.д.
                            </div>
                        </div>

                        <p>
                            <b>Неправильно:</b> <br/>
                            Работы любой сложности
                        </p>

                        <b>Правильно:</b> <br/>
                        Выполняю широкий спектр зуботехнических работ
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
                    <label>Веб-сайт</label>
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
                        <b>
                            Закажите VIP-размещение данного объявления! <br>
                            Отображение вверху списка в рубрике или на главной на 30 календарных дней
                        </b>
                    </div>
                    <div class="a-row">
                        <div class="a-cols-3">
                            <div class="add-form-vip-box">
                                <div class="vip-item-icon">
                                    <img src="/{{ tpl_dir }}/images/vip-3.png"/>
                                </div>

                                в рубрике<br>
                                в подрубрике

                                <div class="vip-box-price">
                                    <b>100 грн.</b>
                                </div>

                                <button class="input-submit vip-submit" type="submit" name="vip" value="3">Заказать
                                </button>
                            </div>
                        </div>
                        <div class="a-cols-3">
                            <div class="add-form-vip-box">
                                <div class="vip-item-icon">
                                    <img src="/{{ tpl_dir }}/images/vip-2.png"/>
                                </div>

                                в разделе<br>
                                в рубрике<br>
                                в подрубрике

                                <div class="vip-box-price">
                                    <b>250 грн.</b>
                                </div>

                                <button class="input-submit vip-submit" type="submit" name="vip" value="2">Заказать
                                </button>
                            </div>
                        </div>
                        <div class="a-cols-3">
                            <div class="add-form-vip-box">
                                <div class="vip-item-icon">
                                    <img src="/{{ tpl_dir }}/images/vip-1.png"/>
                                </div>

                                на главной<br>
                                в разделе<br>
                                в рубрике<br>
                                в подрубрике<br>
                                в объявлениях конкуретов

                                <div class="vip-box-price">
                                    <b>500 грн.</b>
                                </div>

                                <button class="input-submit vip-submit" type="submit" name="vip" value="1">Заказать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="a-row">
                    <div class="a-float-right">
                        <button class="input-submit input-submit-green" type="submit" name="default">Добавить
                            бесплатно
                        </button>
                    </div>
                    <div style="line-height:36px; margin-right:20px" class="a-float-right">
                        Сейчас на NaviStom <strong>{{ count|number_format(0, '', ' ') }}</strong> объявлений
                    </div>

                </div>
            </form>
        {% else %}
            {% if user_info %}
                {% include 'access-denied.tpl' with {'sectionId': 7} %}
            {% else %}
                {% include 'user-no-auth-mess.tpl' %}
            {% endif %}
        {% endif %}
    </div>
{% endblock %}