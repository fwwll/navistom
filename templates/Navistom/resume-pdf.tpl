<!doctype html>
<html>
<header>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</header>
<body>
<div style="background:#FFF; width:100%; height:100%; padding:50px;">
    <div style="width:220px; float:left">
        {% if resume.image != '' %}
            <img title="{{ resume.user_surname }} {{ resume.user_name }} {{ resume.user_firstname }}"
                 src="/uploads/images/work/160x200/{{ resume.image }}"/>
        {% elseif resume.avatar != '' %}
            <img src="/uploads/users/avatars/full/{{ resume.avatar }}"/>
        {% else %}

        {% endif %}
    </div>
    <div style="width:470px; float:right">
        <h1 style="line-height:10px">{{ resume.user_surname }} {{ resume.user_name }} {{ resume.user_firstname }}</h1>
        <span class="resume-user-descr">{{ resume.categs|join(', ') }}</span>
        <br><br>
        <table>
            <tr>
                <td style="color:#888;">
                    Дата рождения:
                </td>
                <td>
                    {{ resume.user_brith|rusDate }} &nbsp;<span
                            class="a-color-gray">({{ resume.years }} {{ resume.years|getNameYears }})</span>
                </td>
            </tr>
            <tr>
                <td style="color:#888">
                    Желаемый город работы:&nbsp;&nbsp;
                </td>
                <td>
                    {{ resume.city_name }}
                    {% if resume.leave_type > 0 %}
                        &nbsp;<span class="a-color-gray">(готов к переезду)</span>
                    {% endif %}
                </td>
            </tr>

            <tr>
                <td style="color:#888">
                    Занятость:
                </td>
                <td>
                    {% if resume.employment_type == 1 %}
                        полная занятость
                    {% elseif resume.employment_type == 2 %}
                        неполная занятость
                    {% elseif resume.employment_type == 3 %}
                        удаленная работа
                    {% else %}
                        посменно
                    {% endif %}
                </td>
            </tr>

            <tr>
                <td style="color:#888">
                    Зарплата:
                </td>
                <td>
                    {% if resume.price > 0 %}
                        <span class="price"><b>от {{ resume.price|number_format(0, '', ' ') }} {{ resume.currency_name }}</b></span>
                    {% else %}
                        не указана
                    {% endif %}
                </td>
            </tr>
        </table>
    </div>

    <div style="clear:both"></div>

    <dl class="resume-data-list a-clear">
        <dt>
            Контактная информация
        </dt>
        <dd>
            <dl class="resume-info-list">
                <dt>Телефон:</dt>
                <dd>{{ resume.phones|join("<br />")|raw }}</dd>
                <dt>Город проживания:</dt>
                <dd>{{ resume.user_city }}</dd>
            </dl>
            <!--<p>Телефон: <strong>{{ resume.contact_phones }}</strong><br /></p>
                <p>Город проживания:</p>-->
        </dd>
        {% if employment %}
            <dt>
                Опыт работы
            </dt>
            <dd>
                {% for e in employment %}
                    <p>
                        <b>{{ e.position }}</b>
                        {{ e.company_name }} &nbsp;<span class="a-color-gray">({{ e.activity }})</span> <br/>
                        c {{ e.date_start|rusDate }} по {{ e.date_end|rusDate }}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if education %}
            <dt>
                Образование
            </dt>
            <dd>
                {% for e in education %}
                    <p>
                        <b>
                            {% if e.type == 1 %}
                                Высшее
                            {% elseif e.type == 2 %}
                                Неоконченное высшее
                            {% elseif e.type == 3 %}
                                Среднее специальное
                            {% else %}
                                Среднее
                            {% endif %}
                        </b>
                        c {{ e.date_start|rusDate }} по {{ e.date_end|rusDate }} <br/>
                        {{ e.institution }},&nbsp; {{ e.faculty }},&nbsp; {{ e.location }}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if traning %}
            <dt>
                Дополнительное образование
            </dt>
            <dd>
                {% for t in traning %}
                    <p>
                        <b>{{ t.name }}</b>
                        {{ t.description }}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if langs %}
            <dt>
                Владение языками
            </dt>
            <dd>
                {% for l in langs %}
                    <p>
                        <b>{{ l.name }}</b>
                        Уровень:
                        {% if l.level == 1 %}
                            Начинающий
                        {% elseif l.level == 2 %}
                            Средний
                        {% else %}
                            Эксперт
                        {% endif %}
                    </p>
                {% endfor %}
            </dd>
        {% endif %}
        {% if resume.content %}
            <dt>
                Дополнительно
            </dt>
            <dd>
                <p>{{ resume.content|raw|nl2br }}</p>
            </dd>
        {% endif %}
    </dl>
</div>
</body>
</html>