{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if demand %}
    
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
            {% for d in demand %}
            <tr> 
            	<td>{{d.demand_id}}</td>
            	<td>
                	<a target="_blank" href="http://navistom.com/ua/demand#/demand/edit-{{d.demand_id}}">{{d.name}}</a>
                </td>
                <td>{{d.user_name}}</td>
                <td>{{d.date_add|rusDate}}</td>
                <td>{% if d.flag > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a target="_blank" href="http://navistom.com/ua/demand#/demand/edit-{{d.demand_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if d.flag_delete > 0 %}
                    	<a href="/admin/demand/reestablish-{{d.demand_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a href="/admin/demand/vip-{{d.demand_id}}"><i class="a-icon-star"></i></a>
                    	<a title="{{d.name}}" class="delete-link" href="/admin/demand/delete-{{d.demand_id}}"><i class="a-icon-trash"></i></a>
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
	<a href="/admin/diagnostic/add" class="a-btn-green">Добавить сервис</a>
{% endblock %}