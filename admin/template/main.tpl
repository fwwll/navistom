{% extends "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <h1 class="ad-title">
        <b>VIP - размещение</b>
        <span></span>
    </h1>

    <div class="section">
    <ul class="tabs a-clear">
        <li class="current">Украина &nbsp;&nbsp;
            <span class="{% if items.ua|length > 0 or count.ua > 0 %}a-count-red {% else %}a-count{% endif %}">{{items.ua|length + count.ua}}</span>
        </li>
        <li class="">Россия &nbsp;&nbsp;
            <span class="{% if items.ru|length > 0 or count.ru > 0 %}a-count-red {% else %}a-count{% endif %}">{{items.ru|length + count.ru}}</span>
        </li>
        <li class="">Беларусь &nbsp;&nbsp;
            <span class="{% if items.by|length > 0 or count.by > 0 %}a-count-red {% else %}a-count{% endif %}">{{items.by|length + count.by}}</span>
        </li>
    </ul>
    <div class="box visible">
       

        <!---table class="a-table tablesorter">
            <thead>
            <tr>
                <th>Объявление</th>
                <th>Пользователь</th>
                <th>Тип</th>
                <th>Отправлено</th>
                <th>Опции</th>
            </tr>
            </thead>
            <tbody id="section">
            {% for item in items.ua %}
                <tr>
                    <td>
                        {{item.section_name}}<br>
                        <a target="_blank" href="//navistom.com/{{item.url}}/{{item.resource_id}}-{{item.name|translit}}">{{item.name}}</a>
                    </td>
                    <td>
                        <b>{{item.user_name}}</b>
                        <br/>
                        {{item.user_phones|nl2br}}
                    </td>
                    <td>
                        {{item.type_name}}
                    </td>
                    <td>
                        {{item.date_add|rusDate}}
                    </td>
                    <td>
                        <a title="Удалить заявку" class="delete-link" href="/vip-request-delete-{{item.section_id}}-{{item.resource_id}}">
                            <i class="a-icon-trash"></i>
                        </a>
                    </td>
                </tr>
            {% else %}
                <td colspan="5">
                    Нет записей для отображения
                </td>
            {% endfor %}
            </tbody>
        </table-->

        <h3 class="stat-title">Объявления с ТОП размещением</h3>

        <table class="a-table tablesorter">
            <thead>
            <tr>
                <th>Объявление</th>
                <th>Пользователь</th>
                <th>Период</th>
            </tr>
            </thead>
            <tbody id="section">
            {% for vip_item in vipads.ua %}
                <tr {% if vip_item.days_left < 8 %} style="background:#FFE8E8" {% endif %}>
                    <td>
                        {{vip_item.section_name}}<br>
                        <a target="_blank" href="/{{vip_item.url}}/{{vip_item.resource_id}}-{{vip_item.name|translit}}">{{vip_item.name}}</a>
                    </td>
                    <td>
                        <b>{{vip_item.user_name}}</b>
                        <br/>
                        {{vip_item.user_phones|nl2br}}
                    </td>
                    <td>
                        осталось <b>{{vip_item.days_left}}</b>
                    </td>
                </tr>
            {% else %}
                <td colspan="5">
                    Нет записей для отображения
                </td>
            {% endfor %}
            </tbody>
        </table>

    </div>
    <div class="box">
        <h3 class="stat-title">Заявки на VIP размещение</h3>

        <table class="a-table tablesorter">
            <thead>
            <tr>
                <th>Объявление</th>
                <th>Пользователь</th>
                <th>Тип</th>
                <th>Отправлено</th>
                <th>Опции</th>
            </tr>
            </thead>
            <tbody id="section">
            {% for item in items.ru %}
                <tr>
                    <td>
                        {{item.section_name}}<br>
                        <a target="_blank" href="/{{item.url}}/{{item.resource_id}}-{{item.name|translit}}">{{item.name}}</a>
                    </td>
                    <td>
                        <b>{{item.user_name}}</b>
                        <br/>
                        {{item.user_phones|nl2br}}
                    </td>
                    <td>
                        {{item.type_name}}
                    </td>
                    <td>
                        {{item.date_add|rusDate}}
                    </td>
                    <td>
                        <a title="Удалить заявку" class="delete-link" href="/vip-request-delete-{{item.section_id}}-{{item.resource_id}}">
                            <i class="a-icon-trash"></i>
                        </a>
                    </td>
                </tr>
            {% else %}
                <td colspan="5">
                    Нет записей для отображения
                </td>
            {% endfor %}
            </tbody>
        </table>

        <h3 class="stat-title">Объявления с VIP размещением</h3>

        <table class="a-table tablesorter">
            <thead>
            <tr>
                <th>Объявление</th>
                <th>Пользователь</th>
                <th>Период</th>
            </tr>
            </thead>
            <tbody id="section">
            {% for vip_item in vipads.ru %}
                <tr {% if vip_item.days_left < 8 %} style="background:#FFE8E8" {% endif %}>
                    <td>
                        {{vip_item.section_name}}<br>
                        <a target="_blank" href="/{{vip_item.url}}/{{vip_item.resource_id}}-{{vip_item.name|translit}}">{{vip_item.name}}</a>
                    </td>
                    <td>
                        <b>{{vip_item.user_name}}</b>
                        <br/>
                        {{vip_item.user_phones|nl2br}}
                    </td>
                    <td>
                        осталось <b>{{vip_item.days_left}}</b>
                    </td>
                </tr>
            {% else %}
                <td colspan="5">
                    Нет записей для отображения
                </td>
            {% endfor %}
            </tbody>
        </table>

    </div>
    <div class="box">
        <h3 class="stat-title">Заявки на VIP размещение</h3>

        <table class="a-table tablesorter">
            <thead>
            <tr>
                <th>Объявление</th>
                <th>Пользователь</th>
                <th>Тип</th>
                <th>Отправлено</th>
                <th>Опции</th>
            </tr>
            </thead>
            <tbody id="section">
            {% for item in items.by %}
                <tr>
                    <td>
                        {{item.section_name}}<br>
                        <a target="_blank" href="/{{item.url}}/{{item.resource_id}}-{{item.name|translit}}">{{item.name}}</a>
                    </td>
                    <td>
                        <b>{{item.user_name}}</b>
                        <br/>
                        {{item.user_phones|nl2br}}
                    </td>
                    <td>
                        {{item.type_name}}
                    </td>
                    <td>
                        {{item.date_add|rusDate}}
                    </td>
                    <td>
                        <a title="Удалить заявку" class="delete-link" href="/vip-request-delete-{{item.section_id}}-{{item.resource_id}}">
                            <i class="a-icon-trash"></i>
                        </a>
                    </td>
                </tr>
            {% else %}
                <td colspan="5">
                    Нет записей для отображения
                </td>
            {% endfor %}
            </tbody>
        </table>

        <h3 class="stat-title">Объявления с VIP размещением</h3>

        <table class="a-table tablesorter">
            <thead>
            <tr>
                <th>Объявление</th>
                <th>Пользователь</th>
                <th>Период</th>
            </tr>
            </thead>
            <tbody id="section">
            {% for vip_item in vipads.by %}
                <tr {% if vip_item.days_left < 8 %} style="background:#FFE8E8" {% endif %}>
                    <td>
                        {{vip_item.section_name}}<br>
                        <a target="_blank" href="/{{vip_item.url}}/{{vip_item.resource_id}}-{{vip_item.name|translit}}">{{vip_item.name}}</a>
                    </td>
                    <td>
                        <b>{{vip_item.user_name}}</b>
                        <br/>
                        {{vip_item.user_phones|nl2br}}
                    </td>

                    <td>
                        осталось <b>{{vip_item.days_left}}</b>
                    </td>
                </tr>
            {% else %}
                <td colspan="5">
                    Нет записей для отображения
                </td>
            {% endfor %}
            </tbody>
        </table>

    </div>
    </div>
{% endblock %}

{% block right %}

{% endblock %}