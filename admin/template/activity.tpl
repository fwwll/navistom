{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if activity %}
    
    <table class="a-table datatables"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название мероприятия</th> 
                <th>Добавил</th>
                <th>Добавлено</th>
                <th>Опубликовано</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="activity"> 
            {% for a in activity %}
            <tr {% if a.flag_moder == 0%} style="background: #fffcd4" {% endif %}> 
            	<td>{{a.activity_id}}</td>
            	<td>{{a.name}}</td>
                <td><a class="ajax-link" href="user/profile-{{a.user_id}}">{{a.user_name}}</a></td>
                <td>{{a.date_add|rusDate}}</td>
                <td>{% if a.flag_moder > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a target="_blank" href="http://navistom.com/ua/activity#/activity/edit-{{a.activity_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if a.flag_delete > 0 %}
                    	<a href="/admin/activity/reestablish-{{a.activity_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a href="/admin/activity/vip-{{a.activity_id}}"><i class="a-icon-star"></i></a>
                    	<a title="{{a.name}}" class="delete-link" href="/admin/activity/delete-{{a.activity_id}}"><i class="a-icon-trash"></i></a>
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
	<a href="/admin/activity/add" class="a-btn-green">Добавить статью</a>
{% endblock %}