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
    <h1 class="n-form-title">
        <span>Выделить цветом объявление</span>

        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
    </h1>
    <div style="width:700px">
        {% if is_admin %}
            <form id="activity-add-form" class="n-add-form a-clear" method="post"
                  action="/index.ajax.php?route=light-content-{{ section_id }}-{{ content_id }}">
                <div class="a-row">
                    <label><font class="a-red">*</font> Период действия выделения</label>

                    <input value="{{ data.date_start }}" placeholder="Дата начала" type="text" name="date_start"
                           id="date_start" class="datepicker-start validate[required]]"/>
                    <i class="a-icon-calendar"></i>
                    <input value="{{ data.date_end }}" placeholder="Дата окончания" type="text" name="date_end"
                           id="date_end" class="datepicker-end validate[required]"/>
                    <i class="a-icon-calendar"></i>
                </div>
                <div class="a-row">
                    <label>&nbsp;</label>
                    <input class="a-btn-green" type="submit" value="Сохранить"/>

                    {% if data.date_start %}
                        <div class="a-float-right">
                            <a id="remove-link" class="a-btn"
                               href="light-content-delete-{{ section_id }}-{{ content_id }}">Отменить выделение</a>
                        </div>
                    {% endif %}
                </div>
            </form>
        {% else %}
            <div style="width:700px" class="a-mess-yellow">
                У Вас нет доступа к этой опции.
            </div>
        {% endif %}
    </div>
{% endblock %}