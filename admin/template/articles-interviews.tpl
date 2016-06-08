{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if interviews %}
    
    <table class="a-table tablesorter">
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название</th> 
                <th>Статья</th> 
                <th>Дата добавления</th>
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="articles/tag"> 
            {% for i in interviews %}
            <tr id="tag-{{i.vote_id}}">
            	<td>{{i.vote_id}}</td> 
            	<td>
                	<a href="/admin/articles/interview/edit-{{i.vote_id}}">{{i.name}}</a>
                </td>
                <td>{{i.article_name}}</td>
                <td>{{i.date_add|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/articles/interview/edit-{{i.vote_id}}"><i class="a-icon-pencil"></i></a>
                    <a class="delete-link" href="/admin/articles/interview/delete-{{i.vote_id}}"><i class="a-icon-remove"></i></a>
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
	<a href="/admin/articles/interview/add" class="a-btn-green">Добавить опрос</a>
{% endblock %}