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

    <h1 style="width: 700px" class="n-form-title">
        <span>Добавить акцию к товару</span>

        <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
    </h1>
    {% if is_add_access %}
        <form style="width:700px" id="activity-add-form" class="n-add-form a-clear" method="post"
              enctype="multipart/form-data" action="/index.ajax.php?route=/product/add_stock_ajax-{{ product_new_id }}">
            <div class="a-row">
                <label><font class="a-red">*</font>Акционная цена</label>
                <input class="n-price-input validate[required, min[1]]" type="text" name="price" id="price"/>

                <select class="n-currensy-input" name="currency_id" id="currency_id">
                    {% for c in currency %}
                        <option value="{{ c.currency_id }}">{{ c.name_min }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="a-row">
                <label>Описание цены</label>
                <input type="text" name="price_description" id="price_description"/>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font>Описание акции</label>
                <textarea class="autosize validate[required]" maxlength="300" name="content"></textarea>
            </div>
            <div class="a-row">
                <label><font class="a-red">*</font> Период действия акции</label>

                <input placeholder="Дата начала" type="text" name="date_start" id="date_start"
                       class="datepicker-start validate[required]]"/>
                <i class="a-icon-calendar"></i>
                <input placeholder="Дата окончания" type="text" name="date_end" id="date_end"
                       class="datepicker-end validate[required]"/>
                <i class="a-icon-calendar"></i>
            </div>
            <div class="a-row">
                <label>&nbsp;</label>
                <input class="a-btn-green" type="submit" value="Сохранить"/>
            </div>
        </form>
    {% else %}
        {% if user_info %}
            {% include 'access-denied.tpl' with {'sectionId': 2} %}
        {% else %}
            {% include 'user-no-auth-mess.tpl' %}
        {% endif %}
    {% endif %}
{% endblock %}