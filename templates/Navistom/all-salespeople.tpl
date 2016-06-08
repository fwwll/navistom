{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

{% block content %}

    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}
    <!--h1 class="n-form-title">
        <span>Все продавцы{{ label }}</span>
    </h1-->
    <!--p>&nbsp;</p-->

    <div id="producers-all-list-nav"></div>

    <ul id="producers-all-list" class="producers-list">
        {% for s in salespeople %}
            <li>
                <a href="/{{ route.controller }}/user-{{ s.user_id }}-{{ s.user_name|translit }}">{{ s.user_name|raw }}
                    ({{ s.count }})</a>
            </li>
        {% endfor %}
    </ul>
    </div>

{% endblock %}