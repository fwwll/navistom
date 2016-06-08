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
                <th>E-mail</th>
                <th>Группа</th>
                <th>Дата регистрации</th>
                <th>Опции</th>
            </tr>
            </thead>
            <tbody>
            {% for elem in table.data %}
                <tr {% if elem.flag_moder == 0 %} style="background: #fffcd4" {% endif %}>
                    <td>{{elem.user_id}}</td>
                    <td>
                        <a href="/admin/user/edit-{{elem.user_id}}">{{elem.name}}</a>
                    </td>
                    <td>
                        <a href="mailto:{{elem.email}}">{{elem.email}}</a>
                    </td>
                    <td>
                        {{elem.group_name}}
                    </td>
                    <td>{{elem.date_add|rusDate}}</td>
                    <td class="ad-table-option">
                        <a target="_blank" title="Войти на сайт от этого пользователя" href="/admin/user/auth-{{elem.user_id}}"><i class="a-icon-check"></i></a>
                        <a href="/admin/user/edit-{{elem.user_id}}"><i class="a-icon-pencil"></i></a>
                        <!--a title="{{elem.name}}" class="delete-link" href="/admin/user/delete-{{elem.user_id}}"><i class="a-icon-remove"></i></a-->
						 <a title="{{elem.name}}" class="delete-link" href="/remove-user-{{elem.user_id}}"><i class="a-icon-remove"></i></a>
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