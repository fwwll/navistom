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
        <span>Редактировать зуботехническую услугу</span>

        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
    </h1>

    {% if data.user_id == user_info.info.user_id or is_admin %}

        <form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/lab/edit_ajax-{{ data.lab_id }}">
            <div class="a-row">
                <label><font class="a-red">*</font> Виды работ</label>
                <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]"
                        id="activity_categ_id" name="categ_id[]">
                    {% for c in categs %}
                        <option {% if c.categ_id in data.categ_id %}selected="selected"{% endif %}
                                value="{{ c.categ_id }}">{{ c.name }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Регион</label>
                <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id"
                        name="region_id">
                    <option value></option>
                    {% for key, value in regions %}
                        <option {% if key == data.region_id %}selected="selected"{% endif %}
                                value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Населенный пункт</label>
                <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
                    <option value></option>
                    {% for key, value in cities %}
                        <option {% if key == data.city_id %}selected="selected"{% endif %}
                                value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label>Адрес</label>
                <input value="{{ data.address }}" type="text" name="address" id="address"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Контактные телефоны</label>
                <input type="text" value="{{ data.contact_phones }}" name="contact_phones"
                       class="phones-input validate[required]"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Заголовок</label>
                <input maxlength="70" value="{{ data.name }}" class="validate[required]" type="text" name="name"
                       id="name"/>
            </div>

            <div class="a-row">
                <label>Описание предоставляемых услуг</label>
                <textarea class="autosize" maxlength="3000" name="content">{{ data.content|raw }}</textarea>
            </div>
            <div class="a-row">
                <label>Вложение <span class="a-form-descr">прайс-лист DOC или PDF</span></label>
                <input type="file" title="{{ data.attach }}" name="attachment" id="attachment"/>
                <input type="hidden" name="attach" value="{{ data.attach }}"/>
            </div>
            <div class="a-row">
                <label>Веб-сайт</label>
                <input type="text" name="link" id="link" value="{{ data.link }}"/>
            </div>
            <div class="a-row">
                <label>Фотографии</label>

                <ul class="uploader" id="uploader">
                    {% for i in images %}
                        <li class="image-added">
                            <input type="hidden" value="{{ i.image_id }}" name="images[]"/>
                            <img src="/uploads/images/labs/80x100/{{ i.url_full }}" alt="{{ i.image_id }}"/>
                        </li>
                    {% endfor %}
                    {% set countim=  images|length %}
                    {% if  countim<8 %}
                        {% set countim = 8 -countim %}
                        {% for i in 0..images_count %}
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