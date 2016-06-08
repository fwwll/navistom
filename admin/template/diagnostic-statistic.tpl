{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>Топ 10 предложений за неделю</b>
        <span></span>
    </h1>
    
    <div class="stat-block">
    	<span>{{section_views}}</span>
        Количество просмотров раздела
    </div>
    
    <div class="stat-block">
    	<span>{{count}}</span>
        Количество материалов в разделе
    </div>
    
    <div class="stat-block">
    	<span>{{content_views}}</span>
        Общее количество просмотров материалов
    </div>
    
    {% if top_week %}
    
    <table class="a-table tablesorter"> 
        <thead> 
            <tr> 
                <th>Название материала</th> 
                <th>Просмотров</th>
                <th>Последний просмотр</th> 
            </tr> 
        </thead> 
        <tbody> 
            {% for t in top_week %}
            <tr>
            	<td>{{t.name}}</td> 
                <td>{{t.views}}</td>
                <td>{{t.last_view|rusDate}}</td>
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