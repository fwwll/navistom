{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

{% block content %}

    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item all-categories-list">
    {% endif %}
    <!--h1 class="n-form-title">
        <span>Список всех рубрик и разделов {{ label }}</span>
    </h1-->

    <div class="a-row a-offset-2">
        <div class="a-cols-2">
            <ul class="categs-list">
                {% if (categs|length)%2 %}
                    {% set inc= (categs|length)-1 %}
                {% else %}
                    {% set inc= (categs|length) %}
                {% endif %}

                {% for i in range(0, inc / 2) %}
                    <li>
                        <a href="/{{ route.controller }}/categ-{{ categs[i].categ_id }}-{{ categs[i].name|translit }}"><b>{{ categs[i].name }}
                                ({{ categs[i].count }})</b></a>
                        <ul class="list sub-categs-list">
                            {% for c in sub_categs[categs[i].categ_id] %}
                                <li {% if c.count == 0 %} class="disabled" {% endif %}>
                                    <a {% if c.count == 0 %} href="javascript:void(0)" {% else %} href="/{{ route.controller }}/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}" {% endif %}>{{ c.name }}
                                        ({{ c.count }})</a>
                                </li>
                            {% endfor %}
                        </ul>
                    </li>
                {% endfor %}
            </ul>
        </div>
        <div class="a-cols-2">

            <ul class="categs-list">
                {% for i in range(  inc / 2 + 1, categs|length - 1) %}
                    <li>
                        <a href="/{{ route.controller }}/categ-{{ categs[i].categ_id }}-{{ categs[i].name|translit }}"><b>{{ categs[i].name }}
                                ({{ categs[i].count }})</b></a>
                        <ul class="list sub-categs-list">
                            {% for c in sub_categs[categs[i].categ_id] %}
                                <li {% if c.count == 0 %} class="disabled" {% endif %}>
                                    <a {% if c.count == 0 %} href="javascript:void(0)" {% else %} href="/{{ route.controller }}/sub_categ-{{ c.categ_id }}-{{ c.name|translit }}" {% endif %}>{{ c.name }}
                                        ({{ c.count }})</a>
                                </li>
                            {% endfor %}
                        </ul>
                    </li>
                {% endfor %}
            </ul>
        </div>
    </div>
    </div>

{% endblock %}