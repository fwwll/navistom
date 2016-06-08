{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if journals %}
    
    <table class="a-table tablesorter sortable"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название</th> 
                <th>Дата добавления</th>
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody> 
            {% for j in journals %}
            <tr>
            	<td>{{j.journal_id}}</td> 
            	<td>№{{j.num}} {{j.year}}</td> 
                <td>{{j.date_add|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/journal/edit-{{j.journal_id}}"><i class="a-icon-pencil"></i></a>
                    <a class="delete-link" href="/admin/journal/delete-{{j.journal_id}}"><i class="a-icon-trash"></i></a>
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
	<a href="/admin/services/category/add" class="a-btn-green">Добавить рубрику</a>
{% endblock %}