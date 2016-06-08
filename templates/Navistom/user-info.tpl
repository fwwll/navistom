<div class="n-tip-user-info a-clear">
    {% if data.avatar %}
        <img src="/uploads/users/avatars/tumb2/{{ data.avatar }}"/>
    {% else %}
        <img src="/uploads/users/avatars/tumb2/none.jpg"/>
    {% endif %}

    <div class="user-detalist">
        <h4>{{ data.name|raw }}</h4>

        <p>
            <i class="a-icon-map-marker a-icon-white"></i> г. {{ data.city_name }}
            , {{ data.country_name|default('Украина') }}
        </p>
        {% if data.site %}
            <i class="a-icon-link a-icon-white"></i> <a target="_blank" style="color:#fff" href="{{ data.site }}">Веб
            сайт</a>
        {% endif %}
    </div>
    <div class="a-clear"></div>
    <div class="phones">
        {% for p in data.phones %}
            {% if p != '' %}
                <i class="a-icon-phone a-icon-white"></i> {{ p }}<br/>
            {% endif %}
        {% endfor %}
    </div>
    <div class="icq">
        {% if data.icq != '' %}
            <div>
                ICQ: <b>{{ data.icq }}</b>
            </div>
        {% endif %}
        {% if data.skype != '' %}
            <div>
                Skype: <b>{{ data.skype }}</b>
            </div>
        {% endif %}
    </div>
</div>