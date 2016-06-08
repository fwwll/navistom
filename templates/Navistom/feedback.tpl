{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

{% block content %}

    <div style="width:700px">

        <h1 class="n-form-title">
            <span>Обратная связь</span>

            <div class="n-title-desc"><font class="a-red">*</font> Поля, обязательные для заполнения</div>
        </h1>

        <form class="n-add-form validation" method="post" action="/index.ajax.php?route=/feedback_ajax">
            <div class="a-row">
                <label for="user_name"><font class="a-red">*</font> Ваше имя</label>
                <input value="{{ user_info.info.name }}" class="validate[required, minSize[3]]" type="text"
                       name="user_name" id="user_name"/>
            </div>
            <div class="a-row">
                <label for="user_email"><font class="a-red">*</font>E-mail адрес</label>
                <input value="{{ user_info.info.email }}" class="validate[required, custom[email]]" type="text"
                       name="user_email" id="user_email"/>
            </div>
            <div class="a-row">
                <label for="user_email">Телефон</label>
                <input value="{{ user_info.info.contact_phones }}" type="text" name="user_phone" id="user_phone"/>
            </div>
            <div class="a-row">
                <label for="user_passw"><font class="a-red">*</font> Текст сообщения</label>
                <textarea name="message" class="validate[required, minSize[6]]"></textarea>
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
                <span>NaviStom +38-044-573-97-73 пн-пт с 10-00 до 17-00</span>
                <input value="Отправить" type="submit" class="a-btn-green a-float-right"/>
            </div>
        </form>
    </div>

{% endblock %}
