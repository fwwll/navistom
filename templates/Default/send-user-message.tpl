{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    <div class="n-send-user-message">
    	<div class="a-clear n-user-info">
        	<div class="col-1">
                <img src="/uploads/users/avatars/tumb2/{{data.avatar}}" />
                <h3>{{data.name}}</h3>
                <div class="contact-phones">Тел. {{data.contact_phones|join(", ")}}</div>
            </div>
            <div class="col-2">
            	<h3>{{data.product_name}}</h3>
                <span>{{data.description}}</span>
                <div class="n-price"><b>{{data.price}} {{data.currency_name}}</b></div>
            </div>
        </div>
        
        <hr class="n-shadow-top">
        
        <div id="comment-list">
        {% for m in messages %}
        
         <div class="n-comment a-clear">
            <div class="col-1">
                <img src="/uploads/users/avatars/tumb2/{{m.avatar}}">
            </div>
            <div class="col-2">
                <div class="n-ad-top-info a-clear">
                    <div class="col-1">
                        <a href="#"><i class="a-icon-user a-icon-gray"></i> {{m.name}}</a>
                    </div>
                    <div class="col-2">
                        {{m.date_add|rusDate}}
                    </div>
                </div>
                {{m.message|nl2br}}
                <p></p>
                <div class="a-color-gray a-font-smal">Статус:&nbsp; {% if m.status > 0 %} прочитано {% else %} не прочитано {% endif %}</div>
            </div>
        </div>
        
        {% endfor %}
        </div>
        
       	Написать автору:
        
        <div class="a-clear">
            <form id="send-user-mess" method="post" action="/index.ajax.php?route=/{{controller}}/send-message-{{data.resource_id}}">
                <textarea name="message"></textarea>
                <input type="hidden" name="user_id" value="{{data.user_id}}" />
                <div class="form-loader display-none">
                    <i class="load"></i>
                    Загрузка...
                </div>
                <input type="submit" class="a-btn-green a-float-right" value="Отправить" />
            </form>
        </div>
    </div>
{% endblock %}