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
        <span>Добавить новое мероприятие</span>
        {% if user_info %}
            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        {% endif %}
    </h1>
    {% if is_add_access %}
        <form id="activity-add-form" class="n-add-form a-clear" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/activity/add_ajax">
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
            <label><font class="a-red">*</font> Контактные телефоны</label>
            <input type="text" value="{{ user_info.info.contact_phones }}" name="contact_phones"
                   class="phones-input validate[required]"/>
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
                <textarea class="validate[required]" name="lector_description[]" id="lector_description_0"></textarea>
            </div>
            <hr class="hr-min"/>
        </div>

        <div class="a-row">
            <a class="a-btn a-float-right" id="add-lector" href="javascript:void(0)"><i class="a-icon-plus"></i>
                Добавить лектора</a>
        </div>

        <div class="a-row n-title-description">
            <label><font class="a-red">*</font> Заголовок</label>
            <input maxlength="100" class="validate[required]" type="text" name="name" id="name"/>

            <div class="n-ad-add-desc a-clear">
                <h5>Делайте заголовок эффективным!</h5>

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
                        Исключайте из заголовка: ДАТУ, АДРЕС, ИМЯ ЛЕКТОРА, КОВЫЧКИ и т.д.
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
            <label>Описание мероприятия</label>
            <textarea class="autosize" maxlength="6000" name="content"></textarea>
        </div>
        <div class="a-row">
            <label>Вложение <span class="a-form-descr">программа мероприятия DOC или PDF</span></label>
            <input type="file" name="attachment" id="attachment"/>
        </div>
        <div class="a-row">
            <label>
                Ссылка на сайт мероприятия
                <span class="a-form-descr">пример: http://mysite.com</span>
            </label>
            <input type="text" value="{{ user_info.info.site }}" name="link" id="link"/>
        </div>
        <div class="a-row">
            <label>Логотип или ключевое фото</label>
            <input type="file" name="image" id="image"/>
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
                <button class="input-submit input-submit-green" type="submit" name="default">Добавить бесплатно</button>
            </div>
            <div style="line-height:36px; margin-right:20px" class="a-float-right">
                Сейчас на NaviStom <strong>{{ count|number_format(0, '', ' ') }}</strong> объявлений
            </div>

        </div>
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