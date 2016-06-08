{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block content %}

    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}

    <div class="n-form-title">
        <span>Сообщить об ошибке на странице: <div id="page-title" class="a-color-gray"></div></span>

    </div>

    <form id="activity-add-form" class="n-add-form a-clear validation" method="post"
          action="/index.ajax.php?route=/send_user_error">
        <input type="hidden" name="url" value="{{ url }}" id="url"/>

        <div class="a-row">
            <label><font class="a-red">*</font> Описание ошибки</label>
            <textarea class="validate[required]" name="message" id="message"></textarea>
        </div>
        <div class="a-row">
            <label><font class="a-red">*</font> E-mail для обратной связи</label>
            <input value="{{ user_info.info.email }}" type="text" name="user_email" id="user_email"
                   class="validate[required, custom[email]]"/>
        </div>
        <div class="a-row">
            <label>Телефон для обратной связи</label>
            <input value="{{ user_info.info.contact_phones }}" type="text" name="user_phone" id="user_phone"/>
        </div>
        {% if user_info %}

        {% else %}
            <div class="a-row">
                <label>&nbsp;</label>

                <div class="qaptcha"></div>
            </div>
        {% endif %}
        <div class="a-row" style="line-height: 30px">
            <label>&nbsp;</label>
            NaviStom +38-044-573-97-73 пн-пт с 10-00 до 17-00
            <input class="a-btn-green a-float-right" type="submit" value="Отправить"/>
        </div>
    </form>

    </div>
{% endblock %}