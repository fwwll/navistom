{% extends ajax ? "index-ajax.tpl" : "index.tpl" %}

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
        <span>Заявка на VIP - размещение</span>
    </h1>

    <div class="vip-request">
        <div class="a-mess-green">Заявка на VIP - размещение была успешно отправлена. <br>
            В ближайшее время с Вами свяжется наш сотрудник для уточнения деталей.
        </div>
    </div>

    </div>
{% endblock %}