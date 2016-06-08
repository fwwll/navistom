{% block content %}

    <div style="width:100%; margin-top: 40px" class="a-mess-orange">
        {% if user_info.user_id|checkUserAccessRequest %}
            {% set dateAccessRequest = user_info.user_id|checkUserAccessRequest|rusDate %}
        {% endif %}

        {% if sectionId|getAccessLimit('add') %}
            <p>
            Доступ к размещению материалов в этом разделе платный.<br/>

            {% if dateAccessRequest %}
                <p>
                    <b>{{ dateAccessRequest }}</b> Вами была отправлена заявка на активацию доступа. <br/>
                    Администратор портала свяжется с Вами в ближайшее время. Телефон для обратной связи <b>+38-
                        044-573-97-73</b>
                </p>
            {% else %}
                Для активации доступа обратитесь к администратору портала по тел. +38- 044-573-97-73 или отправьте заявку.
            {% endif %}
            </p>
            {% if dateAccessRequest == null %}
                <a class="send-permission-request" href="javascript:Main.sendPermissionRequest(0)">Отправить заявку</a>
            {% endif %}
        {% elseif sectionId|getAccessLimit('date') and sectionId|getAccessLimit('dateDiff') <=0 %}
            <p>
            Период размещения объявлений в этом разделе закончился
            <b>{{ sectionId|getAccessLimit('dateDiff') * -1 }}</b> дней назад.<br/>

            {% if dateAccessRequest %}
                <p>
                    <b>{{ dateAccessRequest }}</b> Вами была отправлена заявка на активацию доступа. <br/>
                    Администратор портала свяжется с Вами в ближайшее время. Телефон для обратной связи <b>+38-
                        044-573-97-73</b>
                </p>
            {% else %}
                Для продления доступа обратитесь к администратору сайта по тел. +38- 044-573-97-73 или отправьте заявку
            {% endif %}
            </p>
            {% if dateAccessRequest == null %}
                <a class="send-permission-request" href="javascript:Main.sendPermissionRequest(1)">Отправить заявку</a>
            {% endif %}
        {% elseif sectionId|getAccessLimit('count') and sectionId|getAccessLimit('countDiff') <= 0 %}
            <p>
            Лимит объявлений в этом разделе исчерпан. <br/>

            {% if dateAccessRequest %}
                <p>
                    <b>{{ dateAccessRequest }}</b> Вами была отправлена заявка на активацию доступа. <br/>
                    Администратор портала свяжется с Вами в ближайшее время. Телефон для обратной связи <b>+38-
                        044-573-97-73</b>
                </p>
            {% else %}
                Для продления доступа обратитесь к администратору сайта по тел. +38- 044-573-97-73 или отправьте заявку
            {% endif %}
            </p>
            {% if dateAccessRequest == null %}
                <a class="send-permission-request" href="javascript:Main.sendPermissionRequest(2)">Отправить заявку</a>
            {% endif %}
        {% endif %}
    </div>

{% endblock %}