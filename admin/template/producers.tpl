{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if producers %}
    
    <table class="a-table datatables"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название производителя</th> 
                <th>Дата добавления</th>
                <th>Дата изменения</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="products/producer"> 
            {% for p in producers %}
            <tr {% if p.flag_moder == 0%} style="background: #fffcd4" {% endif %} id="pr-{{p.producer_id}}">
            	<td>{{p.producer_id}}</td> 
            	<td>
                	<a href="/admin/products/producer/edit-{{p.producer_id}}">{{p.name}}</a>
                </td>
                <td>{{p.date_add|rusDate}}</td>
                <td>{{p.date_edit|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/products/producer/edit-{{p.producer_id}}"><i class="a-icon-pencil"></i></a>
                    <a title="{{p.name}}" class="delete-link" href="/admin/products/producer/delete-{{p.producer_id}}"><i class="a-icon-remove"></i></a>
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
	<a href="/admin/products/producer/add" class="a-btn-green">Добавить производителя</a>
{% endblock %}