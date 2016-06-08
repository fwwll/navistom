{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if groups %}
    
    <table class="a-table tablesorter sortable"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название группы</th> 
                <th>Дата добавления</th>
                <th>Дата изменения</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="groups"> 
            {% for elem in groups %}
            <tr id="group-{{elem.section_id}}"> 
            	<td>{{elem.group_id}}</td>
                <td>
                	<a href="/admin/users/group/edit-{{elem.group_id}}">{{elem.name}}</a>
                </td>  
                <td>{{elem.date_add|rusDate}}</td>
                <td>{{elem.date_edit|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/users/group/edit-{{elem.group_id}}"><i class="a-icon-pencil"></i></a>
                    <a title="Обновить данные доступа пользователей в этой группе" href="/admin/users/group/update-{{elem.group_id}}"><i class="a-icon-refresh a-icon-gray"></i></a>
                    <a class="delete-link" href="/admin/users/group/delete-{{elem.group_id}}"><i class="a-icon-remove"></i></a>
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
	<a href="/admin/users/group/add" class="a-btn-green">Добавить группу пользователей</a>
{% endblock %}