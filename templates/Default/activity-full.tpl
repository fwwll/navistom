{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}
	{{meta.meta_title}}
{% endblock %}

{% block meta_description %}
	{{meta.meta_description}}
{% endblock %}

{% block meta_keys %}
	{{meta.meta_keys}}
{% endblock %}

{% block content %}
<div class="n-modal-top-info a-clear">
    <div class="col-1">
    	{% for key, value in activity.categs %}
        	<a href="/activity/categ-{{key}}-{{value|translit}}">{{value}}</a>&nbsp;&nbsp;
        {% endfor %}
    </div>
    <div class="col-2">
        {{ activity.date_add|rusDate }}&nbsp; | &nbsp;
        {{activity.views}} <i class="a-icon-eye-open a-icon-gray"></i>
    </div>
</div>
<h1>{{activity.name}}</h1>
<div class="n-ad-full a-clear">
    <hr />
    
    <div class="n-ad-full-info a-clear n-ed-full-info">
        <div class="col-1">
            <span class="navi-bg-green"><i class="a-icon-phone a-icon-white"></i></span> {{activity.phones.0}}
        </div>
        <div class="col-2">
            <span class="navi-bg-blue"><i class="a-icon-user a-icon-white"></i></span> {{activity.user_name}}
        </div>
        <div class="col-3">
            <div class="n-ad-full-price n-price">
            	{% if activity.flag_agreed > 0 %}
                    по согласованию
                {% else %}
                    {{activity.date_start|rusDate}}
                    
                    {% if activity.date_end != '0000-00-00'%}
                        - {{activity.date_end|rusDate}}
                    {% endif %}
                {% endif %}
            </div>
        </div>
    </div>
    
    <hr />
    
    <div class="col-1">
        <img src="/uploads/images/activity/160x200/{{activity.image}}" />
        
        <ul class="n-left-info-list">
        	<li>
            	<span class="navi-bg-blue"><i class="a-icon-globe a-icon-white"></i></span> г. {{activity.city_name}}
            </li>
            {% if activity.attachment %}
            <li>
            	<span class="navi-bg-blue"><i class="a-icon-file a-icon-white"></i></span> 
                <a target="_blank" href="http://navistom.com/uploads/docs/{{activity.attachment}}">Полная программа</a>
            </li>
            {% endif %}
            {% if activity.link %}
            <li>
            	<span class="navi-bg-blue"><i class="a-icon-link a-icon-white"></i></span> 
                <a target="_blank" href="{{activity.link}}">Ссылка на сайт мероприятия</a>
            </li>
            {% endif %}
        </ul>
        
    </div>
    <div class="col-2">
    	{% for l in lectors %}
        	<div class="n-lectors-list a-clear">
            	<div class="col-1">
                	<img src="/uploads/images/activity/lectors/{{l.image}}" />
                </div>
                <div class="col-2">
                	<b>{{l.name}}</b><br />
                    {{l.description|raw|nl2br}}
                </div>
            </div>
        {% endfor %}
    
        {{activity.content|raw|nl2br}}
    </div>
    
    <div class="a-clear"></div>
	
    <p><br /></p>
    
    {% if activity.video_link %}
    <iframe width="700" height="394" src="//www.youtube.com/embed/{{activity.video_link}}" frameborder="0" allowfullscreen></iframe>
    {% endif %}

    <p><br /></p>
    <div class="n-title">
        Написать автору
    </div>
    
    {% if user_info %}
    <div class="a-clear">
        <form id="send-user-mess" class="n-comment-add a-clear" method="post" action="/index.ajax.php?route=/activity/send-message-{{activity.activity_id}}">
            <textarea class="autosize" placeholder="Начните вводить текст..." name="message"></textarea>
            <input type="hidden" name="user_id" value="{{activity.user_id}}" />
            <div class="form-loader display-none">
                <i class="load"></i>
                Загрузка...
            </div>
            <div class="a-float-right">
                <input class="a-btn-green" type="submit" value="Отправить" />
            </div>
        </form>
    </div>
    {% else %}
    
    <div class="a-mess-yellow">
        Написать автору могут только зарегистрированные пользователи
    </div>
    
    {% endif %}
    
</div>

<div class="a-modal-footer a-clear">
    addThis
</div>
{% endblock %}