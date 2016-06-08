{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if categories %}
    
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
        <tbody id="labs/category"> 
            {% for c in categories %}
            <tr id="categ-{{c.categ_id}}">
            	<td>{{c.categ_id}}</td> 
            	<td>
                	<a href="/admin/labs/category/edit-{{c.categ_id}}">{{c.name}}</a>
                </td>
                <td>{{c.date_add|rusDate}}</td>
                <td>{{c.date_edit|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/labs/category/edit-{{c.categ_id}}"><i class="a-icon-pencil"></i></a>
                    <a title="{{c.name}}" class="delete-link" href="/admin/labs/category/delete-{{c.categ_id}}"><i class="a-icon-remove"></i></a>
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
	<a href="/admin/labs/category/add" class="a-btn-green">Добавить рубрику</a>
{% endblock %}