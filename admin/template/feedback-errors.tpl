{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
	<h1 class="ad-title">
        <b>{{title}}</b>
        <span>{{description}}</span>
    </h1>
    
    <ul class="ad-comments-list">
    {% for d in data %}
    	<li {% if d.flag_view == 0%} style="background: #fffcd4" {%endif%}>
        	<a href="/admin/article/edit-{{c.article_id}}" class="comm-article-name">{{c.name}}</a>
            <span class="comm-date-add"><i class="a-icon-calendar"></i> {{d.date_add|rusDate}}</span>
        	<div class="comm-content">{{d.message|raw|nl2br}}</div>
            <dl class="a-list a-horizontal">
                <dt>Ссылка:</dt>
                <dd>
                	<a target="_blank" href="{{d.url}}">{{d.url}}</a>
                </dd>
                <dt>Браузер:</dt>
                <dd>
                	{{d.browser_name}} {{d.browser_ver}}
                </dd>
                <dt>ОС:</dt>
                <dd>
                	{{d.os_name}}
                </dd>
                <dt>IP адрес:</dt>
                <dd>
                	{{d.ip_address}}
                </dd>
                <dt>Телефон:</dt>
                <dd>
                	{{d.user_phone}}
                </dd>
            </dl>  
            <span class="comm-user"><i class="a-icon-user"></i> {{d.user_email}}</span>
            <span class="comm-options">
            	<a class="delete-link" href="/admin/feedback/error/delete-{{d.mess_id}}"><i class="a-icon-remove"></i></a>&nbsp;
                {% if d.flag_view == 0%}
                <a href="/admin/feedback/error/view-{{d.mess_id}}"> <i class="a-icon-ok"></i></a>
                {% endif %}
            </span>
            
            {% if d.answer_mess %}
            <div class="a-mess-yellow">
            	{{d.answer_date|rusDate}} <b>{{d.answer_user}}</b> ответил:
                <p>
                	{{d.answer_mess}}
                </p>
            </div>
            <p>&nbsp;</p>
            {% endif %}
            
            <div class="user-send-mess">
            <form method="post" class="ad-form a-clear validation" action="/admin/index.ajax.php?route=feedback/error/send-message-{{d.mess_id}}">
            	<div class="a-row">
                	<label>Ответить</label>
            		<textarea class="autosize" name="message"></textarea>
                </div>
                <input type="hidden" value="{{d.mess_id}}" name="mess_id" />
                <div class="a-row">
                	<label>&nbsp;</label>
            		<input type="submit" value="Отправить" class="a-btn-green" />
                </div>
            </form>
            </div>
        </li>
    {% endfor %}
    </ul>
    
{% endblock %}

{% block right %}
	
{% endblock %}