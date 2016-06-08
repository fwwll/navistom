{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if services %}
    
    <table class="a-table datatables"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название</th> 
                <th>Добавил</th>
                <th>Добавлено</th>
                <th>Опубликовано</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="services"> 
            {% for s in services %}
            <tr> 
            	<td>{{s.service_id}}</td>
            	<td>
                	<a target="_blank" href="http://navistom.com/ua/services#/ua/service/edit-{{s.service_id}}">{{s.name}}</a>
                </td>
                <td>{{s.user_name}}</td>
                <td>{{s.date_add|rusDate}}</td>
                <td>{% if s.flag > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a href="/admin/service/edit-{{s.service_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if l.flag_delete > 0 %}
                    	<a href="/admin/service/reestablish-{{s.service_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{s.name}}" class="delete-link" href="/admin/service/delete-{{s.service_id}}"><i class="a-icon-trash"></i></a>
                    {% endif %}
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
	<a href="/admin/service/add" class="a-btn-green">Добавить сервис</a>
{% endblock %}