{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    {% if articles %}
    
    <table class="a-table datatables"> 
        <thead> 
            <tr> 
            	<th>#</th>
                <th>Заголовок статьи</th> 
                <th>Добавил</th>
                <th>Дата публикации</th>
                <th>Опубликовано</th> 
                <th>Опции</th> 
            </tr> 
        </thead> 
        <tbody id="groups"> 
            {% for a in articles %}
            <tr {% if a.flag == 0%} style="background: #fffcd4" {% endif %}> 
            	<td>{{a.article_id}}</td>
            	<td>
                	<a target="_blank" href="http://navistom.com/ua/#/article/{{a.article_id}}-{{a.name|translit}}">{{a.name}}</a>
                </td>
                <td>{{a.user_name}} <br> {{a.email}}</td>
                <td>{{a.date_public|rusDate}}</td>
                <td>{% if a.flag > 0%} Да {% else %} Нет {% endif %}</td>
                <td class="ad-table-option">
                	<a href="/admin/article/edit-{{a.article_id}}"><i class="a-icon-pencil"></i></a>
                
                    {% if a.flag_delete > 0 %}
                    	<a href="/admin/article/reestablish-{{a.article_id}}"><i class="a-icon-ok"></i></a>
                    {% else %}
                    	<a title="{{a.name}}" class="delete-link" href="/admin/article/delete-{{a.article_id}}"><i class="a-icon-trash"></i></a>
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
	<a href="/admin/article/add" class="a-btn-green">Добавить статью</a>
{% endblock %}