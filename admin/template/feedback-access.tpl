{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    <ul class="ad-comments-list">
    {% for d in data %}
    	<li class="a-clear">
            <span class="comm-date-add"><i class="a-icon-calendar"></i> {{d.date_add|rusDate}}</span>
            <p>Заявка от:</p>
            <p>
                <b>{{ d.name }}</b><br>
                &nbsp;&nbsp;&nbsp;{{ d.contact_phones }}<br>
                &nbsp;&nbsp;&nbsp;{{d.email}}
            </p>
            Ссылка, с которой была отправлена заявка:
            <p>
                <a target="_blank" href="{{d.link}}">{{d.link}}</a>
            </p>
            Тип заявки:
            <p>
                {% if d.type == 2 %} Заявка на увеличение количества материалов {% elseif d.type == 1 %} Заявка на продление доступа {% else %} Заявка на предоставление доступа {% endif %}
            </p>
            <span class="comm-options">
            	<a class="delete-link" href="/admin/feedback/access/delete-{{d.user_id}}"><i class="a-icon-remove"></i></a>&nbsp;
            </span>
        </li>
    {% else %}
        <div class="a-mess-yellow">Пока нету ни одной заявки</div>
    {% endfor %}
    </ul>
    
{% endblock %}

{% block right %}
	
{% endblock %}