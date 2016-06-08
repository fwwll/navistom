{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if jobs %}
    
    <table class="a-table tablesorter sortable"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название</th> 
                <th>Дата добавления</th>
                <th>Дата изменения</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="labs/job"> 
            {% for j in jobs %}
            <tr id="categ-{{j.job_id}}">
            	<td>{{j.job_id}}</td> 
            	<td>
                	<a href="/admin/labs/job/edit-{{j.job_id}}">{{j.name}}</a>
                </td>
                <td>{{j.date_add|rusDate}}</td>
                <td>{{j.date_edit|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/labs/job/edit-{{j.job_id}}"><i class="a-icon-pencil"></i></a>
                    <a title="{{j.name}}" class="delete-link" href="/admin/labs/job/delete-{{j.job_id}}"><i class="a-icon-remove"></i></a>
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
	<a href="/admin/labs/job/add" class="a-btn-green">Добавить вид работы</a>
{% endblock %}