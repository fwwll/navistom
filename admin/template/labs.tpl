{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if labs %}
    
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
            {% for l in labs %}
            <tr {% if l.flag_moder == 0%} style="background: #fffcd4" {% endif %}> 
            	<td>{{l.lab_id}}</td>
            	<td>
                	<a target="_blank" href="http://navistom.com/ua/labs#/lab/edit-{{l.lab_id}}">{{l.categs|lower|capitalize}}</a>
                </td>
                <td>{{l.user_name}}</td>
                <td>{{l.date_add|rusDate}}</td>
                <td>{% if l.flag > 0 and l.flag_moder > 0 %} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a target="_blank" href="http://navistom.com/ua/labs#/lab/edit-{{l.lab_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if l.flag_delete > 0 %}
                    	<a href="/admin/lab/reestablish-{{l.lab_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{l.name}}" class="delete-link" href="/admin/lab/delete-{{l.lab_id}}"><i class="a-icon-trash"></i></a>
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
	<a href="/admin/lab/add" class="a-btn-green">Добавить услугу</a>
{% endblock %}