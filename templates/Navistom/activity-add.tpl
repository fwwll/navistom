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
        <div >
    {% else %}
        <div class="item">
    {% endif %}

    <h1 class="n-form-title">
        <span>Добавить анонс мероприятия</span>
        {% if user_info %}
            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        {% endif %}
    </h1>
    {% if is_add_access %}
        <form id="activity-add-form" class="n-add-form a-clear all_f" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/activity/add_ajax">
            <div class="a-row n-title-description">
                <label><font class="a-red">*</font> Заголовок</label>
                <input maxlength="100" class="validate[required]" type="text" name="name" id="name"/>

                <div class="n-ad-add-desc a-clear">
                    <h5>Пишите заголовок правильно:
                    </h5>

                    <div class="a-clear">
                        <div class="col-1">
                            <span class="n-circle">1</span>
                        </div>
                        <div class="col-2">
                            Начинайте с формы проведения и темы мероприятия: СЕМИНАР, МАСТЕР-КЛАСС, ПРАКТИЧЕСКИЕ КУРСЫ и
                            т.д.
                        </div>
                    </div>
                    <div class="a-clear">
                        <div class="col-1">
                            <span class="n-circle">2</span>
                        </div>
                        <div class="col-2">
                            Не пишите в заголовок: ДАТУ, АДРЕС, ИМЯ ЛЕКТОРА, КОВЫЧКИ и т.д.
                        </div>
                    </div>
                    <div class="a-clear">
                        <div class="col-1">
                            <span class="n-circle">3</span>
                        </div>
                        <div class="col-2">
                            Используйте ключевые слова: СТОМАТОЛОГИЧЕСКИЙ, ДЛЯ СТОМАТОЛОГОВ/ЗУБНЫХ ТЕХНИКОВ и т.д.
                        </div>
                    </div>

                    <p>
                        <b>Неправильно:</b> <br/>
                        14.04.2014. Киев, отель в центре, Иванов Андрей. Практические курсы "Металлокерамические и
                        цельнокерамические протезы"
                    </p>

                    <b>Правильно:</b> <br/>
                    Практический курс для зубных техников: Металлокерамические и цельнокерамические зубные протезы
                </div>
            </div>


            <div class="a-row">
                <label><font class="a-red">*</font> Рубрика</label>
                <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]"
                        id="activity_categ_id" name="categ_id[]">
                    {% for c in categs %}
                        <option value="{{ c.categ_id }}">{{ c.name }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Регион проведения мероприятия</label>
                <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id"
                        name="region_id">
                    <option value></option>
                    {% for key, value in regions %}
                        <option value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Город проведения мероприятия</label>
                <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
                    <option value></option>
                </select>
            </div>
            <div class="a-row">
                <label>Место проведения</label>
                <input type="text" name="address" id="address"/>
            </div>
            <div class="a-row">
                <label> Дата проведения мероприятия</label>

                <input placeholder="Дата начала" type="text" name="date_start" id="date_start"
                       class="validate[groupRequired[datess]] datepicker-start"/>
                <i class="a-icon-calendar"></i>
                <input placeholder="Дата окончания" type="text" name="date_end" id="date_end" class="datepicker-end"/>
                <i class="a-icon-calendar"></i>
            </div>
            <div class="a-row">
                <label>Дата по согласованию</label>

                <input type="checkbox" name="flag_agreed" id="flag_agreed" value="1"
                       class="validate[groupRequired[datess]]"/>
            </div>

            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-user"></i> Информация о лекторах</span>
                </div>
            </div>
            <div class="lectors-add">
                <div class="a-row">
                    <label><font class="a-red">*</font> Ф.И.О. лектора</label>
                    <input class="validate[required]" type="text" name="lector_name[]" id="lector_name_0"/>
                </div>
                <div class="a-row">
                    <label><font class="a-red">*</font> Фото лектора</label>
                    <input class="validate[required]" type="file" name="lector_image[]" id="lector_image_0"/>
                </div>
                <div class="a-row">
                    <label><font class="a-red">*</font> О лекторе</label>
                    <textarea class="validate[required]" name="lector_description[]"
                              id="lector_description_0"></textarea>
                </div>
                <hr class="hr-min"/>
            </div>

            <div class="a-row">
                <a class="a-btn a-float-right" id="add-lector" href="javascript:void(0)"><i class="a-icon-plus"></i>
                    Добавить лектора</a>
            </div>

            <div class="a-row">
                <label>Описание мероприятия</label>
                <textarea class="autosize" maxlength="6000" name="content"></textarea>
            </div>
            <div class="a-row">
                <label>Вложение <span class="a-form-descr">программа мероприятия DOC или PDF</span>
                    <span class="a-form-descr">размер файла до 50мб</span>
                </label>
                <input type="file" name="attachment" id="attachment"/>
            </div>
            <div class="a-row">
                <label>
                    Ссылка на сайт мероприятия
                    <span class="a-form-descr">пример: http://mysite.com</span>
                </label>
                <input type="text" value="{{ user_info.info.site }}" name="link" id="link"/>
            </div>
            <!--div class="a-row">
                <label>Логотип или ключевое фото</label>
                <input type="file" name="image" id="image" />
            </div-->
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

                {#% include 'informer.tpl'%#}
                {#% include 'price_inc_new.tpl'%#}
                {% include 'price_inc_noy.tpl' %}




                {#% include 'placement_rules.tpl'%#}


        </form>
    {% else %}
        {% if user_info %}
            {% include 'access-denied.tpl' with {'sectionId': 5} %}
        {% else %}
            {% include 'user-no-auth-mess.tpl' %}
        {% endif %}
    {% endif %}
    </div>
{% endblock %}