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
        <div>
    {% else %}
        <div class="">
    {% endif %}

    <h1 class="n-form-title">
        <span>Добавить вакансию</span>
        {% if user_info %}
            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        {% endif %}
    </h1>
    {% if is_add_access %}
        <form id="activity-add-form" class="n-add-form a-clear all_f" method="post" enctype="multipart/form-data"
              action="/index.ajax.php?route=/work/vacancy_add_ajax">
            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-briefcase"></i> Информации о работодателе</span>
                </div>
            </div>

            <div class="a-row">
                <label><font class="a-red">*</font> Работодатель
                    <span class="a-form-descr">Название организации / Ф.И.О</span>
                </label>
                <input type="text" value="{{ company_info.name }}" name="company_name" id="company_name"/>
            </div>
            <div class="a-row">
                <label>Веб-сайт</label>
                <input type="text" value="{{ company_info.site }}" name="company_site" id="company_site"/>
            </div>

            <!--div class="a-row">
        <label>Логотип</label>
        <input type="file" name="image" id="image" />
        <input type="hidden" name="logotype" value="{{ company_info.logotype }}" />
    </div-->


            <div class="a-row">
                <label><font class="a-red">*</font> Коротко о работодателе</label>
                <textarea class="autosize validate[required]" maxlength="1000" name="company_description"
                          id="company_description">{{ company_info.description }}</textarea>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> ФИО контактного лица</label>
                <input type="text"
                       value="{% if company_info %}{{ company_info.user_name }}{% else %}{{ user_name }}{% endif %}"
                       name="user_name" id="user_name"/>
            </div>
            <div class="a-row">
                <label>
                    <font class="a-red">*</font>
                    Телефон контактного лица
                    <span class="a-form-descr">можете указать несколько телефонов через запятую</span>
                </label>
                <input type="text" value="{{ contact_phones|join(',') }}" name="contact_phones" id="contact_phones"
                       class="phones-input"/>
            </div>

            {% if company_info %}
                <input type="hidden" name="company_id" value="{{ company_info.company_id }}"/>
            {% endif %}
            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-user"></i> Требования к соискателю</span>
                </div>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Рубрика</label>
                <select multiple="multiple" placeholder="Выберите из списка" class="select-2 validate[required]"
                        id="activity_categ_id" name="categ_id[]">
                    {% for key, value in categories %}
                        <option value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Регион размещения вакансии</label>
                <select placeholder="Выберите из списка" class="select-2 validate[required]" id="region_id"
                        name="region_id">
                    <option value></option>
                    {% for key, value in regions %}
                        <option value="{{ key }}">{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Город размещения вакансии</label>
                <select placeholder="Выберите регион" class="select-2 validate[required]" id="city_id" name="city_id">
                    <option value></option>
                </select>
            </div>
            <div class="a-row">
                <label>Зарплата, от</label>
                <input class="n-price-input validate[min[1]]" type="text" name="price" id="price"/>

                <select class="n-currensy-input" name="currency_id" id="currency_id">
                    {% for c in currency %}
                        <option value="{{ c.currency_id }}">{{ c.name_min }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Вид занятости</label>
                <select class="validate[required]" id="type" name="type">
                    <option value="1">полная занятость</option>
                    <option value="2">неполная занятость</option>
                    <option value="3">удаленная работа</option>
                    <option value="4">посменно</option>
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Опыт работы</label>
                <select class="validate[required]" id="experience_type" name="experience_type">
                    <option value="0">не имеет значения</option>
                    <option value="1">от 1 года</option>
                    <option value="2">от 2 лет</option>
                    <option value="3">от 5 лет</option>
                </select>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Требуемый уровень образования</label>
                <select class="validate[required]" id="education_type" name="education_type">
                    <option value="0">не имеет значения</option>
                    <option value="1">высшее</option>
                    <option value="2">неоконченное высшее</option>
                    <option value="3">среднее специальное</option>
                    <option value="4">среднее</option>
                </select>
            </div>

            <div class="a-row">
                <div class="form-separator">
                    <span><i class="a-icon-file"></i> Дополнительно</span>
                </div>
            </div>
            <div class="a-row">
                <label>Описание вакансии</label>
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

                {#% include 'informer.tpl'%#}
                {#% include 'price_inc_new.tpl'%#}
                {% include 'price_inc_noy.tpl' %}

                {#% include 'placement_rules.tpl'%#}

        </form>
    {% else %}
        {% if user_info %}
            {% include 'access-denied.tpl' with {'sectionId': 15} %}
        {% else %}
            {% include 'user-no-auth-mess.tpl' %}
        {% endif %}
    {% endif %}
    </div>
{% endblock %}