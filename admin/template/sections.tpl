{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <h1 class="ad-title">
        <b>{{table.title}}</b>
        <span>{{table.description}}</span>
    </h1>
    
    {% if table.data %}
    
    <table class="a-table tablesorter sortable"> 
        <thead> 
            <tr> 
                <th>#</th> 
                <th>Название раздела на сайте</th> 
                <th>Имя модуля</th> 
                <th>Опубликовано</th> 
                <th>Дата добавления</th>
                <th>Дата изменения</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="section"> 
            {% for elem in table.data %}
            <tr id="section-{{elem.section_id}}"> 
                <td>{{elem.section_id}}</td> 
                <td>
                	<a href="/admin/section/edit-{{elem.section_id}}">{{elem.name}}</a>
                </td> 
                <td>{{elem.name_sys}}</td> 
                <td>
                	{% if elem.flag > 0 %}
                    	<i class="a-icon-ok"></i>
                    {% else %}
                    	<i class="a-icon-remove"></i>
                    {% endif %}
                </td> 
                <td>{{elem.date_add|rusDate}}</td> 
                <td>{{elem.date_edit|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/section/edit-{{elem.section_id}}"><i class="a-icon-pencil"></i></a>
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
	<a href="/admin/section/add" class="a-btn-green">Добавить раздел</a>
{% endblock %}