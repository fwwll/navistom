{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if realty %}
    
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
        <tbody id="realty"> 
            {% for r in realty %}
            <tr {% if r.flag_moder == 0%} style="background: #fffcd4" {% endif %}> 
            	<td>{{r.realty_id}}</td>
            	<td>
                	<a target="_blank" href="http://navistom.com/ua/realty#/realty/edit-{{r.realty_id}}">{{r.name}}</a>
                </td>
                <td>{{r.user_name}}</td>
                <td>{{r.date_add|rusDate}}</td>
                <td>{% if r.flag > 0 and r.flag_moder > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a target="_blank" href="http://navistom.com/ua/realty#/realty/edit-{{r.realty_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if l.flag_delete > 0 %}
                    	<a href="/admin/realty/reestablish-{{r.realty_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{r.name}}" class="delete-link" href="/admin/realty/delete-{{r.realty_id}}"><i class="a-icon-trash"></i></a>
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
	<a href="/admin/realty/add" class="a-btn-green">Добавить недвижимость</a>
{% endblock %}