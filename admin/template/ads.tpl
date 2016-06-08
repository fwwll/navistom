{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if ads %}
    
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
            {% for a in ads %}
            <tr {% if a.flag_moder == 0%} style="background: #fffcd4" {% endif %}> 
            	<td>{{a.ads_id}}</td>
            	<td>
                	<a target="_blank" href="http://navistom.com/ua/ads#/ua/ads/edit-{{a.ads_id}}">{{a.product_name}}</a>
                </td>
                <td>{{a.user_name}}</td>
                <td>{{a.date_add|rusDate}}</td>
                <td>{% if a.flag > 0 and a.flag_moder > 0 %} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a target="_blank" href="http://navistom.com/ua/ads#/ua/ads/edit-{{a.ads_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if a.flag_delete > 0 %}
                    	<a href="/admin/ads/reestablish-{{a.ads_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{a.name}}" class="delete-link" href="/admin/ads/delete-{{a.ads_id}}"><i class="a-icon-trash"></i></a>
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
	<a href="/admin/ads/add" class="a-btn-green">Добавить товар</a>
{% endblock %}