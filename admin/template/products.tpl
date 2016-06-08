{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if products %}
    
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
            {% for p in products %}
            <tr {% if p.flag_moder == 0%} style="background: #fffcd4" {% endif %}> 
            	<td>{{p.product_new_id}}</td>
            	<td>
                	<a target="_blank" href="/products#/product/edit-{{p.product_new_id}}">{{p.product_name}}</a>
                </td>
                <td>{{p.user_name}}</td>
                <td>{{p.date_add|rusDate}}</td>
                <td>{% if p.flag > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a href="/admin/product/edit-{{p.product_new_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if p.flag_delete > 0 %}
                    	<a href="/admin/product/reestablish-{{p.product_new_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{p.product_name}}" class="delete-link" href="/admin/product/delete-{{p.product_new_id}}"><i class="a-icon-trash"></i></a>
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