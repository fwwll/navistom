{% extends "cabinet-index.tpl" %}

{% block tabs_menu %}
<ul class="cabinet-tabs-menu a-clear">
    <li class="active">
        <a href="/cabinet">Мой аккаунт</a>
    </li>
    <li>
        <a href="/cabinet/profile/edit">Редактировать профиль</a>
    </li>
    <li>
        <a href="/cabinet/profile/passw">Сменить пароль</a>
    </li>
</ul>
{% endblock %}

{% block cabinet_content %}
<div class="cabinet-user-info a-clear">
    <div class="a-clear">
        <img src="/uploads/users/avatars/tumb2/{{user.avatar}}" />
        <h4>{{user.name}}<span class="status">{{user.group_name}}</span></h4>
        
        <p></p>
        
        <span class="a-color-gray">
            <i class="a-icon-map-marker a-icon-gray"></i> г. {{user.city_name}}, {{user.country_name}}
        </span>
        <p></p>
        <a href="/cabinet/profile/edit" class="a-color-gray">
            <i class="a-icon-edit a-icon-gray"></i> Редактировать профиль
        </a>
    </div>
    <p>&nbsp;</p>
    <h3> Доступ к разделам</h3>
    <p>&nbsp;</p>
    <div class="a-clear">
        <table class="cabinet-table">
            <thead>
                <tr>
                    <th>Раздел</th>
                    <th>Разрешено</th>
                    <th>Добавлено</th>
                    <th>Осталось</th>
                </tr>
            </thead>
            <tbody>
                {% for p in permissions %}
                <tr>
                    <td>{{p.name}}</td>
                    <td>
                        {% if p.count > 0%}
                        {{p.count}}
                        {% elseif p.count == 0 and p.flag_add == 1 %}
                        неограничено
                        {% else %}
                        0
                        {% endif %}
                    </td>
                    <td>{{counts[p.section_id]}}</td>
                    <td>
                        {% if p.count > 0%}
                        {{p.count - counts[p.section_id]}}
                        {% elseif p.count == 0 and p.flag_add == 1 %}
                        неограничено
                        {% else %}
                        0
                        {% endif %}
                    </td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</div>
{% endblock %}