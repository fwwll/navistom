{% if pages %}
<ul class="a-pagination">
	{% if prev > 1 %}
    <li>
        <a href="/{{no_page}}/page-{{prev}}">«</a>
    </li>
    {% endif %}
    
	{% for key, value in pages %}
    <li {% if value == current %}class="active"{%endif%}>
    	{% if value != current %}
        <a href="/{{no_page}}/page-{{value}}">{{value}}</a>
        {% else %}
        <span>{{value}}</span>
        {% endif %}
    </li>
    {% endfor %}
    
    {% if current < total %}
    <li>
        <a href="/{{no_page}}/page-{{next}}">»</a>
    </li>
    {% endif %}
</ul>
{% endif %}