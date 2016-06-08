{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if producers_products %}
    
    <table class="a-table datatables"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Производитель</th> 
                <th>Название товара</th>
                <th>Дата добавления</th>
                <th>Дата изменения</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="products/producer"> 
            {% for p in producers_products %}
            <tr {% if p.flag_moder == 0%} style="background: #fffcd4" {% endif %} id="pr-{{p.product_id}}">
            	<td>{{p.product_id}}</td>
                <td>{{p.producer}}</td> 
            	<td>
                	<a href="/admin/products/producers_product/edit-{{p.product_id}}">{{p.name}}</a>
                </td>
                <td>{{p.date_add|rusDate}}</td>
                <td>{{p.date_edit|rusDate}}</td>
                <td class="ad-table-option">
                	<a href="/admin/products/producers_product/edit-{{p.product_id}}"><i class="a-icon-pencil"></i></a>
                    <a title="{{p.name}}" class="delete-link" href="/admin/products/producers_product/delete-{{p.product_id}}"><i class="a-icon-remove"></i></a>
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
	<a href="/admin/products/producers_product/add" class="a-btn-green">Добавить товар производителя</a>
{% endblock %}