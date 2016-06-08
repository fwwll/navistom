{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if vacancies %}
    
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
            {% for v in vacancies %}
            <tr> 
            	<td>{{v.vacancy_id}}</td>
            	<td>
                	<a target="_blank" href="/work/vacancy#!/work/vacancy/edit-{{v.vacancy_id}}">{{v.categs}}</a>
                </td>
                <td>{{v.user}}</td>
                <td>{{v.date_add|rusDate}}</td>
                <td>{% if v.flag > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a target="_blank" href="/work/vacancy#!/work/vacancy/edit-{{v.vacancy_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if v.flag_delete > 0 %}
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