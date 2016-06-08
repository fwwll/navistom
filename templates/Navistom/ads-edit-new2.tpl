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
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}

    <h1 class="n-form-title">
        <span>Редактировать товар Б/У</span>

        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
    </h1>

    {% if data.user_id == user_info.info.user_id or is_admin %}

        <form id="product-add-form" class="n-add-form a-clear validation" method="post"
              action="/index.ajax.php?route=/ads/edit_ajax-{{ data.ads_id }}">
            <div class="a-row">
                <label><font class="a-red">*</font> Рубрика</label>
                <select {% if data.flag_moder_view and data.flag_moder and is_admin == null %} disabled="disabled" {% endif %}
                        placeholder="Выберите из списка" class="select-2 validate[required]" id="categ_id"
                        name="categ_id">
                    <option value></option>
                    {% for c in categs %}
                        <option {% if c.categ_id == data.categ_id %}selected="selected"{% endif %}
                                value="{{ c.categ_id }}">{{ c.name }}</option>
                    {% endfor %}
                </select>
                {% if data.flag_moder_view and data.flag_moder and is_admin == null %}
                    <input type="hidden" name="categ_id" value="{{ data.categ_id }}">
                {% endif %}
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Подрубрика</label>
                <select {% if data.flag_moder_view and data.flag_moder and is_admin == null %} disabled="disabled" {% endif %}
                        placeholder="Выберите рубрику" class="select-2 validate[required]" id="sub_categ_id"
                        name="sub_categ_id">
                    {% for c in sub_categs %}
                        <option {% if c.categ_id == data.sub_categ_id %}selected="selected"{% endif %}
                                value="{{ c.categ_id }}">{{ c.name }}</option>
                    {% endfor %}
                </select>
                {% if data.flag_moder_view and data.flag_moder and is_admin == null %}
                    <input type="hidden" name="sub_categ_id" value="{{ data.sub_categ_id }}">
                {% endif %}
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Производитель</label>
                <select placeholder="Выберите из списка" class="select-2 validate[groupRequired[producer]]"
                        id="producer_id" name="producer_id">
                    <option value></option>
                    {% for p in producers %}
                        <option {% if p.producer_id == data.producer_id %}selected="selected"{% endif %}
                                value="{{ p.producer_id }}">{{ p.name }}</option>
                    {% endfor %}
                </select>

                <div class="a-float-right">
                    <!--a id="new-producer-add" class="a-color-gray a-font-small link" href="#">Не нашли нужного производителя, добавьте.</a-->
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
                    {% for p in products %}
                        <option {% if p.product_id == data.product_id %}selected="selected"{% endif %}
                                value="{{ p.product_id }}">{{ p.name }}</option>
                    {% endfor %}
                </select>

                <div class="a-float-right">
                    <!--a id="new-product-add" class="a-color-gray a-font-small link" href="#">Не нашли нужный товар, добавьте.</a-->
                </div>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font>Стоимость</label>
                <input value="{{ data.price }}" class="n-price-input validate[required, min[1], custom[number]]"
                       type="text" name="price" id="price"/>

                <select class="n-currensy-input" name="currency_id" id="currency_id">
                    {% for c in currency %}
                        <option {% if data.currency_id == c.currency_id %}selected="selected"{% endif %}
                                value="{{ c.currency_id }}">{{ c.name_min }}</option>
                    {% endfor %}
                </select>
            </div>

            <div class="a-row">
                <label>Текст объявления</label>
                <textarea class="autosize" maxlength="3000" name="content">{{ data.content }}</textarea>
            </div>
            <div class="a-row">
                <label>Фотографии</label>

                <ul class="uploader" id="uploader">
                    {% for i in images %}
                        <li class="image-added">
                            <input type="hidden" value="{{ i.image_id }}" name="images[]"/>
                            <img src="/uploads/images/offers/80x100/{{ i.url_full }}" alt="{{ i.image_id }}"/>
                        </li>
                    {% endfor %}


                    {% set countim=  images|length %}
                    {% if  countim<8 %}
                        {% set countim2 = 8 -countim %}
                        {% for i in countim2 %}
                            <li></li>
                        {% endfor %}
                    {% endif %}
                </ul>
            </div>
            <div class="a-row">
                <label>Ссылка на видео с YouTube</label>
                <input value="{{ data.video_link }}" type="text" name="video_link" id="video_link"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Контактные телефоны</label>
                <input type="text" value="{{ data.contact_phones }}" name="contact_phones" class="phones-input"/>
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Сохранить"/>
            </div>
        </form>

    {% else %}

        <div class="a-mess-orange">
            У Вас нет прав для редактирования этого объявления.

            <div class="a-float-right">
                <a title="Вход" href="/#/login"> <i class="a-icon-check a-icon-white"></i> Вход</a> &nbsp;&nbsp;&nbsp;&nbsp;
                <a title="Регистрация" href="/#/registration"><i class="a-icon-plus-sign a-icon-white"></i> Регистрация</a>
            </div>
        </div>

    {% endif %}

    </div>

{% endblock %}