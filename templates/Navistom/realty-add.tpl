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
    <div>
        <h1 class="n-form-title">
            <span>Добавить предложение</span>
            {% if user_info %}
                <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
            {% endif %}
        </h1>
        {% if is_add_access %}
            <form id="activity-add-form" class="n-add-form a-clear all_f" method="post" enctype="multipart/form-data"
                  action="/index.ajax.php?route=/realty/add_ajax">
                <div class="a-row">
                    <label><font class="a-red">*</font> Рубрика</label>
                    <select placeholder="Выберите из списка" class="select-2 validate[required]" id="activity_categ_id"
                            name="categ_id">
                        <option></option>
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
                    <label>Цена</label>
                    <input class="n-price-input" type="text" name="price" id="price"/>

                    <select class="n-currensy-input" name="currency_id" id="currency_id">
                        {% for c in currency %}
                            <option value="{{ c.currency_id }}">{{ c.name_min }}</option>
                        {% endfor %}
                    </select>

                    <div class="n-ad-add-desc a-clear">
                        На NaviStom есть два варианта цены на товар: <br/>

                        - в национальной валюте без привязки к курсу доллара и евро<br/>
                        - в долларах или евро с привязкой к курсу и автоматическим пересчетом при изменении <br/><br/>

                        По умолчанию установлена привязка к курсу НБУ <br/>
                        Для корректного отображения цен <a target="_blank" class="link"
                                                           href="/cabinet/profile/exchanges">установите СВОЙ КУРС ВАЛЮТ
                            в личном кабинете.</a>
                    </div>
                </div>
                <div class="a-row">
                    <label>Описание цены</label>
                    <input type="text" name="price_description" id="price_description"/>
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
                        <h5>Пишите заголовок правильно:</h5>

                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">1</span>
                            </div>
                            <div class="col-2">
                                Начинайте с ключевого слова: СДАМ/ВОЗЬМУ В АРЕНДУ, ПРОДАМ/КУПЛЮ и т.д.
                            </div>
                        </div>
                        <div class="a-clear">
                            <div class="col-1">
                                <span class="n-circle">2</span>
                            </div>
                            <div class="col-2">
                                Используйте ключевые слова: СТОМАТОЛОГИЧЕСКИЙ/ЗУБОТЕХНИЧЕСКИЙ, ДЛЯ СТОМАТОЛОГА/ЗУБНОГО
                                ТЕХНИКА и т.д.
                            </div>
                        </div>
                        <div class="a-clear">
                            <div class="col-1">

                            </div>
                        </div>

                        <p>
                            <b>Неправильно:</b> <br/>
                            Отдельный кабинет в центре города
                        </p>

                        <b>Правильно:</b> <br/>
                        Сдам в аренду стоматологический кабинет ... <b>название населенного пункта добавится
                            автоматически.</b>
                    </div>
                </div>

                <div class="a-row">
                    <label><font class="a-red">*</font> Описание</label>
                    <textarea class="autosize validate[required]" maxlength="3000" name="content"></textarea>
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

                    {#% include 'informer.tpl'%#}
                    {#% include 'price_inc_new.tpl'%#}
                    {% include 'price_inc_noy.tpl' %}




                    {#% include 'placement_rules.tpl'%#}

            </form>
        {% else %}
            {% if user_info %}
                {% include 'access-denied.tpl' with {'sectionId': 8} %}
            {% else %}
                {% include 'user-no-auth-mess.tpl' %}
            {% endif %}
        {% endif %}
    </div>
{% endblock %}