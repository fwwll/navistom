{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>Cтатистика раздела "Статьи"</b>
        <span>{{description}}</span>
    </h1>
    
    <div class="a-row a-offset-2 align-center">
        <div class="a-cols-4">
            <input data-fgColor="#ACD02F" class="chart" data-min="0" data-max="{{all_count}}" value="{{current_count}}" data-width="120" data-height="120" data-thickness=".2" />
        	<p><h5>Всего материалов</h5></p>
        </div>
        <div class="a-cols-4">
            <input data-fgColor="#ACD02F" class="chart" data-min="0" data-max="{{month_count}}" value="{{month_current}}" data-width="120" data-height="120" data-thickness=".2" />
        	<p><h5>Материалов за месяц</h5></p>
        </div>
        <div class="a-cols-4">
            <input data-fgColor="#ACD02F" class="chart" data-min="0" data-max="{{week_count}}" value="{{week_current}}" data-width="120" data-height="120" data-thickness=".2" />
        	<p><h5>Материалов за неделю</h5></p>
        </div>
        <div class="a-cols-4">
            <input data-fgColor="#EBB94D" class="chart" data-min="0" data-max="{{all_views}}" value="{{current_views}}" data-width="120" data-height="120" data-thickness=".2" />
        	<p><h5>Общая посещаемость раздела</h5></p>
        </div>
    </div>
    
    <h3 class="stat-title">Топ 10 популярных статей</h3>
    
    <table class="a-table"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Название статьи</th> 
                <th>Просмотров</th>
                <th>Период, дней</th>
            </tr> 
        </thead> 
        <tbody> 
            {% for p in popular_items %}
            <tr>
            	<td>{{p.article_id}}</td> 
            	<td>
                	<a target="_blank" href="/articles#!/article/{{p.article_id}}-{{p.name|translit}}">{{p.name}}</a>
                </td>
                <td>{{p.views}}</td>
                <td>{{p.period}}</td>
            </tr>
            {% endfor %}
        </tbody> 
    </table>
        
{% endblock %}

{% block right %}
	
{% endblock %}