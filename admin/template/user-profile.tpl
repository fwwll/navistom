{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

{% block title %}{{title}}{% endblock %}

{% block content %}
    
    <div class="user-profile a-clear">
    	<div class="user-profile-title a-clear">
        	<img class="user-profile-avatar" src="/uploads/users/avatars/tumb2/{{ user.avatar }}" />
        	<div class="user-profile-title-left a-clear">
            	<h3>{{user.name}}</h3>
                <div class="user-profile-email">{{user.email}}</div>
                <div class="user-profile-phone">Тел. &nbsp;{{user.contact_phones}}</div>
            </div>
            <div class="user-profile-title-right">
            	<div class="user-profile-id">{{user.user_id}}&nbsp; <i class="a-icon-user"></i></div>
                <div class="user-profile-country">{{user.group_name}}</div>
                <div class="user-profile-geo">
                	IP&nbsp; {{user.ip_address}}<br />
                    {{user.geo_country_code}}&nbsp; {{user.geo_city}}
                </div>
            </div>
        </div>
        <hr class="separator" />
    </div>

    <dl class="a-horizontal">
        <dt>Страна</dt>
        <dd>{{user.country_name}}</dd>
        <dt>Регистрация</dt>
        <dd><span class="moment">{{user.date_add}}</span> &nbsp;<i>({{user.date_add|rusDate}})</i></dd>
        <dt>Активность</dt>
        <dd><span class="moment">{{user.date_edit}}</span> &nbsp;<i>({{user.date_edit|rusDate}})</i></dd>
    </dl>
    <p></p>
    <a href="/admin/products/delete-user_id-{{user.user_id}}" class="a-btn-red">Удалить все товары пользователя</a>&nbsp;&nbsp;&nbsp;
    <a href="/admin/products/reorder-user_id-{{user.user_id}}" class="a-btn">Восстановить все товары пользователя</a>
    
{% endblock %}