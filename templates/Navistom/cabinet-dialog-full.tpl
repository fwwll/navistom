{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block content %}

    <div class="dialog-product-info a-clear">
        {% if data.info.image %}
            <img src="/uploads/images/{{ data.info.image }}"/>
        {% else %}
            <img src="/uploads/images/100x80.jpg"/>
        {% endif %}
        <div>{{ data.info.name }}</div>
        <span class="a-color-gray a-font-smal">{{ data.info.description }}</span>
    </div>

    <hr class="n-shadow-top"/>

    <div id="n-dialog-mess-list">
        {% for d in data.dialog %}
            <div class="n-dialog-full a-clear {% if d.from_id != route.values.from_id %} dialog-to{% endif %}">
                <img src="/uploads/users/avatars/tumb2/{{ d.avatar }}"/>

                <div class="n-dialog-mess">
                    <div class="n-dialog-user-info">
                        <i class="a-icon-user a-icon-gray"></i><span
                                class="a-color-gray a-font-smal"> {{ d.name }}</span>

                        <div class="a-float-right">
                            <i class="a-icon-calendar a-icon-gray"></i> <span
                                    class="a-color-gray a-font-smal">{{ d.date_add|rusDate }}</span>
                        </div>
                    </div>
                    {{ d.message }}
                </div>
            </div>
        {% endfor %}
    </div>

    <div class="n-dialog-send-mess a-clear">
        <form class="send-user-mess" method="post"
              action="/index.ajax.php?route=/cabinet/send-message-{{ route.values.from_id }}-{{ route.values.resource_id }}-{{ route.values.section_id }}">
            <textarea class="autosize" placeholder="Написатьт ответ..." name="message"></textarea>

            <div class="form-loader display-none">
                <i class="load"></i>
                Загрузка...
            </div>
            <input type="submit" class="a-btn-green a-float-right" value="Отправить">
        </form>
    </div>

{% endblock %}