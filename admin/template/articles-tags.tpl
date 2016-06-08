{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if tags %}
    
    <table class="a-table tablesorter sortable"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название категории</th> 
                <th>Дата добавления</th>
                <th>Дата изменения</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="articles/tag"> 
            {% for t in tags %}
            <tr id="tag-{{t.tag_id}}">
            	<td>{{t.tag_id}}</td> 
            	<td>
                	<a href="/admin/articles/tag/edit-{{t.tag_id}}">{{t.name}}</a>
                </td>
                <td>{{t.date_add|rusDate}}</td>
                <td>{{t.date_edit|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/articles/tag/edit-{{t.tag_id}}"><i class="a-icon-pencil"></i></a>
                    <a title="{{t.name}}" class="delete-link" href="/admin/articles/tag/delete-{{t.tag_id}}"><i class="a-icon-remove"></i></a>
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
	<a href="/admin/articles/tag/add" class="a-btn-green">Добавить метку</a>
{% endblock %}