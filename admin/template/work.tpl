{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if work %}
    
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
        <tbody id="products_new"> 
            {% for w in work %}
            <tr> 
            	<td>{{w.work_id}}</td>
            	<td>
                	<a href="/admin/work/edit-{{w.work_id}}">{{w.name}}</a>
                </td>
                <td>{{w.user_name}}</td>
                <td>{{w.date_add|rusDate}}</td>
                <td>{% if w.flag > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a href="/admin/work/edit-{{w.work_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if p.flag_delete > 0 %}
                    	<a href="/admin/work/reestablish-{{w.work_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{w.name}}" class="delete-link" href="/admin/work/delete-{{w.work_id}}"><i class="a-icon-trash"></i></a>
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