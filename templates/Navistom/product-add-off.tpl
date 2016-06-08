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
    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}
    <h1 class="n-form-title">
        <span>Добавить новый товар</span>
        {% if user_info %}
            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        {% endif %}
    </h1>
    {% if is_add_access %}
        <form id="resume-add-form" class="n-add-form a-clear validation" method="post"
              action="/index.ajax.php?route=/product/add_ajax">
            <div class="a-row">
                <label><font class="a-red">*</font> Рубрика</label>
                <select placeholder="Выберите из списка" class="select-2 validate[required]" id="categ_id"
                        name="categ_id">
                    <option value></option>
                    {% for c in categs %}
                        <option data-description="{{ c.description }}" value="{{ c.categ_id }}">{{ c.name }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Подрубрика</label>
                <select placeholder="Выберите рубрику" class="select-2 validate[required]" id="sub_categ_id"
                        name="sub_categ_id">
                    <option value></option>
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Производитель</label>
                <select placeholder="Выберите из списка" class="select-2 validate[groupRequired[producer]]"
                        id="producer_id" name="producer_id">
                    <option value></option>
                    {% for p in producers %}
                        <option value="{{ p.producer_id }}">{{ p.name }}</option>
                    {% endfor %}
                </select>

                <div class="a-float-right">
                    <a id="new-producer-add" class="a-color-gray a-font-small link" href="#">Не нашли нужного
                        производителя, добавьте.</a>
                </div>
            </div>
            <div class="a-row">
                <label>
                    <font class="a-red">*</font> Наименование товара
                    <span class="a-form-descr">Модель, марка или серия</span>
                </label>
                <select placeholder="Выберите производителя" class="select-2 validate[groupRequired[product]]"
                        id="product_id" name="product_id">
                    <option value></option>
                </select>

                <div class="a-float-right">
                    <a id="new-product-add" class="a-color-gray a-font-small link" href="#">Не нашли нужный товар,
                        добавьте.</a>
                </div>
            </div>
            <div class="a-row n-title-description">
                <label><font class="a-red">*</font>Цена</label>
                <input class="n-price-input validate[required, min[1], custom[number]]" type="text" name="price"
                       id="price"/>

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
                    Для корректного отображения цен <a target="_blank" class="link" href="/cabinet/profile/exchanges">установите
                        СВОЙ КУРС ВАЛЮТ в личном кабинете.</a>
                </div>
            </div>

            <div class="a-row">
                <label>Описание товара</label>
                <textarea class="autosize" maxlength="3000" name="content"></textarea>
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
                <label><font class="a-red">*</font> Контактные телефоны</label>
                <input type="text" value="{{ user_info.info.contact_phones }}" name="contact_phones"
                       class="phones-input validate[required]"/>
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

                            <button class="input-submit vip-submit" type="submit" name="vip" value="3">Заказать</button>
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

                            <button class="input-submit vip-submit" type="submit" name="vip" value="2">Заказать</button>
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

                            <button class="input-submit vip-submit" type="submit" name="vip" value="1">Заказать</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="a-row">
                <div class="a-float-right">
                    <button class="input-submit input-submit-green" type="submit" name="default">Добавить бесплатно
                    </button>
                </div>
                <div style="line-height:36px; margin-right:20px" class="a-float-right">
                    Сейчас на NaviStom <strong>{{ count|number_format(0, '', ' ') }}</strong> объявлений
                </div>

            </div>
        </form>
    {% else %}
        {% if user_info %}
            {% include 'access-denied.tpl' with {'sectionId': 3} %}
        {% else %}
            {% include 'user-no-auth-mess.tpl' %}
        {% endif %}
    {% endif %}
    </div>
{% endblock %}