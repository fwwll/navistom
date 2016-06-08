{% extends ajax ? "index-ajax.tpl" : "index_new2.tpl" %}

{% block title %}
    {{ meta.meta_title }}
{% endblock %}

{% block meta_description %}
    {{ meta.meta_description }}
{% endblock %}

{% block meta_keys %}
    {{ meta.meta_keys }}
{% endblock %}

{% block content %}
    {% if ajax %}
        <div style="width:700px">
    {% else %}
        <div class="item">
    {% endif %}
    <h1 class="n-form-title">
        <span>Быстрый выбор</span>
    </h1>
    <p></p>
    <div class="a-row a-offset-3">
        <div class="a-cols-3">
            <p><strong>Рубрика:</strong></p>
            <ul id="selection-categs" class="list-as-select">
                {% for c in categs %}
                    <li>
                        <a href="{{ c.categ_id }}">{{ c.name_min }}</a>
                    </li>
                {% endfor %}
            </ul>
        </div>
        <div class="a-cols-3">
            <p><strong>Раздел:</strong></p>
            <ul id="selection-sub-categs" class="list-as-select">
                <li>
                    Выберите рубрику
                </li>
            </ul>
        </div>
        <div class="a-cols-3">
            <p><strong>Производитель:</strong></p>
            <ul id="selection-producers" class="list-as-select">
                <li>
                    Выберите раздел
                </li>
            </ul>
        </div>
    </div>

    </div>
{% endblock %}