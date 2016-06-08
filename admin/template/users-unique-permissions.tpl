{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <h1 class="ad-title">
        <b>{{table.title}}</b>
        <span>{{table.description}}</span>
    </h1>

    {% if table.data %}

        <table class="a-table datatables">
            <thead>
            <tr>
                <th>#</th>
                <th>Имя пользователя</th>
                <th>Контактные данные пользователя</th>
                <th>Заявка на доступ</th>
                <th>До окончания проплаты</th>
                <th>Опции</th>
            </tr>
            </thead>
            <tbody>
            {% for elem in table.data %}
                <tr>
                    <td>{{elem.user_id}}</td>
                    <td>
                        <a href="/admin/user/edit-{{elem.user_id}}">{{elem.name}}</a>
                    </td>
                    <td>
                        <dl class="a-list a-horizontal" style="margin-bottom: 0">
                            <dt style="width: 30%">E-mail:</dt>
                            <dd>{{ elem.email }}</dd>
                            <dt style="width: 30%">Телефон:</dt>
                            <dd>{{ elem.contact_phones }}</dd>
                        </dl>
                    </td>
                    <td>{% if elem.user_id|checkUserAccessRequest %} от {{ elem.user_id|checkUserAccessRequest|rusDate }} {% else %} Нету {% endif %}</td>
                    <td>
                        <dl class="a-list a-horizontal" style="margin-bottom: 0">
                            {% for item in elem.warnings %}
                                <dt style="width: 60%">{{ item.name }}</dt>
                                <dd>{{ item.diff|day }}</dd>
                            {% endfor %}
                        </dl>
                    </td>
                    <td class="ad-table-option">
                        <a target="_blank" title="Войти на сайт от этого пользователя" href="/admin/user/auth-{{elem.user_id}}"><i class="a-icon-check"></i></a>
                        <a href="/admin/user/edit-{{elem.user_id}}"><i class="a-icon-pencil"></i></a>
                        <a title="{{elem.name}}" class="delete-link" href="/admin/user/delete-{{elem.user_id}}"><i class="a-icon-remove"></i></a>
                    </td>
                </tr>
            {% endfor %}
            </tbody>
        </table>

    {% else %}
        <div class="a-mess-yellow">Нет контента для отображения</div>
    {% endif %}

{% endblock %}

{% block right %}
    <a href="/admin/user/add" class="a-btn-green">Добавить пользователя</a>
{% endblock %}