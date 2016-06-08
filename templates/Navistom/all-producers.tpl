{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

{% block content %}

    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}
    <!--h1 class="n-form-title">
        <span>Все производители {{ label }}</span>
    </h1-->
    <!--p>&nbsp;</p-->

    <div id="producers-all-list-nav"></div>

    <ul id="producers-all-list" class="producers-list">
        {% for p in producers %}
            <li>
                <a href="/{{ route.controller }}/firm-{{ p.producer_id }}-{{ p.name|translit }}">{{ p.name|raw }}
                    ({{ p.count }})</a>
            </li>
        {% endfor %}
    </ul>
    </div>

{% endblock %}