{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if resume %}
    
    <table class="a-table datatables"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название</th> 
                <th>Пользователь</th>
                <th>Добавлено</th>
                <th>Опубликовано</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="products_new"> 
            {% for r in resume %}
            <tr> 
            	<td>{{r.work_id}}</td>
            	<td>
                	<a target="_blank" href="/work/resume#!/work/resume/edit-{{r.work_id}}">{{r.categs}}</a>
                </td>
                <td>{{r.user}}</td>
                <td>{{r.date_add|rusDate}}</td>
                <td>{% if r.flag > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a target="_blank" href="/work/resume#!/work/resume/edit-{{r.work_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if p.flag_delete > 0 %}
                    	<a href="/admin/work/reestablish-{{r.work_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{r.categs}}" class="delete-link" href="/admin/work/delete-{{r.work_id}}"><i class="a-icon-trash"></i></a>
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
	
{% endblock %}