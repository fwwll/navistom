{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    
    <div class="user-profile a-clear">
    	<div class="user-profile-title a-clear">
        	<img class="user-profile-avatar" src="/uploads/users/avatars/tumb2/{{ user.avatar }}" />
        	<div class="user-profile-title-left a-clear">
            	<h3>{{user.name}}</h3>
                <div class="user-profile-email">{{user.email}}</div>
                <div class="user-profile-phone">Тел. &nbsp;{{user.phone}}</div>
            </div>
            <div class="user-profile-title-right">
            	<div class="user-profile-id"> {{user.user_id}}&nbsp; <i class="a-icon-user"></i></div>
                <div class="user-profile-country">{% if user.type == 0 %}пользователь{% else %}модератор{% endif %}</div>
                <div class="user-profile-geo">
                	IP&nbsp; {{user.ip_address}}<br />
                    {{user.geo_country_code}}&nbsp; {{user.geo_city}}
                </div>
            </div>
        </div>
        <hr class="separator" />
    </div>
    
    Написать пользователю:
    
    <form class="send-user-message a-clear" action="" method="post">
        <textarea name="message"></textarea>
        
        <input type="submit" class="a-btn-green a-float-right" value="Отправить" />
        <input type="reset" class="a-btn a-float-right a-margin-right" value="Отмена" />
    </form>
    
{% endblock %}