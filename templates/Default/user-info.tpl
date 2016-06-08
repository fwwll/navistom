<div class="n-tip-user-info a-clear">
	<img src="/uploads/users/avatars/tumb2/{{data.avatar}}" />
    
    <div class="user-detalist">
        <h4>{{data.name}}</h4>
        <p>
            <i class="a-icon-map-marker a-icon-white"></i> г. {{data.city_name}}, {{data.country_name}}
        </p>
    </div>
    <div class="a-clear"></div>
    <div class="phones">
    	{% for p in data.phones %}
        	{% if p != ''%}
        	<i class="a-icon-phone a-icon-white"></i> {{p}}<br />
            {% endif %}
        {% endfor %}
    </div>
    <div class="icq">
    	{% if data.icq != '' %}
    	<div>
        	ICQ: <b>{{data.icq}}</b>
        </div>
        {% endif %}
        {% if data.skype != '' %}
        <div>
        	Skype: <b>{{data.skype}}</b>
        </div>
        {%endif%}
    </div>
</div>